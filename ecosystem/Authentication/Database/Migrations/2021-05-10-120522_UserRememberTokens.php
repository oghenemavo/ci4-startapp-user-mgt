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
			],
			'user_id' => [
				'type'       		=> 'INT',
				'constraint' 		=> 11,
				'unsigned'       	=> true,
			],
			'remember_token' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
			],
			'user_agent' => [
				'type'       		=> 'TEXT',
			],
			'expires_at' => [
				'type' 				=> 'DATETIME',
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
