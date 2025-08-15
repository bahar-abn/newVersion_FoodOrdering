<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Order System - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-gray-100">
    <nav class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-white text-xl font-bold">FoodOrder</a>
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                    <a href="{{ route('menu.index') }}" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Menu</a>
                    
                    @auth
                        <a href="{{ route('orders.index') }}" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">My Orders</a>
                        
                        @if(auth()->user()->isAdmin())
                            <div class="relative group">
                                <button class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                                    Admin
                                    <svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden group-hover:block">
                                    <a href="{{ route('admin.orders') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100">Orders Report</a>
                                    <a href="{{ route('admin.popular') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100">Popular Foods</a>
                                    <a href="{{ route('admin.payments') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100">Payment Report</a>
                                    <a href="{{ route('admin.comments') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-100">Pending Comments</a>
                                </div>
                            </div>
                        @endif
                    @endauth
                </div>
                
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    @guest
                        <a href="{{ route('login') }}" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Login</a>
                        <a href="{{ route('register') }}" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Register</a>
                    @else
                        <span class="text-white px-3 py-2 text-sm font-medium">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-sm font-medium">Logout</button>
                        </form>
                    @endguest
                </div>
                
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-indigo-700 focus:outline-none" onclick="toggleMobileMenu()">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="sm:hidden hidden" id="mobile-menu">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('menu.index') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Menu</a>
                
                @auth
                    <a href="{{ route('orders.index') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium">My Orders</a>
                    
                    @if(auth()->user()->isAdmin())
                        <div class="pl-3">
                            <p class="text-white font-medium">Admin</p>
                            <div class="pl-2 mt-1 space-y-1">
                                <a href="{{ route('admin.orders') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-sm font-medium">Orders Report</a>
                                <a href="{{ route('admin.popular') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-sm font-medium">Popular Foods</a>
                                <a href="{{ route('admin.payments') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-sm font-medium">Payment Report</a>
                                <a href="{{ route('admin.comments') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-sm font-medium">Pending Comments</a>
                            </div>
                        </div>
                    @endif
                @endauth
                
                @guest
                    <a href="{{ route('login') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Login</a>
                    <a href="{{ route('register') }}" class="text-white hover:bg-indigo-700 block px-3 py-2 rounded-md text-base font-medium">Register</a>
                @else
                    <div class="pt-4 pb-3 border-t border-indigo-700">
                        <div class="flex items-center px-5">
                            <div class="text-base font-medium text-white">{{ auth()->user()->name }}</div>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left text-white hover:bg-indigo-700 px-3 py-2 rounded-md text-base font-medium">Logout</button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </nav>
    
    <main>
        @yield('content')
    </main>
    
    <footer class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} FoodOrder System. All rights reserved.</p>
        </div>
    </footer>
    
    @stack('scripts')
    
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }
    </script>
</body>
</html>