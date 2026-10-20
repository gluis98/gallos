<?php

namespace App\Http\Requests;

use Illuminate\Validation\Validator;

class UpdateGalloRequest extends StoreGalloRequest
{
    public function rules(): array
    {
        return parent::rules();
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // apiResource usa {gallo}; algunos clientes envían el id en la URL como segmento
            $galloId = (int) ($this->route('gallo') ?? $this->route('id') ?? 0);
            $padreId = (int) $this->input('padre_id');

            if ($galloId && $padreId && $galloId === $padreId) {
                $validator->errors()->add('padre_id', 'Un gallo no puede ser padre de sí mismo.');
            }
        });
    }
}
