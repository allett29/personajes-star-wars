<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePersonajeRequest;
use App\Http\Requests\UpdatePersonajeRequest;
use App\Models\Personaje;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

/**
 * CRUD de personajes. Cada acción devuelve una vista Inertia o redirige con mensaje.
 */
class PersonajeController extends Controller
{
    /** Lista personajes con 10 registros por página. */
    public function index(): Response
    {
        $personajes = Personaje::query()
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Personajes/Index', [
            'personajes' => $personajes,
        ]);
    }

    /** Muestra el formulario vacío para crear. */
    public function create(): Response
    {
        return Inertia::render('Personajes/Create');
    }

    /** Guarda un personaje creado manualmente (origen_swapi = false). */
    public function store(StorePersonajeRequest $request): RedirectResponse
    {
        Personaje::create([
            ...$request->validated(),
            'origen_swapi' => false,
        ]);

        return redirect()
            ->route('personajes.index')
            ->with('success', 'Personaje creado correctamente.');
    }

    /** Muestra el formulario con los datos actuales del personaje. */
    public function edit(Personaje $personaje): Response
    {
        return Inertia::render('Personajes/Edit', [
            'personaje' => $personaje,
        ]);
    }

    /** Actualiza los datos del personaje en base de datos. */
    public function update(UpdatePersonajeRequest $request, Personaje $personaje): RedirectResponse
    {
        $personaje->update($request->validated());

        return redirect()
            ->route('personajes.index')
            ->with('success', 'Personaje actualizado correctamente.');
    }

    /** Elimina el registro de la base de datos. */
    public function destroy(Personaje $personaje): RedirectResponse
    {
        $personaje->delete();

        return redirect()
            ->route('personajes.index')
            ->with('success', 'Personaje eliminado correctamente.');
    }
}