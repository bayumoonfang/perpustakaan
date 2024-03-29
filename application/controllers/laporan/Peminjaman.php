<?php (defined('BASEPATH')) or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Peminjaman extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('addon_model', 'addon');
		$this->load->model('buku_model', 'buku');
		$this->load->model('issue_model', 'issue');
		$this->load->model('library_model', 'library');
		user_access(['laporan library']);
	}

	public function index()
	{
		$search = $this->input->get('s', true);
		$cetak = $this->input->get('cetak', true);
		if ($cetak) {
			return $this->export_peminjaman($this->input->get(null, true));
		}
		$config = pagination();
		$config['base_url'] = admin_url('laporan/peminjaman');
		$total = $this->issue->total_data_pinjam($search);
		$config['total_rows'] = $total;
		$from = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
		$this->pagination->initialize($config);
		$data['books'] = $this->issue->data_pinjam($config['per_page'], $from, $search);
		$data['page'] = $this->pagination->create_links();
		$libs = $this->library->data(10000);
		$data['title'] = 'Laporan Peminjaman Buku';
		// $data['default_library'] = $this->input->get('library', true);
		// $data['default_library'] = $this->library->get_data($curLib[0]->id);
		$curLib = $this->library->current_user_library();
		$data['default_library'] = $curLib[0]->id;
		$data['default_start'] = $this->input->get('start', true);
		$data['default_end'] = $this->input->get('end', true);
		$data['default_status'] = $this->input->get('status', true);
		$data['library'] = $libs;

		return view('laporan.peminjaman', $data);
	}

	public function export_peminjaman($input)
	{

		$data_pinjam_buku = $this->issue->all_data_pinjam_buku($input);

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
		$sheet->setCellValue('A1', "Laporan peminjaman Buku"); // Set kolom A1 dengan tulisan "DATA SISWA"
		$sheet->mergeCells('A1:E1'); // Set Merge Cell pada kolom A1 sampai E1
		$sheet->getStyle('A1')->getFont()->setBold(true); // Set bold kolom A1
		// Buat header tabel nya pada baris ke 3
		$sheet->setCellValue('A3', "NO"); // Set kolom A3 dengan tulisan "NO"
		$sheet->setCellValue('B3', "MEMBER"); // Set kolom B3 dengan tulisan "NIS"
		$sheet->setCellValue('C3', "BUKU"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('D3', "TGL PINJAM"); // Set kolom C3 dengan tulisan "NAMA"
		$sheet->setCellValue('E3', "TGL KEMBALI"); // Set kolom D3 dengan tulisan "JENIS KELAMIN"
		$sheet->setCellValue('F3', "STATUS"); // Set kolom E3 dengan tulisan "ALAMAT"
		$sheet->setCellValue('G3', "DENDA"); // Set kolom E3 dengan tulisan "ALAMAT"

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
		$total = 0;
		foreach ($data_pinjam_buku as $item) {

			$sheet->setCellValue('A' . $numrow, $no);
			$sheet->setCellValue('B' . $numrow, $item->user_nama ? $item->user_nama : '');
			$sheet->setCellValue('C' . $numrow, $item->book_title ? $item->book_title : '');
			$sheet->setCellValue('D' . $numrow, $item->issue_date ? $item->issue_date : '');
			$sheet->setCellValue('E' . $numrow, $item->return_date ? $item->return_date : '');
			$sheet->setCellValue('F' . $numrow, ucfirst($item->status));
			$sheet->setCellValue('G' . $numrow, $item->denda ? $item->denda : '0');

			// Apply style row yang telah kita buat tadi ke masing-masing baris (isi tabel)
			$sheet->getStyle('A' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('B' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('C' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('D' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('E' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('F' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
			$sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('_("Rp."* ####_);_("Rp."* "-"??_);_(@_)');

			$no++; // Tambah 1 setiap kali looping
			$numrow++; // Tambah 1 setiap kali looping
			$total += $item->denda ? $item->denda : 0;
		}
		// Set width kolom
		$sheet->getColumnDimension('A')->setWidth(10); // Set width kolom A
		$sheet->getColumnDimension('B')->setWidth(70); // Set width kolom B
		$sheet->getColumnDimension('C')->setWidth(65); // Set width kolom C
		$sheet->getColumnDimension('D')->setWidth(30); // Set width kolom D
		$sheet->getColumnDimension('E')->setWidth(30); // Set width kolom E
		$sheet->getColumnDimension('F')->setWidth(20); // Set width kolom E
		$sheet->getColumnDimension('G')->setWidth(30); // Set width kolom E

		$sheet->mergeCells('A' . $numrow . ':F' . $numrow);
		$sheet->setCellValue('A' . $numrow, "TOTAL");
		$sheet->getStyle('A' . $numrow)->getFont()->setBold(true);
		$sheet->getStyle('A' . $numrow . ':F' . $numrow)->applyFromArray($style_row);
		$sheet->getStyle('A' . $numrow . ':F' . $numrow)->applyFromArray($style_col);

		$sheet->setCellValue('G' . $numrow, $total);
		$sheet->getStyle('G' . $numrow)->getFont()->setBold(true);
		$sheet->getStyle('G' . $numrow)->applyFromArray($style_row);
		$sheet->getStyle('G' . $numrow)->applyFromArray($style_col);
		$sheet->getStyle('G' . $numrow)->getNumberFormat()->setFormatCode('_("Rp."* ####_);_("Rp."* "-"??_);_(@_)');

		// Set height semua kolom menjadi auto (mengikuti height isi dari kolommnya, jadi otomatis)
		$sheet->getDefaultRowDimension()->setRowHeight(-1);
		// Set orientasi kertas jadi LANDSCAPE
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
		// Set judul file excel nya
		$sheet->setTitle("Laporan peminjaman Buku");
		// Proses file excel
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Laporan Peminjaman Buku.xlsx"); // Set nama file excel nya
		header('Cache-Control: max-age=0');
		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
	}
}
