<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class player
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Check if the authenticated user is an player  
         if ($request->user() instanceof \App\Models\User) {  
            return $next($request);  
        }  

        return response()->json(['error' => 'Unauthorized'], 403); 
    }
}
