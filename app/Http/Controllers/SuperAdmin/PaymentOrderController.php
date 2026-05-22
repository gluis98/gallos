<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SubscriptionUpgradedNotification;
use App\Services\AuditService;
use Illuminate\Http\Request;

class PaymentOrderController extends Controller
{
    public function index()
    {
        $orders = PaymentOrder::query()
            ->with('tenant')
            ->orderByDesc('created_at')
            ->get();

        return view('super-admin.payments.index', compact('orders'));
    }

    public function verify(Request $request, int $id)
    {
        $order = PaymentOrder::query()->findOrFail($id);
        if ($order->status === 'verificado') {
            return redirect()->back()->with('ok', 'Esta orden ya estaba verificada.');
        }

        $order->update(['status' => 'verificado']);

        $sub = Subscription::withoutGlobalScopes()
            ->where('tenant_id', $order->tenant_id)
            ->orderByDesc('id')
            ->first();

        if ($sub) {
            $sub->update([
                'plan' => 'pro',
                'status' => 'active',
                'ends_at' => now()->addYear(),
            ]);
        } else {
            Subscription::withoutGlobalScopes()->create([
                'tenant_id' => $order->tenant_id,
                'plan' => 'pro',
                'status' => 'active',
                'ends_at' => now()->addYear(),
            ]);
        }

        User::query()
            ->where('tenant_id', $order->tenant_id)
            ->where('is_superadmin', false)
            ->get()
            ->each(fn (User $u) => $u->notify(new SubscriptionUpgradedNotification));

        AuditService::log('payment.verified', "Pago #{$order->id} verificado. Tenant {$order->tenant_id}. Monto: {$order->amount} {$order->currency}.", [
            'order_id'  => $order->id,
            'tenant_id' => $order->tenant_id,
            'amount'    => $order->amount,
            'currency'  => $order->currency,
            'method'    => $order->method,
        ]);

        return redirect()->back()->with('ok', 'Pago verificado y plan Pro activado.');
    }

    public function reject(Request $request, int $id)
    {
        $order = PaymentOrder::query()->findOrFail($id);
        $order->update(['status' => 'rechazado']);

        AuditService::log('payment.rejected', "Pago #{$order->id} rechazado. Tenant {$order->tenant_id}.", [
            'order_id'  => $order->id,
            'tenant_id' => $order->tenant_id,
        ]);

        return redirect()->back()->with('ok', 'Pago marcado como rechazado.');
    }
}
