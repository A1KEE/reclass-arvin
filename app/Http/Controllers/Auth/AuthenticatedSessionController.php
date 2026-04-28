<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // =========================
        // 🔥 FORCE CHANGE PASSWORD
        // =========================
        if ($user->must_change_password ?? false) {
            return redirect()->route('change.password');
        }

        // =========================
        // 🔥 ROLE-BASED (SPATIE)
        // =========================
        
        // QS FIRST (IMPORTANT)
        if ($user->hasRole('qs_editor')) {
                return redirect()->route('admin.dashboard');
            }

        // ADMIN
        if ($user->hasAnyRole(['super_admin', 'admin'])) {
            return redirect()->route('admin.dashboard');
        }

        // APPLICANT LAST
        if ($user->hasRole('applicant')) {
            return redirect()->route('applicant.dashboard');
        }

       Auth::logout();

        return redirect()->route('login')->withErrors([
            'email' => 'User has no assigned role.'
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}