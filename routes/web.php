<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\SupplierPaymentController;
use App\Http\Controllers\Admin\GrnController;
use App\Http\Controllers\Admin\SupplierReportController;
use App\Http\Controllers\Staff\OrderController;
use App\Http\Controllers\Admin\DashboardController;
use App\Models\Customer;
use App\Models\Supplier;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // This handles all  customer routes 
    
    Route::resource('suppliers', SupplierController::class);
    
    // Purchase Orders
    Route::resource('purchase_orders', PurchaseOrderController::class); 
    // API Route for fetching prices of a supplier
    Route::get('/api/supplier-prices/{id}', [PurchaseOrderController::class, 'getSupplierPrices'])
         ->name('api.supplier.prices');
    
    // Supplier Payments routes
    Route::resource('payments', SupplierPaymentController::class);
    // API for pos of a supplier
    Route::get('/api/supplier-pos/{id}', [SupplierPaymentController::class, 'getSupplierPOs']);
    
    // Admin only approval route
    Route::post('/grn/{id}/approve', [GrnController::class, 'approve'])->name('grn.approve');
    
    // // AJAX Routes
    // Route::get('/api/supplier-pending-pos/{id}', [GrnController::class, 'getPendingPos']);
    // Route::get('/api/po-items/{id}', [GrnController::class, 'getPoItems']);


    // apporve po route by admin 
    Route::post('/purchase_orders/{id}/approve', [PurchaseOrderController::class, 'approve'])
         ->name('purchase_orders.approve'); 
    
    // Supplier Reports Routes
    Route::get('/reports', [SupplierReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/update-invoice/{id}', [SupplierReportController::class, 'updateInvoice'])->name('reports.update_invoice');
    Route::get('/reports/export/{id}', [SupplierReportController::class, 'exportPdf'])->name('reports.export_pdf');

    // Delivery Routes Management
    Route::resource('routes', \App\Http\Controllers\Admin\RouteController::class);
    
    // Custom route to Start/Stop the journey
    Route::post('/routes/{id}/status', [\App\Http\Controllers\Admin\RouteController::class, 'updateStatus'])
        ->name('routes.update_status');
});

// Staff Routes

Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Staff Dashboard Route
    Route::get('/dashboard', [App\Http\Controllers\Admin\GrnController::class, 'create'])->name('dashboard');
  
    
    // Orders
    Route::resource('orders', \App\Http\Controllers\Staff\OrderController::class);
    
    //status update 
    Route::post('/orders/{id}/next-status', [\App\Http\Controllers\Staff\OrderController::class, 'updateStatus'])->name('orders.next_status');
    
    // aapi for Pricing
    Route::get('/api/get-price', [\App\Http\Controllers\Staff\OrderController::class, 'getCustomerPrice'])->name('api.get_price');


    // Delivery Routes for Staff
    Route::resource('routes', \App\Http\Controllers\Staff\RouteController::class)->only(['index', 'show']);
    
    // Start/Stop Journey
    Route::post('/routes/{id}/status', [\App\Http\Controllers\Staff\RouteController::class, 'updateStatus'])
        ->name('routes.update_status');
});



Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('grn', GrnController::class);

    // ajax routes
    Route::get('/api/supplier-pending-pos/{id}', [GrnController::class, 'getPendingPos']);
    Route::get('/api/po-items/{id}', [GrnController::class, 'getPoItems']);

});

require __DIR__.'/auth.php';
