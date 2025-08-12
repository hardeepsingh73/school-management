<?php

namespace App\Http\Middleware;

use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ApiLogMiddleware
 *
 * Middleware to log API requests and responses for debugging, analytics, and auditing purposes.
 *
 * Captures:
 * - Authenticated user ID (if any)
 * - HTTP method and endpoint URI
 * - Request headers and body (with sensitive fields masked)
 * - Response status code and content (truncated if overly large)
 * - Client IP address
 * - Execution time in seconds
 *
 * Data is stored in the `api_logs` table via the ApiLog model.
 *
 * Exceptions during the logging process are caught to avoid affecting API functionality.
 *
 * @package App\Http\Middleware
 */
class ApiLogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This method records request and response details and stores them in the ApiLog table.
     * 
     * @param  \Illuminate\Http\Request  $request  The current HTTP request instance.
     * @param  \Closure  $next  The next middleware/controller in the pipeline.
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        // Start timing execution
        $start = microtime(true);

        // Pass request further into the pipeline and get the response
        $response = $next($request);

        try {
            // Create a new API log entry
            ApiLog::create([
                'user_id'         => auth()->id(),
                'method'          => $request->method(),
                'endpoint'        => $request->getRequestUri(),
                'request_headers' => $request->headers->all(),
                'request_body'    => $this->filterSensitiveData($request->all()),
                'response_status' => $response->getStatusCode(),
                'response_body'   => $this->getResponseContent($response),
                'ip_address'      => $request->ip(),
                'execution_time'  => round(microtime(true) - $start, 4), // seconds with 4 decimal precision
            ]);
        } catch (\Exception $e) {
            // Prevent logging failures from breaking the API response
            Log::error('API logging failed: ' . $e->getMessage());
        }

        return $response;
    }

    /**
     * Retrieve the response content safely.
     *
     * - Decodes JSON if possible
     * - Truncates large non-binary responses to avoid DB bloat
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return mixed string|array       The decoded array (if JSON) or raw/truncated string content.
     */
    private function getResponseContent(Response $response)
    {
        $content = $response->getContent();

        // Prevent storing overly large content (e.g., binary files)
        if (strlen($content) > 10000) {
            return substr($content, 0, 10000) . '... [TRUNCATED]';
        }

        // Try decoding JSON response to store in structured form
        $decoded = json_decode($content, true);
        return json_last_error() === JSON_ERROR_NONE ? $decoded : $content;
    }

    /**
     * Filter and mask sensitive data from the request body before storing.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>  The filtered data with sensitive fields replaced by "********".
     */
    private function filterSensitiveData(array $data)
    {
        // Common sensitive keys (add more based on your app's requirements)
        $sensitiveKeys = ['password', 'password_confirmation', 'token', 'authorization'];

        array_walk_recursive($data, function (&$value, $key) use ($sensitiveKeys) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $value = '********';
            }
        });

        return $data;
    }
}
