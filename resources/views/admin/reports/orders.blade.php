@extends('layouts.app')

@section('title', 'Orders Report')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Orders Report</h1>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-gray-700">ID</th>
                <th class="px-6 py-3 text-left text-gray-700">User</th>
                <th class="px-6 py-3 text-left text-gray-700">Items</th>
                <th class="px-6 py-3 text-left text-gray-700">Total</th>
                <th class="px-6 py-3 text-left text-gray-700">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $order->id }}</td>
                    <td class="px-6 py-4">{{ $order->user->name }}</td>
                    <td class="px-6 py-4">
                        @foreach($order->items as $item)
                            <div>{{ $item->menu->name }} x{{ $item->quantity }}</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-4">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">{{ $order->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection
