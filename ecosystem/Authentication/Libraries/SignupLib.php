<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use Ecosystem\Authentication\Models\{User, UserProfile, UserRole, AccountVerification};

class SignupLib 
{

    /**
     * Create a User account in 4 tables
     *
     * @param array $user_data
     * @param string $role_id
     * @return void
     */
    public function create_user(array $user_data, $role_id = '1') {
        helper('encryption'); // custom encryption helper function

        $encrypted_token = encrypt_data(6);  // goes to the user email address
        $hashed_token = hash_data($encrypted_token); // goes to the database 

        $result = [];
        $user_data['activation_token'] = $hashed_token;
        $user_data['role_id'] = $role_id;
        
        $db = Database::connect(); // create a database connection

        $db->transStart(); // start transaction automatically

        // User Table
        $user = new User();

        // Insert into user table
        try {
            $user->save($user_data);
            $user_data['user_id'] = $user->insertID(); // last user insert id
        } catch (\ReflectionException $e) {
        }

        // Profile Table
        $profile = new UserProfile();

        // Insert into Profile table
        try {
            $profile->insert($user_data);
        } catch (\ReflectionException $e) {
        }

        // User Role Table
        $user_role = new UserRole();

        // Insert into user role table
        try {
            $user_role->insert($user_data);
        } catch (\ReflectionException $e) {
        }

        // Verification Table
        $verification = new AccountVerification();

        // Insert into user table
        try {
            $user_data['account_token'] = $result['verify'] = md5($hashed_token); // send to db and output
            $user_data['expires_at'] = Time::now()->addMinutes(60)->toDateTimeString(); // expires at 60 mins
            $verification->insert($user_data);
        } catch (\ReflectionException $e) {
        }

        $db->transComplete(); // complete transaction

        if ($db->transStatus() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            $result['error'] = 'Unable to sign up user';
        } else {
            // send email
            $mail = [
                'user_email' => $user_data['user_email'], 
                'name' => $user_data['last_name'], 
                'token' => $encrypted_token,
            ];
            $this->send_activation_mail($mail);
            $result['success'] = true;
        }
        return $result;
    }

    protected function send_activation_mail(array $data) {
        $view = [
            'html' => '\Ecosystem\Authentication\Views\email\activation.php',
            'text' => '\Ecosystem\Authentication\Views\email\text\activation.txt',
        ];

        $view['data'] = [
            'token' => $data['token'],
            'name' => $data['name'],
        ];

        $address = [
            'from' => 'autodispatch@demo.com',
            'from_name' => 'Auto Dispatch',
            'to' => $data['user_email'],
            'to_name' => $data['name'],
        ];

        $mail = [
            'subject' => 'Confirm your Account',
        ];
        return Services::mailerLib()->send_mail($view, $address, $mail);
    }

}