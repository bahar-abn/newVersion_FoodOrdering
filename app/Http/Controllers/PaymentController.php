<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function process(Request $request, Order $order)
{
    if ($order->total_price <= 0) {
        return back()->with('error', 'Order total must be greater than zero');
    }

    $outcomes = ['success', 'failed', 'cancelled'];
    $status = $outcomes[array_rand($outcomes)];
    PaymentHistory::create([
        'order_id' => $order->id,
        'user_id' => $request->user()->id,
        'status' => $status,
        'gateway_ref' => 'GATE-'.uniqid(),
        'amount' => $order->total_price,
        'meta' => ['simulated' => true],
    ]);

    if ($status === 'success') {
        $order->status = 'completed';
        $order->save();
    }

    return redirect()->route('orders.show', $order->id)
        ->with('status', "Payment {$status}");
}

    public function result(string $status, Order $order)
    {
        return view('payment.result', compact('status','order'));
    }

    private function authorizeOrder(Request $request, Order $order): void
    {
        if ($order->user_id !== $request->user()->id) abort(403);
        if (!in_array($order->status, ['pending'])) abort(400, 'Order not payable.');
    }
    public function complete(Request $request, Order $order)
{
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }
    if ($order->status !== 'pending') {
        return redirect()->route('orders.show', $order->id)
            ->with('error', 'Order cannot be completed in its current state');
    }

    return DB::transaction(function () use ($order, $request) {
        $outcomes = ['success', 'failed', 'cancelled'];
        $status = $outcomes[array_rand($outcomes)];

        PaymentHistory::create([
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'status' => $status,
            'gateway_ref' => 'PAY-' . uniqid(),
            'amount' => $order->total_price,
            'meta' => ['simulated' => true],
        ]);

        $order->status = $status === 'success' ? 'completed' : $status;
        $order->save();

        return redirect()->route('orders.show', $order->id)
            ->with('status', "Payment {$status}");
    });
}
public function show(Order $order)
{
    
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Unauthorized action.');
    }

    
    $order->load('items'); 
    $items = $order->items; 
    $total = $order->total ?? 0;

    return view('payment.form', compact('order', 'items', 'total'));
}

}