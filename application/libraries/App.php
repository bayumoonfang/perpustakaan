<?php
defined('BASEPATH') or exit('No direct script access allowed');


class App{
	private $ci;

	public function __construct()
	{
		// Assign the CodeIgniter super-object
		$this->ci = &get_instance();
	}

	public function latest_version()
	{
		$this->ci->load->database();
		$row = $this->ci->db->get(db_prefix() . 'migrations')->row();
		if(!empty($row)){
			if($row->version<1){
				return 0;
			}else{
				return $row->version;
			}
		}else{
			return 0;
		}
	}
}
