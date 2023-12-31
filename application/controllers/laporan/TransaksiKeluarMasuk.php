<?php (defined('BASEPATH')) or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TransaksiKeluarMasuk extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('addon_model', 'addon');
		$this->load->model('buku_model', 'buku');
		$this->load->model('transaction_model', 'transaction');
		$this->load->model('library_model', 'library');
		user_access(['laporan library']);
	}

	public function index()
	{
		$search = $this->input->get('s', true);
		$cetak = $this->input->get('cetak', true);
		if ($cetak) {
			return $this->export_transaksi($this->input->get(null, true));
		}
		$config = pagination();
		$config['base_url'] = admin_url('laporan/transaksi-buku');
		$total = $this->transaction->total_data_report($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->pagination->initialize($config);
		$data['books'] = $this->transaction->data_report($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$data['title'] = 'Laporan Buku Masuk dan Keluar';
		$libs = $this->library->data(10000);
		$data['library'] = $libs;
		// $data['default_library'] = $this->input->get('library', true);
		$curLib = $this->library->current_user_library();
		$data['default_library'] = $curLib[0]->id;
		$data['default_start'] = $this->input->get('start', true);
		$data['default_end'] = $this->input->get('end', true);
		return view('laporan.buku_masuk_keluar', $data);
	}

	public function export_transaksi($input)
	{
		$data_transaction = $this->transaction->export_transaction($input);

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
		$sheet->setCellValue('A1', "Laporan Buku Masuk dan Keluar"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "TANGGAL"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "BUKU"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('D3', "JENIS"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('E3', "JUMLAH"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('F3', "TIPE"); // Set kolom E3 dengan tulisan "ALAMAT"

		// Apply style header yang telah kita buat tadi ke masing-masing kolom header
		$sheet->getStyle('A3')->applyFromArray($style_col);
		$sheet->getStyle('B3')->applyFromArray($style_col);
		$sheet->getStyle('C3')->applyFromArray($style_col);
		$sheet->getStyle('D3')->applyFromArray($style_col);
		$sheet->getStyle('E3')->applyFromArray($style_col);
		$sheet->getStyle('F3')->applyFromArray($style_col);

		$no = 1; // Untuk penomoran tabel, di awal set dengan 1
		$numrow = 4; // Set baris pertama untuk isi tabel adalah baris ke 4
		foreach ($data_transaction as $item) {

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $item->date ? $item->date : '');
			$sheet->setCellValue('C' . $numrow, $item->book_title ? $item->book_title : '');
			$sheet->setCellValue('D' . $numrow, $item->category_name ? $item->category_name : '');
			$sheet->setCellValue('E' . $numrow, $item->qty ? $item->qty : '');
			$sheet->setCellValue('F' . $numrow, $item->type == 'in' ? 'BUKU MASUK' : 'BUKU KELUAR');

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
		$sheet->getColumnDimension('B')->setWidth(30); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(65); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(30); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('E')->setWidth(20); // Set width kolom E

		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("Laporan Buku Masuk dan Keluar");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Laporan Buku Masuk dan Keluar.xlsx"); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
