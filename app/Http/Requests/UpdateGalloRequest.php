<?php

namespace App\Http\Requests;

class UpdateGalloRequest extends StoreGalloRequest
{
    public function rules(): array
    {
        return parent::rules();
    }
}
