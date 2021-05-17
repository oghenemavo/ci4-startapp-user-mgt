<?php

namespace Ecosystem\Management\Controllers;

// use CodeIgniter\RESTful\ResourceController;
use Ecosystem\Authentication\Controllers\AuthBaseController;

class User extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\user\\';

	public function __construct() 
	{
		$this->manageLib = service('manageLib');	
	}

	/**
	 * Return an array of resource objects, themselves in array format
	 *
	 * @return mixed
	 */
	public function index()
	{
		$this->userLib->has_privilege_or_exception('view_user');

		$data['page_title'] = 'All Users List';
        $data['users'] = $this->manageLib->get_users();
        return view($this->_setPagePath($this->viewPath, 'list'), $data);
	}

	/**
	 * Return the properties of a resource object
	 *
	 * @return mixed
	 */
	public function show($id = null)
	{
		$this->userLib->has_privilege_or_exception('view_user');

		if ($id) {
			$data['page_title'] = 'User Profile';
	
			$user = $this->userLib->get_user_by_id($id);
			if ($user) {
				$data['user'] = $user;
				$data['userlib'] = $this->userLib;
				return view($this->_setPagePath($this->viewPath, 'show'), $data);
			}
		}
        return redirect()->to('/manage/users/list')->with('error', 'User not found');
	}

	/**
	 * Return a new resource object, with default properties
	 *
	 * @return mixed
	 */
	public function new()
	{
		$this->userLib->has_privilege_or_exception('add_user');

		$data['page_title'] = 'Create User Profile';
		$data['validation'] = $this->validation;
		$data['userlib'] = $this->userLib;
		$data['roles'] = $this->roleLib->get_roles();
		return view($this->_setPagePath($this->viewPath, 'add'), $data);
	}

	/**
	 * Create a new resource object, from "posted" parameters
	 *
	 * @return mixed
	 */
	public function create()
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
			'gender' => [
				'label'  => 'Gender',
				'rules' => 'required|alpha',
			],
			'phone' => [
				'label'  => 'Phone Number',
				'rules' => 'required|numeric|min_length[8]',
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
				'rules' => 'required|min_length[6]',
			],
			'role'  => [
				'label'  => 'Role',
				'rules' => 'required|integer',
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
				'first_name' => $this->request->getVar('firstname'),
				'last_name' => $this->request->getVar('lastname'),
				'phone_number' => $this->request->getVar('phone'),
				'user_email' => $this->request->getVar('email'),
				'user_password' => $this->request->getVar('password'),
				'user_gender' => $this->request->getVar('gender'),
				'is_active' => (bool) $this->request->getVar('active') ?? false,
				'role_id' => $this->request->getVar('role'),
			];
			
			$result = $this->manageLib->set_user($data); // add role

			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'User Created successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to create user');
				}
			}
			return redirect()->to('/manage/users/list');
		}
	}

	/**
	 * Return the editable properties of a resource object
	 *
	 * @return mixed
	 */
	public function edit($id = null)
	{
		$this->userLib->has_privilege_or_exception('edit_user');

		if ($id) {
			$user = $this->userLib->get_user_by_id($id);
			if ($user) {
				$data['page_title'] = 'Edit User Profile';
				$data['validation'] = $this->validation;
				$data['user'] = $user;
				$data['checker'] = $this->userLib->get_user();
				$data['roles'] = $this->roleLib->get_roles();

				$data['is_same_user'] = $user->id == $data['checker']->id; 
				$data['is_superadmin'] = strtolower($user->role_slug) == strtolower('super_admin'); // edited
				$data['is_checker_superadmin'] = strtolower($data['checker']->role_slug) == strtolower('super_admin'); //editor
				$data['is_same_superadmin'] = $data['is_superadmin'] && $data['is_checker_superadmin'] && !$data['is_same_user']; // check if is super admin && the owner of the profile b4 editing

				return view($this->_setPagePath($this->viewPath, 'edit'), $data);
			}
		}
        return redirect()->to('/manage/users/list')->with('error', 'User not found');
	}

	/**
	 * Add or update a model resource, from "posted" properties
	 *
	 * @return mixed
	 */
	public function update($id = null)
	{
		if ($id) {
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
                'gender' => [
                    'label'  => 'Gender',
                    'rules' => 'required|alpha',
                ],
                'phone' => [
                    'label'  => 'Phone Number',
                    'rules' => 'required|numeric|min_length[8]',
                ],
                'email'     => [
                    'label'  => 'Email',
                    'rules' => "required|valid_email|min_length[8]|is_unique[users.user_email,id,{$id}]",
                    'errors' => [
                        'is_unique' => 'An account with this {field} already exists'
                    ]
                ],
                'password'  => [
                    'label'  => 'Password',
                    'rules' => 'permit_empty|min_length[6]',
                ],
                'role'  => [
                    'label'  => 'Role',
                    'rules' => 'permit_empty|integer',
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
                    'first_name' => $this->request->getVar('firstname'),
                    'last_name' => $this->request->getVar('lastname'),
                    'phone_number' => $this->request->getVar('phone'),
                    'user_email' => $this->request->getVar('email'),
                    'user_gender' => $this->request->getVar('gender'),
                ];

				if (!empty($this->request->getVar('active'))) {
                    $data['is_active'] = (bool) $this->request->getVar('active');
                }

				if (!empty($this->request->getVar('role'))) {
                    $data['role_id'] = $this->request->getVar('role');
                }

                if (!empty($this->request->getVar('password'))) {
                    $data['user_password'] = $this->request->getVar('password');
                }
				
				$result = $this->manageLib->set_user($data); // add role

				if ($this->request->isAJAX()) { // if request is ajax
					return $this->response->setJSON($result); // $result['error'] or $result['success']
				} else {
					if (isset($result['success'])) {
						$this->session->setFlashData('success', 'User updated successfully');
					} else {
						$this->session->setFlashData('error', 'Unable to update user');
					}
				}
				return redirect()->back();
			}
		}
	}

	/**
	 * Delete the designated resource object from the model
	 *
	 * @return mixed
	 */
	public function delete($id = null)
	{
		//
	}
}
