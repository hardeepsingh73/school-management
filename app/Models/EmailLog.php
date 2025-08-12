<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * EmailLog Model
 *
 * Represents the 'email_logs' table.
 * Used for storing and retrieving logs of sent (or failed) emails.
 */
class EmailLog extends Model
{
    /**
     * The attributes that are mass assignable.
     * Only these fields can be filled via mass assignment (e.g., EmailLog::create()).
     *
     * @var array
     */
    protected $fillable = [
        'to',        // Recipient email address(es)
        'subject',   // Email subject line
        'body',      // Email content/body
        'status',    // 'sent' or 'failed'
    ];

    /**
     * Indicates if the model should be timestamped.
     * Default is true, so 'created_at' and 'updated_at' will be managed automatically.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that should be cast to specific types.
     * Here we are ensuring timestamps are treated as datetime objects.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime', // When the log entry was created
        'updated_at' => 'datetime', // When the log entry was last updated
    ];
}
