@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6">Payment for Order #{{ $order->id }}</h1>
        
        <!-- Order Summary -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
            <div class="space-y-2">
                @foreach($order->menus as $menu)
                <div class="flex justify-between">
                    <span>{{ $menu->name }} (x{{ $menu->pivot->quantity }})</span>
                    <span>${{ number_format($menu->pivot->price * $menu->pivot->quantity, 2) }}</span>
                </div>
                @endforeach
            </div>
            
            <!-- Discount Section -->
            <div class="mt-4 border-t pt-4">
                @if(!$order->discount)
                <form action="{{ route('payment.apply-discount', $order->id) }}" method="POST" class="flex items-end gap-4 mb-4">
                    @csrf
                    <div class="flex-grow">
                        <label for="discount_code" class="block text-sm font-medium text-gray-700 mb-1">Discount Code</label>
                        <div class="flex space-x-2">
                            <input type="text" name="discount_code" id="discount_code" 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="Enter promo code"
                                   value="{{ old('discount_code') }}">
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
                                Apply Code
                            </button>
                        </div>
                        @error('discount_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </form>
                @else
                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-green-800">Discount Applied</p>
                            <p class="text-sm text-green-600">Code: {{ $order->discount->code }} (-${{ number_format($order->discount->amount, 2) }})</p>
                        </div>
                        <form action="{{ route('payment.remove-discount', $order->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                Remove
                            </button>
                        </form>
                    </div>
                </div>
                @endif
                
                <!-- Subtotal and Discount Calculation -->
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    @if($order->discount)
                    <div class="flex justify-between">
                        <span>Discount ({{ $order->discount->code }}):</span>
                        <span class="text-red-600">-${{ number_format($order->discount->amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-gray-200 pt-2 font-medium text-base text-gray-900">
                        <span>Total:</span>
                        <span>${{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Button -->
        <form action="{{ route('payment.process', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                Pay Now ${{ number_format($order->total_price, 2) }}
            </button>
        </form>
    </div>
</div>
@endsection