<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckTeamAdminOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        $user = Auth::user();

        // Cek apakah user adalah "Team Admin"
        $isTeamAdmin = \App\Models\BusinessMember::where('user_id', $user->id)
            ->whereHas('position', function ($query) {
                $query->whereIn('name', ['Team Admin', 'Marketing']);
            })
            ->exists();

        if ($isTeamAdmin) {
            // Jika user adalah Team Admin, izinkan akses
            return $next($request);
        }

        // Jika bukan Team Admin, periksa permission
        foreach ($permissions as $permission) {
            if ($user->can($permission)) {
                return $next($request); // Izinkan akses jika salah satu permission terpenuhi
            }
        }

        // Jika tidak memenuhi syarat, tolak akses
        abort(403, 'Unauthorized access.');

    }
}
