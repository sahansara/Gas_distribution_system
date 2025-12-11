<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Distribution Management System</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <script src="https://cdn.tailwindcss.com"></script>
        
        <style>
            body { font-family: 'Figtree', sans-serif; }
        </style>
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">

        <nav class="bg-white shadow-sm border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <div class="flex items-center gap-2">
                        <div class="bg-yellow-400 text-white p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                            </svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-gray-900">Gas DMS</span>
                    </div>

                    @if (Route::has('login'))
                        <div class="flex gap-4">
                            @auth
                                @if(Auth::user()->role === 'admin')
                                    <a href="{{ url('/dashboard') }}" class="font-semibold text-white bg-gray-900 hover:bg-gray-700 px-5 py-2 rounded-md transition">
                                        Admin Dashboard
                                    </a>
                                @else
                                    <a href="{{ route('staff.dashboard') }}" class="font-semibold text-white bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded-md transition">
                                        Staff Portal
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-white bg-yellow-500 hover:bg-red-700 px-6 py-2 rounded-md shadow-md transition transform hover:scale-105">
                                    Login to System
                                </a>
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </nav>

        <div class="relative bg-white overflow-hidden">
            <div class="max-w-7xl mx-auto">
                <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                    <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                        <polygon points="50,0 100,0 50,100 0,100" />
                    </svg>

                    <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                        <div class="sm:text-center lg:text-left">
                            <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                                <span class="block xl:inline">Streamline Your</span>
                                <span class="block text-yellow-500 xl:inline">Gas Distribution</span>
                            </h1>
                            <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                                An end-to-end solution for managing orders, tracking inventory, optimizing delivery routes, and auditing supplier financials. Designed for efficiency.
                            </p>
                            <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                                <div class="rounded-md shadow">
                                    <a href="{{ route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-yellow-500 hover:bg-red-700 md:py-4 md:text-lg md:px-10">
                                        Get Started
                                    </a>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
            <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-gray-50 flex items-center justify-center">
                <div class="p-10 grid grid-cols-2 gap-4 opacity-80">
                    <div class="bg-white p-6 rounded-lg shadow-lg animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    </div>
                    <div class="bg-blue-50 p-6 rounded-lg shadow-lg mt-8">
                        <div class="h-4 bg-blue-200 rounded w-full mb-4"></div>
                        <div class="h-4 bg-blue-200 rounded w-2/3"></div>
                    </div>
                    <div class="bg-red-50 p-6 rounded-lg shadow-lg">
                        <div class="h-4 bg-red-200 rounded w-2/3 mb-4"></div>
                        <div class="h-4 bg-red-200 rounded w-full"></div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg mt-8">
                        <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:text-center">
                    <h2 class="text-base text-yellow-500 font-semibold tracking-wide uppercase">Core Features</h2>
                    <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                        Everything you need to run operations
                    </p>
                </div>

                <div class="mt-10">
                    <dl class="space-y-10 md:space-y-0 md:grid md:grid-cols-2 md:gap-x-8 md:gap-y-10">
                        
                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Order Management</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Staff can easily take customer orders, apply category-based pricing automatically, and mark orders as urgent for priority handling.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Logistics & Routes</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Create delivery schedules, assign drivers and assistants, and track planned vs actual delivery times for maximum efficiency.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Inventory Control</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Manage Purchase Orders (PO) and Goods Received Notes (GRN). Automatically update stock levels only upon admin approval.
                            </dd>
                        </div>

                        <div class="relative">
                            <dt>
                                <div class="absolute flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <p class="ml-16 text-lg leading-6 font-medium text-gray-900">Financial Reporting</p>
                            </dt>
                            <dd class="mt-2 ml-16 text-base text-gray-500">
                                Generate PDF audit reports, track supplier payments, reconcile invoices against POs, and view real-time financial dashboards.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <footer class="bg-gray-800">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="mt-8 border-t border-gray-700 pt-8 md:flex md:items-center md:justify-between">
                    <div class="flex space-x-6 md:order-2">
                        <span class="text-gray-400 hover:text-gray-300">Staff Login</span>
                        <span class="text-gray-400 hover:text-gray-300">Admin Portal</span>
                    </div>
                    <p class="mt-8 text-base text-gray-400 md:mt-0 md:order-1">
                        &copy; {{ date('Y') }} Gas Distribution Management System. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

    </body>
</html>