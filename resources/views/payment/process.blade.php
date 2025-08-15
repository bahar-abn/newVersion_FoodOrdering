@extends('layouts.app')

@section('title', 'Payment')
@section('content')
<div class="min-h-screen bg-gradient-to-r from-green-50 to-teal-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl">
        <div class="md:flex">
            <div class="p-8 w-full">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800 mb-2">Complete Your Payment</h1>
                    <p class="text-gray-600">Order #{{ $order->id }}</p>
                </div>
                
                <div class="mt-6 bg-blue-50 p-4 rounded-lg">
                    <div class="flex justify-between">
                        <span class="font-medium text-gray-700">Total Amount:</span>
                        <span class="font-bold text-blue-700">${{ number_format($order->total_amount - $order->discount_amount, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between mt-1">
                        <span class="text-sm text-green-600">Discount Applied:</span>
                        <span class="text-sm text-green-600">-${{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
                
                <form action="{{ route('payment.complete', $order->id) }}" method="POST" class="mt-8 space-y-6">
                    @csrf
                    
                    <div>
                        <label for="card_number" class="block text-sm font-medium text-gray-700">Card Number</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" id="card_number" name="card_number" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md" placeholder="4242 4242 4242 4242" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                            <div class="mt-1">
                                <input type="text" id="expiry" name="expiry" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md" placeholder="MM/YY" required>
                            </div>
                        </div>
                        
                        <div>
                            <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                            <div class="mt-1">
                                <input type="text" id="cvc" name="cvc" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md" placeholder="123" required>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name on Card</label>
                        <div class="mt-1">
                            <input type="text" id="name" name="name" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-4 pr-12 py-3 sm:text-sm border-gray-300 rounded-md" required>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Pay ${{ number_format($order->total_amount - $order->discount_amount, 2) }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection