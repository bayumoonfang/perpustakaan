<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Rak extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('library_model', 'library');
		$this->load->model('rak_model', 'rak');
	}


	public function rak($id)
	{
		user_access(['edit rak', 'add rak', 'delete rak', 'view rak']);
		$library = $this->library->get_data($id);
		if (!$library) {
			show_404();
		}
		$user_sekolah = current_user('user_sekolahid');
		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				show_404();
			}
		}
		$list_rak = $this->rak->data($id, 10000);
		$data['library'] = $library;
		$data['list_rak'] = $list_rak;
		$data['title'] = 'Data Rak ' . $library->library;
		return view('panel.rak.index', $data);
	}

	public function store_rak($id)
	{
		user_access(['add rak']);
		form_validate([
			'rack' => 'required',
			'status' => 'required',
		]);
		$library = $this->library->get_data($id);
		if (!$library) {
			show_404();
		}
		$user_sekolah = current_user('user_sekolahid');
		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				show_404();
			}
		}
		$input = $this->input->post(NULL, TRUE);
		$rak_name = strtolower(trim($input['rack']));
		$exists_rak = $this->rak->exists_rak($rak_name, $id);
		if ($exists_rak) {
			set_alert('Nama Rak telah terpakai', 'danger');
			back();
		}
		$rak = $this->rak->add($id);
		set_alert('Rak berhasil ditambah', 'success');
		back();
	}

	public function edit_rak($libraryId, $id)
	{
		user_access('edit rak');
		$library = $this->library->get_data($libraryId);
		if (!$library) {
			show_404();
		}
		$user_sekolah = current_user('user_sekolahid');

		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				show_404();
			}
		}
		$rak = $this->rak->get_data($id);
		if (!$rak) {
			show_404();
		}
		$data['library'] = $library;
		$data['rak'] = $rak;
		$data['list_rak'] = $this->rak->data($libraryId, 10000);
		$data['title'] = 'Edit Rak';
		return view('panel.rak.index', $data);
	}

	public function update_rak($libraryId, $id)
	{
		user_access('edit rak');
		form_validate([
			'rack' => 'required',
			'status' => 'required',
		]);
		$library = $this->library->get_data($libraryId);
		if (!$library) {
			show_404();
		}
		$user_sekolah = current_user('user_sekolahid');

		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				show_404();
			}
		}
		$rak = $this->rak->get_data($id);
		if (!$rak) {
			show_404();
		}
		$input = $this->input->post(NULL, TRUE);
		$rak_name = strtolower(trim($input['rack']));
		$exists_rak = $this->rak->exists_rak($rak_name, $libraryId, $id);
		if ($exists_rak) {
			set_alert('Nama Rak telah terpakai', 'danger');
			back();
		}
		$rak = $this->rak->update($id, $libraryId);
		$data['library'] = $library;
		$data['rak'] = $rak;
		$data['list_rak'] = $this->rak->data($libraryId, 10000);
		$data['title'] = 'Edit Rak';
		set_alert('Rak berhasil diubah', 'success');
		redirect(admin_url("perpustakaan/$libraryId/rak"));
	}

	public function delete_rak($libraryId, $id)
	{
		user_access('delete rak');
		$library = $this->rak->get_data($id);
		if (!$library) {
			show_404();
		}
		$library = $this->library->get_data($libraryId);
		if (!$library) {
			show_404();
		}
		$user_sekolah = current_user('user_sekolahid');

		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				show_404();
			}
		}
		$rak = $this->rak->get_data($id);
		if (!$rak) {
			show_404();
		}
		$library = $this->rak->delete($id);
		set_alert('Rak berhasil dihapus', 'success');
		redirect(admin_url("perpustakaan/$libraryId/rak"));
	}


	public function ajax_data($id)
	{
		$data = $this->rak->data($id, 10000,0,'',true);
		echo json_encode($data);
	}
}	
