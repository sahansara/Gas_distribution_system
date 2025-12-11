<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supplier Refill & Financial Tracking') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.reports.index') }}" class="flex flex-col sm:flex-row items-start sm:items-end gap-4">
                        <div class="w-full sm:flex-1">
                            <label class="block font-semibold text-sm text-gray-700 mb-2">Select Supplier to Audit</label>
                            <select name="supplier_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Choose Supplier --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                                        {{ $s->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                            Generate Report
                        </button>
                    </form>
                </div>
            </div>

            @if($selectedSupplier)
                {{-- Use Component for Report Details --}}
                <x-sysadmin.refill_gas.reports 
                    :selectedSupplier="$selectedSupplier"
                    :stats="$stats"
                    :history="$history"
                />
            @else
                <!-- No Supplier Selected State -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Supplier Selected</h3>
                    <p class="text-sm text-gray-600">Please select a supplier above to generate the Audit Report.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>