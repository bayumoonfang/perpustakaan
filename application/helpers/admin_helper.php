<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('admin_url')) {
	function admin_url($url = '')
	{
		$adminURI = get_admin_uri();

		if ($url == '' || $url == '/') {
			if ($url == '/') {
				$url = '';
			}

			return site_url($adminURI) . '/';
		}

		return site_url($adminURI . '/' . $url);
	}
}
if (!function_exists('url_path')) {
	function url_path($url = '')
	{

		return site_url($url);
	}
}
if (!function_exists('get_admin_uri')) {
	function get_admin_uri()
	{
		return ADMIN_URL;
	}
}

if (!function_exists('is_admin')) {
	function is_admin($userid = null)
	{
		if (empty($userid) || current_user() == $userid) {
			return current_user('user_roleid') == 1 ? true : false;
		}
		/**
		 * Checking for current user?
		 */
		if (!is_numeric($userid)) {
			return false;
		}
		$CI = &get_instance();
		$CI->db = $CI->load->database('master', true);
		$CI->db->select('1')
			->where('user_roleid', '1')
			->where('user_isdelete', '0')
			->where('user_status', '1')
			->where('user_id', $userid);
		return $CI->db->count_all_results(db_master_prefix() . 'master_user') > 0 ? true : false;
	}
}

if (!function_exists('has_role_issue')) {
	function has_role_issue($role_id = null, $library_id = null)
	{
		if (empty($role_id) || empty($library_id)) {
			return false;
		}
		/**
		 * Checking for current user?
		 */

		$CI = &get_instance();
		$CI->config->load('roles', true);
		$CI->db = $CI->load->database('default', true);
		$roles = $CI->config->item('roles');

		$CI->db->where('library', $library_id);
		$data_role_issue = $CI->db->get(db_prefix() . 'role_issues')->row();
		if (!$data_role_issue) {
			return false;
		}
		$lib_role = !empty($data_role_issue->roles) ? unserialize($data_role_issue->roles) : [];
		foreach ($lib_role as $value) {
			if ($role_id == $value) {
				return true;
			}
		}
		return false;
	}
}

if (!function_exists('notification_count')) {
	function notification_count()
	{
		if (!user_can(['view issue'])) {
			return 0;
		}
		$ci          = &get_instance();
		$ci->db = $ci->load->database('default', true);
		$ci->load->model('App_model', 'app_model');

		$user_lib = $ci->app_model->user_library();

		if (!empty($user_lib)) {
			$ci->db->where_in('library', $user_lib);
		}
		$ci->db->where('expired_date <', date('Y-m-d 00:00:00'));
		$ci->db->where('status', 'pinjam');
		$ci->db->where('deleted_at', null);
		$total = $ci->db->get(db_prefix() . 'issues')->num_rows();
		return $total;
	}
}

if (!function_exists('notification_expired_list')) {
	function notification_expired_list()
	{
		if (!user_can(['view issue'])) {
			return array();;
		}
		$ci          = &get_instance();
		$ci->db = $ci->load->database('default', true);
		$ci->load->model('App_model', 'app_model');

		$user_lib = $ci->app_model->user_library();

		if (!empty($user_lib)) {
			$ci->db->where_in('library', $user_lib);
		}
		$ci->db->where('expired_date <', date('Y-m-d 00:00:00'));
		$ci->db->where('status', 'pinjam');
		$ci->db->where('deleted_at', null);
		$ci->db->select('expired_date');
		$ci->db->group_by('expired_date');
		$ci->db->order_by('expired_date', 'desc');
		$date_expired = $ci->db->get(db_prefix() . 'issues')->result();
		foreach ($date_expired as $key => $value) {
			$total = 0;
			$ci->db->where('status', 'pinjam');
			$ci->db->where('deleted_at', null);
			$ci->db->where('expired_date', $value->expired_date);
			$data_total = $ci->db->get(db_prefix() . 'issues')->num_rows();
			if (!empty($data_total) || $data_total > 0) {
				$total = $data_total;
			}
			$date_expired[$key]->total = $total;
		}
		return $date_expired;
	}
}
