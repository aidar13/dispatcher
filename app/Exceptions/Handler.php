<?php

namespace App\Exceptions;

use App\Libraries\Codes\ResponseCodes;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Module\Planning\Exceptions\CannotDeleteContainerException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @param Throwable $e
     * @return void
     * @throws Throwable
     * @psalm-suppress UndefinedClass
     */
    public function report(Throwable $e): void
    {
        if (app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e);
        }

        parent::report($e);
    }

    public function render($request, Throwable $e): Response|JsonResponse|ResponseAlias
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
                'errors'  => $e->errors(),
                'code'    => 418
            ], 418);
        }

        if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
                'errors'  => [],
                'code'    => 404
            ], 404);
        }

        if ($e instanceof \DomainException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
                'errors'  => [],
                'code'    => $e->getCode()
            ], 422);
        }

        if ($e instanceof CannotDeleteContainerException) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
                'errors'  => [],
                'code'    => $e->getCode()
            ], 409);
        }

        if ($e instanceof DomainExceptionWithErrors) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'data'    => [],
                'errors'  => [$e->getMessage()],
                'code'    => ResponseCodes::BAD_REQUEST
            ])->setStatusCode(ResponseCodes::BAD_REQUEST);
        }

        return parent::render($request, $e);
    }
}
