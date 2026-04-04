<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // Cek apakah role user sesuai dengan parameter middleware
        if (Auth::user()->role !== $role) {
            // Jika tidak sesuai, kembalikan ke dashboard masing-masing
            return $this->redirectToDashboard(Auth::user()->role);
        }

        return $next($request);
    }

    private function redirectToDashboard($role)
    {
        return match ($role) {
            'super_admin' => redirect()->route('super_admin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'user' => redirect()->route('user.dashboard'),
            default => redirect('/'),
        };
    }
}