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
    /** Lista personajes: paginación, búsqueda, filtro y orden (bonus). */
    public function index(Request $request): Response
    {
        $generosPermitidos = ['masculino', 'femenino', 'n/a'];
        $columnasOrden = ['nombre', 'altura', 'masa', 'genero'];

        $generoFiltro = $request->string('genero')->toString();
        $buscar = trim($request->string('buscar')->toString());
        $ordenar = $request->string('ordenar')->toString();
        $direccion = strtolower($request->string('direccion')->toString()) === 'desc' ? 'desc' : 'asc';

        $consulta = Personaje::query();

        if (in_array($generoFiltro, $generosPermitidos, true)) {
            $consulta->where('genero', $generoFiltro);
        } else {
            $generoFiltro = '';
        }

        if ($buscar !== '') {
            $consulta->where('nombre', 'like', '%'.$buscar.'%');
        }

        if (in_array($ordenar, $columnasOrden, true)) {
            $consulta->orderBy($ordenar, $direccion);
        } else {
            $ordenar = 'nombre';
            $direccion = 'asc';
            $consulta->orderBy('nombre', 'asc');
        }

        return Inertia::render('Personajes/Index', [
            'personajes' => $consulta->paginate(10)->withQueryString(),
            'filtros' => [
                'genero' => $generoFiltro,
                'buscar' => $buscar,
                'ordenar' => $ordenar,
                'direccion' => $direccion,
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

        try {
            $swapi->importarPersonaje($swapiId);
        } catch (RuntimeException $e) {
            return back()->withErrors([
                'swapi_id' => $e->getMessage(),
            ]);
        }

        return redirect()
            ->route('personajes.index')
            ->with('success', 'Personaje importado desde SWAPI correctamente.');
    }
}