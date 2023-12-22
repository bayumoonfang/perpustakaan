<?php

defined('BASEPATH') or exit('No direct script access allowed');

class App_Autoloader
{
    /**
     * Register Autoloader
     */
    public static function register()
    {
        
        spl_autoload_register(function ($classname) {
            // file_exists(APPPATH . 'core/' . $classname . '.php') will include the deprecated too CRM_Controller and CRM_Model
            // strpos($classname, 'App_') !== 0 is for AdminController and ClientsController
            if (strpos($classname, 'App_') !== 0 && file_exists(APPPATH . 'core/' . $classname . '.php')) {
                @include_once(APPPATH . 'core/' . $classname . '.php');
            }

            $prefixes = [
                'app'     => APPPATH,
                'modules' => APP_MODULES_PATH,
            ];
            foreach ($prefixes as $prefix => $replacement) {
                if (strpos(strtolower($classname), "{$prefix}\\") === 0) {

                    // Locate class relative path
                    $classname = str_replace("{$prefix}\\", '', $classname);
                    $filepath = $replacement . str_replace('\\', DIRECTORY_SEPARATOR, ltrim($classname, '\\')) . '.php';
                   
                    if (file_exists($filepath)) {
                       
                        require $filepath;
                    }
                }
            }
        });
       
    }

	public function set_input_flash_data(){
		$ci = &get_instance();
		$ci->load->library('session');
		$ci->load->helper('form');
		$post=array();
		if($ci->input->post()){
			foreach ($ci->input->post() as $key => $val) {
				$post[$key] = $ci->input->post($key, true);
			}
			$ci->session->set_flashdata('input_data', $post);
		}
		
	}

}
