<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $items = Menu::with(['comments' => fn($q) => $q->latest()->limit(3)])->paginate(10);
        return view('menu.index', compact('items'));
    }

    public function create()
    {
        return view('menu.create');
    }
    public function show(Menu $menu)
{
    // Remove the approved filter
    $comments = $menu->comments()->latest()->get();
    $surveys = $menu->surveys()->latest()->take(5)->get();
    
    return view('menu.show', compact('menu', 'comments', 'surveys'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        Menu::create($data);
        return redirect('/menu')->with('success', 'Food created');
    }

    public function edit(Menu $menu)
    {
        return view('menu.edit', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);
        $menu->update($data);
        return redirect('/menu')->with('success', 'Food updated');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect('/menu')->with('success', 'Food deleted');
    }
}