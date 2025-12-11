<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Customer Order Processing') }}
        </h2>
    </x-slot>
    
    <div class="py-8" x-data="{ activeTab: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tab Navigation --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-2">
                    <button @click="activeTab = 'list'" 
                        :class="activeTab === 'list' ? 'border-b-3 border-indigo-600 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3">
                        <span class="mr-2">📋</span> Daily Order List
                    </button>
                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-3 border-indigo-600 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3">
                        <span class="mr-2">➕</span> New Customer Order
                    </button>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="mt-6">
                <div x-show="activeTab === 'list'" x-transition.opacity>
                    <x-staff.orders.list :orders="$orders" />
                </div>
                
                <div x-show="activeTab === 'create'" style="display: none;" x-transition.opacity>
                    <x-staff.orders.create-form 
                        :customers="$customers ?? \App\Models\Customer::with('user')->get()" 
                        :routes="$routes ?? \App\Models\DeliveryRoute::where('is_active', true)->get()"
                        :gasTypes="$gasTypes ?? \App\Models\GasType::all()"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
