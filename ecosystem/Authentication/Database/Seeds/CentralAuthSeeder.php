<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CentralAuthSeeder extends Seeder
{
	public function run()
	{
		$this->call('Ecosystem\Authentication\Database\Seeds\Role');
		$this->call('Ecosystem\Authentication\Database\Seeds\PermissionGroup');
		$this->call('Ecosystem\Authentication\Database\Seeds\Permission');
		$this->call('Ecosystem\Authentication\Database\Seeds\RolePermission');
	}
}
