<?php
declare(strict_types=1);

namespace Tests\Unit\App\Features\Auth\Business;

use App\Features\Auth\Business\AuthBusiness;
use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\Contracts\CreateAuthDataServiceInterface;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthResponse;
use App\Features\Auth\Responses\AuthUserResponse;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Libraries\Uuid;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\Unit\Mocks\AuthMocks;

class AuthBusinessTest extends TestCase
{
    private MockObject|ShowAuthUserServiceInterface   $showAuthUserServiceMock;
    private MockObject|AuthGenerateServiceInterface   $authGenerateServiceMock;
    private MockObject|CreateAuthDataServiceInterface $createAuthDataServiceMock;

    private AuthDTO $authDtoMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->showAuthUserServiceMock = $this->createMock(ShowAuthUserServiceInterface::class);
        $this->authGenerateServiceMock = $this->createMock(AuthGenerateServiceInterface::class);
        $this->createAuthDataServiceMock = $this->createMock(CreateAuthDataServiceInterface::class);

        $this->authDtoMock = $this->createMock(AuthDTO::class);

        $this->authDtoMock->authType  = AuthTypesEnum::EMAIL_PASSWORD->value;
        $this->authDtoMock->email     = 'user-mock@email.com';
        $this->authDtoMock->password  = 'pass';
        $this->authDtoMock->ipAddress = '172.17.0.2';
    }

    public function getAuthUserResponseMock(): AuthUserResponse
    {
        $authUserResponse = new AuthUserResponse();

        $authUserResponse->id = Uuid::uuid4Generate();
        $authUserResponse->email = 'user-mock@email.com';
        $authUserResponse->fullName = 'User Mock';
        $authUserResponse->role = Collection::make();
        $authUserResponse->ability = [];

        return $authUserResponse;
    }

    public function getAuthResponseMock(): AuthResponse
    {
        $authResponse = new AuthResponse(
            $this->getAuthUserResponseMock()
        );

        $authResponse->accessToken = AuthMocks::accessToken();
        $authResponse->tokenType = AuthMocks::tokenType();
        $authResponse->expiresIn = '4320 min';

        return $authResponse;
    }

    public function getAuthBusiness(): AuthBusiness
    {
        return new AuthBusiness(
            $this->showAuthUserServiceMock,
            $this->authGenerateServiceMock,
            $this->createAuthDataServiceMock,
        );
    }

    public function test_must_perform_user_authentication(): void
    {
        $authBusiness = $this->getAuthBusiness();

        $this
            ->showAuthUserServiceMock
            ->method('execute')
            ->willReturn($this->getAuthUserResponseMock());

        $this
            ->authGenerateServiceMock
            ->method('execute')
            ->willReturn($this->getAuthResponseMock());

        $auth = $authBusiness->execute($this->authDtoMock);

        $this->assertInstanceOf(AuthResponse::class, $auth);
    }
}
