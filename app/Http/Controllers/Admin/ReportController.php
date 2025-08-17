<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Comment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    
    public function ordersReport()
    {
        $orders = Order::with(['user', 'items.menu'])
                      ->latest()
                      ->paginate(10);

        return view('admin.reports.orders', compact('orders'));
    }

    
    public function popularFood()
    {
        $popularItems = Menu::withCount(['orderItems as orders_count' => function($query) {
                            $query->whereHas('order', function($q) {
                                $q->where('status', 'completed');
                            });
                        }])
                        ->orderBy('orders_count', 'desc')
                        ->take(10)
                        ->get();

        return view('admin.reports.popular', compact('popularItems'));
    }


    public function paymentReport()
    {
        $payments = Payment::with(['order', 'order.user'])
                          ->latest()
                          ->paginate(10);

        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        return view('admin.reports.payments', [
            'payments' => $payments,
            'totalRevenue' => $totalRevenue
        ]);
    }

 
    public function pendingComments()
    {
        $comments = Comment::with(['user', 'menu'])
                         ->where('approved', false)
                         ->latest()
                         ->paginate(10);

        return view('admin.reports.comments', compact('comments'));
    }


    public function approveComment(Comment $comment)
    {
        $comment->update(['approved' => true]);

        return back()->with('success', 'Comment approved successfully');
    }


    public function rejectComment(Comment $comment)
    {
        $comment->delete();

        return back()->with('success', 'Comment rejected successfully');
    }
}