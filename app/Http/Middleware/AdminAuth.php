<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {

        \Log::info('AdminAuth middleware called', [
            'path' => $request->path(),
            'method' => $request->method(),
            'has_admin_id' => session()->has('admin_id'),
            'admin_id_value' => session()->get('admin_id'),
        ]);

        if (! Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}
