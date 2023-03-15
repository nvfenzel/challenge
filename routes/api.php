<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiAdminController;
use App\Http\Controllers\ApiPlayerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/register', [ApiController::class, 'register']);

Route::post('/login', [ApiController::class, 'login']);

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {

    //all_players recibe un método GET con el cual se obtienen todos los jugadores
    //registrados. La idea sería que primero se registren los jugadores en la ruta register
    //para luego poder ser dados de alta por el administrador. El administrador selecciona
    //el Id del usuario que quiere dar de alta y lo activa mandando un POST a la ruta autorization
    Route::get('/all_players', [ApiAdminController::class, 'players']);

    //autorization recibe un Método POST y admite dos valores:
    //Id: del usuario registrado que se quiere dar de alta
    //Activo: para activar al usuario del ID pasado o Inactivo para inactivar a ese usuario
    Route::post('/autorization', [ApiAdminController::class, 'autorization']);

    //Esta ruta recibe un Método GET y lista todos los items que hay
    Route::get('/items', [ApiAdminController::class, 'items']);

    //Esta ruta recibe un Método POST con los siguientes parámetros para crear un item:
    //'name': nombre del item
    //'pt_defense': puntos de defensa de 0 a 100
    //'pt_attack': puntos de ataque de 0 a 100
    //'type': tipo de item (solo acepta los parámetros: bota, arma y armadura)
    Route::post('/new_item', [ApiAdminController::class, 'item']);

    //Esta ruta recibe un método post y sirve para editar un item.
    //Los parámtreos que recibe son: name, pt_defense, pt_attack, type
    //y el Id del item que se quiere modificar. El único valor que siempre hay que pasar es
    //es el Id, los otros parámetro si no los quiero actualizar no los envío
    Route::post('/edit_item', [ApiAdminController::class, 'edit']);
});

// Estas son las rutas de los players y están protegidas para players logueados y que estén activos
// Si un jugador no está activo el administrador lo tiene que dar de alta.
Route::middleware(['auth:sanctum', 'isUser'])->group(function () {
//     Route::post('/autorization', [ApiPlayerController::class, 'autorization']);
        Route::get('/prueba', [ApiPlayerController::class, 'auth']);
//     Route::post('/new_item', [ApiPlayerController::class, 'item']);
//     Route::get('/items', [ApiPlayerController::class, 'items']);
//     Route::post('/edit_item', [ApiPlayerController::class, 'edit']);
});