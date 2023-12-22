<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ApiAdmin_Controller extends Api_Controller
{
	protected $_user;
	public function __construct()
	{
		parent::__construct();
		$auth='Authorization';
		$user='User';
		$header = $this->input->request_headers();
		if (empty($header[$auth])) {
			return $this->failUnauthorized();
		}
		if (empty($header[$user])) {
			return $this->failUnauthorized('User is required on header request');
		}
		if ($header[$auth] != env('API_KEY')) {
			return $this->failUnauthorized();
		}
		$this->_user = $header[$user];
	}
}
