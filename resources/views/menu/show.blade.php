@extends('layouts.app')

@section('title', $menu->name)
@section('content')
<div class="bg-gradient-to-r from-yellow-50 to-orange-50 py-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Menu Item Details -->
            <div class="md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="h-64 bg-gray-200 overflow-hidden">
                        @if($menu->image)
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-6">
                        <h1 class="text-3xl font-bold text-gray-800">{{ $menu->name }}</h1>
                        <p class="text-gray-600 mt-2">{{ $menu->description }}</p>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-2xl font-bold text-orange-600">${{ number_format($menu->price, 2) }}</span>
                            
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($menu->average_rating))
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endif
                                @endfor
                                <span class="ml-1 text-gray-600">({{ number_format($menu->average_rating, 1) }})</span>
                            </div>
                        </div>
                        
                        @auth
                            <div class="mt-6 flex flex-col space-y-3">
                                <form action="{{ route('cart.add') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                                    <div class="flex items-center">
                                        <label for="quantity" class="mr-2">Quantity:</label>
                                        <input type="number" name="quantity" id="quantity" min="1" value="1" class="w-16 px-2 py-1 border border-gray-300 rounded-md">
                                        <button type="submit" class="ml-4 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition w-full">
                                            Add to Cart
                                        </button>
                                    </div>
                                </form>
                                
                                <form action="{{ route('orders.direct') }}" method="POST" class="w-full">
                                    @csrf
                                    <input type="hidden" name="items[0][menu_id]" value="{{ $menu->id }}">
                                    <input type="hidden" name="items[0][quantity]" value="1">
                                    <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        Order Now
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="mt-6">
                                <a href="{{ route('login') }}" class="block w-full px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition text-center">
                                    Login to Order
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
                
                <!-- Rating Form -->
                <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Rate this item</h3>
                    
                    @auth
                        <form action="{{ route('survey.store', $menu->id) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Rating</label>
                                <div class="rating flex items-center">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                            {{ (auth()->user()->surveys()->where('menu_id', $menu->id)->first()?->rating == $i ? 'checked' : '') }}>
                                        <label for="star{{ $i }}">★</label>
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Your Review (optional)</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" 
                                          id="comment" name="comment" rows="3">{{ auth()->user()->surveys()->where('menu_id', $menu->id)->first()?->comment ?? '' }}</textarea>
                            </div>
                            
                            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                Submit Rating
                            </button>
                        </form>
                    @else
                        <p class="text-gray-600">Please <a href="{{ route('login') }}" class="text-orange-600 hover:underline">login</a> to rate this item.</p>
                    @endauth
                </div>
            </div>
            
            <!-- Comments Section -->
            <div class="md:w-1/2">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Comments</h3>
                    
                    @auth
                        <form action="{{ route('comment.store', $menu->id) }}" method="POST" class="mb-6">
                            @csrf
                            <div class="mb-4">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500" 
                                          id="content" name="content" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition">
                                Post Comment
                            </button>
                        </form>
                    @else
                        <p class="text-gray-600 mb-6">Please <a href="{{ route('login') }}" class="text-orange-600 hover:underline">login</a> to post a comment.</p>
                    @endauth
                    
                    <div class="space-y-4">
                        @forelse($comments as $comment)
                            <div class="border-b border-gray-200 pb-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-medium text-gray-900">{{ $comment->user->name }}</h4>
                                    <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700">{{ $comment->content }}</p>
                            </div>
                        @empty
                            <p class="text-gray-500">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    .rating > input {
        display: none;
    }
    .rating > label {
        position: relative;
        width: 1em;
        font-size: 2rem;
        color: #FFD700;
        cursor: pointer;
    }
    .rating > label:hover:before,
    .rating > label:hover ~ label:before,
    .rating > input:checked ~ label:before {
        opacity: 1;
    }
    .rating > label:before {
        content: "★";
        position: absolute;
        opacity: 0;
    }
</style>
@endpush
@endsection