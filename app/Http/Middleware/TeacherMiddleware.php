<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Use the 'student' guard for authentication
        if (auth('teacher')->check()) {
            return $next($request);
        }
        // if (Auth::Student()->role !== 'student') {
        //     return redirect('/login')->with('error', 'Unauthorized access to the student panel.');
        // }
        // Redirect to login if not authenticated or not a student
        return redirect()->route('teacher.login')->with('error', 'Access denied. Please sign up or log in as a teacher to continue');
    }
}
