<?php

namespace Ecosystem\Profile\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Profile\Libraries\ProfileLib;

class User extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Profile\\Views\\user\\';

	/**
	 * User Profile Page
	 *
	 * @return void
	 */
	public function index()
	{
		$data['page_title'] = 'User Profile';
		$data['user'] = $this->userLib->get_user();
		echo view($this->_setPagePath($this->viewPath, 'profile'), $data);
	}

	/**
     * Edit Profile page
     *
     * @return void
     */
    public function profile() {
        $data['page_title'] = 'Profile Edit';
        $data['validation'] = $this->validation;
        $user = $this->userLib->get_user();

		if ($user) {
			$data['user'] = $user;
			return view($this->_setPagePath($this->viewPath, 'edit-profile'), $data);
		}
        return redirect()->to('/')->with('error', 'profile not found');
    }

	public function editProfile()
	{
		// create validation rules
		$rules = [
			'firstname' => [
				'label'  => 'First Name',
				'rules' => 'required|alpha|min_length[3]',
			],
			'lastname'  => [
				'label'  => 'Surname',
				'rules' => 'required|alpha|min_length[3]',
			],
			'phone' => [
				'label' => 'Phone Number',
				'rules' => 'required|min_length[8]',
			],
			'gender' => [
				'label' => 'Gender',
				'rules' => 'required|alpha',
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
				'last_name' => $this->request->getVar('lastname'),
				'first_name' => $this->request->getVar('firstname'),
				'phone_number' => $this->request->getVar('phone'),
				'user_gender' => $this->request->getVar('gender'),
			];
			
			$profileLib = new ProfileLib();
			$result = $profileLib->update_profile($data); // update user
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setflashdata('success', 'Profile edited successfully');
				} else {
					$this->session->setflashdata('error', 'Unable to edit profile!');
				}
			}
		}
		return redirect()->back();
	}

}
