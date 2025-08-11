<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'event',
        'description',
        'subject_id',
        'subject_type',
        'causer_id',
        'causer_type',
        'properties',
        'ip_address',
        'user_agent',
        'url',
        'method'
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function causer()
    {
        return $this->morphTo();
    }

    public function scopeForSubject($query, $subject)
    {
        return $query->where('subject_id', $subject->id)
            ->where('subject_type', get_class($subject));
    }

    public function scopeForCauser($query, $causer)
    {
        return $query->where('causer_id', $causer->id)
            ->where('causer_type', get_class($causer));
    }
}
