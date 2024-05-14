<?php

namespace App\Features\General\Tags\Requests;

use App\Features\Base\Requests\FormRequest;

class TagsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string',
        ];
    }

    public function attributes(): array
    {
         return [
             'name' => 'Name',
         ];
    }
}
