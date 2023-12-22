<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Authentication extends App_Controller
{
	public function __construct()
	{
		parent::__construct();
		
	}

	public function index()
	{
		return view('panel.dashboard');
	}

	public function login(){
		if (is_login()) {
			redirect(admin_url());
		}
		form_validate([
			'username'=> 'trim|required|max_length[200]',
			'password'=> 'required|max_length[100]',
		]);
		$this->load->model('user_model', 'user');
		$validate=$this->user->login();
		if (is_array($validate) && isset($validate['inactive'])) {
			set_alert('Akun tidak aktif','danger');
			back();
		} elseif ($validate == false) {
			set_alert('Username / Password tidak valid','danger' );
			back();
		}
		redirect(admin_url());
	}
	
	public function logout()
	{
		$this->session->unset_userdata(session_prefix() . 'user_id');
		$this->session->unset_userdata(session_prefix() . 'user_role');
		$this->session->unset_userdata(session_prefix() . 'logged_in');
		$logout_url=false;
		if($this->session->has_userdata(session_prefix() . 'logout_url')){
			$logout_url= $this->session->userdata(session_prefix() . 'logout_url');
		}
		$this->session->sess_destroy();
		if($logout_url){
			redirect($logout_url);
		}else{
			redirect('/');
		}
	}
}
