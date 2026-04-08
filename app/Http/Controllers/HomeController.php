<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $estado = Auth::user()->estado;
        if ($estado == 1) {
            // $sql = DB::select('select count(*) as total from usuario where tipo=1');
            // return view('home')->with('sql', $sql);
        $total_usuario = DB::table('usuario')->count();
        $total_cliente = DB::table('cliente')->count();
        $total_venta = DB::table('venta')->count();
        $total_producto = DB::table('producto')->count();

        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

        $current_year = date('Y');
        $ventas_mensuales = DB::table('venta')
            ->selectRaw('MONTH(fecha) as mes, COUNT(*) as count_ventas')
            ->whereYear('fecha', $current_year)
            ->groupBy(DB::raw('MONTH(fecha)'))
            ->pluck('count_ventas', 'mes')
            ->toArray();

        $ventas = [];
        for ($i = 1; $i <= 12; $i++) {
            $ventas[] = $ventas_mensuales[$i] ?? 0;
        }

        return view('home', compact('total_usuario', 'total_cliente', 'total_venta', 'total_producto', 'meses', 'ventas'));
        } else {
            session()->invalidate();
            session()->regenerateToken();
            return back()->with('mensaje', 'CUENTA ELIMINADA: esta cuenta se ha eliminado, consulte con el administrador');
        }
    }
}
