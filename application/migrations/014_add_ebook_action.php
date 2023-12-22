<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_ebook_action extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'book' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'user' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'action' => array(
				'type' => 'ENUM("view","like")',
				'default' => 'view',
				'null' => FALSE,
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
		$this->dbforge->create_table('ebook_action');
	}

	public function down()
	{
		$this->dbforge->drop_table('ebook_action');
	}
}
