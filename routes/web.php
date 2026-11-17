<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/prueba-inertia', fn () => Inertia\Inertia::render('Prueba', ['mensaje' => 'Inertia + Vue OK']));
