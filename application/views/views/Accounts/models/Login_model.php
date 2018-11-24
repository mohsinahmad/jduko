<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class Login_model extends CI_Model
{
	private $table="users";

	public function validate()
    {
		$username = $this->input->post('username',true);
		$password =	$this->input->post('password',true);
		$data = array('UserName' => $username, 'Password' => md5($password) );
		$this->db->select('id,UserName,Locked,InUse,IsAdmin,LastLogin,CreatedBy,CreatedOn,UpdatedBy,UpdatedOn');
		$this->db->where($data);
		$query= $this->db->get($this->table);
		return $query;
	}

	public function update_lastLogin($id,$L_login)
	{
		$login_up = array('LastLogin' => $L_login );
		$this->db->where('id', $id);
		$this->db->update($this->table, $login_up);
	}

    public function update_inuse($id,$inusevar)
    {
        $InUse = array('InUse' => $inusevar );
        $this->db->where('id', $id);
        $this->db->update($this->table, $InUse);
        return true;
    }
}