<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use App\Models\GasType;
use App\Models\DeliveryRoute;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // kpi calculations
        $totalRevenue = Order::where('status', 'Completed')->sum('total_amount');
        $pendingOrders = Order::where('status', 'Pending')->count();
        $totalCustomers = Customer::count();
        $activeRoutes = DeliveryRoute::where('status', 'Active')->count();

        // stock check
        $lowStockItems = GasType::where('current_stock', '<', 50)->get();

        //chart data last 7 days sales
        $dates = collect();
        $salesData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('M d'));
            
            $sum = Order::whereDate('created_at', $date->format('Y-m-d'))
                        ->sum('total_amount');
            $salesData->push($sum);
        }

        //chart data orders by status
        $orderStats = Order::select('status', DB::raw('count(*) as total'))
                           ->groupBy('status')
                           ->pluck('total', 'status');

        return view('dashboard', compact(
            'totalRevenue', 'pendingOrders', 'totalCustomers', 'activeRoutes',
            'lowStockItems', 'dates', 'salesData', 'orderStats'
        ));
    }
}