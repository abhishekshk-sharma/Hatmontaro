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
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        session(['admin_id' => $admin->id]);

        return redirect()->route('admin.dashboard');
    }

    public function showLogin()
    {

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        // $admin = Admin::where('email', $data['email'])->first();

        // if (! $admin) {
        //     return back()->withErrors(['error' => 'invalid credetial']);
        // }

        // if (Hash::check($data['password'], $admin->password)) {
        //     session()->put('admin_id', $admin->id);

        //     return redirect()->route('admin.dashboard');
        // }

        // Detect if input is an email; otherwise assume username/name column
        $loginType = filter_var($request->name, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $loginType => $request->name,
            'password' => $request->password,
        ];

        try {

            if (Auth::guard('admin')->attempt($data)) {
                $request->session()->regenerate();

                return redirect()->route('admin.dashboard');
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return back()->withErrors(['error' => 'invalid credetial']);

    }

    public function logout(Request $request)
    {

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
