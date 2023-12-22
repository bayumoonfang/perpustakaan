<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_books extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'BIGINT',
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
			'code' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'school' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'class' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'mapel' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'library' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'rak' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'category' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'title' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'author' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'publisher' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'year' => array(
				'type' => 'VARCHAR',
				'constraint' => '5',
				'null' => TRUE,
			),
			'isbn' => array(
				'type' => 'VARCHAR',
				'constraint' => '255',
				'null' => TRUE,
			),
			'cover' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
			'fileurl' => array(
				'type' => 'LONGTEXT',
				'null' => TRUE,
			),
			'language' => array(
				'type' => 'BIGINT',
				'unsigned' => false,
				'auto_increment' => false
			),
			'qty' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
			),
			'is_physical_book' => array(
				'type' => 'ENUM("0","1")',
				'null' => false,
				'default' => "0",
			),
			'is_digital_book' => array(
				'type' => 'ENUM("0","1")',
				'null' => false,
				'default' => "0",
			),
			'status' => array(
				'type' => 'ENUM("0","1")',
				'null' => false,
				'default' => "1",
			),
			'private' => array(
				'type' => 'ENUM("0","1")',
				'null' => false,
				'default' => "0",
			),
			'price' => array(
				'type' => 'DOUBLE',
				'null' => false,
				'default' => "0",
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
		$this->dbforge->create_table('books');
	}

	public function down()
	{
		$this->dbforge->drop_table('books');
	}
}
