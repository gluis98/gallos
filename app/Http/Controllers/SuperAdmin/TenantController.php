<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::query()->orderByDesc('created_at')->get();

        return view('super-admin.tenants.index', compact('tenants'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        Tenant::query()->create([
            'id' => (string) Str::uuid(),
            'name' => $data['name'],
            'status' => 'active',
        ]);

        return redirect()->route('superadmin.tenants.index')->with('ok', 'Tenant creado');
    }

    public function suspend(string $id)
    {
        Tenant::query()->whereKey($id)->update(['status' => 'suspended']);

        return redirect()->back()->with('ok', 'Tenant suspendido');
    }

    public function activate(string $id)
    {
        Tenant::query()->whereKey($id)->update(['status' => 'active']);

        return redirect()->back()->with('ok', 'Tenant activado');
    }
}
