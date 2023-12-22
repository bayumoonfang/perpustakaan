<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class BukuKeluar extends Admin_Controller{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('transaction_model', 'transaction');
		$this->load->model('Library_model', 'library');
		$this->load->model('Buku_model', 'book');
		$this->load->model('pengurangan_model', 'pengurangan');
	}

	public function index(){
		user_access(['edit transaksi buku keluar', 'add transaksi buku keluar', 'delete transaksi buku keluar', 'view transaksi buku keluar']);
		$search = $this->input->get('s', true);
		$lib_get = $this->input->get('library', true);
		$config = pagination();
		$config['base_url'] = admin_url('transaksi/buku-keluar');
		$total = $this->transaction->total_data('out',$search, $lib_get);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->pagination->initialize($config);
		$data['buku_keluar'] = $this->transaction->data('out',$config['per_page'], $from, $search, $lib_get);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Buku keluar';
		$libs = $this->library->data(10000);
		$data['library'] = $libs;
		$data['selected_lib'] = $lib_get;
		return view('panel.transaksi.buku-keluar.index', $data);
	}

	public function add(){
		user_access(['add transaksi buku keluar']);
		$data['library'] = $this->library->data(10000);
		$data['books'] = [];
		$data['categories'] = [];
		$data['title'] = 'Form Buku Keluar';
		return view('panel.transaksi.buku-keluar.form', $data);
	}

	public function store(){
		user_access(['add transaksi buku keluar']);
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
		if(intval($book->qty)<1){
			set_alert('Stok buku yg dipilih kosong', 'danger');
			back();
		}
		$book_issued=$this->book->book_issued($book_id, $library_id);
		if((intval($book->qty) - intval($book_issued))<$input['qty']){
			set_alert('Qty buku yg diinput melebihi stok buku yang ada', 'danger');
			back();
		}
		$category_id = $input['category'];
		$category = $this->pengurangan->get_data($category_id);
		if (!$category) {
			show_404();
		}
		$transaction=$this->transaction->add('out');
		set_alert('buku berhasil dikurangi', 'success');
		redirect(admin_url('transaksi/buku-keluar/new'));
	}

	public function edit($id){
		user_access(['edit transaksi buku keluar']);
		$transaction = $this->transaction->get_data('out', $id);
		if (!$transaction) {
			show_404();
		}
		if($transaction->reff!=null){
			show_404();
		}
		$data['library'] = $this->library->data(10000);
		$data['books'] = $this->book->data_per_library($transaction->library);
		$data['categories'] = $this->pengurangan->data_per_library($transaction->library);
		$data['buku_keluar'] = $transaction;
		$data['title'] = 'Form Update Buku Keluar';
		return view('panel.transaksi.buku-keluar.form', $data);
	}

	public function update($id){
		user_access(['edit transaksi buku keluar']);
		$transaction = $this->transaction->get_data('out', $id);
		if (!$transaction) {
			show_404();
		}
		if ($transaction->reff != null) {
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
		$book_issued = $this->book->book_issued($book_id, $library_id);
		if(((intval($transaction->qty)+intval($book->qty)) - intval($book_issued))<$input['qty']){
			set_alert('Qty buku yg diinput melebihi stok buku yang ada', 'danger');
			back();
		}
		$category_id = $input['category'];
		$category = $this->pengurangan->get_data($category_id);
		if (!$category) {
			show_404();
		}
		$transaction = $this->transaction->update('out',$id);
		set_alert('pengurangan buku berhasil diubah', 'success');
		redirect(admin_url('transaksi/buku-keluar/'. $id.'/edit'));
	}

	public function delete($id){
		user_access(['delete transaksi buku keluar']);
		$transaction = $this->transaction->get_data('out',$id);
		if (!$transaction) {
			show_404();
		}
		if ($transaction->reff != null) {
			show_404();
		}
		$data = $this->transaction->delete('out',$id);
		if (!$data) {
			set_alert('Gagal menghapus data. Silahkan coba lagi', 'danger');
			back();
		}
		set_alert('Buku keluar berhasil dihapus', 'success');
		redirect(admin_url('transaksi/buku-keluar'));
	}
}
