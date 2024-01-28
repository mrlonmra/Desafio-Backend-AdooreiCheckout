<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;

// Rotas para vendas
Route::middleware('auth.api')->group(function () {

    // Rotas para produtos
    Route::get('/products', [ProductController::class, 'index']); // Listar produtos disponíveis

    Route::get('/sales', [SaleController::class, 'index']); // Consultar vendas realizadas
    Route::post('/sales', [SaleController::class, 'store']); // Cadastrar nova venda
    Route::get('/sales/{id}', [SaleController::class, 'show']); // Consultar uma venda específica
    Route::delete('/sales/{id}', [SaleController::class, 'cancel']); // Cancelar uma venda
    Route::post('/sales/{id}/add-products', [SaleController::class, 'addProductsToSale']); // Adicionar produtos a uma venda

});
