<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;

class MateriaPrimaController extends Controller
{
    public function index()
    {
        $datos = MateriaPrima::query()
            ->with(['unidadMedida'])
            ->orderByDesc('id_materia')
            ->paginate(10);

        return view('vistas.materias_primas.indexMateriaPrima', compact('datos'));
    }

    public function create()
    {
        $unidades = UnidadMedida::all();
        return view('vistas.materias_primas.registroMateriaPrima', compact('unidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'txtcodigo' => 'required|string|max:20|unique:materia_prima,codigo',
            'txtnombre' => 'required|string|max:100',
            'txtexistencia' => 'required|numeric|min:0',
            'txtstockminimo' => 'required|numeric|min:0',
            'txtunidadmedida' => 'required|exists:unidad_medida,id_unidad',
            'txtfechavencimiento' => 'nullable|date',
            'txtestado' => 'required|in:ACTIVO,INACTIVO',
        ]);

        $materia = MateriaPrima::create([
            'codigo' => $request->txtcodigo,
            'nombre' => $request->txtnombre,
            'existencia_actual' => $request->txtexistencia,
            'stock_minimo' => $request->txtstockminimo,
            'id_unidad' => $request->txtunidadmedida,
            'fecha_vencimiento' => $request->txtfechavencimiento,
            'estado' => $request->txtestado,
            'fecha_registro' => now(),
        ]);

        if ($materia) {
            return redirect()->route('materias-primas.index')->with('CORRECTO', 'Materia prima registrada correctamente');
        }

        return back()->with('INCORRECTO', 'Error al registrar la materia prima');
    }

    public function edit(string $id)
    {
        $materia = MateriaPrima::with('unidadMedida')->findOrFail($id);
        $unidades = UnidadMedida::all();

        return view('vistas.materias_primas.registroMateriaPrima', compact('materia', 'unidades'));
    }

    public function update(Request $request, string $id)
    {
        $materia = MateriaPrima::findOrFail($id);

        $request->validate([
            'txtcodigo' => 'required|string|max:20|unique:materia_prima,codigo,' . $materia->id_materia . ',id_materia',
            'txtnombre' => 'required|string|max:100',
            'txtexistencia' => 'required|numeric|min:0',
            'txtstockminimo' => 'required|numeric|min:0',
            'txtunidadmedida' => 'required|exists:unidad_medida,id_unidad',
            'txtfechavencimiento' => 'nullable|date',
            'txtestado' => 'required|in:ACTIVO,INACTIVO',
        ]);

        $materia->update([
            'codigo' => $request->txtcodigo,
            'nombre' => $request->txtnombre,
            'existencia_actual' => $request->txtexistencia,
            'stock_minimo' => $request->txtstockminimo,
            'id_unidad' => $request->txtunidadmedida,
            'fecha_vencimiento' => $request->txtfechavencimiento,
            'estado' => $request->txtestado,
        ]);

        return redirect()->route('materias-primas.index')->with('CORRECTO', 'Materia prima actualizada correctamente');
    }

    public function destroy(string $id)
    {
        $materia = MateriaPrima::findOrFail($id);
        $materia->delete();

        return redirect()->route('materias-primas.index')->with('CORRECTO', 'Materia prima eliminada correctamente');
    }
}

