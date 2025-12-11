<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierPayment;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierPaymentController extends Controller
{
    // all payment history with pagination
    public function index()
    {
        $payments = SupplierPayment::with(['supplier', 'purchaseOrder'])->latest()->paginate(10); 
        $ledgerData = $this->getLedgerData();
        
        // for dorpdown filter
        $suppliers = Supplier::all(); 
        return view('sysadmin.payments.index', compact('payments', 'ledgerData', 'suppliers'));
    }

    // create payment form
    public function create()
    {   // fetch all suppliers for dropdown
        $suppliers = Supplier::all();
        return view('sysadmin.payments.create', compact('suppliers'));
    }

    // store payment details in database
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_mode' => 'required|in:Cheque,Cash,Bank Transfer',
            'cheque_number' => 'required_if:payment_mode,Cheque', //cheque details required if payment mode is cheque
            'cheque_date' => 'nullable|date',
        ]);

        SupplierPayment::create($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Payment Recorded Successfully');
    }

    // load POs for a specific supplier via AJAX
    public function getSupplierPOs($supplierId)
    {
        $pos = PurchaseOrder::where('supplier_id', $supplierId)
                            ->whereIn('status', ['Approved', 'Completed']) // Only pay approved POs
                            ->get(['id', 'po_number', 'total_amount']);
        
        // Calculate balance for each PO to show in dropdown
        
        $pos->map(function($po) {
            $paid = SupplierPayment::where('purchase_order_id', $po->id)->sum('amount');
            $po->balance_due = $po->total_amount - $paid;
            return $po;
        });

        return response()->json($pos);
    }

    // Logic for Total PO vs Paid vs Overpayment
    private function getLedgerData()
    {
        $suppliers = Supplier::all();
        $ledger = [];

        foreach($suppliers as $supplier) {
            // cal Total Value of all POs for this supplier
            $totalPOValue = PurchaseOrder::where('supplier_id', $supplier->id)->sum('total_amount');

            // cal Amount Paid to this supplier
            $totalPaid = SupplierPayment::where('supplier_id', $supplier->id)->sum('amount');

            //find for Overpayment / balance
            $balance = $totalPOValue - $totalPaid;
            
            // if balance is negative, it means we paid MORE than PO value overpayment 
           
            $overpayment = ($balance < 0) ? abs($balance) : 0;
            $dueAmount = ($balance > 0) ? $balance : 0;
             // if balance is positive, it means we still OWE money
            $ledger[] = [
                'supplier_name' => $supplier->name,
                'total_po_value' => $totalPOValue,
                'total_paid' => $totalPaid,
                'balance_due' => $dueAmount,
                'overpayment' => $overpayment
            ];
        }

        return $ledger;
    }
}