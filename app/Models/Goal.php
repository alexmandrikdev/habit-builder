<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['updated_at'];

    protected $casts = [
        'finished_at' => 'datetime',
    ];
}
