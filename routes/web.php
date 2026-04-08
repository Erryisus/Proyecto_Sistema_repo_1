<?php

use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\EspecialidadController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RecuperarClaveController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route("home");
});

Auth::routes(['verify' => true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


/* mis rutas */
//RUTAS DE MI PERFIL
Route::get("mi-perfil", [PerfilController::class, "index"])->name("usuario.perfil")->middleware('verified');
Route::post("actualizar-foto-perfil", [PerfilController::class, "actualizarIMG"])->name("perfil.actualizarIMG")->middleware('verified');
Route::get("eliminar-foto-perfil", [PerfilController::class, "eliminarFotoPerfil"])->name("perfil.eliminarFotoPerfil")->middleware('verified');
Route::put("actualizar-datos-perfil", [PerfilController::class, "actualizarDatos"])->name("perfil.actualizarDatos")->middleware('verified');


//empresa
Route::get('empresa-index', [EmpresaController::class, 'index'])->name('empresa.index')->middleware('verified');
Route::post('empresa-update-{id}', [EmpresaController::class, 'update'])->name('empresa.update')->middleware('verified');
Route::post("actualizar-logo", [EmpresaController::class, "actualizarLogo"])->name("empresa.actualizarLogo")->middleware('verified');
Route::delete("eliminar-logo",[EmpresaController::class, "eliminarLogo"])->name("empresa.eliminarLogo")->middleware('verified');


//productos
Route::resource('productos', ProductoController::class)->middleware('verified');
Route::post("buscar-producto",[ProductoController::class, "buscarProducto"])->name("producto.buscar")->middleware('verified');
Route::post("registrar-foto-producto",[ProductoController::class, "registrarFotoProducto"])->name("producto.registrarFotoProducto")->middleware('verified');
Route::delete("eliminar-productos",[ProductoController::class ,"eliminarProducto"])->name("producto.eliminar")->middleware('verified');


//cambiarclave
Route::get("cambiar-clave", [PerfilController::class, "cambiarClave"])->name("usuario.cambiarClave")->middleware('verified');
Route::post("cambiar-clave", [PerfilController::class, "actualizarClave"])->name("usuario.actualizarClave")->middleware('verified');


//categorias
Route::resource('categoria', CategoriaController::class)->middleware('verified');

//usuarios
Route::resource('usuario', UsuarioController::class)->middleware('verified');
Route::post("registrar-foto-usuario",[UsuarioController::class, "registrarFotoUsuario"])->name("usuario.registrarFotoUsuario")->middleware('verified');
Route::delete("eliminar-usuarios",[UsuarioController::class ,"eliminarUsuario"])->name("usuario.eliminar")->middleware('verified');


//entradas
Route::resource('entradas', EntradaController::class)->middleware('verified');

//ventas
use App\Http\Controllers\VentaController;

Route::resource('ventas', VentaController::class)->middleware('verified');
Route::post("buscar-venta",[VentaController::class, "buscarVenta"])->name("venta.buscar")->middleware('verified');
Route::post("registrar-foto-venta",[VentaController::class, "registrarFotoVenta"])->name("venta.registrarFotoVenta")->middleware('verified');
Route::delete("eliminar-foto-venta",[VentaController::class, "eliminarFotoVenta"])->name("venta.eliminarFoto")->middleware('verified');
Route::get("reporte-ventas", [VentaController::class, "reporte"])->name("venta.reporte")->middleware('verified');
Route::get("clientes", [VentaController::class, "getClientes"])->name("venta.clientes")->middleware('verified');
Route::post("cliente-nuevo", [VentaController::class, "storeCliente"])->name("venta.storeCliente")->middleware('verified');

Route::get("reporte-ventas-pdf", [VentaController::class, "reportePDF"])->name("venta.reporte.pdf")->middleware('verified');

Route::get("reporte-productos", [\App\Http\Controllers\ProductoController::class, "reporte"])->name("producto.reporte")->middleware('verified');

