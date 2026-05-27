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
        $usuario = Auth::user();
        return view("vistas.perfil", compact("usuario"));
    }

    public function actualizarIMG(Request $request)
    {
        $request->validate([
            "foto" => "required|image|mimes:jpeg,png,jpg"
        ]);

        $usuario = Auth::user();
        $file = $request->file("foto");
        $nombreArchivo = $usuario->id_usuario . "." . strtolower($file->getClientOriginalExtension());
        $dir = storage_path("app/public/FOTOS-PERFIL-USUARIO");
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Delete old photo
        if ($usuario->foto) {
            $rutaFotoAnterior = $dir . '/' . $usuario->foto;
            @unlink($rutaFotoAnterior);
        }

        $res = $file->move($dir, $nombreArchivo);
        $usuario->foto = $nombreArchivo;
        $actualizarFoto = $usuario->save();

        if ($res && $actualizarFoto) {
            return back()->with("mensaje", "imagen actualizada correctamente");
        } else {
            return back()->with("error", "error al actualizar la imagen");
        }
    }

    public function eliminarFotoPerfil()
    {
        $usuario = Auth::user();
        if ($usuario->foto) {
            $ruta = storage_path("app/public/FOTOS-PERFIL-USUARIO/" . $usuario->foto);
            $res = @unlink($ruta);
            $usuario->foto = null;
            $actualizar = $usuario->save();
        } else {
            $res = true;
            $actualizar = true;
        }

        if ($res && $actualizar) {
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

        $usuario = Auth::user();
        $datos = $request->only(['nombre', 'apellido', 'usuario', 'telefono', 'direccion', 'correo']);
        $modificar = $usuario->update($datos);

        if ($modificar) {
            return back()->with("mensaje", "Datos actualizados correctamente");
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



    }
}
