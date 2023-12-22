<?php defined('BASEPATH') or exit('No direct script access allowed');

class Rak_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'bookselfs';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function data($library,$number = 10, $offset = 0, $search = null,$active=false)
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
			$this->db->like($this->column('rack'), $search);
			$this->db->group_end();
		}
		if($active){
			$this->db->where($this->column('status'), '1');
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('library'), $library);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		return $data;
	}

	public function exists_rak($rack_input, $library,$id=null)
	{
		$this->db->where($this->column('rack'), $rack_input);
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
	
	public function add($library)
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $library,
			'rack' => $input['rack'],
			'status' => $input['status'],
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id,$library)
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $library,
			'rack' => $input['rack'],
			'status' => $input['status'],
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->where($this->column('id'),$id);
		$this->db->update($this->table, $data);
		return $this->db->insert_id();
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
}
