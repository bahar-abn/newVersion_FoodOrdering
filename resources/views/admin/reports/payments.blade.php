@extends('layouts.app')

@section('title', 'Payments Report')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Payments Report</h1>

    <div class="mb-4 font-bold text-lg">
        Total Revenue: ${{ number_format($totalRevenue, 2) }}
    </div>

    <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-gray-700">Payment ID</th>
                <th class="px-6 py-3 text-left text-gray-700">Order ID</th>
                <th class="px-6 py-3 text-left text-gray-700">User</th>
                <th class="px-6 py-3 text-left text-gray-700">Amount</th>
                <th class="px-6 py-3 text-left text-gray-700">Status</th>
                <th class="px-6 py-3 text-left text-gray-700">Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
                <tr class="border-b">
                    <td class="px-6 py-4">{{ $payment->id }}</td>
                    <td class="px-6 py-4">{{ $payment->order->id }}</td>
                    <td class="px-6 py-4">{{ $payment->order->user->name }}</td>
                    <td class="px-6 py-4">${{ number_format($payment->amount, 2) }}</td>
                    <td class="px-6 py-4 capitalize">{{ $payment->status }}</td>
                    <td class="px-6 py-4">{{ $payment->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $payments->links() }}
    </div>
</div>
@endsection
