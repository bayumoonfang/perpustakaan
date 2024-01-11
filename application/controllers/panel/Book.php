<?php (defined('BASEPATH')) or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
		$this->load->model('Subjek_model', 'subjek_buku');
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
		$data['kategori_buku'] = $this->kategori_buku->data();
		$data['subjek_buku'] = $this->subjek_buku->data();
		$data['bentuk_buku'] = $this->bentuk_buku->data();
		return view('panel.buku.index', $data);
	}

	public function new()
	{
		user_access(['add buku']);
		$data['library'] = $this->library->data(10000);
		$data['bahasa'] = $this->bahasa->data();
		$data['rak'] = array();
		$data['kategori_buku'] = array();
		$data['subjek_buku'] = $this->subjek_buku->data();
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
		$data['subjek_buku'] = $this->subjek_buku->data();
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
		// user_access(['edit bentuk buku', 'add bentuk buku', 'delete bentuk buku', 'view bentuk buku']);
		// $search = $this->input->get('s', true);
		// $config = pagination();
		// $config['base_url'] = admin_url('bentuk-buku');
		// $total = $this->bentuk_buku->total_data($search);
		// $config['total_rows'] = $total;
		// $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		// $this->pagination->initialize($config);
		// $data['bentuk_buku'] = $this->bentuk_buku->data($config['per_page'], $from, $search);
		// $data['page'] = $this->pagination->create_links();
		// $this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Bentuk Pustaka Buku';
		return view('panel.bentuk-buku.index', $data);
	}

	public function daftar_bentuk()
	{
		header('Content-Type: application/json');
		$list = $this->bentuk_buku->get_datatables();
		$data = array();
		$no = $this->input->post('start');
		//looping data mahasiswa
		foreach ($list as $data_bentuk) {
			$no++;
			$status = '';
			$row = array();
			//Status Declaration
			if ($data_bentuk->status == '1') {
				$status = '<button class="btn btn-sm btn-success ml-2">Aktif</button>';
			} else {
				$status = '<button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>';
			};
			//Button Action Declaration
			if (user_can(['edit bentuk buku', 'delete bentuk buku'])) {
				$action = '<div class="dropdown">
														<button class="btn btn-block btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Action <i class="mdi mdi-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">';
				if (user_can('edit kategori buku')) {
					$action .=
						'<a class="dropdown-item button-edit" data-id="' . $data_bentuk->id . '">Edit</a>';
				}
				if (user_can('delete kategori buku')) {
					$action .= '<button data-id="' . $data_bentuk->id . '"  data-name="' . $data_bentuk->name . '" type="button" class="dropdown-item button-delete">Hapus</button>';
				};
				$action .= '</div>
					</div>';
			} else {
				$action = '-';
			}


			$row[] = $no;
			$row[] = $data_bentuk->name;
			$row[] = $status;
			$row[] = $action;
			// $row[] =  '<a class="btn btn-success btn-sm"><i class="fa fa-edit"></i> </a>
			//       <a class="btn btn-danger btn-sm "><i class="fa fa-trash"></i> </a>';
			$data[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->bentuk_buku->count_all(),
			"recordsFiltered" => $this->bentuk_buku->count_filtered(),
			"data" => $data,
		);
		//output to json format
		$this->output->set_output(json_encode($output));
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
			// set_alert('Nama Bentuk Pustaka telah terpakai', 'danger');
			echo json_encode(['icon' => 'error', 'message' => 'Nama Bentuk Pustaka telah terpakai', 'status' => 'Gagal'], 200);
			return false;
			// back();
		}
		$category = $this->bentuk_buku->add();
		// set_alert('Bentuk Pustaka berhasil ditambah', 'success');
		// redirect(admin_url('bentuk-buku'));
		$search = $this->input->get('s', true);
		$from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$config = pagination();
		$data['bentuk_buku'] = $this->bentuk_buku->data($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$output = '';
		$no = 1;
		foreach ($data['bentuk_buku'] as $row) {
			$output .= '<tr>
										<td>' . $no++ . '</td>
										<td><strong>' . $row->name . '</strong></td>
										<td>';
			if ($row->status == '1') {
				$output .= '<button class="btn btn-sm btn-success ml-2">Aktif</button>';
			} else {
				$output .= '<button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>';
			};
			$output .= '</td>';
			if (user_access(['edit bentuk buku', 'delete bentuk buku'])) {
				$output .=
					'<td>
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
			</td>';
			} else {
				$output .= '<td></td>';
			}
			$output .= '</tr>';
		};
		$data = array(
			'table' => $output,
			'bentuk_buku' => $this->bentuk_buku->data($config['per_page'], $from, $search),
			'page' => $data['page'],
			'message' => 'Bentuk Pustaka berhasil ditambah',
			'status' => 'Berhasil',
			'icon' => 'success'
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

	public function get_bentuk_by_id()
	{
		$id = $this->input->post('id');
		$get = $this->bentuk_buku->get_by_id($id);
		$data = array('success' => false);
		if ($get) {
			$data = array('success' => true, 'data' => $get);
			echo json_encode($data);
		}
	}

	public function update_bentuk($id)
	{
		$update = $this->bentuk_buku->update($id);
		if ($update) {
			$data = array(
				'icon' => 'success',
				'status' => 'Berhasil',
				'message' => 'Nama Bentuk telah di update',
				'success' => $update
			);
		} else {
			$data = array(
				'icon' => 'error',
				'status' => 'Gagal',
				'message' => 'Nama Bentuk gagal di update',
				'success' => false
			);
		}

		echo json_encode($data);
	}

	public function delete_bentuk($id)
	{
		$delete = $this->bentuk_buku->delete($id);
		if ($delete == true) {
			$data = array(
				'icon' => 'success',
				'status' => 'Berhasil',
				'message' => 'Bentuk berhasil dihapus',
				'success' => $delete
			);
		} else {
			$data = array(
				'icon' => 'error',
				'status' => 'Gagal',
				'message' => 'Bentuk gagal dihapus',
				'success' => $delete
			);
		}

		echo json_encode($data);
	}

	//Controller Bentuk Pustaka
	public function index_subjek()
	{
		// user_access(['edit bentuk buku', 'add bentuk buku', 'delete bentuk buku', 'view bentuk buku']);
		// $search = $this->input->get('s', true);
		// $config = pagination();
		// $config['base_url'] = admin_url('bentuk-buku');
		// $total = $this->subjek_buku->total_data($search);
		// $config['total_rows'] = $total;
		// $from = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		// $this->pagination->initialize($config);
		// $data['subjek_buku'] = $this->subjek_buku->data($config['per_page'], 0, $search);
		// $data['page'] = $this->pagination->create_links();
		// $this->session->set_flashdata('input_data', ['s' => $search]);
		$data['title'] = 'Klasifikasi dan Subjek';
		return view('panel.subjek-buku.index', $data);
	}


	public function daftar_subjek()
	{
		header('Content-Type: application/json');
		$list = $this->subjek_buku->get_datatables();
		$data = array();
		$no = $this->input->post('start');
		//looping data mahasiswa
		foreach ($list as $data_subjek) {
			$no++;
			$status = '';
			$action = '';
			$row = array();
			//Status Declaration
			if ($data_subjek->status == '1') {
				$status = '<button class="btn btn-sm btn-success ml-2">Aktif</button>';
			} else {
				$status = '<button class="btn btn-sm btn-danger ml-2">Tidak Aktif</button>';
			};
			//Button Action Declaration
			if (user_can(['edit subjek buku', 'delete subjek buku'])) {
				$action = '<div class="dropdown">
														<button class="btn btn-block btn-sm btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Action <i class="mdi mdi-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="">';
				if (user_can('edit kategori buku')) {
					$action .=
						'<a class="dropdown-item button-edit" data-id="' . $data_subjek->id . '">Edit</a>';
				}
				if (user_can('delete kategori buku')) {
					$action .= '<button data-id="' . $data_subjek->id . '"  data-name="' . $data_subjek->name . '" type="button" class="dropdown-item button-delete">Hapus</button>';
				};
				$action .= '</div>
				</div>';
			} else {
				$action = '-';
			}


			$row[] = $no;
			$row[] = $data_subjek->min_value . " - " . $data_subjek->max_value;
			$row[] = $data_subjek->name;
			$row[] = $status;
			$row[] = $action;
			// $row[] =  '<a class="btn btn-success btn-sm"><i class="fa fa-edit"></i> </a>
			//       <a class="btn btn-danger btn-sm "><i class="fa fa-trash"></i> </a>';
			$data[] = $row;
		}
		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->subjek_buku->count_all(),
			"recordsFiltered" => $this->subjek_buku->count_filtered(),
			"data" => $data,
		);
		//output to json format
		$this->output->set_output(json_encode($output));
	}

	public function add_subjek()
	{
		$insert = $this->subjek_buku->add();
		$data = array(
			'icon' => 'success',
			'status' => 'Berhasil',
			'message' => 'Nama Subjek telah ditambah',
			'data' => $insert
		);
		echo json_encode($data);
	}

	public function get_subjek_by_id()
	{
		$id = $this->input->post('id');
		$get = $this->subjek_buku->get_by_id($id);
		$data = array('success' => false);
		if ($get) {
			$data = array('success' => true, 'data' => $get);
			echo json_encode($data);
		}
	}

	public function update_subjek($id)
	{
		$update = $this->subjek_buku->update($id);
		if ($update) {
			$data = array(
				'icon' => 'success',
				'status' => 'Berhasil',
				'message' => 'Nama Subjek telah di update',
				'success' => $update
			);
		} else {
			$data = array(
				'icon' => 'error',
				'status' => 'Gagal',
				'message' => 'Nama Subjek gagal di update',
				'success' => false
			);
		}

		echo json_encode($data);
	}

	public function delete_subjek($id)
	{
		$delete = $this->subjek_buku->delete($id);
		if ($delete == true) {
			$data = array(
				'icon' => 'success',
				'status' => 'Berhasil',
				'message' => 'Subjek berhasil dihapus',
				'success' => $delete
			);
		} else {
			$data = array(
				'icon' => 'error',
				'status' => 'Gagal',
				'message' => 'Subjek gagal dihapus',
				'success' => $delete
			);
		}

		echo json_encode($data);
	}


	//Ekspor impor Buku
	public function export_excel_buku()
	{
		$data_buku = $this->buku->export_excel_master_buku();
		// var_dump($data_buku);
		// exit();

		// $get_header = $this->buku->header_excel_buku();
		// foreach ($get_header as $row) {
		// 	$res_header = array_keys($row);
		// 	// foreach ($i as $field) {
		// 	// 	echo $field;
		// 	// }
		// }
		// $fields = array_keys($header);
		// print_r($i);
		// exit();
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Buat sebuah variabel untuk menampung pengaturan style dari header tabel
		$style_col = [
			'font' => ['bold' => true], // Set font nya jadi bold
			'alignment' => [
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Set text jadi ditengah secara horizontal (center)
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		];
		// Buat sebuah variabel untuk menampung pengaturan style dari isi tabel
		$style_row = [
			'alignment' => [
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER // Set text jadi di tengah secara vertical (middle)
			],
			'borders' => [
				'top' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border top dengan garis tipis
				'right' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],  // Set border right dengan garis tipis
				'bottom' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN], // Set border bottom dengan garis tipis
				'left' => ['borderStyle'  => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] // Set border left dengan garis tipis
			]
		];
		$sheet->setCellValue('A1', "Master Buku"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1

		// $col = 3;
		// foreach ($res_header as $header) {
		// 	$sheet->setCellValue('A' . $col, $header); // Set kolom A3 dengan tulisan "NO"
		// 	$sheet->getStyle('A' . $col)->applyFromArray($style_col);
		// 	$col++;
		// }
		// Buat header tabel nya pada baris ke 3
		$rowheader = 3;
		$sheet->setCellValue('A' . $rowheader, "Code"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B' . $rowheader, "Barcode"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('C' . $rowheader, "Sekolah"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('D' . $rowheader, "Kelas"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('E' . $rowheader, "Mapel"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('F' . $rowheader, "Perpustakaan"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G' . $rowheader, "Rak"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('H' . $rowheader, "Kategori"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('I' . $rowheader, "Subjek"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('J' . $rowheader, "Judul"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('L' . $rowheader, "Pengarang"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('M' . $rowheader, "Penerbit"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('N' . $rowheader, "Tahun"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('O' . $rowheader, "Kolasi"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('P' . $rowheader, "ISBN"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('Q' . $rowheader, "Bentuk"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('R' . $rowheader, "Cover"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('S' . $rowheader, "File Url"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('T' . $rowheader, "Bahasa"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('U' . $rowheader, "Jumlah"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('V' . $rowheader, "Jumlah"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('W' . $rowheader, "Ebook"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('B' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('C' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('D' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('E' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('F' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('G' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('H' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('I' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('J' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('K' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('L' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('M' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('N' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('O' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('P' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('Q' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('R' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('S' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('T' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('U' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('V' . $rowheader)->applyFromArray($style_col);
		$sheet->getStyle('W' . $rowheader)->applyFromArray($style_col);

		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach ($data_buku as $item) {

			$sheet->setCellValue('A' . $numrow, $item->code ? $item->code : '-');
			$sheet->setCellValue('B' . $numrow, $item->barcode ? $item->barcode : '-');
			$sheet->setCellValue('C' . $numrow, $item->school ? $item->school : 0);
			$sheet->setCellValue('D' . $numrow, $item->class ? $item->class : 0);
			$sheet->setCellValue('E' . $numrow, $item->mapel ? $item->mapel : '');
			$sheet->setCellValue('F' . $numrow, $item->library ? $item->library : 0);
			$sheet->setCellValue('G' . $numrow, $item->rak ? $item->rak : 0);
			$sheet->setCellValue('H' . $numrow, $item->category ? $item->category : 0);
			$sheet->setCellValue('I' . $numrow, $item->title ? $item->title : '');
			$sheet->setCellValue('J' . $numrow, $item->author ? $item->author : '');
			$sheet->setCellValue('K' . $numrow, $item->publisher ? $item->publisher : '');
			$sheet->setCellValue('L' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('M' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('N' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('O' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('P' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('Q' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('R' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('S' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('T' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('U' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('V' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');
			$sheet->setCellValue('W' . $numrow, $item->books_subjectid ? $item->books_subjectid : '');


			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);

			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(10); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(50); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(20); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(20); // Set width kolom D

		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("Master Buku");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Master Buku.xlsx"); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function template_excel_buku()
	{
		$data_template = $this->buku->template_excel_master_buku();

		$spreadsheet = new Spreadsheet();

		$sheet = $spreadsheet->getActiveSheet();


		$rowheader = 1;
		$sheet->setCellValue('A' . $rowheader, "Judul");
		$sheet->setCellValue('B' . $rowheader, "Bentuk");
		$sheet->setCellValue('D' . $rowheader, "ISBN");
		$sheet->setCellValue('E' . $rowheader, "Penerbit");
		$sheet->setCellValue('F' . $rowheader, "Tahun");
		$sheet->setCellValue('G' . $rowheader, "Kolasi");
		$sheet->setCellValue('I' . $rowheader, "Call");
		$sheet->setCellValue('J' . $rowheader, "Bahasa");
		$sheet->setCellValue('K' . $rowheader, "Tempat");
		$sheet->setCellValue('L' . $rowheader, "Klasifikasi");
		$sheet->setCellValue('N' . $rowheader, "Image Cover");
		$sheet->setCellValue('O' . $rowheader, "Pengarang");
		$sheet->setCellValue('R' . $rowheader, "Barcode");


		$numrow = 2; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach ($data_template as $item) {

			$sheet->setCellValue('A' . $numrow, $item->title ? $item->title : '-');
			$sheet->setCellValue('B' . $numrow, $item->bentuk ? $item->bentuk : '-');
			$sheet->setCellValue('D' . $numrow, $item->isbn ? $item->isbn : 0);
			$sheet->setCellValue('E' . $numrow, $item->publisher ? $item->publisher : '');
			$sheet->setCellValue('F' . $numrow, $item->year ? $item->year : '');
			$sheet->setCellValue('G' . $numrow, $item->kolasi ? $item->kolasi : 0);
			$sheet->setCellValue('I' . $numrow, $item->call ? $item->call : 0);
			$sheet->setCellValue('J' . $numrow, $item->bahasa_name ? $item->bahasa_name : 0);
			$sheet->setCellValue('K' . $numrow, $item->rak ? $item->rak : '');
			$sheet->setCellValue('L' . $numrow, $item->klasifikasi ? $item->klasifikasi : '');
			$sheet->setCellValue('N' . $numrow, $item->cover ? $item->cover : '');
			$sheet->setCellValue('O' . $numrow, $item->author ? $item->author : '');
			$sheet->setCellValue('R' . $numrow, $item->barcode ? $item->barcode : '');

			$numrow++; // Tambah 1 setiap kali looping
		}

		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("Master Buku");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Template.xlsx"); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function import_excel_buku()
	{
		if (isset($_FILES["fileExcel"]["name"])) {
			$path = $_FILES["fileExcel"]["tmp_name"];
			echo $path;
		}
	}
}
