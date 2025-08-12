<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

/**
 * Class ApiLog
 *
 * Stores detailed logs of API requests and responses made by users.
 * Includes:
 * - Soft deletion functionality (keeps deleted logs with a deleted_at timestamp).
 * - Activity logging through the custom LogsActivity trait.
 *
 * This model is useful for debugging, auditing, and performance monitoring.
 *
 * @package App\Models
 *
 * @property int                          $id              Primary key.
 * @property int|null                     $user_id         The ID of the user who made the API call (nullable for guests).
 * @property string                       $method          HTTP method used (GET, POST, etc.).
 * @property string                       $endpoint        API endpoint called.
 * @property array|null                   $request_headers Headers sent in the request.
 * @property array|null                   $request_body    Request payload (body).
 * @property int|null                     $response_status HTTP status code returned.
 * @property array|null                   $response_body   Response payload.
 * @property string|null                  $ip_address      IP address of the requester.
 * @property float|null                   $execution_time  Time taken to execute the request (in seconds or ms).
 * @property \Carbon\Carbon|null          $created_at      Timestamp when the log was created.
 * @property \Carbon\Carbon|null          $updated_at      Timestamp when the log was last updated.
 * @property \Carbon\Carbon|null          $deleted_at      Timestamp when the log was soft deleted.
 *
 * @property-read \App\Models\User|null   $user            The related user model.
 */
class ApiLog extends Model
{
    /**
     * Enables soft deletion to retain API logs even after deletion.
     */
    use SoftDeletes;

    /**
     * Logs model events (create, update, delete) for auditing purposes.
     */
    use LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'method',
        'endpoint',
        'request_headers',
        'request_body',
        'response_status',
        'response_body',
        'ip_address',
        'execution_time',
    ];

    /**
     * The attributes that should be type cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'request_headers' => 'array', // Automatically convert to/from JSON
        'request_body'    => 'array',
        'response_body'   => 'array',
    ];

    /**
     * Get the user associated with this API log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        // Defines inverse one-to-many relationship: Many logs belong to one user
        return $this->belongsTo(User::class);
    }
}
