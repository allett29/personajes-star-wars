<?php

namespace App\Services;

use App\Models\Personaje;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Consulta la API pública SWAPI y convierte la respuesta al formato de nuestra base de datos.
 */
class SwapiPersonajeService
{
    private const BASE_URL = 'https://swapi.py4e.com/api/people';

    /**
     * Obtiene un personaje por su ID en SWAPI (1–83) y devuelve los campos listos para guardar.
     *
     * @throws RuntimeException Si la API falla, el ID no existe o los datos no son válidos.
     */
    public function obtenerDatosParaGuardar(int $swapiId): array
    {
        $json = $this->consultarApi($swapiId);

        return [
            'nombre' => $json['name'],
            'altura' => $this->aEnteroONulo($json['height'] ?? null),
            'masa' => $this->aEnteroONulo($json['mass'] ?? null),
            'color_cabello' => $this->aTextoONulo($json['hair_color'] ?? null),
            'color_ojos' => $this->aTextoONulo($json['eye_color'] ?? null),
            'genero' => $this->mapearGenero($json['gender'] ?? 'n/a'),
            'planeta_origen' => null,
            'origen_swapi' => true,
            'swapi_id' => $swapiId,
        ];
    }

    /**
     * Importa un personaje si el swapi_id no existe aún en la base de datos.
     * Reutilizado por el controlador web y el comando Artisan.
     */
    public function importarPersonaje(int $swapiId): Personaje
    {
        if (Personaje::where('swapi_id', $swapiId)->exists()) {
            throw new RuntimeException('Ese personaje de SWAPI ya está registrado.');
        }

        return Personaje::create($this->obtenerDatosParaGuardar($swapiId));
    }

    /** Realiza la petición HTTP con tiempo máximo de espera. */
    private function consultarApi(int $swapiId): array
    {
        try {
            $response = Http::timeout(15)->get(self::BASE_URL.'/'.$swapiId.'/');
        } catch (ConnectionException) {
            throw new RuntimeException('No se pudo conectar con SWAPI. Verifique su conexión o intente más tarde.');
        }

        if ($response->status() === 404) {
            throw new RuntimeException('No existe un personaje con ese ID en SWAPI (1–83).');
        }

        if (! $response->successful()) {
            throw new RuntimeException('SWAPI respondió con un error. Intente más tarde.');
        }

        return $response->json();
    }

    /** SWAPI devuelve números como texto; "unknown" se guarda como null. */
    private function aEnteroONulo(?string $valor): ?int
    {
        if ($valor === null || $valor === '' || strtolower($valor) === 'unknown') {
            return null;
        }

        return (int) $valor;
    }

    /** Campos de texto opcionales; "unknown" / "n/a" se dejan en null si aplica. */
    private function aTextoONulo(?string $valor): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        return $valor;
    }

    /** Traduce los valores de género de SWAPI al español exigido en la prueba. */
    private function mapearGenero(string $generoIngles): string
    {
        return match (strtolower($generoIngles)) {
            'male' => 'masculino',
            'female' => 'femenino',
            default => 'n/a',
        };
    }
}