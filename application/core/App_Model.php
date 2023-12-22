<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();

    }
	
	protected function column($column_name)
	{
		return $this->table_prefix . $column_name;
	}

	public function user_library($sekolah_id=''){
		if(empty($sekolah_id)){
			$sekolah_id= current_user('user_sekolahid');
		}
		$lib_db=$this->load->database('default', true);
		$library_table = db_prefix() . 'libraries';
		$lib_db->select('id');
		$lib_db->where('school', $sekolah_id);
		$lib_db->where('deleted_at',null);
		$data= $lib_db->get($library_table)->result();
		$result=array();
		foreach ($data as $key => $values) {
			array_push($result,$values->id);
		}
		return $result;
	}

	protected function api_user_sekolah($user_id){
		$master_db=$this->load->database('master', true);
		$user_table = db_master_prefix() . 'master_user';
		$master_db->where('user_id', $user_id);
		$master_db->where('user_status','1');
		$master_db->where('user_isdelete','0');
		$data= $master_db->get($user_table)->row();
		if(!$data){
			return false;
		}else{
			$master_db->where('userkelas_userid', $data->user_id);
			$datakelas = $master_db->get(db_master_prefix() . 'master_user_kelas')->row();
			$kelas='0';
			if($datakelas){
				$kelas= $datakelas->userkelas_id;
			}
			$data->kelas= $kelas;
			return $data;
		}
	}
}
