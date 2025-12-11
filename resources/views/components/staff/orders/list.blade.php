@props(['orders'])

<div class="bg-white overflow-hidden shadow-md sm:rounded-lg border border-gray-300">
    <div class="p-8 bg-white">
        
        {{-- Header with Filters --}}
        <div class="flex flex-wrap gap-4 mb-6 justify-between items-center bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg border border-gray-200">
            <h3 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="text-2xl mr-2">📦</span> Today's Orders
            </h3>
            <div class="flex gap-3">
                <a href="{{ route('staff.orders.index', ['is_urgent' => 1]) }}" 
                    class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-semibold hover:bg-red-200 border border-red-300 transition-colors">
                    <span class="mr-1">🔥</span> Show Urgent Only
                </a>
                <a href="{{ route('staff.orders.index') }}" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-300 border border-gray-400 transition-colors">
                    <span class="mr-1">↺</span> Reset Filters
                </a>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="overflow-x-auto rounded-lg border border-gray-300">
            <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order #</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Route</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Total (Rs)</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Next Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors {{ $order->is_urgent ? 'bg-red-50 border-l-4 border-red-600' : '' }}">
                            
                            {{-- Order Number --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900 text-base">{{ $order->order_number }}</span>
                                @if($order->is_urgent)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-200 text-red-900 border border-red-300">
                                        🔥 Urgent
                                    </span>
                                @endif
                            </td>

                            {{-- Customer --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">{{ $order->customer->user->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ ucfirst($order->customer->customer_type) }}</div>
                            </td>

                            {{-- Route --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">
                                {{ $order->route->name }}
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-4 py-1.5 inline-flex text-xs leading-5 font-bold rounded-full border
                                    {{ $order->status === 'Pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-300' : '' }}
                                    {{ $order->status === 'Loaded' ? 'bg-blue-100 text-blue-800 border-blue-300' : '' }}
                                    {{ $order->status === 'Delivered' ? 'bg-green-100 text-green-800 border-green-300' : '' }}
                                    {{ $order->status === 'Completed' ? 'bg-gray-200 text-gray-800 border-gray-400' : '' }}">
                                    {{ $order->status }}
                                </span>
                            </td>

                            {{-- Total Amount --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-base font-bold text-gray-900">
                                {{ number_format($order->total_amount, 2) }}
                            </td>

                            {{-- Action Buttons --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($order->status == 'Pending')
                                    <form action="{{ route('staff.orders.next_status', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                            class="text-blue-700 hover:text-blue-900 font-semibold bg-blue-100 px-4 py-2 rounded-lg border border-blue-300 hover:bg-blue-200 transition-colors">
                                            <span class="mr-1">🚚</span> Mark Loaded
                                        </button>
                                    </form>
                                @elseif($order->status == 'Loaded')
                                    <form action="{{ route('staff.orders.next_status', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                            class="text-green-700 hover:text-green-900 font-semibold bg-green-100 px-4 py-2 rounded-lg border border-green-300 hover:bg-green-200 transition-colors">
                                            <span class="mr-1">✅</span> Mark Delivered
                                        </button>
                                    </form>
                                @elseif($order->status == 'Delivered')
                                    <span class="text-gray-500 italic text-sm">Wait for payment</span>
                                @else
                                    <span class="text-green-600 font-bold text-sm">✓ Closed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 text-base">
                                <div class="text-4xl mb-2">📭</div>
                                No orders for today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>