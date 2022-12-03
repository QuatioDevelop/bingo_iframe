<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permisos_model extends CI_Model {

	public function getPermissions(){
		$this->db->select('permissions.*,
						   menus.name as menu,
                           roles.name as role');
        $this->db->from('permissions');
        $this->db->join('roles', 'roles.role_id = permissions.role_id');
        $this->db->join('menus', 'menus.id = permissions.menu_id');
        $this->db->order_by("permissions.role_id");
		$resultados = $this->db->get();
		return $resultados->result();
	}

	public function getPermission($id){
		$this->db->select('permissions.*,
						   menus.name as menu,
                           roles.name as role');
        $this->db->from('permissions');
        $this->db->join('roles', 'roles.role_id = permissions.role_id');
        $this->db->join('menus', 'menus.id = permissions.menu_id');
		$this->db->where("permissions.id",$id);
        $this->db->order_by("permissions.role_id");
		$resultado = $this->db->get();
		return $resultado->row();

	}

	public function getMenus(){
		$resultados = $this->db->get('menus');
		return $resultados->result();
	}

	public function save($data){
		return $this->db->insert("permissions",$data);
	}

	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update("permissions",$data);
	}
	public function remove($id){
		$this->db->where("id",$id);
		return $this->db->delete("permissions");
	}
}