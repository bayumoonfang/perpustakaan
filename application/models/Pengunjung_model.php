<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pengunjung_model extends App_Model
{
	private $table;
	private $table_user;
	private $table_role;
	private $table_sekolah;
	private $table_library;
	public $table_prefix;
	private $db;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_prefix() . 'tamu';
		$this->table_library = db_prefix() . 'libraries';
		$this->table_prefix = '';
		$this->table_user = db_master_prefix() . 'master_user';
		$this->table_sekolah = db_master_prefix() . 'master_sekolah';
		$this->table_role = db_master_prefix() . 'master_role';
		$this->db = $this->load->database('default', true);
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

	// public function data_search($search){
	// 	if (!empty($search)) {
	// 		$search_book = $this->search_book($search);
	// 		if (!empty($search_book)) {
	// 			$this->db->group_start();
	// 			if (!empty($search_book)) {
	// 				$this->db->where_in($this->column('book'), $search_book);
	// 			}
	// 			$this->db->group_end();
	// 		} elseif (empty($search_book)) {
	// 			return [];
	// 		}
	// 	}
	// }

	public function data($number = 10, $offset = 0)
	{
		$lib_id = $this->input->get('library', true);
		$start = $this->input->get('start', true);
		$end = $this->input->get('end', true);
		$status = $this->input->get('status', true);

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('date') . '<=', $end);
			$this->db->where($this->column('date') . '>=', $start);
			$this->db->group_end();
		}
		if ($status) {
			$this->db->group_start();
			if ($status == '1') {
				$this->db->where($this->column('is_guest'), $status);
			} else {
				$this->db->where($this->column('status'), $status);
			}
			$this->db->group_end();
		}
		if ($lib_id) {
			$this->db->group_start();
			$this->db->where($this->column('library'), $lib_id);
			$this->db->group_end();
		} else {
			$this->user_libs();
		}
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('date'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			$date = date_create($item->date);
			$data[$key]->tanggal = date_format($date, "d-m-Y");;
			$data[$key]->user_detail = $this->detail_user($item->user);
			$data[$key]->role_detail = $this->detail_role($item->status);
			$data[$key]->library_detail = $this->detail_library($item->library);
			$data[$key]->school_detail = $this->detail_school($item->user);
		}
		return $data;
	}

	public function count_data($number = 10, $offset = 0)
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
		$this->db->select(" COUNT(*) as jumlah, user, library,status, is_guest, guest_name, institution", false);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->group_by("user, library");
		$this->db->order_by($this->column('date'), 'desc');
		$data = $this->db->get($this->table, $number, $offset)->result();
		foreach ($data as $key => $item) {
			// $date = date_create($item->date);
			// $data[$key]->tanggal = date_format($date, "d-m-Y");;
			$data[$key]->user_detail = $this->detail_user($item->user);
			$data[$key]->role_detail = $this->detail_role($item->status);
			$data[$key]->library_detail = $this->detail_library($item->library);
		}
		return $data;
	}

	public function all_data($lib_id)
	{
		$start = $this->input->get('start_excel', true);
		$end = $this->input->get('end_excel', true);
		$status = $this->input->get('status_excel', true);
		$lib = $this->input->get('library_excel', true);

		if ($start && $end) {
			$this->db->group_start();
			$this->db->where($this->column('date') . '<=', $end);
			$this->db->where($this->column('date') . '>=', $start);
			$this->db->group_end();
		}
		if ($status) {
			$this->db->group_start();
			if ($status == '1') {
				$this->db->where($this->column('is_guest'), $status);
			} else {
				$this->db->where($this->column('status'), $status);
			}
			$this->db->group_end();
		}
		$this->db->where($this->column('library'), $lib);
		$this->db->where($this->column('deleted_at'), null);
		$this->db->order_by($this->column('date'), 'desc');
		$data = $this->db->get($this->table)->result();
		foreach ($data as $key => $item) {
			$date = date_create($item->date);
			$data[$key]->tanggal = date_format($date, "d-m-Y");;
			$data[$key]->user_detail = $this->detail_user($item->user);
			$data[$key]->role_detail = $this->detail_role($item->status);
			$data[$key]->library_detail = $this->detail_library($item->library);
		}
		return $data;
	}

	public function total_data()
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
		$this->db->where($this->column('deleted_at'), null);
		$total = $this->db->get($this->table)->num_rows();
		return !empty($total) ? $total : 0;
	}

	public function detail_user($user)
	{
		$master_db = $this->load->database('master', true);
		$master_db->where('user_id', $user);
		$data = $master_db->get($this->table_user)->row();
		if (!$data) {
			return null;
		} else {
			return $data;
		}
	}
	public function detail_school($user)
	{
		$master_db = $this->load->database('master', true);
		$master_db->select('*');
		$master_db->from('master_user');
		$master_db->join('master_sekolah', 'master_user.user_sekolahid = master_sekolah.sekolah_id');
		$master_db->where('master_user.user_id', $user);
		$data = $master_db->get()->row();
		// $master_db = 
		// $master_db->join('')
		// $master_db->where('sekolah_id', $user);
		// $data = $master_db->get($this->table_sekolah)->row();
		if (!$data) {
			return null;
		} else {
			return $data;
		}
	}

	public function detail_role($role)
	{
		$master_db = $this->load->database('master', true);
		$master_db->where('role_id', $role);
		$data = $master_db->get($this->table_role)->row();
		if (!$data) {
			return null;
		} else {
			return $data;
		}
	}
	public function detail_library($lib)
	{

		$this->db->where('id', $lib);
		$data = $this->db->get($this->table_library)->row();
		if (!$data) {
			return null;
		} else {
			return $data;
		}
	}

	public function cek_user($user)
	{
		$master_db = $this->load->database('master', true);
		$master_db->where('user_no', $user);
		$master_db->where('user_status', '1');
		$master_db->where('user_isdelete', '0');
		$data = $master_db->get($this->table_user)->row();
		if (!$data) {
			return false;
		} else {
			return $data;
		}
	}

	public function cek_user_library($user, $lib_id)
	{
		$detail_lib = $this->detail_library($lib_id);
		if ($detail_lib) {
			if (!$user) {
				return false;
			}
			if ($user->user_sekolahid != $detail_lib->school) {
				return false;
			}
			return true;
		} else {
			return false;
		}
	}

	public function post_pengunjung($input, $user)
	{
		date_default_timezone_set('Asia/Jakarta');
		$data = [
			'library' => $input['library'],
			'user' => $user->user_id,
			'status' => $user->user_roleid,
			'description' => $input['description'],
			'date' => now(),
			'time' => date("H:i:s"),
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function post_guest($data_post)
	{
		date_default_timezone_set('Asia/Jakarta');
		$data = [
			'is_guest' => '1',
			'library' => $data_post['library'],
			'guest_name' => $data_post['nama'],
			'institution' => $data_post['institusi'],
			'description' => $data_post['tujuan'],
			'date' => now(),
			'time' => date("H:i:s"),
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		return true;
	}
}
