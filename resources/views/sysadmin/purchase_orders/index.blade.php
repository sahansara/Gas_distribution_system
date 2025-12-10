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
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-t-lg border-b border-gray-200 mb-4">
                <div class="flex">
                    <button @click="activeTab = 'list'" 
                        :class="activeTab === 'list' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-gray-50' : 'text-gray-500 hover:text-gray-700'"
                        class="w-1/2 py-4 px-1 text-center font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                        All Purchase Orders
                    </button>
                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-gray-50' : 'text-gray-500 hover:text-gray-700'"
                        class="w-1/2 py-4 px-1 text-center font-medium text-sm focus:outline-none transition duration-150 ease-in-out">
                        + Create New Order
                    </button>
                </div>
            </div>

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
</x-app-layout>