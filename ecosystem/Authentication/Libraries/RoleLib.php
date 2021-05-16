<?php

namespace Ecosystem\Authentication\Libraries;

use Config\Database;
use Ecosystem\Authentication\Models\{UserRole, Role, RolePermission, PermissionGroup, Permission};

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
    public function get_user_role_info(int $user_id)
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
     * Validate a role is active
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
     * Set role to have requiste permissions
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
     * Set a user role to have requiste permissions
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

    /**
     * Get All roles
     *
     * @return void
     */
    public function get_roles() {
        $role = new Role(); // role table
        return $role->findAll();
    }

    
    public function find_role(int $role_id) {
        $role = new Role();
        return $role->find($role_id);
    }

    /**
     * Get all Permissions
     *
     * @return void
     */
    public function get_permissions()
    {
        return $this->permissions;
    }

    /**
     * Check if (a) permission(s) is set
     *
     * @param mixed $permission         
     * @return boolean
     */
    public function has_permission(mixed $permission):bool
    {
        if (is_string($permission)) {
            return isset($this->permissions[$permission]);
        } elseif (is_array($permission)) {
            foreach ($permission as $value) {
                if(!isset($this->permissions[$value])) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }


    /**
     * Add or Edit a role
     *
     * @param array $data
     * @return void
     */
    public function add_role($data) {
        $result = [];
        
        $db = Database::connect();

        $db->transStart(); // start transaction

        $roles = new Role();

        try {
            $roles->save($data);
        } catch (\ReflectionException $e) {
        }

        // end transaction
        $db->transComplete();

        if ($db->transStatus() === false) {
            $result['error'] = 'Unable to perform this request';
        } else {
            $result['success'] = true;
        }
        return $result;
    }

}
