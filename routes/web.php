<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produtos', [ProductController::class, 'index'])->name('produtos.index');
Route::get('/produtos/adicionar', [ProductController::class, 'create'])->name('produtos.create');
Route::post('/produtos/salvar', [ProductController::class, 'store'])->name('produtos.store');
Route::get('produtos/{nome}/editar', [ProductController::class, 'edit'])->name('produtos.edit');
Route::put('produtos/{nome}', [ProductController::class, 'update'])->name('produtos.update');
Route::delete('produtos/{nomeProduto}', [ProductController::class, 'destroy'])->name('produtos.destroy');



