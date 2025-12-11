<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\SupplierPaymentController;
use App\Http\Controllers\Admin\GrnController;
use App\Http\Controllers\Admin\SupplierReportController;
use App\Models\Customer;
use App\Models\Supplier;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    //fech customers with user details
    $customers = Customer::with('user')->latest()->get();
    $suppliers = Supplier::latest()->get();
    
    // Pass the data to the view
    return view('dashboard', compact('customers', 'suppliers'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // This handles all  customer routes 
    Route::resource('customers', CustomerController::class);
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
});


Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    // Staff Dashboard Route
    Route::get('/dashboard', [App\Http\Controllers\Admin\GrnController::class, 'create'])->name('dashboard');
    // We reuse the GrnController 'create' method but map it to the staff dashboard view
});
Route::middleware(['auth', 'role:admin,staff'])->prefix('admin')->name('admin.')->group(function () {
    
   Route::resource('grn', GrnController::class);

    // AJAX Routes for GRN
    Route::get('/api/supplier-pending-pos/{id}', [GrnController::class, 'getPendingPos']);
    Route::get('/api/po-items/{id}', [GrnController::class, 'getPoItems']);

});

require __DIR__.'/auth.php';
