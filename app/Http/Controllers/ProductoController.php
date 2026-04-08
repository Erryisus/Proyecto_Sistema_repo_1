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
        // $datos=DB::select(" select producto.*,categoria.nombre as 'categoria' from producto
        //                     inner join categoria ON producto.id_categoria=categoria.id_categoria ");

        $categoria = DB::select("select * from categoria");
        $datos = DB::table("producto")
            ->join("categoria", "producto.id_categoria", "=", "categoria.id_categoria")
            ->select("producto.*", "categoria.nombre as categoria")
            ->paginate(10);
        return view("vistas/productos/indexProducto", compact("datos"))
            ->with("categoria", $categoria);
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
            "txtcodigoproducto" => "required",
            "txtnombreproducto" => "required",
            "txtprecioproducto" => "required|numeric",
            "txtstock" => "required|numeric",
            "txtfoto" => "mimes:png,jpg,jpeg"
        ]);


        //validar productos duplicados
        $producto = DB::select("select count(*) as total from producto where codigo=?", [$request->txtcodigoproducto]);
        if ($producto[0]->total > 0) {
            return back()->with("INCORRECTO", "El producto ya se encuentra registrado");
        }


        $registro = DB::table("producto")->insertGetId([
            "id_categoria" => $request->txtcategoria,
            "codigo" => $request->txtcodigoproducto,
            "nombre" => $request->txtnombreproducto,
            "precio" => $request->txtprecioproducto,
            "stock" => $request->txtstock,
            "descripcion" => $request->txtdescripcion,
            "estado" => "1"
        ]);



        try {
            $foto = $request->file("txtfoto");
            $nombreFoto = $registro . "-" . $foto->getClientOriginalName();
            $ruta = storage_path("app/public/FOTO-PRODUCTOS/" . $nombreFoto);
            copy($foto, $ruta);
        } catch (\Throwable $th) {
            $nombreFoto = "";
        }


        //actualizar la tabla producto en el campo foto
        try {
            $actualizar = DB::update("update producto set foto=? where id_producto=?", [
                $nombreFoto,
                $registro
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }



        if ($registro >= 1 and $actualizar == 1) {
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

    public function registrarFotoProducto(Request $request)
    {

        $request->validate([
            "foto" => "required|mimes:png,jpg,jpeg",
            "txtid" => "required"
        ]);
        $id = $request->txtid;

        try {
            $foto = $request->file("foto");
            $nombreFoto = $id . "-" . $foto->getClientOriginalName();
            $ruta = storage_path("app/public/FOTO-PRODUCTOS/" . $nombreFoto);
            copy($foto, $ruta);
        } catch (\Throwable $th) {
            $nombreFoto = "";
        }

        //actualizando la tabla producto
        try {
            $actualizar = DB::update("update producto set foto=? where id_producto=?", [$nombreFoto, $id]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        if ($actualizar == 1) {
            return back()->with("CORRECTO", "Foto actualizada correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar la foto");
        }
    }

    public function eliminarProducto(Request $request)
    {
        $request->validate([
            "txtid" => "required"
        ]);
        $id = $request->txtid;
        $nombreFoto = DB::select("select foto from producto where id_producto=?", [$id]);
        $ruta = storage_path("app/public/FOTO-PRODUCTOS/" . $nombreFoto[0]->foto);

        try {
            $eliminar = unlink($ruta);
            $actualizarCampo = DB::update("update producto set foto='' where id_producto=?", [$id]);
        } catch (\Throwable $th) {
            $eliminar = false;
            $actualizarCampo = false;
        }

        if ($eliminar == true and $actualizarCampo == true) {
            return back()->with("CORRECTO", "Foto eliminada correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar la foto");
        }
    }

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
