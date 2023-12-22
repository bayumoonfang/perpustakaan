<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Issue extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('library_model', 'library');
		$this->load->model('buku_model', 'book');
		$this->load->model('kategori_buku_model', 'category');
		$this->load->model('pengaturan_peminjaman_model', 'pengaturan_peminjaman');
		$this->load->model('issue_model', 'issue');
		$this->load->model('fine_model', 'fine');
		$this->load->model('pengurangan_model', 'pengurangan');
		$this->load->model('fine_model', 'fine');
		$this->load->model('transaction_model', 'transaction');
		$this->load->model('pengaturan_role_issue_model', 'role_issue');
	}

	public function index()
	{
		user_access(['edit issue', 'add issue', 'delete issue', 'view issue']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('issue');
		$total = $this->issue->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['issues'] = $this->issue->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$tgl_kembali = $this->input->get('due_date', true);
		$data['temp_kembali'] = $tgl_kembali;
		$data['title'] = 'Issue';
		return view('panel.issue.index', $data);
	}

	public function add($id)
	{
		user_access(['add issue']);
		$this->load->model('user_model', 'user');
		$user = $this->user->cek_member($id);
		if (!$user) {
			show_404();
		}
		if (current_user('user_sekolahid') != $user->user_sekolahid) {
			show_404();
		}
		$library = $this->library->current_user_library();
		if (empty($library)) {
			show_404();
		}

		$issue_history = $this->issue->get_user_history($user->user_id, $library);
		$data['library'] = $library;
		$data['user'] = $user;
		$data['history'] = $issue_history;
		$data['title'] = "Issue Buku";
		return view('panel.issue.form', $data);
	}

	public function store()
	{
		user_access(['add issue']);
		$data_post = json_decode(file_get_contents('php://input'), true);
		$this->load->model('user_model', 'user');

		$res['status'] = false;
		$res['message'] = 'Gagal issue buku';
		$res['data'] = null;
		if (!isset($data_post['library'])) {
			$res['message'] = 'Perpustakaan masih kosong';
			echo json_encode($res);
			return;
		}
		if (!isset($data_post['user'])) {
			$res['message'] = 'User/Member masih kosong';
			echo json_encode($res);
			return;
		}
		if (!isset($data_post['book']) || !is_array($data_post['book']) || count($data_post['book']) < 1) {
			$res['message'] = 'Buku masih kosong';
			echo json_encode($res);
			return;
		}
		$id = $data_post['user'];
		$user = $this->user->cek_member($id);
		if (!$user) {
			$res['message'] = 'Member tidak ditemukan';
			echo json_encode($res);
			return;
		}
		if (current_user('user_sekolahid') != $user->user_sekolahid) {
			$res['message'] = 'Member bukan dari sekolah yang sama';
			echo json_encode($res);
			return;
		}

		if (!has_role_issue($user->user_roleid, $data_post['library'])) {
			$res['message'] = 'Role user tidak diizinkan untuk meminjam buku, silahkan hubungi admin untuk membuka role user agar bisa meminjam buku';
			echo json_encode($res);
			return;
		}
		$library = $this->library->current_user_library();
		if (empty($library)) {
			$res['message'] = 'Perpustakaan tidak ditemukan';
			echo json_encode($res);
			return;
		}
		$errLib = 0;
		foreach ($library as $value) {
			if ($value->id != $data_post['library']) {
				$errLib++;
			}
		}

		if ($errLib > 0) {
			$res['message'] = 'Perpustakaan tidak ditemukan untuk operator sekarang';
			echo json_encode($res);
			return;
		}

		$settings = $this->pengaturan_peminjaman->get_detail($data_post['library'], 'library');
		if (!$settings) {
			$res['message'] = 'Pengaturan peminjaman belum diatur untuk perpustakaan ini';
			echo json_encode($res);
			return;
		}

		$history_count = $this->issue->get_count_user_history_by_library($id, $data_post['library']);
		$issue_count = count($data_post['book']);

		if ((intval($issue_count) + intval($history_count)) > intval($settings->jml_pinjam)) {
			$res['message'] = 'Jumlah peminjaman melebihi batas. Maks jumlah peminjaman: ' . $settings->jml_pinjam;
			echo json_encode($res);
			return;
		}
		$data_post['setting'] = $settings;
		$this->issue->store($data_post);
		$res['status'] = true;
		$res['message'] = 'Issue buku berhasil';
		echo json_encode($res);
	}

	public function kembali($id)
	{
		user_access(['edit issue']);
		$detail_issue = $this->issue->get_data($id);
		if (!$detail_issue) {
			show_404();
		}
		$settings = $this->pengaturan_peminjaman->get_detail($detail_issue->library, 'library');
		if (!$settings) {
			set_alert('Pengaturan peminjaman belum diatur untuk perpustakaan ini', 'danger');
			back();
			return;
		}
		$data['expired'] = strtotime($detail_issue->expired_date) <= strtotime(date('Y-m-d 00:00:00')) ? true : false;
		$data['title'] = "Pengembalian Buku";
		$data['issue'] = $detail_issue;
		$data['category'] = $this->pengurangan->data(10000);
		$data['settings'] = $settings;
		return view('panel.issue.kembali', $data);
	}

	public function proses_kembali()
	{
		user_access(['edit issue']);
		$data_post = json_decode(file_get_contents('php://input'), true);
		$res['status'] = false;
		$res['message'] = 'Gagal proses pengembalian buku';
		$res['data'] = null;
		$data_post['status'] = 'kembali';
		if (!isset($data_post['issue']) || $data_post['issue'] == '') {
			$res['message'] = 'Issue id wajib diisi';
			echo json_encode($res);
			return;
		}
		if (!isset($data_post['category']) || $data_post['category'] == '') {
			$res['message'] = 'Jenis pengembalian wajib diisi';
			echo json_encode($res);
			return;
		}
		if (!isset($data_post['book']) || $data_post['book'] == '') {
			$res['message'] = 'Buku id wajib diisi';
			echo json_encode($res);
			return;
		}
		if (!isset($data_post['user']) || $data_post['user'] == '') {
			$res['message'] = 'User id wajib diisi';
			echo json_encode($res);
			return;
		}
		$detail_issue = $this->issue->get_data($data_post['issue']);
		if (!$detail_issue) {
			$res['message'] = 'Data peminjaman tidak ditemukan';
			echo json_encode($res);
			return;
		}
		if ($detail_issue && $detail_issue->status != 'pinjam') {
			$res['message'] = 'Data peminjaman sudah dikembalikan atau tidak ditemukan';
			echo json_encode($res);
			return;
		}
		if ($detail_issue && $detail_issue->book->id != $data_post['book']) {
			$res['message'] = 'Buku id tidak sesuai detail peminjaman yang ada';
			echo json_encode($res);
			return;
		}
		if ($detail_issue && $detail_issue->user->user_id != $data_post['user']) {
			$res['message'] = 'User id tidak sesuai detail peminjaman yang ada';
			echo json_encode($res);
			return;
		}

		if ($data_post['category'] != '0') {
			$detail_category = $this->pengurangan->get_data($data_post['category']);
			if (!$detail_category) {
				$res['message'] = 'Jenis pengembalian tidak ditemukan';
				echo json_encode($res);
				return;
			}
			if (!isset($data_post['fine']) || $data_post['fine'] == '') {
				$res['message'] = 'Jumlah denda wajib diisi';
				echo json_encode($res);
				return;
			}
			$detail_fine = $this->fine->get_issue($detail_issue->id);
			if ($detail_fine) {
				$res['message'] = 'Pembayaran denda pengembalian sudah pernah diproses';
				echo json_encode($res);
				return;
			}
			$data_post['status'] = strtolower($detail_category->title);
		}
		$this->issue->proses_kembali($data_post);
		if ($data_post['category'] != '0') {
			$data_post['library'] = $detail_issue->library;
			$this->fine->proses_kembali($data_post);
			$this->transaction->proses_kembali($data_post);
		}
		$res['status'] = true;
		$res['message'] = 'Proses pengembalian buku berhasil';
		$res['data'] = null;
		echo json_encode($res);
	}

	public function get_user_issue_history($user)
	{
		$library = $this->library->current_user_library();
		if (empty($library)) {
			echo json_encode(array());
			return;
		}
		$issue_history = $this->issue->get_user_history($user, $library);
		echo json_encode($issue_history);
	}

	public function update_duration($id, $expired)
	{
		$data = $this->issue->update_duration($id, $expired);
		$res['status'] = true;
		$res['message'] = 'Proses perpanjangan durasi peminjaman buku berhasil';
		$res['data'] = null;
		echo json_encode($res);
	}
}
