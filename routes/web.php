<?php

use App\Http\Controllers\PersonajeController;
use Illuminate\Support\Facades\Route;

// Página principal redirige al listado de personajes.
Route::redirect('/', '/personajes');

// Rutas del CRUD según la especificación de la prueba técnica.
Route::get('/personajes', [PersonajeController::class, 'index'])->name('personajes.index');
Route::get('/personajes/crear', [PersonajeController::class, 'create'])->name('personajes.create');
Route::post('/personajes', [PersonajeController::class, 'store'])->name('personajes.store');
Route::get('/personajes/{personaje}/editar', [PersonajeController::class, 'edit'])->name('personajes.edit');
Route::put('/personajes/{personaje}', [PersonajeController::class, 'update'])->name('personajes.update');
Route::delete('/personajes/{personaje}', [PersonajeController::class, 'destroy'])->name('personajes.destroy');