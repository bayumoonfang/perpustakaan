<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_barcode_book extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'barcode' => array(
				'type' => 'varchar',
				'constraint' => '255',
				'null' => true,
				'after' => 'code'
			),
			
		);
		$this->dbforge->add_column('books', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('books', 'barcode');
	}
}
