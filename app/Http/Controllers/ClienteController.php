<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = DB::table('cliente')->orderBy('id_cliente', 'desc')->paginate(10);
        return view('vistas/clientes/index', compact('clientes'));
    }

    public function create()
    {
        return view('vistas/clientes/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dni' => 'required|unique:cliente,dni',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|email|unique:cliente,correo|max:100',
        ]);

        DB::table('cliente')->insert([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'correo' => $request->correo,
        ]);

        return redirect()->route('clientes.index')->with('CORRECTO', 'Cliente registrado correctamente');
    }

    public function edit($id)
    {
        $cliente = DB::table('cliente')->where('id_cliente', $id)->first();
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('INCORRECTO', 'Cliente no encontrado');
        }
        return view('vistas/clientes/edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = DB::table('cliente')->where('id_cliente', $id)->first();
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('INCORRECTO', 'Cliente no encontrado');
        }

        $request->validate([
            'dni' => 'required|unique:cliente,dni,' . $id . ',id_cliente',
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'telefono' => 'required|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'correo' => 'nullable|email|unique:cliente,correo,' . $id . ',id_cliente|max:100',
        ]);

        DB::table('cliente')->where('id_cliente', $id)->update([
            'dni' => $request->dni,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'correo' => $request->correo,
        ]);

        return redirect()->route('clientes.index')->with('CORRECTO', 'Cliente actualizado correctamente');
    }

    public function destroy($id)
    {
        try {
            $cliente = DB::table('cliente')->where('id_cliente', $id)->first();
            if (!$cliente) {
                return redirect()->route('clientes.index')->with('INCORRECTO', 'Cliente no encontrado');
            }

            $used = DB::table('venta')->where('id_cliente', $id)->count();
            if ($used > 0) {
                return redirect()->route('clientes.index')->with('INCORRECTO', 'No se puede eliminar. Cliente usado en ' . $used . ' venta(s).');
            }

            DB::table('cliente')->where('id_cliente', $id)->delete();
            return redirect()->route('clientes.index')->with('CORRECTO', 'Cliente eliminado correctamente');
        } catch (Exception $e) {
            Log::error('Error deleting client ID ' . $id . ': ' . $e->getMessage());
            return redirect()->route('clientes.index')->with('INCORRECTO', 'Error al eliminar cliente.');
        }
    }
}

