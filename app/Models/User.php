<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail; // Uncomment if using email verification

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Token-based authentication support
use Spatie\Permission\Traits\HasRoles; // Role & permission management

/**
 * Class User
 *
 * The application's main User model, responsible for authentication,
 * authorization, and user account management.
 *
 * Features:
 * - API authentication using Laravel Sanctum
 * - Role & permission management using Spatie Laravel Permission
 * - Activity logging via LogsActivity trait
 * - Notification sending and email handling
 */
class User extends Authenticatable
{
    use LogsActivity; // Custom trait for system activity logging
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * These fields can be set via create(), update(), or fill() without
     * additional guarded restrictions.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',         // User's full name
        'email',        // Email address (unique)
        'password',     // Hashed login password
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * These will NOT be exposed when the User model is converted to JSON/array.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',         // Never expose password hashes
        'remember_token',   // Auth "remember me" token
    ];

    /**
     * The attributes that should be type-cast.
     *
     * - email_verified_at → Casts to Carbon datetime
     * - password → Automatically hashes when set (Laravel 10+ feature)
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Carbon instance
            'password'          => 'hashed',   // Auto-hash when assigning
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    |
    | Define Eloquent relationships here, like:
    |
    | public function posts()
    | {
    |     return $this->hasMany(Post::class);
    | }
    */
    public function loginHistory()
    {
        return $this->hasMany(loginHistory::class);
    }
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    |
    | Example scope for active users:
    |
    | public function scopeActive($query)
    | {
    |     return $query->where('status', 'active');
    | }
    */
}
