<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RolePermission extends Seeder
{
	public function run()
	{
		$data = [
			'0' => [
				'id' 			=> 1,
				'role_id' 		=> 1,
				'permission_id' => 1,
				'is_active' 	=> '1',
			],
			'1' => [
				'id' 			=> 2,
				'role_id' 		=> 1,
				'permission_id' => 2,
				'is_active' 	=> '1',
			],
			'2' => [
				'id' 			=> 3,
				'role_id' 		=> 1,
				'permission_id' => 3,
				'is_active' 	=> '1',
			],
			'3' => [
				'id' 			=> 4,
				'role_id' 		=> 1,
				'permission_id' => 4,
				'is_active' 	=> '1',
			],
			'4' => [
				'id' 			=> 5,
				'role_id' 		=> 1,
				'permission_id' => 1,
				'is_active' 	=> '1',
			],
			'5' => [
				'id' 			=> 6,
				'role_id' 		=> 1,
				'permission_id' => 2,
				'is_active' 	=> '1',
			],
			'6' => [
				'id' 			=> 7,
				'role_id' 		=> 1,
				'permission_id' => 3,
				'is_active' 	=> '1',
			],
			'7' => [
				'id' 			=> 8,
				'role_id' 		=> 1,
				'permission_id' => 4,
				'is_active' 	=> '0',
			],
		];

		$model = model('Ecosystem\Authentication\Models\RolePermission');
		$model->insertBatch($data);
	}
}
