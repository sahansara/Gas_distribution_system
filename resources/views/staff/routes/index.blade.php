<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Delivery Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6 bg-white">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Assigned Routes</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Route Info</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Vehicle</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Pending Deliveries</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-600 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($routes as $route)
                                    <tr class="hover:bg-gray-50 transition">
                                        
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ $route->name }}</div>
                                            <div class="text-xs text-gray-500">Plan: {{ \Carbon\Carbon::parse($route->planned_start_time)->format('h:i A') }}</div>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $route->vehicle_number }}
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $route->status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $route->status === 'Active' ? 'bg-green-100 text-green-800 animate-pulse' : '' }}
                                                {{ $route->status === 'Completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                                {{ $route->status }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if($route->pending_orders_count > 0)
                                                <span class="text-red-600 font-bold text-sm">{{ $route->pending_orders_count }} Stops Left</span>
                                            @else
                                                <span class="text-green-600 font-bold text-xs">All Delivered</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('staff.routes.show', $route->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                                Open Manifest
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                            You are not assigned to any routes today.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>