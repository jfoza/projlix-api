<?php
declare(strict_types=1);

namespace App\Features\Auth\Business;

use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\Contracts\CreateAuthDataServiceInterface;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthResponse;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Hash;

class AuthBusiness implements AuthBusinessInterface
{
    private AuthDTO $authDTO;

    public function __construct(
        private readonly ShowAuthUserServiceInterface   $showAuthUserService,
        private readonly AuthGenerateServiceInterface   $authGenerateService,
        private readonly CreateAuthDataServiceInterface $createAuthDataService,
    ) {}

    public function execute(AuthDTO $authDTO): AuthResponse
    {
        $this->authDTO = $authDTO;

        if($this->authDTO->authType == AuthTypesEnum::GOOGLE->value)
        {
            $this->setAuthByGoogle();
        }

        $authUserResponse    = $this->showAuthUserService->execute($this->authDTO);
        $authGenerateService = $this->authGenerateService->execute($authUserResponse);

        $initialDate = Helpers::getCurrentTimestampCarbon();
        $finalDate   = Helpers::getCurrentTimestampCarbon()->addDays(2);

        $this->authDTO->userId      = $authUserResponse->id;
        $this->authDTO->initialDate = $initialDate;
        $this->authDTO->finalDate   = $finalDate;
        $this->authDTO->token       = $authGenerateService->accessToken;

        $this->createAuthDataService->execute($this->authDTO);

        return $authGenerateService;
    }

    private function setAuthByGoogle(): void
    {
//        try
//        {
//            $response = Socialite::driver('google')->stateless()->userFromToken($this->authDTO->googleAuthToken);
//
//            $this->authDTO->email = $response->email;
//        }
//        catch (\Exception $e)
//        {
//            EnvironmentException::dispatchException($e, Response::HTTP_UNAUTHORIZED);
//        }
    }
}
