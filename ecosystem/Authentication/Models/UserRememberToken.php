<?php

namespace Ecosystem\Authentication\Models;

use CodeIgniter\Model;

class UserRememberToken extends Model
{
	protected $DBGroup              = 'default';
	protected $table                = 'user_remember_tokens';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = true;
	protected $insertID             = 0;
	protected $returnType           = 'object';
	protected $useSoftDelete        = false;
	protected $protectFields        = true;
	protected $allowedFields        = [
		'user_id',
		'remember_token',
        'user_agent',
        'expires_at',
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
	 * Fetch a stored remember login data by token
	 *
	 * @param string $token
	 * @return mixed
	 */
	public function fetchRememberByToken (string $token):mixed {
        return $this->where('remember_token', $token)->first();
    }

	public function fetchUserByToken(string $token):mixed {
        $this->select('user_remember_tokens.remember_token, user_remember_tokens.user_agent, user_remember_tokens.expires_at');
        $this->select('t1.id, t1.first_name, t1.last_name, t1.user_email');
        $this->select('t3.role, t3.role_slug');

        $this->join('users t1', 't1.id = user_remember_tokens.user_id');
        $this->join('user_role t2', 't1.id = t2.user_id');
        $this->join('roles t3', 't2.role_id = t3.id');
        return $this->where('user_remember_tokens.remember_token', $token)->first();
    }
}
