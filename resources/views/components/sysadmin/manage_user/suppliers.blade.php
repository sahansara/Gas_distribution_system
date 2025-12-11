@props(['suppliers'])

<div x-data="{ showCreateForm: {{ $errors->any() ? 'true' : 'false' }} }" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-gray-900">Supplier Management</h3>
            
            <button @click="showCreateForm = !showCreateForm" 
                    :class="showCreateForm ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700'"
                    class="px-6 py-3 border border-transparent rounded-md font-bold text-sm text-black uppercase tracking-widest transition ease-in-out duration-150 shadow-md">
                <span x-show="!showCreateForm">+ Add New Supplier</span>
                <span x-show="showCreateForm" style="display: none;">Cancel</span>
            </button>
        </div>

        <div x-show="showCreateForm" style="display: none;" x-transition class="mb-8 p-6 bg-gray-50 border rounded-lg shadow-inner">
            <h4 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Register New Supplier</h4>
            
            <form action="{{ route('admin.suppliers.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Company Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" required placeholder="e.g. Litro Gas">
                        @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Contact Person</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" placeholder="Manager Name">
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3" required placeholder="0112 123 456">
                        @error('phone') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Email (Optional)</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3">
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block font-bold text-sm text-gray-700 mb-1">Office Address</label>
                        <input type="text" name="address" value="{{ old('address') }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 h-10 px-3">
                    </div>
                </div>

                <div class="mt-6 flex justify-end border-t pt-4">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-black font-bold py-3 px-6 rounded shadow-md transition transform hover:scale-105">
                        Save Supplier
                    </button>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Company Name</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Contact Person</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                        <tr x-data="{ editing: false }" class="hover:bg-blue-50 transition duration-150 ease-in-out">
                            
                            <td class="px-6 py-6 whitespace-nowrap text-sm font-bold text-gray-900 text-center">
                                <span x-show="!editing">{{ $supplier->name }}</span>
                                <input x-show="editing" form="update-supplier-{{ $supplier->id }}" type="text" name="name" value="{{ $supplier->name }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50 font-bold">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ $supplier->contact_person ?? '-' }}</span>
                                <input x-show="editing" form="update-supplier-{{ $supplier->id }}" type="text" name="contact_person" value="{{ $supplier->contact_person ?? '' }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ $supplier->phone }}</span>
                                <input x-show="editing" form="update-supplier-{{ $supplier->id }}" type="text" name="phone" value="{{ $supplier->phone }}" class="text-center text-sm rounded border-gray-300 w-32 px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ $supplier->email ?? '-' }}</span>
                                <input x-show="editing" form="update-supplier-{{ $supplier->id }}" type="email" name="email" value="{{ $supplier->email ?? '' }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 text-sm text-gray-600 text-center">
                                <span x-show="!editing">{{ Str::limit($supplier->address, 20) }}</span>
                                <input x-show="editing" form="update-supplier-{{ $supplier->id }}" type="text" name="address" value="{{ $supplier->address ?? '' }}" class="text-center text-sm rounded border-gray-300 w-full px-2 py-1 bg-yellow-50">
                            </td>

                            <td class="px-6 py-6 whitespace-nowrap text-right text-sm font-medium">
                                <form id="update-supplier-{{ $supplier->id }}" action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST" class="hidden">
                                    @csrf
                                    @method('PUT')
                                </form>

                                <div x-show="!editing" class="flex justify-end space-x-3">
                                    <button @click="editing = true" class="text-indigo-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">Edit</button>
                                    
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure? This might affect existing POs.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 font-bold bg-red-50 px-3 py-1 rounded hover:bg-red-100">Delete</button>
                                    </form>
                                </div>

                                <div x-show="editing" class="flex justify-end gap-2">
                                    <button type="submit" form="update-supplier-{{ $supplier->id }}" class="text-green-600 hover:text-indigo-900 font-bold bg-indigo-50 px-3 py-1 rounded hover:bg-indigo-100">Save</button>
                                    <button @click="editing = false" type="button" class="text-gray-600 hover:text-gray-900 px-2 py-1 text-xs border rounded font-bold bg-white">Cancel</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500 font-medium">
                                No suppliers found. Please add your Gas Company (e.g. Shell/Litro).
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>