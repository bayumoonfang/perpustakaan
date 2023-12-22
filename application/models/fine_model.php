<?php defined('BASEPATH') or exit('No direct script access allowed');

class Fine_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'fines';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}
}
