<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\SupplierPayment;
use App\Models\GrnItem;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // PDF generation package

class SupplierReportController extends Controller
{
    // Show the Supplier Report Page
    public function index(Request $request)
    {
        $suppliers = Supplier::all();
        $selectedSupplier = null;
        $stats = null;
        $history = null;

        // if a supplier is selected cal the stats
        if ($request->has('supplier_id') && $request->supplier_id != '') {
            $supplierId = $request->supplier_id;
            $selectedSupplier = Supplier::find($supplierId);

            //refill tracking
            $grnItems = GrnItem::whereHas('grn', function($q) use ($supplierId) {
                $q->where('supplier_id', $supplierId)->where('status', 'Approved');
            })->get();

            $stats = [
                'total_cylinders_received' => $grnItems->sum('received_qty'),
                'total_damaged' => $grnItems->sum('damaged_qty'),
                // Assuming all GRN items are refills for simplicity
                'total_refills' => $grnItems->sum('received_qty'), 
                // Group by Gas Type for the summary table
                'by_type' => $grnItems->groupBy('gas_type_id')->map(function($row) {
                    return [
                        'name' => $row->first()->gasType->name,
                        'qty' => $row->sum('received_qty')
                    ];
                }),
            ];

            // payment tracking
            $stats['total_po_value'] = PurchaseOrder::where('supplier_id', $supplierId)
                                        ->where('status', '!=', 'Pending')
                                        ->sum('total_amount');

            $stats['total_invoice_value'] = PurchaseOrder::where('supplier_id', $supplierId)
                                        ->sum('invoice_amount'); // The actual billed amount form supplier

            $stats['total_paid'] = SupplierPayment::where('supplier_id', $supplierId)->sum('amount');
            
            // Comparison Logic
            $stats['outstanding'] = $stats['total_invoice_value'] - $stats['total_paid'];

            // payment and PO history
            $history = [
                'payments' => SupplierPayment::where('supplier_id', $supplierId)->latest()->get(),
                'pos' => PurchaseOrder::where('supplier_id', $supplierId)->latest()->get(),
            ];
        }

        return view('sysadmin.refill.index', compact('suppliers', 'selectedSupplier', 'stats', 'history'));
    }

 //update invoice details for a PO
    public function updateInvoice(Request $request, $poId)
    {
        $request->validate([
            'supplier_invoice_no' => 'required|string',
            'invoice_amount' => 'required|numeric|min:0',
        ]);

        PurchaseOrder::where('id', $poId)->update([
            'supplier_invoice_no' => $request->supplier_invoice_no,
            'invoice_amount' => $request->invoice_amount,
        ]);

        return back()->with('success', 'Invoice details updated successfully.');
    }

    // Export Supplier Report as PDF
    public function exportPdf($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        
        //  Fetch Supplier Prices
        $prices = \App\Models\SupplierProduct::where('supplier_id', $supplierId)
                    ->pluck('contract_price', 'gas_type_id');

        // fetch GRN Items for Refill Analysis
        $grnItems = GrnItem::whereHas('grn', function($q) use ($supplierId) {
            $q->where('supplier_id', $supplierId)->where('status', 'Approved');
        })->get();

        //  Refill Analysis with Costs
        $refillAnalysis = $grnItems->groupBy('gas_type_id')->map(function($items, $gasTypeId) use ($prices) {
            $qty = $items->sum('received_qty');
            $damaged = $items->sum('damaged_qty');
            $unitPrice = $prices[$gasTypeId] ?? 0; // Default to 0 if no contract price found
            $totalCost = $qty * $unitPrice;

            return [
                'name' => $items->first()->gasType->name,
                'received_qty' => $qty,
                'damaged_qty' => $damaged,
                'unit_price' => $unitPrice,
                'total_cost' => $totalCost
            ];
        });

       // Overall Payment Stats
        $totalPo = PurchaseOrder::where('supplier_id', $supplierId)
                        ->where('status', '!=', 'Pending')
                        ->sum('total_amount');
                        
        $totalInv = PurchaseOrder::where('supplier_id', $supplierId)
                        ->sum('invoice_amount');

        $totalPaid = SupplierPayment::where('supplier_id', $supplierId)
                        ->sum('amount');

        $data = [
            'supplier' => $supplier,
            'date' => now()->format('Y-m-d'),
            'total_po_value' => $totalPo,
            'total_invoice_value' => $totalInv,
            'total_paid' => $totalPaid,
            'outstanding' => $totalInv - $totalPaid,
            
            // Pass the new Refill Analysis with Costs
            'refill_analysis' => $refillAnalysis,
            'total_refill_cost' => $refillAnalysis->sum('total_cost'),
            
            'pos' => PurchaseOrder::where('supplier_id', $supplierId)->latest()->get(),
        ];

        $pdf = Pdf::loadView('sysadmin.refill.pdf', $data);
        $pdf = Pdf::loadView('sysadmin.refill.pdf', $data);
        
        // Force the browser to treat this as a new pdf link genrated each time
        return $pdf->download('Supplier_Report_'.$supplier->name.'.pdf')
                   ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                   ->header('Pragma', 'no-cache')
                   ->header('Expires', '0');
    }
}