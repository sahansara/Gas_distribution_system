<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Summary Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Stats Cards Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                {{-- Total Revenue Card --}}
                <div class="bg-gradient-to-br from-green-50 to-green-100 overflow-hidden shadow-lg sm:rounded-xl p-6 border-l-4 border-green-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-green-600 text-xs font-bold uppercase tracking-wider">Total Revenue</div>
                        <div class="bg-green-500 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">Rs. {{ number_format($totalRevenue) }}</div>
                    <div class="text-xs text-green-700 mt-2 font-medium">Completed Orders</div>
                </div>

                {{-- Pending Orders Card --}}
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 overflow-hidden shadow-lg sm:rounded-xl p-6 border-l-4 border-blue-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-blue-600 text-xs font-bold uppercase tracking-wider">Pending Orders</div>
                        <div class="bg-blue-500 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $pendingOrders }}</div>
                    <div class="text-xs text-blue-700 mt-2 font-medium">Awaiting Processing</div>
                </div>

                {{-- Total Customers Card --}}
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 overflow-hidden shadow-lg sm:rounded-xl p-6 border-l-4 border-indigo-500 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-indigo-600 text-xs font-bold uppercase tracking-wider">Total Customers</div>
                        <div class="bg-indigo-500 p-2 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900">{{ $totalCustomers }}</div>
                    <div class="text-xs text-indigo-700 mt-2 font-medium">Active Accounts</div>
                </div>

                {{-- Active Trucks Card --}}
                <div class="bg-gradient-to-br from-red-50 to-red-100 overflow-hidden shadow-lg sm:rounded-xl p-6 border-l-4 border-red-500 hover:shadow-xl transition-shadow duration-300 animate-pulse">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-red-600 text-xs font-bold uppercase tracking-wider">Active Trucks</div>
                        <div class="bg-red-500 p-2 rounded-lg animate-bounce">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-red-600">{{ $activeRoutes }}</div>
                    <div class="text-xs text-red-700 mt-2 font-medium flex items-center">
                        <span class="inline-block w-2 h-2 bg-red-500 rounded-full mr-2 animate-ping"></span>
                        Live Now
                    </div>
                </div>
            </div>

            {{-- Charts Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- Sales Trends Chart --}}
                <div class="bg-white shadow-lg sm:rounded-xl p-6 lg:col-span-2 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                <span class="mr-2">📈</span> Sales Trends
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">Last 7 Days Performance</p>
                        </div>
                        <div class="bg-indigo-100 px-3 py-1 rounded-full">
                            <span class="text-xs font-semibold text-indigo-700">7D</span>
                        </div>
                    </div>
                    <canvas id="salesChart" height="120"></canvas>
                </div>

                {{-- Order Status Distribution Chart --}}
                <div class="bg-white shadow-lg sm:rounded-xl p-6 border border-gray-200 hover:shadow-xl transition-shadow duration-300">
                    <div class="mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="mr-2">🎯</span> Order Status
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Distribution Overview</p>
                    </div>
                    <div class="relative h-64">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Low Stock Alerts Section --}}
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 shadow-lg sm:rounded-xl p-6 border-l-4 border-yellow-500 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-yellow-200">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800 flex items-center">
                            <span class="mr-2 text-2xl">⚠️</span> Low Stock Alerts
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Items requiring immediate attention</p>
                    </div>
                    @if($lowStockItems->count() > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse">
                            {{ $lowStockItems->count() }} Alert(s)
                        </span>
                    @endif
                </div>
                
                @if($lowStockItems->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($lowStockItems as $item)
                            <div class="bg-white p-4 rounded-lg border-2 border-yellow-300 shadow-md hover:shadow-lg transition-shadow duration-300 hover:border-yellow-400">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="font-bold text-gray-800 text-base">{{ $item->name }}</span>
                                    <span class="bg-gradient-to-r from-red-500 to-red-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">
                                        {{ $item->current_stock }} left
                                    </span>
                                </div>
                                <div class="mt-3 bg-red-50 rounded-lg p-2">
                                    <div class="flex items-center text-xs text-red-700">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold">Action Required</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-green-700 font-bold text-lg">All inventory levels are healthy!</p>
                        <p class="text-green-600 text-sm mt-1">No action required at this time</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // sales chart
        const ctxSales = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctxSales, {
            type: 'line',
            data: {
                labels: @json($dates),
                datasets: [{
                    label: 'Daily Sales (Rs)',
                    data: @json($salesData),
                    borderColor: '#4F46E5',
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#4F46E5',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#4F46E5'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: { size: 11 }
                        }
                    }
                }
            }
        });


        // status chart
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Loaded', 'Delivered', 'Completed'],
                datasets: [{
                    data: [
                        {{ $orderStats['Pending'] ?? 0 }},
                        {{ $orderStats['Loaded'] ?? 0 }},
                        {{ $orderStats['Delivered'] ?? 0 }},
                        {{ $orderStats['Completed'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#FCD34D',
                        '#60A5FA',
                        '#34D399',
                        '#9CA3AF'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverOffset: 8,
                    hoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 12,
                            font: { size: 11, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        cornerRadius: 8
                    }
                }
            }
        });
    </script>
</x-app-layout>