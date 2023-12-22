<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_issue_substraction extends CI_Migration
{

	public function up()
	{
		$fields = array(
			'issue_category' => array(
				'type' => 'ENUM("1","0")',
				'default' => '0',
				'null' => FALSE,
				'after' => 'status'
			)
		);
		$this->dbforge->add_column('substractions', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('substractions', 'issue_category');
	}
}
