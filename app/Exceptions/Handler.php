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
    /**
     * Report or log an exception.
     *
     * This method is called automatically whenever an exception occurs in the application.
     * Here, we add logic to log certain exceptions into the database (ErrorLog model),
     * while skipping common exceptions like authentication/validation/4xx HTTP errors.
     *
     * @param Throwable $e The exception that occurred.
     */
    public function report(Throwable $e): void
    {
        /**
         * Skip logging certain exceptions into the database:
         * - AuthenticationException → User is not logged in
         * - ValidationException → Form/request validation failed
         * - NotFoundHttpException → 404 errors
         * - HttpException with status < 500 → Client-side errors (4xx)
         * - If 'error_logs' setting is disabled in application settings
         */
        if (
            $e instanceof AuthenticationException ||
            $e instanceof ValidationException ||
            $e instanceof NotFoundHttpException ||
            ($e instanceof HttpException && $e->getStatusCode() < 500) ||
            (!Settings::get('error_logs', true))
        ) {
            // Call default Laravel logging; skip DB logging
            parent::report($e);
            return;
        }

        /**
         * Try to store the exception details in the database.
         * We log:
         *  - Error message
         *  - Stack trace
         *  - File & line where the error occurred
         *  - Request URL & method at the time of the error
         *  - Type of exception (class name)
         */
        try {
            ErrorLog::create([
                'message'     => $e->getMessage(),
                'trace'       => $e->getTraceAsString(),
                'file'        => $e->getFile(),
                'line'        => $e->getLine(),
                'url'         => request()?->fullUrl() ?? 'N/A',
                'method'      => request()?->method() ?? 'N/A',
                'error_type'  => get_class($e),
            ]);
        } catch (\Exception $loggingException) {
            /**
             * If DB logging fails (e.g., DB connection down),
             * log the failure into Laravel's default log system
             * so the error is not silently ignored.
             */
            Log::error(' Failed to log error to database: ' . $loggingException->getMessage());
        }

        // Always call the parent report method to allow Laravel’s normal logging process.
        parent::report($e);
    }
}
