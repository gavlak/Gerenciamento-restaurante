<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardapioController;
use App\Http\Controllers\Api\FuncionarioController;
use App\Http\Controllers\Api\ProdutoController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/produtos-estoque-baixo', [ProdutoController::class, 'estoqueBaixo']);
    Route::get('/produtos', [ProdutoController::class, 'index']);
    Route::post('/produtos', [ProdutoController::class, 'store']);
    Route::get('/produtos/{id}', [ProdutoController::class, 'show']);
    Route::put('/produtos/{id}', [ProdutoController::class, 'update']);
    Route::delete('/produtos/{id}', [ProdutoController::class, 'destroy']);

    Route::get('/funcionarios', [FuncionarioController::class, 'index']);
    Route::post('/funcionarios', [FuncionarioController::class, 'store']);
    Route::get('/funcionarios/{id}', [FuncionarioController::class, 'show']);
    Route::put('/funcionarios/{id}', [FuncionarioController::class, 'update']);
    Route::delete('/funcionarios/{id}', [FuncionarioController::class, 'destroy']);

    Route::get('/cardapios', [CardapioController::class, 'index']);
    Route::post('/cardapios', [CardapioController::class, 'store']);
    Route::get('/cardapios/{id}', [CardapioController::class, 'show']);
    Route::put('/cardapios/{id}', [CardapioController::class, 'update']);
    Route::delete('/cardapios/{id}', [CardapioController::class, 'destroy']);
});
