<?php

namespace App\Helpers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
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
    public static function generateLoginJWT(string $id): string
{
    try {
        Log::info("Check id : " . $id);

        // Find the user with the given ID
        $user = User::findOrFail($id);
        Log::info("check user jwt : " . json_encode($user));

        // Define JWT secret key (make sure it's the same as the one used to decode)
        $key = env('JWT_SECRET'); // Retrieve from your .env file

        // Define the payload (customize as needed)
        $payload = [
            'iat' => time(), // Issued at: time when the token was generated
            'exp' => time() + 3600, // Expiration time: 1 hour from the issued time
            'sub' => $user->id, // Subject: user ID or any other identifier
            'name' => $user->name,
            'phone_number' => $user->phone_number,
        ];

        // Generate JWT token
        $token = JWT::encode($payload, $key, 'HS256'); // Use HS256 algorithm

        Log::info("check token jwt : " . $token); // Log the token

        // Return the generated token
        return $token;
    } catch (\Exception $e) {
        // Handle any exceptions
        Log::error('Failed to generate JWT token: ' . $e->getMessage());
        return '';
    }
}
}