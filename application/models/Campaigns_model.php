<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaigns_model extends CI_Model {

	public function getCampaigns(){
		/*$this->db->select('clients.user_name,
                           clients.name,
                           clients.email,
                           clients.password,
						   clients.role,
                           roles.name as role_name');
        $this->db->from('clients')->join('roles', 'roles.role_id = clients.role');*/
		$resultados = $this->db->get('campaigns');
		return $resultados->result();
	}

	public function getCampaign($id){
		/*$this->db->select('clients.user_name,
                           clients.name,
                           clients.email,
						   clients.password,
						   clients.role,
                           roles.name as role_name');
        $this->db->from('clients')->join('roles', 'roles.role_id = clients.role');*/
		$this->db->where("campaign_id",$id);
		$resultado = $this->db->get('campaigns');
		return $resultado->row();

	}

	public function save($data){
		return $this->db->insert("campaigns",$data);
	}

	public function update($id,$data){
		$this->db->where("campaign_id",$id);
		return $this->db->update("campaigns",$data);
	}
	public function remove($id){
		$this->db->where("campaign_id",$id);
		return $this->db->delete("campaigns");
	}

	public function getCampaignsLike($valor){
		$this->db->select("campaign_id,name AS label", FALSE);
		$this->db->from("campaigns");
		$this->db->or_like(array('name' => $valor));
		$resultados = $this->db->get();
		return $resultados->result_array();
	}	

	public function getUsersByCampaign(){
		$this->db->select('campaign,count(DISTINCT(user)) as total_users,
                           campaigns.name as campaign_name');
        $this->db->from('stats')->join('campaigns', 'stats.campaign = campaigns.campaign_id');
		$this->db->group_by("stats.campaign");
		$this->db->order_by('stats.stats_id', 'DESC');
		$this->db->limit(50); 
		$resultado = $this->db->get();
		return $resultado->result();

	}

	public function getUsersByCampaignOfClient($campaigns){
		$this->db->select('campaign,count(DISTINCT(user)) as total_users,
                           campaigns.name as campaign_name');
        $this->db->from('stats')->join('campaigns', 'stats.campaign = campaigns.campaign_id');
		$this->db->or_where_in("stats.campaign",$campaigns);
		$this->db->group_by("stats.campaign");
		$this->db->order_by('stats.stats_id', 'DESC');
		$this->db->limit(50); 
		$resultado = $this->db->get();
		return $resultado->result();

	}

}