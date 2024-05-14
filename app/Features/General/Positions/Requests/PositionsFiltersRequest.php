<?php

namespace App\Features\General\Positions\Requests;

use App\Features\Base\Requests\FormRequest;

class PositionsFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules(
            [
                'name' => 'nullable|string',
            ]
        );
    }

    public function attributes(): array
    {
         return $this->mergePaginationOrderAttributes(
             [
                 'name' => 'Name',
             ]
         );
    }
}
