<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{

    use SoftDeletes, LogsActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'head_teacher_id',
    ];
    /**
     * Relationship with Head Teacher
     */
    public function headTeacher()
    {
        return $this->belongsTo(Teacher::class, 'head_teacher_id');
    }
    /**
     * Relationship with Teachers
     */
    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }
    /**
     * Relationship with Exams
     */
    public function exams()
    {
        return $this->hasMany(Exams::class);
    }
}
