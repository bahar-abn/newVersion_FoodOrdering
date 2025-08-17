<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function rate(Request $request, Menu $food)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string',
        ]);

        Survey::updateOrCreate(
            ['user_id' => $request->user()->id, 'menu_id' => $food->id],
            ['rating' => $data['rating'], 'note' => $data['note'] ?? null]
        );

        $avg = Survey::where('menu_id', $food->id)->avg('rating');
        $food->average_rating = round($avg, 2);
        $food->save();

        return back()->with('success', 'Thanks for your rating!');
    }
}