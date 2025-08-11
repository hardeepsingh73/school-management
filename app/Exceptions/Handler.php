<?php

namespace App\Exceptions;

use App\Helpers\Settings;
use App\Models\ErrorLog;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler
{
    public function report(Throwable $e): void
    {
        // Skip logging for these exceptions:
        if (
            $e instanceof AuthenticationException ||
            $e instanceof ValidationException ||
            $e instanceof NotFoundHttpException ||
            ($e instanceof HttpException && $e->getStatusCode() < 500) // skip 4xx HTTP errors
            || (!Settings::get('error_logs', true))
        ) {
            // Don't log these exceptions to DB, but still call parent for default logging if needed
            parent::report($e);
            return;
        }

        try {
            ErrorLog::create([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => request()?->fullUrl() ?? 'N/A',
                'method' => request()?->method() ?? 'N/A',
                'error_type' => get_class($e),
            ]);
        } catch (\Exception $loggingException) {
            Log::error('❌ Failed to log error to database: ' . $loggingException->getMessage());
        }

        parent::report($e);
    }
}
