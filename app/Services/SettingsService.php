<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
    private const FILE = 'sa_settings.json';
    private const DB_KEY = 'sa_settings';
    private const CACHE_KEY = 'app.sa_settings';

    public static function defaults(): array
    {
        return [
            'dollar_rate' => [
                'api_url' => '',
                'api_key' => '81c11692-0399-4bc5-b69b-fd5cf4e3524f',
            ],
            'plans' => [
                'pro_monthly_price'      => 15,
                'pro_yearly_price'       => 150,
                'pro_currency'           => 'USD',
                'pro_description'        => 'Plan profesional con gallos, gallinas y ventas ilimitadas.',
                'free_gallos_limit'      => 20,
                'free_gallinas_limit'    => 20,
                'extra_galpon_price'     => 5,
                'marketplace_rating_days'=> 12,
            ],
            'paypal' => [
                'enabled'          => true,
                'monthly_plan_id'  => '',
                'yearly_plan_id'   => '',
            ],
            'payment_methods' => [
                'zelle' => [
                    'enabled' => true,
                    'email'   => 'pagos@gallos.pro',
                    'name'    => 'Gallos Pro LLC',
                    'notes'   => 'Incluye tu nombre completo en la nota del pago.',
                ],
                'pagomovil' => [
                    'enabled' => true,
                    'banco'   => 'Banco de Venezuela (0102)',
                    'telefono'=> '0414-0000000',
                    'cedula'  => 'V-00000000',
                    'notes'   => 'Concepto: tu email registrado.',
                ],
                'usdt_binance' => [
                    'enabled' => true,
                    'red'     => 'TRC20 (Tron)',
                    'wallet'  => 'TXxxxxxxxxxxxxxxxxxxxxxxxxxxx',
                    'notes'   => 'Verifica la red antes de enviar. Solo TRC20.',
                ],
            ],
        ];
    }

    public static function get(): array
    {
        return Cache::remember(self::CACHE_KEY, 60, function () {
            $stored = self::readStored();

            if (! is_array($stored)) {
                return self::defaults();
            }

            return array_replace_recursive(self::defaults(), $stored);
        });
    }

    public static function save(array $data): void
    {
        // Persistir en BD (fuente de verdad en producción)
        if (self::dbReady()) {
            DB::table('app_settings')->updateOrInsert(
                ['key' => self::DB_KEY],
                [
                    'value' => json_encode($data, JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Mantener copia en JSON por compatibilidad local
        try {
            Storage::disk('local')->put(
                self::FILE,
                json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        } catch (\Throwable $e) {
            Log::warning('SettingsService: no se pudo escribir sa_settings.json', [
                'error' => $e->getMessage(),
            ]);
        }

        Cache::forget(self::CACHE_KEY);
    }

    protected static function readStored(): ?array
    {
        // 1) Preferir BD
        if (self::dbReady()) {
            $row = DB::table('app_settings')->where('key', self::DB_KEY)->first();
            if ($row) {
                $decoded = is_string($row->value)
                    ? json_decode($row->value, true)
                    : (array) $row->value;
                if (is_array($decoded)) {
                    return $decoded;
                }
            }
        }

        // 2) Fallback al archivo JSON
        if (Storage::disk('local')->exists(self::FILE)) {
            $decoded = json_decode(Storage::disk('local')->get(self::FILE), true);
            if (is_array($decoded)) {
                // Auto-migrar a BD si aún no existe
                if (self::dbReady()) {
                    try {
                        DB::table('app_settings')->updateOrInsert(
                            ['key' => self::DB_KEY],
                            [
                                'value' => json_encode($decoded, JSON_UNESCAPED_UNICODE),
                                'updated_at' => now(),
                                'created_at' => now(),
                            ]
                        );
                    } catch (\Throwable $e) {
                        Log::warning('SettingsService: no se pudo migrar a BD', ['error' => $e->getMessage()]);
                    }
                }

                return $decoded;
            }
        }

        return null;
    }

    protected static function dbReady(): bool
    {
        try {
            return Schema::hasTable('app_settings');
        } catch (\Throwable) {
            return false;
        }
    }
}
