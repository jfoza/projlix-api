<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'teamUsers'   => 'nullable|array',
            'tags'        => 'nullable|array',

            'teamUsers.*' => ['nullable', new Uuid4Rule],
            'tags.*'      => ['nullable', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
         return [
             'name'        => 'Name',
             'description' => 'Description',
             'teamUsers'   => 'Membros',
             'tags'        => 'Tags',
         ];
    }
}
