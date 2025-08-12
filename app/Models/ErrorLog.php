<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErrorLog extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * These represent columns in the 'error_logs' table
     * that can be bulk filled using create() or update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'message',  // Error message text
        'trace',    // Stack trace of the error (string / text format)
        'file',     // File path or name where the error occurred
        'line',     // Line number in the file where error triggered
        'url',      // URL the user was visiting when error occurred
        'method',   // HTTP method used for the request (GET/POST/PUT/DELETE)
    ];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     |
     | If ErrorLog entries are related to any models (e.g., User who caused the error),
     | you can define Eloquent relationships here.
     | Example:
     | public function user()
     | {
     |     return $this->belongsTo(User::class, 'user_id');
     | }
     |
     */

    /*
     |--------------------------------------------------------------------------
     | Scopes
     |--------------------------------------------------------------------------
     |
     | Query scopes can be added here for reusable filtering, such as:
     | - Filtering by date range
     | - Searching by keyword
     |
     | Example:
     | public function scopeRecent($query)
     | {
     |     return $query->orderBy('created_at', 'desc');
     | }
     |
     */
}
