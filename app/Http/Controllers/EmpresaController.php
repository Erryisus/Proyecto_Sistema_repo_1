<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
{
    public function index()
    {
        try {
            $sql = DB::select('select * from empresa');
        } catch (\Throwable $th) {
            //throw $th;
        }
        return view('vistas/empresa/empresa', compact("sql"));
    }
    public function update(Request $request, $id)
    {
        try {
            $sql = DB::update('update empresa set nombre=?, telefono=?, ubicacion=?, ruc=?, correo=? where id_empresa=?', [
                $request->nombre,
                $request->telefono,
                $request->ubicacion,
                $request->ruc,
                $request->correo,
                $id
            ]);
            if ($sql == 0) {
                $sql = 1;
            }
        } catch (\Throwable $th) {
            $sql = 0;
        }
        if ($sql == 1) {
            return back()->with('CORRECTO', 'Datos modificados correctamente');
        } else {
            return back()->with('INCORRECTO', 'Error al modificar');
        }
    }

    public function actualizarLogo(Request $request)
    {
        $request->validate([
            "foto" => "required|image|mimes:jpeg,png,jpg"
        ]);

        $file = $request->file("foto");
        $nombreArchivo = "logo" . "." . strtolower($file->getClientOriginalExtension());
        $ruta = storage_path("app/public/empresa/" . $nombreArchivo);

        $verificarLogo = DB::select('select foto from empresa');
        $verificarLogo = $verificarLogo[0]->foto;
        $nombreLogoAnterior = $verificarLogo;

        if ($nombreLogoAnterior != null) {
            $rutaAnterior = storage_path("app/public/empresa/" . $nombreLogoAnterior);
            try {
                unlink($rutaAnterior);
            } catch (\Throwable $th) {
            }
        }

// Create dir if not exists
        $dir = dirname($ruta);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $verificarLogo = DB::select('select foto from empresa');
        $nombreLogoAnterior = $verificarLogo[0]->foto ?? null;

        if ($nombreLogoAnterior) {
            $rutaAnterior = $dir . '/' . $nombreLogoAnterior;
            @unlink($rutaAnterior);
        }

        $res = $file->move($dir, $nombreArchivo);

        try {
            $actualizarCampo = DB::update("update empresa set foto=?", [$nombreArchivo]);
            if ($actualizarCampo == 0) {
                $actualizarCampo = 1;
            }
        } catch (\Throwable $th) {
            $actualizarCampo = 0;
        }

        if ($res && $actualizarCampo) {
            return back()->with('CORRECTO', 'Logo actualizado correctamente');
        } else {
            return back()->with('INCORRECTO', 'Error al actualizar el logo');
        }
    }

    public function eliminarLogo()
    {
        $consulta = DB::select('select foto from empresa');
        $nombreLogo = $consulta[0]->foto;
        $ruta = public_path("storage/empresa/$nombreLogo");

        try {
            $eliminar = unlink($ruta);
            $actualizarCampo = DB::update(" update empresa set foto='' ");
        } catch (\Throwable $th) {
            $eliminar = false;
            $actualizarCampo = false;
        }

        if ($eliminar && $actualizarCampo) {
            return back()->with("CORRECTO", "Logo eliminado correctamente");
        } else {
            return back()->with("INCORRECTO", "Error al eliminar el logo");
        }
    }
}
