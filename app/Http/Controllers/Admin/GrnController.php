<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grn;
use App\Models\GrnItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\GasType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GrnController extends Controller
{
    // list of all GRNs
    public function index()
    {
        $grns = Grn::with(['supplier', 'purchaseOrder', 'creator'])->latest()->paginate(10);
        return view('sysadmin.GRN.index', compact('grns'));
    }

    // STAFF VIEW (Dashboard / Create Form)
    public function create()
    {
        $suppliers = Supplier::all();
        // Also fetch recent GRNs created by this staff member
        $myGrns = Grn::where('created_by', Auth::id())->latest()->limit(5)->get();
        
        return view('staff.dashboard', compact('suppliers', 'myGrns'));
    }

    
    // Returns only POs that are 'Approved' or 'Partially Received'
    public function getPendingPos($supplierId)
    {
        $pos = PurchaseOrder::where('supplier_id', $supplierId)
                            ->whereIn('status', ['Approved', 'Partially Received'])
                            ->get(['id', 'po_number']);
        return response()->json($pos);
    }

   
    // Returns items for the PO, calculating what's left to receive
    public function getPoItems($poId)
    {
        $poItems = PurchaseOrderItem::where('purchase_order_id', $poId)->with('gasType')->get();
        
        $data = $poItems->map(function($item) {
            // Check how many already received in previous GRNs
            $receivedSoFar = GrnItem::whereHas('grn', function($q) use ($item) {
                $q->where('purchase_order_id', $item->purchase_order_id);
            })->where('gas_type_id', $item->gas_type_id)->sum('received_qty');

            return [
                'gas_type_id' => $item->gas_type_id,
                'gas_name' => $item->gasType->name,
                'ordered_qty' => $item->quantity,
                'remaining_qty' => max(0, $item->quantity - $receivedSoFar) // Don't show negative
            ];
        });

        return response()->json($data);
    }

    // store GRN by save button in staff form
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'purchase_order_id' => 'required',
            'received_date' => 'required|date',
            'items' => 'required|array',
            'items.*.received_qty' => 'required|integer|min:0',
            'items.*.damaged_qty' => 'nullable|integer|min:0',
        ]);

        DB::transaction(function () use ($request) {
            //create header GRN
            $grn = Grn::create([
                'supplier_id' => $request->supplier_id,
                'purchase_order_id' => $request->purchase_order_id,
                'received_date' => $request->received_date,
                'remarks' => $request->remarks,
                'status' => 'Pending', // Must be approved by Admin
                'created_by' => Auth::id(),
            ]);

            // create Items
            foreach ($request->items as $item) {
                // Calculate missing: Remaining (Ordered) - Received - Damaged
                // Note: Frontend sends 'ordered_qty' (which is actually remaining from PO)
                $missing = $item['ordered_qty'] - ($item['received_qty'] + ($item['damaged_qty'] ?? 0));

                GrnItem::create([
                    'grn_id' => $grn->id,
                    'gas_type_id' => $item['gas_type_id'],
                    'ordered_qty' => $item['ordered_qty'], // Snapshot of what was expected
                    'received_qty' => $item['received_qty'],
                    'damaged_qty' => $item['damaged_qty'] ?? 0,
                    'missing_qty' => max(0, $missing),
                ]);
            }
        });

        return redirect()->route('staff.dashboard')->with('success', 'GRN Created. Waiting for Admin Approval.');
    }

    // approve GRN by admin
    public function approve($id)
    {
        // Security Check: Only Admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $grn = Grn::with('items')->findOrFail($id);

        if ($grn->status === 'Approved') {
            return back()->with('error', 'Already Approved');
        }

        DB::transaction(function () use ($grn) {
            //update stock and GRN status
            foreach ($grn->items as $item) {
                // Find Gas Type
                $gas = GasType::find($item->gas_type_id);
                // increment stock by received qty
                $gas->increment('current_stock', $item->received_qty);
            }

            // update GRN status
            $grn->update([
                'status' => 'Approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Check PO Status auto close logic chcek
            $po = PurchaseOrder::with('items')->find($grn->purchase_order_id);
            $allItemsReceived = true;

            foreach ($po->items as $poItem) {
                // Calculate total received then damaged for this PO item across all GRNs
                $totalReceived = GrnItem::whereHas('grn', function($q) use ($po) {
                    $q->where('purchase_order_id', $po->id);
                })->where('gas_type_id', $poItem->gas_type_id)
                  ->sum(DB::raw('received_qty + damaged_qty')); // Include damaged in total received

                if ($totalReceived < $poItem->quantity) {
                    $allItemsReceived = false;
                    break;
                }
            }

            if ($allItemsReceived) {
                $po->update(['status' => 'Completed']);
            } else {
                $po->update(['status' => 'Partially Received']);
            }
        });

        return back()->with('success', 'GRN Approved & Stock Updated!');
    }
}