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

Route::namespace("API")->prefix('v1')->group(function() {
    // auth routes
    Route::post('login', 'AuthController@login');
    // rotas que precisam de autenticação(logar no sistema) e de um token de autorização(JWT)
    Route::middleware('jwt.auth')->group(function() {
        Route::apiResource('clientes', 'ClientesController');
        Route::apiResource('carros', 'CarrosController');
        Route::apiResource('locacoes', 'LocacaoController');
        Route::apiResource('marcas', 'MarcasController');
        Route::apiResource('modelos', 'ModelosController');
        // pegar os dados do usuário logado
        Route::post('profile', 'AuthController@profile');
        // renovar o token de autorização
        Route::post('refresh', 'AuthController@refresh');
        // logout
        Route::post('logout', 'AuthController@logout');
    });
});



