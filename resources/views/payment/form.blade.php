@extends('layouts.app')

@section('content')
<div class="container py-8">
    <h1 class="text-2xl font-bold mb-6">Payment for Order #{{ $order->id }}</h1>
    
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
        
        <div class="mb-4">
            @foreach($items as $item)
            <div class="flex justify-between py-2 border-b">
                <span>{{ $item->menu->name }} × {{ $item->quantity }}</span>
                <span>${{ number_format($item->menu->price * $item->quantity, 2) }}</span>
            </div>
            @endforeach
        </div>
        
        <div class="flex justify-between font-bold text-lg pt-2">
            <span>Total:</span>
            <span>${{ number_format($total, 2) }}</span>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold mb-4">Payment Method</h2>
        
        <form action="{{ route('payment.process', $order->id) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Card Number</label>
                <input type="text" class="w-full px-3 py-2 border rounded-md" 
                       placeholder="1234 5678 9012 3456" required>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 mb-2">Expiry Date</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-md" 
                           placeholder="MM/YY" required>
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">CVV</label>
                    <input type="text" class="w-full px-3 py-2 border rounded-md" 
                           placeholder="123" required>
                </div>
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-md font-medium">
                Pay ${{ number_format($total, 2) }}
            </button>
        </form>
    </div>
</div>
@endsection