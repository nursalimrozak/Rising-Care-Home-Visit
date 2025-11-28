<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirect based on role
            if ($user->hasRole(['superadmin', 'admin_staff'])) {
                \App\Helpers\LogActivity::record('LOGIN', 'User logged in', $user);
                return redirect()->intended('/admin/dashboard');
            } elseif ($user->isPetugas()) {
                \App\Helpers\LogActivity::record('LOGIN', 'Petugas logged in', $user);
                return redirect()->intended('/petugas/dashboard');
            } else {
                \App\Helpers\LogActivity::record('LOGIN', 'Customer logged in', $user);
                return redirect()->intended('/member/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            \App\Helpers\LogActivity::record('LOGOUT', 'User logged out', Auth::user());
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
