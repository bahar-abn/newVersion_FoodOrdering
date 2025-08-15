<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // Get cart items from session
        $cart = session()->get('cart', []);
        
        // Get menu items details
        $menuItems = [];
        $total = 0;
        
        foreach ($cart as $menuId => $quantity) {
            $menu = Menu::find($menuId);
            if ($menu) {
                $menuItems[] = [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'price' => $menu->price,
                    'quantity' => $quantity,
                    'subtotal' => $menu->price * $quantity
                ];
                $total += $menu->price * $quantity;
            }
        }

        return view('cart.index', compact('menuItems', 'total'));
    }

    public function add(Request $request)
{
    $request->validate([
        'menu_id' => 'required|exists:menus,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $menuId = $request->menu_id;
    $quantity = $request->quantity;

    $cart = session()->get('cart', []);

    if (isset($cart[$menuId])) {
        $cart[$menuId] += $quantity;
    } else {
        $cart[$menuId] = $quantity;
    }

    session()->put('cart', $cart);

    return redirect()->route('cart.index')
        ->with('success', 'Item added to cart!');
}
    public function remove($menuId)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart');
    }

    public function update(Request $request, $menuId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        $cart = session()->get('cart', []);
        
        if (isset($cart[$menuId])) {
            $cart[$menuId] = $request->quantity;
            session()->put('cart', $cart);
        }
        
        return redirect()->route('cart.index')->with('success', 'Cart updated');
    }
    
}