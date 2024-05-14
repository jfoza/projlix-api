<?php
declare(strict_types=1);

namespace App\Features\User\Users\Requests;

use App\Features\Base\Requests\FormRequest;

class UsersFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name'  => 'nullable|string',
            'email' => 'nullable|string|email',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'  => 'Name',
            'email' => 'E-mail',
        ]);
    }
}
