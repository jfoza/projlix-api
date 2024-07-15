<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;

class UpdateProjectInfoRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
         return [
             'name'        => 'Name',
             'description' => 'Description',
         ];
    }
}
