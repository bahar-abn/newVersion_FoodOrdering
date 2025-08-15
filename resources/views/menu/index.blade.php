@extends('layouts.app')

@section('title', 'Our Menu')
@section('content')
<div class="bg-gradient-to-r from-yellow-50 to-orange-50 py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-4xl font-bold text-center mb-12 text-orange-600">Our Delicious Menu</h1>
        
        @include('components.flash')
        
        @auth
            @if(auth()->user()->isAdmin())
                <div class="mb-6 text-right">
                    <a href="{{ route('admin.menu.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Add New Item
                    </a>
                </div>
            @endif
        @endauth

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($items as $item)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transition transform hover:scale-105">
                <div class="h-48 bg-gray-200 overflow-hidden">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <h3 class="text-xl font-bold text-gray-800">{{ $item->name }}</h3>
                        <span class="text-lg font-bold text-orange-600">${{ number_format($item->price, 2) }}</span>
                    </div>
                    
                    <p class="mt-2 text-gray-600">{{ $item->description }}</p>
                    
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($item->average_rating))
                                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-1 text-gray-600">({{ number_format($item->average_rating, 1) }})</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-between items-center">
                        <a href="{{ route('menu.show', $item->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">View Details</a>
                        
                        @auth
                            <button class="px-3 py-1 bg-orange-500 text-white rounded-full hover:bg-orange-600 transition add-to-cart" 
                                    data-id="{{ $item->id }}" data-name="{{ $item->name }}" data-price="{{ $item->price }}">
                                Add to Cart
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="px-3 py-1 bg-gray-500 text-white rounded-full hover:bg-gray-600 transition">
                                Login to Order
                            </a>
                        @endauth
                    </div>
                    
                    @auth
                        @if(auth()->user()->isAdmin())
                            <div class="mt-4 pt-4 border-t border-gray-200 flex space-x-2">
                                <a href="{{ route('admin.menu.edit', $item->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded-full hover:bg-blue-600 transition">
                                    Edit
                                </a>
                                <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure?')" class="px-3 py-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-8">
            {{ $items->links() }}
        </div>
    </div>
</div>

@auth
<div class="fixed bottom-4 right-4 z-50">
    <div id="cart-toggle" class="bg-orange-500 text-white p-4 rounded-full shadow-lg cursor-pointer hover:bg-orange-600 transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <span id="cart-count" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
    </div>
    
    <div id="cart-panel" class="hidden absolute bottom-full right-0 mb-4 w-80 bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="bg-orange-500 text-white p-4">
            <h3 class="font-bold">Your Order</h3>
        </div>
        
        <div class="p-4 max-h-96 overflow-y-auto">
            <div id="cart-items">
                <p class="text-gray-500 text-center py-4">Your cart is empty</p>
            </div>
        </div>
        
        <div class="p-4 border-t border-gray-200">
            <div class="flex justify-between font-bold mb-4">
                <span>Total:</span>
                <span id="cart-total">$0.00</span>
            </div>
            
            <form id="order-form" action="{{ route('orders.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Address</label>
                    <input type="text" name="delivery_address" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone_number" required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Special Instructions</label>
                    <textarea name="special_instructions" class="w-full px-3 py-2 border border-gray-300 rounded-md"></textarea>
                </div>
                
                <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-md hover:bg-orange-600 transition">
                    Place Order ($<span id="order-total">0.00</span>)
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cart = [];
        const cartItemsEl = document.getElementById('cart-items');
        const cartCountEl = document.getElementById('cart-count');
        const cartTotalEl = document.getElementById('cart-total');
        const orderTotalEl = document.getElementById('order-total');
        const cartToggle = document.getElementById('cart-toggle');
        const cartPanel = document.getElementById('cart-panel');
        const orderForm = document.getElementById('order-form');
        
        // Toggle cart panel
        cartToggle.addEventListener('click', function() {
            cartPanel.classList.toggle('hidden');
        });
        
        // Add to cart
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const price = parseFloat(this.getAttribute('data-price'));
                
                const existingItem = cart.find(item => item.id === id);
                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({
                        id: id,
                        name: name,
                        price: price,
                        quantity: 1
                    });
                }
                
                updateCartDisplay();
                cartPanel.classList.remove('hidden');
            });
        });
        
        function updateCartDisplay() {
            if (cart.length === 0) {
                cartItemsEl.innerHTML = '<p class="text-gray-500 text-center py-4">Your cart is empty</p>';
                cartCountEl.textContent = '0';
                cartTotalEl.textContent = '$0.00';
                orderTotalEl.textContent = '0.00';
                return;
            }
            
            let html = '';
            let total = 0;
            let totalItems = 0;
            
            cart.forEach((item, index) => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;
                totalItems += item.quantity;
                
                html += `
                    <div class="flex justify-between items-center mb-3 pb-3 border-b border-gray-200">
                        <div>
                            <h4 class="font-medium">${item.name}</h4>
                            <p class="text-sm text-gray-600">$${item.price.toFixed(2)} x ${item.quantity}</p>
                        </div>
                        <div class="flex items-center">
                            <span class="font-medium mr-4">$${itemTotal.toFixed(2)}</span>
                            <button class="text-red-500 remove-item" data-index="${index}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="items[${index}][menu_id]" value="${item.id}">
                    <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                `;
            });
            
            cartItemsEl.innerHTML = html;
            cartCountEl.textContent = totalItems;
            cartTotalEl.textContent = `$${total.toFixed(2)}`;
            orderTotalEl.textContent = total.toFixed(2);
            
            // Add event listeners to remove buttons
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    cart.splice(index, 1);
                    updateCartDisplay();
                });
            });
        }
    });
</script>
@endpush
@endauth
@endsection