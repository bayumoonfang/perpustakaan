<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Library extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('library_model', 'library');
		$this->load->model('rak_model', 'rak');
	}

	public function index()
	{
		user_access(['edit perpustakaan', 'add perpustakaan', 'delete perpustakaan', 'view perpustakaan']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('perpustakaan');
		$total = $this->library->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['library'] = $this->library->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Perpustakaan';
		return view('panel.perpustakaan.index', $data);
	}

	public function new()
	{
		user_access('add perpustakaan');
		$this->load->model('sekolah_model', 'sekolah');
		$data['schools'] = $this->sekolah->data(1000);
		$data['title'] = 'Tambah Perpustakaan';
		return view('panel.perpustakaan.form', $data);
	}

	public function store()
	{
		user_access('add perpustakaan');
		form_validate([
			'library' => 'required',
			'school' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$library_name = strtolower(trim($input['library']));
		$exists_library = $this->library->exists_library($input['school'], $library_name);
		if ($exists_library) {
			set_alert('Nama perpustakaan telah terpakai', 'danger');
			back();
		}
		$library = $this->library->add();
		set_alert('Perpustakaan berhasil ditambah', 'success');
		redirect(admin_url('perpustakaan'));
	}

	public function edit($id)
	{
		user_access('edit perpustakaan');
		$library = $this->library->get_data($id);
		if (!$library) {
			show_404();
		}
		$this->load->model('sekolah_model', 'sekolah');
		$data['library'] = $library;
		$data['schools'] = $this->sekolah->data(1000);
		$data['title'] = 'Edit Perpustakaan';
		return view('panel.perpustakaan.form', $data);
	}

	public function update($id)
	{
		user_access('edit perpustakaan');
		form_validate([
			'library' => 'required',
			'school' => 'required',
		]);
		$library = $this->library->get_data($id);
		if (!$library) {
			show_404();
		}

		$input = $this->input->post(NULL, TRUE);
		$library_name = strtolower(trim($input['library']));
		$exists_library = $this->library->exists_library($input['school'], $library_name, $id);
		if ($exists_library) {
			set_alert('Nama perpustakaan telah terpakai', 'danger');
			back();
		}
		$library = $this->library->update($id);
		set_alert('Perpustakaan berhasil diubah', 'success');
		redirect(admin_url('perpustakaan'));
	}

	public function delete($id)
	{
		user_access('delete perpustakaan');
		$library = $this->library->get_data($id);
		if (!$library) {
			show_404();
		}
		$library = $this->library->delete($id);
		set_alert('Perpustakaan berhasil dihapus', 'success');
		redirect(admin_url('perpustakaan'));
	}

	public function kelas_ajax_data($id)
	{
		$this->load->model('Kelas_model', 'kelas');
		$data = $this->kelas->data_per_library($id);
		echo json_encode($data);
	}
	public function mapel_ajax_data($id)
	{
		$this->load->model('Mapel_model', 'mapel');
		$data = $this->mapel->data_per_library($id);
		echo json_encode($data);
	}
	public function member_ajax_data($member)
	{
		$search = str_replace('%20', ' ', $member);
		$this->load->model('user_model', 'user');
		$data = $this->user->get_member($search);
		echo json_encode($data);
	}
	public function book_ajax_data($id)
	{
		$this->load->model('buku_model', 'buku');
		$data = $this->buku->data_per_library($id);
		echo json_encode($data);
	}

	public function category_buku_masuk_ajax_data($id)
	{
		$this->load->model('penambahan_model', 'penambahan');
		$data = $this->penambahan->data_per_library($id);
		echo json_encode($data);
	}

	public function category_buku_keluar_ajax_data($id)
	{
		$this->load->model('pengurangan_model', 'pengurangan');
		$data = $this->pengurangan->data_per_library($id);
		echo json_encode($data);
	}
}
