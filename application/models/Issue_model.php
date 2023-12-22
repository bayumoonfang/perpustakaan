<?php defined('BASEPATH') or exit('No direct script access allowed');

class Issue_model extends App_Model
{
	private $table;
	public $table_prefix;
	public $table_library;
	public $table_tamu;
	public $db;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'issues';
		$this->table_library = db_prefix() . 'libraries';
		$this->table_tamu = db_prefix() . 'tamu';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
		$this->load->helper('date');
	}

	public function total_data($search = null,$status='pinjam')
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			}else{
				return 0;
			}
		}
		if (!empty($search)) {
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if(!empty($search_book) || !empty($search_user)){
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			}elseif(empty($search_book) && empty($search_user)){
				return 0;
			}
		}
		$tgl_kembali = $this->input->get('due_date', true);
		if ($tgl_kembali) {
			$this->db->group_start();
			$this->db->where($this->column('expired_date'), $tgl_kembali . ' 23:59:59');
			$this->db->group_end();
		}
		$this->db->where($this->column('status'), $status);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data($number = 10, $offset = 0, $search = null, $status = 'pinjam')
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			}else{
				return array();
			}
		}
		if (!empty($search)) {
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return [];
			}
		}
		$tgl_kembali=$this->input->get('due_date', true);
		if($tgl_kembali){
			$this->db->group_start();
			$this->db->where($this->column('expired_date'), $tgl_kembali.' 23:59:59');
			$this->db->group_end();
		}
		$this->db->where($this->column('status'), $status);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$book_title='-';
			$book_code='-';
			$user_no='-';
			$user_nama='-';
			$user_alamat='-';
			$book_data= $this->book_data($item->book, $item->library);
			$user_data= $this->user_data($item->user);
			if($book_data){
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if($user_data){
				$user_no = $user_data->user_uid;
				$user_alamat = $user_data->user_alamat;
				$user_nama = $user_data->user_nama;
			}
			$data[$key]->book_title= $book_title;
			$data[$key]->book_code= $book_code;
			$data[$key]->user_no= $user_no;
			$data[$key]->user_nama= $user_nama;
			$data[$key]->user_alamat= $user_alamat;
			$data[$key]->expired = strtotime($item->expired_date) <= strtotime(date('Y-m-d 00:00:00')) ? true : false;
			$data[$key]->tgl_pinjam = date_format(date_create($item->issue_date), 'Y-m-d');
			$data[$key]->tgl_pinjam = date_format(date_create($item->issue_date), 'Y-m-d');
			$data[$key]->issue_date = date_format(date_create($item->issue_date), 'd F Y');
			$data[$key]->return_date = date_format(date_create($item->return_date), 'd F Y');
			$data[$key]->tgl_kembali = date_format(date_create($item->expired_date), 'Y-m-d');
			$data[$key]->tgl_kembali_time = $item->expired_date;
			$data[$key]->expired_date = date_format(date_create($item->expired_date), 'd F Y');
		}
		return $data;
	}

	private function search_user($search){
		$db_user=$this->load->database('master', true);
		$user_prefix='user_';
		if (!is_admin()) {
			$sekolah_id = current_user('user_sekolahid');
			$db_user->where($user_prefix.'sekolahid', $sekolah_id);
		}
		if (!empty($search)) {
			$db_user->group_start();
			$db_user->like($user_prefix . 'email', $search);
			$db_user->or_like($user_prefix . 'uid', $search);
			$db_user->or_like($user_prefix . 'no', $search);
			$db_user->or_like($user_prefix . 'nama', $search);
			$db_user->or_like($user_prefix . 'panggilan', $search);
			$db_user->or_like($user_prefix . 'ponsel', $search);
			$db_user->group_end();
		}
		$db_user->where($user_prefix . 'isdelete', '0');
		$db_user->where($user_prefix . 'status', '1');
		$data = $db_user->get('master_user')->result();
		$result = array();
		foreach ($data as $key => $values) {
			array_push($result, $values->user_id);
		}
		
		return $result;
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
		$book_detail=null;
		$user_detail=null;
		$book_data=$this->book_data($data->book, $data->library);
		if($book_data){
			$book_detail=$book_data;
		}
		$user_data = $this->user_data($data->user);
		if ($user_data) {
			$user_detail = $user_data;
		}
		if(!$user_data || !$book_data){
			return false;
		}
		$data->book=$book_detail;
		$data->user= $user_detail;
		return $data;
	}

	private function search_book($search){
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			}else{
				return array();
			}
		}
		if (!empty($search)) {
			$this->db->group_start();
			$this->db->like($this->column('code'), $search);
			$this->db->or_like($this->column('title'), $search);
			$this->db->or_like($this->column('author'), $search);
			$this->db->or_like($this->column('publisher'), $search);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get(db_prefix() . 'books')->result();
		$result = array();
		foreach ($data as $key => $values) {
			array_push($result, $values->id);
		}
		
		return $result;
	}

	public function book_data($book_id,$library_id){
		$this->db->where('id', $book_id);
		$this->db->where('library', $library_id);
		$data=$this->db->get(db_prefix() . 'books')->row();
		if(empty($data)){
			return false;
		}
		$category_name = '-';
		$rak_name = '-';
		$this->db->where($this->column('id'), $data->category);
		$catData = $this->db->get(db_prefix() . 'book_categories')->row();
		if (!empty($catData)) {
			$category_name = ucfirst($catData->category);
		};
		$this->db->where($this->column('id'), $data->rak);
		$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
		if (!empty($rakData)) {
			$rak_name = ucfirst($rakData->rack);
		};
		$data->category_name = $category_name;
		$data->rak_name = $rak_name;
		return $data;
	}

	public function get_user_history($user,$library){
		if(!is_array($library)){
			return array();
		}
		$arrLib=array();
		foreach ($library as $value) {
			array_push($arrLib,$value->id);
		}
		if(empty($arrLib)){
			return array();
		}
		$this->db->where_in($this->column('library'), $arrLib);
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), 'pinjam');
		$this->db->order_by($this->column('expired_date'), 'desc');
		$data = $this->db->get(db_prefix() . 'issues')->result();
		foreach ($data as $key => $value) {
			$book=null;
			$library_name='-';
			$expired=false;
			$data_book=$this->book_data($value->book,$value->library);
			if($data_book){
				$book= $data_book;
			}
			$this->db->where($this->column('id'),$value->library);
			$data_library=$this->db->get($this->table_library)->row();
			if($data_library){
				$library_name=ucfirst($data_library->library);
			}
			$data[$key]->library_name = $library_name;
			$data[$key]->book = $book;
			$data[$key]->expired = strtotime($value->expired_date) <= strtotime(date('Y-m-d 00:00:00')) ? true:false;
			$data[$key]->issue_date = date_format(date_create($value->issue_date),'d F Y');
			$data[$key]->return_date = date_format(date_create($value->return_date),'d F Y');
			$data[$key]->expired_date = date_format(date_create($value->expired_date),'d F Y');
		}
		return $data;
	}

	public function get_count_user_history_by_library($user,$library){
		
		$this->db->where_in($this->column('library'), $library);
		$this->db->where($this->column('user'), $user);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('status'), 'pinjam');
		$data = $this->db->get(db_prefix() . 'issues')->num_rows();
		
		return !empty($data) ? $data : 0;
	}

	public function user_data($user_id){
		$db_user=$this->load->database('master', true);
		$db_user->where('user_id', $user_id);
		$data=$db_user->get('master_user')->row();
		if(empty($data)){
			return false;
		}
		return $data;
	}

	public function store($data){
		$setting=$data['setting'];
		$return_date= date('Y-m-d 23:59:59', strtotime(date('Y-m-d') . ' + '. $setting->hari_pinjam.' days'));
		if(isset($data['book']) && is_array($data['book'])){
			foreach ($data['book'] as $value) {
				$data_issue = array(
					'library' => $data['library'],
					'user' => $data['user'],
					'book' => $value,
					'status' => 'pinjam',
					'issue_date' => date('Y-m-d'),
					'expired_date' => $return_date,
					'created_at' => now(),
					'created_by' => current_user(),
					'updated_at' => now(),
					'updated_by' => current_user(),
				);
				$this->db->insert($this->table, $data_issue);
			}
		}
	}

	public function proses_kembali($data){
		$issue_id=$data['issue'];
		$data_issue = array(
			'return_date' => date('Y-m-d'),
			'status' => $data['status'],
			'notes' => isset($data['notes']) && $data['notes']!='' ? $data['notes']:null,
			'updated_at' => now(),
			'updated_by' => current_user(),
		);
		$this->db->where('id', $issue_id);
		$this->db->update($this->table, $data_issue);
		return true;
	}

	public function update_duration($id,$expired){
		$return_date = date('Y-m-d 23:59:59', strtotime($expired . ' + 0 days'));
		$data_issue = array(
			'expired_date' => $return_date,
			'updated_at' => now(),
			'updated_by' => current_user(),
		);
		$this->db->where('id', $id);
		$this->db->update($this->table, $data_issue);
		return true;
	}

	public function total_pinjam_dashboard($start_date,$end_date, $all = true){
		$library = $this->input->get('library', true);
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		if(!$all){
			$this->db->where($this->column('issue_date') . '>=', $start_date);
			$this->db->where($this->column('issue_date') . '<=', $end_date);
		}
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->num_rows();
		return !empty($data) ? $data : 0;
	}

	public function data_pinjam_by_book($book,$number = 10, $offset = 0, $search = null, $status = 'pinjam')
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
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return [];
			}
		}
		// $this->db->where($this->column('status'), $status);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('book'), $book);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$user_no = '-';
			$user_nama = '-';
			$user_alamat = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$user_data = $this->user_data($item->user);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($user_data) {
				$user_no = $user_data->user_uid;
				$user_alamat = $user_data->user_alamat;
				$user_nama = $user_data->user_nama;
			}
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->user_no = $user_no;
			$data[$key]->user_nama = $user_nama;
			$data[$key]->user_alamat = $user_alamat;
			// $data[$key]->expired = strtotime($item->expired_date) <= strtotime(date('Y-m-d 00:00:00')) ? true : false;
			$data[$key]->issue_date = date_format(date_create($item->issue_date), 'd F Y');
			$data[$key]->return_date = date_format(date_create($item->return_date), 'd F Y');
			$data[$key]->expired_date = date_format(date_create($item->expired_date), 'd F Y');
		}
		return $data;
	}

	public function total_data_pinjam_by_book($book,$search = null)
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
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return 0;
			}
		}
		// $this->db->where($this->column('status'), $status);
		$this->db->where($this->column('book'), $book);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function all_data_pinjam_history($book,$input)
	{
		$lib_id = $input['library'];
		$start = $input['start'];
		$end = $input['end'];

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('issue_date') . '<=', $end);
			$this->db->where($this->column('issue_date') . '>=', $start);
			$this->db->group_end();
		}
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			$this->user_libs();
		}
		if (!empty($search)) {
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return [];
			}
		}
		
		$this->db->where($this->column('deleted_at'), null);
		$this->db->where($this->column('book'), $book);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$user_no = '-';
			$user_nama = '-';
			$user_alamat = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$user_data = $this->user_data($item->user);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($user_data) {
				$user_no = $user_data->user_uid;
				$user_alamat = $user_data->user_alamat;
				$user_nama = $user_data->user_nama;
			}
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->user_no = $user_no;
			$data[$key]->user_nama = $user_nama;
			$data[$key]->user_alamat = $user_alamat;
			$data[$key]->issue_date = date_format(date_create($item->issue_date), 'd F Y');
			$data[$key]->return_date = date_format(date_create($item->return_date), 'd F Y');
			$data[$key]->expired_date = date_format(date_create($item->expired_date), 'd F Y');
		}
		return $data;
	}

	public function all_data_pinjam_buku($input)
	{
		$lib_id = $input['library'];
		$start = $input['start'];
		$end = $input['end'];

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('issue_date') . '<=', $end);
			$this->db->where($this->column('issue_date') . '>=', $start);
			$this->db->group_end();
		}
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			$this->user_libs();
		}
		if (!empty($search)) {
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return [];
			}
		}
		
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$user_no = '-';
			$user_nama = '-';
			$user_alamat = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$user_data = $this->user_data($item->user);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($user_data) {
				$user_no = $user_data->user_uid;
				$user_alamat = $user_data->user_alamat;
				$user_nama = $user_data->user_nama;
			}
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->user_no = $user_no;
			$data[$key]->user_nama = $user_nama;
			$data[$key]->user_alamat = $user_alamat;
			$data[$key]->issue_date = date_format(date_create($item->issue_date), 'd F Y');
			$data[$key]->return_date = date_format(date_create($item->return_date), 'd F Y');
			$data[$key]->expired_date = date_format(date_create($item->expired_date), 'd F Y');
		}
		return $data;
	}

	public function data_pinjam($number = 10, $offset = 0, $search = null, $status = 'pinjam')
	{
		$lib_id = $this->input->get('library', true);
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('issue_date') . '<=', $end);
			$this->db->where($this->column('issue_date') . '>=', $start);
			$this->db->group_end();
		}
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			$this->user_libs();
		}
		if (!empty($search)) {
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return [];
			}
		}
		// $this->db->where($this->column('status'), $status);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$user_no = '-';
			$user_nama = '-';
			$user_alamat = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$user_data = $this->user_data($item->user);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($user_data) {
				$user_no = $user_data->user_uid;
				$user_alamat = $user_data->user_alamat;
				$user_nama = $user_data->user_nama;
			}
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->user_no = $user_no;
			$data[$key]->user_nama = $user_nama;
			$data[$key]->user_alamat = $user_alamat;
			// $data[$key]->expired = strtotime($item->expired_date) <= strtotime(date('Y-m-d 00:00:00')) ? true : false;
			$data[$key]->issue_date = date_format(date_create($item->issue_date), 'd F Y');
			$data[$key]->return_date = date_format(date_create($item->return_date), 'd F Y');
			$data[$key]->expired_date = date_format(date_create($item->expired_date), 'd F Y');
		}
		return $data;
	}

	public function user_libs()
	{
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return array();
			}
		}
	}

	public function total_data_pinjam($search = null)
	{
		$lib_id = $this->input->get('library', true);
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('issue_date') . '<=', $end);
			$this->db->where($this->column('issue_date') . '>=', $start);
			$this->db->group_end();
		}
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			$this->user_libs();
		}
		if (!empty($search)) {
			$search_book = $this->search_book($search);
			$search_user = $this->search_user($search);
			if (!empty($search_book) || !empty($search_user)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				if (!empty($search_user)) {
					$this->db->or_where_in($this->column('user'), $search_user);
				}
				$this->db->group_end();
			} elseif (empty($search_book) && empty($search_user)) {
				return 0;
			}
		}
		// $this->db->where($this->column('status'), $status);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function total_current_issue(){
		$library = $this->input->get('library', true);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->where($this->column('status'), 'pinjam');
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function total_current_overdue(){
		$library = $this->input->get('library', true);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->where($this->column('status'), 'pinjam');
		$this->db->where($this->column('deleted_at'), null);
		$total=0;
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $value) {
			if(strtotime($value->expired_date) <= strtotime(date('Y-m-d 00:00:00'))){
				$total++;
			}
		}
		return !empty($total) ? $total : 0;
	}

	public function total_member_issue(){
		$library = $this->input->get('library', true);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->select($this->column('user'));
		$this->db->where($this->column('deleted_at'), null);
		$this->db->group_by($this->column('user'));
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function total_member_not_issue(){
		$library = $this->input->get('library', true);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->select($this->column('user'));
		$this->db->where($this->column('deleted_at'), null);
		$this->db->group_by($this->column('user'));
		$data = $this->db->get($this->table)->result();
		$list_user=array();
		foreach ($data as $key => $item) {
			array_push($list_user,$item->user);
		}
		$db_master = $this->load->database('master', true);
		$user_table= db_master_prefix() . 'master_user';
		$user_prefix = 'user_';
		if (!is_admin()) {
			$db_master->where($user_prefix.'sekolahid', current_user('user_sekolahid'));
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('id'),$library);
			$data_lib_selected = $this->db->get($this->table_library)->row();
			if(!empty($data_lib_selected)){
				$db_master->where($user_prefix . 'sekolahid', $data_lib_selected->school);
			}
		}
		if(!empty($list_user)){
			$db_master->where_not_in($user_prefix . 'id', $list_user);
		}
		$db_master->where($user_prefix . 'roleid !=', '7');
		$db_master->where($user_prefix . 'isdelete !=', '1');
		$db_master->where($user_prefix . 'status', '1');
		$total = $db_master->get($user_table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function top_member(){
		$library = $this->input->get('library', true);
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->select('user,count(user) as total');

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('issue_date') . '<=', $end);
			$this->db->where($this->column('issue_date') . '>=', $start);
			$this->db->group_end();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->group_by($this->column('user'));
		$this->db->order_by('total', 'desc');
		$data = $this->db->get($this->table, 10)->result();
		foreach ($data as $key => $item) {
			$data[$key]->detail = $this->book_top_member($item->user);
		}
		return $data;
	}

	public function book_top_member($user){
		$db_master = $this->load->database('master', true);
		$user_table = db_master_prefix() . 'master_user';
		$user_prefix = 'user_';
		$db_master->where($user_prefix . 'id', $user);
		$data = $db_master->get($user_table)->row();
			$data->detail_kelas=$this->member_kelas($data->user_kelasdetailid);
		return $data;
	}

	public function member_kelas($kelas){
		$db_master = $this->load->database('master', true);
		$user_table = db_master_prefix() . 'master_kelas_detail';
		$user_prefix = 'kelasdetail_';
		$db_master->where($user_prefix . 'id', $kelas);
		$data = $db_master->get($user_table)->row();
		return $data ?? null;
	}

	public function top_book(){
		$library = $this->input->get('library', true);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		$this->db->select('book,count(book) as total');
		$this->db->where($this->column('deleted_at'), null);
		$this->db->group_by($this->column('book'));
		$this->db->order_by('total','desc');
		$data=$this->db->get($this->table,10)->result();
		foreach ($data as $key => $item) {
			$data[$key]->detail=$this->book_top_data($item->book);
		}
		return $data;
	}

	public function book_top_data($book_id){
		$this->db->where('id', $book_id);
		$data=$this->db->get(db_prefix() . 'books')->row();
		if(empty($data)){
			return false;
		}
		$category_name = '-';
		$rak_name = '-';
		$this->db->where($this->column('id'), $data->category);
		$catData = $this->db->get(db_prefix() . 'book_categories')->row();
		if (!empty($catData)) {
			$category_name = ucfirst($catData->category);
		};
		$this->db->where($this->column('id'), $data->rak);
		$rakData = $this->db->get(db_prefix() . 'bookselfs')->row();
		if (!empty($rakData)) {
			$rak_name = ucfirst($rakData->rack);
		};
		$data->category_name = $category_name;
		$data->rak_name = $rak_name;
		return $data;
	}

	public function statistic_dashboard(){
		$library = $this->input->get('library', true);
		$year = $this->input->get('year', true);
		$peminjaman=array();
		$pengunjung=array();
		for ($month=1; $month < 13; $month++) {
			if (!is_admin()) {
				$user_library = $this->user_library();
				if (!empty($user_library)) {
					$this->db->where_in($this->column('library'), $user_library);
				} else {
					return 0;
				}
			}
			if (is_admin() && !empty($library)) {
				$this->db->where($this->column('library'), $library);
			}
			if(!empty($year)){
				$this->db->where('year(issue_date)', $year);
			}
			$this->db->where('month(issue_date)', $month);
			$this->db->where($this->column('deleted_at'), null);
			$total = $this->db->get($this->table)->num_rows();
			array_push($peminjaman, !empty($total) ? $total : 0);

			// get pengunjung
			if (!is_admin()) {
				$user_library = $this->user_library();
				if (!empty($user_library)) {
					$this->db->where_in($this->column('library'), $user_library);
				} else {
					return 0;
				}
			}
			if (is_admin() && !empty($library)) {
				$this->db->where($this->column('library'), $library);
			}
			if (!empty($year)) {
				$this->db->where('year(date)', $year);
			}
			$this->db->where('month(date)', $month);
			$this->db->where($this->column('deleted_at'), null);
			$total_tamu = $this->db->get($this->table_tamu)->num_rows();
			array_push($pengunjung, !empty($total_tamu) ? $total_tamu : 0);

		}
		$data['peminjaman']=$peminjaman;
		$data['pengunjung']=$pengunjung;
		return $data;
	}

	public function data_pengunjung(){
		$library = $this->input->get('library', true);
		$year = $this->input->get('year', true);
		$db_master = $this->load->database('master', true);
		$role_table = db_master_prefix() . 'master_role';
		$role_prefix = 'role_';
		$user_table = db_master_prefix() . 'master_user';
		$user_prefix = 'user_';
		$db_master->where_not_in($role_prefix.'id',['1','7']);
		$db_master->order_by($role_prefix.'id','ASC');
		$list_role=$db_master->get($role_table)->result();
		foreach ($list_role as $key => $item) {
			$arr_user_list=array();
			if (!is_admin()) {
				$db_master->where($user_prefix . 'sekolahid', current_user('user_sekolahid'));
			}
			if (is_admin() && !empty($library)) {
				$this->db->where($this->column('id'), $library);
				$data_lib_selected = $this->db->get($this->table_library)->row();
				if (!empty($data_lib_selected)) {
					$db_master->where($user_prefix . 'sekolahid', $data_lib_selected->school);
				}
			}
			$db_master->where($user_prefix . 'roleid', $item->role_id);
			$user_lists = $db_master->get($user_table)->result();
			foreach ($user_lists as $key_user => $user_item) {
				array_push($arr_user_list, $user_item->user_id);
			}
			
			for ($month = 1; $month < 13; $month++) {
				if(empty($arr_user_list)){
					$list_role[$key]->$month = 0;
				}else{
					// get pengunjung
					if (!is_admin()) {
						$user_library = $this->user_library();
						if (!empty($user_library)) {
							$this->db->where_in($this->column('library'), $user_library);
						} else {
							return 0;
						}
					}
					if (is_admin() && !empty($library)) {
						$this->db->where($this->column('library'), $library);
					}
					if (!empty($year)) {
						$this->db->where('year(date)', $year);
					}
					$this->db->group_start();
					$this->db->where_in('user', $arr_user_list);
					$this->db->group_end();
					$this->db->where('month(date)', $month);
					$this->db->where($this->column('deleted_at'), null);
					$total_tamu = $this->db->get($this->table_tamu)->num_rows();
					$totalss_tamu = !empty($total_tamu) ? $total_tamu : 0;
					$list_role[$key]->$month = $totalss_tamu;
				}
				
			}
		}
		$data_guest=array();
		$data_guest['role_id']='xx';
		$data_guest['role_name']= 'Non-Member';
		for ($month = 1; $month < 13; $month++) {
			
			// get pengunjung
			if (!is_admin()) {
				$user_library = $this->user_library();
				if (!empty($user_library)) {
					$this->db->where_in($this->column('library'), $user_library);
					
				} else {
					return 0;
				}
			}
			if (is_admin() && !empty($library)) {
				$this->db->where($this->column('library'), $library);
			}
			if (!empty($year)) {
				$this->db->where('year(date)', $year);
			}
			$this->db->where('is_guest', '1');
			$this->db->where('month(date)', $month);
			$this->db->where($this->column('deleted_at'), null);
			$total_tamus = $this->db->get($this->table_tamu)->num_rows();
			$totalsss_tamu = !empty($total_tamus) ? $total_tamus : 0;
			$data_guest[$month] = $totalsss_tamu;
		}
		$data['internal']=$list_role;
		$data['external']=$data_guest;
		return $data;
	}
}
