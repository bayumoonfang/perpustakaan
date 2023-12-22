<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class App_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->form_validation->set_message('required', 'Bidang {field} wajib diisi');
		$this->form_validation->set_message('valid_email', 'Email tidak valid');
		$this->form_validation->set_message('is_unique', '{field} telah terpakai');
    }
   
}
