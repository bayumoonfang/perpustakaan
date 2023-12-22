<?php
defined('BASEPATH') or exit('No direct script access allowed');
use chriskacerguis\RestServer\RestController;

class Api_Controller extends RestController
{
	public $code=200;

	public $status=false;

	public $message='success';

	public $data=null;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('apivalidation');
	}

	public function validations(){
		return $this->apivalidation->test();
	}

	public function respond(){
		$respond = [
			'status' => $this->status,
			'message' => $this->message,
		];
		if($this->status){
			$respond['payload']= $this->data;
		}
		return $this->response($respond,$this->code);
	}

	protected function respondSuccess($data = null,$message='Success',$code= RestController::HTTP_OK){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(true);
		$this->set_data($data);
		return $this->respond();
	}

	protected function respondCreated($data = null,$message='Data successfully created',$code= RestController::HTTP_CREATED){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(true);
		$this->set_data($data);
		return $this->respond();
	}

	protected function respondUpdated($data = null,$message='Data successfully updated',$code= RestController::HTTP_OK){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(true);
		$this->set_data($data);
		return $this->respond();
	}

	protected function respondDeleted($data = null,$message='Data successfully deleted',$code= RestController::HTTP_OK){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(true);
		$this->set_data($data);
		return $this->respond();
	}
	
	protected function respondNoContent($data = null,$message='No Content',$code= 204){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(true);
		$this->set_data($data);
		return $this->respond();
	}

	protected function respondError($message='Error',$code= RestController::HTTP_BAD_REQUEST){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(false);
		return $this->respond();
	}

	protected function failNotFound($message='Data not found',$code= RestController::HTTP_NOT_FOUND){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(false);
		return $this->respond();
	}

	protected function failValidationError($message='Missing data required',$code= RestController::HTTP_BAD_REQUEST){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(false);
		return $this->respond();
	}
	
	protected function failUnauthorized($message= 'Unauthorized',$code= RestController::HTTP_UNAUTHORIZED){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(false);
		return $this->respond();
	}

	protected function failForbidden($message= 'Forbidden',$code= RestController::HTTP_FORBIDDEN){
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(false);
		return $this->respond();
	}

	protected function failServerError($message = 'Internal Server Error', $code = RestController::HTTP_INTERNAL_ERROR)
	{
		$this->set_code($code);
		$this->set_message($message);
		$this->set_status(false);
		return $this->respond();
	}

	private function set_code($code){
		$this->code=$code;
		return $this;
	}

	private function set_message($message){
		$this->message= $message;
		return $this;
	}

	private function set_status($status){
		$this->status=$status;
		return $this;
	}

	private function set_data($data){
		$this->data= $data;
		return $this;
	}
}
