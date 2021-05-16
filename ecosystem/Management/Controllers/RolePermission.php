<?php

namespace Ecosystem\Management\Controllers;

use Ecosystem\Authentication\Controllers\AuthBaseController;
use Ecosystem\Management\Libraries\PermissionsLib;

class RolePermission extends AuthBaseController
{
	protected $viewPath = 'Ecosystem\\Management\\Views\\settings\\';

	/**
	 * Role Permissions page
	 *
	 * @param integer $role_id
	 * @return void
	 */
	public function index($role_id)
	{
		$this->userLib->has_privilege_or_exception('edit_role'); // check privilege or 404

		$role_info = $this->roleLib->find_role($role_id);
		if ($role_info) {
			$data['page_title'] = 'Edit Role Permission';
			$data['validation'] = $this->validation;
			$data['role_info'] = $role_info;
            $data['role_perm'] = service('permissionsLib')->find_role_permission($role_id); // find role permission
			$data['role_permissions'] = service('permissionsLib')->get_role_permissions($role_id);
			$data['permission_group'] = service('permissionsLib')->get_permission_groups(); // fetch permission groups
            $data['permissions'] = service('permissionsLib')->get_permissions(); // get permissions
			return  view($this->_setPagePath($this->viewPath, 'edit-role-permission'), $data);
		}
		return redirect()->back()->with('error', 'Role not found');
	}

	/**
	 * Set role permissions
	 *
	 * @param integer $role_id
	 * @return void
	 */
	public function edit($role_id) {
        $this->userLib->has_privilege_or_exception('edit_role'); // check if user has the privilege or show 404
		
		$data = [
			'role_id' => $role_id,
			'permission_id' => $this->request->getVar('permission'),
		];
		
		$result = service('permissionsLib')->set_role_permission($data); // add role
		if ($this->request->isAJAX()) { // if request is ajax
			return $this->response->setJSON($result); // $result['error'] or $result['success']
		} else {
			if (isset($result['success'])) {
				$this->session->setFlashData('success', 'Role Permissions set successfully');
			} else {
				$this->session->setFlashData('error', 'Unable to set Role Permissions');
			}
		}
		return redirect()->back();
    }

}
