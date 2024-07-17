<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class UpdateProjectTeamUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'teamUserId' => ['required', 'string', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
         return [
             'teamUserId' => 'Team User Id',
         ];
    }
}
