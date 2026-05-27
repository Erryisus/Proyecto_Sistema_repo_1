<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfVentaController extends Controller
{
    /**
     * Genera el PDF de una venta específica.
     */
    public function generarPDF(int $id)
    {
        try {
            // 1. Obtener la cabecera de la venta con JOINs para traer nombres de cliente y usuario
            $venta = DB::table('venta')
                ->select('venta.*', 'cliente.nombre as cliente_nombre', 'usuario.nombre as cajero_nombre')
                ->join('cliente', 'venta.id_cliente', '=', 'cliente.id_cliente')
                ->join('usuario', 'venta.id_usuario', '=', 'usuario.id_usuario')
                ->where('venta.id_venta', $id)
                ->first();

            // Verificar si la venta existe antes de continuar
            if (!$venta) {
                return back()->with('INCORRECTO', 'La venta solicitada no existe.');
            }

            // 2. Obtener los detalles de la venta (productos)
            $detalles = DB::table('venta_detalle')
                ->join('producto', 'venta_detalle.id_producto', '=', 'producto.id_producto')
                ->select(
                    'venta_detalle.cantidad',
                    'venta_detalle.precio',
                    'venta_detalle.subtotal',
                    'producto.nombre as producto_nombre'
                )
                ->where('venta_detalle.id_venta', $id)
                ->orderBy('venta_detalle.id_venta_detalle')
                ->get();

            // 3. Preparar los datos que se enviarán a la vista Blade
            $fecha = null;
            try {
                $fecha = \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s');
            } catch (\Throwable $e) {
                $fecha = date('d/m/Y H:i:s');
            }

            $data = [
                'venta'    => $venta,
                'detalles' => $detalles,
                'cajero'   => $venta->cajero_nombre,
                'fecha'    => $fecha
            ];


            // 4. Generar el PDF cargando una vista (Asegúrate de crear resources/views/pdf/factura.blade.php)
            $pdf = Pdf::loadView('pdf.factura', $data)
                      ->setPaper('a4', 'portrait');

            // 5. Definir el nombre del archivo
            $filename = 'factura-' . ($venta->codigo_venta ?? $venta->id_venta) . '.pdf';

            // 6. Retornar el PDF para visualización (abre en pestaña nueva)
            return $pdf->stream($filename);


        } catch (\Throwable $e) {
            // Registrar el error en el log de Laravel para poder revisarlo después
            Log::error('[PDF Venta] Error generando PDF ID ' . $id . ': ' . $e->getMessage());

            return back()->with('INCORRECTO', 'Error interno al generar el PDF.');
        }
    }
}
