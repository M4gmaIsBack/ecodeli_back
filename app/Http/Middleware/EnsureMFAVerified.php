<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMFAVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next) {
        $user = Auth::user();
        if (!$user->mfaSecret || !$user->mfaSecret->is_verified) {
            return response()->json(['message' => 'MFA requis'], 403);
        }

        return $next($request);
    }

}
