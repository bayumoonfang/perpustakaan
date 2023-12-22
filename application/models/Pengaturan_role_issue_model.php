<?php defined('BASEPATH') or exit('No direct script access allowed');

class Pengaturan_role_issue_model extends App_Model
{
	private $table;

	public function __construct()
	{
		parent::__construct();
		$this->config->load('roles', true);
		$this->roles=$this->config->item('roles');
		$this->table = db_prefix() . 'role_issues';
		$this->table_library = db_prefix() . 'libraries';
		$this->table_prefix = '';
		$this->db = $this->load->database('default', true);
	}

	public function data(){
		$this->db->where($this->column('deleted_at',null));
		$this->db->order_by($this->column('library'), 'asc');
		$data = $this->db->get($this->table_library)->result();
		foreach ($data as $key => $item) {
			$roles= $this->lib_role($item->id);
			$role_name='';
			foreach ($roles as $key1=>$role) {
				$comma=count($roles)-1 == $key1 ? '':', ';
				foreach ($this->roles as $value) {
					if ($value['id'] == $role) {
						$role_name = $role_name.$value['name']. $comma;
					}
				}
			}
			if($role_name == ''){
				$role_name='-';
			}
			$data[$key]->library_name= $data[$key]->library;
			$data[$key]->role_name= $role_name;
		}
		return $data;
	}

	public function lib_role($id){
		$this->db->where($this->column('library'),$id);
		$data=$this->db->get($this->table)->row();
		if(!$data){
			return array();
		}else{
			return !empty($data->roles) ? unserialize($data->roles) : [];
		}
	}

	public function get_data($id){
		$this->db->where($this->column('id'), $id);
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if(!$data){
			return false;
		}
		$library_name = '-';
		$role_name = '-';
		$this->db->where($this->column('id'), $data->library);
		$libData = $this->db->get(db_prefix() . 'libraries')->row();
		if (!empty($libData)) {
			$library_name = ucfirst($libData->library);
		};
		foreach ($this->roles as $value) {
			if ($value['id'] == $data->role) {
				$role_name = $value['name'];
				continue;
			}
		}
		$data->library_name = $library_name;
		$data->role_name = $role_name;
		return $data;
	}

	public function check($library=null,$role=null){
		if(!$library || !$role){
			return false;
		}
		$this->db->where($this->column('library'), $library);
		$this->db->where($this->column('role'), $role);
		$this->db->where($this->column('deleted_at'), null);
		$data = $this->db->get($this->table)->row();
		if (!$data) {
			return false;
		}else{
			return true;
		}
	}

	public function add()
	{
		$input = $this->input->post(NULL, TRUE);
		$data = [
			'role' => $input['role'],
			'library' => $input['library'],
			'created_at' => now(),
			'created_by' => current_user(),
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($id){
		$input = $this->input->post(NULL, TRUE);
		$roles=isset($input['role']) && is_array($input['role']) ? serialize($input['role']):serialize(array());
		$data = [
			'roles' => $roles,
			'library' => $input['library'],
			'updated_at' => now(),
			'updated_by' => current_user(),
		];
		$this->db->where($this->column('library'),$id);
		$data_exists = $this->db->get($this->table)->row();
		if(!$data_exists){
			$data['created_at']=now();
			$data['created_by']= current_user();
			$this->db->insert($this->table, $data);
		}else{
			$this->db->where($this->column('id'), $data_exists->id);
			$this->db->update($this->table, $data);
		}
	}

	public function delete($id){
		$this->db->where($this->column('id'), $id);
		$this->db->delete($this->table);
	}
}
