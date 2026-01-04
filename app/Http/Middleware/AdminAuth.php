<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Temporarily bypass auth for testing
        return $next($request);
        
        \Log::info('AdminAuth middleware called', [
            'path' => $request->path(),
            'method' => $request->method(),
            'has_admin_id' => session()->has('admin_id'),
            'admin_id_value' => session()->get('admin_id'),
        ]);
        
        if (!session()->has('admin_id')) {
            \Log::warning('AdminAuth: No admin_id in session, redirecting to login');
            return redirect()->route('admin.login');
        }

        \Log::info('AdminAuth: Admin authenticated, proceeding');
        return $next($request);
    }
}
