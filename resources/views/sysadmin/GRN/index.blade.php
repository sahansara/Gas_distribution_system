<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('GRN Approval Management') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ activeTab: 'pending' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-t-lg border-b border-gray-200 mb-4">
                <div class="flex">
                    <button @click="activeTab = 'pending'" 
                        :class="activeTab === 'pending' ? 'border-b-2 border-red-500 text-red-600 bg-red-50' : 'text-gray-500 hover:text-gray-700'"
                        class="w-1/2 py-4 px-1 text-center font-bold text-sm transition duration-150">
                        ⚠️ Pending Approvals
                    </button>
                    <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'border-b-2 border-indigo-500 text-indigo-600 bg-blue-50' : 'text-gray-500 hover:text-gray-700'"
                        class="w-1/2 py-4 px-1 text-center font-bold text-sm transition duration-150">
                        📚 All GRN History
                    </button>
                </div>
            </div>

            <div x-show="activeTab === 'pending'" x-transition.opacity>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Action Required</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">GRN #</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Created By</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-700 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($grns->where('status', 'Pending') as $grn)
                                    <tr class="hover:bg-red-50 transition">
                                        <td class="px-6 py-4 font-bold text-gray-900">{{ $grn->grn_number }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $grn->supplier->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $grn->creator->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $grn->received_date }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('admin.grn.approve', $grn->id) }}" method="POST" onsubmit="return confirm('Approve this GRN? Stock will be updated.');">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                                                    ✓ Approve Stock
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No pending GRNs. Good job!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'all'" style="display: none;" x-transition.opacity>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">GRN #</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Supplier</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Approved By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($grns as $grn)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-bold">{{ $grn->grn_number }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $grn->supplier->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $grn->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $grn->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500">
                                        {{ $grn->approved_by ? 'Admin ID: '.$grn->approved_by : 'Pending' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $grns->links() }}</div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>