<?php
declare(strict_types=1);

namespace App\Features\User\TeamUsers\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Features\User\Users\Models\User;
use App\Shared\Rules\Uuid4Rule;

class TeamUsersFiltersRequest extends FormRequest
{
    public array $sorting = [
        User::NAME,
        User::EMAIL,
        User::ACTIVE,
    ];

    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name'        => 'nullable|string',
            'email'       => 'nullable|string|email',
            'nameOrEmail' => 'nullable|string',
            'active'      => 'nullable|boolean',
            'profileId'   => ['nullable', 'string', new Uuid4Rule()],
            'projectId'   => ['nullable', 'string', new Uuid4Rule()],
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'        => 'Name',
            'email'       => 'E-mail',
            'nameOrEmail' => 'Name or E-mail',
            'active'      => 'Active',
            'profileId'   => 'Profile Id',
            'projectId'   => 'Project Id',
        ]);
    }
}
