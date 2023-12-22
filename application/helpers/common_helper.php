<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('db_version')) {
	function db_version()
	{
		$ci = &get_instance();
		if (!class_exists('app', false)) {
			$ci->load->library('app');
		}
		return $ci->app->latest_version();
	}
}

// if (!function_exists('get_user_row')) {
// 	function get_user_row($param = NULL, $where = 'user_id')
// 	{
// 		$ci = &get_instance();
// 		$ci->load->model('user_model');
// 		return $ci->user_model->get_user_row($param, $where);
// 	}
// }
// if (!function_exists('get_user_id')) {
// 	function get_user_id()
// 	{
// 		$CI = &get_instance();
// 		return $CI->session->userdata(session_prefix() . 'user_id');
// 	}
// }
// if (!function_exists('get_user_data')) {
// 	function get_user_data()
// 	{
// 		$CI = &get_instance();
// 		return $CI->session->userdata(session_prefix() . 'user');
// 	}
// }

if (!function_exists('asset_url')) {
	function asset_url($path='')
	{
		return base_url() . 'assets/'.$path;
	}
}

if (!function_exists('form_validate')) {
	function form_validate($data=[])
	{
		if(count($data)<=0){
			return false;
		}
		$CI = &get_instance();
		$CI->load->library('session');
		$CI->load->library('form_validation');
		$CI->load->library('user_agent');
		foreach ($data as $key => $value) {
			$CI->form_validation->set_rules($key, $key, $value);
			
		}
		if($CI->form_validation->run() == FALSE){
			$errors = $CI->form_validation->error_array();
			
			$CI->session->set_flashdata('errors', $errors);
			back();
		}
		return true;
	}
}

if (!function_exists('back')) {
	function back()
	{
		$CI = &get_instance();
		$CI->load->library('user_agent');
		$referrer=$CI->agent->referrer();	
		redirect($referrer);
	}
}

if (!function_exists('get_error')) {
	function get_error($name = '')
	{
		$CI = &get_instance();
		$CI->load->library('session');
		$data_error = $CI->session->flashdata('errors');
		if(empty($name)){
			if(isset($data_error)){
				return $data_error;
			}else{
				return [];
			}
		}else{
			if (isset($data_error) && isset($data_error[$name])) {
				return $data_error[$name];
			} else {
				return '';
			}
		}
	}
}

if (!function_exists('set_alert')) {
	function set_alert($message, $type='info')
	{
		$CI = &get_instance();
		$CI->load->library('session');
		$CI->session->set_flashdata('alert',['type'=> $type,'message'=> $message]);
	}
}

if (!function_exists('show_status')) {
	function show_status()
	{
		$CI = &get_instance();
		$CI->load->library('session');
		$alert= $CI->session->flashdata('alert');
		if(isset($alert)){
			echo '<div class=" alert alert-'. $alert['type']. '">' . $alert['message'] . '</div>';
		}
		return null;
	}
}

if (!function_exists('pagination')) {
	function pagination()
	{
		$config['per_page'] = 10;
		$config['reuse_query_string'] = true;
		$config['first_link']       = 'First';
		$config['last_link']        = 'Last';
		$config['next_link']        = 'Next';
		$config['prev_link']        = 'Prev';
		$config['full_tag_open']    = '<div class="pagging text-center"><nav><ul class="pagination">';
		$config['full_tag_close']   = '</ul></nav></div>';
		$config['num_tag_open']     = '<li class="page-item"><span class="page-link">';
		$config['num_tag_close']    = '</span></li>';
		$config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
		$config['next_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['next_tagl_close']  = '<span aria-hidden="true">&raquo;</span></span></li>';
		$config['prev_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['prev_tagl_close']  = '</span>Next</li>';
		$config['first_tag_open']   = '<li class="page-item"><span class="page-link">';
		$config['first_tagl_close'] = '</span></li>';
		$config['last_tag_open']    = '<li class="page-item"><span class="page-link">';
		$config['last_tagl_close']  = '</span></li>';
		return $config;
	}
}

if(!function_exists('now')){
	function now(){
		return date('Y-m-d H:i:s');
	}
}
if (!function_exists('appWantJson')) {
	function appWantJson()
	{
		if (detect_app_input_format() == 'json') {
			return true;
		} else {
			return false;
		}
	}
}
if (!function_exists('detect_app_input_format')) {
	function detect_app_input_format()
	{
		$_supported_formats = [
			'json'       => 'application/json',
			'array'      => 'application/json',
			'csv'        => 'application/csv',
			'html'       => 'text/html',
			'jsonp'      => 'application/javascript',
			'php'        => 'text/plain',
			'serialized' => 'application/vnd.php.serialized',
			'xml'        => 'application/xml',
		];
		$CI = &get_instance();
		// Get the CONTENT-TYPE value from the SERVER variable
		$content_type = $CI->input->server('CONTENT_TYPE');

		if (empty($content_type) === false) {
			// If a semi-colon exists in the string, then explode by ; and get the value of where
			// the current array pointer resides. This will generally be the first element of the array
			$content_type = (strpos($content_type, ';') !== false ? current(explode(';', $content_type)) : $content_type);
			// Check all formats against the CONTENT-TYPE header
			foreach ($_supported_formats as $type => $mime) {
				// $type = format e.g. csv
				// $mime = mime type e.g. application/csv

				// If both the mime types match, then return the format
				if ($content_type === $mime) {
					return $type;
				}
			}
		}
	}
}