<?php

namespace Ecosystem\Authentication\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Authentication\Libraries\LoginLib;

class Login extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Authentication\\Views\\login\\';
	protected $loginLib;

	public function __construct()
	{
		$this->loginLib = new LoginLib(\Config\Services::request());
	}

	/**
	 * log in page
	 *
	 * @return void
	 */
	public function index()
	{
		$data['page_title'] = 'Login';
        $data['validation'] = $this->validation;
        
        echo view($this->_setPagePath($this->viewPath, 'login'), $data);
	}

	/**
	 * Process a user login
	 *
	 * @return void
	 */
	public function process()
	{
		// create validation rules
		$rules = [
			'email' => [
				'label' => 'Email',
				'rules' => 'required|valid_email|min_length[8]',
			],
			'password'  => ['rules' => 'required|min_length[6]'],
		];

		// set validation rules
		$this->validation->setRules($rules);
		// validate request
		if (! $this->validation->withRequest($this->request)->run()) { // fails
			if ($this->request->isAJAX()) { // if ajax request
				return $this->response->setJSON($this->validation->getErrors());    
			} else {
				return redirect()->back()->withInput()->with('error', 'Invalid details found!'); // return to sign up page
			}
		} else { // passes
			$data = [
				'email' => $this->request->getVar('email'),
				'password' => $this->request->getVar('password'),
			];
			$remember_me = (bool) $this->request->getVar('remember');
			
			$result = $this->loginLib->authenticate_user($data, $remember_me);
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$location = '/dashboard'; // destination
					$this->session->setFlashdata('success', 'Log in successful');
					$url = $this->session->get('return_to') ?? $location; // if there is an initial location use it or use $location
					$this->session->remove('return_to'); // unset the session location
					return redirect()->to($url);
				}
			}
		}
		return redirect()->to('/login')->with('error', $result['error']);
	}

	/**
     * Log a user out
     *
     * @return void
     */
    public function logout() {
        $this->loginLib->logout();
		return redirect()->to('/');
    }
}
