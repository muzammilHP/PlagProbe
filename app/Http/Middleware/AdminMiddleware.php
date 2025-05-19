<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Admin;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('admin')->check()) {
            return $next($request);
        }
        return redirect()->route('admin.login')->with('error', 'Access denied. Log in as a Admin to continue');
    }
}
