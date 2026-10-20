<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class TenantCheckSubscriptions extends Command
{
    protected $signature = 'tenant:check-subscriptions';

    protected $description = 'Desactiva suscripciones y trials vencidos';

    public function handle(): int
    {
        $expired = Subscription::query()
            ->withoutGlobalScopes()
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->update(['status' => 'expired', 'plan' => 'free']);

        $trialsExpired = Subscription::query()
            ->withoutGlobalScopes()
            ->where('status', 'active')
            ->whereNotNull('trial_ends_at')
            ->where('trial_ends_at', '<', now())
            ->where('plan_sku', 'trial')
            ->update(['status' => 'expired', 'plan' => 'free']);

        $this->info("Suscripciones expiradas: {$expired}, trials expirados: {$trialsExpired}");

        return self::SUCCESS;
    }
}
