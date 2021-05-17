<?php

namespace Ecosystem\Authentication\Database\Migrations;

use CodeIgniter\Database\Migration;

class MailTemplates extends Migration
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
			'mail_for' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 50,
				'null' 				=> false,
			],
			'for_slug' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 100,
				'null' 				=> false,
			],
			'mail_from' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 150,
				'null' 				=> false,
			],
			'from_name' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 				=> false,
			],
			'subject' => [
				'type'       		=> 'VARCHAR',
				'constraint' 		=> 256,
				'null' 				=> false,
			],
			'template_html' => [
				'type'       		=> 'TEXT',
				'null' 				=> false,
			],
			'template_text' => [
				'type'       		=> 'TEXT',
				'null' 				=> false,
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
		$this->forge->addUniqueKey('mail_for');
		$this->forge->addUniqueKey('for_slug');
		$this->forge->createTable('mail_templates');

		$this->db->enableForeignKeyChecks();
	}

	public function down()
	{
		$this->forge->dropTable('mail_templates');
	}
}
