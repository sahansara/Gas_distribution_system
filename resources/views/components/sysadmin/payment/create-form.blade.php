@props(['suppliers'])

<div x-data="paymentFormHandler()" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Record Supplier Payment</h3>

        <form action="{{ route('admin.payments.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Select Supplier</label>
                    <select name="supplier_id" x-model="supplierId" @change="fetchPOs()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                        <option value="">-- Choose Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Select Purchase Order</label>
                    <select name="purchase_order_id" x-model="poId" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" :disabled="!supplierId" required>
                        <option value="">-- Select PO --</option>
                        <template x-for="po in purchaseOrders" :key="po.id">
                            <option :value="po.id" x-text="po.po_number + ' (Due: Rs. ' + po.balance_due + ')'"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Payment Amount (Rs)</label>
                    <input type="number" name="amount" step="0.01" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required placeholder="0.00">
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Payment Date</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Payment Mode</label>
                    <select name="payment_mode" x-model="paymentMode" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3">
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                    </select>
                </div>

                <div x-show="paymentMode === 'Cheque'" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50 p-4 rounded border">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Cheque Number</label>
                        <input type="text" name="cheque_number" class="w-full rounded-md border-gray-300 shadow-sm h-10 px-3">
                    </div>
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Bank Name</label>
                        <input type="text" name="bank_name" class="w-full rounded-md border-gray-300 shadow-sm h-10 px-3">
                    </div>
                </div>

            </div>

            <div class="mt-6 flex justify-end border-t pt-4">
                <button type="submit" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                    Save Payment Record
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function paymentFormHandler() {
        return {
            supplierId: '',
            poId: '',
            paymentMode: 'Cheque',
            purchaseOrders: [],

            fetchPOs() {
                if (!this.supplierId) return;

                // Call the API po Controller
                fetch(`/admin/api/supplier-pos/${this.supplierId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.purchaseOrders = data;
                    });
            }
        }
    }
</script>