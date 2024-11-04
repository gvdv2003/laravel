<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon;

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


        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        \Log::info('Login days value before decoding:', ['login_days' => $user->login_days]);


        if (is_null($user->login_days)) {
            $user->login_days = json_encode([]);
            $user->save();
        }

        \Log::info('Login days value before decoding:', ['login_days' => $user->login_days]);

        $loginDays = collect(json_decode($user->login_days, true));


        if (!$loginDays->contains($today)) {
            $loginDays->push($today);
            $user->login_days = $loginDays->unique()->values()->toJson();
            $user->save();
        }

        return redirect()->intended(route('games.index', absolute: false));
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
