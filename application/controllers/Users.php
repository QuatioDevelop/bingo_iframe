<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->permissions = $this->backend_lib->control();
        if (!$this->session->userdata("user_data")) {
			redirect(base_url());
		}
		$this->load->model("users_model");
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
		$data  = array(
			'users' => $this->users_model->getUsers(), 
			'u_permissions' => $this->permissions 
		);
		$this->load->view("users/list",$data);
		
	}

	public function edit($user_id){
		$data =array( 
			'user' => $this->users_model->getUser($user_id), 
			'details' => $this->users_model->getUserCampaigns($user_id),
			"roles" => $this->users_model->getRoles()
		);
		$this->load->view("users/edit",$data);
	}

	public function update(){

		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		$user_name = $this->input->post("user_name");
		$name = $this->input->post("name");
		$email = $this->input->post("email");
		$password = $this->input->post("password");
		$passconf = $this->input->post("passconf");
		$role = $this->input->post("role");

		$refs = $this->input->post("refs");

		$this->form_validation->set_rules("name",$this->lang->line('register_name_lb'),"required");
		$this->form_validation->set_rules("email",$this->lang->line('register_email_lb'),"valid_email");
		
		if(!empty($password))
		{
			$this->form_validation->set_rules('password', $this->lang->line('login_pass_tf'), 'required|min_length[8]');
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

			$this->users_model->removeUserCampaigns($user_name);

			if($refs && count($refs) > 0)
			{
				for ($i=0; $i < count($refs); $i++) { 
					$dataCampaigns  = array(
						'user_id' => $user_name, 
						'campaign_id' => $refs[$i]
					);

					$this->users_model->saveUserCampaigns($dataCampaigns);
				}
			}


			if ($this->users_model->update($user_name,$data)) {
				redirect(base_url()."users");
			}
			else{
				$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
				redirect(base_url()."users/edit/".$user_name);
			}
		}
		else{
			$this->edit($user_name);
		}
	}

	public function view($user_id){
		//$idOrder = $this->input->post("id");
		$data  = array(
			'user' => $this->users_model->getUser($user_id), 
			'details' => $this->users_model->getUserCampaigns($user_id)
		);
		$this->load->view("users/view",$data);
	}

	public function delete($user_name){
		$this->users_model->remove($user_name);
		redirect(base_url()."users");
	}
	
}