<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//include_once(APPPATH.'core/CI_Front_End_Controller.php');
//
class Login extends CI_Controller {

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
		//$this->logout();
		//xpelluser
		//58Ccg-L7x0L_ER5.
		
		/*$data  = array(
			'user_id' => "00000", 
			'name' => "Administrador",
			'email' => "",
			'password' => password_hash("xp3llD1g174l4dm1n", PASSWORD_BCRYPT),
			'role' => "1"
		);
		$this->clientes_model->save($data);*/

		//print_r($this->input->get('uid'));
		//print_r("<br>");
		//print_r("->".($this->input->get('uid') != null)."-");
		if($this->input->get('uid') != null)
		{
		  	$id = $this->input->get('uid');
		  	if($this->login_model->login(test_input($id), test_input(strtoupper($id))))
			{
				/*$data = array(
					'id' => 'sisas',
					'password' => $this->input->post('password')
				);*/
				redirect(base_url().'dashboard?uid='.$id);
			}else
			{
				/*$data = array(
					'id' => 'nonas',
					'password' => $this->input->post('password')
				);*/
				//$this->load->view('iniciosesion');
				$this->session->set_flashdata("error",$this->lang->line('user_not_registered'));
				redirect(base_url());
			}
		  	//redirect(base_url().'dashboard?uid='.$id);
		}/*else if(is_logged_in())
		{
			redirect(base_url().'dashboard?uid='.$this->session->userdata('user_data')['user_uname']);
		}*/else
		{
			$this->session->unset_userdata('user_data');
			//$this->load->view('layouts/header');
			$cfg = $this->bingo_model->getRoomData(get_set_id());
			$data  = array(
				'date' => $cfg ? $cfg->date : date("Y-m-d H:i:s"),
				'time' => $cfg ? $cfg->time : date("Y-m-d H:i:s")
			);
			$this->load->view('login', $data);
			//$this->load->view('layouts/footer');
		}
		
	}

	public function login()
	{
		//$this->logout();
		//xpelluser
		//58Ccg-L7x0L_ER5.
		
		/*$data  = array(
			'user_id' => "00000", 
			'name' => "Administrador",
			'email' => "",
			'password' => password_hash("xp3llD1g174l4dm1n", PASSWORD_BCRYPT),
			'role' => "1"
		);
		$this->clientes_model->save($data);*/

		if(is_logged_in())
		{
			redirect(base_url().'dashboard?uid='.$this->session->userdata('user_data')['user_uname']);
		}else
		{
			$this->load->view('loginadmin');
		}
		
	}

	public function loginhost()
	{
		//$this->logout();
		//xpelluser
		//58Ccg-L7x0L_ER5.
		
		/*$data  = array(
			'user_id' => "00000", 
			'name' => "Administrador",
			'email' => "",
			'password' => password_hash("xp3llD1g174l4dm1n", PASSWORD_BCRYPT),
			'role' => "1"
		);
		$this->clientes_model->save($data);*/

		if(is_logged_in())
		{
			redirect(base_url()."admin/bingo/host");
			//redirect(base_url().'dashboard');
		}else
		{
			$this->load->view('loginhost');
		}
		
	}

	public function iniciarsesion()
	{
		//$this->outh_model->CSRFVerify();
	
		//if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		if($this->login_model->login(test_input($this->input->get('user_name')), test_input(strtoupper($this->input->get('user_name')))))
		{
			/*$data = array(
				'id' => 'sisas',
				'password' => $this->input->post('password')
			);*/
			redirect(base_url().'dashboard?uid='.$this->input->get('user_name'));
		}else
		{
			/*$data = array(
				'id' => 'nonas',
				'password' => $this->input->post('password')
			);*/
			//$this->load->view('iniciosesion');
			$this->session->set_flashdata("error",$this->lang->line('user_not_registered'));
			redirect(base_url());
		}
		//$this->load->view('debug', $data); 
	}

	public function iniciarsesionhost()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		if($this->login_model->login(test_input($this->input->post('user_name')), test_input($this->input->post('user_name'))))
		{
			/*$data = array(
				'id' => 'sisas',
				'password' => $this->input->post('password')
			);*/
			redirect(base_url().'admin/bingo/host');
		}else
		{
			/*$data = array(
				'id' => 'nonas',
				'password' => $this->input->post('password')
			);*/
			//$this->load->view('iniciosesion');
			$this->session->set_flashdata("error",$this->lang->line('user_not_registered'));
			redirect(base_url().'loginhost');
		}
		//$this->load->view('debug', $data); 
	}

	public function iniciarsesionadmin()
	{
		$this->outh_model->CSRFVerify();
	
		if ($_SERVER['REQUEST_METHOD'] != 'POST') exit; // Don't allow anything but POST

		if($this->login_model->login(test_input($this->input->post('user_name')), test_input($this->input->post('password'))))
		{
			/*$data = array(
				'id' => 'sisas',
				'password' => $this->input->post('password')
			);*/
			redirect(base_url().'dashboard?uid='.$this->session->userdata('user_data')['user_uname']);
		}else
		{
			/*$data = array(
				'id' => 'nonas',
				'password' => $this->input->post('password')
			);*/
			//$this->load->view('iniciosesion');
			$this->session->set_flashdata("error",$this->lang->line('wrong_user_pass'));
			redirect(base_url().'login');
		}
		//$this->load->view('debug', $data); 
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

	public function logout()
	{
		$this->logs_model->logMessage("info","El usuario ".$this->session->userdata('user_data')['user_uname']." ha cerrado sesión del bingo ".get_set_name());
		$this->session->unset_userdata('user_data');
		$this->session->unset_userdata('site_lang');
		redirect(base_url());
	}
	
}