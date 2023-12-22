<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Dashboard extends Admin_Controller{

	public $previous_week;
	public $today;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Buku_model', 'buku');
		$this->load->model('Issue_model', 'issue');
		$this->load->model('Library_model', 'library');
		$this->load->model('Transaction_model', 'transaction');
		$this->previous_week = date('Y-m-d 00:00:00', strtotime("-1 week +1 day"));
		$this->today = date('Y-m-d 23:59:59', strtotime("today"));
	}

	public function index(){

		// $start_date= $this->previous_week;
		// $end_date= $this->today;
		// $data['total_book']=$this->buku->total_buku_dashboard($start_date,$end_date,'physics',true);
		// $data['total_ebook']=$this->buku->total_buku_dashboard($start_date, $end_date, 'ebook',true);
		// $data['total_pinjam']=$this->issue->total_pinjam_dashboard($start_date, $end_date,true);
		// $data['total_hilang']=$this->transaction->total_keluar_dashboard($start_date, $end_date,true);
		
		// $data['total_book_week']=$this->buku->total_buku_dashboard($start_date,$end_date,'physics',false);
		// $data['total_ebook_week']=$this->buku->total_buku_dashboard($start_date, $end_date, 'ebook',false);
		// $data['total_pinjam_week']=$this->issue->total_pinjam_dashboard($start_date, $end_date,false);
		// $data['total_hilang_week']=$this->transaction->total_keluar_dashboard($start_date, $end_date,false);
		$data['title']='Dashboard';
		return view('panel.dashboard', $data);
	}

	public function total_book(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_dashboard($this->today,$this->previous_week,'physics',true);
		echo json_encode($res);
		return;
	}

	public function total_ebook(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_dashboard($this->today, $this->previous_week, 'ebook', true);
		echo json_encode($res);
		return;
	}

	public function total_pinjam(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->total_pinjam_dashboard($this->today, $this->previous_week, true);
		echo json_encode($res);
		return;
	}

	public function total_keluar(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->transaction->total_keluar_dashboard($this->today, $this->previous_week, true);
		echo json_encode($res);
		return;
	}

	public function total_book_week(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_dashboard($this->today,$this->previous_week,'physics',false);
		echo json_encode($res);
		return;
	}

	public function total_ebook_week(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_dashboard($this->today, $this->previous_week, 'ebook', false);
		echo json_encode($res);
		return;
	}

	public function total_pinjam_week(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->total_pinjam_dashboard($this->today, $this->previous_week, false);
		echo json_encode($res);
		return;
	}

	public function total_keluar_week(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->transaction->total_keluar_dashboard($this->today, $this->previous_week, false);
		echo json_encode($res);
		return;
	}

	public function total_judul(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_judul();
		echo json_encode($res);
		return;
	}

	public function total_judul_item(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_judul(true);
		echo json_encode($res);
		return;
	}

	public function total_buku_koleksi(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->buku->total_buku_koleksi();
		echo json_encode($res);
		return;
	}

	public function total_item_pinjam(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->total_current_issue();
		echo json_encode($res);
		return;
	}

	public function total_item_overdue(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->total_current_overdue();
		echo json_encode($res);
		return;
	}

	public function total_member_issue(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->total_member_issue();
		echo json_encode($res);
		return;
	}

	public function total_member_not_issue(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->total_member_not_issue();
		echo json_encode($res);
		return;
	}

	public function top_member(){
		$res['status'] = true;
		$res['message'] = 'Total Buku';
		$res['data'] = $this->issue->top_member();
		echo json_encode($res);
		return;
	}

	public function top_book(){
		$res['status'] = true;
		$res['message'] = 'Data Buku';
		$res['data'] = $this->issue->top_book();
		echo json_encode($res);
		return;
	}

	public function dashobard_library(){
		$res['status'] = true;
		$res['message'] = 'Data Library';
		$res['data'] = $this->library->data(100000);
		echo json_encode($res);
		return;
	}

	public function statistik_dashboard(){
		$res['status'] = true;
		$res['message'] = 'Data Library';
		$res['data'] = $this->issue->statistic_dashboard();
		echo json_encode($res);
		return;
	}

	public function data_pengunjung_dashboard(){
		$res['status'] = true;
		$res['message'] = 'Data Library';
		$res['data'] = $this->issue->data_pengunjung();
		echo json_encode($res);
		return;
	}

}
