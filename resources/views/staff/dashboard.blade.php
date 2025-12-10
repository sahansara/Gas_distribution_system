<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Staff Portal - Goods Received') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'create' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200 mb-4">
                <div class="flex">
                    <button @click="activeTab = 'create'" 
                        :class="activeTab === 'create' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                        class="w-1/2 py-4 px-1 text-center font-bold text-sm transition duration-150">
                        📦 Receive New Stock (Create GRN)
                    </button>
                    <button @click="activeTab = 'history'" 
                        :class="activeTab === 'history' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                        class="w-1/2 py-4 px-1 text-center font-bold text-sm transition duration-150">
                        🕒 My Recent Submissions
                    </button>
                </div>
            </div>

            <div x-show="activeTab === 'create'" x-transition.opacity>
                <x-sysadmin.grn.create-form :suppliers="$suppliers" />
            </div>

            <div x-show="activeTab === 'history'" style="display: none;" x-transition.opacity>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">My Recently Created GRNs</h3>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">GRN #</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($myGrns as $grn)
                                    <tr>
                                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $grn->grn_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $grn->received_date }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-500">{{ $grn->supplier->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $grn->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $grn->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">No GRNs created yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>