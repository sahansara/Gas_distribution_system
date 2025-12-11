@props(['customers'])

<div x-data="{ showCreateForm: {{ $errors->any() ? 'true' : 'false' }} }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Customer List</h3>
            
            <button @click="showCreateForm = !showCreateForm" 
                    :class="showCreateForm ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700'"
                    class="px-6 py-3 border border-transparent rounded-md font-bold text-sm text-black uppercase tracking-widest transition ease-in-out duration-150 shadow-md">
                <span x-show="!showCreateForm">+ Add New Customer</span>
                <span x-show="showCreateForm" style="display: none;">Cancel</span>
            </button>
        </div>

        <div x-show="showCreateForm" style="display: none;" x-transition class="mb-8 p-6 bg-gray-50 border rounded-lg shadow-inner">
            <h4 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2"> +  Add New Customer</h4>
            
            <form action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" required placeholder="Enter full name">
                        @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" required placeholder="email@example.com">
                        @error('email') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Customer Type</label>
                        <select name="customer_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3 bg-white">
                            <option value="individual">Individual (Standard Price)</option>
                            <option value="dealer">Dealer (Wholesale Price)</option>
                            <option value="commercial">Commercial (Bulk Price)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" placeholder="07XX XXX XXX">
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Credit Limit</label>
                        <input type="number" step="0.01" name="credit_limit" value="{{ old('credit_limit', 0) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3">
                    </div>

                    <div class="col-span-1 md:col-span-2 lg:col-span-3">
                        <label class="block font-bold text-sm text-gray-700 mb-1">Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" placeholder="Street Address, City">
                    </div>
                </div>

                <div class="mt-6 flex justify-end border-t pt-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-black font-bold py-3 px-6 rounded shadow-md transition transform hover:scale-105">
                        Save Customer Details
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                        <tr x-data="{ editing: false }" class="hover:bg-blue-50 transition duration-150 ease-in-out">
                            
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-medium text-gray-900 text-center">
                                <span x-show="!editing">{{ $customer->user->name ?? 'N/A' }}</span>
                                <input x-show="editing" form="update-form-{{ $customer->id }}" type="text" name="name" value="{{ $customer->user->name ?? '' }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ $customer->user->email ?? 'N/A' }}</span>
                                <input x-show="editing" form="update-form-{{ $customer->id }}" type="email" name="email" value="{{ $customer->user->email ?? '' }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-center">
                                <span x-show="!editing" class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full shadow-sm 
                                    {{ $customer->customer_type === 'dealer' ? 'bg-purple-100 text-purple-800' : '' }}
                                    {{ $customer->customer_type === 'commercial' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $customer->customer_type === 'individual' ? 'bg-gray-100 text-gray-800' : '' }}">
                                    {{ ucfirst($customer->customer_type) }}
                                </span>
                                <select x-show="editing" form="update-form-{{ $customer->id }}" name="customer_type" class="text-xs rounded border-gray-300 py-1 bg-yellow-50">
                                    <option value="dealer" {{ $customer->customer_type == 'dealer' ? 'selected' : '' }}>Dealer</option>
                                    <option value="commercial" {{ $customer->customer_type == 'commercial' ? 'selected' : '' }}>Commercial</option>
                                    <option value="individual" {{ $customer->customer_type == 'individual' ? 'selected' : '' }}>Individual</option>
                                </select>
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ $customer->phone ?? '-' }}</span>
                                <input x-show="editing" form="update-form-{{ $customer->id }}" type="text" name="phone" value="{{ $customer->phone ?? '' }}" class="text-center text-sm rounded border-gray-300 w-32 px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ Str::limit($customer->address, 25) }}</span>
                                <input x-show="editing" form="update-form-{{ $customer->id }}" type="text" name="address" value="{{ $customer->address ?? '' }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-right text-sm font-medium">
                                <form id="update-form-{{ $customer->id }}" action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('PUT')
                                </form>

                                <div x-show="!editing" class="flex justify-end space-x-3">
                                    <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">Edit</button>
                                    
                                    <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-3 py-1 rounded hover:bg-red-100">Delete</button>
                                    </form>
                                </div>

                                <div x-show="editing" class="flex justify-end gap-2">
                                    <button type="submit" form="update-form-{{ $customer->id }}" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">Save</button>
                                    <button @click="editing = false" type="button" class="text-gray-600 hover:text-gray-900 px-2 py-1 text-xs border rounded font-bold bg-white">Cancel</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 font-medium">
                                No customers found in the system. Click the "+ Add New Customer" button to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
    </div>
</div>