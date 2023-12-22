<?php defined('BASEPATH') or exit('No direct script access allowed');

class Sekolah_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_master_prefix() . 'master_sekolah';
		$this->table_prefix = 'sekolah_';
		$this->db = $this->load->database('master', true);
	}

	public function data($number = 10, $offset = 0, $search = null)
	{
		if (!empty($search)) {
			$this->db->like($this->table_prefix . 'nama', $search);
		}
		$this->db->where($this->table_prefix . 'status', '1');
		$this->db->order_by($this->table_prefix . 'nama', 'asc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		return $data;
	}

	public function detail_sekolah($sekolah_id)
	{

		$this->db->where($this->table_prefix . 'id', $sekolah_id);
		$this->db->order_by($this->table_prefix . 'nama', 'asc');
		$data = $this->db->get($this->table)->row();
		return $data;
	}
}
