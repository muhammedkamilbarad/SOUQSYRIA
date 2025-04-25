<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = Auth::user();
        if(!$user || !$user->hasPermission($permission))
        {
            return response()->json([
                'status' => false,
                'message'=> 'Unauthorized, you can not access this route'
            ], 403);
        }
        return $next($request);
    }
}
