<?php

namespace App\Http\Controllers;

use App\Models\MateriaPrima;
use App\Models\MovimientoMateria;
use App\Models\TipoMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoMateriaController extends Controller
{
    public function index()
    {
        $movimientos = MovimientoMateria::query()
            ->with(['materiaPrima.unidadMedida', 'tipoMovimiento'])
            ->orderByDesc('id_movimiento')
            ->paginate(10);

        return view('vistas.materias_primas.movimientos.indexMovimientosMateria', compact('movimientos'));
    }

    public function create()
    {
        $materias = MateriaPrima::where('estado', 'ACTIVO')->orderBy('nombre')->get();
        $tipos = TipoMovimiento::all();

        return view('vistas.materias_primas.movimientos.registroMovimientoMateria', compact('materias', 'tipos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'txtmateria' => 'required|exists:materia_prima,id_materia',
            'txttipoMovimiento' => 'required|exists:tipo_movimiento,id_tipo_movimiento',
            'txtcantidad' => 'required|numeric|min:0.01',
            'txtrazon' => 'nullable|string|max:255',
        ]);

        $usuario = auth()->check()
            ? (auth()->user()->usuario ?? auth()->user()->name ?? auth()->user()->email ?? 'system')
            : 'system';

        DB::beginTransaction();
        try {
            $materia = MateriaPrima::where('id_materia', $request->txtmateria)->lockForUpdate()->firstOrFail();
            $tipo = TipoMovimiento::findOrFail($request->txttipoMovimiento);

            $anterior = (float) $materia->existencia_actual;
            $cantidad = (float) $request->txtcantidad;

            if ($tipo->afecta_stock === 'SUMA') {
                $nueva = $anterior + $cantidad;
            } else {
                $nueva = $anterior - $cantidad;
            }

            // Evitar stock negativo (regla común). Si no deseas esta validación, elimínala.
            if ($tipo->afecta_stock === 'RESTA' && $nueva < 0) {
                DB::rollBack();
                return back()->with('INCORRECTO', 'No se puede registrar el movimiento: stock insuficiente.');
            }

            $mov = MovimientoMateria::create([
                'id_materia' => $materia->id_materia,
                'id_tipo_movimiento' => $tipo->id_tipo_movimiento,
                'cantidad' => $cantidad,
                'existencia_anterior' => $anterior,
                'existencia_nueva' => $nueva,
                'razon' => $request->txtrazon,
                'fecha' => now(),
                'usuario' => (string) $usuario,
            ]);

            $materia->existencia_actual = $nueva;
            $materia->save();

            DB::commit();

            if ($mov) {
                return redirect()->route('movimientos-materia.index')->with('CORRECTO', 'Movimiento registrado y stock actualizado correctamente');
            }

            return back()->with('INCORRECTO', 'Error al registrar el movimiento');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('INCORRECTO', 'Error al registrar el movimiento');
        }
    }
}

