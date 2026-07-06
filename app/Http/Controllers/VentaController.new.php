<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function getClientes()
    {
        $clientes = DB::table('cliente')
            ->select('id_cliente as id', DB::raw('CONCAT(nombre, " ", COALESCE(apellido, "")) as name'))
            ->get();
        return response()->json($clientes);
    }

    public function buscarClientePorDni(Request $request)
    {
        try {
            $request->validate([
                'txtdni' => 'required|numeric'
            ]);

            $dni = $request->input('txtdni');

            $cliente = DB::table('cliente')
                ->where('dni', $dni)
                ->first(['id_cliente', 'nombre', 'apellido', 'dni']);

            if (!$cliente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cliente no encontrado'
                ], 404);
            }

            $name = trim(($cliente->nombre ?? '') . ' ' . ($cliente->apellido ?? ''));

            return response()->json([
                'success' => true,
                'id' => $cliente->id_cliente,
                'name' => $name,
                'dni' => $cliente->dni
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => 'Error DB: ' . $th->getMessage()
            ], 500);
        }
    }

    public function index()
    {
        $usuarios = DB::select("select * from usuario");
        $clientes = DB::select("select * from cliente");
        $productos = DB::select("select * from producto");
        $usuarios = DB::select("select * from usuario");

        $datos = DB::table("venta")
            ->join("cliente", "venta.id_cliente", "=", "cliente.id_cliente")
            ->select(
                "venta.*",
                DB::raw("' ' as foto"),
                "cliente.nombre as cliente",
                DB::raw("(SELECT COUNT(*) FROM venta_detalle vd WHERE vd.id_venta = venta.id_venta) as num_productos"),
                DB::raw("GROUP_CONCAT(DISTINCT producto.nombre SEPARATOR ', ') as productos_list")
            )
            ->leftJoin("venta_detalle", "venta.id_venta", "=", "venta_detalle.id_venta")
            ->leftJoin("producto", "venta_detalle.id_producto", "=", "producto.id_producto")
            ->groupBy("venta.id_venta", "cliente.nombre")
            ->paginate(10);

        return view("vistas/ventas/indexVenta", compact("datos", "usuarios", "clientes", "productos"));
    }

    public function create()
    {
        $clientes = DB::select("select * from cliente");
        $usuarios = DB::select("select * from usuario");

        // Última tasa vigente hacia moneda destino (id_moneda_destino = 2)
        $tasaCambiaria = DB::table('tasas_cambio')
            ->where('id_moneda_destino', 2)
            ->orderBy('fecha_vigencia', 'desc')
            ->value('valor_conversion');

        // Fallback por seguridad
        if (!$tasaCambiaria) {
            $tasaCambiaria = 1;
        }

        // Productos base en USD (precio) + precálculo en Bs para la vista/JS
        $productos = DB::table('producto')
            ->select('*')
            ->get()
            ->map(function ($p) use ($tasaCambiaria) {
                $p->precio_bs = (float) $p->precio * (float) $tasaCambiaria;
                $p->precio_compra_bs = (float) ($p->precio_compra ?? 0) * (float) $tasaCambiaria;

                // Aseguramos que el guardado/validación use Bs en lugar de USD si el formulario envía Bs
                $p->precio = $p->precio_bs;
                $p->precio_compra = $p->precio_compra_bs;

                return $p;
            });

        return view("vistas/ventas/registroVentas")
            ->with("clientes", $clientes)
            ->with("usuarios", $usuarios)
            ->with("productos", $productos)
            ->with("tasaCambiaria", $tasaCambiaria);
    }

    public function store(Request $request)
    {
        $request->validate([
            "txtcliente" => "required|exists:cliente,id_cliente",
            "txtfecha" => "required|date",
            "productos" => "required|array|min:1",
            "cantidades" => "required|array|min:1",
            "subtotales" => "required|array|min:1",
            "productos.*" => "required|exists:producto,id_producto",
            "cantidades.*" => "required|numeric|min:1",
            "subtotales.*" => "required|numeric|min:0",
        ]);

        // Fetch prices from DB and validate subtotals
        $num_items = count($request->productos);
        if (count($request->cantidades) != $num_items || count($request->subtotales) != $num_items) {
            return back()->with("INCORRECTO", "Arrays de productos inconsistentes");
        }

        // Aseguramos que la venta se procese estrictamente en Bs.
        $tasaCambiaria = DB::table('tasas_cambio')
            ->where('id_moneda_destino', 2)
            ->orderBy('fecha_vigencia', 'desc')
            ->value('valor_conversion');
        if (!$tasaCambiaria) {
            $tasaCambiaria = 1;
        }

        $grand_total = 0;
        $precios_unitarios = [];
        foreach ($request->productos as $i => $id_prod) {
            $prod = DB::table('producto')->where('id_producto', $id_prod)->first();
            if (!$prod) {
                return back()->with("INCORRECTO", "Producto inválido: " . $id_prod);
            }

            // precio en BD está en USD: convertimos a Bs para validar y guardar.
            $precio_usd = (float) $prod->precio;
            $precio_bs = $precio_usd * (float) $tasaCambiaria;

            $qty = (float) $request->cantidades[$i];
            $expected_sub = $precio_bs * $qty;
            $sub = (float) $request->subtotales[$i];

            if (abs($sub - $expected_sub) > 0.01) {
                return back()->with(
                    "INCORRECTO",
                    "Subtotal " . ($i + 1) . " no coincide con precio producto (" . number_format($precio_bs, 2, '.', '') . " Bs x " . $qty . ")"
                );
            }

            $precios_unitarios[$i] = $precio_bs;
            $grand_total += $sub;
        }

        $request->merge(['precios_unitarios' => $precios_unitarios]);
        $grand_total = 0;
        foreach ($request->subtotales as $i => $sub) {
            if ((float)$sub != (float)$request->cantidades[$i] * (float)$request->precios_unitarios[$i]) {
                return back()->with("INCORRECTO", "Subtotal " . ($i+1) . " no coincide");
            }
            $grand_total += (float)$sub;
        }

        $ultima = (int) DB::table('venta')->where('estado', 1)->count();
        $codigo_venta = str_pad($ultima + 1, 4, '0', STR_PAD_LEFT);

        // Persistimos también el equivalente en USD.
        $tasa_activa = DB::table('tasas_cambio')
            ->where('id_moneda_destino', 2)
            ->orderBy('fecha_vigencia', 'desc')
            ->value('valor_conversion');

        if (!$tasa_activa || (float)$tasa_activa <= 0) {
            $tasa_activa = $tasaCambiaria ?? 1;
        }

        $total_en_usd = ((float) $grand_total) / ((float) $tasa_activa);

        $id_venta = DB::table("venta")->insertGetId([
            "codigo_venta" => $codigo_venta,
            "id_cliente" => $request->txtcliente,
            "id_usuario" => auth()->user()->id_usuario ?? 1,
            "fecha" => \Carbon\Carbon::now()->toDateTimeString(),
            "total_usd" => $total_en_usd,
            "tasa_bcv_usada" => $tasa_activa,
            "total_bs" => $grand_total,
            "total" => $grand_total,
            "estado" => 1
        ]);

        foreach ($request->productos as $i => $id_prod) {
            $qty = $request->cantidades[$i];
            if ($qty > 0) {
                DB::table("venta_detalle")->insert([
                    "id_venta" => $id_venta,
                    "id_producto" => $id_prod,
                    "precio" => $request->precios_unitarios[$i],
                    "cantidad" => $qty,
                    "subtotal" => $request->subtotales[$i]
                ]);
                DB::table("producto")->where("id_producto", $id_prod)->decrement("stock", $qty);
            }
        }

        // Foto opcional
        if ($request->hasFile('txtfoto')) {
            $foto = $request->file("txtfoto");
            $nombreFoto = $id_venta . "-" . $foto->getClientOriginalName();
            $ruta = storage_path("app/public/FOTO-VENTAS/" . $nombreFoto);
            copy($foto, $ruta);
            DB::update("UPDATE venta SET foto=? WHERE id_venta=?", [$nombreFoto, $id_venta]);
        }

        return redirect()->route('ventas.index')->with("CORRECTO", "Venta con " . $num_items . " productos registrada correctamente");
    }

    public function show(string $id)
    {
        $venta = DB::table('venta')
            ->join('cliente', 'venta.id_cliente', '=', 'cliente.id_cliente')
            ->join('usuario', 'venta.id_usuario', '=', 'usuario.id_usuario')
            ->select('venta.*', 'cliente.nombre as cliente', 'usuario.nombre as cajero')
            ->where('venta.id_venta', $id)
            ->first();

        $detalles = DB::table('venta_detalle')
            ->join('producto', 'venta_detalle.id_producto', '=', 'producto.id_producto')
            ->select('venta_detalle.*', 'producto.nombre as producto_nombre', 'producto.precio_venta as precio_unitario')
            ->where('venta_detalle.id_venta', $id)
            ->get();

        $usuarios = DB::select("select * from usuario");
        $productos = DB::select("select * from producto");

        if (!$venta) {
            return back()->with("INCORRECTO", "La venta no existe");
        }

        return view("vistas/ventas/showVenta", compact("venta", "detalles", "usuarios", "productos"));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            "txtcliente" => "required|exists:cliente,id_cliente",
            "txtfecha" => "required|date",
            "productos" => "required|array|min:1",
            "cantidades" => "required|array|min:1",
            "subtotales" => "required|array|min:1",
            "productos.*" => "required|exists:producto,id_producto",
            "cantidades.*" => "required|numeric|min:1",
            "subtotales.*" => "required|numeric|min:0",
        ]);

        $num_items = count($request->productos);
        if (count($request->cantidades) != $num_items || count($request->subtotales) != $num_items) {
            return back()->with("INCORRECTO", "Arrays inconsistentes");
        }

        $grand_total = 0;
        $precios_unitarios = [];
        foreach ($request->productos as $i => $id_prod) {
            $prod = DB::table('producto')->where('id_producto', $id_prod)->first();
            if (!$prod) {
                return back()->with("INCORRECTO", "Producto inválido: " . $id_prod);
            }

            $precio = $prod->precio;
            $qty = (float) $request->cantidades[$i];
            $expected_sub = $precio * $qty;
            $sub = (float) $request->subtotales[$i];

            if (abs($sub - $expected_sub) > 0.01) {
                return back()->with("INCORRECTO", "Subtotal " . ($i+1) . " no coincide (S/. " . $precio . " x " . $qty . ")");
            }

            $precios_unitarios[$i] = $precio;
            $grand_total += $sub;
        }

        $request->merge(['precios_unitarios' => $precios_unitarios]);
        $grand_total = 0;
        foreach ($request->subtotales as $i => $sub) {
            if ((float)$sub != (float)$request->cantidades[$i] * (float)$request->precios_unitarios[$i]) {
                return back()->with("INCORRECTO", "Subtotal " . ($i+1) . " no coincide");
            }
            $grand_total += (float)$sub;
        }

        DB::table('venta_detalle')->where('id_venta', $id)->delete();

        DB::update("UPDATE venta SET id_cliente=?, fecha=?, total=? WHERE id_venta=?", [
            $request->txtcliente, $request->txtfecha, $grand_total, $id
        ]);

        foreach ($request->productos as $i => $id_prod) {
            $qty = $request->cantidades[$i];
            if ($qty > 0) {
                DB::table("venta_detalle")->insert([
                    "id_venta" => $id,
                    "id_producto" => $id_prod,
                    "precio" => $request->precios_unitarios[$i],
                    "cantidad" => $qty,
                    "subtotal" => $request->subtotales[$i]
                ]);
                DB::table("producto")->where("id_producto", $id_prod)->decrement("stock", $qty);
            }
        }

        return back()->with("CORRECTO", "Venta actualizada con " . $num_items . " productos");
    }

    public function destroy(string $id)
    {
        $verificar = DB::select("select count(*) as total from venta where id_venta=?", [$id]);
        if ($verificar[0]->total <= 0) {
            return back()->with("INCORRECTO", "La venta no existe");
        }

        $eliminar = DB::delete("delete from venta where id_venta=?", [$id]);

        return $eliminar
            ? redirect()->route("ventas.index")->with("CORRECTO", "Venta eliminada correctamente")
            : redirect()->route("ventas.index")->with("INCORRECTO", "Error al eliminar");
    }

    public function buscarVenta(Request $request)
    {
        $buscar = $request->buscar;
        if (!$buscar) {
            return response()->json(["success" => false, "dato" => []], 400);
        }

        $datos = DB::select(" SELECT venta.*, cliente.nombre as cliente, producto.nombre as cajero
            FROM venta
            INNER JOIN cliente ON venta.id_cliente = cliente.id_cliente
            INNER JOIN venta_detalle ON venta.id_venta = venta_detalle.id_venta
            INNER JOIN producto ON venta_detalle.id_producto = producto.id_producto
            WHERE cliente.nombre LIKE ? OR producto.nombre LIKE ? OR venta.id_venta LIKE ? LIMIT 5", ["%$buscar%", "%$buscar%", "%$buscar%"]);

        return response()->json(["success" => true, "dato" => $datos], 200);
    }

    public function registrarFotoVenta(Request $request)
    {
        $request->validate([
            "foto" => "required|mimes:png,jpg,jpeg",
            "txtid" => "required"
        ]);

        $id = $request->txtid;
        $foto = $request->file("foto");
        $nombreFoto = $id . "-" . $foto->getClientOriginalName();
        $ruta = storage_path("app/public/FOTO-VENTAS/" . $nombreFoto);
        copy($foto, $ruta);

        $actualizar = DB::update("UPDATE venta SET foto=? WHERE id_venta=?", [$nombreFoto, $id]);

        return $actualizar == 1
            ? back()->with("CORRECTO", "Foto actualizada correctamente")
            : back()->with("INCORRECTO", "Error al actualizar foto");
    }

    public function eliminarFotoVenta(Request $request)
    {
        $request->validate(["txtid" => "required"]);
        $id = $request->txtid;
        $venta = DB::select("SELECT foto FROM venta WHERE id_venta=?", [$id]);
        if (isset($venta[0]->foto) && $venta[0]->foto) {
            $ruta = storage_path("app/public/FOTO-VENTAS/" . $venta[0]->foto);
            unlink($ruta);
            DB::update("UPDATE venta SET foto='' WHERE id_venta=?", [$id]);
        }
        return back()->with("CORRECTO", "Foto eliminada correctamente");
    }

    public function reporte(Request $request)
    {
        $fecha_desde = $request->fecha_desde ?? date('Y-m-01');
        $fecha_hasta = $request->fecha_hasta ?? date('Y-m-d');

        $total_ventas = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->count();

        $total_ingresos = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->sum('total');

        $total_ingresos_usd = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->sum('total_usd');

        $ticket_promedio_usd = $total_ventas > 0 ? $total_ingresos_usd / $total_ventas : 0;

        $top_productos = DB::table('venta_detalle')
            ->join('producto', 'venta_detalle.id_producto', '=', 'producto.id_producto')
            ->join('venta', 'venta_detalle.id_venta', '=', 'venta.id_venta')
            ->whereBetween('venta.fecha', [$fecha_desde, $fecha_hasta])
            ->select('producto.nombre', DB::raw('SUM(venta_detalle.cantidad) as cantidad'), DB::raw('SUM(venta_detalle.subtotal) as ingresos'))
            ->groupBy('producto.id_producto', 'producto.nombre')
            ->orderByDesc('ingresos')
            ->limit(10)
            ->get();

        $fechas = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->groupBy(DB::raw('DATE(fecha)'))
            ->pluck(DB::raw('DATE(fecha) as fecha'))->toArray();

        $ingresos_fechas = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->groupBy(DB::raw('DATE(fecha)'))
            ->select(DB::raw('DATE(fecha) as fecha'), DB::raw('SUM(total) as ingresos'))
            ->pluck('ingresos', 'fecha')->toArray();

        return view('vistas/ventas/reporteVentas', compact(
            'total_ventas',
            'total_ingresos',
            'total_ingresos_usd',
            'ticket_promedio_usd',
            'top_productos',
            'fechas',
            'ingresos_fechas',
            'fecha_desde',
            'fecha_hasta'
        ));
    }

    public function storeCliente(Request $request)
    {
        try {
            $request->validate([
                'txtdni' => 'required|unique:cliente,dni',
                'txtnombre' => 'required',
                'txtapellido' => 'required',
                'txttelefono' => 'required',
                'txtdireccion' => 'required',
                'txtcorreo' => 'required|email|unique:cliente,correo'
            ]);

            $cliente = DB::table('cliente')->insertGetId([
                'dni' => $request->txtdni,
                'nombre' => $request->txtnombre,
                'apellido' => $request->txtapellido,
                'telefono' => $request->txttelefono,
                'direccion' => $request->txtdireccion,
                'correo' => $request->txtcorreo
            ]);

            return response()->json(['success' => true, 'id' => $cliente]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Error DB: ' . $th->getMessage()], 500);
        }
    }

    public function reportePDF(Request $request)
    {
        $fecha_desde = $request->fecha_desde ?? date('Y-m-01');
        $fecha_hasta = $request->fecha_hasta ?? date('Y-m-d');

        $total_ventas = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->count();

        $total_ingresos = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->sum('total');

        $total_ingresos_usd = DB::table('venta')
            ->whereBetween('fecha', [$fecha_desde, $fecha_hasta])
            ->sum('total_usd');

        $ticket_promedio_usd = $total_ventas > 0 ? $total_ingresos_usd / $total_ventas : 0;

        $top_productos = DB::table('venta_detalle')
            ->join('producto', 'venta_detalle.id_producto', '=', 'producto.id_producto')
            ->join('venta', 'venta_detalle.id_venta', '=', 'venta.id_venta')
            ->whereBetween('venta.fecha', [$fecha_desde, $fecha_hasta])
            ->select('producto.nombre', DB::raw('SUM(venta_detalle.cantidad) as cantidad'), DB::raw('SUM(venta_detalle.subtotal) as ingresos'))
            ->groupBy('producto.id_producto', 'producto.nombre')
            ->orderByDesc('ingresos')
            ->limit(10)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('vistas/ventas/reporteVentasPDF', compact(
            'total_ventas',
            'total_ingresos',
            'total_ingresos_usd',
            'ticket_promedio_usd',
            'top_productos',
            'fecha_desde',
            'fecha_hasta'
        ));

        $filename = 'reporte-ventas-' . $fecha_desde . '-al-' . $fecha_hasta . '.pdf';

        return $pdf->stream($filename);
    }
}

