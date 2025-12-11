<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\GasType;
use App\Models\DeliveryRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
   // list all orders with filters
    public function index(Request $request)
    {
        $query = Order::with(['customer.user', 'route'])->latest();

        // Filters [cite: 20]
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        if ($request->has('is_urgent') && $request->is_urgent == '1') {
            $query->where('is_urgent', true);
        }
        
        $orders = $query->paginate(10);
        return view('staff.orders.index', compact('orders'));
    }

    // show create form
    public function create()
    {
        $customers = Customer::with('user')->get();
        $routes = DeliveryRoute::where('is_active', true)->get();
        $gasTypes = GasType::all();

        return view('staff.orders.create', compact('customers', 'routes', 'gasTypes'));
    }

    // show customer price for gas type using "ajax"
    public function getCustomerPrice(Request $request)
    {
        $customerId = $request->customer_id;
        $gasTypeId = $request->gas_type_id;

        $customer = Customer::findOrFail($customerId);
        $gasType = GasType::findOrFail($gasTypeId);

        // free defined method in GasType model to get price for customer
        $price = $gasType->getPriceForCustomer($customer);

        return response()->json(['price' => $price]);
    }

    // store new order
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'delivery_route_id' => 'required|exists:delivery_routes,id',
            'items' => 'required|array',
            'items.*.gas_type_id' => 'required|exists:gas_types,id',
            'items.*.quantity' => 'required|integer|min:1',
            
        ]);

        DB::transaction(function () use ($request) {
            // create order header with sparete tables
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'delivery_route_id' => $request->delivery_route_id,
                'is_urgent' => $request->has('is_urgent'), // Checkbox check
                'status' => 'Pending',
                'created_by' => Auth::id(),
                'total_amount' => 0 
            ]);

            $grandTotal = 0;
            $customer = Customer::find($request->customer_id);

            // create order items
            foreach ($request->items as $item) {
                // flect price at moment of order creation
                $gasType = GasType::find($item['gas_type_id']);
                $unitPrice = $gasType->getPriceForCustomer($customer);
                $subtotal = $unitPrice * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'gas_type_id' => $item['gas_type_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal
                ]);

                $grandTotal += $subtotal;
            }

            // udate order total amount
            $order->update(['total_amount' => $grandTotal]);
        });

        return redirect()->route('staff.orders.index')->with('success', 'Order Created Successfully');
    }

    // update order status
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Basic State Machine Logic
        $statusMap = [
            'Pending' => 'Loaded',
            'Loaded' => 'Delivered',
            'Delivered' => 'Completed'
        ];

        if (isset($statusMap[$order->status])) {
            $order->update(['status' => $statusMap[$order->status]]);
            
        
        }

        return back()->with('success', 'Order status updated to ' . $order->status);
    }
}