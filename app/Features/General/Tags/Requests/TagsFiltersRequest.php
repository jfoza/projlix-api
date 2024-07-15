<?php

namespace App\Features\General\Tags\Requests;

use App\Features\Base\Requests\FormRequest;

class TagsFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name' => 'nullable|string|max:100',
        ]);
    }

    public function attributes(): array
    {
         return $this->mergePaginationOrderAttributes([
             'name' => 'Name',
         ]);
    }
}
