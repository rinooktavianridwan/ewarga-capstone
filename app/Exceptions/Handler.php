<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Services\Traits\ResponseFormatter;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ResponseFormatter;

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $modelClass = $exception->getModel();
            $modelName = class_basename($modelClass);

            $messages = [
                'Aset' => 'Data aset tidak ditemukan',
                'AsetFoto' => 'Data foto aset tidak ditemukan',
                'AsetMJenis' => 'Data jenis aset tidak ditemukan',
                'AsetMStatus' => 'Data status aset tidak ditemukan',
                'AsetPenghuni' => 'Data penghuni aset tidak ditemukan',
            ];

            $message = $messages[$modelName] ?? $exception->getMessage();

            return response()->json($this->formatResponse(false, 404, $message), 404);
        }

        if ($exception instanceof ValidationException) {
            return response()->json($this->formatResponse(false, 422, 'Validasi gagal', $exception->errors()), 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json($this->formatResponse(false, 404, 'Endpoint tidak ditemukan'), 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json($this->formatResponse(false, 401, 'Belum login, tidak dapat diakses'), 401);
        }

        return response()->json($this->formatResponse(false, 500, 'Terjadi kesalahan pada server', $exception->getMessage()), 500);
    }
}
