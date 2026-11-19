<?php

namespace App\Console\Commands;

use App\Services\SwapiPersonajeService;
use Illuminate\Console\Command;
use RuntimeException;

/**
 * Bonus: importa uno o varios IDs de SWAPI desde la terminal.
 * Ejemplo: php artisan personajes:importar-swapi 1 2 3
 */
class ImportarPersonajesSwapiCommand extends Command
{
    protected $signature = 'personajes:importar-swapi {ids* : IDs SWAPI entre 1 y 83}';

    protected $description = 'Importa personajes desde SWAPI por lista de IDs';

    public function handle(SwapiPersonajeService $swapi): int
    {
        $importados = 0;
        $errores = 0;

        foreach ($this->argument('ids') as $idCrudo) {
            $id = (int) $idCrudo;

            if ($id < 1 || $id > 83) {
                $this->error("ID {$idCrudo} fuera de rango (1–83).");
                $errores++;

                continue;
            }

            try {
                $personaje = $swapi->importarPersonaje($id);
                $this->info("Importado: {$personaje->nombre} (SWAPI #{$id})");
                $importados++;
            } catch (RuntimeException $e) {
                $this->warn("ID {$id}: {$e->getMessage()}");
                $errores++;
            }
        }

        $this->line("Listo. Importados: {$importados}. Con aviso o error: {$errores}.");

        return $errores > 0 ? self::FAILURE : self::SUCCESS;
    }
}