<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Helpers\RedisHelper;
use App\Helpers\CheckUserHelper;
use App\Models\profileModel;
use Illuminate\Support\Facades\DB;
use App\Helpers\JWTHelper;
use App\Helpers\sendCodeHelper;
use Illuminate\Support\Facades\Log;
class RegisterController extends Controller
{
    /**
     * get User Request Data
     * 
     * @param request $request
     */
    public function getUserData (Request $request) {
        try {
            // validate user data
            $validateData = $request->validate([
                'name' => 'required|string',
                'phone_number' => 'required|string',
                'second_phone_number' => 'nullable|string',
            ]);

             // Check if user already exists
             $userExistsResponse = CheckUserHelper::checkUserExist($validateData);

                // Return error response if user already exists
                if ($userExistsResponse == true) {
                    return response()->json([
                        'message' => 'User with phone number already exists'
                    ], 400);
                }
            //store validate data to session with Redis
            RedisHelper::set(env('REDIS_KEYS_USER_DATA_SESSION'), json_encode($validateData), 600); // 10 minutes
            //redirect to send verification code
           $sendVerification =   sendCodeHelper::sendVerificationCode();
            
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
     * Create a new user and their profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request)
    {
        try {
            $getCode =  $request->input('code');
            $checkVerifyCode = sendCodeHelper::checkVerifyCode($getCode);

            //check Response from checkVerifyCode
            if ($checkVerifyCode->getStatusCode() != 200) {
                return response()->json([
                    'message' => 'Verification code is incorrect'
                ], 400);
            }
            
            // Get user data from session
            $userData = RedisHelper::get(env('REDIS_KEYS_USER_DATA_SESSION'));

            // Check if user data exists
            if (!$userData) {
                return response()->json([
                    'message' => 'User data not found'
                ], 400);
            }
            Log::info('check user data : '.json_encode($userData));

            // Start a database transaction
            DB::beginTransaction();

            // Create user
            $user = new User();
            $user->name = json_decode($userData)->name;
            $user->phone_number = json_decode($userData)->phone_number;
            $user->save();

            // Create user profile
            $profile = new profileModel();
            $profile->user_id = $user->user_id; // Set the user_id
            $profile->second_phone_number = json_decode($userData)->second_phone_number;
            $profile->save();

            // Commit the transaction if all operations succeed
            DB::commit();

            // Delete session data
            RedisHelper::delete(env('REDIS_KEYS_USER_DATA_SESSION'));
            RedisHelper::delete(env('REDIS_KEYS_USERS'));
            // Generate JWT token for Login user 
            //get id from user
            $jwt = new JWTHelper();
            $token = $jwt->generateLoginJWT($user->user_id);
            Log::info('check token : ' . json_encode ($token));
            //configure session login with redis JWT
            RedisHelper::set(env('REDIS_KEYS_LOGIN'), $token, 3600); // 1 hour
            
            // Return success response
            return response()->json([
                'message' => 'User created successfully',
                'token' => $token
            ], 200);
        } catch (\Exception $e) {
            // Rollback the transaction if an exception occurs
            DB::rollBack();
            Log::info('check error driver : '.json_encode($e->getMessage()));
            return response()->json([
                'message' => 'Failed to create user: ' . $e->getMessage()
            ], 500);
        }
    }


}