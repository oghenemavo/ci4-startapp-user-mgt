<?php

namespace Ecosystem\Authentication\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Authentication\Libraries\SignupLib;

class Signup extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Authentication\\Views\\signup\\';

	/**
	 * Sign up page
	 *
	 * @return void
	 */
	public function index()
	{
		$data['page_title'] = 'Sign up';
		$data['validation'] = $this->validation;

		echo view($this->_setPagePath($this->viewPath, 'signup'), $data);
	}

	/**
	 * Process user signup details
	 *
	 * @return void
	 */
	public function process()
	{
		// create validation rules
		$rules = [
			'lastname'  => [
				'label'  => 'Surname',
				'rules' => 'required|alpha|min_length[3]',
			],
			'firstname' => [
				'label'  => 'First Name',
				'rules' => 'required|alpha|min_length[3]',
			],
			'phone' => [
				'label'  => 'Phone Number',
				'rules' => 'required|numeric',
			],
			'email'     => [
				'label'  => 'Email',
				'rules' => 'required|valid_email|min_length[8]|is_unique[users.user_email]',
				'errors' => [
					'is_unique' => 'An account with this {field} already exists'
				]
			],
			'password'  => [
				'label'  => 'Password',
				'rules' => 'required|min_length[6]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,}$/]',
				'errors' => [
					'regex_match' => 'Password must contain at least 6 characters including an uppercase and a lower case letter and a number.'
				]
			],
			'terms' => [
				'label'  => 'Terms of Service',
				'rules' => 'required',
			],
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
				'last_name' => $this->request->getVar('lastname'),
				'first_name' => $this->request->getVar('firstname'),
				'user_email' => $this->request->getVar('email'),
				'phone_number' => $this->request->getVar('phone'),
				'user_password' => $this->request->getVar('password'),
			];
			
			$signupLib = new SignupLib();
			$result = $signupLib->create_user($data);
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					return redirect()->to('/account/verify/'. $result['verify'])->with('success', 'Signup Successful check email for activation token!');
				} else {
					return redirect()->back()->with('error', $result['error']);
				}
			}
		}
	}

}
