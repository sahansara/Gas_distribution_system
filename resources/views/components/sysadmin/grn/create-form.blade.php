@props(['suppliers'])

<div x-data="grnFormHandler()" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">New Goods Received Note</h3>

        <form action="{{ route('admin.grn.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Supplier</label>
                    <select name="supplier_id" x-model="supplierId" @change="fetchPendingPos()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                        <option value="">-- Choose Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Purchase Order</label>
                    <select name="purchase_order_id" x-model="poId" @change="fetchPoItems()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" :disabled="!supplierId" required>
                        <option value="">-- Select PO --</option>
                        <template x-for="po in pendingPos" :key="po.id">
                            <option :value="po.id" x-text="po.po_number"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Date Received</label>
                    <input type="date" name="received_date" value="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                </div>
            </div>

            <div x-show="poItems.length > 0" x-transition class="mb-6 bg-gray-50 p-4 rounded-lg border">
                <h4 class="font-bold text-sm text-gray-700 mb-3 uppercase tracking-wide">Check Items Against Delivery</h4>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-700 uppercase">Item</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-700 uppercase">Ordered (Remaining)</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-green-700 uppercase">Received Good</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-red-700 uppercase">Damaged/Rejected</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
    <template x-for="(item, index) in poItems" :key="index">
        <tr>
            <td class="px-4 py-3">
                <span x-text="item.gas_name" class="font-bold text-gray-800"></span>
                <input type="hidden" :name="`items[${index}][gas_type_id]`" :value="item.gas_type_id">
                
                <input type="hidden" :name="`items[${index}][ordered_qty]`" :value="item.remaining_ref">
            </td>

            <td class="px-4 py-3 text-center">
                <span x-text="item.remaining_ref" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"></span>
            </td>

            <td class="px-4 py-3">
                <input type="number" :name="`items[${index}][received_qty]`" x-model="item.received_now" min="0" class="w-full text-center font-bold text-green-700 border-green-300 focus:ring-green-500 rounded-md shadow-sm">
            </td>

            <td class="px-4 py-3">
                <input type="number" :name="`items[${index}][damaged_qty]`" value="0" min="0" class="w-full text-center text-red-600 border-red-300 focus:ring-red-500 rounded-md shadow-sm">
            </td>
        </tr>
    </template>
</tbody>
                </table>
            </div>

            <div class="mb-6">
                <label class="block font-bold text-sm text-gray-700 mb-1">Remarks / Notes</label>
                <textarea name="remarks" rows="2" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2" placeholder="e.g. Delivery truck number, driver name..."></textarea>
            </div>

            <div class="flex justify-end border-t pt-4">
                <button type="submit" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
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