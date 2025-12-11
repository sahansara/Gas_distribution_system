@props(['suppliers'])

<div x-data="grnFormHandler()" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-6 pb-3 border-b-2 border-gray-200">New Goods Received Note</h3>

        <form action="{{ route('admin.grn.store') }}" method="POST">
            @csrf

            <!-- Supplier, PO, and Date Selection -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">Supplier</label>
                    <select name="supplier_id" x-model="supplierId" @change="fetchPendingPos()" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">-- Choose Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">Purchase Order</label>
                    <select name="purchase_order_id" x-model="poId" @change="fetchPoItems()" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" :disabled="!supplierId" required>
                        <option value="">-- Select PO --</option>
                        <template x-for="po in pendingPos" :key="po.id">
                            <option :value="po.id" x-text="po.po_number"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-sm text-gray-700 mb-2">Date Received</label>
                    <input type="date" name="received_date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                </div>
            </div>

            <!-- Items Table -->
            <div x-show="poItems.length > 0" x-transition class="mb-8">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-lg mb-4">
                    <h4 class="font-bold text-sm text-gray-800 uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Check Items Against Delivery
                    </h4>
                </div>
                
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Ordered (Remaining)</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-green-700 uppercase tracking-wider">Received Good</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-red-700 uppercase tracking-wider">Damaged/Rejected</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <template x-for="(item, index) in poItems" :key="index">
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4">
                                        <span x-text="item.gas_name" class="font-semibold text-gray-900"></span>
                                        <input type="hidden" :name="`items[${index}][gas_type_id]`" :value="item.gas_type_id">
                                        <input type="hidden" :name="`items[${index}][ordered_qty]`" :value="item.remaining_ref">
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span x-text="item.remaining_ref" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800"></span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <input type="number" :name="`items[${index}][received_qty]`" x-model="item.received_now" min="0" class="w-full text-center font-semibold text-green-700 border-green-300 focus:ring-2 focus:ring-green-500 focus:border-green-500 rounded-lg shadow-sm">
                                    </td>

                                    <td class="px-6 py-4">
                                        <input type="number" :name="`items[${index}][damaged_qty]`" value="0" min="0" class="w-full text-center font-semibold text-red-600 border-red-300 focus:ring-2 focus:ring-red-500 focus:border-red-500 rounded-lg shadow-sm">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Remarks -->
            <div class="mb-8">
                <label class="block font-semibold text-sm text-gray-700 mb-2">Remarks / Notes</label>
                <textarea name="remarks" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="e.g. Delivery truck number, driver name, special conditions..."></textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end border-t pt-6">
                <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Submit GRN for Approval
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function grnFormHandler() {
        return {
            supplierId: '',
            poId: '',
            pendingPos: [],
            poItems: [],

            // Fetch POs when Supplier changes
            fetchPendingPos() {
                if (!this.supplierId) return;
                this.pendingPos = [];
                this.poItems = [];
                
                fetch(`/admin/api/supplier-pending-pos/${this.supplierId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.pendingPos = data;
                    });
            },

            // Fetch Items when PO changes
            fetchPoItems() {
                if (!this.poId) return;
                
                fetch(`/admin/api/po-items/${this.poId}`)
                    .then(res => res.json())
                    .then(data => {
                        
                        this.poItems = data.map(item => {
                            return {
                                gas_type_id: item.gas_type_id,
                                gas_name: item.gas_name,
                                
                                // read-only
                                remaining_ref: item.remaining_qty,
                                
                                //  EDITABLE value 
                                received_now: item.remaining_qty 
                            };
                        });
                    });
            }
        }
    }
</script>