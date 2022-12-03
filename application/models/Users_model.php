<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

	public function getUsers(){
		/*$this->db->select('users.user_name,
                           users.name,
                           users.email,
						   users.role,
                           roles.name as role_name');
        $this->db->from('users')->join('roles', 'roles.role_id = users.role');*/

		$resultados = $this->db->get('users');
		return $resultados->result();
	}

	public function getUser($id){
		$this->db->select('users.user_name,
                           users.name,
                           users.email,
						   users.role,
                           roles.name as role_name');
        $this->db->from('users')->join('roles', 'roles.role_id = users.role');
		$this->db->where("user_name",$id);
		$resultado = $this->db->get();
		return $resultado->row();

	}

	public function getRoles(){
		$resultados = $this->db->get('roles');
		return $resultados->result();
	}

	public function save($data){
		echo "USERS";
		return $this->db->insert("users",$data);
	}

	public function update($id,$data){
		$this->db->where("user_name",$id);
		return $this->db->update("users",$data);
	}
	public function remove($user_id){
		$this->db->where("user_name",$user_id);
		return $this->db->delete("users");
	}

	public function removeClientCampaigns($user_id){
		$this->db->where("client_id",$user_id);
		return $this->db->delete("clients_campaigns");
	}

	public function getUsersCampaigns(){
		$this->db->select('clients_campaigns.client_id,
                           clients_campaigns.campaign_id,
                           campaigns.name as campaign_name,
                           clients.name as client_name');
        $this->db->from('clients_campaigns')->join('clients', 'clients_campaigns.client_id = clients.user_name');
        $this->db->join('campaigns', 'clients_campaigns.campaign_id = campaigns.campaign_id');
		$resultado = $this->db->get();
		return $resultado->result();
	}


	public function getClientCampaigns($cid){
		$this->db->select('clients_campaigns.client_id,
                           clients_campaigns.campaign_id,
                           campaigns.name as campaign_name,
                           clients.name as client_name');
        $this->db->from('clients_campaigns')->join('campaigns', 'clients_campaigns.campaign_id = campaigns.campaign_id');
        $this->db->join('clients', 'clients_campaigns.client_id = clients.user_name');
		$this->db->where("clients_campaigns.client_id",$cid);
		$resultado = $this->db->get();
		return $resultado->result();
	}

	public function saveClientCampaigns($data){
		$this->db->insert("clients_campaigns",$data);
	}
}