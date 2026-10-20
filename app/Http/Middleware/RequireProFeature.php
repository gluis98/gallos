<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireProFeature
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();
        if (! $user || $user->is_superadmin) {
            return $next($request);
        }

        if ($this->subscriptionService->canUseFeature($feature)) {
            return $next($request);
        }

        $pitch = $this->subscriptionService->upgradePitch($feature);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'message' => $pitch['body'],
                'msj' => $pitch['body'],
                'title' => $pitch['title'],
                'upgrade_url' => route('billing.checkout', ['plan' => 'pro_monthly', 'from' => $pitch['module']]),
            ], 403);
        }

        return redirect()
            ->route('billing.checkout', ['plan' => 'pro_monthly', 'from' => $pitch['module']])
            ->with('upgrade_pitch', $pitch);
    }
}
