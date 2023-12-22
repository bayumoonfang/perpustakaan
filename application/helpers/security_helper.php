<?php
defined('BASEPATH') or exit('No direct script access allowed');


// create instance phpass
if (!function_exists('csrf_token')) {
	function csrf_token()
	{
		$ci = &get_instance();
		$csrf_token = $ci->security->get_csrf_token_name();
		$csrf_hash = $ci->security->get_csrf_hash();

		echo '<input type="hidden" name="' . $csrf_token . '" value="' . $csrf_hash . '">';
	}
}

if (!function_exists('is_login')) {
	function is_login()
	{
		return get_instance()->session->has_userdata(session_prefix() . 'logged_in');
	}
}

if (!function_exists('app_hash')) {
	function app_hash()
	{
		$ci = &get_instance();
		if (!class_exists('PasswordHash', false)) {
			$ci->load->library('PasswordHash');
		}
		$app_hash = new PasswordHash(PHPASS_HASH_STRENGTH, PHPASS_HASH_PORTABLE);

		return $app_hash;
	}
}

/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 */
if (!function_exists('generatePassword')) {
	function generatePassword($plainPassword = '')
	{
		if (!$plainPassword) {
			return false;
		}
		return app_hash()->HashPassword($plainPassword);
	}
}
/**
 * This function used to generate the hashed password
 * @param {string} $plainPassword : This is plain text password
 * @param {string} $hashedPassword : This is hashed password
 */
if (!function_exists('verifyPassword')) {
	function verifyPassword($plainPassword = '', $hashedPassword = '')
	{
		if (!$plainPassword && !$hashedPassword) {
			return false;
		}
		return app_hash()->CheckPassword($plainPassword, $hashedPassword);
	}
}

function user_can($permission, $userid = '')
{
	// return true;
	return user_access($permission, $userid, false);
}

function user_access($access, $userid = '', $redirect = true)
{
	if(empty($userid)){
		$userid= current_user('user_id');
	}

	if (is_admin($userid)) {
		return true;
	}

	$CI = &get_instance();
	$permission_list= $CI->session->userdata(session_prefix() . 'permissions');

	if(!$permission_list){
		return false;
	}
	
	$CI->load->database();
	if (gettype($access) == 'string') {
		if (in_array(strtolower(trim($access)), array_column($permission_list, 'permission'))) {
			$status = true;
		} else {
			$status = false;
		}
	} else {
		$exists_access = 0;
		foreach ($access as $value) {
			if (in_array(strtolower(trim($value)), array_column($permission_list, 'permission'))) {
				$exists_access++ ;
			} 
		}
		$status = $exists_access > 0 ? true : false;
	}

	if (!$redirect) {
		return $status;
	}
	if ($status) {
		return true;
	}
	show_404();
}

function has_role_permission($role_id, $capability)
{
	$CI          = &get_instance();

	$CI->db->where('role', $role_id);
	$role = $CI->db->get(db_prefix() . 'master_role_permissions')->row();
	if(!$role){
		return false;
	}
	$permissions = !empty($role->access) ? unserialize($role->access) : [];
	foreach ($permissions as $permission) {
		if ($permission == $capability){
			return true;
		}
	}

	return false;
}

function has_user_permission($user, $capability)
{
	$CI          = &get_instance();
	$CI->db->where('user', $user);
	$CI->db->where('permission', $capability);
	$user_permission = $CI->db->get(db_prefix() . 'master_user_permissions')->row();
	if(!$user_permission){
		return false;
	}
	return true;
}
if (!function_exists('strEncrypt')) {
	function strEncrypt($str = "", $forDB = FALSE)
	{
		$CI = &get_instance();
		$key    = $CI->config->item('encryption_key');

		$str    = ($forDB) ? 'md5(concat(\'' . $key . '\',' . $str . '))' : md5($key . $str);
		return $str;
	}
}
