<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Student;
use Symfony\Component\HttpFoundation\Response;

class StudentMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth('student')->check()) {
            return $next($request);
        }
        return redirect()->route('student.login')->with('error', 'Access denied. Please sign up or log in as a student to continue');
    }
}
