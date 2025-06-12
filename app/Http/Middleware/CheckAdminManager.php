<?php  

namespace App\Http\Middleware;  

use Closure;  
use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Auth;  

class CheckAdminManager  
{  
    /**  
     * Handle an incoming request.  
     *  
     * @param  \Illuminate\Http\Request  $request  
     * @param  \Closure  $next  
     * @return mixed  
     */  
    // public function handle(Request $request, Closure $next)  
    // {  
    //     $admin = Auth::user(); // Ensure you are using the correct guard  

    //     // Check if the admin is authenticated and has a type of 'manager'  
    //     if ($admin && $admin->type === 'manager') {  
    //         return $next($request);  
    //     }  

    //     return response()->json(['message' => 'Forbidden'], 403);  
    // }  
}  