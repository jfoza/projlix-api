<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class UpdateProjectTagsRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'tagId' => ['required', 'string', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
         return [
             'tagId' => 'Tag Id',
         ];
    }
}
