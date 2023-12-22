<?php defined('BASEPATH') or exit('No direct script access allowed');

class Bentuk_model extends App_Model
{
	private $table;

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

	public function update($id)
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $input['library'],
			'status' => $input['status'],
			'category' => strtolower(trim($input['category'])),
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
		$this->db->update($this->table, $data);
		return true;
	}

	public function data_per_library($id)
	{
		$this->db->where($this->column('library'), $id);
		$this->db->where($this->column('status'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('category'), 'asc');
		$data = $this->db->get($this->table)->result();
		return $data;
	}

	public function api_data($param)
	{
		$user_sekolah = $this->api_user_sekolah($param['user']);
		if (!$user_sekolah) {
			return array();
		}
		$number = $param['limit'];
		$search = $param['search'];
		$offsets = ((int)$number * (int)$param['page']) - (int)$number;
		$offset = $offsets < 0 ? 0 : $offsets;

		$user_library = $this->user_library($user_sekolah->user_sekolahid);
		if (!empty($user_library)) {
			$this->db->where_in($this->column('library'), $user_library);
		} else {
			return array();
		}
		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('category'), 'asc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$data[$key]->library = $this->library_by_book($item->library);
			$data[$key]->jml_buku = $this->jumlah_buku($item->id);
		}
		return $data;
	}

	public function api_data_total($param)
	{
		$user_sekolah = $this->api_user_sekolah($param['user']);
		if (!$user_sekolah) {
			return 0;
		}
		$user_library = $this->user_library($user_sekolah->user_sekolahid);
		if (!empty($user_library)) {
			$this->db->where_in($this->column('library'), $user_library);
		} else {
			return 0;
		}
		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('category'), 'asc');
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function api_general_data($param)
	{

		$number = $param['limit'];
		$search = $param['search'];
		$search_book = $param['search_book'];
		$offsets = ((int)$number * (int)$param['page']) - (int)$number;
		$offset = $offsets < 0 ? 0 : $offsets;

		if (!empty($param['library'])) {
			$this->db->where_in($this->column('library'), $param['library']);
		} else {
			return array();
		}
		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('category'), 'asc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$data[$key]->jml_buku = $this->jumlah_buku_general($item->id, $item->library, $search_book);
			$data[$key]->library = $this->library_by_book($item->library);
			$data[$key]->category = ucwords($item->category);
		}
		return $data;
	}
	public function api_general_data_total($param)
	{

		if (!empty($param['library'])) {
			$this->db->where_in($this->column('library'), $param['library']);
		} else {
			return 0;
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('category'), 'asc');
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function library_by_book($id)
	{
		$this->db->where($this->column('id'), $id);
		$data = $this->db->get(db_prefix() . 'libraries')->row();
		if (!$data) {
			return null;
		}
		$arr = array('id' => $data->id, 'name' => $data->library);
		return $arr;
	}

	public function jumlah_buku($category_id)
	{
		$this->db->where($this->column('category'), $category_id);
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$data = $this->db->get(db_prefix() . 'books')->num_rows();
		return $data;
	}

	public function jumlah_buku_general($category_id, $library, $search_book)
	{
		if (!empty($search_book)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search_book);
			$this->db->or_like($this->column('title'), $search_book);
			$this->db->or_like($this->column('author'), $search_book);
			$this->db->or_like($this->column('publisher'), $search_book);
			$this->db->or_like($this->column('isbn'), $search_book);
			$this->db->or_like($this->column('barcode'), $search_book);
			$this->db->group_end();
		}
		$this->db->where($this->column('library'), $library);
		$this->db->where($this->column('category'), $category_id);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$data = $this->db->get(db_prefix() . 'books')->num_rows();
		return $data;
	}

	public function total_data_kategori_laporan($search = null)
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
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data_kategori_laporan($number = 10, $offset = 0, $search = null, $role = null, $sekolah = null)
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
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$library_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$data[$key]->library_name = $library_name;
			$book_issued = $this->jumlah_pinjam($item->id);
			$book_viewed = $this->view_by_book($item->id);
			$data[$key]->pinjam = $book_issued;
			$data[$key]->baca = $book_viewed;
		}
		return $data;
	}

	public function export_laporan_kategori_buku()
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
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$library_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$data[$key]->library_name = $library_name;
			$book_issued = $this->jumlah_pinjam($item->id);
			$book_viewed = $this->view_by_book($item->id);
			$data[$key]->pinjam = $book_issued;
			$data[$key]->baca = $book_viewed;
		}
		return $data;
	}

	public function jumlah_pinjam($cat_id)
	{
		$list_book = array();
		$this->db->where($this->column('category'), $cat_id);
		$table_book = $this->db->get(db_prefix() . 'books')->result();
		foreach ($table_book as $key => $value) {
			array_push($list_book, $value->id);
		}
		if (empty($list_book)) {
			return 0;
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where_in($this->column('book'), $list_book);
		$total = $this->db->get(db_prefix() . 'issues')->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function view_by_book($cat_id)
	{
		$list_book = array();
		$this->db->where($this->column('category'), $cat_id);
		$table_book = $this->db->get(db_prefix() . 'books')->result();
		foreach ($table_book as $key => $value) {
			array_push($list_book, $value->id);
		}
		if (empty($list_book)) {
			return 0;
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('action'), 'view');
		$this->db->where_in($this->column('book'), $list_book);
		$total = $this->db->get(db_prefix() . 'ebook_action')->num_rows();
		return !empty($total) ? $total : 0;
	}


	public function api_mapel_data($user)
	{
		// $user_sekolah = $this->api_user_sekolah($user);
		// if (!$user_sekolah) {
		// 	return [];
		// }	
		$master_db = $this->load->database('master', true);
		$master_db->where('mapel_isdelete', '0');
		// $master_db->where('mapel_sekolahid', $user_sekolah->user_sekolahid);
		$master_db->order_by('mapel_nama', 'asc');
		$data = $master_db->get('master_mapel')->result();
		return $data;
	}

	public function api_kelas_data($user)
	{
		// $user_sekolah = $this->api_user_sekolah($user);
		// if (!$user_sekolah) {
		// 	return [];
		// }	
		$master_db = $this->load->database('master', true);
		$master_db->where('kelas_isdelete', '0');
		// $master_db->where('kelas_sekolahid', $user_sekolah->user_sekolahid);
		$master_db->order_by('kelas_nama', 'asc');
		$data = $master_db->get('master_kelas')->result();
		return $data;
	}
}
