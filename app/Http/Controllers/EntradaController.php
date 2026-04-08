<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = DB::select(" SELECT
            entrada.*,
            DATE(entrada.fecha) as 'fechaEntrada',
            producto.nombre as 'nomProducto',
            proveedor.nombre as 'nomProveedor',
            proveedor.apellido
            FROM
            entrada
            INNER JOIN producto ON entrada.id_producto = producto.id_producto
            INNER JOIN proveedor ON entrada.id_proveedor = proveedor.id_proveedor
        ");

        $proveedor=DB::select("SELECT * FROM proveedor");
        $producto=DB::select("SELECT * FROM producto");
        
        return view("vistas/entradas.indexEntrada", compact("datos", "proveedor", "producto"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
