<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Traits\ResponseFormatter;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseFormatter;

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
        if ($exception instanceof ModelNotFoundException) {
            return response()->json($this->formatResponse(false, 404, 'Data tidak ditemukan'), 404);
        }

        if ($exception instanceof ValidationException) {
            return response()->json($this->formatResponse(false, 422, 'Validasi gagal', $exception->errors()), 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json($this->formatResponse(false, 404, 'Endpoint tidak ditemukan'), 404);
        }

        return response()->json($this->formatResponse(false, 500, 'Terjadi kesalahan pada server', $exception->getMessage()), 500);
    }
}
