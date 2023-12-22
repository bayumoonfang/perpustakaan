<?php defined('BASEPATH') or exit('No direct script access allowed');

class Kelas_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_master_prefix() . 'master_kelas';
		$this->table_prefix = 'kelas_';
		$this->db = $this->load->database('master', true);
	}

	public function data()
	{
		if (!empty($search)) {
			$this->db->like($this->table_prefix . 'nama', $search);
		}
		if (!is_admin()) {
			$sekolah_id = current_user('user_sekolahid');
			$this->db->where_in($this->table_prefix . 'sekolahid', $sekolah_id);
		}
		$this->db->where($this->table_prefix . 'isdelete', '0');
		$this->db->order_by($this->table_prefix . 'nama', 'asc');
		$data = $this->db->get($this->table)->result();
		if(is_admin()){
			foreach ($data as $key => $item) {
				$sekolah_nama='-';
				$this->db->where('sekolah_id', $item->kelas_sekolahid);
				$data_sekolah = $this->db->get(db_master_prefix() . 'master_sekolah')->row();
				if(!empty($data_sekolah)){
					$sekolah_nama=$data_sekolah->sekolah_nama;
				}
				$data[$key]->sekolah_nama= $sekolah_nama;
			}
		}
		return $data;
	}

	public function data_per_library($id){
		$db_local=$this->load->database('default', true);
		$db_local->where('id',$id);
		$db_local->where('status','1');
		$db_local->where('deleted_at',null);
		$library= $db_local->get(db_prefix().'libraries')->row();
		if(!$library){
			return array();
		};
		$sekolah_id=$library->school;
		$this->db->where_in($this->table_prefix . 'sekolahid', $sekolah_id);
		$this->db->where($this->table_prefix . 'isdelete', '0');
		$this->db->order_by($this->table_prefix . 'nama', 'asc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$sekolah_nama = '';
			$this->db->where('sekolah_id', $item->kelas_sekolahid);
			$data_sekolah = $this->db->get(db_master_prefix() . 'master_sekolah')->row();
			if (!empty($data_sekolah)) {
				$sekolah_nama = $data_sekolah->sekolah_nama;
			}
			$data[$key]->sekolah_nama = $sekolah_nama;
		}
		return $data;
	}
}
