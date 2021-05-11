<?php

namespace Ecosystem\Authentication\Models;

use CodeIgniter\Model;

class Role extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'roles';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'role',
		'role_slug',
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

	
	/**
	 * Check if role is active
	 *
	 * @param integer $role_id		column (id)
	 * @return boolean
	 */
    public function isRoleActive(int $role_id):bool {
        return (bool) $this->where('id', $role_id)->where('is_active', '1')->countAll();
    }
}
