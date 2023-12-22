<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiValidation{

	protected $post=[] ;

	public function __construct()
	{
		$this->ci = &get_instance();
	}

	public function validation_post($input_post=[],$post_rule=[]){
		
	}
}
