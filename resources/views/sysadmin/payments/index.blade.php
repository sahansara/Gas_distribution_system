<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supplier Payments & Ledger') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-t-lg border-b border-gray-200 mb-4">
                <div class="flex flex-wrap sm:flex-nowrap">
                    <button @click="activeTab = 'list'" 
                        :class="activeTab === 'list' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="w-full sm:w-1/3 py-4 px-1 text-center font-bold text-sm focus:outline-none transition duration-150 ease-in-out">
                        📜 Payment History
                    </button>

                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="w-full sm:w-1/3 py-4 px-1 text-center font-bold text-sm focus:outline-none transition duration-150 ease-in-out">
                        ➕ Record New Payment
                    </button>

                    <button @click="activeTab = 'ledger'" 
                        :class="activeTab === 'ledger' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                        class="w-full sm:w-1/3 py-4 px-1 text-center font-bold text-sm focus:outline-none transition duration-150 ease-in-out">
                        📊 Supplier Ledger
                    </button>
                </div>
            </div>

            <div x-show="activeTab === 'list'" x-transition.opacity>
                <x-sysadmin.payment.list :payments="$payments" />
            </div>

            <div x-show="activeTab === 'create'" style="display: none;" x-transition.opacity>
                <x-sysadmin.payment.create-form :suppliers="$suppliers" />
            </div>

            <div x-show="activeTab === 'ledger'" style="display: none;" x-transition.opacity>
                <x-sysadmin.payment.ledger :ledgerData="$ledgerData" />
            </div>

        </div>
    </div>
</x-app-layout>