<?php defined('BASEPATH') or exit('No direct script access allowed');

class Transaction_model extends App_Model
{
	private $table;
	public $table_barcode;
	public $table_issue;
	public $table_prefix;
	public $table_book;
	public $db;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'transactions';
		$this->table_issue = db_prefix() . 'issues';
		$this->table_book = db_prefix() . 'books';
		$this->table_barcode = db_prefix() . 'barcodes';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function total_data($type='in',$search = null,$library=null){
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
			$search_book = $this->search_book($search);
			if (!empty($search_book)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				$this->db->group_end();
			} elseif (empty($search_book)) {
				return [];
			}
		}
		$this->db->where($this->column('type'), $type);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total:0;
	}

	public function data($type='in',$number = 10, $offset = 0, $search = null,$library=null){
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
			$search_book = $this->search_book($search);
			if (!empty($search_book)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				$this->db->group_end();
			} elseif (empty($search_book)) {
				return [];
			}
		}
		$this->db->where($this->column('type'), $type);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$library_name = '-';
			$category_name = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$library_data = $this->library_data($item->library);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($library_data) {
				$library_name = $library_data->library;
			}
			if($type=='in'){
				$jenis_penambahan=$this->jenis_penambahan($item->category);
				if($jenis_penambahan){
					$category_name=ucfirst($jenis_penambahan->title);
				}
			}elseif($type=='out'){
				$jenis_pengurangan=$this->jenis_pengurangan($item->category);
				if($jenis_pengurangan){
					$category_name=ucfirst($jenis_pengurangan->title);
				}
			}

			$data[$key]->date = date_format(date_create($item->date), 'd F Y');
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->library_name = $library_name;
			$data[$key]->category_name = $category_name;
		}
		return $data;
	}

	public function book_data($book_id, $library_id)
	{
		$this->db->where('id', $book_id);
		$this->db->where('library', $library_id);
		$data = $this->db->get(db_prefix() . 'books')->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}

	public function get_data($type='in',$value=null,$column='id'){
		$this->db->where($column, $value);
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return false;
			}
		}
		$this->db->where($this->column('type'), $type);
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}

	public function library_data($library_id)
	{
		$this->db->where('id', $library_id);
		$data = $this->db->get(db_prefix() . 'libraries')->row();
		if (empty($data)) {
			return false;
		}
		return $data;
	}

	private function search_book($search)
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

	public function add($type='in'){
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $input['library'],
			'book' => $input['book'],
			'category' => $input['category'],
			'qty' => $input['qty'],
			'notes' => isset($input['notes']) && $input['notes']!='' ? $input['notes']:null,
			'reff' => isset($input['reff']) ? $input['reff']:null,
			'type' => $type,
			'date' =>  $input['date'],
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		$this->update_book_qty($input['book'], $input['qty'], $type);
		return $this->db->insert_id();
	}

	public function proses_kembali($data){
		$data = [
			'library' => $data['library'],
			'book' => $data['book'],
			'category' => $data['category'],
			'qty' => 1,
			'notes' => isset($data['notes']) && $data['notes'] != '' ? $data['notes'] : null,
			'reff' => isset($data['issue']) ? $data['issue'] : null,
			'type' => 'out',
			'date' => date('Y-m-d'),
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		$this->update_book_qty($data['book'], $data['qty'], 'out');
		return $this->db->insert_id();
	}

	public function update($type='in',$id=null){
		$this->db->where('type', $type);
		$this->db->where('id', $id);
		$data_old = $this->db->get($this->table)->row();
		if(!$data_old){
			return false;
		}

		$input = $this->input->post(NULL, TRUE);
		$data = [
			'library' => $input['library'],
			'book' => $input['book'],
			'category' => $input['category'],
			'qty' => $input['qty'],
			'notes' => isset($input['notes']) && $input['notes']!='' ? $input['notes'] : null,
			'reff' => isset($input['reff']) ? $input['reff'] : null,
			'type' => $type,
			'date' =>  $input['date'],
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->where('type', $type);
		$this->db->where('id', $id);
		$data_update = $this->db->update($this->table,$data);
		if((intval($data_old->qty) != intval($input['qty'])) || ($data_old->book !=$input['book'])){
			$type_rev=null;
			if($type=='in'){
				$type_rev = 'out';
			}elseif($type == 'out'){
				$type_rev = 'in';
			}
			$rev_qty=$this->update_book_qty($data_old->book, $data_old->qty, $type_rev);
			if($rev_qty){
				$this->update_book_qty($input['book'], $input['qty'], $type);
			}
		}
		return true;
	}

	public function delete($type = 'in', $id=null)
	{
		$this->db->where('type', $type);
		$this->db->where('id', $id);
		$data_delete = $this->db->get($this->table)->row();
		if (empty($data_delete)) {
			return false;
		}

		$data = [
			'deleted_at' => now(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];

		$this->db->where('type', $type);
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
		$type_rev=null;
		if($type=='in'){
			$type_rev = 'out';
		}elseif($type == 'out'){
			$type_rev = 'in';
		}
		$this->update_book_qty($data_delete->book,$data_delete->qty, $type_rev);
		return true;
	}

	public function update_book_qty($book,$qty,$type){

		$qty_update=$qty;
		if($type=='in'){
			$qty_update=intval($qty) * (1);
		}elseif($type=='out'){
			$qty_update = intval($qty) * (-1);
		}else{
			return false;
		}
		$this->db->where($this->column('id'), $book);
		$data_book = $this->db->get(db_prefix() . 'books')->row();
		if(!$data_book){
			return false;
		}
		$qty_update= $qty_update + $data_book->qty;

		$data = [
			'qty' => $qty_update,
		];
		$this->db->where($this->column('id'), $book);
		$this->db->update(db_prefix() .'books', $data);
		return true;
	}

	public function jenis_penambahan($id){
		$table_penambahan=db_prefix() . 'additions';
		$this->db->where($this->column('id'),$id);
		$data = $this->db->get($table_penambahan)->row();
		if(!$data){
			return false;
		}
		return $data;
	}

	public function jenis_pengurangan($id){
		$table_pengurangan=db_prefix() . 'substractions';
		$this->db->where($this->column('id'),$id);
		$data = $this->db->get($table_pengurangan)->row();
		if(!$data){
			return false;
		}
		return $data;
	}

	public function total_keluar_dashboard($start_date,$end_date,$all=true){
		$library = $this->input->get('library', true);
		if (is_admin() && !empty($library)) {
			$this->db->where($this->column('library'), $library);
		}
		if(!$all){
			$this->db->where($this->column('date') . '>=', $start_date);
			$this->db->where($this->column('date') . '<=', $end_date);
		}
		
		if (!is_admin()) {
			$user_library = $this->user_library();
			if (!empty($user_library)) {
				$this->db->where_in($this->column('library'), $user_library);
			} else {
				return 0;
			}
		}
		$this->db->where($this->column('type'), 'out');
		$this->db->where($this->column('reff').'!=',null );
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->result();
		$total=0;
		foreach ($data as $value) {
			$total=$total+$value->qty;
		}
		return $total;
	}

	public function total_data_report($search = null)
	{
		$lib_id = $this->input->get('library', true);
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('date') . '<=', $end);
			$this->db->where($this->column('date') . '>=', $start);
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
			if (!empty($search_book)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				$this->db->group_end();
			} elseif (empty($search_book)) {
				return [];
			}
		}
		// $this->db->where($this->column('type'), $type);
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function data_report($number = 10, $offset = 0, $search = null)
	{
		$lib_id = $this->input->get('library', true);
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('date') . '<=', $end);
			$this->db->where($this->column('date') . '>=', $start);
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
			if (!empty($search_book)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				$this->db->group_end();
			} elseif (empty($search_book)) {
				return [];
			}
		}
		// $this->db->where($this->column('type'), $type);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$library_name = '-';
			$category_name = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$library_data = $this->library_data($item->library);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($library_data) {
				$library_name = $library_data->library;
			}
			if ($item->type == 'in') {
				$jenis_penambahan = $this->jenis_penambahan($item->category);
				if ($jenis_penambahan) {
					$category_name = ucfirst($jenis_penambahan->title);
				}
			} elseif ($item->type == 'out') {
				$jenis_pengurangan = $this->jenis_pengurangan($item->category);
				if ($jenis_pengurangan) {
					$category_name = ucfirst($jenis_pengurangan->title);
				}
			}

			$data[$key]->date = date_format(date_create($item->date), 'd F Y');
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->library_name = $library_name;
			$data[$key]->category_name = $category_name;
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

	public function export_transaction($input)
	{
		$lib_id = $input['library'];
		$start = $input['start'];
		$end = $input['end'];

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('date') . '<=', $end);
			$this->db->where($this->column('date') . '>=', $start);
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
			if (!empty($search_book)) {
				$this->db->group_start();
				if (!empty($search_book)) {
					$this->db->where_in($this->column('book'), $search_book);
				}
				$this->db->group_end();
			} elseif (empty($search_book)) {
				return [];
			}
		}
		// $this->db->where($this->column('type'), $type);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('id'), 'desc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$book_title = '-';
			$book_code = '-';
			$library_name = '-';
			$category_name = '-';
			$book_data = $this->book_data($item->book, $item->library);
			$library_data = $this->library_data($item->library);
			if ($book_data) {
				$book_title = $book_data->title;
				$book_code = $book_data->code;
			}
			if ($library_data) {
				$library_name = $library_data->library;
			}
			if ($item->type == 'in') {
				$jenis_penambahan = $this->jenis_penambahan($item->category);
				if ($jenis_penambahan) {
					$category_name = ucfirst($jenis_penambahan->title);
				}
			} elseif ($item->type == 'out') {
				$jenis_pengurangan = $this->jenis_pengurangan($item->category);
				if ($jenis_pengurangan) {
					$category_name = ucfirst($jenis_pengurangan->title);
				}
			}

			$data[$key]->date = date_format(date_create($item->date), 'd F Y');
			$data[$key]->book_title = $book_title;
			$data[$key]->book_code = $book_code;
			$data[$key]->library_name = $library_name;
			$data[$key]->category_name = $category_name;
		}
		return $data;
	}
}
