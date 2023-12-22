<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_issue extends CI_Migration
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
			'book' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'issue_date' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'return_date' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'expired_date' => array(
				'type' => 'timestamp',
				'null' => true,
			),
			'status' => array(
				'type' => 'ENUM("pinjam","kembali","rusak","hilang")',
				'default' => 'pinjam',
				'null' => FALSE,
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
		$this->dbforge->create_table('issues');
	}

	public function down()
	{
		$this->dbforge->drop_table('issues');
	}
}
