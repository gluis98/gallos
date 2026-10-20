<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    public function isConfigured(): bool
    {
        return (bool) (config('paypal.client_id') && config('paypal.client_secret'));
    }

    public function baseUrl(): string
    {
        return config('paypal.mode') === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    public function getAccessToken(): string
    {
        return Cache::remember('paypal_access_token', 3000, function () {
            $response = Http::asForm()
                ->withBasicAuth(config('paypal.client_id'), config('paypal.client_secret'))
                ->post($this->baseUrl().'/v1/oauth2/token', [
                    'grant_type' => 'client_credentials',
                ]);

            if (! $response->successful()) {
                Log::error('PayPal token error', ['body' => $response->body()]);
                throw new \RuntimeException('No se pudo autenticar con PayPal.');
            }

            return $response->json('access_token');
        });
    }

    public function createSubscription(string $planId, string $tenantId, string $returnUrl, string $cancelUrl): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->post($this->baseUrl().'/v1/billing/subscriptions', [
                'plan_id' => $planId,
                'custom_id' => $tenantId,
                'application_context' => [
                    'brand_name' => 'Galpon',
                    'locale' => 'es-VE',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'SUBSCRIBE_NOW',
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ],
            ]);

        if (! $response->successful()) {
            Log::error('PayPal create subscription error', ['body' => $response->body()]);
            throw new \RuntimeException('No se pudo crear la suscripción en PayPal.');
        }

        return $response->json();
    }

    public function getSubscription(string $subscriptionId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->get($this->baseUrl()."/v1/billing/subscriptions/{$subscriptionId}");

        if (! $response->successful()) {
            throw new \RuntimeException('No se pudo verificar la suscripción en PayPal.');
        }

        return $response->json();
    }

    public function createOrder(float $amount, string $currency, string $description, string $tenantId, string $returnUrl, string $cancelUrl): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->post($this->baseUrl().'/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'custom_id' => $tenantId,
                    'description' => $description,
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                ]],
                'application_context' => [
                    'brand_name' => 'Galpon',
                    'locale' => 'es-VE',
                    'shipping_preference' => 'NO_SHIPPING',
                    'user_action' => 'PAY_NOW',
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ],
            ]);

        if (! $response->successful()) {
            Log::error('PayPal create order error', ['body' => $response->body()]);
            throw new \RuntimeException('No se pudo crear la orden en PayPal.');
        }

        return $response->json();
    }

    public function captureOrder(string $orderId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->baseUrl()."/v2/checkout/orders/{$orderId}/capture", (object) []);

        if (! $response->successful()) {
            Log::error('PayPal capture order error', ['body' => $response->body()]);
            throw new \RuntimeException('No se pudo capturar el pago en PayPal.');
        }

        return $response->json();
    }

    public function getOrder(string $orderId): array
    {
        $response = Http::withToken($this->getAccessToken())
            ->get($this->baseUrl()."/v2/checkout/orders/{$orderId}");

        if (! $response->successful()) {
            throw new \RuntimeException('No se pudo verificar la orden en PayPal.');
        }

        return $response->json();
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        $webhookId = config('paypal.webhook_id');
        if (! $webhookId) {
            Log::warning('PayPal webhook ID not configured — skipping verification');

            return app()->environment('local');
        }

        $response = Http::withToken($this->getAccessToken())
            ->post($this->baseUrl().'/v1/notifications/verify-webhook-signature', [
                'auth_algo' => $headers['paypal-auth-algo'] ?? $headers['PAYPAL-AUTH-ALGO'] ?? '',
                'cert_url' => $headers['paypal-cert-url'] ?? $headers['PAYPAL-CERT-URL'] ?? '',
                'transmission_id' => $headers['paypal-transmission-id'] ?? $headers['PAYPAL-TRANSMISSION-ID'] ?? '',
                'transmission_sig' => $headers['paypal-transmission-sig'] ?? $headers['PAYPAL-TRANSMISSION-SIG'] ?? '',
                'transmission_time' => $headers['paypal-transmission-time'] ?? $headers['PAYPAL-TRANSMISSION-TIME'] ?? '',
                'webhook_id' => $webhookId,
                'webhook_event' => json_decode($body, true),
            ]);

        return $response->successful() && $response->json('verification_status') === 'SUCCESS';
    }

    public function getApprovalUrl(array $paypalResponse): ?string
    {
        foreach ($paypalResponse['links'] ?? [] as $link) {
            if (($link['rel'] ?? '') === 'approve') {
                return $link['href'];
            }
        }

        return null;
    }
}
