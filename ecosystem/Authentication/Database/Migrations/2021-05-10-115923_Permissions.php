<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permissions extends Migration
{
	public function up()
	{
		$fields = [
			'id' => [
				'type' 				=> 'INT',
				'constraint'     	=> 11,
				'unsigned'       	=> true,
				'auto_increment' 	=> true,
			],
			'perm_group_id' => [
				'type' 				=> 'INT',
				'constraint'     	=> 11,
				'unsigned'       	=> true,
			],
			'permission' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 50,
			],
			'permission_slug' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 100,
			],
			'created_at' => [
				'type' 				=> 'DATETIME',
			],
			'updated_at' => [
				'type' 				=> 'DATETIME',
			]
		];

		$this->db->disableForeignKeyChecks();

		$this->forge->addField($fields);
		$this->forge->addPrimaryKey('id');
		$this->forge->addKey('perm_group_id');
		$this->forge->addForeignKey('perm_group_id','permission_groups','id','RESTRICT','CASCADE');
		$this->forge->createTable('permissions');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('permissions');
	}
}
