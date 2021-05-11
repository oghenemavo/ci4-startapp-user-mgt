<?php

namespace Ecosystem\Authentication\Libraries;

use Config\{Database, Services};
use CodeIgniter\I18n\Time;
use Ecosystem\Authentication\Models\{User, UserRole, Role, RolePermission};

class RoleLib
{
    public $roles = [];
    public $permissions = [];

    /**
     * Get a User Role details
     *
     * @param integer $user_id              user id
     * @return object
     */
    public function get_user_role_info(int $user_id):object
    {
        $user_role = new UserRole();
        return $user_role->fetchRoleInfo($user_id);
    }

    /**
     * Get Permissions associated with a role
     *
     * @param integer $role_id                  role id
     * @return array
     */
    public function get_role_permissions_info(int $role_id):array
    {
        $role_permission = new RolePermission();
        $role_setup = $role_permission->fetchRolePermissionsInfo($role_id);

        foreach ($role_setup as $setup) {
            $this->permissions[$setup->permission_slug] = true;
        }
        return $this->permissions;
    }

    /**
     * Undocumented function
     *
     * @param integer $role_id
     * @return boolean
     */
    public function validate_role(int $role_id):bool
    {
        $role = new Role();
        return $role->isRoleActive($role_id);
    }

    /**
     * Undocumented function
     *
     * @param integer $user_id
     * @return array
     */
    public function set_role(int $user_id):array
    {
        $role = $this->get_user_role_info($user_id);
        if ($role) {
            $this->roles[$role->role] = $this->get_role_permissions_info($role->role_id);
        }
        return $this->roles;
    }

    /**
     * Undocumented function
     *
     * @param integer $user_id
     * @return mixed
     */
    public function set_user_role(int $user_id):mixed
    {
        $role_id = $this->get_user_role_info($user_id)->role_id;
        if ($role_id) {
            if ($this->validate_role($role_id)) {
                return $this->set_role($user_id);
            }
        }
        return false;
    }

}
