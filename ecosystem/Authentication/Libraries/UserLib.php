<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use CodeIgniter\HTTP\RequestInterface;
use Ecosystem\Authentication\Models\{User};
use Ecosystem\Authentication\Libraries\LoginLib;

class UserLib
{
    protected $auth_user = false;

    protected $request;

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    /**
     * Get a user by email
     *
     * @param string $email
     * @return void
     */
    public function get_by_email(string $email) {
        $user = new User();
        return $user->where('user_email', $email)->first();
    }

    /**
     * Get a user by id
     *
     * @param integer $id
     * @return void
     */
    public function get_user_by_id($id) {
        $user = new User();
        return $user->fetchUserById($id);
    }

    /**
     * Get the current logged in user
     * 
     * @return mixed
     */
    public function get_user():mixed {
        $user_session = session()->identity;
        if (isset($user_session['id'])) {
            $this->auth_user = $this->get_user_by_id($user_session['id']);
        } else {
            $loginLib = new LoginLib(Services::request());
            $cookie_user = $loginLib->login_from_cookie();
            if ($cookie_user) {
                $this->auth_user = $this->get_user_by_id($cookie_user->id);
            }
        }
        return $this->auth_user;
    }

    /**
     * Check if the user is signed in
     * 
     * @return boolean
     */
    public function is_user_signed_in():bool {
        return $this->get_user() !== false;
    }

    /**
     * Redirect to the home page if a user is logged in.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function require_guest($named_route = '') {
        if ($this->is_user_signed_in()) {
            return empty($named_route) ? redirect()->to('/') : redirect()->route($named_route);
        }
        return false;
    }

    /**
     * Redirect to the login page if no user is logged in.
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function require_login () {
        if ( ! $this->is_user_signed_in()) {
            $request = Services::request();

            // Save the requested page to return to after logging in
            $url = '/' . $request->uri->getPath();
            if ( ! empty($url)) {
                $data['return_to'] = $url;
                session()->set($data);
            }
            return redirect()->to('/login');
        }
    }

    /**
     * Check if user privilege is sufficient
     *
     * @param mixed $privilege
     * @return boolean
     */
    public function has_privilege(mixed $privilege) {
        if ($this->auth_user) {
            if (service('roleLib')->set_role($this->auth_user->id)) {
                return service('roleLib')->has_permission($privilege);
            }
        }
        return false;
    }

    /**
     * Check if user privilege is sufficient to access a page
     *
     * @param mixed $privilege
     * @return boolean
     */
    public function has_privilege_or_exception(mixed $privilege) {
        if (! $this->has_privilege($privilege)) {
            throw new \CodeIgniter\Router\Exceptions\RedirectException(403);
        }
    }
    
}
