<?php defined('BASEPATH') or exit('No direct script access allowed');

class Bentuk_model extends App_Model
{
	private $table;
	//set kolom order, kolom pertama saya null untuk kolom edit dan hapus
	var $column_order = array('id', 'name', 'status');

	var $column_search = array('name');
	// default order 
	var $order = array('id' => 'asc');
	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'type';
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
			$this->db->like($this->column('name'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data($number = 10, $offset = 0, $search = null, $role = null, $sekolah = null)
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
			$this->db->like($this->column('name'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		return $data;
	}

	public function exist_type($bentuk, $id = null)
	{
		$this->db->where($this->column('name'), $bentuk);
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
			'name' => $input['name'],
			'status' => '1',
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function get_data($value, $column = 'id')
	{
		$this->db->where($column, $value);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return array();
			}
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function update($id)
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'name' => $input['name'],
			'status' => "1",
			'updated_at' => now(),
			'updated_by' => current_user(),
		];

		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function delete($id)
	{

		$data = [
			'deleted_at' => now(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];

		$this->db->where('id', $id);
		$del = $this->db->update($this->table, $data);

		if ($del) {
			return true;
		} else {
			return false;
		}
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		$this->db->where($this->column('deleted_at'), null);
		$i = 0;
		foreach ($this->column_search as $item) // loop kolom 
		{
			if ($this->input->post('search')['value']) // jika datatable mengirim POST untuk search
			{
				if ($i === 0) // looping pertama
				{
					$this->db->group_start();
					$this->db->like($item, $this->input->post('search')['value']);
				} else {
					$this->db->or_like($item, $this->input->post('search')['value']);
				}
				if (count($this->column_search) - 1 == $i) //looping terakhir
					$this->db->group_end();
			}
			$i++;
		}

		// jika datatable mengirim POST untuk order
		if ($this->input->post('order')) {
			$this->db->order_by($this->column_order[$this->input->post('order')['0']['column']], $this->input->post('order')['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($this->input->post('length') != -1)
			$this->db->limit($this->input->post('length'), $this->input->post('start'));
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
}
