<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function apply(Request $request, string $code)
    {
        $orderId = $request->query('order');
        $order = Order::whereKey($orderId)->where('user_id', $request->user()->id)->firstOrFail();

        $discount = Discount::where('code', $code)->first();
        if (!$discount || !$discount->isValid()) {
            return back()->with('error', 'Invalid or expired discount code.');
        }

        $discountValue = 0;
        if ($discount->type === 'percentage') {
            $discountValue = round($order->subtotal * ($discount->percent/100), 2);
        } else {
            $discountValue = min($discount->amount, $order->subtotal);
        }

        $order->discount_total = $discountValue;
        $order->total = max(0, $order->subtotal - $discountValue);
        $order->save();

        return back()->with('success', 'Discount applied.');
    }
}