<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_peminjaman extends CI_Migration
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
			'jml_pinjam' => array(
				'type' => 'int',
				'constraint' => '11',
				'default' => 0,
			),
			'hari_pinjam' => array(
				'type' => 'int',
				'constraint' => '11',
				'default' => 0,
			),
			'denda_hari' => array(
				'type' => 'int',
				'constraint' => '11',
				'default' => 0,
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
		$this->dbforge->create_table('set_peminjaman');
	}

	public function down()
	{
		$this->dbforge->drop_table('set_peminjaman');
	}
}
