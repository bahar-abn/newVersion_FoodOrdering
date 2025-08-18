<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; 


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.menu'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
    
    public function store(Request $request)
    {
        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menu,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();

        return DB::transaction(function () use ($data, $user) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'pending',
                'subtotal' => 0,
                'discount_total' => 0,
                'total' => 0,
            ]);

            $subtotal = 0;
            foreach ($data['items'] as $line) {
                $menu = Menu::findOrFail($line['menu_id']);
                $qty = (int)$line['quantity'];
                $unit = $menu->price;
                $lineTotal = $unit * $qty;
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $qty,
                    'unit_price' => $unit,
                    'line_total' => $lineTotal,
                ]);
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal, 
            ]);

            return redirect("/payment/{$order->id}");
        });
    }
    
public function edit(Order $order)
{
    if ($order->status !== 'pending') {
        abort(403, 'You can only edit pending orders');
    }

    $menuItems = Menu::all(); 
    
    return view('orders.edit', compact('order', 'menuItems'));
}

public function update(Request $request, Order $order)
{
  
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.quantity' => 'sometimes|integer|min:1',
        'new_items' => 'sometimes|array',
        'new_items.*.menu_id' => 'sometimes|exists:menu,id',
        'new_items.*.quantity' => 'sometimes|integer|min:1'
    ]);

    
    $order = DB::transaction(function () use ($order, $validated) {
        
        foreach ($validated['items'] as $itemId => $itemData) {
            if (isset($itemData['_delete'])) {
                
                $order->items()->where('id', $itemId)->delete();
            } else {
                
                $order->items()->where('id', $itemId)->update([
                    'quantity' => $itemData['quantity'],
                    'line_total' => $itemData['quantity'] * $order->items()->find($itemId)->unit_price
                ]);
            }
        }
        
        
        if (isset($validated['new_items'])) {
            foreach ($validated['new_items'] as $newItem) {
                $menu = Menu::find($newItem['menu_id']);
                $order->items()->create([
                    'menu_id' => $menu->id,
                    'quantity' => $newItem['quantity'],
                    'unit_price' => $menu->price,
                    'line_total' => $menu->price * $newItem['quantity']
                ]);
            }
        }
        
        
        $order->recalculateTotals();
        
        return $order;
    });

    return redirect()->route('orders.show', $order->id)
                   ->with('success', 'Order updated successfully');
}
public function directOrder(Request $request)
{
    $validated = $request->validate([
        'items' => 'required|array|min:1',
        'items.*.menu_id' => 'required|exists:menus,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    $user = $request->user();

    return DB::transaction(function () use ($validated, $user) {
       
        $totalPrice = 0;
        $itemsWithPrices = [];
        
        foreach ($validated['items'] as $itemData) {
            $menu = Menu::findOrFail($itemData['menu_id']);
            $itemPrice = $menu->price;
            $totalPrice += $itemPrice * $itemData['quantity'];
            $itemsWithPrices[] = [
                'menu' => $menu,
                'quantity' => $itemData['quantity'],
                'price' => $itemPrice
            ];
        }

        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => $totalPrice
        ]);

        foreach ($itemsWithPrices as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_id' => $item['menu']->id,
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        return redirect()->route('payment.form', $order->id)
            ->with('success', 'Order created successfully!');
    });
}
public function show(Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $order->load(['items.menu']);

    return view('orders.show', compact('order'));
}
public function reorder(Request $request, Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    $order->load(['items.menu']);

    return DB::transaction(function () use ($order, $request) {
       
        $newOrder = Order::create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'total_price' => $order->total_price,
        ]);

        foreach ($order->items as $item) {
            OrderItem::create([
                'order_id' => $newOrder->id,
                'menu_id' => $item->menu_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ]);
        }

        return redirect()->route('orders.show', $newOrder->id)
            ->with('success', 'Order has been recreated successfully!');
    });
}
public function cancel(Request $request, Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    if ($order->status !== 'pending') {
        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Only pending orders can be canceled');
    }

    return DB::transaction(function () use ($order) {
        $order->status = 'cancelled';
        $order->save();

        if ($order->paymentHistory) {
            PaymentHistory::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'status' => 'cancelled',
                'gateway_ref' => 'CANCEL-'.uniqid(),
                'amount' => $order->total_price,
                'meta' => ['cancelled_by' => 'user']
            ]);
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order has been cancelled');
    });
}
}