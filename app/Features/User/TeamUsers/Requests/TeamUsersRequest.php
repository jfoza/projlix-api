<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;
use App\Shared\Rules\Uuid4Rule;

class TeamUsersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => 'required|string',
            'email'      => 'required|string|email',
            'profileId'  => ['required', 'string', new Uuid4Rule()],
            'projectsId' => ['nullable', 'array', new ManyUuidv4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'Name',
            'email'      => 'E-mail',
            'profileId'  => 'Profile Id',
            'projectsId' => 'Projects Id',
        ];
    }
}
