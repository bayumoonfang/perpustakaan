<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Book extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Buku_model', 'buku');
		$this->load->model('Rak_model', 'rak');
		$this->load->model('Library_model', 'library');
		$this->load->model('Kategori_buku_model', 'kategori_buku');
		$this->load->model('Bahasa_model', 'bahasa');
		$this->load->model('Mapel_model', 'mapel');
		$this->load->model('Bentuk_model', 'bentuk_buku');
	}

	public function index()
	{
		user_access(['edit buku', 'add buku', 'delete buku', 'view buku']);
		$search = $this->input->get('s', true);
		$lib_get = $this->input->get('library', true);
		$config = pagination();
		$config['base_url'] = admin_url('buku');
		$total = $this->buku->total_data($search, $lib_get);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['books'] = $this->buku->data($config['per_page'], $from, $search, $lib_get);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$libs = $this->library->data(10000);
		$data['library'] = $libs;
		$data['title'] = 'Buku';
		$data['selected_lib'] = $lib_get;
		return view('panel.buku.index', $data);
	}

	public function new()
	{
		user_access(['add buku']);
		$data['library'] = $this->library->data(10000);
		$data['bahasa'] = $this->bahasa->data();
		$data['rak'] = array();
		$data['kategori_buku'] = array();
		$data['mapel'] = array();
		$data['title'] = 'Tambah Buku';
		return view('panel.buku.form', $data);
	}

	public function store()
	{
		user_access(['add buku']);
		form_validate([
			'code' => 'required',
			'library' => 'required',
			'category' => 'required',
			'rak' => 'required',
			'title' => 'required',
			'author' => 'required',
			'publisher' => 'required',
			'year' => 'required',
			'kolasi' => 'required',
			'isbn' => 'required',
			'status' => 'required',
			'bentuk' => 'required',
			'price' => 'required'
		]);
		if (!empty($_FILES['ebook']['name'])) {
			if (empty($_FILES['cover']['name'])) {
				set_alert('Cover buku wajib diisi jika menambahkan E-book', 'danger');
				back();
			}
		}
		$input = $this->input->post(NULL, TRUE);
		$code = $input['code'];
		$library_id = $input['library'];
		$rak_id = $input['rak'];
		$category_id = $input['category'];
		$bahasa_id = $input['language'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			set_alert('Perpustakaan tidak ditemukan', 'danger');
			back();
		}
		$rak = $this->rak->get_data($rak_id);
		if (!$rak) {
			set_alert('Rak tidak ditemukan', 'danger');
			back();
		}
		$category = $this->kategori_buku->get_data($category_id);
		if (!$category) {
			set_alert('Kategori buku tidak ditemukan', 'danger');
			back();
		}
		$bahasa = $this->bahasa->get_data($bahasa_id);
		if (!$bahasa) {
			set_alert('Bahasa tidak ditemukan', 'danger');
			back();
		}
		$exists_code = $this->buku->exists_code($code, $library_id);
		if ($exists_code) {
			set_alert('Code buku telah terpakai', 'danger');
			back();
		}
		$user_sekolah = current_user('user_sekolahid');
		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				set_alert('Data sekolah tidak ditemukan', 'danger');
				back();
			}
		}

		if (!empty($_FILES['cover']['name'])) {
			$upload_cover = $this->upload_cover();
			if ($upload_cover['error']) {
				set_alert($upload_cover['message'], 'danger');
				back();
			}
		}
		if (!empty($_FILES['ebook']['name'])) {
			$upload_ebook = $this->upload_ebook();
			if ($upload_ebook['error']) {
				set_alert($upload_ebook['message'], 'danger');
				back();
			}
		}

		$buku = $this->buku->add();
		if (!empty($_FILES['cover']['name'])) {
			if (!$upload_cover['error']) {
				$this->buku->update_cover($buku, $upload_cover['data']);
			}
		}
		if (!empty($_FILES['ebook']['name'])) {
			if (!$upload_ebook['error']) {
				$this->buku->update_ebook($buku, $upload_ebook['data']);
			}
		}

		set_alert('Buku berhasil ditambah', 'success');
		redirect(admin_url('buku'));
	}

	public function edit($id)
	{
		user_access(['edit buku']);
		$buku = $this->buku->get_data($id);
		if (!$buku) {
			show_404();
		}

		$data['buku'] = $buku;
		$data['library'] = $this->library->data(10000);
		$data['bahasa'] = $this->bahasa->data();
		$this->load->model('Kelas_model', 'kelas');
		$this->load->model('Mapel_model', 'mapel');
		$data['kelas'] = $this->kelas->data_per_library($buku->library);
		$data['mapel'] = $this->mapel->data_per_library($buku->library);
		$data['rak'] = $this->rak->data($buku->library, 10000);
		$data['kategori_buku'] = $this->kategori_buku->data_per_library($buku->library);
		$data['title'] = 'Update Buku';
		return view('panel.buku.form', $data);
	}

	public function update($id)
	{
		user_access(['edit buku']);
		form_validate([
			'code' => 'required',
			'library' => 'required',
			'category' => 'required',
			'rak' => 'required',
			'title' => 'required',
			'author' => 'required',
			'publisher' => 'required',
			'year' => 'required',
			'isbn' => 'required',
			'status' => 'required',
			'bentuk' => 'required',
			'price' => 'required'
		]);

		$data_buku = $this->buku->get_data($id);
		if (!$data_buku) {
			show_404();
		}
		if (!empty($_FILES['ebook']['name'])) {
			if (empty($_FILES['cover']['name']) && $data_buku->cover == null) {
				set_alert('Cover buku wajib diisi jika menambahkan E-book', 'danger');
				back();
			}
		}
		$input = $this->input->post(NULL, TRUE);
		$code = $input['code'];
		$library_id = $input['library'];
		$rak_id = $input['rak'];
		$category_id = $input['category'];
		$bahasa_id = $input['language'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			set_alert('Perpustakaan tidak ditemukan', 'danger');
			back();
		}
		$rak = $this->rak->get_data($rak_id);
		if (!$rak) {
			set_alert('Rak tidak ditemukan', 'danger');
			back();
		}
		$category = $this->kategori_buku->get_data($category_id);
		if (!$category) {
			set_alert('Kategori buku tidak ditemukan', 'danger');
			back();
		}
		$bahasa = $this->bahasa->get_data($bahasa_id);
		if (!$bahasa) {
			set_alert('Bahasa tidak ditemukan', 'danger');
			back();
		}
		$exists_code = $this->buku->exists_code($code, $library_id, $id);
		if ($exists_code) {
			set_alert('Code buku telah terpakai', 'danger');
			back();
		}
		$user_sekolah = current_user('user_sekolahid');
		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				set_alert('Data sekolah tidak ditemukan', 'danger');
				back();
			}
		}

		if (!empty($_FILES['cover']['name'])) {
			$upload_cover = $this->upload_cover();
			if ($upload_cover['error']) {
				set_alert($upload_cover['message'], 'danger');
				back();
			}
		}
		if (!empty($_FILES['ebook']['name'])) {
			$upload_ebook = $this->upload_ebook();
			if ($upload_ebook['error']) {
				set_alert($upload_ebook['message'], 'danger');
				back();
			}
		}

		$buku = $this->buku->update($id);
		if (!empty($_FILES['cover']['name'])) {
			if (!$upload_cover['error']) {
				$this->buku->update_cover($id, $upload_cover['data']);
			}
		}
		if (!empty($_FILES['ebook']['name'])) {
			if (!$upload_ebook['error']) {
				$this->buku->update_ebook($id, $upload_ebook['data']);
			}
		}

		set_alert('Buku berhasil diubah', 'success');
		redirect(admin_url('buku'));
	}

	public function delete($id)
	{
		user_access(['delete buku']);

		$buku = $this->buku->get_data($id);
		if (!$buku) {
			show_404();
		}

		$buku = $this->buku->delete($id);
		set_alert('Buku berhasil dihapus', 'success');
		redirect(admin_url('buku'));
	}

	public function index_kategori()
	{
		user_access(['edit kategori buku', 'add kategori buku', 'delete kategori buku', 'view kategori buku']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('kategori-buku');
		$total = $this->kategori_buku->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['kategori_buku'] = $this->kategori_buku->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Kategori Buku';
		return view('panel.kategori-buku.index', $data);
	}

	public function new_kategori()
	{
		user_access(['add kategori buku']);
		$data['library'] = $this->library->data(10000);
		$data['title'] = 'Tambah Kategori Buku';
		return view('panel.kategori-buku.form', $data);
	}

	public function add_kategori()
	{
		user_access(['add kategori buku']);
		form_validate([
			'library' => 'required',
			'category' => 'required',
			'status' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$library_id = $input['library'];
		$library = $this->library->get_data($library_id);
		if (!$library) {
			show_404();
		}
		$user_sekolah = current_user('user_sekolahid');
		if (!is_admin()) {
			if ($user_sekolah != $library->school) {
				show_404();
			}
		}
		$category_name = strtolower(trim($input['category']));
		$exists_category = $this->kategori_buku->exists_category($library_id, $category_name);
		if ($exists_category) {
			set_alert('Nama Kategori telah terpakai', 'danger');
			back();
		}
		$sekolah_id = $library->school;
		$category = $this->kategori_buku->add($sekolah_id);
		set_alert('Perpustakaan berhasil ditambah', 'success');
		redirect(admin_url('kategori-buku'));
	}

	public function edit_kategori($id)
	{
		user_access(['edit kategori buku']);
		$kategori_buku = $this->kategori_buku->get_data($id);
		if (!$kategori_buku) {
			show_404();
		}
		$data['library'] = $this->library->data(10000);
		$data['kategori_buku'] = $kategori_buku;
		$data['title'] = 'Edit Kategori Buku';
		return view('panel.kategori-buku.form', $data);
	}

	public function update_kategori($id)
	{
		user_access(['edit kategori buku']);
		form_validate([
			'library' => 'required',
			'category' => 'required',
			'status' => 'required',
		]);
		$kategori_buku = $this->kategori_buku->get_data($id);
		if (!$kategori_buku) {
			show_404();
		}
		$input = $this->input->post(NULL, TRUE);
		$category_name = strtolower(trim($input['category']));
		$exists_category = $this->kategori_buku->exists_category($kategori_buku->library, $category_name, $id);
		if ($exists_category) {
			set_alert('Nama Kategori telah terpakai', 'danger');
			back();
		}
		$kategori_buku = $this->kategori_buku->update($id);
		set_alert('Kategori buku berhasil diubah', 'success');
		redirect(admin_url('kategori-buku'));
	}

	public function delete_kategori($id)
	{
		user_access(['delete kategori buku']);

		$kategori_buku = $this->kategori_buku->get_data($id);
		if (!$kategori_buku) {
			show_404();
		}

		$kategori_buku = $this->kategori_buku->delete($id);
		set_alert('Kategori buku berhasil dihapus', 'success');
		redirect(admin_url('kategori-buku'));
	}

	public function ajax_data($id)
	{
		$data = $this->kategori_buku->data_per_library($id);
		echo json_encode($data);
	}

	public function upload_cover()
	{
		$file_user = str_replace('.', '', current_user());
		$file_names =  strtotime("now") . "-" . $file_user;
		$config['upload_path']          = FCPATH . '/upload/cover/';
		$config['allowed_types']        = 'gif|jpg|jpeg|png|bmp';
		$config['file_name']            = $file_names;
		$config['overwrite']            = true;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$uploading_cover = $this->upload->do_upload('cover');
		$data = array();
		if (!$uploading_cover) {
			$data['message'] = $this->upload->display_errors();
			$data['error'] = true;
		} else {
			$uploaded_data = $this->upload->data();
			$data['data'] = site_url('upload/cover/' . $uploaded_data['orig_name']);
			$data['message'] = 'Upload cover berhasil';
			$data['error'] = false;
		}
		return $data;
	}

	public function upload_ebook()
	{
		$file_user = str_replace('.', '', current_user());
		$file_names =  strtotime("now") . "-" . $file_user;
		$config['upload_path']          = FCPATH . '/upload/ebook/';
		$config['allowed_types']        = 'pdf';
		$config['file_name']            = $file_names;
		$config['overwrite']            = true;
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		$uploading_ebook = $this->upload->do_upload('ebook');
		$data = array();
		if (!$uploading_ebook) {
			$data['message'] = $this->upload->display_errors();
			$data['error'] = true;
		} else {
			$uploaded_data = $this->upload->data();
			$data['data'] = site_url('upload/ebook/' . $uploaded_data['orig_name']);
			$data['message'] = 'Upload Ebook berhasil';
			$data['error'] = false;
		}
		return $data;
	}

	public function search()
	{
		$data_post = json_decode(file_get_contents('php://input'), true);
		$book = $data_post['book'];
		$library = $data_post['library'];
		$data = $this->buku->book_search_ajax($book, $library);
		echo json_encode($data);
	}

	public function book_barcode($id, $library)
	{
		$res['status'] = false;
		$res['message'] = 'Buku tidak ditemukan';

		$book = $this->buku->get_data_book_barcode($id, $library);
		if (!$book) {
			echo json_encode($res);
			return;
		}
		$res['status'] = true;
		$res['message'] = 'Buku ditemukan';
		$res['data'] = $book;
		echo json_encode($res);
	}

	public function book_barcode_list($id, $library)
	{
		$res['status'] = true;
		$res['message'] = 'List Barcode Buku ditemukan';
		$book = $this->buku->get_data_book_barcode_list($id, $library);
		$res['data'] = $book;
		echo json_encode($res);
	}

	public function book_barcode_generate()
	{
		$res['status'] = true;
		$res['message'] = 'List Barcode Buku ditemukan';
		$data_post = json_decode(file_get_contents('php://input'), true);
		$book = $this->buku->generate_book_barcode($data_post);

		$res['data'] = $book;
		echo json_encode($res);
	}
	public function image($value)
	{
		$this->load->library('BarcodeQR');
		$content = $value;
		ob_start();
		QRcode::png($content, false, 12, 3);
		$result_qr_content_in_png = ob_get_contents();
		ob_end_clean();
		// PHPQRCode change the content-type into image/png... we change it again into html
		header("Content-type: text/html");
		$image = 'data:image/png;base64,' . base64_encode($result_qr_content_in_png);
		$img = explode(',', $image, 2)[1];
		$pic = 'data://text/plain;base64,' . $img;
		return $pic;
	}

	public function book_barcode_print()
	{
		$barr = $this->input->get('barcode', true);
		$list_barcode = json_decode($barr, true);
		if (!$list_barcode || !$barr) {
			show_404();
			die;
		}

		$this->load->library('Pdf');
		$pdf = new FPDF('P', 'cm', 'A4');
		$pdf->AddPage();
		$pdf->SetFont('Arial', '', 12);
		$qrcode_width = ($pdf->GetPageWidth() - 2) / 4;
		$counter = 1;
		$counterPage = 1;
		$arrKey = array();
		$pdf->SetAutoPageBreak(false);
		foreach ($list_barcode as $keys => $value) {
			$new_line = 0;
			array_push($arrKey, $value);
			if ($counter % 4 == 0 || $keys === array_key_last($list_barcode)) {
				$new_line = 1;
			}
			$pdf->Cell($qrcode_width, $qrcode_width, $pdf->Image($this->image($value), $pdf->GetX(), $pdf->GetY(), $qrcode_width, $qrcode_width, 'png'), 1, $new_line);
			if ($new_line == 1) {
				foreach ($arrKey as $key => $item) {
					$nl = 0;
					if ($key === array_key_last($arrKey)) {
						$nl = 1;
					}
					$pdf->Cell($qrcode_width, 0.75, $item, 1, $nl, 'C');
				}
			}
			if ($counterPage % 20 == 0) {
				$pdf->AddPage();
			}
			if ($new_line == 1) {
				$arrKey = array();
			}
			$counter++;
			$counterPage++;
		}

		$pdf->Output();
	}

	//Controller Bentuk Pustaka
	public function index_bentuk()
	{
		user_access(['edit bentuk buku', 'add bentuk buku', 'delete bentuk buku', 'view bentuk buku']);
		$search = $this->input->get('s', true);
		$config = pagination();
		$config['base_url'] = admin_url('bentuk-buku');
		$total = $this->bentuk_buku->total_data($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$this->pagination->initialize($config);
		$data['bentuk_buku'] = $this->bentuk_buku->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Bentuk Pustaka Buku';
		return view('panel.bentuk-buku.index', $data);
	}

	public function new_bentuk()
	{
		user_access(['add bentuk buku']);
		$data['library'] = $this->library->data(10000);
		$data['title'] = 'Tambah Bentuk Pustaka Buku';
		return view('panel.bentuk-buku.form', $data);
	}

	public function add_bentuk()
	{
		user_access(['add bentuk buku']);
		form_validate([
			'name' => 'required',
		]);
		$input = $this->input->post(NULL, TRUE);
		$bentuk = strtolower(trim($input['name']));
		$exist_type = $this->bentuk_buku->exist_type($bentuk);
		if ($exist_type) {
			set_alert('Nama Bentuk Pustaka telah terpakai', 'danger');
			back();
		}
		$category = $this->bentuk_buku->add();
		set_alert('Perpustakaan berhasil ditambah', 'success');
		// redirect(admin_url('bentuk-buku'));
		$search = $this->input->get('s', true);
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$config = pagination();
		$data['bentuk_buku'] = $this->bentuk_buku->data($config['per_page'], $from, $search);
		$output = '';
		$no = 1;
		foreach ($data['bentuk_buku'] as $row) {
			$output .= '<tr>
										<td>' . $no++ . '</td>
										<td>' . $row->name . '</td>
										<td>';
			if ($row->status == '1') {
				$output .= '<button class="btn btn-sm btn-success ml-2">Aktif</button>';
			} else {
				$output .= '<button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>';
			};
			$output .= '</td>
			<td>
					<div class="dropdown">
							<button
									class="btn btn-block btn-sm btn-secondary dropdown-toggle"
									type="button" id="dropdownMenuButton"
									data-toggle="dropdown" aria-haspopup="true"
									aria-expanded="false">
									Action <i class="mdi mdi-chevron-down"></i>
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
									style="">';
			if (user_access(['edit bentuk buku'])) {
				$output .= '<a class="dropdown-item"
							href="' . admin_url("bentuk-buku/$row->id/edit") . '">Edit</a>';
			};
			if (user_access(['hapus bentuk buku'])) {
				$output .= '<button data-id="' . $row->id . '" type="button"
						class="dropdown-item button-delete">Hapus</button>';
			};
			$output .=	'</div>
					</div>
			</td>
			</tr>';
		};
		$data = array(
			'table' => $output,
			'bentuk_buku' => $this->bentuk_buku->data($config['per_page'], $from, $search),
		);
		echo json_encode($data);
	}

	public function edit_bentuk($id)
	{
		user_access(['edit bentuk buku']);
		$bentuk_buku = $this->bentuk_buku->get_data($id);
		if (!$bentuk_buku) {
			show_404();
		}
		$data['library'] = $this->library->data(10000);
		$data['bentuk_buku'] = $bentuk_buku;
		$data['title'] = 'Edit Bentuk Pustaka Buku';
		return view('panel.bentuk-buku.form', $data);
	}

	public function update_bentuk($id)
	{
		user_access(['edit bentuk buku']);
		form_validate([
			'library' => 'required',
			'category' => 'required',
			'status' => 'required',
		]);
		$bentuk_buku = $this->bentuk_buku->get_data($id);
		if (!$bentuk_buku) {
			show_404();
		}
		$input = $this->input->post(NULL, TRUE);
		$category_name = strtolower(trim($input['category']));
		$exists_category = $this->bentuk_buku->exists_category($bentuk_buku->library, $category_name, $id);
		if ($exists_category) {
			set_alert('Nama Bentuk Pustaka telah terpakai', 'danger');
			back();
		}
		$bentuk_buku = $this->bentuk_buku->update($id);
		set_alert('Bentuk Pustaka buku berhasil diubah', 'success');
		redirect(admin_url('bentuk-buku'));
	}

	public function delete_bentuk($id)
	{
		user_access(['delete bentuk buku']);

		$bentuk_buku = $this->bentuk_buku->get_data($id);
		if (!$bentuk_buku) {
			show_404();
		}

		$bentuk_buku = $this->bentuk_buku->delete($id);
		set_alert('Bentuk Pustaka buku berhasil dihapus', 'success');
		redirect(admin_url('bentuk-buku'));
	}
}
