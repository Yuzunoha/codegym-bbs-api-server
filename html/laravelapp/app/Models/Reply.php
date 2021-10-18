<?php

namespace App\Models;

class Reply extends ModelBase
{
    protected $fillable = [
        'thread_id',
        'number',
        'user_id',
        'text',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
