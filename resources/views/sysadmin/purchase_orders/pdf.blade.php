<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Purchase Order {{ $po->po_number }}</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; }
        
        /* Header Section */
        .header-table { width: 100%; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        .company-info h1 { margin: 0; color: #dc2626; font-size: 24px; text-transform: uppercase; }
        .company-info p { margin: 2px 0; font-size: 12px; color: #666; }
        
        .po-title { text-align: right; }
        .po-title h2 { margin: 0; font-size: 28px; color: #1f2937; }
        .po-title p { margin: 5px 0 0; font-size: 14px; font-weight: bold; color: #4b5563; }

        /* Details Section */
        .details-table { width: 100%; margin-bottom: 30px; }
        .box { padding: 15px; border: 1px solid #e5e7eb; background: #f9fafb; border-radius: 5px; }
        .box h3 { margin: 0 0 10px; font-size: 12px; text-transform: uppercase; color: #6b7280; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .box p { margin: 3px 0; font-size: 13px; font-weight: bold; }
        .box span { font-weight: normal; color: #555; }

        /* Items Table */
        .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items-table th { background: #1f2937; color: white; padding: 10px; text-align: left; font-size: 12px; text-transform: uppercase; }
        .items-table td { padding: 10px; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        
        /* Total Section */
        .total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 14px; background: #f3f4f6; }
        
        /* Footer */
        .footer { margin-top: 50px; padding-top: 20px; border-top: 1px solid #eee; text-align: center; font-size: 10px; color: #9ca3af; }
        .status-stamp { 
            position: absolute; top: 150px; right: 50px; 
            border: 3px solid {{ $po->status == 'Approved' ? 'green' : ($po->status == 'Completed' ? '#4b5563' : 'orange') }};
            color: {{ $po->status == 'Approved' ? 'green' : ($po->status == 'Completed' ? '#4b5563' : 'orange') }};
            padding: 10px 20px; font-size: 20px; font-weight: bold; text-transform: uppercase; transform: rotate(-15deg); opacity: 0.7;
        }
    </style>
</head>
<body>

    <div class="status-stamp">{{ $po->status }}</div>

    <table class="header-table">
        <tr>
            <td class="company-info">
                <h1>Gas Distribution Co.</h1>
                <p>123 Main Street, Colombo 03</p>
                <p>Phone: +94 11 234 5678</p>
                <p>Email: admin@gasdist.lk</p>
            </td>
            <td class="po-title">
                <h2>PURCHASE ORDER</h2>
                <p># {{ $po->po_number }}</p>
                <p style="font-size: 12px; font-weight: normal; margin-top: 5px;">Date: {{ $po->created_at->format('Y-m-d') }}</p>
            </td>
        </tr>
    </table>

    <table class="details-table">
        <tr>
            <td width="48%" valign="top">
                <div class="box">
                    <h3>Vendor / Supplier</h3>
                    <p>{{ $supplier->name }}</p>
                    <p><span>Contact:</span> {{ $supplier->contact_person }}</p>
                    <p><span>Phone:</span> {{ $supplier->phone }}</p>
                    <p><span>Address:</span> {{ $supplier->address }}</p>
                </div>
            </td>
            <td width="4%"></td>
            <td width="48%" valign="top">
                <div class="box">
                    <h3>Ship To / Warehouse</h3>
                    <p>Central Warehouse</p>
                    <p><span>Attn:</span> Inventory Manager</p>
                    <p><span>Expected Delivery:</span> {{ $po->expected_date ?? 'Immediate' }}</p>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="45%">Description / Item</th>
                <th width="15%" class="text-center">Quantity</th>
                <th width="15%" class="text-right">Unit Price</th>
                <th width="20%" class="text-right">Total (Rs)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    <b>{{ $item->gasType->name }}</b>
                    <br><span style="font-size: 10px; color: #666;">Standard Refill Cylinder</span>
                </td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->subtotal, 2) }}</td>
            </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="3"></td>
                <td class="text-right">GRAND TOTAL</td>
                <td class="text-right">Rs. {{ number_format($po->total_amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 40px; border-top: 1px solid #ddd; padding-top: 10px;">
        <p style="font-weight: bold; font-size: 12px;">Remarks / Instructions:</p>
        <p style="font-size: 12px; color: #555;">{{ $po->remarks ?? 'None' }}</p>
    </div>

    <table style="width: 100%; margin-top: 60px;">
        <tr>
            <td width="40%" style="border-top: 1px solid #333; text-align: center; font-size: 12px; padding-top: 5px;">
                Authorized Signature
            </td>
            <td width="20%"></td>
            <td width="40%" style="border-top: 1px solid #333; text-align: center; font-size: 12px; padding-top: 5px;">
                Supplier Acceptance
            </td>
        </tr>
    </table>

    <div class="footer">
        This is a computer-generated document. Gas Distribution Management System &copy; {{ date('Y') }}
    </div>

</body>
</html>