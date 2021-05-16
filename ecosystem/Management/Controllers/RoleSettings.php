<?php

namespace Ecosystem\Management\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;

class RoleSettings extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\settings\\';

	public function index()
	{
		$data['page_title'] = 'Role Settings';
        
        echo view($this->_setPagePath($this->viewPath, 'index'), $data);
	}

	/**
     * View Roles Page
     *
     * @return void
     */
    public function viewRole() {
        $this->userLib->has_privilege_or_exception('view_role');

        $data['page_title'] = 'Roles';
        $data['validation'] = $this->validation;
        $data['roles'] = $this->roleLib->get_roles();
        $data['user_lib'] = $this->userLib;
        return view($this->_setPagePath($this->viewPath, 'add-role'), $data);
    }

	/**
     * Create Role page
     *
     * @return void
     */
    public function createRole() {
        $this->userLib->has_privilege_or_exception('add_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'role' => [
				'label' => 'Role Name',
				'rules' => 'required|alpha_numeric_space|min_length[4]|is_unique[roles.role]',
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
			helper('inflector');
			$role = $this->request->getVar('role');

			$data = [
				'role' => ucwords($role),
				'role_slug' => underscore(strtolower(trim($role))),
				'is_active' => (bool) $this->request->getVar('activate'),
			];
			
			$result = $this->roleLib->add_role($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Role created successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to create Role');
				}
			}
			return redirect()->back();
		}
    }

	/**
     * Edit a Role Page
     *
     * @return void
     */
    public function editRole($roleId) {
        $this->userLib->has_privilege_or_exception('edit_role');

		$role = $this->roleLib->find_role($roleId);
		if ($role) {
			$data['page_title'] = 'Edit Role';
			$data['validation'] = $this->validation;
			$data['role'] = $role;
			return view($this->_setPagePath($this->viewPath, 'edit-role'), $data);
		}
		return redirect()->back()->with('error', 'Role not found');
    }

	/**
	 * Process a role edit
	 *
	 * @param integer $roleId
	 * @return void
	 */
	public function processEditRole($roleId) {
        $this->userLib->has_privilege_or_exception('edit_role'); // check if user has the privilege or show 404

		$role = $this->roleLib->find_role($roleId);
		// create validation rules
		$rules = [
			'role' => [
				'label' => 'Role Name',
				'rules' => "required|alpha_numeric_space|min_length[4]|is_unique[roles.role,id,{$roleId}]",
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
			helper('inflector');
			$role = $this->request->getVar('role');

			$data = [
				'id' => $roleId,
				'role' => ucwords($role),
				'role_slug' => underscore(strtolower(trim($role))),
				'is_active' => (bool) $this->request->getVar('activate'),
			];
			
			$result = $this->roleLib->add_role($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Role Edited successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to create Role');
				}
			}
			return redirect()->back();
		}
    }
	
}
