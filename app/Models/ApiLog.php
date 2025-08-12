<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class ApiLog extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'method',
        'endpoint',
        'request_headers',
        'request_body',
        'response_status',
        'response_body',
        'ip_address',
        'execution_time',
    ];
    protected $casts = [
        'request_headers' => 'array',
        'request_body'    => 'array',
        'response_body'   => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
