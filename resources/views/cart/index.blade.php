@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Shopping Cart</h1>
    
    @if(count($menuItems) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($menuItems as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>${{ number_format($item['price'], 2) }}</td>
                    <td>
                        <form action="{{ route('cart.update', $item['id']) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1">
                            <button type="submit" class="btn btn-sm btn-info">Update</button>
                        </form>
                    </td>
                    <td>${{ number_format($item['subtotal'], 2) }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right"><strong>Total:</strong></td>
                    <td>${{ number_format($total, 2) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        
        <div class="text-right">
            <a href="{{ route('menu.index') }}" class="btn btn-secondary">Continue Shopping</a>
            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
        </div>
    @else
        <div class="alert alert-info">
            Your cart is empty. <a href="{{ route('menu.index') }}">Browse our menu</a> to add items.
        </div>
    @endif
</div>
@endsection