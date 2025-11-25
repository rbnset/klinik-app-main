<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        $roleName = $user?->role?->name;

        // Jika user tidak login atau role tidak ada atau tidak termasuk role yang diizinkan
        if (!$user || !$roleName || !in_array(strtolower($roleName), array_map('strtolower', $roles))) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // Lanjutkan request
        return $next($request);
    }
}
