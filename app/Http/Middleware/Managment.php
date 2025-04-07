<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;



class Managment
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $admin = Auth::guard('admins')->user();  

        if ($admin && $admin->type === 'manager') {  
            return $next($request);  
        }  

        return response()->json(['message' => 'Forbidden'], 403);  
    }
}
