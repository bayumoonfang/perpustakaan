<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Penambahan extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('penambahan_model', 'penambahan');
		$this->load->model('library_model', 'library');
	}

	public function index(){
		user_access(['edit penambahan', 'add penambahan', 'delete penambahan', 'view penambahan']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('penambahan');
		$total = $this->penambahan->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['penambahan'] = $this->penambahan->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Jenis penambahan';
		return view('panel.penambahan.index', $data);
	}
	public function new(){
		user_access(['add penambahan']);
		$data['library'] = $this->library->data(10000);
		$data['title'] = 'Tambah Jenis penambahan';
		return view('panel.penambahan.form', $data);
	}
	public function store(){
		user_access(['add penambahan']);
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
		$buku = $this->penambahan->add();
		set_alert('Jenis penambahan berhasil ditambah', 'success');
		redirect(admin_url('penambahan'));
	}
	public function edit($id){
		user_access(['edit penambahan']);
		$penambahan = $this->penambahan->get_data($id);
		if (!$penambahan) {
			show_404();
		}
		$data['penambahan'] = $penambahan;
		$data['library'] = $this->library->data(10000);
		$data['title'] = 'Tambah Jenis penambahan';
		return view('panel.penambahan.form', $data);

	}
	public function update($id){
		user_access(['edit penambahan']);
		form_validate([
			'library' => 'required',
			'title' => 'required',
			'status' => 'required',
		]);
		$penambahan = $this->penambahan->get_data($id);
		if (!$penambahan) {
			show_404();
		}
		$input = $this->input->post(NULL, TRUE);
		$library_id = $input['library'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			set_alert('Perpustakaan tidak ditemukan', 'danger');
			back();
		}
		$data_penambahan = $this->penambahan->update($id);
		set_alert('Jenis penambahan berhasil ditambah', 'success');
		redirect(admin_url('penambahan'));

	}
	public function delete($id){
		user_access(['delete penambahan']);
		$penambahan = $this->penambahan->get_data($id);
		if (!$penambahan) {
			show_404();
		}

		$data_penambahan = $this->penambahan->delete($id);
		set_alert('Jenis penambahan berhasil dihapus', 'success');
		redirect(admin_url('penambahan'));
	}
}
