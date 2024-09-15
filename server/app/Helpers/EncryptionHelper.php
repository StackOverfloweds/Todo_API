<?php

namespace APP\Helpers;

use Illuminate\Support\Facades\Storage;

class EncryptionHelper{
    
    // initialize variable
    protected $publicKey;
    protected $privateKey;

    // create constructor
    public function __construct() {
        //Load the public key
        $this->publicKey = Storage::get(path: env(key: 'PUBLIC_KEY_PATH'));
        //Load the private key
        $this->privateKey = Storage::get(env('PRIVATE_KEY_PATH'));
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