<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PaymentOrder;
use App\Models\User;
use App\Services\AuditService;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentOrderController extends Controller
{
    public function create()
    {
        $settings = SettingsService::get();
        return view('tenant.payments.create', compact('settings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'currency' => ['required', 'string', 'max:8'],
            'method' => ['required', 'in:zelle,pagomovil,usdt_binance'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'proof' => ['nullable', 'file', 'image', 'max:5120'],
        ]);

        $proofPath = null;
        if ($request->hasFile('proof')) {
            $proofPath = $request->file('proof')->store('payment_proofs', 'public');
        }

        $order = PaymentOrder::query()->create([
            'tenant_id' => tenant('id'),
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'method' => $data['method'],
            'reference' => $data['reference'] ?? null,
            'status' => 'pendiente',
            'proof_path' => $proofPath,
            'notes' => $data['notes'] ?? null,
        ]);

        $emails = User::query()->where('is_superadmin', true)->whereNotNull('email')->pluck('email')->filter()->values();
        if ($emails->isNotEmpty()) {
            $body = "Nueva orden de pago #{$order->id} por {$order->amount} {$order->currency} ({$order->method}). Tenant: ".tenant('id');
            Mail::raw($body, function ($message) use ($emails) {
                $message->to($emails->all())->subject('Pago pendiente de verificación — Gallos');
            });
        }

        AuditService::log('payment.submitted', "Comprobante de pago enviado. Tenant ".tenant('id').". Monto: {$data['amount']} {$data['currency']}.", [
            'order_id' => $order->id,
            'method'   => $data['method'],
            'amount'   => $data['amount'],
        ]);

        return redirect()->route('tenant.payments.create')->with('ok', 'Comprobante enviado. Te avisaremos al verificar el pago.');
    }
}
