<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Jenssegers\Blade\Blade;

if (!function_exists('view')) {
	function view($view,$data=[]){
		$path=APPPATH.'views';
		$blade=new Blade($path, $path.'/cache');
		echo $blade->make($view,$data);
	}
}

if (!function_exists('old')) {
	function old($key,$default=''){
		$ci=&get_instance();
		$ci->load->library('session');
		$input_data = $ci->session->flashdata('input_data');

		return $input_data[$key] ?? $default;
	}
}

