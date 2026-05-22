<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\CatalogService;
use App\Services\DollarRateService;

class CatalogController extends Controller
{
    public function __construct(
        protected CatalogService $catalogService
    ) {}

    public function show(string $token)
    {
        $tenant = $this->catalogService->findTenantByToken($token);

        if (! $tenant) {
            abort(404);
        }

        $gallos = $this->catalogService->gallosForCatalog($tenant);
        $rate = DollarRateService::getCachedRate();

        return view('catalog.public', [
            'tenant' => $tenant,
            'gallos' => $gallos,
            'rate' => $rate > 0 ? $rate : null,
        ]);
    }
}
