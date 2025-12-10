<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

      
        
        $user = $request->user();

        // If user is Admin, send to Customer List
        if ($user->role === 'admin') {
            return redirect()->route('/dashboard'); 
        }

        // If user is Staff, maybe send to Orders?
        if ($user->role === 'staff') {
            // return redirect()->route('admin.orders.index'); // Example
             return redirect('staff/dashboard');
        }

        // Default: Send to standard dashboard
        return redirect()->intended(route('dashboard', absolute: false));
        
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
