<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends CI_Controller {

	public function __construct()
    {
        parent::__construct();
		$this->permissions = $this->backend_lib->control();
        if (!$this->session->userdata("user_data")) {
			redirect(base_url());
		}
		$this->load->model("permisos_model");
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
		$data  = array(
			'permissions' => $this->permisos_model->getPermissions(), 
			'u_permissions' => $this->permissions 
		);
		//print_r($data);
		$this->backend_model->setUserPlace($this->session->userdata('user_data')['user_uname'],"permissions");
		$this->load->view("admin/permisos/list",$data);
		
	}

	public function add(){

		$data =array( 
			'menus' => $this->permisos_model->getMenus(), 
			"roles" => $this->clientes_model->getRoles()
		);
		$this->load->view("admin/permisos/add", $data);
	}

	public function store(){
		$role = $this->input->post("role");
		$menu = $this->input->post("menu");
		$read = $this->input->post("read");
		$read_other = $this->input->post("read_other");
		$insert = $this->input->post("insert");
		$update = $this->input->post("update");
		$delete = $this->input->post("delete");

		$data  = array(
			'role_id' => $role, 
			'menu_id' => $menu,
			'read_per' => $read,
			'read_other_per' => $read_other,
			'insert_per' => $insert,
			'update_per' => $update,
			'delete_per' => $delete
		);

		if ($this->permisos_model->save($data)) {
			$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." agregó permisos del bingo ".$set);
			redirect(base_url()."admin/permissions");
		}
		else{
			$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
			$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó agregar permisos del bingo ".$set);
			redirect(base_url()."admin/permissions/add");
		}
		
	}

	public function edit($id){
		$data =array( 
			'permission' => $this->permisos_model->getPermission($id), 
			'menus' => $this->permisos_model->getMenus(), 
			'roles' => $this->clientes_model->getRoles()
		);
		$this->load->view("admin/permisos/edit",$data);
	}

	public function update(){

		$id = $this->input->post("id");
		
		$read = $this->input->post("read");
		$read_other = $this->input->post("read_other");
		$insert = $this->input->post("insert");
		$update = $this->input->post("update");
		$delete = $this->input->post("delete");

		$data  = array(
			'read_per' => $read,
			'read_other_per' => $read_other,
			'insert_per' => $insert,
			'update_per' => $update,
			'delete_per' => $delete
		);

		if ($this->permisos_model->update($id,$data)) {
			$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." cambió permisos del bingo ".$set);
			redirect(base_url()."admin/permissions");
		}
		else{
			$this->session->set_flashdata("error",$this->lang->line('error_saving_data'));
			$this->logs_model->logMessage("warning","Usuario ".$this->session->userdata('user_data')['user_uname']." intentó cambiar permisos del bingo ".$set);
			redirect(base_url()."admin/permissions/edit/".$id);
		}
		
	}

	public function delete($id){
		$this->permisos_model->remove($id);
		$this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." eliminó permiso ".$id." del bingo ".$set);
		redirect(base_url()."admin/permissions");
	}
	
}