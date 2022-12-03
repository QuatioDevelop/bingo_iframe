<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->permissions = $this->backend_lib->control();
        if (!$this->session->userdata("user_data")) {
			redirect(base_url());
		}
		$this->load->model("clientes_model");
    }

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$set = get_set_id();
		$data  = array(
			'clients' => $this->clientes_model->getClients($set), 
			'gen_clients' => $this->clientes_model->getGenClients($set,'0'),
			'total_clients' => $this->clientes_model->getClientsLogged($set), 
			//'winners' => $this->clientes_model->getWinners($set), 
			'u_permissions' => $this->permissions 
		);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"clients");
		$this->load->view("admin/clients/list",$data);
		
	}
	public function add(){
		$data =array( 
			"roles" => $this->clientes_model->getRoles()
		);
		$this->load->view("admin/clients/add",$data);
	}
	public function store(){
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$user_id = test_input($this->input->post("user_id"));
		$name = test_input($this->input->post("name"));
		$email = test_input($this->input->post("email"));
		$phone = test_input($this->input->post("phone"));
		$password = test_input($this->input->post("password"));
		$passconf = test_input($this->input->post("passconf"));
		$role = $this->input->post("role");
		$num_cards = $this->input->post("num_cards");

		$this->form_validation->set_rules("user_id",$this->config->item('usenumberid') ? $this->lang->line('register_username_lb') : $this->lang->line('register_email_lb'),"required|is_unique[clients.user_name]");
		$this->form_validation->set_rules("name",$this->lang->line('register_name_lb'),"required");
		$this->form_validation->set_rules("email",$this->lang->line('register_email_lb'),"valid_email");
		$this->form_validation->set_rules("phone","Teléfono","numeric");
		//$this->form_validation->set_rules('password', 'Contraseña', 'required|min_length[4]');
		//$this->form_validation->set_rules('passconf', 'Confirmar Contraseña', 'required|matches[password]');
		
		if(!empty($password))
		{
			$this->form_validation->set_rules('password', $this->lang->line('login_pass_tf'), 'required|min_length[4]');
			$this->form_validation->set_rules('passconf', $this->lang->line('register_rptpass_lb'), 'required|matches[password]');
		}else
		{
			$password = strtoupper($user_id);
		}
		$this->form_validation->set_rules("num_cards","<?php echo $this->lang->line('register_numcards_lb'); ?>","numeric");

		$set = get_set_id();
	
		if ($this->form_validation->run()) {
			$data  = array(
				'user_name' => $user_id, 
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'bingo' => $set,
				'password' => password_hash($password, PASSWORD_BCRYPT),
				'role' => $role,
				'tyc' => 0
			);

			if ($this->clientes_model->save($data)) {
				for ($j=0; $j < $num_cards; $j++) {
				  	add_table($user_id);
				  	//echo "add_table(".$id.")<br>";
				}
				$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." agregó usuario ".$user_id." en el bingo ".$set);
				redirect(base_url()."admin/clients");
			}
			else{
				$this->session->set_flashdata("error","No se pudo guardar la información");
				$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó guardar usuario ".$user_id." en el bingo ".$set);
				redirect(base_url()."admin/clients/add");
			}
		}
		else{
			$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó guardar con campos incorrectos al usuario ".$user_id." en el bingo ".$set);
			$this->add();
		}
	}
	public function edit($user_id){
		$data =array( 
			'client' => $this->clientes_model->getClient($user_id), 
			//'details' => $this->clientes_model->getClientCampaigns($user_id),
			"roles" => $this->clientes_model->getRoles()
		);
		$this->load->view("admin/clients/edit",$data);
	}

	public function addTable($user_id){
		$set = get_set_id();
		add_table($user_id);
		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." agregó cartón al usuario ".$user_id." en el bingo ".$set);
		//$this->index();
		redirect(base_url()."admin/clients");
	}

	public function removeTable($user_id){
		$set = get_set_id();
		remove_table($user_id);
		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." eliminó cartón al usuario ".$user_id." en el bingo ".$set);
		//$this->index();
		redirect(base_url()."admin/clients");
	}

	public function update(){

		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$user_name = $this->input->post("user_name");
		$name = $this->input->post("name");
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$passconf = $this->input->post("passconf");
		$role = $this->input->post("role");

		//$refs = $this->input->post("refs");

		$this->form_validation->set_rules("name",$this->lang->line('register_name_lb'),"required");
		$this->form_validation->set_rules("email",$this->lang->line('register_email_lb'),"valid_email");
		
		$set = get_set_id();

		if(!empty($password))
		{
			$this->form_validation->set_rules('password', $this->lang->line('login_pass_tf'), 'required|min_length[4]');
			$this->form_validation->set_rules('passconf', $this->lang->line('register_rptpass_lb'), 'required|matches[password]');
		}
		if ($this->form_validation->run()) {
			if(!empty($password))
			{
				$data  = array(
					'name' => $name,
					'email' => $email,
					'password' => password_hash($password, PASSWORD_BCRYPT),
					'role' => $role
				);
			}
			else
			{
				$data  = array(
					'name' => $name,
					'email' => $email,
					'role' => $role
				);
			}

			if ($this->clientes_model->update($user_name,$data)) {
				$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." actualizó datos del usuario ".$user_name." en el bingo ".$set);
				redirect(base_url()."admin/clients");
			}
			else{
				$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
				$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó actualizar datos del usuario ".$user_name." en el bingo ".$set);
				redirect(base_url()."admin/clients/edit/".$user_name);
			}
		}
		else{
			$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó actualizar con campos erroneos datos del usuario ".$user_name." en el bingo ".$set);
			$this->edit($user_name);
		}
	}

	public function view($user_id){
		//$idOrder = $this->input->post("id");
		$data  = array(
			'client' => $this->clientes_model->getClient($user_id), 
			'details' => $this->clientes_model->getClientCampaigns($user_id)
		);
		$this->load->view("admin/clients/view",$data);
	}

	public function deleteall(){
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$set = get_set_id();
		$this->clientes_model->removeallclients($set);
		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." eliminó todos los usuario del bingo ".get_set_name());
		//redirect(base_url()."admin/clients");
		echo "admin/clients";
	}

	public function delete($user_name){
		$set = get_set_id();
		$this->clientes_model->remove($user_name);
		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." eliminó usuario ".$user_name." del bingo ".$set);
		redirect(base_url()."admin/clients");
	}
	
	public function dobingo($uid, $card)
	{
		$this->outh_model->CSRFVerify();
	
		//if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		date_default_timezone_set("America/Bogota");

		$timestamp = date("Y-m-d H:i:s");
		//$uid = test_input($this->input->post('id'));
		//$sid = test_input($this->input->post('sid'));
		//$card = test_input($this->input->post('card'));
		$sid = get_set_id();
		$already = $this->bingo_model->getMessage($sid, $uid, $card);
		if($already == null)
		{
			$wins = check_table($uid, $card);
			if(sizeof($wins) > 0 )
			{
				$data  = array(
					'user_id' => $uid,
					'room' => $sid,
					'timestamp' => $timestamp,
					'card' => $card,
					'patterns' => json_encode($wins),
					'status' => '0' 
				);
				$msgs = $this->bingo_model->saveMessages($data);
				//$query3    = "INSERT INTO messages values (NULL,'$uid','$sid','$timestamp','".$card."','".json_encode($wins)."','0')";
				//$con       = $mysqli->query($query3) or die($mysql->error);
				////$row        = mysqli_fetch_array($con);
			}
		}
		redirect(base_url()."admin/clients");
	}
}