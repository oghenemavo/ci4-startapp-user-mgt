<?php

namespace Ecosystem\Authentication\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Authentication\Libraries\RecoverLib;

class RecoverAccount extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Authentication\\Views\\recover\\';

	/**
	 * Recover forgot password Page
	 *
	 * @return void
	 */
	public function index()
	{
		$data['page_title'] = 'Recover Forgotten Password';
        $data['validation'] = $this->validation;
        
        echo view($this->_setPagePath($this->viewPath, 'forgot-password'), $data);
	}

	/**
	 * Process Password Recover 
	 *
	 * @return void
	 */
	public function forgotPasswordProcess()
    {
		// create validation rules
		$rules = [
			'email'     => [
				'label'  => 'Email',
				'rules' => 'required|valid_email|min_length[8]',
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
			$email = $this->request->getVar('email');

			$recoverLib = new RecoverLib();
			$result = $recoverLib->start_reset($email);
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setflashdata('success', 'Reset token sent to the email, paste it below!');
				} else {
					$this->session->setflashdata('success', 'if we find an account with that email, a reset link would be sent!');
				}
			}
		}
		return redirect()->to('/recover/forgot-password');
    }

	/**
	 * Password Reset Page
	 *
	 * @param string $token
	 * @return void
	 */
	public function process(string $token)
    {
		$recoverLib = new RecoverLib();
		
		$user = $recoverLib->get_user_by_token($token);
		
        if (!$user) {
			$data['page_title'] = 'Reset Process Failed';
		} else {
            $data['page_title'] = 'Reset Password';
            $data['validation'] = $this->validation;
            $data['token'] = $token;
            $data['user'] = $user;
		}
        return view($this->_setPagePath($this->viewPath, 'reset-password'), $data);
    }

	/**
	 * Process password reset
	 *
	 * @param string $token
	 * @return void
	 */
	public function resetPassword(string $token)
    {
		// create validation rules
		$rules = [
			'password'  => [
				'label'  => 'Password',
				'rules' => 'required|min_length[6]|regex_match[/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{6,}$/]',
				'errors' => [
					'regex_match' => 'Password must contain at least 6 characters including an uppercase and a lower case letter and a number.'
				]
			],
			'confirm_password' => [
				'label' => 'Confirm Password',
				'rules' => 'required|matches[password]',
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
				'user_password' => $this->request->getVar('password'),
				'token' => $token
			];

			$recoverLib = new RecoverLib();
			$result = $recoverLib->reset_password($data);
			if ($this->request->isAJAX()) { // if request is ajax
				echo json_encode($result);  // $result['error'] or $result['success']             
			} else {
				if (isset($result['success'])) {
					return redirect()->to('/login')->with('success', 'Password Reset successfully');
				}
			}
		}
		return redirect()->to('/recover/forgot-password')->with('error', 'Unable to process reset, try again');
    }
}
