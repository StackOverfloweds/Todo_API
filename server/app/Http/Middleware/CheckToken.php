<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\JWTHelper;
use Illuminate\Support\Facades\Log;
use App\Helpers\RedisHelper;
class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = RedisHelper::get(env('REDIS_KEYS_LOGIN'));
        Log::info("check token middleware : ".json_encode ($token));
        if (!$token) {
            return response()->json(['message' => 'Token not provided'], 401);
        }

        // Remove "Bearer " from the token string
        $token = str_replace('Bearer ', '', $token);

        // Validate the token
        $jwt = new JWTHelper();
        $payload = $jwt->decodeToken($token);

        if (!$payload) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        return $next($request);
    }
}