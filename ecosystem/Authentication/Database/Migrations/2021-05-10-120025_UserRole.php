<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserRole extends Migration
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
			'user_id' => [
				'type'       		=> 'INT',
				'constraint' 		=> 11,
				'unsigned'       	=> true,
				'null' 				=> false,
			],
			'role_id' => [
				'type'       		=> 'INT',
				'constraint' 		=> 11,
				'unsigned'       	=> true,
				'null' 				=> false,
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
		$this->forge->addKey('user_id');
		$this->forge->addForeignKey('user_id','users','id','RESTRICT','CASCADE');
		$this->forge->addKey('role_id');
		$this->forge->addForeignKey('role_id','roles','id','RESTRICT','CASCADE');
		$this->forge->createTable('user_role');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('user_role');
	}
}
