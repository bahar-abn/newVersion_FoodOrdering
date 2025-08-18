<?php namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function apply(Request $request, Order $order)
    {
        $request->validate([
            'discount_code' => 'required|string'
        ]);

        $discount = Discount::where('code', $request->discount_code)
    ->where(function ($q) {
        $q->whereNull('valid_to')      
          ->orWhere('valid_to', '>=', now());
    })
    ->first();


        if (!$discount) {
            return back()->withErrors(['discount_code' => 'Invalid or expired discount code']);
        }

        $order->discount_id = $discount->id;
        $order->discount_total = $discount->amount;
        $order->total = max($order->subtotal - $discount->amount, 0);
        $order->save();

        return back()->with('success', 'Discount applied successfully!');
    }
}
