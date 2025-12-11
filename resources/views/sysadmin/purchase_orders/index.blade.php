<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Purchase Order Management') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tab Navigation --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-2">
                    <button @click="activeTab = 'list'" 
                        :class="activeTab === 'list' ? 'border-b-3 border-indigo-600 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3 focus:outline-none">
                        <span class="mr-2">📋</span> All Purchase Orders
                    </button>
                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-3 border-green-600 text-green-700 bg-green-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3 focus:outline-none">
                        <span class="mr-2">➕</span> Create New Order
                    </button>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="mt-6">
                <div x-show="activeTab === 'list'" x-transition.opacity>
                    <x-sysadmin.po.list 
                        :purchaseOrders="$purchaseOrders" 
                        :suppliers="$suppliers" 
                    />
                </div>

                <div x-show="activeTab === 'create'" style="display: none;" x-transition.opacity>
                    <x-sysadmin.po.create-form 
                        :suppliers="$suppliers" 
                        :gasTypes="$gasTypes" 
                    />
                </div>
            </div>

        </div>
    </div>
</x-app-layout>