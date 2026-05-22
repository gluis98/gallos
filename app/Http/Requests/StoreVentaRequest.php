<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $tipo = $this->input('tipo_item', 'gallo');

        return [
            'tipo_item'     => ['nullable', 'string', 'in:gallo,gallina,inventario'],
            'gallo_id'      => $tipo === 'gallo'      ? ['required', 'integer', 'exists:gallos,id'] : ['nullable', 'integer'],
            'gallina_id'    => $tipo === 'gallina'    ? ['required', 'integer', 'exists:gallinas,id'] : ['nullable', 'integer'],
            'inventario_id' => $tipo === 'inventario' ? ['required', 'integer', 'exists:inventarios,id'] : ['nullable', 'integer'],
            'cantidad'      => ['nullable', 'numeric', 'min:0.01'],
            'nombre_cliente' => ['required', 'string', 'max:255'],
            'telefono'       => ['nullable', 'string', 'max:255'],
            'monto'          => ['required', 'numeric', 'min:0'],
            'observaciones'  => ['nullable', 'string'],
            'tipo_venta'     => ['nullable', 'string', 'max:64'],
            'fecha'          => ['nullable', 'date'],
            'publicacion_id' => ['nullable', 'integer', 'exists:publicaciones,id'],
        ];
    }
}
