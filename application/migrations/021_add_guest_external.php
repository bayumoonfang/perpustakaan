<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_guest_external extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'is_guest' => array(
				'type' => 'ENUM("0","1")',
				'null' => false,
				'default' => "0",
				'after' => 'status'
			),
			'guest_name' => array(
				'type' => 'varchar',
				'constraint' => '255',
				'null' => true,
				'after' => 'is_guest'
			),
			'institution' => array(
				'type' => 'varchar',
				'constraint' => '255',
				'null' => true,
				'after' => 'guest_name'
			),
			
		);
		$this->dbforge->add_column('tamu', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('tamu', 'is_guest');
		$this->dbforge->drop_column('tamu', 'guest_name');
		$this->dbforge->drop_column('tamu', 'institution');
	}
}
