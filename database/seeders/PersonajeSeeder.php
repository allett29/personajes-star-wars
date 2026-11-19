<?php

namespace Database\Seeders;

use App\Models\Personaje;
use Illuminate\Database\Seeder;

/**
 * Datos de ejemplo para probar el listado sin depender de SWAPI.
 * No duplica registros si ya existe el mismo nombre.
 */
class PersonajeSeeder extends Seeder
{
    public function run(): void
    {
        $ejemplos = [
            [
                'nombre' => 'Leia Organa',
                'altura' => 150,
                'masa' => 49,
                'color_cabello' => 'brown',
                'color_ojos' => 'brown',
                'genero' => 'femenino',
                'planeta_origen' => 'Alderaan',
                'origen_swapi' => false,
                'swapi_id' => null,
            ],
            [
                'nombre' => 'Han Solo',
                'altura' => 180,
                'masa' => 80,
                'color_cabello' => 'brown',
                'color_ojos' => 'brown',
                'genero' => 'masculino',
                'planeta_origen' => 'Corellia',
                'origen_swapi' => false,
                'swapi_id' => null,
            ],
            [
                'nombre' => 'Chewbacca',
                'altura' => 228,
                'masa' => 112,
                'color_cabello' => 'brown',
                'color_ojos' => 'blue',
                'genero' => 'n/a',
                'planeta_origen' => 'Kashyyyk',
                'origen_swapi' => false,
                'swapi_id' => null,
            ],
            [
                'nombre' => 'Padmé Amidala',
                'altura' => 165,
                'masa' => 45,
                'color_cabello' => 'brown',
                'color_ojos' => 'brown',
                'genero' => 'femenino',
                'planeta_origen' => 'Naboo',
                'origen_swapi' => false,
                'swapi_id' => null,
            ],
            [
                'nombre' => 'Yoda',
                'altura' => 66,
                'masa' => 17,
                'color_cabello' => 'white',
                'color_ojos' => 'brown',
                'genero' => 'masculino',
                'planeta_origen' => null,
                'origen_swapi' => false,
                'swapi_id' => null,
            ],
        ];

        // withTrashed(): si un ejemplo fue eliminado, no se vuelve a crear duplicado.
        foreach ($ejemplos as $fila) {
            Personaje::withTrashed()->firstOrCreate(
                ['nombre' => $fila['nombre']],
                $fila
            );
        }
    }
}