<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class UserRememberTokens extends Migration
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
			'remember_token' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 				=> false,
			],
			'user_agent' => [
				'type'       		=> 'TEXT',
				'null' 				=> false,
			],
			'expires_at' => [
				'type' 				=> 'DATETIME',
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
		$this->forge->createTable('user_remember_tokens');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('user_remember_tokens');
	}
}
