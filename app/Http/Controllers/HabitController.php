<?php

namespace App\Http\Controllers;

use App\Http\Requests\HabitStoreRequest;
use App\Models\Habit;

class HabitController extends Controller
{
    public function index()
    {
        $habits = auth()->user()->habits()
            ->withSum('days', 'silver_stars')
            ->withSum('days', 'golden_stars')
            ->paginate(5);

        return $habits;
    }

    public function store(HabitStoreRequest $request)
    {
        $habit = Habit::create(array_merge(
            [
                'user_id' => auth()->id()
            ],
            $request->validated()
        ));

        $habit->days()->create([
            'day' => 1
        ]);

        return $habit;
    }
}
