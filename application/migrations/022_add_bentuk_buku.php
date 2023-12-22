<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_bentuk_buku extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'bentuk' => array(
				'type' => 'varchar',
				'constraint' => '255',
				'null' => true,
				'after' => 'isbn'
			),
			
		);
		$this->dbforge->add_column('books', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('books', 'bentuk');
	}
}
