<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use SoftDeletes, LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * These fields can be filled via create() / update().
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'section',
        'class_teacher_id',
    ];

    /**
     * The attributes that should be appended to the model's array form.
     *
     * These are not stored in the database but are computed properties.
     *
     * @var array<int, string>
     */
    protected $appends = ['full_name', 'current_student_count'];

    /**
     * Relationship with Class Teacher
     */
    public function classTeacher()
    {
        return $this->belongsTo(Teacher::class, 'class_teacher_id');
    }

    /**
     * Relationship with Students
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'school_class_id');
    }

    /**
     * Relationship with Timetables
     */
    public function timetables()
    {
        return $this->hasMany(Timetable::class, 'class_id');
    }

    /**
     * Get full class name (e.g. "Class 10-A")
     */
    public function getFullNameAttribute()
    {
        return $this->name . ($this->section ? '-' . $this->section : '');
    }

    /**
     * Get current student count
     */
    public function getCurrentStudentCountAttribute()
    {
        return $this->students()->count();
    }
    /**
     * Relationship with Subjects through ClassSubject pivot
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')->withTimestamps();
    }
}
