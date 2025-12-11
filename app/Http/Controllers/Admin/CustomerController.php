<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Http\Middleware\RoleMiddleware;
class CustomerController extends Controller
{   
public function index()
    {
        
        $customers = Customer::with('user')->latest()->paginate(10);
        $suppliers = Supplier::latest()->get();
        return view('sysadmin.user_manage.index', compact('customers', 'suppliers'));

        return view('sysadmin.user_manage.index', compact('customers'));
    }

    public function store(Request $request)
    {   
        try {
            //validate input
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:1000',
                'customer_type' => ['required', Rule::in(['dealer','commercial','individual'])],
                'credit_limit' => 'nullable|numeric|min:0',
            ]);

            // Create user (password can be generated and emailed — here we use a default)
            $passwordPlain = \Str::random(8); // or use a default 'password123' for tests
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($passwordPlain),
                'role' => 'customer',
                'customer_type' => $data['customer_type'],
            ]);

            // Create customer profile
            $customer = Customer::create([
                'user_id' => $user->id,
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'customer_type' => $data['customer_type'],
                'credit_limit' => $data['credit_limit'] ?? 0,
                'outstanding_balance' => 0,
            ]);

            \Log::info('Customer created successfully', ['user_id' => $user->id, 'customer_id' => $customer->id]);

            return redirect()->route('sysadmin.customers')->with('success', 'Customer created successfully. Initial password: '.$passwordPlain);
        } catch (\Exception $e) {
            \Log::error('Error creating customer: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create customer. Please try again.');
        }
    }

    public function update(Request $request, Customer $customer)
{
    try {
        // Validate only the fields that are present in the request
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignore($customer->user_id)],
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:1000',
            'customer_type' => ['sometimes', Rule::in(['dealer', 'commercial', 'individual'])],
            'credit_limit' => 'nullable|numeric|min:0',
            'outstanding_balance' => 'nullable|numeric|min:0',
        ]);

        // Separate user fields from customer fields
        $userFields = [];
        $customerFields = [];

        // Map fields to their respective models
        if ($request->has('name')) {
            $userFields['name'] = $validated['name'];
        }
        
        if ($request->has('email')) {
            $userFields['email'] = $validated['email'];
        }

        if ($request->has('customer_type')) {
            $userFields['customer_type'] = $validated['customer_type'];
            $customerFields['customer_type'] = $validated['customer_type'];
        }

        // Customer-specific fields
        $customerOnlyFields = ['phone', 'address', 'credit_limit', 'outstanding_balance'];
        foreach ($customerOnlyFields as $field) {
            if ($request->has($field)) {
                $customerFields[$field] = $validated[$field];
            }
        }

        // Update user if there are user fields to update
        if (!empty($userFields)) {
            $customer->user()->update($userFields);
        }

        // Update customer if there are customer fields to update
        if (!empty($customerFields)) {
            $customer->update($customerFields);
        }

        \Log::info('Customer updated successfully', [
            'customer_id' => $customer->id,
            'updated_fields' => array_merge($userFields, $customerFields)
        ]);

        return redirect()->route('sysadmin.customers')
            ->with('success', 'Customer updated successfully.');
            
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withInput()
            ->withErrors($e->errors())
            ->with('error', 'Validation failed. Please check your input.');
            
    } catch (\Exception $e) {
        \Log::error('Error updating customer: ' . $e->getMessage(), [
            'customer_id' => $customer->id,
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'Failed to update customer. Please try again.');
    }
}

}
