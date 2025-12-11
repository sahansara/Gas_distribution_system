@props(['routes'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-b-lg">
    <div class="p-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-900">Delivery Fleet Overview</h3>
            <p class="text-sm text-gray-600 mt-1">Monitor all active and scheduled routes</p>
        </div>

        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Route Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Driver</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-blue-700 uppercase tracking-wider">Planned Time</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-green-700 uppercase tracking-wider">Actual Time</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($routes as $route)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900 text-sm">{{ $route->name }}</span>
                                <div class="text-xs text-gray-600 mt-1">{{ $route->vehicle_number }}</div>
                            </td>

                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $route->driver->name ?? 'Unassigned' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $route->status === 'Scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $route->status === 'Active' ? 'bg-green-100 text-green-800 animate-pulse' : '' }}
                                    {{ $route->status === 'Completed' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ $route->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center text-sm">
                                <div class="text-blue-800 font-semibold">
                                    {{ \Carbon\Carbon::parse($route->planned_start_time)->format('h:i A') }}
                                </div>
                                @if($route->planned_end_time)
                                    <div class="text-xs text-gray-500 mt-1">
                                        End: {{ \Carbon\Carbon::parse($route->planned_end_time)->format('h:i A') }}
                                    </div>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center text-sm">
                                @if($route->actual_start_time)
                                    <div class="text-green-700 font-bold">
                                        {{ \Carbon\Carbon::parse($route->actual_start_time)->format('h:i A') }}
                                    </div>
                                    @if($route->actual_end_time)
                                        <div class="text-xs text-gray-600 mt-1">
                                            Ended: {{ \Carbon\Carbon::parse($route->actual_end_time)->format('h:i A') }}
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-700 mt-1 animate-pulse">
                                            In Progress
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-400 text-xs font-medium">Not Started</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.routes.show', $route->id) }}" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                    </svg>
                                    <p class="text-gray-600 font-medium">No routes scheduled</p>
                                    <p class="text-sm text-gray-500 mt-1">Create a new route to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>