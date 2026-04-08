<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datos = DB::select("SELECT usuario.*, tipo_usuario.tipo from usuario
        inner join tipo_usuario ON usuario.tipo_usuario=tipo_usuario.id_tipo");

        $tipos = DB::select("SELECT * FROM tipo_usuario");
        return view('vistas/usuario/indexUsuario', compact('datos', 'tipos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // consulta para obtener los tipos de usuario
        $tipos = DB::select("SELECT * FROM tipo_usuario");
        return view("vistas/usuario/registroUsuario", compact('tipos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "tipo_usuario" => "required|integer",
            "nombre" => "required",
            "apellido" => "required",
            "usuario" => "required|unique:usuario,usuario",
            "password" => "required",
            "correo" => "required|unique:usuario,correo",
            "foto" => "mimes:jpg,png,jpeg,gif|max:2048"
        ]);

        //registramos el usuario
        $id_registro = DB::table("usuario")->insertGetId([
            "tipo_usuario" => $request->tipo_usuario,
            "nombre" => $request->nombre,
            "apellido" => $request->apellido,
            "usuario" => $request->usuario,
            "password" => bcrypt($request->password),
            "telefono" => $request->telefono,
"direccion" => $request->direccion,
            "correo" => $request->correo,
            "estado" => 1
        ]);

        //registrar la foto
        try {
            $foto = $request->file("foto");
            $nombreFoto = "usuario_" . $id_registro . "." . $foto->getClientOriginalExtension();
            $ruta = storage_path("app/public/FOTOS-PERFIL-USUARIO/" . $nombreFoto);
            copy($foto, $ruta);
        } catch (\Throwable $th) {
            $nombreFoto = "";
        }

        //actualizar el registro con la foto
        $actualizar = DB::update("update usuario set foto=? where id_usuario=?", [$nombreFoto, $id_registro]);


        if ($id_registro >= 1 and $actualizar == 1) {
            return back()->with("CORRECTO", "Usuario registrado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al registrar el usuario");
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
            "txttipo" => "required|integer",
            "txtnombre" => "required",
            "txtapellido" => "required",
            "txtusuario" => "required|unique:usuario,usuario," . $id . ",id_usuario",
            "txtcorreo" => "required|unique:usuario,correo," . $id . ",id_usuario",
            "txttelefono"=>"required",
            "txtdireccion"=>"required"
        ]);


        try {
            $actualiar=DB::update("  update usuario set tipo_usuario=?, nombre=?, apellido=?, usuario=?, telefono=?, direccion=?, correo=? where id_usuario=?",
            [
                $request->txttipo,
                $request->txtnombre,
                $request->txtapellido,
                $request->txtusuario,
                $request->txttelefono,
                $request->txtdireccion,
                $request->txtcorreo,
                $id
            ]);
        } catch (\Throwable $th) {
            $actualiar=0;
        }

        if ($actualiar >= 0) {
            return back()->with("CORRECTO", "Datos de usuario actualizados correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar los datos del usuario");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $validar=DB::select(" select count(*) as total from usuario where id_usuario=?", [$id]);
        if ($validar[0]->total<=0) {
            return back()->with("INCORRECTO", "No se puede eliminar el usuario, no existe");
        }

        try {
            $eliminar=DB::delete(" delete from usuario where id_usuario=$id ");
        } catch (\Throwable $th) {
            $eliminar=0;
        }

        if ($eliminar==1) {
            return back()->with("CORRECTO", "Usuario eliminado correctamente");
        } else {
            return back()->with("INCORRECTO", "No se pudo eliminar el usuario");
        }

    }

    public function registrarFotoUsuario(Request $request)
    {
        $request->validate([
            "foto" => "required|mimes:jpg,png,jpeg,gif",
            "txtid" => "required|integer"
        ]);

        $id_usuario = $request->txtid;

        //registrar la foto
        try {
            $foto = $request->file("foto");
            $nombreFoto = "usuario_" . $id_usuario . "." . $foto->getClientOriginalExtension();
            $ruta = storage_path("app/public/FOTOS-PERFIL-USUARIO/" . $nombreFoto);
            copy($foto, $ruta);
        } catch (\Throwable $th) {
            $nombreFoto = "";
        }

        //actualizar el registro con la foto
        $actualizar = DB::update("update usuario set foto=? where id_usuario=?", [$nombreFoto, $id_usuario]);

        if ($nombreFoto != "" and $actualizar > 0) {
            return back()->with("CORRECTO", "Foto de usuario actualizada correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al actualizar la foto de usuario");
        }
    }

    public function eliminarUsuario(Request $request)
    {
        $request->validate([
            "txtid" => "required|integer"
        ]);

        $id_usuario = $request->txtid;
        $nombreFoto = DB::select("SELECT foto from usuario where id_usuario=?", [$id_usuario]);
        $rutaFoto = storage_path("app/public/FOTOS-PERFIL-USUARIO/" . $nombreFoto[0]->foto);

        try {
            $eliminar = unlink($rutaFoto);
            $actualizarCampo = DB::update("update usuario set foto='' where id_usuario=?", [$id_usuario]);
        } catch (\Throwable $th) {
            $eliminar = false;
            $actualizarCampo = false;
        }

        if ($eliminar == true and $actualizarCampo == true) {
            return back()->with("CORRECTO", "Foto de usuario eliminada correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar la foto de usuario");
        }
    }
}
