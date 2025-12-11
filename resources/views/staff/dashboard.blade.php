<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Portal - Goods Received') }}
        </h2>
    </x-slot>
    <div class="py-8" x-data="{ activeTab: 'create' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tabs Navigation -->
            <div class="bg-white shadow-md sm:rounded-lg mb-6 overflow-hidden">
                <div class="grid grid-cols-2">
                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-3 border-indigo-600 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50 border-b-3 border-transparent'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3">
                        <span class="inline-flex items-center gap-2">
                            <span class="text-xl">📦</span>
                            <span>Receive New Stock</span>
                        </span>
                    </button>
                    <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'border-b-2 border-indigo-600 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                        class="py-4 px-6 text-center font-semibold text-sm transition duration-200 border-b-3">
                        <span class="inline-flex items-center gap-2">
                            <span class="text-xl">🕒</span>
                            <span>My Recent Submissions</span>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Create GRN Tab -->
            <div x-show="activeTab === 'create'" x-transition.opacity>
                <x-sysadmin.grn.create-form :suppliers="$suppliers" />
            </div>

            <!-- History Tab -->
            <div x-show="activeTab === 'history'" style="display: none;" x-transition.opacity>
                <div class="bg-white overflow-hidden shadow-md sm:rounded-lg">
                    <div class="p-8">
                        <div class="mb-6 pb-4 border-b-2 border-gray-200">
                            <h3 class="text-sm font-bold text-gray-900 mx-auto ">My Recently Created GRNs</h3>
                            <p class="text-sm text-gray-600 mt-1">View and track your submitted goods received notes</p>
                        </div>
                        
                        <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">GRN Number</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Received Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Supplier</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($myGrns as $grn)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4">
                                                <span class="text-sm font-bold text-gray-900">{{ $grn->grn_number }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-gray-700">{{ $grn->received_date }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-gray-700">{{ $grn->supplier->name }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    {{ $grn->status === 'Pending' ? 'bg-yellow-100 text-yellow-800 border border-yellow-200' : 'bg-green-100 text-green-800 border border-green-200' }}">
                                                    {{ $grn->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-10 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                    </svg>
                                                    <span class="text-gray-500 text-base">No GRNs created yet</span>
                                                    <span class="text-gray-400 text-sm mt-1">Your submitted GRNs will appear here</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>