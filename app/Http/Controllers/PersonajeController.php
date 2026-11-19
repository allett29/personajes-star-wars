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
    /** Lista con paginación, búsqueda, filtro por género y orden por columna (query string). */
    public function index(Request $request): Response
    {
        $generoFiltro = $request->string('genero')->toString();
        $generosPermitidos = ['masculino', 'femenino', 'n/a'];
        $buscar = trim($request->string('buscar')->toString());

        $orden = $request->string('orden')->toString();
        $columnasOrden = ['nombre', 'altura', 'masa', 'genero'];
        if (! in_array($orden, $columnasOrden, true)) {
            $orden = 'nombre';
        }

        $direccion = strtolower($request->string('direccion')->toString()) === 'desc' ? 'desc' : 'asc';

        $consulta = Personaje::query();

        if ($buscar !== '') {
            $consulta->where('nombre', 'like', '%'.addcslashes($buscar, '%_\\').'%');
        }

        if (in_array($generoFiltro, $generosPermitidos, true)) {
            $consulta->where('genero', $generoFiltro);
        } else {
            $generoFiltro = '';
        }

        $consulta->orderBy($orden, $direccion);

        return Inertia::render('Personajes/Index', [
            'personajes' => $consulta->paginate(10)->withQueryString(),
            'filtros' => [
                'genero' => $generoFiltro,
                'buscar' => $buscar,
                'orden' => $orden,
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

    /** Borrado lógico: el registro queda en la base con deleted_at y deja de listarse. */
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

        // withTrashed() incluye los eliminados: swapi_id es único también para ellos.
        $existente = Personaje::withTrashed()->where('swapi_id', $swapiId)->first();

        if ($existente?->trashed()) {
            $existente->restore();

            return redirect()
                ->route('personajes.index')
                ->with('success', "{$existente->nombre} estaba eliminado y se restauró.");
        }

        if ($existente) {
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