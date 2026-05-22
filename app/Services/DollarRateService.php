<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class DollarRateService
{
    private const FILE = 'dollar_rate.json';

    /**
     * API gratuita sin autenticación ni Cloudflare.
     * Devuelve: {"promedio": 520.91, "fechaActualizacion": "2026-05-20T..."}
     */
    private const FREE_API = 'https://ve.dolarapi.com/v1/dolares/oficial';

    /** Devuelve la tasa vigente. Si no es fresca para hoy, refresca. */
    public static function getRate(): float
    {
        if (!self::isFresh()) {
            try {
                self::refreshWithDetails();
            } catch (\Throwable) {
                // Fallo silencioso en auto-refresh
            }
        }
        return self::getCachedRate();
    }

    /**
     * Intenta obtener la tasa usando todas las fuentes disponibles:
     * 1. API gratuita (ve.dolarapi.com) — sin autenticación
     * 2. API configurada por el usuario (bcvapi.tech u otra)
     * 3. Si todo falla, lanza excepción con detalle
     */
    public static function refreshWithDetails(): array
    {
        $errors = [];

        // ── Fuente 1: ve.dolarapi.com (gratis, sin auth, sin Cloudflare) ──
        try {
            $result = self::fetchFromUrl(self::FREE_API, '');
            if ($result['tasa'] > 0) {
                self::saveCache($result['tasa'], $result['fecha'], 've.dolarapi.com');
                return $result;
            }
        } catch (\Throwable $e) {
            $errors[] = 've.dolarapi.com: ' . $e->getMessage();
        }

        // ── Fuente 2: API configurada por el usuario ──
        $settings = SettingsService::get();
        $apiUrl   = trim($settings['dollar_rate']['api_url'] ?? '');
        $apiKey   = trim($settings['dollar_rate']['api_key'] ?? '');

        if (!empty($apiUrl)) {
            try {
                $result = self::fetchFromUrl($apiUrl, $apiKey);
                if ($result['tasa'] > 0) {
                    self::saveCache($result['tasa'], $result['fecha'], $apiUrl);
                    return $result;
                }
            } catch (\Throwable $e) {
                $errors[] = "{$apiUrl}: " . $e->getMessage();
            }
        }

        throw new \RuntimeException(
            'No se pudo obtener la tasa de ninguna fuente. Detalles: ' . implode(' | ', $errors)
        );
    }

    /** Realiza la petición HTTP y extrae la tasa del JSON. */
    private static function fetchFromUrl(string $url, string $apiKey): array
    {
        $headers = [
            'Accept'     => 'application/json',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ];

        if (!empty($apiKey)) {
            $headers['Authorization'] = $apiKey;
            $headers['X-API-Key']     = $apiKey;
        }

        $response = Http::withHeaders($headers)
            ->withOptions(['verify' => false, 'allow_redirects' => true])
            ->timeout(12)
            ->get($url);

        $body = $response->body();

        if (stripos(ltrim($body), '<!DOCTYPE') === 0 || stripos(ltrim($body), '<html') === 0) {
            throw new \RuntimeException("HTTP {$response->status()} — Cloudflare bloqueó la petición.");
        }

        if (!$response->successful()) {
            throw new \RuntimeException("HTTP {$response->status()} — " . substr($body, 0, 150));
        }

        $data = $response->json();

        if (!is_array($data)) {
            throw new \RuntimeException('Respuesta no es JSON válido: ' . substr($body, 0, 100));
        }

        $tasa  = self::extractTasa($data);
        $fecha = self::extractFecha($data);

        return ['tasa' => $tasa, 'fecha' => $fecha];
    }

    /** Guarda la tasa en caché local. */
    private static function saveCache(float $tasa, string $fecha, string $source): void
    {
        Storage::put(self::FILE, json_encode([
            'tasa'       => (string) $tasa,
            'fecha'      => $fecha,
            'source'     => $source,
            'fetched_at' => now()->toDateTimeString(),
        ], JSON_PRETTY_PRINT));
    }

    /**
     * Detecta automáticamente el campo de tasa.
     * Soporta: tasa, promedio, rate, precio, price, paralelo, bcv, dolar, valor, value
     */
    private static function extractTasa(array $data): float
    {
        $candidates = ['tasa', 'promedio', 'promedio_real', 'rate', 'precio', 'price', 'paralelo', 'bcv', 'dolar', 'valor', 'value'];

        foreach ($candidates as $key) {
            if (isset($data[$key]) && is_numeric($data[$key]) && (float) $data[$key] > 0) {
                return round((float) $data[$key], 4);
            }
        }

        foreach ($data as $value) {
            if (is_array($value)) {
                $nested = self::extractTasa($value);
                if ($nested > 0) return $nested;
            }
        }

        return 0;
    }

    /** Extrae la fecha de la respuesta. */
    private static function extractFecha(array $data): string
    {
        foreach (['fecha', 'fechaActualizacion', 'date', 'updated_at', 'last_update'] as $key) {
            if (!empty($data[$key])) {
                // Normalizar a Y-m-d
                $raw = (string) $data[$key];
                if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $raw, $m)) {
                    return $m[1];
                }
            }
        }
        return now()->toDateString();
    }

    /** Alias para compatibilidad — devuelve solo el float. */
    public static function refresh(): float
    {
        try {
            return self::refreshWithDetails()['tasa'];
        } catch (\Throwable) {
            return self::getCachedRate();
        }
    }

    /** ¿El caché es de hoy? */
    public static function isFresh(): bool
    {
        $cached = self::getCached();
        return $cached !== null && ($cached['fecha'] ?? '') === now()->toDateString();
    }

    public static function getCached(): ?array
    {
        if (!Storage::exists(self::FILE)) return null;
        $data = json_decode(Storage::get(self::FILE), true);
        return is_array($data) ? $data : null;
    }

    /** Tasa del caché o 0 si no hay datos. */
    public static function getCachedRate(): float
    {
        $cached = self::getCached();
        return $cached ? (float) ($cached['tasa'] ?? 0) : 0;
    }

    public static function toBolivares(float $usd): float
    {
        $rate = self::getCachedRate();
        return $rate > 0 ? round($usd * $rate, 2) : 0;
    }
}
