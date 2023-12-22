<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Alter_type_fines extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'type' => array(
				'name' => 'type',
				'type' => 'varchar',
				'default' => NULL,
				'null' => TRUE,
				'constraint' => '255',
			)
		);
		$this->dbforge->modify_column('fines', $fields);
	}

	public function down()
	{

	}
}
