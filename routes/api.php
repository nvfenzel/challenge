<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ApiAdminController;
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

Route::middleware(['auth:sanctum', 'isAdmin'])->post('/autorization', [ApiAdminController::class, 'autorization']);

Route::middleware(['auth:sanctum', 'isAdmin'])->get('/all_players', [ApiAdminController::class, 'players']);

Route::middleware(['auth:sanctum', 'isAdmin'])->post('/new_item', [ApiAdminController::class, 'item']);

Route::middleware(['auth:sanctum', 'isAdmin'])->get('/items', [ApiAdminController::class, 'items']);
