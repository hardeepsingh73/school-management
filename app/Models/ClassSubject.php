<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassSubject extends Pivot
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id',
        'subject_id',
        'teacher_id',
    ];

    /**
     * Relationship with Class
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Relationship with Teacher
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Scope for teacher assignments
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Get formatted class-subject name
     */
    public function getDisplayNameAttribute()
    {
        return $this->schoolClass->full_name . ' - ' . $this->subject->name;
    }

    /**
     * Assign teacher to this class-subject combination
     */
    public function assignTeacher($teacherId)
    {
        $this->update(['teacher_id' => $teacherId]);
        return $this;
    }
    /**
     * Relationship with SchoolClass
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
