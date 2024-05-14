<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Auth\Services;

use App\Features\Auth\Responses\AuthResponse;
use App\Features\Auth\Services\AuthGenerateService;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\Mocks\AuthMocks;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthGenerateServiceTest extends TestCase
{
    private MockObject|AuthResponse $authResponseMock;

    protected function setUp(): void
    {
        parent::setUp();

        JWTAuth::shouldReceive('tokenById')->andreturn(AuthMocks::accessToken());
        JWTAuth::shouldReceive('getTTL')->andreturn(AuthMocks::getTTL());

        Auth::shouldReceive('tokenById')->andreturn(AuthMocks::accessToken());
        Auth::shouldReceive('getTTL')->andreturn(AuthMocks::getTTL());

        $this->authResponseMock = $this->createMock(AuthResponse::class);
    }

    public function getAuthGenerateService(): AuthGenerateService
    {
        return new AuthGenerateService($this->authResponseMock);
    }

    public function test_should_return_authentication_object()
    {
        $authGenerateService = $this->getAuthGenerateService();

        $authUserResponse = AuthMocks::getAuthUserResponse();

        $authResponse = $authGenerateService->execute($authUserResponse);

        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertEquals(AuthMocks::accessToken(), $authResponse->accessToken);
        $this->assertEquals(AuthMocks::getTTL(), $authResponse->expiresIn);
        $this->assertEquals(AuthMocks::tokenType(), $authResponse->tokenType);
    }
}
