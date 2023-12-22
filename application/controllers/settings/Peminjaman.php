<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Peminjaman extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('library_model', 'library');
		$this->load->model('pengaturan_peminjaman_model', 'pengaturan_peminjaman');
	}

	public function index(){
		user_access(['edit pengaturan peminjaman','view pengaturan peminjaman']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('pengaturan/peminjaman');
		$total = $this->pengaturan_peminjaman->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->pagination->initialize($config);
		$data['pengaturan_peminjaman'] = $this->pengaturan_peminjaman->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$data['title'] = 'Pengaturan Peminjaman';
		return view('panel.pengaturan-peminjaman.index', $data);
	}

	public function edit($id){
		user_access(['edit pengaturan peminjaman']);
		$library = $this->pengaturan_peminjaman->get_data($id);
		if (!$library) {
			show_404();
		}
		$data['pengaturan_peminjaman'] = $library;
		$data['title'] = 'Edit Pengaturan Peminjaman';
		return view('panel.pengaturan-peminjaman.form', $data);
	}

	public function update($id){
		user_access(['edit pengaturan peminjaman']);
		form_validate([
			'hari_pinjam' => 'required',
			'jml_pinjam' => 'required',
			'denda_hari' => 'required',
		]);
		$library = $this->pengaturan_peminjaman->get_data($id);
		if (!$library) {
			show_404();
		}
		$library = $this->pengaturan_peminjaman->update($id);
		set_alert('Pengaturan Peminjaman berhasil diubah', 'success');
		redirect(admin_url('pengaturan/peminjaman'));
	}
}
