@props(['ledgerData'])

<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
    <div class="p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Supplier General Ledger</h3>
        <p class="text-sm text-gray-500 mb-6">Summary of total billed value vs total paid amounts per supplier.</p>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold uppercase tracking-wider">Supplier Name</th>
                        <th class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Total PO Value (Billed)</th>
                        <th class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Total Amount Paid</th>
                        <th class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Balance Due</th>
                        <th class="px-6 py-4 text-right text-sm font-bold uppercase tracking-wider">Overpayment</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ledgerData as $row)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ $row['supplier_name'] }}
                            </td>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-700">
                                {{ number_format($row['total_po_value'], 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-blue-600 font-medium">
                                {{ number_format($row['total_paid'], 2) }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $row['balance_due'] > 0 ? 'text-red-600' : 'text-gray-400' }}">
                                {{ $row['balance_due'] > 0 ? number_format($row['balance_due'], 2) : '-' }}
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold {{ $row['overpayment'] > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $row['overpayment'] > 0 ? number_format($row['overpayment'], 2) : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No ledger data available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>