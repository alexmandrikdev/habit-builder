<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGoalRequest;
use App\Http\Requests\UpdateGoalRequest;
use App\Models\Day;
use App\Models\Goal;

class GoalController extends Controller
{
    public function store(StoreGoalRequest $request): Goal
    {
        return Goal::create([
            'habit_id' => $request->habit_id,
            'goal' => $request->goal,
        ]);
    }

    public function update(UpdateGoalRequest $request): void
    {
        $goal = Goal::where([
            'habit_id' => $request->habit_id,
            'finished_at' => null
        ])
            ->first();

        $goal->update([
            'finished_at' => now(),
        ]);

        $result = floor($goal->finished_at->floatDiffInDays($goal->created_at));

        if (!$result) {
            return;
        }

        if ($result < $goal->goal) {
            Day::incrementStarsOrCreate('silver', $goal->habit_id, $result);
        } else {
            Day::incrementStarsOrCreate('golden', $goal->habit_id, $result);
        }
    }
}
