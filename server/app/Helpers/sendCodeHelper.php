<?php

namespace App\Helpers;
use App\Helpers\RedisHelper;
use App\Helpers\CheckUserHelper;
use App\Http\Controllers\API\FonnteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class sendCodeHelper{
        /**
         * Create Send verification code
         * 
         * @return bool
         */
        public static function sendVerificationCode () {
            try {
                // Get user data from session
                $userData = RedisHelper::get(env('REDIS_KEYS_USER_DATA_SESSION'));
                
                // Check if already expired or not found
                if (!$userData) {
                    return false;
                }

                // Decode the user data from JSON
                $userDataObject = json_decode($userData);

                // Create some verification code 
                $verificationCode = CheckUserHelper::generateVerificationCode();
                $phone_number = $userDataObject->phone_number;

                // Prepare data for FonnteController
                $fonnteRequestData = [
                    'phone_number' => $phone_number,
                    'verification_code' => $verificationCode
                ];
                
                // Send verification code to user phone number via Fonnte API
                $fonnteRequest = new Request($fonnteRequestData);
                $fonnte = new FonnteController();
                $fonnte->sendVerificationCode($fonnteRequest);
                Log::info('Verification code sent successfully: ' . $verificationCode);

                $userDataObject->verification_code = $verificationCode;
                RedisHelper::set(env('REDIS_KEYS_USER_DATA_SESSION'), json_encode($userDataObject), 120); // Store updated user data with 2 minutes expiration
                // Check result
                return true;
            } catch (\Exception $e) {
                return false;
            }
        }

        /**
         * Check verification code from user input and if true create user
         * 
         * @param Request $request
         */
        public static function checkVerifyCode($data) {
            // Get user input verification code
            Log::info('Data: ' . json_encode($data));

            // Get user data from session
            $userData = RedisHelper::get(env('REDIS_KEYS_USER_DATA_SESSION'));

            // Check if user data is found
            if (!$userData) {
                return response()->json([
                    'message' => 'Verification code not found'
                ], 400);
            }

            // Decode the user data from JSON
            $userDataObject = json_decode($userData);

            // Get the verification code from user data
            $verificationCode = $userDataObject->verification_code ?? null;
            
            $check =  CheckUserHelper::checkVerificationCode($verificationCode, $data);

            // Check if the verification code is correct
            if ($check == true) {
                // Create user
                return response()->json([
                    'message' => 'Verification code is correct'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Verification code is incorrect'
                ], 400);
            }
        }


}