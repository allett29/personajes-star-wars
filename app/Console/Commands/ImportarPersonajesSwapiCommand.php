<?php

namespace App\Console\Commands;

use App\Models\Personaje;
use App\Services\SwapiPersonajeService;
use Illuminate\Console\Command;
use RuntimeException;

/**
 * Bonus: importa varios IDs de SWAPI desde la terminal.
 * Ejemplo: php artisan personajes:importar-swapi 1 2 3
 */
class ImportarPersonajesSwapiCommand extends Command
{
    protected $signature = 'personajes:importar-swapi {ids* : IDs SWAPI entre 1 y 83}';

    protected $description = 'Importa uno o más personajes desde SWAPI por ID';

    public function handle(SwapiPersonajeService $swapi): int
    {
        $importados = 0;
        $omitidos = 0;
        $errores = 0;

        foreach ($this->argument('ids') as $idCrudo) {
            $swapiId = (int) $idCrudo;

            if ($swapiId < 1 || $swapiId > 83) {
                $this->error("ID {$idCrudo} fuera de rango (1–83).");
                $errores++;

                continue;
            }

            $existente = Personaje::withTrashed()->where('swapi_id', $swapiId)->first();

            if ($existente?->trashed()) {
                $existente->restore();
                $this->info("Restaurado: {$existente->nombre} (SWAPI #{$swapiId})");
                $importados++;

                continue;
            }

            if ($existente) {
                $this->warn("ID {$swapiId} ya importado, se omite.");
                $omitidos++;

                continue;
            }

            try {
                $datos = $swapi->obtenerDatosParaGuardar($swapiId);
                Personaje::create($datos);
                $this->info("Importado: {$datos['nombre']} (SWAPI #{$swapiId})");
                $importados++;
            } catch (RuntimeException $e) {
                $this->error("ID {$swapiId}: {$e->getMessage()}");
                $errores++;
            }
        }

        $this->newLine();
        $this->line("Listo — importados: {$importados}, omitidos: {$omitidos}, errores: {$errores}");

        return $errores > 0 ? self::FAILURE : self::SUCCESS;
    }
}