<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Role extends Seeder
{
	public function run()
	{
		$data = [
			'id' 			=> 1,
			'role' 	=> 'Super Admin',
			'role_slug' 	=> 'super_admin',
			'is_active'		=> '1',
		];

		$model = model('Ecosystem\Authentication\Models\Role');
		$model->insert($data);
	}
}
