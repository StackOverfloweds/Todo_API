<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\RedisHelper;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function logout($token)
{
    try {

        $key = env('REDIS_KEYS_LOGIN');
        // Hapus token dari Redis
       $check =  RedisHelper::removeStringValue($key, $token);
        if ($check == true ) {
            Log::info('User logged out successfully: ' . $token);
            return response()->json([
                'message' => 'Successfully logged out.'
            ]);
        } else {
            return response()->json([
                'message' => 'You Already Logged Out.',
            ], 500);
        }
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to logout. Please try again. Error: ' . $e->getMessage(),
        ], 500);
    }
}


}