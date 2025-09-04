<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use SoftDeletes, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id',
        'exam_id',
        'class_id',
        'total_marks',
        'obtained_marks',
        'grade',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_marks' => 'decimal:2',
        'obtained_marks' => 'decimal:2',
    ];

    /**
     * Relationship with Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Relationship with Exam
     */
    public function exam()
    {
        return $this->belongsTo(Exams::class);
    }

    /**
     * Relationship with Class
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relationship with Creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Calculate percentage if not set
     */
    public function getPercentageAttribute()
    {
        if ($this->total_marks > 0) {
            return (($this->obtained_marks / $this->total_marks) * 100) . '%';
        }
        return 0;
    }
}
