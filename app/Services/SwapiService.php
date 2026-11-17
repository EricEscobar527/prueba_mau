<?php

namespace App\Services;

use App\Exceptions\SwapiException;
use App\Models\Personaje;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class SwapiService
{
    private const URL_BASE = 'https://swapi.py4e.com/api';

    private const SEGUNDOS_TIMEOUT = 10;

    private const GENEROS = [
        'male' => 'masculino',
        'female' => 'femenino',
    ];

    public function importarPersonaje(int $swapiId): Personaje
    {
        $datos = $this->obtenerPersonaje($swapiId);

        return Personaje::create([
            'nombre' => $datos['name'],
            'altura' => $this->convertirEntero($datos['height'] ?? null),
            'masa' => $this->convertirEntero($datos['mass'] ?? null),
            'color_cabello' => $datos['hair_color'] ?? null,
            'color_ojos' => $datos['eye_color'] ?? null,
            'genero' => self::GENEROS[$datos['gender'] ?? ''] ?? 'n/a',
            'planeta_origen' => $this->obtenerNombrePlaneta($datos['homeworld'] ?? null),
            'origen_swapi' => true,
            'swapi_id' => $swapiId,
        ]);
    }

    private function obtenerPersonaje(int $swapiId): array
    {
        $respuesta = $this->consultar(self::URL_BASE."/people/{$swapiId}/");

        if ($respuesta->notFound()) {
            throw new SwapiException("No existe un personaje con el ID {$swapiId} en SWAPI.");
        }

        if ($respuesta->failed() || ! $respuesta->json('name')) {
            throw new SwapiException('SWAPI respondió con un error. Intenta de nuevo más tarde.');
        }

        return $respuesta->json();
    }

    private function obtenerNombrePlaneta(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $respuesta = $this->consultar($url);
        } catch (SwapiException) {
            return null;
        }

        return $respuesta->successful() ? $respuesta->json('name') : null;
    }

    private function consultar(string $url): Response
    {
        try {
            return Http::timeout(self::SEGUNDOS_TIMEOUT)->get($url);
        } catch (ConnectionException) {
            throw new SwapiException('No se pudo conectar con SWAPI (tiempo de espera agotado o servicio caído).');
        }
    }

    private function convertirEntero(?string $valor): ?int
    {
        $numero = str_replace(',', '', (string) $valor);

        return is_numeric($numero) ? (int) $numero : null;
    }
}
