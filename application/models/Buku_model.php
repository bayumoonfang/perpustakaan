<?php defined('BASEPATH') or exit('No direct script access allowed');

class Buku_model extends App_Model
{
	private $table;
	public $table_barcode;
	public $table_issue;
	public $table_prefix;
	public $db;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'books';
		$this->table_issue = db_prefix() . 'issues';
		$this->table_barcode = db_prefix() . 'barcodes';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function exists_code($code = '', $library = 0, $id = null)
	{
		$this->db->where($this->column('code'), $code);
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

	public function total_data($search = null, $library = null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $library);
			$this->db->group_end();
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->or_like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->or_like($this->column('isbn'), $search);
			$this->db->or_like($this->column('barcode'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('is_physical_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data($number = 10, $offset = 0, $search = null, $library = null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return array();
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $library);
			$this->db->group_end();
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->or_like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->or_like($this->column('isbn'), $search);
			$this->db->or_like($this->column('barcode'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('is_physical_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$category_name = '-';
			$rak_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$this->db->where($this->column('id'), $item->category);
			$catData = $this->db->get(db_prefix() . 'book_categories')->row();
			if (!empty($catData)) {
				$category_name = ucfirst($catData->category);
			};
			$this->db->where($this->column('id'), $item->rak);
			$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
			if (!empty($rakData)) {
				$rak_name = ucfirst($rakData->rack);
			};
			$data[$key]->library_name = $library_name;
			$data[$key]->category_name = $category_name;
			$data[$key]->rak_name = $rak_name;
			$book_issued = $this->book_issued($item->id, $item->library);
			$data[$key]->issued = $book_issued;
		}
		return $data;
	}

	public function exists_category($library, $category, $id = null)
	{
		$this->db->where($this->column('library'), $library);
		$this->db->where($this->column('category'), $category);
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
			'code' => $input['code'],
			'class' => $input['class'],
			'mapel' => $input['mapel'],
			'library' => $input['library'],
			'status' => $input['status'],
			'category' => $input['category'],
			'rak' =>  $input['rak'],
			'title' =>  $input['title'],
			'author' =>  $input['author'],
			'publisher' =>  $input['publisher'],
			'year' =>  $input['year'],
			'kolasi' =>  $input['kolasi'],
			'isbn' =>  $input['isbn'],
			'language' =>  $input['language'],
			'price' =>  $input['price'],
			'barcode' =>  $input['barcode'],
			'bentuk' =>  $input['bentuk'],
			'is_physical_book' =>  '1',
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

	public function get_data_book_barcode($id = 'id', $library)
	{
		$this->db->where($this->column('id'), $id);
		$this->db->where($this->column('library'), $library);
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
			'code' => $input['code'],
			'class' => $input['class'],
			'mapel' => $input['mapel'],
			'library' => $input['library'],
			'status' => $input['status'],
			'category' => $input['category'],
			'rak' =>  $input['rak'],
			'title' =>  $input['title'],
			'author' =>  $input['author'],
			'publisher' =>  $input['publisher'],
			'year' =>  $input['year'],
			'isbn' =>  $input['isbn'],
			'language' =>  $input['language'],
			'price' =>  $input['price'],
			'barcode' =>  $input['barcode'],
			'bentuk' =>  $input['bentuk'],
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

	public function update_cover($id, $url)
	{
		$data = [
			'cover' => $url,
			'updated_at' => now(),
			'updated_by' => current_user(),
		];

		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function update_ebook($id, $url)
	{
		$data = [
			'is_digital_book' => '1',
			'fileurl' => $url,
			'updated_at' => now(),
			'updated_by' => current_user(),
		];

		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		return true;
	}

	public function api_data($param)
	{
		$user_sekolah = $this->api_user_sekolah($param['user']);
		if (!$user_sekolah) {
			return array();
		}
		$number = $param['limit'];
		$offsets = ((int)$number * (int)$param['page']) - (int)$number;
		$offset = $offsets < 0 ? 0 : $offsets;

		$user_library = $this->user_library($user_sekolah->user_sekolahid);
		if (!empty($user_library)) {
			$this->db->where_in($this->column('library'), $user_library);
		} else {
			return array();
		}
		$kelas_id = $user_sekolah->kelas;
		if (!empty($param['kelas']) || $param['kelas'] == '0') {
			$kelas_id = $param['kelas'];
			$this->db->where($this->column('class'), $kelas_id);
		}
		if (!empty($param['mapel']) || $param['mapel'] == '0') {
			$mapel_id = $param['mapel'];
			$this->db->where($this->column('mapel'), $mapel_id);
		}
		// var_dump($param);die;
		if ($param['category']) {
			$category = $param['category'];
			$this->db->where($this->column('category'), $category);
		}
		if ($param['bahasa']) {
			$bahasa = $param['bahasa'];
			$this->db->where($this->column('language'), $bahasa);
		}
		if ($param['tahun1']) {
			$tahun1 = $param['tahun1'];
			$this->db->where($this->column('year') . '>=', $tahun1);
		}
		if ($param['tahun2']) {
			$tahun2 = $param['tahun2'];
			$this->db->where($this->column('year') . '<=', $tahun2);
		}

		if ($param['filter']) {
			$user_action = $this->user_action($param['user'], $param['filter']);
			if (!empty($user_action)) {
				$this->db->where_in($this->column('id'), $user_action);
			} else {
				return array();
			}
		}

		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('year'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$data[$key]->category = $this->category_by_book($item->category);
			$data[$key]->language = $this->language_by_book($item->language);
			$data[$key]->library = $this->library_by_book($item->library);
			$data[$key]->view = $this->view_by_book($item->id);
			$data[$key]->like = $this->like_by_book($item->id);
			$data[$key]->is_like = $this->like_by_user($item->id, $param['user']);
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
		$kelas_id = $user_sekolah->kelas;
		if (!empty($param['kelas']) || $param['kelas'] == '0') {
			$kelas_id = $param['kelas'];
			$this->db->where($this->column('class'), $kelas_id);
		}
		if (!empty($param['mapel']) || $param['mapel'] == '0') {
			$mapel_id = $param['mapel'];
			$this->db->where($this->column('mapel'), $mapel_id);
		}
		// var_dump($param);die;
		if ($param['category']) {
			$category = $param['category'];
			$this->db->where($this->column('category'), $category);
		}
		if ($param['bahasa']) {
			$bahasa = $param['bahasa'];
			$this->db->where($this->column('language'), $bahasa);
		}
		if ($param['tahun1']) {
			$tahun1 = $param['tahun1'];
			$this->db->where($this->column('year') . '>=', $tahun1);
		}
		if ($param['tahun2']) {
			$tahun2 = $param['tahun2'];
			$this->db->where($this->column('year') . '<=', $tahun2);
		}

		if ($param['filter']) {
			$user_action = $this->user_action($param['user'], $param['filter']);
			if (!empty($user_action)) {
				$this->db->where_in($this->column('id'), $user_action);
			} else {
				return 0;
			}
		}

		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function api_general_data($param)
	{

		$number = $param['limit'] ?? 10;
		$offsets = ((int)$number * (int)$param['page']) - (int)$number;
		$offset = $offsets < 0 ? 0 : $offsets;

		if (!empty($param['library'])) {
			$this->db->where($this->column('library'), $param['library']);
		} else {
			return array();
		}

		if ($param['category']) {
			$category = $param['category'];
			$this->db->where($this->column('category'), $category);
		}
		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		// $this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('year'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$data[$key]->category = $this->category_by_book($item->category);
			$data[$key]->language = $this->language_by_book($item->language);
			$data[$key]->library = $this->library_by_book($item->library);
			$data[$key]->view = $this->view_by_book($item->id);
			$data[$key]->like = $this->like_by_book($item->id);
			$rak_name = '-';
			$this->db->where($this->column('id'), $item->rak);
			$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
			if (!empty($rakData)) {
				$rak_name = ucfirst($rakData->rack);
			};
			$data[$key]->rak_name = $rak_name;
			$book_issued = $this->book_issued($item->id, $param['library']);
			$data[$key]->stok = intval($item->qty) - $book_issued;
		}
		return $data;
	}


	public function api_general_data_total($param)
	{
		if ($param['library']) {
			$this->db->where($this->column('library'), $param['library']);
		} else {
			return 0;
		}

		if ($param['category']) {
			$category = $param['category'];
			$this->db->where($this->column('category'), $category);
		}

		$search = $param['search'];
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		// $this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function api_data_category($param, $id)
	{
		$user_sekolah = $this->api_user_sekolah($param['user']);
		if (!$user_sekolah) {
			return array();
		}
		$number = $param['limit'];
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
			$this->db->like($this->column('code'), $search);
			$this->db->like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('category'), $id);
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('year'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$data[$key]->category = $this->category_by_book($item->category);
			$data[$key]->language = $this->language_by_book($item->language);
			$data[$key]->library = $this->library_by_book($item->library);
			$data[$key]->view = $this->view_by_book($item->id);
			$data[$key]->like = $this->like_by_book($item->id);
			$data[$key]->is_like = $this->like_by_user($item->id, $param['user']);
		}
		return $data;
	}

	public function api_data_category_total($param, $id)
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
			$this->db->like($this->column('code'), $search);
			$this->db->like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('category'), $id);
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function api_data_get_action($param, $action)
	{
		$user_sekolah = $this->api_user_sekolah($param['user']);
		if (!$user_sekolah) {
			return array();
		}
		$number = $param['limit'];
		$offsets = ((int)$number * (int)$param['page']) - (int)$number;
		$offset = $offsets < 0 ? 0 : $offsets;
		$user_action = $this->user_action($param['user'], $action);
		if (!empty($user_action)) {
			$this->db->where_in($this->column('id'), $user_action);
		} else {
			return array();
		}
		$user_library = $this->user_library($user_sekolah->user_sekolahid);
		if (!empty($user_library)) {
			$this->db->where_in($this->column('library'), $user_library);
		} else {
			return array();
		}

		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('year'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$data[$key]->category = $this->category_by_book($item->category);
			$data[$key]->language = $this->language_by_book($item->language);
			$data[$key]->library = $this->library_by_book($item->library);
			$data[$key]->view = $this->view_by_book($item->id);
			$data[$key]->like = $this->like_by_book($item->id);
			$data[$key]->is_like = $this->like_by_user($item->id, $param['user']);
		}
		return $data;
	}

	public function api_data_get_action_total($param, $action)
	{
		$user_sekolah = $this->api_user_sekolah($param['user']);
		if (!$user_sekolah) {
			return 0;
		}
		$user_action = $this->user_action($param['user'], $action);
		if (!empty($user_action)) {
			$this->db->where_in($this->column('id'), $user_action);
		} else {
			return 0;
		}
		$user_library = $this->user_library($user_sekolah->user_sekolahid);
		if (!empty($user_library)) {
			$this->db->where_in($this->column('library'), $user_library);
		} else {
			return 0;
		}
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('year'), 'desc');
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function category_by_book($id)
	{
		$this->db->where($this->column('id'), $id);
		$data = $this->db->get(db_prefix() . 'book_categories')->row();
		if (!$data) {
			return null;
		}
		$arr = array('id' => $data->id, 'name' => $data->category);
		return $arr;
	}

	public function language_by_book($id)
	{
		$this->db->where($this->column('id'), $id);
		$data = $this->db->get(db_prefix() . 'bahasa')->row();
		if (!$data) {
			return null;
		}
		$arr = array('id' => $data->id, 'name' => $data->name);
		return $arr;
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

	public function view_by_book($id)
	{
		$this->db->where($this->column('book'), $id);
		$this->db->where($this->column('action'), 'view');
		$data = $this->db->get(db_prefix() . 'ebook_action')->num_rows();
		if (!$data) {
			return 0;
		}
		return $data;
	}

	public function like_by_book($id)
	{
		$this->db->where($this->column('book'), $id);
		$this->db->where($this->column('action'), 'like');
		$data = $this->db->get(db_prefix() . 'ebook_action')->num_rows();
		if (!$data) {
			return 0;
		}
		return $data;
	}

	public function like_by_user($id, $user)
	{
		$this->db->where($this->column('book'), $id);
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('action'), 'like');
		$data = $this->db->get(db_prefix() . 'ebook_action')->num_rows();
		if (!$data) {
			return false;
		}
		return true;
	}

	public function user_action($user, $action)
	{
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('action'), $action);
		$data = $this->db->get(db_prefix() . 'ebook_action')->result();
		$res = array();
		foreach ($data as $key => $item) {
			array_push($res, $item->book);
		}

		return $res;
	}

	public function api_detail_data($id, $user)
	{
		$user_sekolah = $this->api_user_sekolah($user);
		if (!$user_sekolah) {
			return false;
		}

		$user_library = $this->user_library($user_sekolah->user_sekolahid);
		if (!empty($user_library)) {
			$this->db->where_in($this->column('library'), $user_library);
		} else {
			return false;
		}
		$this->db->where($this->column('id'), $id);
		$this->db->where($this->column('is_digital_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$data = $this->db->get($this->table)->row();
		if (!$data) {
			return false;
		}
		$data->category = $this->category_by_book($data->category);
		$data->language = $this->language_by_book($data->language);
		$data->library = $this->library_by_book($data->library);
		$data->view = $this->view_by_book($data->id);
		$data->like = $this->like_by_book($data->id);
		$data->is_like = $this->like_by_user($id, $user);
		return $data;
	}

	public function api_data_action($id, $user, $action)
	{
		$this->db->where($this->column('book'), $id);
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('action'), $action);
		$data = $this->db->get(db_prefix() . 'ebook_action')->row();
		if (!$data) {
			$data_insert['book'] = $id;
			$data_insert['user'] = $user;
			$data_insert['action'] = $action;
			$data_insert['created_at'] = now();
			$data_insert['created_by'] = $user;
			$data_insert['updated_at'] = now();
			$data_insert['updated_by'] = $user;
			$this->db->insert(db_prefix() . 'ebook_action', $data_insert);
		} else {
			if ($action == 'like') {
				$this->db->where('id', $data->id);
				$this->db->delete(db_prefix() . 'ebook_action');
			} else {
				$data_action = [
					'updated_at' => now(),
					'updated_by' => $user,
				];
				$this->db->where('id', $data->id);
				$this->db->update(db_prefix() . 'ebook_action', $data_action);
			}
		}
		return true;
	}

	public function book_search_ajax($search, $library)
	{
		$this->db->group_start();
		$this->db->like($this->column('code'), $search);
		$this->db->or_like($this->column('title'), $search);
		$this->db->or_like($this->column('author'), $search);
		$this->db->or_like($this->column('publisher'), $search);
		$this->db->or_like($this->column('isbn'), $search);
		$this->db->or_like($this->column('barcode'), $search);
		$this->db->group_end();
		$this->db->where($this->column('library'), $library);
		$this->db->where($this->column('is_physical_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('title'), 'asc');
		$data = $this->db->get($this->table, 10, 0)->result();
		foreach ($data as $key => $item) {
			$category_name = '-';
			$rak_name = '-';
			$this->db->where($this->column('id'), $item->category);
			$catData = $this->db->get(db_prefix() . 'book_categories')->row();
			if (!empty($catData)) {
				$category_name = ucfirst($catData->category);
			};
			$this->db->where($this->column('id'), $item->rak);
			$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
			if (!empty($rakData)) {
				$rak_name = ucfirst($rakData->rack);
			};
			$book_issued = $this->book_issued($item->id, $library);
			$data[$key]->category_name = $category_name;
			$data[$key]->rak_name = $rak_name;
			$data[$key]->stok = intval($item->qty) - $book_issued;
		}
		return $data;
	}

	public function book_issued($book, $library)
	{
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), 'pinjam');
		$this->db->where($this->column('library'), $library);
		$this->db->where($this->column('book'), $book);
		$total = $this->db->get($this->table_issue)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data_per_library($id, $phisical_book = 'true')
	{
		$this->db->where('id', $id);
		$this->db->where('status', '1');
		$this->db->where('deleted_at', null);
		$library = $this->db->get(db_prefix() . 'libraries')->row();
		if (!$library) {
			return array();
		};
		if ($phisical_book) {
			$this->db->where($this->column('is_physical_book'), '1');
		}
		$this->db->where($this->column('library'), $id);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), '1');
		$this->db->order_by($this->column('title'), 'asc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$book_issued = $this->book_issued($item->id, $id);
			$data[$key]->qty = intval($item->qty) - $book_issued;
		}
		return $data;
	}

	public function total_buku_dashboard($start_date, $end_date, $type, $all = true)
	{
		$library = $this->input->get('library', true);
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		if (!$all) {
			$this->db->where($this->column('created_at') . '>=', $start_date);
			$this->db->where($this->column('created_at') . '<=', $end_date);
		}
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if ($type == 'physics') {
			$this->db->where($this->column('is_physical_book'), '1');
		}
		if ($type == 'ebook') {
			$this->db->where($this->column('is_digital_book'), '1');
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function total_buku_judul($qty = false, $library = null)
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		$library = $this->input->get('library', true);
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->where($this->column('is_physical_book'), '1');
		if (!$qty) {
			$this->db->where($this->column('qty') . '<', 1);
		} else {
			$this->db->where($this->column('qty') . '>', 0);
		}

		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function total_buku_koleksi()
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		$library = $this->input->get('library', true);
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->select('bentuk');
		$this->db->where($this->column('bentuk') . '!=', null);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->group_by('bentuk');
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function history_buku($number = 10, $offset = 0, $search = null, $sekolah = null)
	{
		$lib_id = $this->input->get('library', true);
		// if (!is_admin()) {
		// 	$user_library = $this->user_library();
		// 	if (!empty($user_library)) {
		// 		$this->db->where_in($this->column('library'), $user_library);
		// 	} else {
		// 		return array();
		// 	}
		// }
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			// $this->user_libs();
			if (!is_admin()) {
				$user_library = $this->user_library();
				if (!empty($user_library)) {
					$this->db->where_in($this->column('library'), $user_library);
				} else {
					return array();
				}
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->or_like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->or_like($this->column('isbn'), $search);
			$this->db->or_like($this->column('barcode'), $search);
			$this->db->group_end();
		}
		// $this->db->where($this->column('is_physical_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$category_name = '-';
			$rak_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$this->db->where($this->column('id'), $item->category);
			$catData = $this->db->get(db_prefix() . 'book_categories')->row();
			if (!empty($catData)) {
				$category_name = ucfirst($catData->category);
			};
			$this->db->where($this->column('id'), $item->rak);
			$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
			if (!empty($rakData)) {
				$rak_name = ucfirst($rakData->rack);
			};
			$data[$key]->library_name = $library_name;
			$data[$key]->category_name = $category_name;
			$data[$key]->rak_name = $rak_name;
			$book_issued = $this->jumlah_pinjam($item->id);
			$book_viewed = $this->view_by_book($item->id);
			$data[$key]->pinjam = $book_issued;
			$data[$key]->baca = $book_viewed;
		}
		return $data;
	}

	public function total_history_buku($search = null, $sekolah = null)
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
			$this->db->like($this->column('code'), $search);
			$this->db->or_like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->or_like($this->column('isbn'), $search);
			$this->db->or_like($this->column('barcode'), $search);
			$this->db->group_end();
		}
		// $this->db->where($this->column('is_physical_book'), '1');
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function jumlah_pinjam($book)
	{
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('book'), $book);
		$total = $this->db->get($this->table_issue)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function all_data_history()
	{
		// if (!is_admin()) {
		// 	$user_library = $this->user_library();
		// 	if (!empty($user_library)) {
		// 		$this->db->where_in($this->column('library'), $user_library);
		// 	} else {
		// 		return array();
		// 	}
		// }
		$lib_id = $this->input->get('library_excel', true);
		// if (!is_admin()) {
		// 	$user_library = $this->user_library();
		// 	if (!empty($user_library)) {
		// 		$this->db->where($this->column('library'), $lib_id);
		// 		// $this->db->where_in($this->column('library'), $user_library);
		// 	} else {
		// 		// return array();
		// 		$this->db->where($this->column('library'), $lib_id);
		// 	}
		// }
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			// $this->user_libs();
			if (!is_admin()) {
				$user_library = $this->user_library();
				if (!empty($user_library)) {
					$this->db->where_in($this->column('library'), $user_library);
				} else {
					return array();
				}
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->or_like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->or_like($this->column('isbn'), $search);
			$this->db->or_like($this->column('barcode'), $search);
			$this->db->group_end();
		}

		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$category_name = '-';
			$rak_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$this->db->where($this->column('id'), $item->category);
			$catData = $this->db->get(db_prefix() . 'book_categories')->row();
			if (!empty($catData)) {
				$category_name = ucfirst($catData->category);
			};
			$this->db->where($this->column('id'), $item->rak);
			$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
			if (!empty($rakData)) {
				$rak_name = ucfirst($rakData->rack);
			};
			$data[$key]->library_name = $library_name;
			$data[$key]->category_name = $category_name;
			$data[$key]->rak_name = $rak_name;
			$book_issued = $this->jumlah_pinjam($item->id);
			$book_viewed = $this->view_by_book($item->id);
			$data[$key]->pinjam = $book_issued;
			$data[$key]->baca = $book_viewed;
		}
		return $data;
	}

	public function get_data_book_barcode_list($id, $library)
	{
		$this->db->where($this->column('book'), $id);
		$this->db->where($this->column('library'), $library);
		$data = $this->db->get($this->table_barcode)->result();
		foreach ($data as $key => $item) {
			$data[$key]->selected = false;
		}
		return $data;
	}

	public function generate_book_barcode($input)
	{
		$this->db->where($this->column('id'), $input['book']);
		$books = $this->db->get($this->table)->row();
		if (empty($books)) {
			return false;
		}
		$qtys = $books->qty ?? 0;
		$this->db->where($this->column('library'), $input['library']);
		$this->db->where($this->column('book'), $input['book']);
		$book_barcodes = $this->db->get($this->table_barcode)->num_rows();
		$no = $book_barcodes + 1;
		$qty = $qtys > $book_barcodes ? $qtys - $book_barcodes : 0;
		for ($i = 0; $i < $qty; $i++) {
			$barcode = $books->code . sprintf("%03s", $no);
			$data = [
				'library' => $input['library'],
				'book' => $input['book'],
				'barcode' => $barcode,
				'created_at' => now(),
				'created_by' => current_user(),
				'updated_at' => now(),
				'updated_by' => current_user(),
			];
			$this->db->insert($this->table_barcode, $data);
			$no++;
		}
		return true;
	}

	public function api_total_action($user, $action = 'view')
	{
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('action'), $action);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get(db_prefix() . 'ebook_action')->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function api_total_issue($user, $status = 'pinjam')
	{
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('status'), $status);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get(db_prefix() . 'issues')->num_rows();
		return !empty($total) ? $total : 0;
	}

	//Ekspor impor buku
	public function export_excel_master_buku()
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return array();
			}
		}
		$get_select_bentuk = $this->input->get('select_bentuk', true);
		if (!empty($get_select_bentuk)) {
			$where = array();
			foreach ($get_select_bentuk as $line) {
				array_push($where, $line);
			}
			$this->db->where_in('bentuk', $where);
		}
		$get_select_kategori = $this->input->get('select_kategori', true);
		if (!empty($get_select_kategori)) {
			$where = array();
			foreach ($get_select_kategori as $line) {
				array_push($where, $line);
			}
			$this->db->where_in('category', $where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table)->result();
		// foreach ($data as $key => $item) {
		// 	$library_name = '-';
		// 	$library_name = '-';
		// 	$this->db->where($this->column('id'), $item->library);
		// 	$libData = $this->db->get(db_prefix() . 'libraries')->row();
		// 	if (!empty($libData)) {
		// 		$library_name = ucfirst($libData->library);
		// 	};
		// 	$data[$key]->library_name = $library_name;
		// 	$book_issued = $this->jumlah_pinjam($item->id);
		// 	$book_viewed = $this->view_by_book($item->id);
		// 	$data[$key]->pinjam = $book_issued;
		// 	$data[$key]->baca = $book_viewed;
		// }
		return $data;
	}

	//Template impor buku
	public function template_excel_master_buku()
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return array();
			}
		}
		$get_select_bentuk = $this->input->get('select_bentuk', true);
		if (!empty($get_select_bentuk)) {
			$where = array();
			foreach ($get_select_bentuk as $line) {
				array_push($where, $line);
			}
			$this->db->where_in('bentuk', $where);
		}
		$get_select_kategori = $this->input->get('select_kategori', true);
		if (!empty($get_select_kategori)) {
			$where = array();
			foreach ($get_select_kategori as $line) {
				array_push($where, $line);
			}
			$this->db->where_in('category', $where);
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('category'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$this->db->limit(1);
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$library_name = '-';
			$library_name = '-';
			$this->db->where($this->column('id'), $item->library);
			$libData = $this->db->get(db_prefix() . 'libraries')->row();
			if (!empty($libData)) {
				$library_name = ucfirst($libData->library);
			};
			$this->db->where($this->column('id'), $item->language);
			$langData = $this->db->get(db_prefix() . 'bahasa')->row();
			if (!empty($langData)) {
				$language_name = ucfirst($langData->name);
			};
			$data[$key]->library_name = $library_name;
			$data[$key]->bahasa_name = $language_name;
		}
		return $data;
	}

	public function header_excel_buku()
	{
		$get = $this->db->get($this->table)->result_array();
		return $get;
	}
}
