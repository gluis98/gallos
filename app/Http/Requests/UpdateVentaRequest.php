<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'gallo_id' => ['sometimes', 'integer', 'exists:gallos,id'],
            'cliente_id' => ['sometimes', 'integer', 'exists:clients,id'],
            'precio' => ['sometimes', 'numeric', 'min:0'],
            'observaciones' => ['nullable', 'string'],
            'tipo_venta' => ['nullable', 'string', 'max:64'],
            'fecha' => ['nullable', 'date'],
            'estatus' => ['nullable', 'string', 'max:255'],
        ];
    }
}
