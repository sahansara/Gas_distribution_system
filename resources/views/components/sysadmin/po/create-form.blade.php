@props(['suppliers', 'gasTypes'])

<div x-data="poFormHandler()" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Create New Purchase Order</h3>

        <form action="{{ route('admin.purchase_orders.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Select Supplier</label>
                    <select name="supplier_id" x-model="supplierId" @change="fetchPrices()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                        <option value="">-- Choose Supplier --</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Expected Delivery Date</label>
                    <input type="date" name="expected_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3">
                </div>
            </div>

            <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                <h4 class="font-bold text-sm text-gray-700 mb-3 uppercase tracking-wide">Order Items</h4>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Gas Type</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Quantity</th>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-500 uppercase">Unit Price (Rs)</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-500 uppercase">Total (Rs)</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-500 uppercase">Remove</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2">
                                    <select :name="`items[${index}][gas_type_id]`" x-model="item.gas_type_id" @change="updatePrice(index)" class="w-full rounded border-gray-300 text-sm" required>
                                        <option value="">Select Type</option>
                                        @foreach($gasTypes as $gas)
                                            <option value="{{ $gas->id }}">{{ $gas->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-4 py-2">
                                    <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" class="w-24 rounded border-gray-300 text-sm" required>
                                </td>

                                <td class="px-4 py-2">
                                    <input type="number" :name="`items[${index}][unit_price]`" x-model="item.unit_price" step="0.01" class="w-32 rounded border-gray-300 text-sm bg-gray-100" readonly>
                                </td>

                                <td class="px-4 py-2 text-right font-bold text-gray-700">
                                    <span x-text="(item.quantity * item.unit_price).toFixed(2)"></span>
                                </td>

                                <td class="px-4 py-2 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 font-bold text-xl">&times;</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <div class="mt-3">
                    <button type="button" @click="addItem()" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                        + Add Another Item
                    </button>
                </div>
            </div>

            <div class="flex justify-end items-center border-t pt-4">
                <div class="text-right mr-6">
                    <span class="block text-gray-500 text-sm">Grand Total Amount</span>
                    <span class="block text-2xl font-bold text-gray-900" x-text="'Rs. ' + grandTotal"></span>
                </div>
                
                <button type="submit" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                    Save Purchase Order
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function poFormHandler() {
        return {
            supplierId: '',
            supplierPrices: [], // Stores fetched contract prices
            items: [
                { gas_type_id: '', quantity: 1, unit_price: 0 } // Start with 1 empty row
            ],

            // 1. Fetch Contract Prices from Backend
            fetchPrices() {
                if (!this.supplierId) return;

                fetch(`/admin/api/supplier-prices/${this.supplierId}`)
                    .then(response => response.json())
                    .then(data => {
                        this.supplierPrices = data;
                        // Reset prices for current items based on new supplier
                        this.items.forEach((item, index) => this.updatePrice(index));
                    });
            },

            // 2. Find Price when Gas Type is selected
            updatePrice(index) {
                let selectedGasId = this.items[index].gas_type_id;
                
                // Find matching price in the fetched list
                let priceObj = this.supplierPrices.find(p => p.gas_type_id == selectedGasId);
                
                if (priceObj) {
                    this.items[index].unit_price = priceObj.contract_price;
                } else {
                    this.items[index].unit_price = 0; // Or keep existing
                }
            },

            // 3. Add New Row
            addItem() {
                this.items.push({ gas_type_id: '', quantity: 1, unit_price: 0 });
            },

            // 4. Remove Row
            removeItem(index) {
                this.items.splice(index, 1);
            },

            // 5. Calculate Grand Total dynamically
            get grandTotal() {
                let total = this.items.reduce((sum, item) => {
                    return sum + (item.quantity * item.unit_price);
                }, 0);
                return total.toFixed(2); // Format as currency string
            }
        }
    }
</script>