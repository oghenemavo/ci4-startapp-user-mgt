<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\RequestInterface;
use Ecosystem\Authentication\Models\{UserRememberToken};

class LoginLib 
{
    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Authenticate a user by email and password
     *
     * @param array $user_data              Email address, Password
     * @param boolean $remember
     * @return array
     */
    public function authenticate_user(array $user_data, bool $remember) {
        $operation = $this->check_user($user_data['email'], $user_data['password']);
        [$user, $result] = $operation; // destructing PHP v7.1

        if ($user) {
            $this->set_user_session($user);

            if ($remember) {
                $this->create_remember_environment($user->id);
            }
        }
        return $result;
    }

    /**
     * Log the user in from the remember me cookie
     *
     * @return void
     */
    public function login_from_cookie() {
        helper('encryption');

        $cookie = $this->request->getCookie('remember_me');
        if ($cookie !== null) {
            // Find user that has the token set (the token is hashed in the database)
            $hashed_cookie = hash_data($cookie);

            $remember_login = new UserRememberToken();
            $user = $remember_login->fetchUserByToken($hashed_cookie);
            if ($user !== null) {
                $current_user_agent = $this->request->getUserAgent(); // CI user agent function
                $current_user_agent_info = [
                    'browser' => $current_user_agent->getBrowser(), 
                    'mobile' => $current_user_agent->isMobile(), 
                    'platform' => $current_user_agent->getPlatform()
                ];
                $remembered_user_agent = \json_decode($user->user_agent, true);
                $same_agent = array_diff($current_user_agent_info, $remembered_user_agent);
                $remember_time = strtotime($user->expires_at) > time();

                if ($remember_time && empty($same_agent)) {
                    $roleLib = Services::roleLib();
                    $role_info = $roleLib->set_user_role($user->id);
                    if ($role_info) {
                        $this->set_user_session($user);
                        return $user;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Logout a user
     *
     * @return void
     */
    public function logout() {
        helper('encryption');       

        $cookie = $this->request->getCookie('remember_me'); // get cookie value
        if ($cookie) {
            $hashed_cookie = hash_data($cookie); // hash cookie value
            
            $remember_login = new UserRememberToken(); // initialize UserRememberTokens table
            $remembered = $remember_login->fetchRememberByToken($hashed_cookie); //  get data if token is found
            
            if ($remembered) {
                $remember_login->delete($remembered->id); // delete data from table
                
                setcookie('remember_me', null, -1, '/');
                unset($cookie); // unset cookie
            }
        }
        session()->destroy();
        return true;
    }

    /**
     * Check the user credentials
     *
     * @param string $email
     * @param string $password
     * @return array
     */
    protected function check_user (string $email, string $password):array {
        $userLib = Services::userLib();
        $roleLib = Services::roleLib();

        $authorized_user = $userLib->get_by_email($email);
        $result = [];
        $user = false;

        if (!$authorized_user) {
            $result['error'] = 'Invalid Login Details 1!';
        } else {
            if (!password_verify($password, $authorized_user->user_password)) {
                $result['error'] = 'Invalid Login Details 2!';
            } else {
                if (!$authorized_user->is_active) {
                    $result['error'] = 'Account has not been activated, check email for previously sent activation email!';
                } else {
                    $role_info = $roleLib->set_user_role($authorized_user->id);
                    if (!$role_info) {
                        $result['error'] = "Can\'t sign in currently, please contact support";
                    } else {
                        $authorized_user->role = array_key_first($role_info); // set role to user
                        $user = $authorized_user;
                        $result['success'] = true;
                    }
                }
            }
        }
        return [$user, $result];
    }

    /**
     * Set a user identity in session
     *
     * @param object $user
     * @return boolean
     */
    protected function set_user_session(object $user):bool {
        $full_name = $user->last_name . ' ' . $user->first_name;
        $user_identity['identity'] = [
            'id' => $user->id,
            'email' => $user->user_email,
            'name' => $full_name,
            'role' => $user->role,
            'is_logged_in' => true,
        ];
        session()->set($user_identity);
        return true;
    }

    /**
     * Save user Log in cookie and database
     *
     * @param integer $user_id
     * @return boolean
     */
    protected function create_remember_environment($user_id) {
        helper('encryption');

        $expiry = new Time('+7 day'); // CI time function
        $encrypted_token = encrypt_data(); // token goes to the cookie
        $hashed_token = hash_data($encrypted_token); // token goes to the db
        $user_agent = $this->request->getUserAgent(); // CI user agent function
        $agent_info = [
            'browser' => $user_agent->getBrowser(), 
            'mobile' => $user_agent->isMobile(), 
            'platform' => $user_agent->getPlatform()
        ];

        $data = [
            'user_id' => $user_id,
            'remember_token' => $hashed_token,
            'user_agent' => \json_encode($agent_info, true),
            'expires_at' => $expiry,
        ];

        $remember_login = new UserRememberToken(); // initialize UserRememberTokens table

        try {
            $remember_login->insert($data); // save in the database
        } catch (\ReflectionException $e) {
        }

        setcookie('remember_me', $encrypted_token, $expiry->timestamp, '/');
        return true;
    }
    
}