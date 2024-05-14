<?php

namespace App\Features\Base\ACL;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class Policy
{
    private array $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @throws AppException
     */
    public function havePermission(string|null $rule = null
    ): void
    {
        if(!$this->haveRule($rule)) {
            $this->dispatchForbiddenError();
        }
    }

    /**
     * @param string $rule
     * @return bool
     */
    public function haveRule(string $rule): bool
    {
        return in_array($rule, $this->rules);
    }

    /**
     * @throws AppException
     */
    public function dispatchForbiddenError()
    {
        throw new AppException(
            MessagesEnum::NOT_AUTHORIZED,
            Response::HTTP_FORBIDDEN
        );
    }
}
