<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'customers' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-t-lg border-b border-gray-200">
                <div class="p-4 flex flex-wrap gap-2 justify-center sm:justify-start bg-gray-50">
                    
                    <button @click="activeTab = 'customers'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'customers', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'customers' }"
                        class="px-4 py-2 rounded-md font-medium text-sm transition shadow-sm border">
                        Manage Customers
                    </button>

                    <button @click="activeTab = 'suppliers'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'suppliers', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'suppliers' }"
                        class="px-4 py-2 rounded-md font-medium text-sm transition shadow-sm border">
                        Suppliers
                    </button>

                    <button @click="activeTab = 'grn'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'grn', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'grn' }"
                        class="px-4 py-2 rounded-md font-medium text-sm transition shadow-sm border">
                        GRN Management
                    </button>

                    <button @click="activeTab = 'balance'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'balance', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'balance' }"
                        class="px-4 py-2 rounded-md font-medium text-sm transition shadow-sm border">
                        Balance / Accounts
                    </button>

                    <button @click="activeTab = 'tracking'" 
                        :class="{ 'bg-blue-600 text-white': activeTab === 'tracking', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'tracking' }"
                        class="px-4 py-2 rounded-md font-medium text-sm transition shadow-sm border">
                        Track Orders
                    </button>

                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-b-lg p-6 min-h-[400px]">

                <div x-show="activeTab === 'customers'" x-transition.opacity>
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Customer Management</h3>
                    <x-sysadmin.customers :customers="$customers" />
                </div>

                <div x-show="activeTab === 'suppliers'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Supplier Management</h3>
                    <x-sysadmin.suppliers :suppliers="$suppliers" />
                </div>

                <div x-show="activeTab === 'grn'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Goods Received Note (GRN)</h3>
                    <x-sysadmin.grn />
                </div>

                <div x-show="activeTab === 'balance'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Account Balances</h3>
                    <x-sysadmin.balance />
                </div>

                <div x-show="activeTab === 'tracking'" style="display: none;" x-transition.opacity>
                    <h3 class="text-lg font-bold mb-4 text-gray-700">Order Tracking</h3>
                    <x-sysadmin.tracking />
                </div>

            </div>
        </div>
    </div>
</x-app-layout>