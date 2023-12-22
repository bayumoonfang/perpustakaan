<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan_peminjaman_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'set_peminjaman';
		$this->table_library = db_prefix() . 'libraries';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function total_data($search=null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('id'), $user_library);
			} else {
				return 0;
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('library'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table_library)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data($number = 10, $offset = 0, $search=null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('id'), $user_library);
			} else {
				return array();
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('library'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table_library, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$jml_pinjam = 0;
			$hari_pinjam = 0;
			$denda_hari = 0;
			$this->db->where($this->column('library'), $item->id);
			$this->db->where($this->column('deleted_at'), null);
			$pinjamData = $this->db->get($this->table)->row();
			if (!empty($pinjamData)) {
				$jml_pinjam = $pinjamData->jml_pinjam;
				$hari_pinjam = $pinjamData->hari_pinjam;
				$denda_hari = $pinjamData->denda_hari;
			};
			$data[$key]->jml_pinjam = $jml_pinjam;
			$data[$key]->hari_pinjam = $hari_pinjam;
			$data[$key]->denda_hari = $denda_hari;
		}
		return $data;
	}

	public function get_data($value, $column = 'id')
	{
		$this->db->where($column, $value);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('id'), $user_library);
			} else {
				return false;
			}
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table_library)->row();
		if (empty($data)) {
			return false;
		}

		$this->db->where($this->column('library'), $data->id);
		$this->db->where($this->column('deleted_at'), null);
		$pinjamData = $this->db->get($this->table)->row();
		$data->jml_pinjam = 0;
		$data->hari_pinjam = 0;
		$data->denda_hari = 0;
		if (!empty($pinjamData)) {
			$data->jml_pinjam = $pinjamData->jml_pinjam;
			$data->hari_pinjam = $pinjamData->hari_pinjam;
			$data->denda_hari = $pinjamData->denda_hari;
		};

		return $data;
	}

	public function update($id){
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'hari_pinjam' => $input['hari_pinjam'],
			'jml_pinjam' => $input['jml_pinjam'],
			'denda_hari' => $input['denda_hari'],
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->where($this->column('library'), $id);
		$this->db->where($this->column('deleted_at'), null);
		$pinjamData = $this->db->get($this->table)->row();
		if (!empty($pinjamData)) {
			$this->db->where('library', $id);
			$this->db->update($this->table, $data);
		}else{
			$data['created_at']= now();
			$data['created_by']=current_user();
			$data['library']= $id;
			$this->db->insert($this->table, $data);
		}
		return true;
	}

	public function get_detail($value,$column='id'){
		$this->db->where($this->column($column), $value);
		$this->db->where($this->column('deleted_at'), null);
		$pinjamData = $this->db->get($this->table)->row();
		if(!$pinjamData){
			return false;
		}
		return $pinjamData;
	}
}
