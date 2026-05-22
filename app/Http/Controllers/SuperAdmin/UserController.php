<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->where('is_superadmin', false)
            ->orderByDesc('created_at')
            ->get();

        $gallosCounts    = DB::table('gallos')->selectRaw('tenant_id, COUNT(*) as cnt')->groupBy('tenant_id')->pluck('cnt', 'tenant_id');
        $gallinasCounts  = DB::table('gallinas')->selectRaw('tenant_id, COUNT(*) as cnt')->groupBy('tenant_id')->pluck('cnt', 'tenant_id');
        $ventasCounts    = DB::table('ventas')->selectRaw('tenant_id, COUNT(*) as cnt')->groupBy('tenant_id')->pluck('cnt', 'tenant_id');
        $comprasCounts   = DB::table('compras')->selectRaw('tenant_id, COUNT(*) as cnt')->groupBy('tenant_id')->pluck('cnt', 'tenant_id');

        return view('super-admin.users.index', compact(
            'users', 'gallosCounts', 'gallinasCounts', 'ventasCounts', 'comprasCounts'
        ));
    }

    public function show(string $id)
    {
        $user = User::query()->findOrFail($id);
        $tenantId = $user->tenant_id;

        $gallos   = $tenantId ? DB::table('gallos')->where('tenant_id', $tenantId)->orderByDesc('created_at')->get() : collect();
        $gallinas = $tenantId ? DB::table('gallinas')->where('tenant_id', $tenantId)->orderByDesc('created_at')->get() : collect();
        $ventas   = $tenantId ? DB::table('ventas')->where('tenant_id', $tenantId)->orderByDesc('created_at')->limit(50)->get() : collect();
        $compras  = $tenantId ? DB::table('compras')->where('tenant_id', $tenantId)->orderByDesc('created_at')->limit(50)->get() : collect();

        return view('super-admin.users.show', compact('user', 'gallos', 'gallinas', 'ventas', 'compras'));
    }
}
