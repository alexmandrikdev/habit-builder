<?php

namespace App\Http\Controllers;

use App\Http\Requests\HabitStoreRequest;
use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    public function index()
    {
        return auth()->user()->habits()->paginate(5);
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
