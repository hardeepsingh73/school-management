<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use SoftDeletes, LogsActivity;
    /**
     * Constants for days of the week.
     */
    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    /**
     * Constants for schedule types.
     */
    const REGULAR = 1;
    const MAKEUP = 2;
    const SPECIAL = 3;

    public static $days = [
        self::SUNDAY => 'Sunday',
        self::MONDAY => 'Monday',
        self::TUESDAY => 'Tuesday',
        self::WEDNESDAY => 'Wednesday',
        self::THURSDAY => 'Thursday',
        self::FRIDAY => 'Friday',
        self::SATURDAY => 'Saturday'
    ];

    public static $scheduleTypes = [
        self::REGULAR => 'Regular Class',
        self::MAKEUP => 'Makeup Class',
        self::SPECIAL => 'Special Class'
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id',
        'teacher_id',
        'subject_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'schedule_type',
        'effective_from',
        'effective_until'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'effective_from' => 'date',
        'effective_until' => 'date'
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['day_name', 'schedule_type_label', 'duration'];

    /**
     * Relationship with Class
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relationship with Teacher
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Get day name (e.g. "Monday")
     */
    public function getDayNameAttribute()
    {
        return self::$days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Get schedule type label
     */
    public function getScheduleTypeLabelAttribute()
    {
        return self::$scheduleTypes[$this->schedule_type] ?? 'Unknown';
    }

    /**
     * Get duration in minutes
     */
    public function getDurationAttribute()
    {
        return $this->end_time->diffInMinutes($this->start_time);
    }

    /**
     * Check if timetable slot is active for given date
     */
    public function isActiveOn($date)
    {
        $date = $date instanceof \DateTime ? $date : new \DateTime($date);

        return (!$this->effective_from || $date >= $this->effective_from) &&
            (!$this->effective_until || $date <= $this->effective_until) &&
            $date->format('w') == $this->day_of_week;
    }

    /**
     * Scope for specific day
     */
    public function scopeOnDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }
}
