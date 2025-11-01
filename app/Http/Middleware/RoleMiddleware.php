<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = $user->role;

        if ($role === 'superadmin') {
            if ($userRole !== 'superadmin') {
                abort(403, 'Unauthorized action. Hanya Super Admin yang dapat mengakses halaman ini.');
            }
        }
        elseif ($role === 'admin') {
            if (!in_array($userRole, ['superadmin', 'admin'])) {
                abort(403, 'Unauthorized action. Hanya Admin dan Super Admin yang dapat mengakses halaman ini.');
            }
        }
        elseif ($role === 'user') {
            if (!in_array($userRole, ['superadmin', 'admin', 'user'])) {
                abort(403, 'Unauthorized action. Anda tidak memiliki akses ke halaman ini.');
            }
        }
        else {
            abort(403, 'Invalid role specified.');
        }

        return $next($request);
    }
}