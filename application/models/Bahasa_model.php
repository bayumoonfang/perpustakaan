<?php defined('BASEPATH') or exit('No direct script access allowed');

class Bahasa_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'bahasa';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function data()
	{
		$this->db->where($this->column('status'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('name'), 'asc');
		$data = $this->db->get($this->table)->result();
		return $data;
	}

	public function get_data($value, $column = 'id')
	{
		$this->db->where($column, $value);
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}
}
