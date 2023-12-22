<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_transactions extends CI_Migration
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
			'book' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'category' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'qty' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
			),
			'type' => array(
				'type' => 'ENUM("in","out")',
				'default' => 'in',
				'null' => FALSE,
			),
			'notes' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			),
			'date' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'reff' => array(
				'type' => 'BIGINT',
				'null' => TRUE,
				'unsigned' => false,
				'auto_increment' => false
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
		$this->dbforge->create_table('transactions');
	}

	public function down()
	{
		$this->dbforge->drop_table('transactions');
	}
}
