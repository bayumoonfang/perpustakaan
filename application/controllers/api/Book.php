<?php (defined('BASEPATH')) or exit('No direct script access allowed');

class Book extends ApiAdmin_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Buku_model', 'buku');
		$this->load->model('Kategori_buku_model', 'category');
		$this->load->model('Bahasa_model', 'bahasa');
	}

	public function index_get()
	{
		$params = $this->get(null, true);
		$params['user'] = $this->_user ?? '0';
		$params['page'] = $params['page'] ?? '1';
		$params['limit'] = $params['limit'] ?? '10';
		$params['search'] = $params['search'] ?? '';
		$params['kelas'] = $params['kelas'] ?? '';
		$params['mapel'] = $params['mapel'] ?? '';
		$params['bahasa'] = $params['bahasa'] ?? '';
		$params['tahun1'] = $params['tahun1'] ?? '';
		$params['tahun2'] = $params['tahun2'] ?? '';
		$params['category'] = $params['category'] ?? '';
		$params['filter'] = $params['filter'] ?? '';
		$data['total'] = $this->buku->api_data_total($params);
		$data['data'] = $this->buku->api_data($params);
		return $this->respondSuccess($data);
	}

	public function book_by_category_get($id)
	{
		$params = $this->get(null, true);
		$params['user'] = $this->_user ?? '0';
		$params['page'] = $params['page'] ?? '1';
		$params['limit'] = $params['limit'] ?? '10';
		$params['search'] = $params['search'] ?? '';
		$data['total'] = $this->buku->api_data_category_total($params, $id);
		$data['data'] = $this->buku->api_data_category($params, $id);
		return $this->respondSuccess($data);
	}

	public function category_get()
	{
		$params = $this->get(null, true);
		$params['user'] = $this->_user ?? '0';
		$params['page'] = $params['page'] ?? '1';
		$params['limit'] = $params['limit'] ?? '10';
		$params['search'] = $params['search'] ?? '';
		$data['total'] = $this->category->api_data_total($params);
		$data['data'] = $this->category->api_data($params);
		return $this->respondSuccess($data);
	}

	public function language_get()
	{
		$data['data'] = $this->bahasa->data();
		return $this->respondSuccess($data);
	}

	public function book_detail_get($id)
	{
		$user = $this->_user ?? '0';
		$data['data'] = $data_detail = $this->buku->api_detail_data($id, $user);
		if (!$data_detail) {
			return $this->failNotFound();
		}
		$this->buku->api_data_action($id, $user, 'view');
		return $this->respondSuccess($data_detail);
	}

	public function book_like_get($id)
	{
		$user = $this->_user ?? '0';
		$this->buku->api_data_action($id, $user, 'like');
		return $this->respondSuccess();
	}

	public function book_data_read_get()
	{
		$params = $this->get(null, true);
		$params['user'] = $this->_user ?? '0';
		$params['page'] = $params['page'] ?? '1';
		$params['limit'] = $params['limit'] ?? '10';
		$data['total'] = $this->buku->api_data_get_action_total($params, 'view');
		$data['data'] = $this->buku->api_data_get_action($params, 'view');
		return $this->respondSuccess($data);
	}

	public function book_data_like_get()
	{
		$params = $this->get(null, true);
		$params['user'] = $this->_user ?? '0';
		$params['page'] = $params['page'] ?? '1';
		$params['limit'] = $params['limit'] ?? '10';
		$data['total'] = $this->buku->api_data_get_action_total($params, 'like');
		$data['data'] = $this->buku->api_data_get_action($params, 'like');
		return $this->respondSuccess($data);
	}

	public function current_user_overview_get()
	{
		$user = $this->_user ?? '0';
		$data['data']['baca'] = $this->buku->api_total_action($user, 'view');
		$data['data']['favorite'] = $this->buku->api_total_action($user, 'like');
		$data['data']['pinjam'] = $this->buku->api_total_issue($user, 'pinjam');
		$data['data']['kembali'] = $this->buku->api_total_issue($user, 'kembali');
		return $this->respondSuccess($data);
	}
}
