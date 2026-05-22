<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use Illuminate\Console\Command;

class TenantCheckSubscriptions extends Command
{
    protected $signature = 'tenant:check-subscriptions';

    protected $description = 'Desactiva suscripciones vencidas (plan SaaS)';

    public function handle(): int
    {
        $updated = Subscription::query()
            ->withoutGlobalScopes()
            ->where('status', 'active')
            ->whereNotNull('ends_at')
            ->where('ends_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Suscripciones marcadas como expiradas: {$updated}");

        return self::SUCCESS;
    }
}
