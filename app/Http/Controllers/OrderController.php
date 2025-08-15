<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Add this line


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
    // Create a cart/order with items from request
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
                'total' => $subtotal, // discount can be applied later
            ]);

            return redirect("/payment/{$order->id}");
        });
    }
    // OrderController.php
public function edit(Order $order)
{
    // Only allow editing pending orders
    if ($order->status !== 'pending') {
        abort(403, 'You can only edit pending orders');
    }

    $menuItems = Menu::all(); // Get all menu items for adding new ones
    
    return view('orders.edit', compact('order', 'menuItems'));
}

public function update(Request $request, Order $order)
{
    // Validate and process the order update
    $validated = $request->validate([
        'items' => 'required|array',
        'items.*.quantity' => 'sometimes|integer|min:1',
        'new_items' => 'sometimes|array',
        'new_items.*.menu_id' => 'sometimes|exists:menu,id',
        'new_items.*.quantity' => 'sometimes|integer|min:1'
    ]);

    // Process the order update (you'll need to implement this logic)
    $order = DB::transaction(function () use ($order, $validated) {
        // Update existing items
        foreach ($validated['items'] as $itemId => $itemData) {
            if (isset($itemData['_delete'])) {
                // Remove item
                $order->items()->where('id', $itemId)->delete();
            } else {
                // Update quantity
                $order->items()->where('id', $itemId)->update([
                    'quantity' => $itemData['quantity'],
                    'line_total' => $itemData['quantity'] * $order->items()->find($itemId)->unit_price
                ]);
            }
        }
        
        // Add new items
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
        
        // Recalculate order totals
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
        // Calculate total first
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

        // Create the order with total_price
        $order = Order::create([
            'user_id' => $user->id,
            'status' => 'pending',
            'total_price' => $totalPrice
        ]);

        // Add order items with prices
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
    // Verify the authenticated user owns this order
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Load the order with its items and menu information
    $order->load(['items.menu']);

    return view('orders.show', compact('order'));
}
public function reorder(Request $request, Order $order)
{
    // Verify the authenticated user owns this order
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Load the order items with menu information
    $order->load(['items.menu']);

    return DB::transaction(function () use ($order, $request) {
        // Create a new order based on the previous one
        $newOrder = Order::create([
            'user_id' => $request->user()->id,
            'status' => 'pending',
            'total_price' => $order->total_price,
        ]);

        // Copy all items from the original order
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
    // Verify the authenticated user owns this order
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    // Only allow canceling pending orders
    if ($order->status !== 'pending') {
        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Only pending orders can be canceled');
    }

    return DB::transaction(function () use ($order) {
        // Update order status
        $order->status = 'cancelled';
        $order->save();

        // Record cancellation in payment history if payment exists
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