<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'teamUsers'   => ['nullable', 'array', new ManyUuidv4Rule()],
        ];
    }

    public function attributes(): array
    {
         return [
             'name'        => 'Name',
             'description' => 'Description',
             'teamUsers'   => 'Membros',
         ];
    }
}
