<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exams extends Model
{
    use SoftDeletes, LogsActivity;
    /**
     * Constants for exam types.
     */
    const TYPE_WEEKLY = 1;
    const TYPE_MONTHLY = 2;
    const TYPE_QUARTERLY = 3;
    const TYPE_SEMESTER = 4;
    const TYPE_FINAL = 5;

    public static $types = [
        self::TYPE_WEEKLY => 'Weekly Test',
        self::TYPE_MONTHLY => 'Monthly Test',
        self::TYPE_QUARTERLY => 'Quarterly Exam',
        self::TYPE_SEMESTER => 'Semester Exam',
        self::TYPE_FINAL => 'Final Exam'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled via create() / update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'class_id',
        'subject_id',
        'exam_date',
        'type',
        'additional_information'
    ];
    /**
     * The attributes that should be type cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'exam_date' => 'date',
        'additional_information' => 'array',
        'type' => 'integer',
    ];
    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    /**
     * Relationship with Class
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }
    /**
     * Relationship with Results
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }
    /**
     * The attributes that should be appended to the model's array form.
     *
     * These are not stored in the database but are computed properties.
     *
     * @var array<int, string>
     */
    protected $appends = ['type_label', 'type_badge_class'];

    public function getTypeLabelAttribute()
    {
        return self::$types[$this->type] ?? 'Unknown';
    }

    public function getTypeBadgeClassAttribute()
    {
        return match ($this->type) {
            self::TYPE_WEEKLY => 'info',
            self::TYPE_MONTHLY => 'secondary',
            self::TYPE_QUARTERLY => 'warning',
            self::TYPE_SEMESTER => 'primary',
            self::TYPE_FINAL => 'danger',
            default => 'secondary',
        };
    }
}
