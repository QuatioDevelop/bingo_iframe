<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats_model extends CI_Model {

	public function getStats($campaign_id){
		$this->db->select('*,
                           campaigns.name as campaign_name');
        $this->db->from('stats')->join('campaigns', 'stats.campaign = campaigns.campaign_id');
		$this->db->where("campaign",$campaign_id);
		$resultado = $this->db->get();
		return $resultado->result();

	}

	public function getUsersOfCampaign($cid){
		$this->db->select('campaign,count(DISTINCT(user)) as total_users,
                           campaigns.name as campaign_name');
        $this->db->from('stats')->join('campaigns', 'stats.campaign = campaigns.campaign_id');
		$this->db->where("stats.campaign",$cid);
		$resultado = $this->db->get();
		return $resultado->row();

	}
}