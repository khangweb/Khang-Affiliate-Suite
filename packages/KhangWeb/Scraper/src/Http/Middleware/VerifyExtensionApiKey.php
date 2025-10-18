<?php 

namespace KhangWeb\Scraper\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use KhangWeb\Scraper\Models\DomainToken;
use Illuminate\Support\Facades\Log;

class VerifyExtensionApiKey
{
    public function handle($request, Closure $next)
    {
        $apiKey = $request->bearerToken();

        $isValid = DomainToken::where('access_token', $apiKey)
            ->where('token_expires_at', '>', now())
            ->exists();

        if (! $isValid) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
