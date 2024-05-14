<?php

namespace App\Features\Auth\Responses;

use Illuminate\Database\Eloquent\Collection;

class AuthUserResponse
{
    public string|null $id;
    public string|null $email;
    public string|null $fullName;
    public Collection $role;
    public Collection $churches;
    public bool|null   $status;
    public array|null  $ability;
}
