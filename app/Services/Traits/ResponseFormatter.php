<?php

namespace App\Services\Traits;

trait ResponseFormatter
{
    protected function formatResponse(bool $status, int $kode, string $message, $data = null)
    {
        return [
            'status' => $status,
            'kode' => $kode,
            'message' => $message,
            'data' => $data
        ];
    }
}
