<?php

namespace Ecosystem\Profile\Libraries;

use Config\Database;
use Ecosystem\Authentication\Models\User;

class SettingsLib {
    
    /**
     * Update a user email
     *
     * @param array $user_data
     * @return array
     */
    public function update_email(array $user_data):array
    {
        $db = Database::connect();
        $result = [];

        $db->transStart(); // start transaction automatically

        // User Table
        $user = new User();

        // Update into user table
        try {
            $user->save($user_data);
        } catch (\ReflectionException $e) {
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === FALSE) {
            $result['error'] = 'Unable to perform this request';
        } else {
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Update a user password
     *
     * @param array $data
     * @return void
     */
    public function update_password(array $user_data)
    {
        $db = Database::connect();
        $result = [];

        $db->transStart(); // start transaction automatically

        // User Table
        $user = new User();

        // Update into user table
        try {
            $user->save($user_data);
        } catch (\ReflectionException $e) {
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === FALSE) {
            $result['error'] = 'Unable to perform this request';
        } else {
            $result['success'] = true;
        }
        return $result;
    }
}