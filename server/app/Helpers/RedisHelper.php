<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
class RedisHelper {

    /**
     * save data to redis
     * 
     * @param string $key
     * @param mixed $value
     * @param int|null $expiration (optional) seconds time to expire
     * @return bool
     */

     public static function set ($key, $value, $expiration = null) {
        
            if ($expiration !== null) {
                return Redis::setex($key, $expiration, $value);
            } else {
                return Redis::set($key, $value);    
            }
     }

     /**
     * Get Value By Key
     *
     * @param string $key
     * @return mixed
     */

     public static function get ($key) {
            return Redis::get($key);
     }

     /**
     * Hapus nilai dari Redis berdasarkan kunci.
     *
     * @param string $key
     * @return int
     */
    public static function delete ($key) {
        return Redis::del($key);
    }

    /**
     * Check if key already exist
     *
     * @param string $key
     * @return bool
     */
    public static function exists ($key) {
        return Redis::exists($key);
    }

    /**
     * Remove a specific field from a hash.
     *
     * @param string $key
     * @param string $field
     * @return int Number of fields removed
     */
    public static function hdelete($key, $field) {
        return Redis::hdel($key, $field);
    }

    /**
     * Remove a specific element from a list.
     *
     * @param string $key
     * @param string $value
     * @return int Number of removed elements
     */
    public static function lremove($key, $value) {
        return Redis::lrem($key, 0, $value);  // Remove all occurrences of $value
    }

    /**
     * Remove a specific member from a set.
     *
     * @param string $key
     * @param string $member
     * @return int Number of removed members
     */
    public static function sremove($key, $member) {
        return Redis::srem($key, $member);
    }

    /**
     * Remove a specific member from a sorted set.
     *
     * @param string $key
     * @param string $member
     * @return int Number of removed members
     */
    public static function zremove($key, $member) {
        return Redis::zrem($key, $member);
    }

    /**
     * Clear the value of a key without deleting the key itself.
     *
     * @param string $key
     * @return bool
     */
    public static function clearValue($key) {
        return Redis::set($key, '');
    }

    /**
     * Remove a specific value from a string key.
     *
     * @param string $key
     * @param string $value
     * @return bool True if value is removed, false otherwise
     */
    public static function removeStringValue($key, $value) {

        if (Redis::get($key) == $value) {
            Redis::set($key, ''); // Clear the value
            return true;
        }
        return false;
    }

     /**
     * Logout user by key and value.
     *
     * @param string $key
     * @param string $value
     * @return bool True if token is deleted, false otherwise
     */
    public static function deleteByKeyValue($key, $value)
    {
      $keys = Redis::keys($key);
      $deleted = false;
      Log::info('Keys: ' . json_encode($keys));

      foreach ($keys as $key) {
        // Check if value is the same as the value in Redis
        if (Redis::get($key) == $value) {
            // delete value in key from redis

            Redis::del($key);
            $deleted = true;
            Log::info('Deleted key: ' . $key);
        } else {
            Log::info('Key not found: ' . $key);
        }
    }

        return $deleted;
       
    }

}