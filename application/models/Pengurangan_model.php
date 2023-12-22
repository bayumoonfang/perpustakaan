<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pengurangan_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'substractions';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function total_data($search = null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('title'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data($number = 10, $offset = 0, $search = null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return array();
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('title'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$data[$key]->library_name = $library_name;
		}
		return $data;
	}

	public function get_data($value, $column = 'id')
	{
		$this->db->where($column, $value);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return false;
			}
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}

	public function add()
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $input['library'],
			'title' => $input['title'],
			'status' => $input['status'],
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		if(isset($input['issue_category']) && $input['issue_category']=='1'){
			$data['issue_category'] = '1';
		}else{
			$data['issue_category'] = '0';
		}
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id)
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $input['library'],
			'title' => $input['title'],
			'status' => $input['status'],
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		if (isset($input['issue_category']) && $input['issue_category'] == '1') {
			$data['issue_category'] = '1';
		} else {
			$data['issue_category'] = '0';
		}
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function delete($id){
		
		$data = [
			'deleted_at' => now(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function data_per_library($id)
	{
		$this->db->where('id', $id);
		$this->db->where('status', '1');
		$this->db->where('deleted_at', null);
		$library = $this->db->get(db_prefix() . 'libraries')->row();
		if (!$library) {
			return array();
		};
		$this->db->where($this->column('library'), $id);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('title'), 'asc');
		$data = $this->db->get($this->table)->result();
		return $data;
	}
}
