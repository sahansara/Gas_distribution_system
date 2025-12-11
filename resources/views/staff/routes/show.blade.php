<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Route Manifest') }}
        </h2>
    </x-slot>
    <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 border-l-8 border-green-500">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        
        <div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $route->name }}</h3>
            <p class="text-sm text-gray-500">Vehicle: <span class="font-bold text-gray-800">{{ $route->vehicle_number }}</span></p>
            
            <div class="mt-2 flex gap-4 text-sm">
                <div class="bg-blue-50 px-2 py-1 rounded border border-blue-100 text-blue-800">
                    <strong>Plan:</strong> {{ \Carbon\Carbon::parse($route->planned_start_time)->format('h:i A') }}
                </div>
                @if($route->actual_start_time)
                    <div class="bg-green-50 px-2 py-1 rounded border border-green-100 text-green-800">
                        <strong>Started:</strong> {{ \Carbon\Carbon::parse($route->actual_start_time)->format('h:i A') }}
                    </div>
                @endif
            </div>
        </div>
        
        <div class="w-full md:w-auto">
            @if($route->status === 'Scheduled')
                <form action="{{ route('staff.routes.update_status', $route->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="Active">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded shadow-lg transform hover:scale-105 transition flex items-center justify-center">
                        <span class="text-xl mr-2">▶</span> START DRIVING
                    </button>
                </form>
            @elseif($route->status === 'Active')
                <form action="{{ route('staff.routes.update_status', $route->id) }}" method="POST" onsubmit="return confirm('Finish this route?');">
                    @csrf
                    <input type="hidden" name="status" value="Completed">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-8 rounded shadow-lg flex items-center justify-center">
                        <span class="text-xl mr-2">🏁</span> FINISH ROUTE
                    </button>
                </form>
            @else
                <div class="text-center p-4 bg-gray-100 rounded border border-gray-300">
                    <span class="text-gray-500 font-bold block">Route Completed</span>
                    <span class="text-xs text-gray-400">Ended: {{ \Carbon\Carbon::parse($route->actual_end_time)->format('h:i A') }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6 border-l-8 border-green-500">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $route->name }}</h3>
                        <p class="text-sm text-gray-500">Truck: {{ $route->vehicle_number }}</p>
                    </div>
                    
                    <div>
                        @if($route->status === 'Scheduled')
                            <form action="{{ route('staff.routes.update_status', $route->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="Active">
                                <button type="submit" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                                    ▶ START DRIVING
                                </button>
                            </form>
                        @elseif($route->status === 'Active')
                            <form action="{{ route('staff.routes.update_status', $route->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to finish the route?');">
                                @csrf
                                <input type="hidden" name="status" value="Completed">
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded shadow-lg">
                                    🏁 FINISH ROUTE
                                </button>
                            </form>
                        @else
                            <span class="text-gray-500 font-bold bg-gray-100 px-4 py-2 rounded">Route Completed</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h4 class="font-bold text-gray-700 mb-4 uppercase text-sm tracking-wide">Delivery Stops</h4>
                
                <div class="grid gap-4">
                    @forelse($route->orders as $index => $order)
                        <div class="border rounded-lg p-4 flex justify-between items-center {{ $order->is_urgent ? 'bg-red-50 border-red-200' : 'bg-gray-50' }}">
                            
                            <div class="flex items-center gap-4">
                                <div class="bg-white border-2 border-gray-300 rounded-full h-10 w-10 flex items-center justify-center font-bold text-gray-600">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="font-bold text-lg text-gray-900">
                                        {{ $order->customer->user->name }}
                                        @if($order->is_urgent) 
                                            <span class="text-red-600 text-xs ml-2">🔥 URGENT</span> 
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $order->customer->address }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Order #{{ $order->order_number }} • {{ $order->items->count() }} Items</div>
                                </div>
                            </div>

                            <div>
                                @if($order->status == 'Completed' || $order->status == 'Delivered')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">✓ Delivered</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-bold">Pending</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8">No stops added to this route yet.</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>