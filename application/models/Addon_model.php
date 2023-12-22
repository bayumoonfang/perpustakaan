<?php defined('BASEPATH') or exit('No direct script access allowed');

class Addon_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'addons';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function data(){
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('name'), 'asc');
		return $this->db->get($this->table)->result();
	}
}
