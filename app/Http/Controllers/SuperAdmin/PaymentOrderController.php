<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Services\AuditService;
use App\Services\SubscriptionActivationService;
use Illuminate\Http\Request;

class PaymentOrderController extends Controller
{
    public function __construct(
        protected SubscriptionActivationService $activationService
    ) {}

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

        $planSku = $order->plan_sku ?? 'pro_yearly';

        $this->activationService->activatePro($order->tenant_id, $planSku, now(), [
            'gateway' => $order->gateway ?? 'manual',
        ]);

        AuditService::log('payment.verified', "Pago #{$order->id} verificado. Tenant {$order->tenant_id}. Plan: {$planSku}. Monto: {$order->amount} {$order->currency}.", [
            'order_id'  => $order->id,
            'tenant_id' => $order->tenant_id,
            'plan_sku'  => $planSku,
            'amount'    => $order->amount,
            'currency'  => $order->currency,
            'method'    => $order->method,
        ]);

        return redirect()->back()->with('ok', 'Pago verificado y plan activado.');
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
