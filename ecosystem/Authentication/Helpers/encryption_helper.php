<?php

use Config\Services;

if (! function_exists('encrypt_data')) {

    function encrypt_data($length = 0) {
        $encrypter = \Config\Services::encrypter();

        $token = random_string('crypto', 32);
        $hash = bin2hex($encrypter->encrypt($token));
        if ($length) {
            $hash = substr($hash, 0, $length);
        }
        return $hash;
    }
}

if (! function_exists('hash_data')) {

    function hash_data($data) {
        return hash_hmac('sha256', $data, '4MGWK8rE9p9yaR75pkig6LBP4iK8SY2g');  // sha256 = 64 chars
    }
}