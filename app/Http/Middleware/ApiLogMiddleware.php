<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Start timing
        $start = microtime(true);

        // Pass request to the next step
        $response = $next($request);

        try {
            ApiLog::create([
                'user_id'         => auth()->id(),
                'method'          => $request->method(),
                'endpoint'        => $request->getRequestUri(),
                'request_headers' => $request->headers->all(),
                'request_body'    => $this->filterSensitiveData($request->all()),
                'response_status' => $response->getStatusCode(),
                'response_body'   => $this->getResponseContent($response),
                'ip_address'      => $request->ip(),
                'execution_time'  => round(microtime(true) - $start, 4),
            ]);
        } catch (\Exception $e) {
            Log::error('API logging failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Get the response content safely.
     */
    private function getResponseContent(Response $response)
    {
        $content = $response->getContent();

        // Prevent storing huge binary files
        if (strlen($content) > 10000) {
            return substr($content, 0, 10000) . '... [TRUNCATED]';
        }

        // Try to decode JSON and store in structured format
        $decoded = json_decode($content, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $content;
    }

    /**
     * Optionally mask sensitive data from being stored.
     */
    private function filterSensitiveData(array $data)
    {
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'authorization'];

        array_walk_recursive($data, function (&$value, $key) use ($sensitiveKeys) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $value = '********';
            }
        });

        return $data;
    }
}
