<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::guard('admin')->check()) {
            if (session()->has('admin_id')) {
                $admin = \App\Models\Admin::find(session('admin_id'));
                if ($admin) {
                    Auth::guard('admin')->login($admin);
                    return $next($request);
                }
            }
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
