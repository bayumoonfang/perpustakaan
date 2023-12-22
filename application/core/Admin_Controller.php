<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Admin_Controller extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        if(!is_login()){
            if (!appWantJson()) {
                redirect(site_url());
            } else {
                ob_start();
                $respond = [
                    'status' => false,
                    'message' => 'Unauthorized. silahkan login kembali',
                ];
                $this->output->set_status_header(401);
                $this->output->set_content_type('application/json');
                $data = json_encode($respond, 256);
                $this->output->set_output($data);
                $this->output->_display();
                ob_end_flush();
                exit;
            }
        }
    }
   
}
