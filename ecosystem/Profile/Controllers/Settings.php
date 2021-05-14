<?php

namespace Ecosystem\Profile\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Profile\Libraries\SettingsLib;

class Settings extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Profile\\Views\\user\\';

	/**
	 * User Account Settings Page
	 *
	 * @return void
	 */
	public function index()
	{
		$data['page_title'] = 'Account Settings';
        $data['validation'] = $this->validation;
        $data['user'] = $this->userLib->get_user();

        return view($this->_setPagePath($this->viewPath, 'settings'), $data);
	}

	/**
	 * Edit a user email
	 *
	 * @return void
	 */
	public function editEmail()
	{
		$id = $this->userLib->get_user()->id;

		// create validation rules
		$rules = [
			'email' => [
				'label'  => 'Email',
				'rules' => "required|valid_email|is_unique[users.user_email,id,{$id}]",
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
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
				'id' => $id,
				'user_email' => $this->request->getVar('email'),
			];
			
			$settingsLib = new SettingsLib();
			$result = $settingsLib->update_email($data); // update user
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashdata('success', 'Email edited successfully');
				} else {
					$this->session->setFlashdata('error', 'Unable to edit email!');
				}
			}
		}
		return redirect()->back();
	}

	/**
	 * Edit a user password
	 *
	 * @return void
	 */
	public function editPassword()
	{
		// create validation rules
		$rules = [
			'password' => [
				'label'  => 'Current Password',
				'rules' => 'required|min_length[6]|checkPassword',
			],
			'new_password'  => [
				'label'  => 'New Password',
				'rules' => 'required|min_length[6]|differs[password]',
			],
			'confirm_new_password' => [
				'label' => 'Repeat Password',
				'rules' => 'required|matches[new_password]',
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
				'id' => $this->userLib->get_user()->id,
				'user_password' => $this->request->getVar('new_password'),
			];
			
			$settingsLib = new SettingsLib();
			$result = $settingsLib->update_password($data); // update user
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashdata('success', 'Password edited successfully');
				} else {
					$this->session->setFlashdata('error', 'Unable to edit Password!');
				}
			}
		}
		return redirect()->back();
	}

}
