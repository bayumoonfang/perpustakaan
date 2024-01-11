<?php defined('BASEPATH') or exit('No direct script access allowed');

class Library_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'libraries';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function total_data($search = null)
	{
		if (!is_admin()) {
			$sekolah_id = current_user('user_sekolahid');
			$this->db->where($this->column('school'), $sekolah_id);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('library'), $search);
			$this->db->or_like($this->column('location'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return $total;
	}

	public function data($number = 10, $offset = 0, $search = null, $role = null, $sekolah = null)
	{
		if (!is_admin()) {
			$sekolah_id = current_user('user_sekolahid');
			$this->db->where($this->column('school'), $sekolah_id);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('library'), $search);
			$this->db->or_like($this->column('location'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$school_name = '-';
			$detail_school = $this->get_school($item->school);
			if ($detail_school) {
				$school_name = strtoupper($detail_school->sekolah_nama);
			}
			$data[$key]->school_name = $school_name;
		}
		return $data;
	}

	public function get_school($sekolah_id)
	{
		$this->db_master = $this->load->database('master', true);
		$this->db_master->where('sekolah_id', $sekolah_id);
		$data = $this->db_master->get(db_master_prefix() . 'master_sekolah')->row();
		if (!$data) {
			return false;
		}
		return $data;
	}

	public function exists_library($school, $library, $id = null)
	{
		$this->db->where($this->column('school'), $school);
		$this->db->where($this->column('library'), $library);
		$this->db->where($this->column('deleted_at'), null);
		if (!empty($id)) {
			$this->db->where($this->column('id') . ' !=', $id);
		}
		$exists = $this->db->get($this->table)->row();
		if (empty($exists)) {
			return false;
		}
		return $exists;
	}

	public function add()
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'school' => $input['school'],
			'library' => strtolower(trim($input['library'])),
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		if (isset($input['location'])) {
			$data['location'] = $input['location'];
		}
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
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
			$this->db->where_in($this->column('id'), $user_library);
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}

	public function update($id)
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'school' => $input['school'],
			'library' => strtolower(trim($input['library'])),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		if (isset($input['location'])) {
			$data['location'] = $input['location'];
		}
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function delete($id)
	{
		$data = [
			'updated_at' => now(),
			'deleted_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function current_user_library()
	{
		$sekolah_id = current_user('user_sekolahid');
		$this->db->where($this->column('school'), $sekolah_id);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('library'), 'asc');
		$data = $this->db->get($this->table)->result();
		// if (!is_admin()) {
		// } else {
		// 	$data = array("0" => (object)['id' => ' ']);
		// }
		return $data;
	}

	public function get_data_perpus($value, $column = 'id')
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
