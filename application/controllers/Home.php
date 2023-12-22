<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Home extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengunjung_model', 'pengunjung');
		$this->load->model('library_model', 'library');
	}

	// ini adalah dari addons
	public function index()
	{
		$auth_key = $this->input->get('auth', true);
		if ($auth_key) {
			if (is_login()) {
				redirect(admin_url());
			}
			$auth_key = str_replace(' ', '+', $auth_key);
			$this->load->library('encryption');
			// $sample=json_encode(array('user'=>'234'));
			// $decrypt_auth = $this->encryption->encrypt($sample);  
			// var_dump($decrypt_auth);die;
			$decrypt_auth = $this->encryption->decrypt($auth_key);
			// var_dump($decrypt_auth);die;
			if (!$decrypt_auth) {
				set_alert('Authentikasi tidak valid', 'danger');
				redirect(site_url());
			}
			$data_decrypt = json_decode($decrypt_auth, true);
			if (!isset($data_decrypt['user'])) {
				set_alert('User tidak ditemukan', 'danger');
				redirect(site_url());
			}
			if (isset($data_decrypt['source'])) {
				$source = $data_decrypt['source'];
				$this->session->set_userdata(session_prefix() . 'logout_url', $source);
			}
			$this->load->model('user_model', 'user');
			$validate = $this->user->login_ssi($data_decrypt['user']);
			if (is_array($validate) && isset($validate['inactive'])) {
				set_alert('Akun tidak aktif', 'danger');
				redirect(site_url());
			} elseif ($validate == false) {
				set_alert('Authentikasi user tidak valid', 'danger');
				redirect(site_url());
			}
			redirect(admin_url());
		}
		return view('home');
	}

	public function buku_tamu()
	{
		$lib_id = $this->input->get('library', true);
		$cetak = $this->input->get('cetak', true);
		if (!$lib_id) {
			show_404();
		}
		$this->load->library('encryption');
		$this->load->model('library_model', 'library');
		$this->load->model('sekolah_model', 'sekolah');
		$decrypt_id = $this->encryption->decrypt($lib_id);
		if (!$decrypt_id) {
			show_404();
		}
		$library = $this->library->get_data_perpus($decrypt_id);
		if (!$library) {
			show_404();
		}
		$sekolah_detail = $this->sekolah->detail_sekolah($library->school);
		if (!$sekolah_detail) {
			show_404();
		}
		if ($cetak) {
			return $this->export($decrypt_id, $library);
		}

		$data['lib_id'] = $decrypt_id;
		$data['lib_enc'] = $lib_id;
		$data['library_detail'] = $library;
		$data['sekolah_detail'] = $sekolah_detail;
		return view('buku_tamu', $data);
	}

	public function post_buku_tamu()
	{
		form_validate([
			'nis' => 'trim|required',
			'library' => 'required',
			'lib_enc' => 'required',
			'description' => 'required',
		]);
		$this->load->model('pengunjung_model', 'pengunjung');
		$input = $this->input->post(NULL, TRUE);
		$user = $this->pengunjung->cek_user($input['nis']);
		if (!$user) {
			set_alert('Data pengunjung tidak ditemukan', 'danger');
			back();
		}
		$user_library = $this->pengunjung->cek_user_library($user, $input['library']);
		if (!$user_library) {
			set_alert('Data pengunjung tidak ditemukan', 'danger');
			back();
		}
		$this->pengunjung->post_pengunjung($input, $user);
		set_alert('Presensi Berhasil!, Selamat Datang <b>' . $user->user_nama . '</b>', 'success');

		redirect(site_url('buku-tamu?library=' . $input['lib_enc']));
	}


	public function export($lib_id, $libs)
	{
		$data_pengunjung = $this->pengunjung->all_data($lib_id);

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
		$sheet->setCellValue('A1', "Data Pengunjung " . $libs->library); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "NAMA"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "STATUS"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('D3', "NIS / NIP"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('E3', "TANGGAL"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('F3', "JAM"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G3', "KEPERLUAN"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);

		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach ($data_pengunjung as $item) {
			$user_no = $item->user_detail ? $item->user_detail->user_no : '';
			// $user_numeric= is_numeric($item->user_detail ? $item->user_detail->user_no : '');
			// return $user_numeric ? '=TEXT(' . $user_no . ',"0")' : $user_no;
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $item->user_detail ? $item->user_detail->user_nama : '');
			$sheet->setCellValue('C' . $numrow, $item->role_detail ? $item->role_detail->role_name : '');
			$sheet->setCellValue('D' . $numrow, $user_no);
			// $sheet->setCellValue('D' . $numrow, $user_numeric ? '=TEXT('.$user_no.',"0")': $user_no);
			$sheet->setCellValue('E' . $numrow, $item->tanggal);
			$sheet->setCellValue('F' . $numrow, $item->time);
			$sheet->setCellValue('G' . $numrow, $item->description);

			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);

			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
		}
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(10); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(30); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(30); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(25); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('F')->setWidth(25); // Set width kolom E
		$sheet->getColumnDimension('G')->setWidth(100); // Set width kolom E

		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("Data Pengunjung");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Data Pengunjung " . $libs->library . ".xlsx"); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}

	public function post_guest()
	{
		$data_post = json_decode(file_get_contents('php://input'), true);
		$response = $this->pengunjung->post_guest($data_post);
		$res['status'] = false;
		$res['message'] = 'Gagal menyimpan data. silahkan coba lagi';
		if ($response) {
			$res['status'] = true;
			$res['message'] = 'Berhasil Menyimpan Data. Selamat Datang ' . $data_post['nama'];
		}
		echo json_encode($res);
	}
}
