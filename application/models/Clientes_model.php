<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes_model extends CI_Model {

	public function getClients($sid){
		$this->db->select('clients.*,
                           roles.name as role_name,
                           user_table.table_index');
        $this->db->from('clients')->join('roles', 'roles.role_id = clients.role');
        $this->db->join('user_table', 'user_table.user_id = clients.user_name AND user_table.room='.$sid, 'left outer');
		//$rooms = array($sid);
		//$this->db->where_in('clients.bingo', $rooms);
		$this->db->where('clients.bingo', $sid);
		$resultados = $this->db->get();
		return $resultados->result();
	}

	public function getGenClients($sid,$room){
		$this->db->select('clients.*,
                           roles.name as role_name,
                           user_table.table_index');
        $this->db->from('clients')->join('roles', 'roles.role_id = clients.role');
        $this->db->join('user_table', 'user_table.user_id = clients.user_name AND user_table.room='.$sid, 'left outer');
		//$rooms = array($sid);
		//$this->db->where_in('clients.bingo', $rooms);
		$this->db->where('clients.bingo', $room);
		$resultados = $this->db->get();
		return $resultados->result();
	}

	public function getClient($id){
		$this->db->select('clients.*,
                           roles.name as role_name');
        $this->db->from('clients')->join('roles', 'roles.role_id = clients.role');
		$this->db->where("user_name",$id);
		$resultado = $this->db->get();
		return $resultado->row();

	}
	public function getClientsLogged($sid){
		$this->db->select('count(DISTINCT(user_name)) as total_users');
        $this->db->from('clients');
		$this->db->where("logged",1);
		$rooms = array($sid);
		$this->db->where_in('clients.bingo', $rooms);
		$resultado = $this->db->get();
		return $resultado->row();
	}
	
	public function getWinners($sid){
		$this->db->select('clients.name, messages.*');
        $this->db->from('messages');
        $this->db->join('clients', 'messages.user_id = clients.user_name');
		$this->db->where("room",$sid);
		$this->db->where("status",'2');
		$this->db->order_by('timestamp', 'DESC');
		$resultado = $this->db->get();
		return $resultado->result();
	}

	public function getRoles(){
		$resultados = $this->db->get('roles');
		return $resultados->result();
	}

	public function save($data){
		//echo "CLIENTES";
		return $this->db->insert("clients",$data);
	}

	public function update($id,$data){
		$this->db->where("user_name",$id);
		return $this->db->update("clients",$data);
	}
	public function remove($user_id){
		$this->db->where("user_name",$user_id);
		return $this->db->delete("clients");
	}
	public function removeallclients($sid){
		$this->db->where("bingo",$sid);
		return $this->db->delete("clients");
	}
	public function removeClientCampaigns($user_id){
		$this->db->where("client_id",$user_id);
		return $this->db->delete("clients_campaigns");
	}

	public function getClientsCampaigns(){
		$this->db->select('clients_campaigns.client_id,
                           clients_campaigns.campaign_id,
                           campaigns.name as campaign_name,
                           clients.name as client_name');
        $this->db->from('clients_campaigns')->join('clients', 'clients_campaigns.client_id = clients.user_name');
        $this->db->join('campaigns', 'clients_campaigns.campaign_id = campaigns.campaign_id');
        $this->db->group_by("clients_campaigns.campaign_id");
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