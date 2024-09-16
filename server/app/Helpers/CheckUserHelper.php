<?php

namespace App\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Helpers\RedisHelper;
class CheckUserHelper {
    
    /**
     * Generate a random 6-digit verification code.
     *
     * @return string
     */
    public static function generateVerificationCode() {
        return str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if the provided verification code matches the generated code.
     *
     * @param string $verificationCode
     * @param string $inputCode
     * @return bool
     */
    public static function checkVerificationCode($verificationCode, $inputCode) {
        return $verificationCode === $inputCode;
    }

     /**
     * Check if a user with the provided phone number already exists.
     * 
     * @param array $userData
     * @return bool True if user exists, false otherwise
     */

     public static function checkUserExist($userData) {
        try {
            // Extract phone number from user data
            $phoneNumber = $userData['phone_number'];

            // get all data from db and save to redis
            $users = User::all();
            RedisHelper::set(env('REDIS_KEYS_USERS'), json_encode($users), 300); // 5 minutes
            Log::info('Users: ' . json_encode($users));
            
            //get spesific phone_number from redis
            $users = RedisHelper::get(env('REDIS_KEYS_USERS'));
            
            Log::info('Users: ' . $users);
            // Check if user data is found
            if (!$users) {
                return false;
            } else {
                // Decode the user data from JSON
                $usersObject = json_decode($users);
                // Check if the user already exists
                foreach ($usersObject as $user) {
                    if ($user->phone_number == $phoneNumber) {
                        // Return true if user exists
                        return true;
                    }
            }
        }
            // Return false if user does not exist
            return false;
        } catch (\Exception $e) {
            // Log any errors that occur during the process
            Log::error('Error checking user existence: ' . $e->getMessage());
            // Return false in case of an error
            return false;
        }
    }

}