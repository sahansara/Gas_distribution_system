<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Supplier Audit Report</title>
    <style>
        body { font-family: sans-serif; color: #333; }
        .header { width: 100%; border-bottom: 2px solid #ddd; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #2d3748; }
        .header p { margin: 5px 0 0; color: #718096; font-size: 14px; }
        
        .scorecards { width: 100%; margin-bottom: 20px; }
        .card { width: 32%; display: inline-block; background-color: #f7fafc; padding: 10px; border: 1px solid #e2e8f0; margin-right: 1%; vertical-align: top; }
        .card h3 { margin: 0 0 5px; font-size: 12px; color: #718096; text-transform: uppercase; }
        .card p { margin: 0; font-size: 18px; font-weight: bold; color: #2d3748; }
        .card .sub { font-size: 10px; color: #a0aec0; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 12px; }
        th { background-color: #edf2f7; text-align: left; padding: 8px; border-bottom: 1px solid #cbd5e0; font-weight: bold; color: #4a5568; }
        td { padding: 8px; border-bottom: 1px solid #e2e8f0; color: #4a5568; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row td { border-top: 2px solid #cbd5e0; font-weight: bold; background-color: #f7fafc; }
        
        .section-title { margin-top: 30px; margin-bottom: 10px; font-size: 14px; font-weight: bold; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 10px; color: #a0aec0; border-top: 1px solid #eee; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <table style="border: none; margin: 0;">
            <tr>
                <td style="border: none; padding: 0;">
                    <h1>{{ $supplier->name }}</h1>
                    <p>Supplier Performance & Refill Tracking</p>
                </td>
                <td style="border: none; padding: 0; text-align: right;">
                    <p>Generated: {{ $date }}</p>
                    <p>Contact: {{ $supplier->phone }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="scorecards">
        <div class="card">
            <h3>Billed (Invoice)</h3>
            <p>Rs. {{ number_format($total_invoice_value, 2) }}</p>
            <div class="sub">PO Value: {{ number_format($total_po_value) }}</div>
        </div>
        <div class="card">
            <h3>Total Paid</h3>
            <p style="color: green;">Rs. {{ number_format($total_paid, 2) }}</p>
            <div class="sub">Verified Payments</div>
        </div>
        <div class="card">
            <h3>Outstanding</h3>
            <p style="color: {{ $outstanding > 0 ? 'red' : '#2d3748' }};">
                Rs. {{ number_format($outstanding, 2) }}
            </p>
            <div class="sub">Invoice - Paid</div>
        </div>
    </div>

    <div class="section-title">1. Invoice Reconciliation (PO vs Actual Bill)</div>
    <table>
        <thead>
            <tr>
                <th>PO Number</th>
                <th>Date</th>
                <th class="text-right">PO Value</th>
                <th class="text-right">Invoice Value</th>
                <th class="text-right">Variance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pos as $po)
                <tr>
                    <td>{{ $po->po_number }}</td>
                    <td>{{ $po->created_at->format('Y-m-d') }}</td>
                    <td class="text-right">{{ number_format($po->total_amount, 2) }}</td>
                    <td class="text-right">{{ $po->invoice_amount ? number_format($po->invoice_amount, 2) : '-' }}</td>
                    <td class="text-right">
                        @if($po->invoice_amount)
                            {{ number_format($po->invoice_amount - $po->total_amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">TOTALS</td>
                <td class="text-right">{{ number_format($total_po_value, 2) }}</td>
                <td class="text-right">{{ number_format($total_invoice_value, 2) }}</td>
                <td class="text-right">{{ number_format($total_invoice_value - $total_po_value, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title" style="page-break-inside: avoid;">2. Refill Volume & Cost Analysis</div>
    <table>
        <thead>
            <tr>
                <th>Cylinder Type</th>
                <th class="text-center">Refills (Qty)</th>
                <th class="text-center">Damaged</th>
                <th class="text-right">Unit Cost (Contract)</th>
                <th class="text-right">Total Refill Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($refill_analysis as $data)
                <tr>
                    <td>{{ $data['name'] }}</td>
                    <td class="text-center">{{ $data['received_qty'] }}</td>
                    <td class="text-center" style="color: red;">{{ $data['damaged_qty'] }}</td>
                    <td class="text-right">Rs. {{ number_format($data['unit_price'], 2) }}</td>
                    <td class="text-right font-bold">Rs. {{ number_format($data['total_cost'], 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td>TOTALS</td>
                <td class="text-center">{{ $refill_analysis->sum('received_qty') }}</td>
                <td class="text-center">{{ $refill_analysis->sum('damaged_qty') }}</td>
                <td></td>
                <td class="text-right">Rs. {{ number_format($total_refill_cost, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Distribution Management System &copy; {{ date('Y') }}</p>
    </div>

</body>
</html>