<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		//$this->permissions = $this->backend_lib->control();
		$this->load->model('backend_model');
		$this->load->model("clientes_model");
		$this->load->model("campaigns_model");
		$this->load->model("users_model");
		$this->load->model("bingo_model");
		$this->db->query('SET SESSION sql_mode =
                  REPLACE(REPLACE(REPLACE(
                  @@sql_mode,
                  "ONLY_FULL_GROUP_BY,", ""),
                  ",ONLY_FULL_GROUP_BY", ""),
                  "ONLY_FULL_GROUP_BY", "")');
	}
	public function index()
	{
		if($this->input->get('uid') == null)
		{
		  	redirect(base_url());
		}
		$id = $this->input->get('uid');
		$client = $this->clientes_model->getClient($id);
		if(empty($client))
		{
	  		redirect(base_url());
	  	}
		//$this->getGraphData();
		$data  = array(
			'uid' => $id,
			'role' => $client->role,
			'iframe' => $this->bingo_model->getRoomIframe(get_set_id())
			//'permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($id,"dashboard");
		$this->load->view("dashboard/dashboard", $data);
		/*$data  = array(
			'users' => $this->clientes_model->getUsers(), 
			'u_permissions' => $this->permissions 
		);
		$this->load->view("admin/usuarios/list",$data);*/
		
	}

	public function alterno()
	{
		$data  = array(
			'iframe' => $this->bingo_model->getRoomIframe(get_set_id()),
			'channel' => $this->bingo_model->getRoomChannel(get_set_id())
			//'permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"alterno");
		$this->load->view("dashboard/alterno", $data);
	}
	
	public function termsconditions()
	{
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"termsconditions");
		$this->load->view("dashboard/termsconditions");

	}

	public function dobingo()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		date_default_timezone_set("America/Bogota");

		$timestamp = date("Y-m-d H:i:s");
		$id = test_input($this->input->post('id'));
		$sid = test_input($this->input->post('sid'));
		$card = test_input($this->input->post('card'));
		$setid = get_set_id();
        $this->backend_model->setUserActivity($id);
		$already = $this->bingo_model->getMessage($sid, $id, $card);
		if($already == null)
		{
			$wins = check_table($id, $card);
			if($sid == $setid && sizeof($wins) > 0 )
			{
				$data  = array(
					'user_id' => $id,
					'room' => $sid,
					'timestamp' => $timestamp,
					'card' => $card,
					'patterns' => json_encode($wins),
					'status' => '0' 
				);
				$msgs = $this->bingo_model->saveMessages($data);
				//$query3    = "INSERT INTO messages values (NULL,'$id','$sid','$timestamp','".$card."','".json_encode($wins)."','0')";
				//$con       = $mysqli->query($query3) or die($mysql->error);
				////$row        = mysqli_fetch_array($con);
			}else
			{
				$data  = array(
					'user_id' => $id,
					'room' => $sid,
					'timestamp' => $timestamp,
					'card' => $card,
					'status' => '3' 
				);
				$msgs = $this->bingo_model->saveMessages($data);
			}
			$dato_json = array('response' => "Ready");
			$dato_json['wins'] = $wins;
			echo json_encode($dato_json);
		}else
		{
			$data  = array(
				'user_id' => $id,
				'room' => $sid,
				'timestamp' => $timestamp,
				'card' => $card,
				'status' => '4' 
			);
			$msgs = $this->bingo_model->saveMessages($data);
			$dato_json = array('response' => "Already");
			echo json_encode($dato_json);
		}
	}

	public function cleanbingo()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		date_default_timezone_set("America/Bogota");
		$id = test_input($this->input->post('id'));
		$sid = test_input($this->input->post('sid'));
		$card = test_input($this->input->post('card'));
        $this->backend_model->setUserActivity($id);
        $this->logs_model->logMessage("info","Usuario ".$id." ha limpiado tabla ".$card." en el bingo ".$sid);
		$dato_json = array('response' => "Clean OK");
		echo json_encode($dato_json);
	}
	
	public function checkbingo()
	{
		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		//$sid = test_input($_POST['sid']);
		$setid = get_set_id();
		//print_r($setid);
		//$query3    = "INSERT INTO messages values (NULL,'$id','$timestamp','0')";
		//$con       = $mysqli->query($query3) or die($mysql->error);
		////$row        = mysqli_fetch_array($con);

		$dato_json = array('sid' => $setid);
		echo json_encode($dato_json);
	}
	public function logactivity()
	{
		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		$id = test_input($this->input->post('id'));
        $this->backend_model->setUserActivity($id);

		$dato_json = array('response' => "ok");
		echo json_encode($dato_json);
	}
	public function log_e()
	{
		$this->outh_model->CSRFVerify();

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		$uid = test_input($this->input->post('id'));
		$jqxhr = test_input($this->input->post('jqxhr'));
		$text = test_input($this->input->post('text'));
		$place = test_input($this->input->post('place'));
		$this->logs_model->logError($uid, $jqxhr, $text, $place);
		
		$dato_json = array('response' => "ok");
		echo json_encode($dato_json);
	}
	/*public function getGraphData()
	{

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		if($this->session->userdata('user_data')['user_role'] == 1)
		{
			$userscampaigns = $this->campaigns_model->getUsersByCampaign();
		}else
		{
			$ucs = $this->clientes_model->getClientCampaigns($this->session->userdata('user_data')['user_uname']);
			$campaigns = array();
			foreach ($ucs as $uc) {
				array_push($campaigns, $uc->campaign_id);
			}
			$userscampaigns = $this->campaigns_model->getUsersByCampaignOfClient($campaigns);
		}
		//print_r($userscampaigns);
		$campaigns = array();
		$users = array();
		foreach ($userscampaigns as $cpgn) {
			array_push($campaigns, $cpgn->campaign_name);
			array_push($users, (int)$cpgn->total_users);
		}
		$data = array('xData' => $campaigns, 'yData' => $users );
		//print_r($data);
		echo json_encode($data);
	}*/
}