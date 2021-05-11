<?php

namespace Ecosystem\Authentication\Models;

use CodeIgniter\Model;

class User extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'users';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'first_name',
        'last_name',
        'phone_number',
        'user_email',
        'user_password',
        'password_reset_token',
        'activation_token',
        'is_active',
        'password_reset_expires_at',
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
	protected $beforeInsert         = ['hashPassword'];
	protected $afterInsert          = ['hashPassword'];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];

	protected function hashPassword(array $data) {
        if (isset($data['data']['user_password'])) { // check if the password key is set
            $data['data']['user_password'] = password_hash($data['data']['user_password'], PASSWORD_DEFAULT); // hash password
        }
        return $data;
    }

	/**
	 * Fetch a user record
	 *
	 * @param integer $id		user id
	 * @return void
	 */
	public function fetchUserById(int $id) {
        $this->select('users.*');
        $this->select('t2.*');
        $this->select('t3.role_id');
        $this->select('t4.role');

        $this->join('user_profile t2', 'users.id = t2.user_id');
        $this->join('user_role t3', 'users.id = t3.user_id');
        $this->join('roles t4', 't3.role_id = t4.id');
        return $this->where('users.id', $id)->first();
    }
	
}
