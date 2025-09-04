<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes, LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'department_id',
    ];

    /**
     * Relationship with Department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Relationship with Classes (many-to-many)
     */
    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')->withTimestamps();
    }

    /**
     * Relationship with Teachers (many-to-many)
     */
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'teacher_subject', 'subject_id', 'teacher_id')->withTimestamps();
    }
}
