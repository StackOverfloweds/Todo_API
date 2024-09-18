<?php

namespace APP\Helpers;

use Illuminate\Support\Facades\Storage;

class EncryptionHelper{
    
    // initialize variable
    protected $publicKey;
    protected $privateKey;

    // create constructor
    public function __construct() {
        // Load the public key
        $this->publicKey = file_get_contents(public_path(env('PUBLIC_KEY_PATH')));
        // Load the private key
        $this->privateKey = file_get_contents(public_path(env('PRIVATE_KEY_PATH')));
    
        // Debug: Check if keys are loaded correctly
        if (!$this->publicKey || !$this->privateKey) {
            throw new \Exception('Failed to load keys from public directory.');
        }
    }
    
    

    /**
     * Encrypt data using the public key.
     *
     * @param string $data
     * @return string
     */
    public function encryptData ($data) {
        openssl_public_encrypt($data, $encrypted, $this->publicKey);
        return base64_encode($encrypted);
    }

    /**
     * Decrypt data using the private key.
     *
     * @param string $data
     * @return string
     */
    public function decryptData ($data) {
        openssl_private_decrypt(base64_decode($data), $decrypted, $this->privateKey);
        return $decrypted;
    }
}