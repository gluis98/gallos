<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Services\AuditService;
use App\Services\SubscriptionActivationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function __construct(
        protected SubscriptionActivationService $activationService
    ) {}

    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $search = $request->get('q', '');

        $query = Subscription::withoutGlobalScopes()->with('tenant')->orderByDesc('updated_at');

        if ($filter === 'pro')     $query->where('plan', 'pro')->where('status', 'active');
        if ($filter === 'free')    $query->where('plan', 'free');
        if ($filter === 'expired') $query->where('ends_at', '<', now())->where('status', 'active');
        if ($filter === 'expiring')$query->whereBetween('ends_at', [now(), now()->addDays(30)])->where('status', 'active');

        $subscriptions = $query->paginate(30)->appends($request->query());

        $subscriptions->getCollection()->transform(function ($sub) use ($search) {
            $sub->user = User::query()
                ->where('tenant_id', $sub->tenant_id)
                ->where('is_superadmin', false)
                ->when($search, fn($q) => $q->where(function($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                }))
                ->first();
            $sub->gallos_count   = DB::table('gallos')->where('tenant_id', $sub->tenant_id)->count();
            $sub->gallinas_count = DB::table('gallinas')->where('tenant_id', $sub->tenant_id)->count();
            $sub->ventas_count   = DB::table('ventas')->where('tenant_id', $sub->tenant_id)->count();
            return $sub;
        });

        if ($search) {
            $subscriptions->setCollection(
                $subscriptions->getCollection()->filter(fn($s) => $s->user !== null)
            );
        }

        $stats = [
            'pro'      => Subscription::withoutGlobalScopes()->where('plan', 'pro')->where('status', 'active')->count(),
            'free'     => Subscription::withoutGlobalScopes()->where('plan', 'free')->count(),
            'expiring' => Subscription::withoutGlobalScopes()->whereBetween('ends_at', [now(), now()->addDays(30)])->where('status', 'active')->count(),
            'expired'  => Subscription::withoutGlobalScopes()->where('ends_at', '<', now())->where('status', 'active')->count(),
        ];

        return view('super-admin.subscriptions.index', compact('subscriptions', 'stats', 'filter', 'search'));
    }

    public function activate(string $id, Request $request)
    {
        $sub = Subscription::withoutGlobalScopes()->findOrFail($id);
        $months = (int) $request->input('months', 12);
        $planSku = $months <= 1 ? 'pro_monthly' : 'pro_yearly';

        $this->activationService->activatePro($sub->tenant_id, $planSku, now(), [
            'gateway' => 'manual',
        ]);

        $newEnd = $sub->fresh()->ends_at;

        return redirect()->back()->with('ok', "Plan Pro activado hasta {$newEnd->format('d/m/Y')}.");
    }

    public function cancel(string $id)
    {
        $sub = Subscription::withoutGlobalScopes()->findOrFail($id);
        $sub->update(['status' => 'cancelled']);

        AuditService::log('subscription.cancelled', "Suscripción cancelada para tenant {$sub->tenant_id}.", [
            'subscription_id' => $sub->id,
            'tenant_id'       => $sub->tenant_id,
        ]);

        return redirect()->back()->with('ok', 'Suscripción cancelada.');
    }

    public function setPlan(string $id, Request $request)
    {
        $request->validate(['plan' => ['required', 'in:free,pro']]);
        $sub = Subscription::withoutGlobalScopes()->findOrFail($id);
        $data = ['plan' => $request->plan, 'status' => 'active'];
        if ($request->plan === 'pro') {
            $data['ends_at'] = now()->addYear();
        }
        $sub->update($data);

        AuditService::log('subscription.plan_changed', "Plan cambiado a {$request->plan} para tenant {$sub->tenant_id}.", [
            'subscription_id' => $sub->id,
            'tenant_id'       => $sub->tenant_id,
            'plan'            => $request->plan,
        ]);

        return redirect()->back()->with('ok', 'Plan actualizado correctamente.');
    }
}
