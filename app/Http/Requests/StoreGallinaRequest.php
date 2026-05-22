<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGallinaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'placa' => ['required', 'string', 'max:255'],
            'nombre' => ['nullable', 'string', 'max:255'],
            'marca' => ['nullable', 'string', 'max:255'],
            'anillo' => ['nullable', 'string', 'max:255'],
            'marca_nacimiento' => ['nullable', 'string', 'max:255'],
            'marca_federacion' => ['nullable', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'color_alternativo' => ['nullable', 'string', 'max:255'],
            'cresta' => ['nullable', 'string', 'max:255'],
            'fecha_nacimiento' => ['nullable', 'string', 'max:255'],
            'luna' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string'],
            'estatus' => ['nullable', 'string', 'max:255'],
            'padre_id' => ['nullable', 'integer', 'exists:gallos,id'],
            'madre_id' => ['nullable', 'integer', 'exists:gallinas,id'],
            'imagen' => ['nullable', 'array'],
            'imagen.*' => ['file', 'image', 'max:5120'],
        ];
    }
}
