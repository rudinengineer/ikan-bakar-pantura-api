<?php

namespace App\Http\Helpers;

use Illuminate\Support\Facades\Log as FacadesLog;

class Log
{
    public static function error(string $message, \Throwable $error)
    {
        FacadesLog::error($message, [
            'message' => $error->getMessage(),
            'file' => $error->getFile(),
            'line' => $error->getLine(),
        ]);
    }
}
