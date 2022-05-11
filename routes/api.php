<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function() {
    Route::apiResource('clientes', 'ClientesController');
    Route::apiResource('carros', 'CarrosController');
    Route::apiResource('locacoes', 'LocacaoController');
    Route::apiResource('marcas', 'MarcasController');
    Route::apiResource('modelos', 'ModelosController');
    // auth routes
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('profile', 'AuthController@profile');

});

