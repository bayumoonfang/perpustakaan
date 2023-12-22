<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class BukuMasuk extends Admin_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaction_model', 'transaction');
		$this->load->model('Library_model', 'library');
		$this->load->model('Buku_model', 'book');
		$this->load->model('Penambahan_model', 'penambahan');
	}

	public function index(){
		user_access(['edit transaksi buku masuk', 'add transaksi buku masuk', 'delete transaksi buku masuk', 'view transaksi buku masuk']);
		$search = $this->input->get('s', true);
		$lib_get = $this->input->get('library', true);
		$config = pagination();
		$config['base_url'] = admin_url('transaksi/buku-masuk');
		$total = $this->transaction->total_data('in',$search, $lib_get);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->pagination->initialize($config);
		$data['buku_masuk'] = $this->transaction->data('in',$config['per_page'], $from, $search, $lib_get);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Buku masuk';
		$libs = $this->library->data(10000);
		$data['library'] = $libs;
		$data['selected_lib'] = $lib_get;
		return view('panel.transaksi.buku-masuk.index', $data);
	}

	public function add(){
		user_access(['add transaksi buku masuk']);
		$data['library'] = $this->library->data(10000);
		$data['books'] = [];
		$data['categories'] = [];
		$data['title'] = 'Form Buku Masuk';
		return view('panel.transaksi.buku-masuk.form', $data);
	}

	public function store(){
		user_access(['add transaksi buku masuk']);
		form_validate([
			'library' => 'required',
			'book' => 'required',
			'category' => 'required',
			'qty' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$library_id = $input['library'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			show_404();
		}
		$book_id = $input['book'];
		$book = $this->book->get_data($book_id);
		if (!$book) {
			show_404();
		}
		$category_id = $input['category'];
		$category = $this->penambahan->get_data($category_id);
		if (!$category) {
			show_404();
		}
		$transaction=$this->transaction->add('in');
		set_alert('buku berhasil ditambah', 'success');
		redirect(admin_url('transaksi/buku-masuk/new'));
	}

	public function edit($id){
		user_access(['edit transaksi buku masuk']);
		$transaction = $this->transaction->get_data('in', $id);
		if (!$transaction) {
			show_404();
		}
		$data['library'] = $this->library->data(10000);
		$data['books'] = $this->book->data_per_library($transaction->library);
		$data['categories'] = $this->penambahan->data_per_library($transaction->library);
		$data['buku_masuk'] = $transaction;
		$data['title'] = 'Form Update Buku Masuk';
		return view('panel.transaksi.buku-masuk.form', $data);
	}

	public function update($id){
		user_access(['edit transaksi buku masuk']);
		$transaction = $this->transaction->get_data('in', $id);
		if (!$transaction) {
			show_404();
		}
		form_validate([
			'library' => 'required',
			'book' => 'required',
			'category' => 'required',
			'qty' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$library_id = $input['library'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			show_404();
		}
		$book_id = $input['book'];
		$book = $this->book->get_data($book_id);
		if (!$book) {
			show_404();
		}
		$category_id = $input['category'];
		$category = $this->penambahan->get_data($category_id);
		if (!$category) {
			show_404();
		}
		$transaction = $this->transaction->update('in',$id);
		set_alert('penambahan buku berhasil diubah', 'success');
		redirect(admin_url('transaksi/buku-masuk/'. $id.'/edit'));
	}

	public function delete($id){
		user_access(['delete transaksi buku masuk']);
		$transaction = $this->transaction->get_data('in',$id);
		if (!$transaction) {
			show_404();
		}

		$data = $this->transaction->delete('in',$id);
		if (!$data) {
			set_alert('Gagal menghapus data. Silahkan coba lagi', 'danger');
			back();
		}
		set_alert('Buku masuk berhasil dihapus', 'success');
		redirect(admin_url('transaksi/buku-masuk'));
	}
}
