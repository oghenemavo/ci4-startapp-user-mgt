<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class Roles extends Migration
{
	public function up()
	{
		$fields = [
			'id' => [
				'type' 				=> 'INT',
				'constraint'     	=> 11,
				'unsigned'       	=> true,
				'auto_increment' 	=> true,
				'null' 				=> false,
			],
			'role' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 50,
				'null' 				=> false,
			],
			'role_slug' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 100,
				'null' 				=> false,
			],
			'is_active' => [
				'type'       		=> 'ENUM',
				'constraint' 		=> ['0', '1'],
				'default'        	=> '0',
			],
			'created_at' => [
				'type' 				=> 'DATETIME',
				'null' 				=> false,
			],
			'updated_at' => [
				'type' 				=> 'DATETIME',
			]
		];

		$this->db->disableForeignKeyChecks();

		$this->forge->addField($fields);
		$this->forge->addPrimaryKey('id');
		$this->forge->addUniqueKey('role');
		$this->forge->addUniqueKey('role_slug');
		$this->forge->createTable('roles');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('roles');
	}
}
