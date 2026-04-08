<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function index()
    {
        $idUsuario = Auth::user()->id_usuario;
        $datos = DB::select("select * from usuario where id_usuario=$idUsuario");
        return view("vistas.perfil", compact("datos"));
    }

    public function actualizarIMG(Request $request)
    {
        $request->validate([
            "foto" => "required|image|mimes:jpeg,png,jpg"
        ]);

        $file = $request->file("foto");
        $idUsuario = Auth::user()->id_usuario;
        $nombreArchivo = $idUsuario . "." . strtolower($file->getClientOriginalExtension());
        // Create dir if not exists
        $dir = storage_path("app/public/FOTOS-PERFIL-USUARIO");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ruta = $dir . '/' . $nombreArchivo;

        $verificarFoto = DB::select(" select foto from usuario where id_usuario=$idUsuario  ");
        $nombreFotoAnterior = $verificarFoto[0]->foto ?? null;

        if ($nombreFotoAnterior) {
            $rutaFotoAnterior = $dir . '/' . $nombreFotoAnterior;
            @unlink($rutaFotoAnterior);
        }

        $res = $file->move($dir, $nombreArchivo);

        try {
            $actualizarFoto = DB::update("update usuario set foto='$nombreArchivo' where id_usuario=$idUsuario");
            if ($actualizarFoto == 0) {
                $actualizarFoto = 1;
            }
        } catch (\Throwable $th) {
            $actualizarFoto = 0;
        }

        if ($res and $actualizarFoto) {
            return back()->with("mensaje", "imagen actualizada correctamente");
        } else {
            return back()->with("error", "error al actualizar la imagen");
        }
    }

    public function eliminarFotoPerfil()
    {
        $idUsuario = Auth::user()->id_usuario;
        $nombreFoto = Auth::user()->foto;
        $ruta = storage_path("app/public/FOTOS-PERFIL-USUARIO/$nombreFoto");

        try {
            $res = unlink($ruta);
            $actualizarCampoFoto = DB::update("update usuario set foto='' where id_usuario=$idUsuario ");
        } catch (\Throwable $th) {
            $res = false;
            $actualizarCampoFoto = false;
        }

        if ($res and $actualizarCampoFoto) {
            return back()->with("mensaje", "Imagen eliminada correctamente");
        } else {
            return back()->with("error", "Error al eliminar la imagen");
        }
    }

    public function actualizarDatos(Request $request)
    {
        $request->validate([
            "nombre" => "required",
            "apellido" => "required",
            "correo" => "required|email",
            "usuario" => "required",
        ]);

        $idUsuario = Auth::user()->id_usuario;

        try {
            $modificar = DB::update(" update usuario set nombre=?, apellido=?, usuario=?, telefono=?, direccion=?, correo=? where id_usuario=$idUsuario ",[
                $request->nombre,
                $request->apellido,
                $request->usuario,
                $request->telefono,
                $request->direccion,
                $request->correo,
            ]);
            $modificar = true;
        } catch (\Throwable $th) {
            $modificar = false;
        }

        if ($modificar) {
            return back()->with("mensaje", "Datos actualizados correctamento");
        } else {
            return back()->with("error", "Error al modificar los datos");
        }
    }

    public function cambiarClave(){
        return view("vistas/cambiarClave");
    }

    public function actualizarClave(Request $request){
        $request->validate([
            "claveActual"=>"required",
            "claveNueva"=>"required",
        ]);

        $claveActual = ($request->claveActual);
        $claveNueva = $request->claveNueva;

        $idUsuario= Auth::user()->id_usuario;
        $verificarClave=DB::select(" select password from usuario where id_usuario=$idUsuario ");
        $verificarClave=$verificarClave[0]->password;

        if(Hash::check($claveActual,$verificarClave)){

            $claveNueva=Hash::make($claveNueva);

            try {
                $actualizar=DB::update(" update usuario set password=? where id_usuario=$idUsuario ",[
                    $claveNueva
                ]);
                $actualizar=true;
            } catch (\Throwable $th) {
                $actualizar=false;
            }

            if ($actualizar) {
                return back()->with("CORRECTO", "Clave actualizada correctamente");
            } else {
                return back()->with("INCORRECTO", "Error al actualizar la clave");
            }


        }else{
            return back()->with("INCORRECTO", "La clave actual no es correcta");
        }


    }
}
