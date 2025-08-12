<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoginHistory extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * The associated table for the model.
     *
     * We explicitly define the table name in case it does not follow
     * Laravel's default naming convention (plural snake_case).
     *
     * @var string
     */
    protected $table = 'login_history';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',     // ID of the user who logged in
        'ip_address',  // IP from which the user logged in
        'user_agent',  // Browser/device string of the login session
        'login_at',    // Timestamp when the login happened
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * 'login_at' will automatically be converted to a Carbon datetime instance.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'login_at' => 'datetime',
    ];

    /**
     * Get the user associated with this login history record.
     *
     * This defines an inverse one-to-many relationship:
     * A single login record belongs to exactly one user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
