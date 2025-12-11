<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Route Details & Manifest') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Route Header Card -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 border-l-4 border-indigo-500">
                <div class="flex flex-col lg:flex-row justify-between items-start gap-4">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $route->name }}</h3>
                        <div class="space-y-1 text-sm">
                            <p class="text-gray-600">
                                <span class="font-semibold text-gray-700">Vehicle:</span> 
                                <span class="font-bold text-gray-900">{{ $route->vehicle_number }}</span>
                            </p>
                            <p class="text-gray-600">
                                <span class="font-semibold text-gray-700">Driver:</span> 
                                {{ $route->driver->name ?? 'N/A' }}
                            </p>
                            <p class="text-gray-600">
                                <span class="font-semibold text-gray-700">Assistant:</span> 
                                {{ $route->assistant->name ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="text-left lg:text-right">
                        <div class="mb-4">
                            <span class="inline-flex items-center px-4 py-2 text-sm font-bold rounded-full shadow-sm
                                {{ $route->status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $route->status === 'Active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $route->status === 'Completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                {{ strtoupper($route->status) }}
                            </span>
                        </div>
                        
                        @if($route->status === 'Scheduled')
                            <form action="{{ route('admin.routes.update_status', $route->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Active">
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                    Start Journey
                                </button>
                            </form>
                        @elseif($route->status === 'Active')
                            <form action="{{ route('admin.routes.update_status', $route->id) }}" method="POST" onsubmit="return confirm('Finish this route?');">
                                @csrf
                                <input type="hidden" name="status" value="Completed">
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-lg shadow-sm transition duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Complete Route
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Time Performance Cards -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Planned Schedule Card -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 shadow-sm">
                    <h4 class="text-xs font-bold text-blue-600 uppercase tracking-wide mb-3">Planned Schedule</h4>
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="text-2xl font-bold text-blue-900">
                                {{ \Carbon\Carbon::parse($route->planned_start_time)->format('h:i A') }}
                            </div>
                            <div class="text-xs text-blue-700 mt-1">Expected Start</div>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold {{ $route->planned_end_time ? 'text-blue-900' : 'text-gray-400' }}">
                                {{ $route->planned_end_time ? \Carbon\Carbon::parse($route->planned_end_time)->format('h:i A') : '--:--' }}
                            </div>
                            <div class="text-xs {{ $route->planned_end_time ? 'text-blue-700' : 'text-gray-400' }} mt-1">Expected End</div>
                        </div>
                    </div>
                </div>

                <!-- Actual Performance Card -->
                <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-sm">
                    <h4 class="text-xs font-bold text-green-600 uppercase tracking-wide mb-3">Actual Performance</h4>
                    <div class="flex justify-between items-center">
                        <div>
                            @if($route->actual_start_time)
                                <div class="text-2xl font-bold text-green-900">
                                    {{ \Carbon\Carbon::parse($route->actual_start_time)->format('h:i A') }}
                                </div>
                                @php
                                    $plan = \Carbon\Carbon::parse(date('Y-m-d') . ' ' . $route->planned_start_time);
                                    $actual = \Carbon\Carbon::parse($route->actual_start_time);
                                    $diff = $plan->diffInMinutes($actual, false);
                                @endphp
                                
                                @if($diff > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-700 mt-1">
                                        Late by {{ $diff }} mins
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-700 mt-1">
                                        On Time
                                    </span>
                                @endif
                            @else
                                <div class="text-2xl font-bold text-gray-400">--:--</div>
                                <div class="text-xs text-gray-500 mt-1">Waiting for driver</div>
                            @endif
                        </div>

                        <div class="text-right">
                            @if($route->actual_end_time)
                                <div class="text-2xl font-bold text-green-900">
                                    {{ \Carbon\Carbon::parse($route->actual_end_time)->format('h:i A') }}
                                </div>
                                <div class="text-xs text-green-700 mt-1 font-semibold">Completed</div>
                            @else
                                <div class="text-2xl font-bold text-gray-400">--:--</div>
                                <div class="text-xs text-gray-500 mt-1">In Progress</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Stops Table -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-6">
                    <h4 class="text-lg font-bold text-gray-900">Delivery Stops Sequence</h4>
                    <p class="text-sm text-gray-600 mt-1">Optimized route for efficient delivery</p>
                </div>
                
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Stop #</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Order Details</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Customer / Address</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $count = 1; @endphp
                            @forelse($route->orders as $order)
                                <tr class="hover:bg-gray-50 transition duration-150 {{ $order->is_urgent ? 'bg-red-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-bold text-sm">
                                            {{ $count++ }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $order->order_number }}</div>
                                        <div class="text-xs text-gray-600 mt-1">
                                            <span class="inline-flex items-center">
                                                {{ $order->items->count() }} Items
                                            </span>
                                            <span class="mx-1">•</span>
                                            <span class="font-semibold">Rs. {{ number_format($order->total_amount) }}</span>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->customer->user->name }}</div>
                                        <div class="text-xs text-gray-600 mt-1">{{ Str::limit($order->customer->address, 40) }}</div>
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($order->is_urgent)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                                🔥 URGENT
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                Normal
                                            </span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if($order->status == 'Completed' || $order->status == 'Delivered')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Delivered
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                            <p class="text-gray-600 font-medium">No orders assigned to this route yet</p>
                                            <p class="text-xs text-gray-500 mt-1">Staff needs to create orders and select "{{ $route->name }}"</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>