<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_bahasa extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'name' => array(
				'type' => 'varchar',
				'constraint' => '100',
				'null' => TRUE,
			),
			
			'status' => array(
				'type' => 'ENUM("0","1")',
				'null' => false,
				'default' => "1",
			),
			'created_at' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'created_by' => array(
				'type' => 'BIGINT',
				'null' => TRUE,
				'unsigned' => FALSE,
				'auto_increment' => FALSE,
			),
			'updated_at' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'updated_by' => array(
				'type' => 'BIGINT',
				'null' => TRUE,
				'unsigned' => FALSE,
				'auto_increment' => FALSE,
			),
			'deleted_at' => array(
				'type' => 'timestamp',
				'null' => true,
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('bahasa');
	}

	public function down()
	{
		$this->dbforge->drop_table('bahasa');
	}
}
