<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class RoleIssue extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->config->load('roles',true);
		$this->load->model('library_model', 'library');
		$this->load->model('pengaturan_role_issue_model', 'role_issue');
	}

	public function index(){
		if(!is_admin()){
			show_404();
		}
		$data_role=$this->role_issue->data();
		$data['data']= $data_role;
		$data['title']='Role can Issue';
		return view('panel.pengaturan-role-issue.index', $data);
	}

	public function add(){
		if(!is_admin()){
			show_404();
		}
		$roles = $this->config->item('roles');
		$libraries=$this->library->data(100000);
		$data['libraries']= $libraries;
		$data['roles']= $roles;
		$data['title']='Tambah Role can Issue';
		return view('panel.pengaturan-role-issue.form', $data);
	}

	public function store(){
		if (!is_admin()) {
			show_404();
		}
		form_validate([
			'library' => 'required',
			'role' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$check=$this->role_issue->check($input['library'], $input['role']);
		if($check){
			set_alert('Role dari perpustakaan sudah ada sebelumnya', 'danger');
			back();
		}
		$data_input=$this->role_issue->add();
		set_alert('Role Issue berhasil ditambah', 'success');
		redirect(admin_url('pengaturan/role-issue'));
	}

	public function edit($id){
		if (!is_admin()) {
			show_404();
		}
		$library=$this->library->get_data($id);
		if(!$library){
			show_404();
		}
		$roles = $this->config->item('roles');
		$data['library'] = $library;
		$data['roles'] = $roles;
		$data['role_issue'] = $this->role_issue->lib_role($id);;
		$data['title'] = 'Edit Role can Issue';
		return view('panel.pengaturan-role-issue.form', $data);
	}

	public function update($id)
	{
		if (!is_admin()) {
			show_404();
		}
		form_validate([
			'library' => 'required',
		]);
		$library = $this->library->get_data($id);
		if (!$library) {
			show_404();
		}
		$data_input = $this->role_issue->update($id);
		set_alert('Role Issue berhasil diubah', 'success');
		redirect(admin_url('pengaturan/role-issue'));
	}

	public function delete($id){
		if (!is_admin()) {
			show_404();
		}
		$role_issue = $this->role_issue->get_data($id);
		if (!$role_issue) {
			show_404();
		}

		$this->role_issue->delete($id);
		set_alert('Role Issue berhasil dihapus', 'success');
		redirect(admin_url('pengaturan/role-issue'));
	}
}
