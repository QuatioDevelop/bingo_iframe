<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//8dGu#1Sa#eQ1nf8Aest5
//
class Register extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->model('login_model'); 
        $this->load->model('clientes_model'); 
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
		$this->load->view('register');
	}

	public function store(){
		
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST
		
		$user_name = test_input($this->input->post("user_name"));
		$name = test_input($this->input->post("name"));
		$email = test_input($this->input->post("email"));
		$phone = test_input($this->input->post("phone"));
		$cellphone = test_input($this->input->post("cellphone"));
		$address = test_input($this->input->post("address"));
		$adult = test_input($this->input->post("adult"));
		$tyc = test_input($this->input->post("tyc"));
		$password = test_input($this->input->post("password"));
		$passconf = test_input($this->input->post("repeat_password"));

		$this->form_validation->set_rules("user_name",$this->lang->line('register_username_lb'),"required|is_unique[clients.user_name]|integer");
		$this->form_validation->set_rules("name",$this->lang->line('register_name_lb'),"required");
		$this->form_validation->set_rules("cellphone","Celular","required|regex_match[/^[0-9]{10}$/]");
		$this->form_validation->set_rules("adult","Soy mayor de edad","required");
		$this->form_validation->set_rules("tyc","Acepto términos y condiciones","required");
		$this->form_validation->set_rules("email",$this->lang->line('register_email_lb'),"valid_email");
		$this->form_validation->set_rules('password', $this->lang->line('login_pass_tf'), 'required|min_length[8]');
		$this->form_validation->set_rules('repeat_password', $this->lang->line('register_rptpass_lb'), 'required|matches[password]');

			//echo "STORE";
		if ($this->form_validation->run()) {
        	$setid = get_set_id();
			$data  = array(
				'user_name' => $user_name, 
				'name' => $name,
				'email' => $email,
				'phone' => $phone,
				'cellphone' => $cellphone,
				'address' => $address,
				'bingo' => $setid,
				'password' => password_hash($password, PASSWORD_BCRYPT),
				'role' => '3'
			);

			if ($this->clientes_model->save($data)) {
				$this->logs_model->logMessage("info","Se ha registrado el usuario ".$user_name." con el email ".$email);
				add_table($user_name);
				if($this->login_model->login($user_name, $password))
				{
					redirect(base_url().'dashboard');
				}else
				{
					$this->session->set_flashdata("error",$this->lang->line('wrong_user_pass'));
					redirect(base_url()."register");
				}
			}
			else{
				$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
				$this->logs_model->logMessage("warning","Se ha intentado registradar el usuario ".$user_name." con el email ".$email);
				redirect(base_url()."register");
			}
		}
		else{
			$this->session->set_flashdata("error",$this->form_validation->error_string());
			$this->logs_model->logMessage("warning","Se ha intentado registradar el usuario ".$user_name." con el email ".$email." - campos erroneos");
			redirect(base_url()."register");
		}
	}

	public function validar()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST


		if($this->login_model->login(test_input($this->input->post('i')), test_input($this->input->post('p'))))
		{
			$data = array(
				'response' => 'true',
				'user' => $this->clientes_model->getUser(test_input($this->input->post('i')))
			);
			//redirect(base_url().'dashboard');
			echo json_encode($data);
		}else
		{
			$data = array(
				'error' => $this->lang->line('wrong_user_pass')
			);
			//redirect(base_url().'dashboard');
			echo json_encode($data);
			
		}
	}

	public function cerrarsesion()
	{
		$this->logs_model->logMessage("info","El usuario ".$this->session->userdata('user_data')['user_uname']." ha cerrado sesión del bingo ".get_set_name());
		$this->session->unset_userdata('user_data');
		$this->session->unset_userdata('site_lang');
		redirect(base_url());
	}
	
}