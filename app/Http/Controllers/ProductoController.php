<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Última tasa vigente hacia moneda destino (id_moneda_destino = 2)
        // Usamos el valor_conversion como factor de conversión.
        $tasaCambiaria = DB::table('tasas_cambio')
            ->where('id_moneda_destino', 2)
            ->orderBy('fecha_vigencia', 'desc')
            ->value('valor_conversion');

        // Fallback por seguridad (evita división/NaN en JS)
        if (!$tasaCambiaria) {
            $tasaCambiaria = 1;
        }

        $categoria = DB::select("select * from categoria");

        $datos = DB::table("producto")
            ->join("categoria", "producto.id_categoria", "=", "categoria.id_categoria")
            ->select(
                "producto.*",
                "categoria.nombre as categoria"
            )
            ->paginate(10);

        return view('vistas/productos/indexProducto', compact('datos', 'categoria', 'tasaCambiaria'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categoria = DB::select("select * from categoria");
        return view("vistas/productos/registroProductos")->with("categoria", $categoria);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "txtcategoria" => "required",
            "txtnombreproducto" => "required",
            "txtprecioproducto" => "required|numeric",
            "txtpreciocompra" => "required|numeric|min:0",
            "txtstock" => "required|numeric|min:0",
            "txtstockminimo" => "required|integer|min:0",
            "txtstockmaximo" => "required|integer|min:0|gte:txtstockminimo",
            "txtunidadmedida" => "required|in:Gramos,Mililitros,Unidades,Cajas"
        ]);



        // Generar código automáticamente: PREFIJO (3 letras) + '-' + correlativo con ceros

        $categoriaId = (int) $request->txtcategoria;
        $categoria = DB::table('categoria')->where('id_categoria', $categoriaId)->first();

        // Regla de prefijos (según nombre de categoría)
        $nombreCategoria = strtolower(trim($categoria->nombre ?? ''));
        $prefix = 'PRO';
        if (str_contains($nombreCategoria, 'café') || str_contains($nombreCategoria, 'cafe')) {
            $prefix = 'CAF';
        } elseif (str_contains($nombreCategoria, 'bebida') || str_contains($nombreCategoria, 'beb')) {
            $prefix = 'BEB';
        } elseif (str_contains($nombreCategoria, 'alimento') || str_contains($nombreCategoria, 'alm')) {
            $prefix = 'ALM';
        }

        $ultimoCodigo = DB::table('producto')
            ->where('codigo', 'like', $prefix . '-%')
            ->orderByDesc('id_producto')
            ->value('codigo');

        $correlativo = 1;
        if ($ultimoCodigo) {
            $parteNumero = preg_replace('/^' . preg_quote($prefix . '-', '/') . '/','', $ultimoCodigo);
            $parteNumero = preg_replace('/[^0-9]/','', $parteNumero);
            if (is_numeric($parteNumero) && (int)$parteNumero >= 1) {
                $correlativo = (int)$parteNumero + 1;
            }
        }

        $nuevoCodigo = $prefix . '-' . str_pad((string)$correlativo, 3, '0', STR_PAD_LEFT);

        // Asegurar unicidad (por si existe una duplicidad inesperada)
        $existe = DB::table('producto')->where('codigo', $nuevoCodigo)->exists();
        while ($existe) {
            $correlativo++;
            $nuevoCodigo = $prefix . '-' . str_pad((string)$correlativo, 3, '0', STR_PAD_LEFT);
            $existe = DB::table('producto')->where('codigo', $nuevoCodigo)->exists();
        }


        $registro = DB::table("producto")->insertGetId([
            "id_categoria" => $request->txtcategoria,
            "codigo" => $nuevoCodigo,

            "nombre" => $request->txtnombreproducto,
            "precio" => $request->txtprecioproducto,
            "precio_compra" => $request->txtpreciocompra,
            "stock" => $request->txtstock,
            "stock_minimo" => $request->txtstockminimo,
            "stock_maximo" => $request->txtstockmaximo,
            "unidad_medida" => $request->txtunidadmedida,
            "fecha_vencimiento" => now()->toDateString(),
            "descripcion" => $request->txtdescripcion,
            "estado" => "1"
        ]);




        // No se procesa foto (funcionalidad eliminada)

        if ($registro >= 1) {
            return back()->with("CORRECTO", "Producto registrado correctamente");
        } else {
            return back()->with("ERROR", "Error al registrar el producto");
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $datos=DB::select(" SELECT
            producto.*,
            categoria.nombre as categoria
            FROM
            producto
            INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria
            where id_producto=?  ", [$id]);

        $categoria = DB::select("select * from categoria");

        if (count($datos) <= 0) {
            return back()->with("INCORRECTO", "El producto no existe");
        }

        return view("vistas/productos/showProducto", compact("datos", "categoria"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            "txtcategoria" => "required",
            "txtcodigoproducto" => "required",
            "txtnombreproducto" => "required",
            "txtprecioproducto" => "required|numeric",
            "txtstock" => "required|numeric",
        ]);

        $duplicidadCodigo = DB::select(
            " select count(*) as total from producto where codigo=? and id_producto<>? ",
            [$request->txtcodigoproducto, $id]
        );

        if ($duplicidadCodigo[0]->total > 0) {
            return back()->with("INCORRECTO", "El codigo ya se encuentra registrado");
        }

        $actualizar = DB::update(
            " update producto set id_categoria=?, codigo=?, nombre=?, precio=?, stock=?, descripcion=? where id_producto=? ",
            [
                $request->txtcategoria,
                $request->txtcodigoproducto,
                $request->txtnombreproducto,
                $request->txtprecioproducto,
                $request->txtstock,
                $request->txtdescripcion,
                $id
            ]
        );

        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Producto actualizado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar el producto");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $verificar = DB::select(" select count(*) as total from producto where codigo=?", [$id]);
        if ($verificar[0]->total <= 0) {
            return back()->with("INCORRECTO", "El producto no existe");
        }

        try {
            $eliminar=DB::delete(" delete from producto where codigo=?", [$id]);
        } catch (\Throwable $th) {
            $eliminar = false;
        }

        if ($eliminar == true) {
            return redirect()->route("productos.index")->with("CORRECTO", "Producto eliminado correctamente");
        } else {
            return redirect()->route("productos.index")->with("INCORRECTO", "Error al eliminar el producto");
        }


    }

    public function buscarProducto(Request $request)
    {
        $id = $request->buscar;
        if ($id == null) {
            return response()->json([
                "success" => false,
                "dato" => []
            ], 400);
        }

        $datos = DB::select(" SELECT
        producto.*,
        categoria.nombre as cate
        FROM
        producto
        INNER JOIN categoria ON producto.id_categoria = categoria.id_categoria where codigo like '%$id%' or producto.nombre like '%$id%' limit 5
        ");

        return response()->json([
            "success" => true,
            "dato" => $datos
        ], 200);
    }

    // Funcionalidad foto eliminada completamente.
    // (Métodos intencionalmente deshabilitados para que el sistema no intente usar la columna `foto`.)


    public function reporte(Request $request)
    {
        $bajo_stock = $request->bajo_stock ?? 10;

        $total_productos = DB::table('producto')->where('estado', 1)->count();
        $bajo_stock_count = DB::table('producto')->where('estado', 1)->where('stock', '<', $bajo_stock)->count();
        $total_value = DB::table('producto')->where('estado', 1)->sum(DB::raw('precio * stock'));
        $avg_price = $total_productos > 0 ? DB::table('producto')->where('estado', 1)->avg('precio') : 0;

        $top_productos = DB::table('producto')
            ->leftJoin('categoria', 'producto.id_categoria', '=', 'categoria.id_categoria')
            ->where('producto.estado', 1)
            ->select('producto.*', 'categoria.nombre as categoria', DB::raw('precio * stock as valor_total'))
            ->orderByDesc('valor_total')
            ->limit(10)
            ->get();

        $productos_categoria = DB::table('producto')
            ->leftJoin('categoria', 'producto.id_categoria', '=', 'categoria.id_categoria')
            ->where('producto.estado', 1)
            ->select('categoria.nombre', DB::raw('COUNT(producto.id_producto) as total'), DB::raw('SUM(producto.precio * producto.stock) as valor_total'))
            ->groupBy('categoria.id_categoria', 'categoria.nombre')
            ->get();

        return view('vistas.productos.reporteProductos', compact(
            'total_productos',
            'bajo_stock_count',
            'total_value',
            'avg_price',
            'top_productos',
            'productos_categoria',
            'bajo_stock'
        ));
    }
}
