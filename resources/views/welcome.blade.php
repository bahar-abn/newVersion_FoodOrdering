@extends('layouts.app')

@section('title', 'Welcome')
@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="text-center">
        <h1 class="text-4xl font-bold text-white mb-6">Welcome to FoodOrder</h1>
        <p class="text-xl text-white mb-8">Order delicious food from our menu</p>
        
        <div class="flex justify-center space-x-4">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-white text-indigo-600 font-medium rounded-lg hover:bg-gray-100 transition">
                Login
            </a>
            <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-700 text-white font-medium rounded-lg hover:bg-indigo-800 transition">
                Register
            </a>
        </div>
        
        <div class="mt-8">
            <a href="{{ route('menu.index') }}" class="text-white hover:underline">Browse Menu</a>
        </div>
    </div>
</div>
@endsection