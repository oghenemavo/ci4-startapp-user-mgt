<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
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
			'last_name' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 50,
			],
			'first_name' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 50,
			],
			'phone_number' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 20,
			],
			'user_email' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 100,
				'null' 				=> false,
			],
			'user_password' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 				=> false,
			],
			'password_reset_token' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 		 		=> true,
			],
			'password_reset_expires_at' => [
				'type'       		=> 'DATETIME',
				'null' 		 		=> true,
			],
			'activation_token' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 		 		=> true,
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
		$this->forge->addUniqueKey('user_email');
		$this->forge->addUniqueKey('password_reset_token');
		$this->forge->addUniqueKey('activation_token');
		$this->forge->createTable('users');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('users');
	}
}
