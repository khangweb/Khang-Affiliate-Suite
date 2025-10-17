<?php
namespace KhangWeb\ClientMessage\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ValidateClientMessageToken
{
    // Số lần cho phép trong khoảng thời gian
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    public function handle(Request $request, Closure $next)
    {
        $id = $request->input('id');
        $ip = $request->ip();
        $token = $request->header('X-Client-Token');


        if (!$id || !$token) {
            return response()->json([
                'error' => 'Missing id or token.'
            ], 400);
        }

        // Key để theo dõi số lần thất bại
        $cacheKey = "failed_attempts:client_message:{$id}:{$ip}";

        // Nếu đã vượt quá số lần cho phép
        if (Cache::has($cacheKey) && Cache::get($cacheKey) >= $this->maxAttempts) {
            return response()->json([
                'error' => 'Too many invalid attempts. Please try again later.'
            ], 429); // 429 Too Many Requests
        }

        // Kiểm tra hợp lệ
        $exists = DB::table('client_messages')
            ->where('id', $id)
            ->where('token', $token)
            ->exists();

        if (!$exists) {
            // Tăng số lần sai
            Cache::add($cacheKey, 0, now()->addMinutes($this->decayMinutes));
            Cache::increment($cacheKey);

            return response()->json([
                'error' => 'Invalid id or token.'
            ], 401);
        }

        // Nếu hợp lệ: reset lại bộ đếm
        Cache::forget($cacheKey);

        return $next($request);
    }
}
