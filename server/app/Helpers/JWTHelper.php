<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
class JWTHelper {
        

    /**
     * Decode a JWT token.
     *
     * @param string $token
     * @return object
     */
    public static function decodeToken($token) {
        return JWTAuth::decode($token, env('JWT_SECRET'), ['HS256']);
    }

    /**
     * Generate a JWT token for login.
     *
     * @param int $id
     * @return string
     */
    public static function generateLoginJWT($id): string {
        try {
            // Find the user with the given ID
            $user = User::findOrFail($id);

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            // Return the generated token
            return $token;
        } catch (\Exception $e) {
            // Handle any exceptions
            return '';
        }
    }
}