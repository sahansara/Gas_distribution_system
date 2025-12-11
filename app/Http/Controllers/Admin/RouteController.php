<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryRoute;
use App\Models\User; // To list staff
use App\Models\Order;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    //list all routes with pending deliveries count
    public function index()
    {
        
        $routes = DeliveryRoute::with(['driver', 'assistant'])
            ->withCount(['orders as pending_orders_count' => function ($query) {
                $query->where('status', '!=', 'Completed');
            }])
            ->get();

        return view('sysadmin.routes.index', compact('routes'));
    }

    //create route form
    public function create()
    {
        // get staff as dirve and assistant in dropdown
        $staff = User::where('role', 'staff')->get();
        return view('admin.routes.create', compact('staff'));
    }

    //store route to table
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'vehicle_number' => 'required|string',
            'driver_id' => 'required|exists:users,id',
            'assistant_id' => 'nullable|exists:users,id',
            'planned_start_time' => 'required',
        ]);

        DeliveryRoute::create($request->all());

        return redirect()->route('admin.routes.index')->with('success', 'Route created successfully.');
    }

    //view route details
    public function show($id)
    {
        $route = DeliveryRoute::with(['driver', 'orders' => function($q) {
                // Prioritize urgent orders sorting
            $q->orderByDesc('is_urgent')->orderBy('id');
        }, 'orders.customer.user'])->findOrFail($id);

        return view('sysadmin.routes.show', compact('route'));
    }

    // update route status start or complete admin planening
    public function updateStatus(Request $request, $id)
    {
        $route = DeliveryRoute::findOrFail($id);
        
        if ($request->status === 'Active') {
            $route->update([
                'status' => 'Active',
                'actual_start_time' => now() // track Actual Start
            ]);
        } 
        elseif ($request->status === 'Completed') {
            $route->update([
                'status' => 'Completed',
                'actual_end_time' => now() // track Actual End
            ]);
        }

        return back()->with('success', 'Route status updated.');
    }
}