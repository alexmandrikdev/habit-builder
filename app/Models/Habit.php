<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Habit extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'user_id'];

    public function days()
    {
        return $this->hasMany(Day::class);
    }

    public function goal()
    {
        return $this->hasOne(Goal::class)->whereNull('finished_at');
    }
}
