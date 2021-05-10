<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermissionGroup extends Seeder
{
	public function run()
	{
		$data = [
			'0' => [
				'id' 			=> 1,
				'group_name' 	=> 'User',
				'group_slug' 	=> 'user',
				'created_at'    => date('Y-m-d H:i:s'),
				'updated_at'    => date('Y-m-d H:i:s'),
			],
			'1' => [
				'id' 			=> 2,
				'group_name' 	=> 'Role',
				'group_slug' 	=> 'role',
				'created_at'    => date('Y-m-d H:i:s'),
				'updated_at'    => date('Y-m-d H:i:s'),
			],
		];

		$model = model('Ecosystem\Authentication\Models\PermissionGroup');
		$model->insertBatch($data);
	}
}
