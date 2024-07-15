<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class UpdateProjectIconRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'iconId' => ['required', 'string', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
         return [
             'iconId' => 'Icon Id',
         ];
    }
}
