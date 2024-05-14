<?php

namespace App\Features\General\Positions\Requests;

use App\Features\Base\Requests\FormRequest;

class PositionsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'active'      => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
         return [
             'name'        => 'Name',
             'description' => 'Description',
             'active'      => 'Active',
         ];
    }
}
