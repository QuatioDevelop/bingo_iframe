<?php

class Backend_lib {

	private $CI;
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	public function control()
	{
		if(!is_logged_in())
		{
			//print_r("not logged in");
			redirect(base_url());
		}
		//print_r($this->CI->backend_model->userAcceptTerms($this->CI->session->userdata('user_data')['user_uname']));
		if(!$this->CI->backend_model->userAcceptTerms($this->CI->session->userdata('user_data')['user_uname'])->tyc)
		{
			redirect(base_url()."termsconditions");
			//return;
		}
		$url = $this->CI->uri->segment(1);
		//if($this->CI->uri->segment(2) && $url != 'dashboard' && $url != 'reports')
		if($this->CI->uri->segment(2) && $url == 'admin')
		{
			$url = $this->CI->uri->segment(1)."/".$url = $this->CI->uri->segment(2);
		}
		//echo $url." : <br>";
		$infomenu = $this->CI->backend_model->getId($url);
		//print_r($infomenu);
		//echo " : <br>";
		if($infomenu)
			$permissions = $this->CI->backend_model->getPermissions($infomenu->id, $this->CI->session->userdata('user_data')['user_role']);
		//echo empty($permissions)." : <br>";
		//print_r($permissions);

		if(empty($permissions))
		{
			$permissions = new stdClass();
			$permissions->id = 0;
			$permissions->menu_id = ($infomenu) ? $infomenu->id : 0;
			$permissions->role_id = $this->CI->session->userdata('user_data')['user_role'];
			$permissions->read_per = 1;//$url != 'dashboard' ? 0 : 1;
			$permissions->read_other_per = 0;
			$permissions->insert_per = 0;
			$permissions->update_per = 0;
			$permissions->delete_per = 0;
		}
		//echo empty($permissions)." ** <br>";
		//print_r($permissions);
		if($permissions->read_per == 0)
		{
			//print_r("not permissions");
			redirect(base_url()."dashboard");
		}else
		{
			return $permissions;
		}
	}
}


?>