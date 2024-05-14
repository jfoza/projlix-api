<?php

use App\Exceptions\AppException;
use App\Exceptions\HandlerExceptions;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withCommands([
        \App\Console\Commands\ClearPolicyCache::class
    ])
    ->withMiddleware(function (Middleware $middleware) {})
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(Throwable $e) {
            return match (get_class($e))
            {
                MethodNotAllowedHttpException::class => HandlerExceptions::returnMethodNotAllowedHttpException(),
                NotFoundHttpException::class         => HandlerExceptions::returnNotFoundHttpException(),
                QueryException::class                => HandlerExceptions::returnQueryException($e),
                AppException::class                  => HandlerExceptions::returnAppException($e),
                UnauthorizedHttpException::class     => HandlerExceptions::returnUnauthorizedHttpException(),
                ThrottleRequestsException::class     => HandlerExceptions::returnThrottleRequestsException(),
                default                              => HandlerExceptions::returnDefaultException($e)
            };
        });
    })->create();
