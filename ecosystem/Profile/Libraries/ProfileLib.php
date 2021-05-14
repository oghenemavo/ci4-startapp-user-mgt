<?php

namespace Ecosystem\Profile\Libraries;

use Config\Database;
use Ecosystem\Authentication\Models\{User, UserProfile};

class ProfileLib {

    /**
     * Update a user account details
     *
     * @param array $data
     * @return void
     */
    public function update_profile(array $data) {
        $db = Database::connect();
        $result = [];
        
        $db->transStart(); // start transaction automatically

        // User Table
        $user = new User();

        // Update into user table
        try {
            $user->save($data);
        } catch (\ReflectionException $e) {
        }

        // Profile Table
        $profile = new UserProfile();
        
        $id = $data['id'];
        unset($data['id']);
        try {
            $profile->update(['user_id' => $id], $data);
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