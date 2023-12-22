<?php

class Migrate extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->db=$this->load->database('default',true);
		$this->load->library('migration');
	}

	public function index()
	{
		if ($this->migration->latest() === FALSE) {
			show_error($this->migration->error_string());
		}
	}

	public function migrate()
	{
		
		if ($this->migration->latest() === FALSE) {
			show_error($this->migration->error_string());
		}
	}

	public function rollback($id=null)
	{
		$version=db_version()-1;
		if(!is_null($id) && intval($id)>-1){
			$version= $id;
		}
		if ($this->migration->version($version) === FALSE) {
			show_error($this->migration->error_string());
		}
	}
	public function refresh()
	{
		$this->rollback(0);
		$this->migrate();
		redirect('/');
	}
}
