<?php

namespace App\Models;

use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
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
        'subject_id',
        'class_id',
        'date',
        'attendance',
        'recorded_by'
    ];
    /**
     * The attributes that should be type cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'attendance' => 'array',
        'date' => 'date',
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
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relationship with Teacher who recorded
     */
    public function recordedBy()
    {
        return $this->belongsTo(Teacher::class, 'recorded_by');
    }
}
