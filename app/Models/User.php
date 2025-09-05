<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use LogsActivity, HasFactory, Notifiable, HasRoles, HasApiTokens, SoftDeletes;

    /**
     * Mass assignable attributes.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'dob',
        'address',
        'blood_group',
        'phone',
        'profile_image_id',
        'status',
    ];

    /**
     * Hidden attributes for arrays and JSON.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute type casting.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'integer',
        ];
    }
    /**
     * Relationship with LoginHistory
     */

    public function loginHistory()
    {
        return $this->hasMany(LoginHistory::class);
    }
    /**
     * Relationship with Student
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }
    /**
     * Relationship with Teacher
     */
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    /**
     * Relation to the profile image file record.
     */
    public function profileImage()
    {
        return $this->belongsTo(File::class, 'profile_image_id');
    }

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    /**
     * Status labels.
     *
     * @var array<int, string>
     */
    public static $statuses = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_INACTIVE => 'Inactive',
    ];
    /**
     * Check if student is active
     */
    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['status_label', 'status_badge_class'];

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return self::$statuses[$this->status] ?? 'Unknown';
    }
    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match ($this->status) {
            self::STATUS_ACTIVE  => 'bg-success',
            self::STATUS_INACTIVE => 'bg-warning text-dark',
            default => 'bg-secondary',
        };
    }
}
