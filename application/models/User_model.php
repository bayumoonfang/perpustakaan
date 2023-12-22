<?php defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = db_master_prefix() . 'master_user';
		$this->table_prefix = 'user_';
		$this->db = $this->load->database('master', true);
	}

	public function get_user_row($param = NULL, $where = 'id')
	{
		$this->db->where($where, $param);
		$this->db->where($this->table_prefix .'isdelete', '0');
		$user = $this->db->get($this->table)->row();
		if (empty($user)) {
			return false;
		}
		return $user;
	}

	public function login(){
		$username = $this->input->post('username', true);
		$password = $this->input->post('password', true);
		if (empty($username) && empty($password)) {
			return false;
		}
		$user = $this->get_user_row($username, $this->table_prefix .'no');
		if (!$user) {
			//user not exists
			return false;
		}
		// check password
		$valid = strEncrypt($password)== $user->user_password;
		if (!$valid) {
			return false;
		}
		//check user active or deleted
		if ($user->user_status != "1") {
			return ["inactive" => true];
		}
		$this->set_user_sessions($user);
		return $user;
	}

	public function login_ssi($user_id){
		$user = $this->get_user_row($user_id, $this->table_prefix . 'id');
		if (!$user) {
			//user not exists
			return false;
		}
		if ($user->user_status != "1") {
			return ["inactive" => true];
		}
		$this->set_user_sessions($user);
		return $user;
	}

	protected function set_user_sessions($user){
		$user_data = [
			session_prefix() . 'user_id'  => $user->user_id,
			session_prefix() . 'user_role' => $user->user_roleid,
			session_prefix() . 'logged_in' => true,
			session_prefix() . 'user' => $user,
			session_prefix() . 'permissions' => $user->user_roleid == '1' ? array() : $this->permissions($user->user_id),
		];
		$this->session->set_userdata($user_data);
	}

	public function permissions($userid){
		$this->db->where('user',$userid);
		$user_permissions=$this->db->get(db_master_prefix() . 'master_user_permissions')->result();
		$permission_list=array();
		foreach ($user_permissions as $key => $permission) {
			$this->db->where('id',$permission->permission);
			$master_permissions = $this->db->get(db_master_prefix() . 'master_permissions')->row();
			if($master_permissions){
				$data = array(
					'id' => $master_permissions->id,
					'permission' => $master_permissions->permission,
				);
				array_push($permission_list, $data);
			}
		}
		return $permission_list;
	}

	public function get_member($search){
		$master_db = $this->load->database('master', true);
		$user_table = db_master_prefix() . 'master_user';
		$sekolah_id = current_user('user_sekolahid');
		$master_db->where('user_sekolahid', $sekolah_id);
		$master_db->group_start();
		$master_db->like('user_no', $search);
		$master_db->or_like('user_nama', $search);
		$master_db->group_end();
		
		$master_db->where('user_status', '1');
		$master_db->where('user_isdelete', '0');
		$data=$master_db->get($user_table)->result();
		return $data;
	}

	public function cek_member($id){
		$master_db = $this->load->database('master', true);
		$user_table = db_master_prefix() . 'master_user';
		
		$sekolah_id = current_user('user_sekolahid');
		$master_db->where('user_sekolahid', $sekolah_id);
	
		$master_db->where('user_id', $id);
		$master_db->where('user_status', '1');
		$master_db->where('user_isdelete', '0');
		$data=$master_db->get($user_table)->row();
		if(!$data){
			return false;
		}
		return $data;
	}
}
