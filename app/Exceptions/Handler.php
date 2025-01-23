<?php

namespace App\Exceptions;

use BezhanSalleh\FilamentExceptions\FilamentExceptions;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
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
            if ($this->shouldReport($e)) {
                FilamentExceptions::report($e);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            // Return a JSON response instead of a view
            return response()->json([
                'message' => 'Too many requests. Please try again later.'
            ], 429); // 429 is the HTTP status code for Too Many Requests
        }

        return parent::render($request, $exception);
    }

}
