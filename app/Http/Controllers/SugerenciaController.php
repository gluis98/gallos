<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SugerenciaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mensaje' => ['required', 'string', 'max:2000'],
            'tipo'    => ['nullable', 'string', 'max:50'],
        ]);

        $entry = [
            'tipo'       => $request->input('tipo', 'otro'),
            'mensaje'    => $request->input('mensaje'),
            'user_id'    => optional(auth()->user())->id,
            'user_name'  => optional(auth()->user())->name,
            'tenant_id'  => optional(auth()->user())->tenant_id,
            'ip'         => $request->ip(),
            'created_at' => now()->toDateTimeString(),
        ];

        $path = 'sugerencias.json';
        $existing = Storage::exists($path) ? json_decode(Storage::get($path), true) : [];
        $existing[] = $entry;
        Storage::put($path, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return response()->json(['ok' => true, 'msj' => '¡Sugerencia recibida! Gracias.']);
    }
}
