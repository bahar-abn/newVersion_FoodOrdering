@extends('layouts.app')

@section('title', 'My Orders')
@section('content')
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-indigo-700">My Orders</h1>
            <div class="flex space-x-4">
                <a href="{{ route('menu.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                    Browse Menu
                </a>
                <a href="{{ route('cart.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-700 bg-white hover:bg-gray-50">
                    View Cart
                </a>
            </div>
        </div>
        
        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No orders yet</h3>
                <p class="mt-1 text-gray-500">Start ordering from our delicious menu!</p>
                <div class="mt-6">
                    <a href="{{ route('menu.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                        Browse Menu
                    </a>
                </div>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-medium 
                                @if($order->status == 'completed') bg-green-100 text-green-800
                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        
                        <div class="mt-4">
                            <h4 class="font-medium text-gray-900">Items</h4>
                            <ul class="mt-2 divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <li class="py-3 flex justify-between">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $item->menu->name }}</p>
                                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-between">
                                <p class="text-base font-medium text-gray-900">Subtotal</p>
                                <p class="text-base font-medium text-gray-900">${{ number_format($order->total_amount, 2) }}</p>
                            </div>
                            @if($order->discount_amount > 0)
                            <div class="flex justify-between mt-1">
                                <p class="text-sm font-medium text-green-600">Discount</p>
                                <p class="text-sm font-medium text-green-600">-${{ number_format($order->discount_amount, 2) }}</p>
                            </div>
                            @endif
                            <div class="flex justify-between mt-2">
                                <p class="text-lg font-bold text-gray-900">Total</p>
                                <p class="text-lg font-bold text-gray-900">${{ number_format($order->total_amount - $order->discount_amount, 2) }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end space-x-4">
                            <form action="{{ route('orders.reorder', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 border border-indigo-600 rounded-md shadow-sm text-sm font-medium text-indigo-600 bg-white hover:bg-indigo-50">
                                    Buy Again
                                </button>
                            </form>
                            <a href="{{ route('orders.show', $order->id) }}" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection