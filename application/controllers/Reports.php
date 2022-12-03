<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->permissions = $this->backend_lib->control();
        if (!$this->session->userdata("user_data")) {
			redirect(base_url());
		}
		$this->load->model("clientes_model");
		$this->load->model("stats_model");
		$this->db->query('SET SESSION sql_mode =
                  REPLACE(REPLACE(REPLACE(
                  @@sql_mode,
                  "ONLY_FULL_GROUP_BY,", ""),
                  ",ONLY_FULL_GROUP_BY", ""),
                  "ONLY_FULL_GROUP_BY", "")');
    }

	public function index()
	{
		if($this->session->userdata('user_data')['user_role'] == 1)
		{
			$data  = array(
				'campaigns' => $this->clientes_model->getClientsCampaigns(), 
				'u_permissions' => $this->permissions 
			);	
		}else
		{
			$data  = array(
				'campaigns' => $this->clientes_model->getClientCampaigns($this->session->userdata('user_data')['user_uname']), 
				'u_permissions' => $this->permissions 
			);
		}
		$this->load->view("report/list",$data);
	}

	/*public function view($user_id){
		print_r($user_id);
		$data  = array(
				'user_id' => $user_id, 
				'u_permissions' => $this->permissions 
			);
		$this->load->view("report/view",$data);
	}*/

	public function view($campaign_id){
		$data  = array(
			'stats' => $this->stats_model->getStats($campaign_id),
			'users' => $this->stats_model->getUsersOfCampaign($campaign_id)
		);
		$this->load->view("report/view",$data);
	}
}