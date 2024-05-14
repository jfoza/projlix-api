<?php
declare(strict_types=1);

namespace App\Features\User\AdminUsers\Requests;

use App\Features\Base\Requests\FormRequest;

class AdminUsersRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredString = ['required', 'string'];
        $emailRules     = ['required', 'string', 'email'];

        return [
            'name'  => $requiredString,
            'email' => $emailRules,
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => 'Name',
            'email' => 'E-mail',
        ];
    }
}
