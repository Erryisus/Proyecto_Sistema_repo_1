<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasaCambioController extends Controller
{
    public function index()
    {
        // Última tasa vigente hacia moneda destino (id_moneda_destino = 2)
        $tasaCambiaria = DB::table('tasas_cambio')
            ->where('id_moneda_destino', 2)
            ->orderBy('fecha_vigencia', 'desc')
            ->first();

        $valorActual = $tasaCambiaria ? $tasaCambiaria->valor_conversion : null;
        $fechaActual = $tasaCambiaria ? $tasaCambiaria->fecha_vigencia : null;

        return view('vistas.tasas.configuracion_tasa', compact('valorActual', 'fechaActual'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'valor_conversion' => 'required|numeric|min:0.0001'
        ]);

        $valor = $request->input('valor_conversion');

        DB::table('tasas_cambio')->insert([
            'id_moneda_origen' => 1, // USD
            'id_moneda_destino' => 2, // VES
            'valor_conversion' => $valor,
            'fecha_vigencia' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('CORRECTO', 'Tasa de cambio registrada correctamente.');
    }
}

