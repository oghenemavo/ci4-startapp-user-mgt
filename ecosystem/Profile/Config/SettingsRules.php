<?php

namespace Ecosystem\Profile\Config;

use Config\Services;

class SettingsRules
{
	/**
     * Check current user password
     *
     * @param string $password
     * @param string $error
     * @return boolean
     */
    public function checkPassword(string $password, string &$error = null): bool {
        helper('password');
        $user_id = session()->identity['id']; // user session id

        $user = service('userLib')->get_user_by_id($user_id);
        
        if(!password_verify($password, $user->user_password)) {
            $error = 'The is not the current password';
            return false;
        }
        return true;
    }
}
