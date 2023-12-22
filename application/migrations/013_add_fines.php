<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_fines extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'issue' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
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
			'book' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'days' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
			),
			'amount' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
			),
			'fine' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
			),
			'total' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
			),
			'type' => array(
				'type' => 'ENUM("denda","rusak","hilang")',
				'default' => 'denda',
				'null' => FALSE,
			),
			'date' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'notes' => array(
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
		$this->dbforge->create_table('fines');
	}

	public function down()
	{
		$this->dbforge->drop_table('fines');
	}
}
