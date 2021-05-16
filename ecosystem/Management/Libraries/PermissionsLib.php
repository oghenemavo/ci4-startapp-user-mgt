<?php

namespace Ecosystem\Management\Libraries;

use Config\{Database, Services};
use Ecosystem\Authentication\Models\{Permission, PermissionGroup, RolePermission};

class PermissionsLib
{
    /**
     * Get all permission group
     *
     * @return void
     */
    public function get_permission_groups() {
        $group = new PermissionGroup(); // permission group table
        return $group->findAll();
    }

    /**
     * Get a Permission group by its id
     *
     * @param int $id
     * @return void
     */
    public function find_permission_group($id) {
        $group = new PermissionGroup();
        return $group->find($id);
    }

    /**
     * Add a group into the PermissionGroup table and set the permissions into the Permission table
     *
     * @param int $data
     * @return void
     */
    public function add_group($data) {
        $result = [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        $group = new PermissionGroup(); // permission-group table
        try {
            $group->save($data); // add/edit
        } catch (\ReflectionException $e) {
        }

        if (!isset($data['id'])) {
            $group_insert_id = $group->insertID();
            $batch_data = $this->set_permission($group_insert_id, $data['group_focus']);

            try {
                $permission = new Permission();
                $permission->insertBatch($batch_data); // insert multiple data
            } catch (\ReflectionException $e) {
            }
        }
        
        // end transaction
        $db->transComplete();
        if ($db->transStatus() === false) {
            $result['error'] = 'Unable to perform this request';
        } else {
            // append inserted to roles
            $this->append_role_permission($group_insert_id);
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Add new permissions to existing roles
     *
     * @param integer $group_id
     * @return void
     */
    protected function append_role_permission($group_id) {
        $insert_batch_data = [];
        $permission = new Permission();
        $set_permission = $permission->where('perm_group_id', $group_id)->get()->getResultObject();

        if ($set_permission) {
            $roles = service('roleLib')->get_roles();
            foreach($roles as $role) {
                foreach($set_permission as $perm) {
                    $insert_batch_data[] = [
                        'role_id' => $role->id,
                        'permission_id' => $perm->id,
                        'is_active' => '0'
                    ];
                }
            }
            if (count($insert_batch_data) > 0) {
                $role_perm = new RolePermission();
                try {
                    return $role_perm->insertBatch($insert_batch_data);
                } catch (\ReflectionException $e) {
                }
            }
        }
        return false;
    }

    /**
     * Set an array of permissions
     *
     * @param int $perm_group_id
     * @param string $focus
     * @return array
     */
    protected function set_permission(int $perm_group_id, string $focus) {
        helper('inflector');
        $operation = ['add', 'edit', 'view', 'delete'];
        $data = [];
        foreach ($operation as $key => $action) {
            $data[$key] = [
                'perm_group_id' => $perm_group_id,
                'permission' => ucwords( "{$action} " . $focus),
                'permission_slug' => "{$action}_" . underscore(strtolower($focus)),
            ];
        }
        return $data;
    }

    /**
     * Get a Role Permission
     *
     * @param integer $id
     * @return void
     */
    public function find_role_permission($id) {
        $role_perm = new RolePermission();
        return $role_perm->find($id);
    }

    /**
     * Fetch Role Permissions by conditions
     *
     * @param integer $role_id
     * @return void
     */
    public function get_role_permissions($role_id = 0) {
        $role_perm = new RolePermission();
        if ($role_id) {
            return $role_perm->where('role_id', $role_id)->get()->getResultObject();
        }
        return $role_perm->findAll();
    }

    /**
     * Get all Permissions
     *
     * @return void
     */
    public function get_permissions() {
        $permission = new Permission();
        return $permission->findAll();
    }

    /**
     * Set Permissions to a role
     *
     * @param array $data
     * @return void
     */
    public function set_role_permission(array $data) {
        $result = $update_batch_data = $insert_batch_data = [];
        $role_id = $data['role_id'];
        $permissions_id = $data['permission_id'] ?? [];

        $db = Database::connect();
        $db->transStart(); // start transaction

        $role_permissions = $this->get_role_permissions($role_id); // permissions associated with role id

        $general_permisssions = $this->get_permissions(); // general permissions

        $rp = new RolePermission();

        if (empty($role_permissions)) { // if role doesn't have stored db permissions
            foreach ($general_permisssions as $gen_perm) {
                if (in_array($gen_perm->id, $permissions_id)) {
                    $insert_batch_data[] = [
                        'role_id' => $role_id,
                        'permission_id' => $gen_perm->id,
                        'is_active' => '1',
                    ];
                } else {
                    $insert_batch_data[] = [
                        'role_id' => $role_id,
                        'permission_id' => $gen_perm->id,
                        'is_active' => '0',
                    ];
                }
            }

            try {
                $rp->insertBatch($insert_batch_data, 'id');
            } catch (\ReflectionException $e) {
            }
        } else { // if role haves stored db permissions
            foreach ($role_permissions as $permission) {
                if (in_array($permission->permission_id, $permissions_id)) { // if role set permission is in the form checked submitted permission
                    // set it to active 
                    $update_batch_data[] = [
                        'id' => $permission->id,
                        'is_active' => '1',
                    ];
                    $set_perm_uarr[] = $permission->permission_id; // perm id set for update
                } else { // if role set permission IS NOT in the form checked submitted permission
                    $update_batch_data[] = [
                        'id' => $permission->id,
                        'is_active' => '0',
                    ];
                    $set_perm_uarr[] = $permission->permission_id; // perm id set for update
                }
            }
            if (!empty($update_batch_data)) {
                try {
                    $rp->updateBatch($update_batch_data, 'id');
                } catch (\ReflectionException $e) {
                }
            } else {
                if (count($general_permisssions) > count($role_permissions)) {

                    foreach($role_permissions as $rp_data) {
                        $rp_id_arr[] = $rp_data->permission_id;
                    }

                    foreach($general_permisssions as $gp_data) {
                        $gp_id_arr[] = $gp_data->id;
                    }

                    $diff = array_diff($gp_id_arr,$rp_id_arr);

                    foreach ($diff as $diff_id) {
                        if (in_array($diff_id, $permissions_id)) {
                            $insert_batch_data[] = [
                                'role_id' => $role_id,
                                'permission_id' => $diff_id,
                                'is_active' => '1',
                            ];
                        } else {
                            $insert_batch_data[] = [
                                'role_id' => $role_id,
                                'permission_id' => $diff_id,
                                'is_active' => '0',
                            ];
                        }
                    }
                    
                    if (!empty($insert_batch_data)) {
                        try {
                            $rp->insertBatch($insert_batch_data);
                        } catch (\ReflectionException $e) {
                        }
                    }
                }
            }
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