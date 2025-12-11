@props(['selectedSupplier', 'stats', 'history'])

<div>
    <!-- Header with Supplier Name and PDF Button -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h3 class="text-2xl font-bold text-gray-900">{{ $selectedSupplier->name }}</h3>
            <p class="text-sm text-gray-600 mt-1">Performance & Financial Summary</p>
        </div>
        
        <a href="{{ route('admin.reports.export_pdf', $selectedSupplier->id) }}" target="_blank" class="inline-flex items-center px-5 py-2.5 bg-red-600 hover:bg-red-700 rounded-lg font-semibold text-sm text-white shadow transition duration-150">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Download PDF Report
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Card 1: Total Refills -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-blue-500">
            <div class="text-gray-600 text-xs font-semibold uppercase tracking-wide mb-2">Total Refills Received</div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($stats['total_cylinders_received']) }}</div>
            <div class="text-sm text-gray-500">cylinders</div>
            <div class="text-xs text-red-600 font-medium mt-2">Damaged: {{ $stats['total_damaged'] }}</div>
        </div>

        <!-- Card 2: Billed vs Ordered -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-purple-500">
            <div class="text-gray-600 text-xs font-semibold uppercase tracking-wide mb-3">Billed vs Ordered</div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">PO Value</span>
                    <span class="font-semibold text-gray-700">Rs. {{ number_format($stats['total_po_value']) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500">Invoice Value</span>
                    <span class="font-bold text-purple-700 text-lg">Rs. {{ number_format($stats['total_invoice_value']) }}</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Paid -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <div class="text-gray-600 text-xs font-semibold uppercase tracking-wide mb-2">Total Amount Paid</div>
            <div class="text-3xl font-bold text-green-600 mb-1">Rs. {{ number_format($stats['total_paid']) }}</div>
            <div class="text-xs text-gray-500 mt-2">Via Cheque/Cash</div>
        </div>

        <!-- Card 4: Outstanding -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 {{ $stats['outstanding'] > 0 ? 'border-red-500' : 'border-gray-300' }}">
            <div class="text-gray-600 text-xs font-semibold uppercase tracking-wide mb-2">Outstanding Balance</div>
            <div class="text-3xl font-bold {{ $stats['outstanding'] > 0 ? 'text-red-600' : 'text-gray-400' }} mb-1">
                Rs. {{ number_format($stats['outstanding']) }}
            </div>
            <div class="text-xs text-gray-500 mt-2">Invoice - Paid</div>
        </div>
    </div>

    <!-- Tabbed Content -->
    <div x-data="{ activeTab: 'reconciliation' }" class="bg-white shadow-sm sm:rounded-lg">
        
        <!-- Tabs Navigation -->
        <div class="border-b border-gray-200 bg-gray-50 rounded-t-lg">
            <div class="flex flex-wrap">
                <button @click="activeTab = 'reconciliation'" 
                    :class="activeTab === 'reconciliation' ? 'bg-white border-b-2 border-indigo-600 text-indigo-700' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'"
                    class="px-6 py-4 text-sm font-semibold focus:outline-none transition duration-150 flex items-center gap-2">
                    <span>🧾</span> Invoice Reconciliation
                </button>
                <button @click="activeTab = 'refills'" 
                    :class="activeTab === 'refills' ? 'bg-white border-b-2 border-indigo-600 text-indigo-700' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'"
                    class="px-6 py-4 text-sm font-semibold focus:outline-none transition duration-150 flex items-center gap-2">
                    <span>📦</span> Refill Volume Analysis
                </button>
                <button @click="activeTab = 'payments'" 
                    :class="activeTab === 'payments' ? 'bg-white border-b-2 border-indigo-600 text-indigo-700' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'"
                    class="px-6 py-4 text-sm font-semibold focus:outline-none transition duration-150 flex items-center gap-2">
                    <span>💰</span> Payment History
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            
            <!-- Invoice Reconciliation Tab -->
            <div x-show="activeTab === 'reconciliation'" x-transition.opacity>
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-600 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">
                                Action Required
                            </p>
                            <p class="text-sm text-yellow-700 mt-1">
                                Please enter the actual <span class="font-semibold">Invoice Number</span> and <span class="font-semibold">Billed Amount</span> sent by the supplier for each Approved PO.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">PO Number</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Our PO Value</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-blue-700 uppercase tracking-wider">Supplier Invoice No</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-blue-700 uppercase tracking-wider">Actual Billed Amount</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Variance</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($history['pos'] as $po)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $po->po_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $po->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 text-sm text-right font-semibold text-gray-900">
                                        Rs. {{ number_format($po->total_amount, 2) }}
                                    </td>

                                    <form action="{{ route('admin.reports.update_invoice', $po->id) }}" method="POST">
                                        @csrf
                                        
                                        <td class="px-6 py-4 text-center">
                                            <input type="text" name="supplier_invoice_no" value="{{ $po->supplier_invoice_no }}" 
                                                class="w-36 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center" 
                                                placeholder="Enter Invoice #">
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <input type="number" step="0.01" name="invoice_amount" value="{{ $po->invoice_amount }}" 
                                                class="w-36 text-sm border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-right font-semibold text-blue-700" 
                                                placeholder="0.00">
                                        </td>

                                        <td class="px-6 py-4 text-sm text-right font-bold {{ ($po->invoice_amount - $po->total_amount) > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            @if($po->invoice_amount)
                                                Rs. {{ number_format($po->invoice_amount - $po->total_amount, 2) }}
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow-sm transition duration-150">
                                                Save
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Refill Volume Analysis Tab -->
            <div x-show="activeTab === 'refills'" style="display: none;" x-transition.opacity>
                <h4 class="font-bold text-lg text-gray-800 mb-6">Total Gas Refills Received (Approved GRNs)</h4>
                <div class="max-w-2xl">
                    <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Cylinder Type</th>
                                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Received Qty</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($stats['by_type'] as $type)
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $type['name'] }}</td>
                                        <td class="px-6 py-4 text-sm text-right font-bold text-indigo-600">{{ number_format($type['qty']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment History Tab -->
            <div x-show="activeTab === 'payments'" style="display: none;" x-transition.opacity>
                <h4 class="font-bold text-lg text-gray-800 mb-6">Payment Transaction Log</h4>
                <div class="overflow-hidden rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">PO Number</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Payment Mode</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($history['payments'] as $pay)
                                <tr class="hover:bg-gray-50 transition duration-150">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pay->payment_date)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $pay->purchaseOrder->po_number }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $pay->payment_mode }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right font-bold text-green-600">Rs. {{ number_format($pay->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center">
                                        <div class="text-gray-400">
                                            <svg class="mx-auto h-12 w-12 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-sm font-medium">No payments found</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>