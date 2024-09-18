<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\RedisHelper;
use App\Models\User;
use App\Helpers\CheckUserHelper;
use App\Helpers\sendCodeHelper;
use App\Helpers\JWTHelper;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     * 
     * @param  \Illuminate\Http\Request  $request
     */

     public function getData(Request $request) {
        try {
            // validate user data
            $validateData = $request->validate([
                'phone_number' => 'required|string',
            ]);
            // Check if user already exists
            $userExistsResponse = CheckUserHelper::checkUserExist($validateData);

            if ($userExistsResponse == false) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }

            //store validate data to session with Redis
            RedisHelper::set(env('REDIS_KEYS_USER_DATA_SESSION'), json_encode($validateData), 600); // 10 minutes
            //redirect to send verification code
           $sendVerification =  sendCodeHelper::sendVerificationCode();
            
           if ($sendVerification == true ) {
                return response()->json([
                     'message' => 'Verification code sent successfully'
                ], 200);
              } else {
                return response()->json([
                     'message' => 'Failed to send verification code'
                ], 500);
           }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle an authentication attempt.
     * 
     * Apply the JWT token to the user
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        try {
            // Validate input
            $request->validate([
                'code' => 'required|string',
            ]);

            // Get input code from user
            $inputCode = $request->input('code');

            // Check verification code from user input
            $checkVerifyCode = sendCodeHelper::checkVerifyCode($inputCode);

            // Check response from checkVerifyCode
            if ($checkVerifyCode->getStatusCode() != 200) {
                return response()->json([
                    'message' => 'Verification code is incorrect'
                ], 400);
            }

            // Get specific phone_number from redis who has the same phone number with user REDIS_KEYS_USER_DATA_SESSION 
            $phoneNumber = json_decode(RedisHelper::get(env('REDIS_KEYS_USER_DATA_SESSION')))->phone_number;

            // Get all users from the database
            $users = User::all();

            // Store all users in Redis cache
            RedisHelper::set(env('REDIS_KEYS_USERS'), json_encode($users), 300); // 5 minutes

            // Check if the user already exists
            $user = $users->firstWhere('phone_number', $phoneNumber);
            Log::info('User: ' . json_encode($user));

            if ($user) {
                // Log user ID
                Log::info('User ID: ' . $user->user_id);

                // Generate JWT token for the logged in user
                $jwt = new JWTHelper();
                $token = $jwt->generateLoginJWT($user->user_id);

                // delete session data
                RedisHelper::delete(env('REDIS_KEYS_USER_DATA_SESSION'));
                RedisHelper::delete(env('REDIS_KEYS_USERS'));

                //set session login with redis JWT
                RedisHelper::set(env('REDIS_KEYS_LOGIN'), $token, 3600); // 1 hour

                return response()->json([
                    'token' => $token,
                    'user' => $user,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }



}