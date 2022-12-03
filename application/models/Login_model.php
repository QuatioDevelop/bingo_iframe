<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('usuarios_model');
        $this->load->model('backend_model');
    }
    public function login($id, $password)
    {
        $query = $this->db->get_where('clients', array('user_name' => $id));
        if($query->num_rows() == 1)
        {
            $row=$query->row();
            if(password_verify($password, $row->password))
            {
                $permissions = $this->getUserPermissions($row->role);
                $data=array('user_data'=>array(
                    'user_name'=>$row->name,
                    'user_uname'=>$row->user_name,
                    'user_email'=>$row->email,
                    'user_role'=>$row->role,
                    'permissions'=>$permissions)
                );
                $this->session->set_userdata($data);
                $this->backend_model->setUserLogged($this->session->userdata('user_data')['user_uname']);
                $this->backend_model->setUserActivity($this->session->userdata('user_data')['user_uname']);
                $this->logs_model->logMessage("info","Usuario ".$this->session->userdata('user_data')['user_uname']." ha iniciado sesión");
                return true;
            }
        }
        $this->session->unset_userdata('user_data');
        $this->logs_model->logMessage("warning","Fallo inicio de sesión usuario ".$id);
        $this->logs_model->logSesionFail("warning","Fallo inicio de sesión usuario ".$id);
        return false;
    }

    public function getUserPermissions($role)
    {
        $perms = $this->backend_model->getRolePermissions($role);
        $permissions = array();

         foreach ($perms as $permission) {
            $arr_perm = array(
                    $permission->menu_id =>array(
                        'read'=>$permission->read_per,
                        'read_other'=>$permission->read_other_per,
                        'insert'=>$permission->insert_per,
                        'update'=>$permission->update_per,
                        'delete'=>$permission->delete_per
                        )
                );
            //array_push($permissions, $arr_perm);
            $permissions += $arr_perm;
        }
        return $permissions;
    }
}