<?php

namespace KhangWeb\SharedPost\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VerifyHostToken
{
    public function handle(Request $request, Closure $next)
    {
        // Lấy token từ header Authorization
        $token = $request->bearerToken();

        if ($token !== config('sharedpost.host_token')) {


            return response()->json(['error' => 'Unauthorized'], 401);
        }


        return $next($request);
    }
}
