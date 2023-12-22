<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class AddonManager extends Admin_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('addon_model', 'addon');
	}

	public function index(){
		$data['addons']=$this->addon->data();
		$data['title']='Addon Manager';
		return view('panel.addon.index', $data);
	}

	public function new(){
		$data['title'] = 'New Addon';
		return view('panel.addon.form', $data);
	}

	public function upload_file($task=''){
		$dir='update';
		if(!is_dir($dir)){
			mkdir($dir,0777,true);
		}
		$zipped_filename=$_FILES['addon']['name'];
		$path=$dir.'/'.$zipped_filename;
		move_uploaded_file($_FILES['addon']['tmp_name'],$path);

		// unzip uploaded file
		$zip = new ZipArchive;
		$zip->open($path);
		$zip->extractTo($dir);
		$zip->close();
		
		// remove uploaded zip
		unlink($path);
		$unzipped_filename=substr($zipped_filename,0,-4);
		$addonPath= './' . $dir . '/' . $unzipped_filename;
		if(!file_exists($addonPath . '/update_config.json')){
			echo 'file config not exists';
			$this->emptyDir($addonPath);
			return;
		}
		$str=file_get_contents('./'. $dir.'/'.$unzipped_filename.'/update_config.json');
		$json=json_decode($str,true);

		//run PHP modification code

		if (!file_exists($addonPath . '/update_script.php')) {
			echo 'file scripts not exists';
			$this->emptyDir($addonPath);
			return;
		}

		require './'. $dir.'/'. $unzipped_filename.'/update_script.php';

		// create new directory
		if(!empty($json['directory'])){
			foreach ($json['directory'] as $key => $directory) {
				$dir_name=$directory['name'];
				if(!is_dir($dir_name)){
					mkdir($dir_name, 0777, true);
				}
			}
		}

		//create or replace new file
		if(!empty($json['files'])){
			foreach ($json['files'] as $key => $file) {
				copy($file['root_directory'],$file['update_directory']);
			}
		}
		$this->emptyDir($addonPath);
		set_alert('Addon berhasil diinstall', 'success');
		redirect(admin_url('addon-manager'));
	}

	private function emptyDir($dir)
	{
		if (is_dir($dir)) {
			$scn = scandir($dir);
			foreach ($scn as $files) {
				if ($files !== '.') {
					if ($files !== '..') {
						if (!is_dir($dir . '/' . $files)) {
							unlink($dir . '/' . $files);
						} else {
							$this->emptyDir($dir . '/' . $files);
							rmdir($dir . '/' . $files);
						}
					}
				}
			}
			rmdir($dir);
		}
	}

}
