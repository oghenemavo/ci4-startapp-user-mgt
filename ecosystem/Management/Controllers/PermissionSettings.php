<?php

namespace Ecosystem\Management\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;

class PermissionSettings extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\settings\\';

	/**
	 * Permission Group Page
	 *
	 * @return void
	 */
	public function index()
	{
		$this->userLib->has_privilege_or_exception('view_role'); // check privilege or 404

        $data['page_title'] = 'Permission Group';
        $data['validation'] = $this->validation;
        $data['groups'] = service('permissionsLib')->get_permission_groups(); // fetch permission groups
        echo  view($this->_setPagePath($this->viewPath, 'add-permission-group'), $data);
	}

	/**
     * Create Role page
     *
     * @return void
     */
    public function createPermissionGroup() {
        $this->userLib->has_privilege_or_exception('add_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'group' => [
				'label' => 'Group Name',
				'rules' => 'required|alpha_numeric_space|min_length[4]|is_unique[permission_groups.group_name]',
				'errors' => [
					'is_unique' => 'This {field} already exists'
				]
			],
			'group_focus' => [
				'label' => 'Group Focus',
				'rules' => 'required|alpha_numeric_space|min_length[3]|is_unique[permission_groups.group_focus]',
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
			$focus = strtolower(trim($this->request->getVar('group_focus')));

			$data = [
				'group_name' => ucwords($this->request->getVar('group')),
				'group_focus' => $focus,
				'group_slug' => underscore($focus),
			];
			
			$result = service('permissionsLib')->add_group($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Permission Group successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to create Permission Group');
				}
			}
			return redirect()->back();
		}
    }

	/**
	 * Edit Permission Group page
	 *
	 * @param integer $perm_group_id
	 * @return void
	 */
	public function edit($perm_group_id) {
		$this->userLib->has_privilege_or_exception('edit_role');

		$permission_group = service('permissionsLib')->find_permission_group($perm_group_id);
		if ($permission_group) {
			$data['page_title'] = 'Edit Permission Group';
			$data['validation'] = $this->validation;
			$data['group'] = $permission_group;
			return view($this->_setPagePath($this->viewPath, 'edit-permission-group'), $data);
		}
		return redirect()->back()->with('error', 'Role not found');
	}

	/**
	 * Edit Permission Group
	 *
	 * @param integer $perm_group_id
	 * @return void
	 */
	public function editPermissionGroup($perm_group_id) {
        $this->userLib->has_privilege_or_exception('edit_role'); // check if user has the privilege or show 404

		// create validation rules
		$rules = [
			'group' => [
				'label' => 'Group Name',
				'rules' => "required|alpha_numeric_space|min_length[4]|is_unique[permission_groups.group_name,id,{$perm_group_id}]",
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
				'id' => $perm_group_id,
				'group_name' => ucwords($this->request->getVar('group')),
			];
			
			$result = service('permissionsLib')->add_group($data); // add role
			if ($this->request->isAJAX()) { // if request is ajax
				return $this->response->setJSON($result); // $result['error'] or $result['success']
			} else {
				if (isset($result['success'])) {
					$this->session->setFlashData('success', 'Permission Group Edited successfully');
				} else {
					$this->session->setFlashData('error', 'Unable to edit Permission Group');
				}
			}
			return redirect()->back();
		}
    }

}
