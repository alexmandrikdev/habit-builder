<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $guarded = ['id'];

    protected $hidden = ['created_at', 'updated_at', 'habit_id'];

    public static function incrementStarsOrCreate(string $starType, int $habitId, $resultDay): void
    {
        if (!collect(['silver', 'golden'])->contains($starType)) {
            throw new Exception("Invalid start type! Valid start types: silver or golden", 1);
        }

        $day = static::where([
            'habit_id' => $habitId,
            'day' => $resultDay,
        ])->first();

        if ($day) {
            $day->increment($starType . '_stars');
        } else {
            static::create([
                'habit_id' => $habitId,
                'day' => $resultDay,
                $starType . '_stars' => 1
            ]);
        }
    }
}
