<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        Log::info('RoleMiddleware Executed'); 
    
        if (!$request->user()) {
            Log::warning('User is not authenticated');
            abort(403, 'Unauthorized');
        }
    
        if ($request->user()->role !== $role) {
            Log::warning('User does not have the required role', ['required' => $role, 'user_role' => $request->user()->role]);
            abort(403, 'Unauthorized');
        }
    
        return $next($request);
    }
}
