<?php

namespace App\Http\Controllers;

use App\Http\Requests\HabitStoreRequest;
use App\Models\Habit;

class HabitController extends Controller
{
    public function index()
    {
        $habits = auth()->user()->habits()
            ->with('goal')
            ->withSum('days', 'silver_stars')
            ->withSum('days', 'golden_stars')
            ->withMax('days', 'day')
            ->paginate(5);

        return $habits;
    }

    public function store(HabitStoreRequest $request)
    {
        return Habit::create(array_merge(
            [
                'user_id' => auth()->id()
            ],
            $request->validated()
        ));
    }

    public function show(int $habitId)
    {
        return Habit::with(['days', 'goal'])
            ->withSum('days', 'silver_stars')
            ->withSum('days', 'golden_stars')
            ->findOrFail($habitId);
    }
}
