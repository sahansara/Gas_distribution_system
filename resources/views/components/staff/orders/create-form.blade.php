@props(['customers', 'routes', 'gasTypes'])

<div x-data="orderFormHandler()" class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2">Create New Customer Order</h3>

        <form action="{{ route('staff.orders.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                
                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Select Customer</label>
                    <select name="customer_id" x-model="customerId" @change="refreshPrices()" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                        <option value="">-- Choose Customer --</option>
                        @foreach($customers as $c)
                            <option value="{{ $c->id }}">{{ $c->user->name }} ({{ ucfirst($c->customer_type) }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-sm text-gray-700 mb-1">Assign Delivery Route</label>
                    <select name="delivery_route_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 h-10 px-3" required>
                        <option value="">-- Choose Route --</option>
                        @foreach($routes as $r)
                            <option value="{{ $r->id }}">{{ $r->name }} ({{ $r->vehicle_number }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center h-full pt-6">
                    <label class="inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_urgent" value="1" class="form-checkbox h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <span class="ml-2 text-red-700 font-bold uppercase tracking-wide">🔥 Mark as Urgent Order</span>
                    </label>
                </div>
            </div>

            <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                <h4 class="font-bold text-sm text-gray-700 mb-3 uppercase tracking-wide">Add Cylinders</h4>
                
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase">Cylinder Type</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-600 uppercase">Quantity</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-600 uppercase">Unit Price (Auto)</th>
                            <th class="px-4 py-2 text-right text-xs font-bold text-gray-600 uppercase">Subtotal</th>
                            <th class="px-4 py-2 text-center text-xs font-bold text-gray-600 uppercase">Remove</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        <template x-for="(item, index) in items" :key="index">
                            <tr>
                                <td class="px-4 py-2">
                                    <select :name="`items[${index}][gas_type_id]`" x-model="item.gas_type_id" @change="fetchItemPrice(index)" class="w-full rounded border-gray-300 text-sm" :disabled="!customerId" required>
                                        <option value="">Select Type</option>
                                        @foreach($gasTypes as $gas)
                                            <option value="{{ $gas->id }}">{{ $gas->name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td class="px-4 py-2">
                                    <input type="number" :name="`items[${index}][quantity]`" x-model="item.quantity" min="1" class="w-20 text-center rounded border-gray-300 text-sm" required>
                                </td>

                                <td class="px-4 py-2 text-right">
                                    <input type="text" x-model="item.unit_price" class="w-24 text-right rounded border-gray-300 text-sm bg-gray-100 text-gray-500" readonly>
                                </td>

                                <td class="px-4 py-2 text-right font-bold text-gray-800">
                                    <span x-text="(item.quantity * item.unit_price).toFixed(2)"></span>
                                </td>

                                <td class="px-4 py-2 text-center">
                                    <button type="button" @click="removeItem(index)" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                
                <div class="mt-3">
                    <button type="button" @click="addItem()" class="text-indigo-600 hover:text-indigo-900 font-bold text-sm">
                        + Add Another Cylinder
                    </button>
                </div>
            </div>

            <div class="flex justify-end items-center border-t pt-4">
                <div class="text-right mr-8">
                    <span class="block text-xs text-gray-500 uppercase">Total Amount</span>
                    <span class="block text-2xl font-bold text-gray-900" x-text="'Rs. ' + grandTotal"></span>
                </div>
                <button type="submit" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">
                    Confirm Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function orderFormHandler() {
        return {
            customerId: '',
            items: [
                { gas_type_id: '', quantity: 1, unit_price: 0 }
            ],

            // Add new row
            addItem() {
                this.items.push({ gas_type_id: '', quantity: 1, unit_price: 0 });
            },

            // Remove row
            removeItem(index) {
                this.items.splice(index, 1);
            },

            // Fetch Price from API
            fetchItemPrice(index) {
                let row = this.items[index];
                if (!this.customerId || !row.gas_type_id) return;

                fetch(`/staff/api/get-price?customer_id=${this.customerId}&gas_type_id=${row.gas_type_id}`)
                    .then(res => res.json())
                    .then(data => {
                        this.items[index].unit_price = data.price;
                    });
            },

            // If customer changes, refresh all prices
            refreshPrices() {
                this.items.forEach((item, index) => {
                    if (item.gas_type_id) this.fetchItemPrice(index);
                });
            },

            // Calculate Grand Total
            get grandTotal() {
                return this.items.reduce((sum, item) => sum + (item.quantity * item.unit_price), 0).toFixed(2);
            }
        }
    }
</script>