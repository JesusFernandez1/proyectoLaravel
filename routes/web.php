<?php

use App\Http\Controllers\ProfileController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\githubController;
use App\Http\Controllers\googleController;
use App\Http\Controllers\userController;
use App\Http\Controllers\taskController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\feeController;
use App\Http\Controllers\paypalController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Mail\CuotaCreada;
use Illuminate\Support\Facades\Mail;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', function () {
    return view('base');
})->middleware(['auth', 'verified'])->name('base');

// Paypal
Route::controller(paypalController::class)->group(function(){
    Route::get('/paypal/pay/{id}', 'payWithPaypal')->name('paypal.pay');
    Route::get('/paypal/status/{id}','payPalStatus')->name('paypal.status');
    Route::get('/pagocorrecto','pagoCorrecto')->name('pagofinalizado');
});

Route::controller(githubController::class)->group(function () {
    Route::get('/auth/github/redirect', 'redirectGithub')->name('github.redirectGithub');
    Route::get('/auth/github/callback', 'callbackGithub');
});

Route::controller(googleController::class)->group(function () {
    Route::get('/auth/google/redirect', 'redirectGoogle')->name('google.redirectGoogle');
    Route::get('/google/callback', 'callbackGoogle');
});

Route::controller(customerController::class)->group(function () {
    Route::get('clientes/clientes_crearIncidencia', 'nuevaIncidencia')->name('clientes.nuevaIncidencia');
    Route::post('clientes/clientes_incidenciaCreada', 'crearIncidencia')->name('clientes.crearIncidencia');
    Route::get('clientes/clientes_eliminado/{id}', 'confirmarEliminarCliente')->middleware('auth')->middleware('admin')->name('clientes.confirmarEliminarCliente');
});
Route::resource('clientes', customerController::class)->middleware('auth')->middleware('admin');

Route::controller(feeController::class)->group(function () {
    Route::get('cuotas/cuotas_pagar', 'pagar')->middleware('auth')->middleware('admin')->name('cuotas.pagar');
    Route::get('cuotas/cuotas_excepcional/{id}', 'agregar')->middleware('auth')->middleware('admin')->name('cuotas.agregar');
    Route::get('cuotas/cuotas_eliminar/{id}', 'mostrarEliminar')->middleware('auth')->middleware('admin')->name('cuotas.mostrarEliminar');
    Route::get('cuotas/cuotas_eliminada/{id}', 'confirmarEliminarCuota')->middleware('auth')->middleware('admin')->name('cuotas.confirmarEliminarCuota');
    Route::get('cuotas/cuotas_remesaMensual', 'verRemesaMensual')->middleware('auth')->middleware('admin')->name('cuotas.verRemesaMensual');
    Route::get('cuotas/cuotas_remesaCreada', 'crearRemesaMensual')->middleware('auth')->middleware('admin')->name('cuotas.crearRemesaMensual');
    Route::get('cuotas/{id}/pdf', 'crearPDF')->middleware('auth')->middleware('admin')->name('cuotas.pdf');
});
Route::resource('cuotas', feeController::class)->middleware('auth')->middleware('admin');

Route::controller(userController::class)->group(function () {
    Route::get('usuarios/usuarios_eliminada/{id}', 'confirmarEliminarUsuario')->middleware('auth')->name('usuarios.confirmarEliminarUsuario');
});
Route::resource('usuarios', userController::class)->middleware('auth');

Route::controller(taskController::class)->group(function () {
    Route::get('tareas/tareas_verInformacionDetallada', 'verInformacionDetallada')->middleware('auth')->name('tareas.verInformacionDetallada');
    Route::get('tareas/tareas_pendientes', 'verTareasPendientes')->middleware('auth')->name('tareas.verTareasPendientes');
    Route::get('tareas/tareas_mostrarNoAsignadas', 'verTareasNoAsignadas')->middleware('auth')->middleware('admin')->name('tareas.verTareasNoAsignadas');
    Route::get('tareas/tareas_asignarOperario/{id}', 'asignarOperario')->middleware('auth')->middleware('admin')->name('tareas.asignarOperario');
    Route::put('tareas/tareas_asignada/{id}', 'operarioAsignado')->middleware('auth')->middleware('admin')->name('tareas.operarioAsignado');
    Route::get('tareas/tareas_eliminada/{id}', 'confirmarBorrarTarea')->middleware('auth')->middleware('admin')->name('tareas.confirmarBorrarTarea');
    Route::get('tareas/tareas_completar/{id}', 'cambiarEstadoTarea')->middleware('auth')->middleware('operario')->name('tareas.cambiarEstadoTarea');
    Route::put('tareas/tareas_completada/{id}', 'completarTarea')->middleware('auth')->middleware('operario')->name('tareas.completarTarea');
});
Route::resource('tareas', taskController::class)->middleware('auth');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
