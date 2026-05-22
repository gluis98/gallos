<?php

namespace App\Listeners;

use App\Services\DollarRateService;
use Illuminate\Auth\Events\Login;

class RefreshDollarRateOnLogin
{
    /**
     * Si la tasa del dólar no es de hoy, la refresca al iniciar sesión.
     * Así el sistema siempre tiene la tasa del día vigente sin intervención manual.
     */
    public function handle(Login $event): void
    {
        if (!DollarRateService::isFresh()) {
            DollarRateService::refresh();
        }
    }
}
