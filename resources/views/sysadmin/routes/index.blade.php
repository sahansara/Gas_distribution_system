<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Logistics & Route Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'list' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Tab Navigation -->
            <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200">
                <div class="flex">
                    <button @click="activeTab = 'list'" 
                        :class="activeTab === 'list' ? 'border-b-2 border-indigo-500 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                        class="flex-1 py-4 px-4 text-center font-semibold text-sm transition duration-150 flex items-center justify-center gap-2">
                        <span class="text-lg">🚛</span>
                        <span>Active Route Status</span>
                    </button>
                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-2 border-green-500 text-green-700 bg-green-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                        class="flex-1 py-4 px-4 text-center font-semibold text-sm transition duration-150 flex items-center justify-center gap-2">
                        <span class="text-lg">➕</span>
                        <span>Schedule New Route</span>
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
             <div class="mt-6">
            <div x-show="activeTab === 'list'" x-transition.opacity>
                <x-sysadmin.routes.list :routes="$routes" />
            </div>

            <div x-show="activeTab === 'create'" style="display: none;" x-transition.opacity>
                <x-sysadmin.routes.create-form 
                    :staff="\App\Models\User::where('role', 'staff')->get()" 
                />
            </div>
            </div>
        </div>
    </div>
</x-app-layout>