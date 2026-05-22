<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\AuditService;
use App\Services\DollarRateService;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings   = SettingsService::get();
        $rateCache  = DollarRateService::getCached();
        $currentRate = DollarRateService::getCachedRate();
        return view('super-admin.settings.index', compact('settings', 'rateCache', 'currentRate'));
    }

    public function update(Request $request)
    {
        $data = SettingsService::get();

        // ── Tasa del dólar ──
        $data['dollar_rate']['api_url'] = trim($request->input('dollar_rate_api_url', ''));
        $data['dollar_rate']['api_key'] = trim($request->input('dollar_rate_api_key', ''));

        // ── Precios de planes ──
        $data['plans']['pro_monthly_price']   = (float) $request->input('pro_monthly_price', 15);
        $data['plans']['pro_yearly_price']    = (float) $request->input('pro_yearly_price', 150);
        $data['plans']['pro_currency']        = $request->input('pro_currency', 'USD');
        $data['plans']['pro_description']     = $request->input('pro_description', '');
        $data['plans']['free_gallos_limit']   = (int) $request->input('free_gallos_limit', 20);
        $data['plans']['free_gallinas_limit'] = (int) $request->input('free_gallinas_limit', 20);

        // ── Zelle ──
        $data['payment_methods']['zelle']['enabled'] = $request->boolean('zelle_enabled');
        $data['payment_methods']['zelle']['email']   = $request->input('zelle_email', '');
        $data['payment_methods']['zelle']['name']    = $request->input('zelle_name', '');
        $data['payment_methods']['zelle']['notes']   = $request->input('zelle_notes', '');

        // ── Pago Móvil ──
        $data['payment_methods']['pagomovil']['enabled']  = $request->boolean('pagomovil_enabled');
        $data['payment_methods']['pagomovil']['banco']    = $request->input('pagomovil_banco', '');
        $data['payment_methods']['pagomovil']['telefono'] = $request->input('pagomovil_telefono', '');
        $data['payment_methods']['pagomovil']['cedula']   = $request->input('pagomovil_cedula', '');
        $data['payment_methods']['pagomovil']['notes']    = $request->input('pagomovil_notes', '');

        // ── USDT ──
        $data['payment_methods']['usdt_binance']['enabled'] = $request->boolean('usdt_enabled');
        $data['payment_methods']['usdt_binance']['red']     = $request->input('usdt_red', 'TRC20 (Tron)');
        $data['payment_methods']['usdt_binance']['wallet']  = $request->input('usdt_wallet', '');
        $data['payment_methods']['usdt_binance']['notes']   = $request->input('usdt_notes', '');

        SettingsService::save($data);

        AuditService::log('settings.updated', 'Configuración del sistema actualizada.', []);

        return redirect()->route('superadmin.settings.index')->with('ok', 'Configuración guardada correctamente.');
    }

    public function refreshRate()
    {
        try {
            $result = DollarRateService::refreshWithDetails();

            AuditService::log('dollar_rate.refreshed', 'Tasa del dólar actualizada manualmente.', $result);

            return response()->json([
                'ok'    => true,
                'tasa'  => $result['tasa'],
                'fecha' => $result['fecha'],
                'msg'   => 'Bs. ' . number_format($result['tasa'], 2),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'ok'    => false,
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
