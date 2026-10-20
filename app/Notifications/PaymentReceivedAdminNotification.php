<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentReceivedAdminNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $tenantId,
        public string $planSku,
        public float $amount,
        public array $meta = []
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_received',
            'tenant_id' => $this->tenantId,
            'plan_sku' => $this->planSku,
            'amount' => $this->amount,
            'gateway' => $this->meta['gateway'] ?? 'paypal',
            'message' => "Pago de \${$this->amount} recibido ({$this->planSku}) — Tenant {$this->tenantId}",
        ];
    }
}
