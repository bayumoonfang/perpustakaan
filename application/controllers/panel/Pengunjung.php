<?php (defined('BASEPATH')) or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Pengunjung extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('pengunjung_model', 'pengunjung');
		$this->load->model('library_model', 'library');
		user_access(['laporan library']);
	}

	public function index()
	{
		user_access(['laporan library']);
		$lib_id = $this->input->get('library', true);
		$cetak = $this->input->get('cetak', true);
		$library = $this->library->get_data_perpus($lib_id);
		if ($lib_id && !$library) {
			show_404();
		}
		if ($cetak) {
			return $this->export($lib_id, $library);
		}
		$this->load->library('encryption');
		$config = pagination();
		$config['base_url'] = admin_url('laporan/pengunjung');
		$total = $this->pengunjung->total_data();
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->pagination->initialize($config);
		$data['title'] = 'Laporan Pengunjung';
		$data['pengunjung'] = $this->pengunjung->data($config['per_page'], $from);
		$data['per_pengunjung'] = $this->pengunjung->count_data($config['per_page'], $from);
		$data['page'] = $this->pagination->create_links();
		$curLib = $this->library->current_user_library();
		$data['default_library'] = $curLib[0]->id;
		$data['default_jenis_laporan'] = $this->input->get('jenis_laporan', true);
		$data['default_start'] = $this->input->get('start', true);
		$data['default_end'] = $this->input->get('end', true);
		$libs = $this->library->data(10000);
		foreach ($libs as $key => $item) {
			$libs[$key]->enc = $this->encryption->encrypt($item->id);
		};
		$data['library'] = $libs;
		// $this->session->set_flashdata('input_data', ['s' => $search]);
		return view('panel.pengunjung.index', $data);
	}

	public function export($lib_id, $libs)
	{
		user_access(['laporan library']);
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
		$sheet->setCellValue('D3', "INSTANSI"); // Set kolom C3 dengan tulisan "Instansi"
		$sheet->setCellValue('E3', "NIS / NIP"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('F3', "TANGGAL"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G3', "JAM"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('H3', "KEPERLUAN"); // Set kolom E3 dengan tulisan "ALAMAT"
		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);
		$sheet->getStyle('G3')->applyFromArray($style_col);
		$sheet->getStyle('H3')->applyFromArray($style_col);

		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach ($data_pengunjung as $item) {
			$user_no = $item->user_detail ? $item->user_detail->user_no : '';
			// $user_numeric= is_numeric($item->user_detail ? $item->user_detail->user_no : '');
			// return $user_numeric ? '=TEXT(' . $user_no . ',"0")' : $user_no;
			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $item->user_detail ? $item->user_detail->user_nama : (empty($item->guest_name) ?  'Guest'  : $item->guest_name));
			$sheet->setCellValue('C' . $numrow, $item->role_detail ? $item->role_detail->role_name : 'Guest');
			$sheet->setCellValue('D' . $numrow, $item->user_detail ? $item->user_detail->user_sekolahid : 'Guest');
			$sheet->setCellValue('E' . $numrow, $user_no ? $user_no : '-');
			// $sheet->setCellValue('D' . $numrow, $user_numeric ? '=TEXT('.$user_no.',"0")': $user_no);
			$sheet->setCellValue('F' . $numrow, $item->tanggal);
			$sheet->setCellValue('G' . $numrow, $item->time);
			$sheet->setCellValue('H' . $numrow, $item->description);

			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('H' . $numrow)->applyFromArray($style_row);

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
		$sheet->getColumnDimension('G')->setWidth(25); // Set width kolom E
		$sheet->getColumnDimension('H')->setWidth(50); // Set width kolom E

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
}
