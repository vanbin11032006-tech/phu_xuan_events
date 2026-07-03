<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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

    /**
     * Render an exception into an HTTP response.
     * For API requests, always return clean JSON instead of HTML.
     */
    public function render($request, Throwable $exception)
    {
        // Only intercept API requests (those that expect JSON)
        if ($request->expectsJson() || $request->is('api/*')) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Convert exceptions to standardised JSON responses for API callers.
     */
    private function handleApiException($request, Throwable $exception)
    {
        // 401 – Unauthenticated
        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn cần đăng nhập để tiếp tục.',
            ], 401);
        }

        // 403 – Forbidden / Unauthorised
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thực hiện thao tác này.',
            ], 403);
        }

        // 404 – Model not found
        if ($exception instanceof ModelNotFoundException) {
            $model = class_basename($exception->getModel());
            return response()->json([
                'success' => false,
                'message' => "Không tìm thấy tài nguyên ({$model}).",
            ], 404);
        }

        // 422 – Validation error
        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ. Vui lòng kiểm tra lại.',
                'errors' => $exception->errors(),
            ], 422);
        }

        // 500 – Generic server error (hide stack trace in production)
        $message = config('app.debug')
            ? $exception->getMessage()
            : 'Lỗi máy chủ nội bộ. Vui lòng thử lại sau.';

        return response()->json([
            'success' => false,
            'message' => $message,
        ], 500);
    }
}
