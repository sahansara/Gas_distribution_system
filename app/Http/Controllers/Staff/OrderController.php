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
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // list all orders with filters
    public function index(Request $request)
    {
        \Log::info('OrderController@index - Starting', [
            'request_params' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $query = Order::with(['customer.user', 'route'])->latest();

            // Filters
            if ($request->has('status') && $request->status) {
                \Log::info('Applying status filter', ['status' => $request->status]);
                $query->where('status', $request->status);
            }
            
            if ($request->has('is_urgent') && $request->is_urgent == '1') {
                \Log::info('Applying urgent filter');
                $query->where('is_urgent', true);
            }
            
            $orders = $query->paginate(10);
            
            \Log::info('OrderController@index - Success', [
                'total_orders' => $orders->total(),
                'current_page' => $orders->currentPage()
            ]);

            return view('staff.orders.index', compact('orders'));
            
        } catch (\Exception $e) {
            \Log::error('OrderController@index - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // show create form
    public function create()
    {
        \Log::info('OrderController@create - Starting', [
            'user_id' => Auth::id()
        ]);

        try {
            $customers = Customer::with('user')->get();
            $routes = DeliveryRoute::where('is_active', true)->get();
            $gasTypes = GasType::all();

            \Log::info('OrderController@create - Data loaded', [
                'customers_count' => $customers->count(),
                'routes_count' => $routes->count(),
                'gas_types_count' => $gasTypes->count()
            ]);

            return view('staff.orders.create', compact('customers', 'routes', 'gasTypes'));
            
        } catch (\Exception $e) {
            \Log::error('OrderController@create - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    // show customer price for gas type using "ajax"
    public function getCustomerPrice(Request $request)
    {
        \Log::info('OrderController@getCustomerPrice - Starting', [
            'customer_id' => $request->customer_id,
            'gas_type_id' => $request->gas_type_id
        ]);

        try {
            $customerId = $request->customer_id;
            $gasTypeId = $request->gas_type_id;

            $customer = Customer::findOrFail($customerId);
            \Log::info('Customer found', [
                'customer_id' => $customer->id,
                'customer_name' => $customer->user->name ?? 'N/A'
            ]);

            $gasType = GasType::findOrFail($gasTypeId);
            \Log::info('Gas type found', [
                'gas_type_id' => $gasType->id,
                'gas_type_name' => $gasType->name ?? 'N/A'
            ]);

            // free defined method in GasType model to get price for customer
            $price = $gasType->getPriceForCustomer($customer);

            \Log::info('OrderController@getCustomerPrice - Success', [
                'calculated_price' => $price
            ]);

            return response()->json(['price' => $price]);
            
        } catch (\Exception $e) {
            \Log::error('OrderController@getCustomerPrice - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // store new order
    public function store(Request $request)
    {
        \Log::info('OrderController@store - Starting', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'delivery_route_id' => 'required|exists:delivery_routes,id',
                'items' => 'required|array',
                'items.*.gas_type_id' => 'required|exists:gas_types,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            \Log::info('Validation passed', ['validated_data' => $validated]);

            DB::transaction(function () use ($request) {
                \Log::info('Starting database transaction');

                // create order header with separate tables
                $order = Order::create([
                    'customer_id' => $request->customer_id,
                    'delivery_route_id' => $request->delivery_route_id,
                    'is_urgent' => $request->has('is_urgent'), // Checkbox check
                    'status' => 'Pending',
                    'created_by' => Auth::id(),
                    'total_amount' => 0 
                ]);

                \Log::info('Order header created', [
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'is_urgent' => $order->is_urgent,
                    'status' => $order->status
                ]);

                $grandTotal = 0;
                $customer = Customer::find($request->customer_id);

                \Log::info('Processing order items', [
                    'items_count' => count($request->items)
                ]);

                // create order items
                foreach ($request->items as $index => $item) {
                    \Log::info("Processing item #{$index}", [
                        'gas_type_id' => $item['gas_type_id'],
                        'quantity' => $item['quantity']
                    ]);

                    // reflect price at moment of order creation
                    $gasType = GasType::find($item['gas_type_id']);
                    $unitPrice = $gasType->getPriceForCustomer($customer);
                    $subtotal = $unitPrice * $item['quantity'];

                    \Log::info("Item #{$index} price calculation", [
                        'gas_type_name' => $gasType->name ?? 'N/A',
                        'unit_price' => $unitPrice,
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotal
                    ]);

                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'gas_type_id' => $item['gas_type_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal
                    ]);

                    \Log::info("Item #{$index} created", [
                        'order_item_id' => $orderItem->id
                    ]);

                    $grandTotal += $subtotal;
                }

                \Log::info('All items processed', [
                    'grand_total' => $grandTotal
                ]);

                // update order total amount
                $order->update(['total_amount' => $grandTotal]);

                \Log::info('Order total updated', [
                    'order_id' => $order->id,
                    'total_amount' => $order->total_amount
                ]);
            });

            \Log::info('OrderController@store - Transaction completed successfully');

            return redirect()->route('staff.orders.index')->with('success', 'Order Created Successfully');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('OrderController@store - Validation failed', [
                'errors' => $e->errors()
            ]);
            throw $e;
            
        } catch (\Exception $e) {
            \Log::error('OrderController@store - Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withInput()->with('error', 'Failed to create order: ' . $e->getMessage());
        }
    }

    // update order status
    public function updateStatus(Request $request, $id)
    {
        \Log::info('OrderController@updateStatus - Starting', [
            'order_id' => $id,
            'user_id' => Auth::id()
        ]);

        try {
            $order = Order::findOrFail($id);
            
            \Log::info('Order found', [
                'order_id' => $order->id,
                'current_status' => $order->status
            ]);

            // Basic State Machine \Logic
            $statusMap = [
                'Pending' => 'Loaded',
                'Loaded' => 'Delivered',
                'Delivered' => 'Completed'
            ];

            if (isset($statusMap[$order->status])) {
                $newStatus = $statusMap[$order->status];
                
                \Log::info('Updating order status', [
                    'order_id' => $order->id,
                    'old_status' => $order->status,
                    'new_status' => $newStatus
                ]);

                $order->update(['status' => $newStatus]);
                
                \Log::info('OrderController@updateStatus - Success', [
                    'order_id' => $order->id,
                    'updated_status' => $order->status
                ]);
                
                return back()->with('success', 'Order status updated to ' . $order->status);
            } else {
                \Log::warning('OrderController@updateStatus - Invalid status transition', [
                    'order_id' => $order->id,
                    'current_status' => $order->status
                ]);
                
                return back()->with('error', 'Cannot update status from ' . $order->status);
            }
            
        } catch (\Exception $e) {
            \Log::error('OrderController@updateStatus - Error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }
}