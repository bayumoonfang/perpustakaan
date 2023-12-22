<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Pengurangan extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengurangan_model', 'pengurangan');
		$this->load->model('library_model', 'library');
	}

	public function index(){
		user_access(['edit pengurangan', 'add pengurangan', 'delete pengurangan', 'view pengurangan']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('pengurangan');
		$total = $this->pengurangan->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['pengurangan'] = $this->pengurangan->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Jenis Pengurangan';
		return view('panel.pengurangan.index', $data);
	}
	public function new(){
		user_access(['add pengurangan']);
		$data['library'] = $this->library->data(10000);
		$data['title'] = 'Tambah Jenis Pengurangan';
		return view('panel.pengurangan.form', $data);
	}
	public function store(){
		user_access(['add pengurangan']);
		form_validate([
			'library' => 'required',
			'title' => 'required',
			'status' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$library_id = $input['library'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			set_alert('Perpustakaan tidak ditemukan', 'danger');
			back();
		}
		$buku = $this->pengurangan->add();
		set_alert('Jenis Pengurangan berhasil ditambah', 'success');
		redirect(admin_url('pengurangan'));
	}
	public function edit($id){
		user_access(['edit pengurangan']);
		$pengurangan = $this->pengurangan->get_data($id);
		if (!$pengurangan) {
			show_404();
		}
		$data['pengurangan'] = $pengurangan;
		$data['library'] = $this->library->data(10000);
		$data['title'] = 'Tambah Jenis Pengurangan';
		return view('panel.pengurangan.form', $data);

	}
	public function update($id){
		user_access(['edit pengurangan']);
		form_validate([
			'library' => 'required',
			'title' => 'required',
			'status' => 'required',
		]);
		$pengurangan = $this->pengurangan->get_data($id);
		if (!$pengurangan) {
			show_404();
		}
		$input = $this->input->post(NULL, TRUE);
		$library_id = $input['library'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			set_alert('Perpustakaan tidak ditemukan', 'danger');
			back();
		}
		$data_pengurangan = $this->pengurangan->update($id);
		set_alert('Jenis Pengurangan berhasil diubah', 'success');
		redirect(admin_url('pengurangan'));

	}
	public function delete($id){
		user_access(['delete pengurangan']);
		$pengurangan = $this->pengurangan->get_data($id);
		if (!$pengurangan) {
			show_404();
		}

		$data_pengurangan = $this->pengurangan->delete($id);
		set_alert('Jenis Pengurangan berhasil dihapus', 'success');
		redirect(admin_url('pengurangan'));
	}
}
