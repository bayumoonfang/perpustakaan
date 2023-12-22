<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class General extends ApiGeneral_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Buku_model', 'buku');
		$this->load->model('Kategori_buku_model', 'category');
		$this->load->model('Bahasa_model', 'bahasa');
	}

    public function get_books_get()
    {
		$params = $this->get(null, true);
		$params['category'] = $params['category'] ?? '';
		$params['page'] = $params['page'] ?? '1';
		$params['search'] = $params['search'] ?? '';
		$params['library'] = $params['library'] ?? '';
		$data['total'] = $this->buku->api_general_data_total($params);
		$data['data'] = $this->buku->api_general_data($params);
		return $this->respondSuccess($data);
    }

	public function get_categories_get(){
		$params = $this->get(null, true);
		$params['library'] = $params['library'] ?? '';
		$params['page'] = $params['page'] ?? '1';
		$params['search'] = $params['search'] ?? '';
		$params['search_book'] = $params['search_book'] ?? '';
		$data['total'] = $this->category->api_general_data_total($params);
		$data['data'] = $this->category->api_general_data($params);
		return $this->respondSuccess($data);
	}

	public function get_mapel_get()
	{
		$data['data'] = $total = $this->category->api_mapel_data($this->_user);
		$data['total'] = count($total);
		return $this->respondSuccess($data);
	}
	public function get_kelas_get()
	{
		$data['data'] = $total = $this->category->api_kelas_data($this->_user);
		$data['total'] = count($total);
		return $this->respondSuccess($data);
	}
}