<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Exception;
use Throwable;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }


    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');

            return $this->handleApiException($request, $exception);
        } else if ($exception instanceof HttpException && $exception->getStatusCode() == 403) {
            return response()->view('errors.403', [], 403);
        } else {
            $retval = parent::render($request, $exception);
        }

        return $retval;
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            $exception = $exception->getResponse();
        }

        if ($exception instanceof AuthenticationException) {
            $exception = $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $exception = $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $code = $exception->getStatusCode();
        } else {
            $code = 500;
        }

        $response = [
            'code' => $code,
            'status' => 'error',
            'message' => 'Something went wrong',
            'errors' => null
        ];

        switch ($code) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Not Found';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
                $response['message'] = 'Validation Failed';
                if (isset($exception->original['errors'])) {
                    $response['errors'] = $exception->original['errors'];
                }
                $code = 422;
                break;
            case 500:
                $response['message'] = 'Internal Server Error';
                if (property_exists($exception, 'original') && isset($exception->original['errors'])) {
                    $response['errors'] = $exception->original['errors'];
                }
                break;
        }

        return response()->json($response, $code);
    }
}
