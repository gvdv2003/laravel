<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Carbon\Carbon; // Vergeet niet Carbon te importeren

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

        // Verkrijg de huidige gebruiker
        $user = Auth::user();
        $today = Carbon::today()->toDateString();
        \Log::info('Login days value before decoding:', ['login_days' => $user->login_days]);

        // Controleer of de login_days kolom al een waarde heeft, anders initialiseren
        if (is_null($user->login_days)) {
            $user->login_days = json_encode([]); // Start met een lege array
            $user->save();
        }

        \Log::info('Login days value before decoding:', ['login_days' => $user->login_days]);
        // Converteer opgeslagen login-dagen van JSON naar een array
        $loginDays = collect(json_decode($user->login_days, true));

        // Voeg vandaag toe aan login-dagen als het nog niet aanwezig is
        if (!$loginDays->contains($today)) {
            $loginDays->push($today);
            $user->login_days = $loginDays->unique()->values()->toJson(); // Update JSON-kolom met unieke dagen
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
