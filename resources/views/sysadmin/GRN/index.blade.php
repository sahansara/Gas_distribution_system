<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('GRN Approval Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'pending' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tab Navigation -->
            <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200">
                <div class="flex">
                    <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'border-b-2 border-red-500 text-red-700 bg-red-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                        class="flex-1 py-4 px-4 text-center font-semibold text-sm transition duration-150 flex items-center justify-center gap-2">
                        <span class="text-lg">!</span>
                        <span>Pending Approvals</span>
                    </button>
                    <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'border-b-2 border-indigo-500 text-indigo-700 bg-indigo-50' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-50'"
                        class="flex-1 py-4 px-4 text-center font-semibold text-sm transition duration-150 flex items-center justify-center gap-2">
                        <span class="text-lg">📚</span>
                        <span>All GRN History</span>
                    </button>
                </div>
            </div>
            <!-- Tab Content -->
             <div class="mt-6">
            <!-- Pending Approvals Tab -->
            <div x-show="activeTab === 'pending'" x-transition.opacity>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-b-lg p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Action Required</h3>
                        <p class="text-sm text-gray-600 mt-1">Review and approve pending goods received notes</p>
                    </div>
                    
                    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">GRN #</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Supplier</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Created By</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($grns->where('status', 'Pending') as $grn)
                                    <tr class="hover:bg-red-50 transition duration-150">
                                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $grn->grn_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $grn->supplier->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $grn->creator->name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $grn->received_date }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('admin.grn.approve', $grn->id) }}" method="POST" onsubmit="return confirm('Approve this GRN? Stock will be updated.');">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-2 py-1 rounded hover:bg-indigo-100">
                                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Approve Stock
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <p class="text-gray-600 font-medium">No pending GRNs</p>
                                                <p class="text-sm text-gray-500 mt-1">All caught up! Good job.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- All GRN History Tab -->
            <div x-show="activeTab === 'all'" style="display: none;" x-transition.opacity>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-b-lg p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900">Complete GRN Records</h3>
                        <p class="text-sm text-gray-600 mt-1">View all goods received notes history</p>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">GRN #</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Supplier</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date Received</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Approved By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($grns as $grn)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $grn->grn_number }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">{{ $grn->supplier->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                                {{ $grn->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                                {{ $grn->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">{{ $grn->received_date }}</td>
                                        <td class="px-6 py-4 text-right text-sm text-gray-600">
                                            {{ $grn->approved_by ? 'Admin ID: '.$grn->approved_by : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $grns->links() }}
                    </div>
                </div>
            </div>
             </div>
        </div>
    </div>
</x-app-layout>