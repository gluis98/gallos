<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SettingsService
{
    private const FILE = 'sa_settings.json';

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
        if (!Storage::exists(self::FILE)) {
            return self::defaults();
        }
        $data = json_decode(Storage::get(self::FILE), true);
        if (!is_array($data)) {
            return self::defaults();
        }
        return array_replace_recursive(self::defaults(), $data);
    }

    public static function save(array $data): void
    {
        Storage::put(self::FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
