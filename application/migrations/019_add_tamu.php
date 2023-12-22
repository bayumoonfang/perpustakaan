<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_tamu extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'library' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'user' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'status' => array(
				'type' => 'varchar',
				'constraint' => '255',
				'null' => true,
			),
			'date' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'time' => array(
				'type' => 'varchar',
				'constraint' => '30',
				'null' => true,
			),
			'description' => array(
				'type' => 'TEXT',
				'null' => TRUE,
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
		$this->dbforge->create_table('tamu');
	}

	public function down()
	{
		$this->dbforge->drop_table('tamu');
	}
}
