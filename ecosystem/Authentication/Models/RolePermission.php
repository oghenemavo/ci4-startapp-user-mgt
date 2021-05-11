<?php

namespace Ecosystem\Authentication\Models;

use CodeIgniter\Model;

class RolePermission extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'role_permissions';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'role_id',
		'permission_id',
		'is_active',
	];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
	protected $deletedField         = 'deleted_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	public function fetchRolePermissionsInfo($role_id):array
    {
        $this->select('t2.permission, t2.permission_slug');
        $this->join('permissions t2', 'role_permissions.permission_id = t2.id');
        $this->where('role_permissions.role_id', $role_id);
        return $this->where('role_permissions.is_active', '1')->get()->getResultObject(); // permission is active
    }
	
}
