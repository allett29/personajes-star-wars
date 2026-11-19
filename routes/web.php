<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::redirect('/', '/personajes');

Route::get('/personajes', function () {
    return Inertia::render('Welcome', [
        'titulo' => 'Personajes Star Wars',
    ]);
})->name('personajes.index');
