<?php

namespace Database\Seeders;

use App\Models\Day;
use App\Models\Goal;
use App\Models\Habit;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class HabitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $habit = Habit::create([
            'name' => 'Test Habit',
            'user_id' => 6,
        ]);

        $date = Carbon::create(2020, 01, 01);

        for ($i = 0; $i < 50; $i++) {
            $goal = new Goal;

            $goal->habit_id = $habit->id;
            $goal->goal = $i + rand(1, 5);
            $goal->created_at = $date;

            $date = $date->addDays(rand(0, $i + 7));

            $goal->finished_at = $date;

            $goal->save();

            $result = floor($goal->finished_at->floatDiffInDays($goal->created_at));

            if ($result) {
                if ($result < $goal->goal) {
                    Day::incrementStarsOrCreate('silver', $goal->habit_id, $result);
                } else {
                    Day::incrementStarsOrCreate('golden', $goal->habit_id, $result);
                }
            }
        }
    }
}


