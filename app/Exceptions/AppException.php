<?php

namespace App\Exceptions;

use App\Shared\Enums\EnvironmentEnum;
use App\Shared\Enums\MessagesEnum;
use Exception;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class AppException extends Exception
{
    private mixed $options;

    public function __construct(
        $message,
        $code = 0,
        Exception $previous = null,
        $options = []
    )
    {
        parent::__construct(json_encode($message), $code, $previous);

        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @throws AppException
     */
    public static function dispatchByEnvironment(Exception $e, int $httpStatus = null)
    {
        $info = App::environment([EnvironmentEnum::LOCAL->value])
            ? $e->getMessage()
            : MessagesEnum::INTERNAL_SERVER_ERROR;

        throw new self(
            $info,
            !is_null($httpStatus) ? $httpStatus : Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}

