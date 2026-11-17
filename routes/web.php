<?php

use App\Http\Controllers\PersonajeController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/personajes');

Route::controller(PersonajeController::class)->group(function () {
    Route::get('/personajes', 'index')->name('personajes.index');
    Route::get('/personajes/crear', 'create')->name('personajes.create');
    Route::post('/personajes', 'store')->name('personajes.store');
    Route::post('/personajes/importar', 'importar')->name('personajes.importar');
    Route::get('/personajes/{personaje}/editar', 'edit')->name('personajes.edit');
    Route::put('/personajes/{personaje}', 'update')->name('personajes.update');
    Route::delete('/personajes/{personaje}', 'destroy')->name('personajes.destroy');
});
