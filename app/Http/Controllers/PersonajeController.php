<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportPersonajeSwapiRequest;
use App\Http\Requests\StorePersonajeRequest;
use App\Http\Requests\UpdatePersonajeRequest;
use App\Models\Personaje;
use App\Services\SwapiPersonajeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

/**
 * CRUD de personajes. Cada acción devuelve una vista Inertia o redirige con mensaje.
 */
class PersonajeController extends Controller
{
    /** Lista personajes con 10 registros por página y filtro opcional por género. */
    public function index(Request $request): Response
    {
        $generoFiltro = $request->string('genero')->toString();
        $generosPermitidos = ['masculino', 'femenino', 'n/a'];

        $consulta = Personaje::query()->orderBy('nombre');

        // Bonus: filtrar solo si llega un género válido en la URL (?genero=masculino).
        if (in_array($generoFiltro, $generosPermitidos, true)) {
            $consulta->where('genero', $generoFiltro);
        } else {
            $generoFiltro = '';
        }

        return Inertia::render('Personajes/Index', [
            'personajes' => $consulta->paginate(10)->withQueryString(),
            'filtros' => [
                'genero' => $generoFiltro,
            ],
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

    /**
     * Importa un personaje desde SWAPI por su ID.
     * Evita duplicados por swapi_id y devuelve errores legibles si la API falla.
     */
    public function importar(
        ImportPersonajeSwapiRequest $request,
        SwapiPersonajeService $swapi
    ): RedirectResponse {
        $swapiId = (int) $request->validated('swapi_id');

        if (Personaje::where('swapi_id', $swapiId)->exists()) {
            return back()->withErrors([
                'swapi_id' => 'Ese personaje de SWAPI ya está registrado.',
            ]);
        }

        try {
            $datos = $swapi->obtenerDatosParaGuardar($swapiId);
        } catch (RuntimeException $e) {
            return back()->withErrors([
                'swapi_id' => $e->getMessage(),
            ]);
        }

        Personaje::create($datos);

        return redirect()
            ->route('personajes.index')
            ->with('success', 'Personaje importado desde SWAPI correctamente.');
    }
}