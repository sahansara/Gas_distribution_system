<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\DeliveryRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RouteController extends Controller
{
    // view my assigned routes
    public function index()
    {
        $userId = Auth::id();

        // Find routes to drive
        $routes = DeliveryRoute::where('driver_id', $userId)
            ->orWhere('assistant_id', $userId)
            ->withCount(['orders as pending_orders_count' => function ($query) {
                $query->where('status', '!=', 'Completed');
            }])
            ->latest()
            ->paginate(10);

        return view('staff.routes.index', compact('routes'));
    }

    // view my stops 
    public function show($id)
    {
        // staff can only see routes 
        $userId = Auth::id();
        $route = DeliveryRoute::where('id', $id)
            ->where(function($q) use ($userId) {
                $q->where('driver_id', $userId)->orWhere('assistant_id', $userId);
            })
            ->with(['orders' => function($q) {
                $q->orderByDesc('is_urgent'); // Urgent First
            }, 'orders.customer.user'])
            ->firstOrFail();

        return view('staff.routes.show', compact('route'));
    }

    // update route status 
    public function updateStatus(Request $request, $id)
    {
        $route = DeliveryRoute::findOrFail($id);
        
        if ($request->status === 'Active') {
            $route->update([
                'status' => 'Active',
                'actual_start_time' => now()
            ]);
        } 
        elseif ($request->status === 'Completed') {
            $route->update([
                'status' => 'Completed',
                'actual_end_time' => now()
            ]);
        }

        return back()->with('success', 'Route status updated.');
    }
}