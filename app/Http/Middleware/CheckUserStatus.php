<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->user_status === 'banned') {
            return response()->json([
                'message' => 'Your account is banned. Please contact support.'
            ], 403);
        }

        return $next($request);
    }
}

