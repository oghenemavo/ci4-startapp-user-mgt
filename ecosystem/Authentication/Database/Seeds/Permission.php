<?php

namespace Ecosystem\Authentication\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Permission extends Seeder
{
	public function run()
	{
		$data = [
			'0' => [
				'id' 				=> 1,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Add User',
				'permission_slug' 	=> 'add_user',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'1' => [
				'id' 				=> 2,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'View User',
				'permission_slug' 	=> 'view_user',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'2' => [
				'id' 				=> 3,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Edit User',
				'permission_slug' 	=> 'edit_user',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'4' => [
				'id' 				=> 4,
				'perm_group_id' 	=> 1,
				'permission' 		=> 'Delete User',
				'permission_slug' 	=> 'delete_user',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'5' => [
				'id' 				=> 5,
				'perm_group_id' 	=> 2,
				'permission' 		=> 'Add Role',
				'permission_slug' 	=> 'add_role',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'6' => [
				'id' 				=> 6,
				'perm_group_id' 	=> 2,
				'permission' 		=> 'Edit Role',
				'permission_slug' 	=> 'edit_role',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'7' => [
				'id' 				=> 7,
				'perm_group_id' 	=> 2,
				'permission' 		=> 'View Role',
				'permission_slug' 	=> 'view_role',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
			'8' => [
				'id' 				=> 8,
				'perm_group_id' 	=> 2,
				'permission' 		=> 'Delete Role',
				'permission_slug' 	=> 'delete_role',
				'created_at'    	=> date('Y-m-d H:i:s'),
				'updated_at'    	=> date('Y-m-d H:i:s'),
			],
		];

		$model = model('Ecosystem\Authentication\Models\Permission');
		$model->insertBatch($data);
	}
}
