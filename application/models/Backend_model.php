<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backend_model extends CI_Model {

	public function getId($url){
		$this->db->like('url',$url);
		$resultados = $this->db->get('menus');
		return $resultados->row();
	}
	
	public function getPermissions($menu,$role){
		$this->db->where('menu_id',$menu);
		$this->db->where('role_id',$role);
		$resultados = $this->db->get('permissions');
		return $resultados->row();
	}

	public function getRolePermissions($role){
		$this->db->where('role_id',$role);
		$resultados = $this->db->get('permissions');
		return $resultados->result();
	}

	public function getLastPlayer(){
		$this->db->select('stats.id,
                           stats.date,
                           stats.patient,
                           stats.data,
                           patients.name as user_name,
                           stats.game as game_id,
                           games.name as game_name');
        $this->db->from('stats')->join('games', 'stats.game = games.id')->join('patients', 'stats.patient = patients.id');
		$this->db->order_by('stats.date', 'DESC');
		$resultado = $this->db->get();
		return $resultado->first_row();
	}

	public function rowCount($table)
	{
		$resultados = $this->db->get($table);
		return $resultados->num_rows();
	}

	public function rowUserCount($table, $userId)
	{
		$this->db->where("orders.user",$userId);
		$resultados = $this->db->get($table);
		return $resultados->num_rows();
	}

	public function userAcceptTerms($userId){
		$this->db->select('tyc');
        $this->db->from('clients');
		$this->db->where("user_name",$userId);
		$resultado = $this->db->get();
		return $resultado->row();
	}

	public function setUserTerms($userId){
		//print_r($userId);

		date_default_timezone_set("America/Bogota");
		$data  = array(
			'last_activity' => date('Y-m-d H:i:s'),
			'tyc' => 1
		);

		//print_r($data);
		$this->db->where("user_name",$userId);
		return $this->db->update("clients",$data);
	}
	public function setUserPlace($userId,$place){
		date_default_timezone_set("America/Bogota");
		$data  = array(
			'last_activity' => date('Y-m-d H:i:s'),
			'place' => $place
		);
		$this->db->where("user_name",$userId);
		return $this->db->update("clients",$data);
	}
	public function setUserActivity($userId){
		date_default_timezone_set("America/Bogota");
		$data  = array(
			'last_activity' => date('Y-m-d H:i:s')
		);
		$this->db->where("user_name",$userId);
		return $this->db->update("clients",$data);
	}
	public function setUserLogged($userId){
		//print_r($userId);

		date_default_timezone_set("America/Bogota");
		$data  = array(
			'browser' => $this->agent->browser(),
			'browser_version' => $this->agent->version(),
			'os' => $this->agent->platform(),
			'logged' => 1,
			'last_login' => date('Y-m-d H:i:s')
		);

		//print_r($data);
		$this->db->where("user_name",$userId);
		return $this->db->update("clients",$data);
	}
}