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
@endsection
