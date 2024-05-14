<?php

namespace App\Features\Auth\Responses;

class AuthResponse
{
    public string $accessToken;
    public string $tokenType;
    public string $expiresIn;

    public function __construct(
        public AuthUserResponse $user
    ) {}
}
