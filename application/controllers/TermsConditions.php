<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TermsConditions extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//$this->permissions = $this->backend_lib->control();
		$this->load->model('backend_model');
	}

	public function index()
	{
		//print_r("dashboard/termsconditions");
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"termsconditions");
		$this->load->view("dashboard/termsconditions");
	}

	public function accepttyc()
	{
		$this->backend_model->setUserTerms($this->session->userdata('user_data')['user_uname']);
		redirect(base_url());
	}
}
