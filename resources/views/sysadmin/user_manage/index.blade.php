<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'customers' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tab Navigation --}}
            <div class="bg-white shadow sm:rounded-lg border border-gray-200 overflow-hidden">
                <div class="grid grid-cols-2">
                    <button @click="activeTab = 'customers'" 
                        :class="activeTab === 'customers' ? 'border-b-3 border-indigo-600 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3 focus:outline-none">
                        <span class="mr-2">👥</span> Manage Customers
                    </button>
                    <button @click="activeTab = 'suppliers'" 
                        :class="activeTab === 'suppliers' ? 'border-b-3 border-purple-600 text-purple-700 bg-purple-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3 focus:outline-none">
                        <span class="mr-2">🏢</span> Suppliers
                    </button>
                </div>
            </div>

            {{-- Tab Content --}}
            <div class="mt-6">
                <div x-show="activeTab === 'customers'" x-transition.opacity>
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg border border-gray-200">
                        <div class="p-6 bg-white">
                            {{-- Header --}}
                            <div class="mb-6 pb-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800">Customer Management</h3>
                                <p class="text-sm text-gray-500 mt-1">View and manage all customer accounts</p>
                            </div>
                            
                            {{-- Customer Component --}}
                            <x-sysadmin.manage_user.customers :customers="$customers" />
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'suppliers'" style="display: none;" x-transition.opacity>
                    <div class="bg-white overflow-hidden shadow sm:rounded-lg border border-gray-200">
                        <div class="p-6 bg-white">
                            {{-- Header --}}
                            <div class="mb-6 pb-4 border-b border-gray-200">
                                <h3 class="text-xl font-bold text-gray-800">Supplier Management</h3>
                                <p class="text-sm text-gray-500 mt-1">View and manage all supplier accounts</p>
                            </div>
                            
                            {{-- Supplier Component --}}
                            <x-sysadmin.manage_user.suppliers :suppliers="$suppliers" />
                            
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>