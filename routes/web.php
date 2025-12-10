<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\PurchaseOrderController;
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
    
    // This handles all your customer routes (index, store, create, etc.)
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    // Purchase Orders
    Route::resource('purchase_orders', PurchaseOrderController::class);
    
    // API Route for fetching prices of a supplier
    Route::get('/api/supplier-prices/{id}', [PurchaseOrderController::class, 'getSupplierPrices'])
         ->name('api.supplier.prices');
    

});
require __DIR__.'/auth.php';
