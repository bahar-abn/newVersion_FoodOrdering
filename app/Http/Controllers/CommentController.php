<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Menu $food)
    {
        $data = $request->validate(['body' => 'required|string|max:2000']);
        Comment::create([
            'user_id' => $request->user()->id,
            'menu_id' => $food->id,
            'body' => $data['body'],
        ]);
        return back()->with('success', 'Comment added');
    }
}