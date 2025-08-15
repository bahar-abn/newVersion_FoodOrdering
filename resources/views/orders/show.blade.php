@extends('layouts.app')

@section('title', 'Order #' . $order->id)
@section('content')
<div class="bg-gradient-to-r from-blue-50 to-indigo-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="md:w-2/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                            <p class="text-gray-500">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-sm font-medium 
                            @if($order->status == 'completed') bg-green-100 text-green-800
                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Delivery Information</h2>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700"><strong>Address:</strong> {{ $order->delivery_address }}</p>
                            <p class="text-gray-700"><strong>Phone:</strong> {{ $order->phone_number }}</p>
                            @if($order->special_instructions)
                                <p class="text-gray-700"><strong>Instructions:</strong> {{ $order->special_instructions }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Order Items</h2>
                        <div class="divide-y divide-gray-200">
                            @foreach($order->items as $item)
                            <div class="py-4 flex justify-between items-center">
                                <div class="flex items-center">
                                    @if($item->menu->image)
                                        <img src="{{ asset('storage/' . $item->menu->image) }}" alt="{{ $item->menu->name }}" class="w-16 h-16 object-cover rounded-md">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-gray-400">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="ml-4">
                                        <h3 class="text-sm font-medium text-gray-900">{{ $item->menu->name }}</h3>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <p class="text-sm font-medium text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="md:w-1/3">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                        
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-green-600">Discount</span>
                            <span class="text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        
                        <div class="border-t border-gray-200 pt-3 mt-3">
                            <div class="flex justify-between">
                                <span class="font-bold">Total</span>
                                <span class="font-bold">${{ number_format($order->total_amount - $order->discount_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($order->paymentHistory)
                    <div class="mt-6">
                        <h2 class="text-lg font-medium text-gray-900 mb-2">Payment Information</h2>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700"><strong>Status:</strong> 
                                <span class="capitalize 
                                    @if($order->paymentHistory->status == 'success') text-green-600
                                    @else text-red-600 @endif">
                                    {{ $order->paymentHistory->status }}
                                </span>
                            </p>
                            <p class="text-gray-700"><strong>Amount:</strong> ${{ number_format($order->paymentHistory->amount, 2) }}</p>
                            <p class="text-gray-700"><strong>Method:</strong> {{ ucfirst($order->paymentHistory->payment_method) }}</p>
                            <p class="text-gray-700"><strong>Transaction ID:</strong> {{ $order->paymentHistory->transaction_id }}</p>
                            <p class="text-gray-700"><strong>Date:</strong> {{ $order->paymentHistory->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    @endif
                    
                    @if($order->status == 'pending' && !$order->paymentHistory)
                    <div class="mt-6">
                        <form action="{{ route('payment.complete', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Complete Payment
                            </button>
                        </form>
                    </div>
                    @endif
                    
                    @if($order->status == 'pending')
                    <div class="mt-4">
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Are you sure you want to cancel this order?')" class="w-full px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700">
                                Cancel Order
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection