<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = DB::select(" select * from categoria ");
        return view("vistas/categoria/indexCategoria", compact("categorias"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("vistas/categoria/registroCategoria");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "txtnombrecategoria" => "required",
        ]);

        //validar si existe el nombre de la categoria
        $existeCategoria = DB::select(" select count(*) as total from categoria where nombre=? ", [$request->txtnombrecategoria]);
        if ($existeCategoria[0]->total >= 1) {
            return redirect()->back()->with("INCORRECTO", "El nombre de la categoria ya existe");
        }

        //registrar categoria
        try {
            $res=DB::insert(" insert into categoria(nombre) values(?) ",[
                $request->txtnombrecategoria
            ]);
        } catch (\Throwable $th) {
            $res=0;
        }

        if ($res==1) {
            return redirect()->route("categoria.index")->with("CORRECTO", "Categoria registrada correctamente");
        } else {
            return redirect()->back()->with("INCORRECTO", "Error al registrar la categoria");
        }
        

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
        $request->validate([
            "txtnombrecategoria"=> "required",
        ]);

        //validar si existe el nombre de la categoria
        $existeCategoria=DB::select(" select count(*) as total from categoria where nombre=? and id_categoria!=? ",[
            $request->txtnombrecategoria,
            $id
        ]);

        if($existeCategoria[0]->total >= 1){
            return redirect()->back()->with("INCORRECTO", "El nombre de la categoria ya existe");
        }

        //actualizar categoria
        try {
            $res=DB::update(" update categoria set nombre=? where id_categoria=? ",[
                $request->txtnombrecategoria,
                $id
            ]);
            $res=1;
        } catch (\Throwable $th) {
            $res=0;
        }

        if ($res==1) {
            return redirect()->route("categoria.index")->with("CORRECTO", "Categoria actualizada correctamente");
        } else {
            return redirect()->route("categoria.index")->with("INCORRECTO", "Error al actualizar la categoria");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $res=DB::delete(" delete from categoria where id_categoria=? ", [$id]);
        } catch (\Throwable $th) {
            $res=0;
        }

        if ($res == 1) {
            return redirect()->route("categoria.index")->with("CORRECTO", "Categoria eliminada correctamente");
        } else {
            return redirect()->route("categoria.index")->with("INCORRECTO", "Error al eliminar la categoria");
        }
        
    }
}
