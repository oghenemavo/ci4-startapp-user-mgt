<?php

namespace Ecosystem\Authentication\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;

class VerifyAccount extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Authentication\\Views\\account\\';

	/**
	 * Account Verification Page
	 *
	 * @param string $token
	 * @return void
	 */
	public function index(string $token)
	{
		$data['page_title'] = 'Verify Account';
		$data['validation'] = $this->validation;

		$verified = $this->accountLib->verify_token($token);
		
        if ($verified) {
            $data['verification'] = $verified->account_token;
			return view($this->_setPagePath($this->viewPath, 'authenticate'), $data);
        }

        $this->session->setFlashdata('warning', 'cannot process request now');
        return redirect()->to('/login');
	}

	/**
	 * Resend token
	 *
	 * @param string $token
	 * @return void
	 */
	public function resendVerification(string $token)
	{
		$result = $this->accountLib->resend_verification($token);
		if ($this->request->isAJAX()) { // if ajax request
			return $this->response->setJSON($result);    
		} else {
			if (isset($result['success'])) {
				return redirect()->route('verify', [$result['verify_token']]);
			}
		}
		return redirect()->route('verify', [$token])->with('error', 'Unable to process request now');
	}

	/**
	 * Verify token validity
	 *
	 * @param string $verification
	 * @return void
	 */
	public function process(string $verification)
    {
		// create validation rules
		$rules = [
			'code'  => [
				'label'  => 'Code',
				'rules' => 'required|exact_length[6]',
			],
			'verifier' => [
				'label'  => 'Phone Number',
				'rules' => 'required|min_length[12]',
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
				'code' => $this->request->getVar('code'),
				'account_token' => $this->request->getVar('verifier'),
			];
			
			$result = $this->accountLib->verify_user($data);
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					return redirect()->to('/login')->with('success', 'Successful!');
				} else {
					return redirect()->back()->with('error', $result['error']);
				}
			}
		}
    }
}
