<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produtos', [ProductController::class, 'index'])->name('produtos.index');
Route::get('/produtos/adicionar', [ProductController::class, 'create'])->name('produtos.create');
Route::post('/produtos/salvar', [ProductController::class, 'store'])->name('produtos.store');
Route::get('/produtos/{id}/editar', [ProductController::class, 'edit'])->name('produtos.edit');
Route::put('/produtos/{id}', [ProductController::class, 'update'])->name('produtos.update');
Route::delete('/produtos/{id}', [ProductController::class, 'destroy'])->name('produtos.destroy');
Route::get('/produtos/importar', [ProductController::class, 'showImportForm'])->name('produtos.importar');
Route::post('/produtos/importar', [ProductController::class, 'importar'])->name('produtos.importar.post');
