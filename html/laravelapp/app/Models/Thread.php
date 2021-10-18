<?php

namespace App\Models;

class Thread extends ModelBase
{
    protected $fillable = [
        'user_id',
        'title',
        'ip_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
