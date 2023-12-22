<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_sessions extends CI_Migration
{

	public function up()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'varchar',
				'constraint' => '128',
				'null' => FALSE,
			),
			'ip_address' => array(
				'type' => 'varchar',
				'constraint' => '45',
				'null' => FALSE,
			),
			'timestamp' => array(
				'type' => 'int',
				'constraint' => '10',
				'unsigned' => true,
				'default' =>0,
				"null" =>false
			),
			'data' => array(
				'type' => 'blob',
				'null' => FALSE,
			),
		));
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('ip_address', TRUE);
		$this->dbforge->add_key('timestamp', TRUE);
		$this->dbforge->create_table('ci_sessions');
	}

	public function down()
	{
		$this->dbforge->drop_table('ci_sessions');
	}
}
