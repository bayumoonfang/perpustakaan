<?php
defined('BASEPATH') or exit('No direct script access allowed');


if (!function_exists('permissions')) {
	function permissions()
	{
		
		$CI = &get_instance();
		$CI->load->config('permissions');
		return $CI->config->item('permissions');
	}
}
if (!function_exists('current_user')) {
	function current_user($column='user_id')
	{
		$CI = &get_instance();
		$data=$CI->session->userdata(session_prefix() . 'user');
		return $data->$column;
	}
}

