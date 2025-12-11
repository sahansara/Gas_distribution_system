<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Models\GasType;
use App\Models\SupplierProduct; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    // view purchase orders with filters
    public function index(Request $request)
    {
        \Log::info('PurchaseOrderController@index called', [
            'request_params' => $request->all()
        ]);

        // 1. Fetch POs for the List (with filters)
        $query = PurchaseOrder::with('supplier');

        if ($request->has('status') && $request->status != '') {
            \Log::debug('Filtering by status', ['status' => $request->status]);
            $query->where('status', $request->status);
        }

        if ($request->has('supplier_id') && $request->supplier_id != '') {
            \Log::debug('Filtering by supplier', ['supplier_id' => $request->supplier_id]);
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchaseOrders = $query->latest()->paginate(10);
        \Log::info('Purchase orders fetched', ['count' => $purchaseOrders->count()]);

        // 2. Fetch Data for the "Create Form" (Suppliers & Gas Types)
        $suppliers = Supplier::all();
        $gasTypes = GasType::all();
        
        \Log::debug('Suppliers and Gas Types loaded', [
            'suppliers_count' => $suppliers->count(),
            'gas_types_count' => $gasTypes->count()
        ]);

        // 3. Return the View
        return view('sysadmin.purchase_orders.index', compact('purchaseOrders', 'suppliers', 'gasTypes'));
    }

    // show create purchase order form
    public function create()
    {
        \Log::info('PurchaseOrderController@create called');
        
        $suppliers = Supplier::all();
        $gasTypes = GasType::all();
        
        \Log::debug('Create form data loaded', [
            'suppliers_count' => $suppliers->count(),
            'gas_types_count' => $gasTypes->count()
        ]);
        
        return view('sysadmin.purchase_orders.index', compact('suppliers', 'gasTypes'));
    }

  
    // When you select a supplier in the form, this API returns their prices
    public function getSupplierPrices($supplierId)
    {
        \Log::info('Getting supplier prices', ['supplier_id' => $supplierId]);
        
        $prices = SupplierProduct::where('supplier_id', $supplierId)
                    ->with('gasType')
                    ->get();
        
        \Log::debug('Supplier prices retrieved', [
            'supplier_id' => $supplierId,
            'prices_count' => $prices->count(),
            'prices' => $prices->toArray()
        ]);
        
        return response()->json($prices);
    }

    // store purchase order in database
    public function store(Request $request)
    {
        \Log::info('PurchaseOrderController@store called', [
            'request_data' => $request->all()
        ]);

        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'expected_date' => 'nullable|date',
            'items' => 'required|array|min:1', //  least one gas type
            'items.*.gas_type_id' => 'required|exists:gas_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        \Log::debug('Validation passed');

        try {
            DB::transaction(function () use ($request) {
                \Log::info('Starting database transaction');
                
                // po Create Header
                $po = PurchaseOrder::create([
                    'supplier_id' => $request->supplier_id,
                    'expected_date' => $request->expected_date,
                    'status' => 'Pending',
                    'total_amount' => 0, 
                ]);

                \Log::info('Purchase Order header created', [
                    'po_id' => $po->id,
                    'supplier_id' => $po->supplier_id,
                    'expected_date' => $po->expected_date
                ]);

                $totalAmount = 0;

                //  Create Items
                foreach ($request->items as $index => $item) {
                    \Log::debug("Processing item {$index}", ['item' => $item]);
                    
                    $subtotal = $item['quantity'] * $item['unit_price'];
                    $totalAmount += $subtotal;

                    \Log::debug("Item {$index} calculations", [
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $subtotal,
                        'running_total' => $totalAmount
                    ]);

                    $poItem = PurchaseOrderItem::create([
                        'purchase_order_id' => $po->id,
                        'gas_type_id' => $item['gas_type_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $subtotal,
                    ]);

                    \Log::info("Purchase Order Item created", [
                        'po_item_id' => $poItem->id,
                        'gas_type_id' => $poItem->gas_type_id,
                        'quantity' => $poItem->quantity
                    ]);
                }

                // Update Header total amount
                $po->update(['total_amount' => $totalAmount]);
                
                \Log::info('Purchase Order total updated', [
                    'po_id' => $po->id,
                    'total_amount' => $totalAmount,
                    'items_count' => count($request->items)
                ]);

                \Log::info('Transaction completed successfully');
            });

            \Log::info('Purchase Order created successfully');
            return redirect()->route('admin.purchase_orders.index')
                             ->with('success', 'Purchase Order Created Successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating Purchase Order', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return back()->with('error', 'Error creating PO: ' . $e->getMessage());
        }
    }
    //after create po approve purchase order by admin  
    public function approve($id)
    {
        $po = PurchaseOrder::findOrFail($id);

        if ($po->status !== 'Pending') {
            return back()->with('error', 'This PO is already processed.');
        }

        $po->update(['status' => 'Approved']);

        return back()->with('success', 'Purchase Order Approved! Staff can now receive goods.');
    }
}