<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
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
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();
    
        $user = auth()->user();
        \Log::info('User logged in', [
            'id' => $user->id,
            'type' => $user->type,
            'type_as_int' => (int)$user->type,
            'is_admin' => $user->isAdmin(),
            'isFinance' => $user->isFinance(),
            'isShipping' => $user->isShipping(),
            'isShipping' => $user->isShipping(),
            'isInventoryStaff' => $user->isInventoryStaff()
        ]);
    
        if ($user->isAdmin()) {
            return redirect()->intended(RouteServiceProvider::ADMIN_HOME);
        } elseif ($user->isFinance()) {
            return redirect()->intended(RouteServiceProvider::FINANCE_HOME);
        } elseif ($user->isShipping()) {
            return redirect()->intended(RouteServiceProvider::SHIPPING_HOME);
        } elseif ($user->isInventoryStaff()) {
            return redirect()->intended(RouteServiceProvider::INVENTORY_HOME);
        } else {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->session()->forget('cart');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
