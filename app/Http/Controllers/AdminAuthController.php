<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showRegister()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => trim($data['name']),
            'email' => trim($data['email']),
            'password' => $data['password'],
        ]);

        Auth::guard('admin')->login($admin);
        session(['admin_id' => $admin->id]);

        return redirect()->route('admin.dashboard');
    }

    public function showLogin()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('name'));
        $password = (string) $request->input('password');
        $remember = $request->boolean('remember');

        // Look up admin by either email OR username/name (case-insensitive)
        $admin = Admin::where('email', $loginInput)
            ->orWhere('name', $loginInput)
            ->orWhereRaw('LOWER(email) = ?', [strtolower($loginInput)])
            ->orWhereRaw('LOWER(name) = ?', [strtolower($loginInput)])
            ->first();

        if ($admin) {
            $authPassword = $admin->getAuthPassword();
            $valid = false;

            // 1. Standard Hash check
            if (Hash::check($password, $authPassword)) {
                $valid = true;
            }
            // 2. Direct PHP password_verify
            elseif (is_string($authPassword) && password_verify($password, $authPassword)) {
                $valid = true;
            }
            // 3. Fallback for $2b$ or $2a$ prefix if needed
            elseif (is_string($authPassword) && (str_starts_with($authPassword, '$2b$') || str_starts_with($authPassword, '$2a$'))) {
                $normalized = '$2y$' . substr($authPassword, 4);
                if (password_verify($password, $normalized) || Hash::check($password, $normalized)) {
                    $valid = true;
                }
            }

            if ($valid) {
                Auth::guard('admin')->login($admin, $remember);
                $request->session()->regenerate();
                session(['admin_id' => $admin->id]);

                return redirect()->intended(route('admin.dashboard'));
            }
        }

        return back()
            ->withInput($request->only('name'))
            ->withErrors(['error' => 'Invalid credentials. Please check your username/email and password.']);
    }

    public function get_hashed($password)
    {
        return Hash::make($password);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->forget('admin_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
