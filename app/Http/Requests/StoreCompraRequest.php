<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreCompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'proveedor_nombre' => ['required', 'string', 'max:255'],
            'proveedor_telefono' => ['nullable', 'string', 'max:255'],
            'proveedor_email' => ['nullable', 'email', 'max:255'],
            'fecha_compra' => ['required', 'date'],
            'observaciones' => ['nullable', 'string', 'max:4000'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.tipo_ave' => ['required', 'in:gallo,gallina,inventario'],
            'items.*.inventario_id' => ['nullable', 'integer', 'exists:inventarios,id'],
            'items.*.cantidad' => ['nullable', 'numeric', 'min:0.01'],
            'items.*.placa' => ['nullable', 'string', 'max:255'],
            'items.*.nombre' => ['nullable', 'string', 'max:255'],
            'items.*.marca_nacimiento' => ['nullable', 'string', 'max:255'],
            'items.*.color' => ['nullable', 'string', 'max:255'],
            'items.*.costo' => ['required', 'numeric', 'min:0'],
            'items.*.observaciones' => ['nullable', 'string', 'max:2000'],
            'fotos' => ['sometimes', 'array'],
            'fotos.*' => ['nullable', 'file', 'image', 'max:5120'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            foreach ($this->input('items', []) as $idx => $item) {
                if (($item['tipo_ave'] ?? '') === 'inventario' && empty($item['inventario_id'])) {
                    $validator->errors()->add("items.$idx.inventario_id", 'Selecciona un ítem de inventario.');
                }
            }
        });
    }
}

