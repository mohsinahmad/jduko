<?php
/**
 *
 */
class QurExpenceTypeModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_type($id = '')
    {
        if ($id != '') {
            $this->db->where('id', $id);
        }
        return $this->db->get('qur_expence_type')->result();
    }

    public function Save_Type()
    {
        $createdOn = date('Y-m-d H:i:s');
        $createdBy = $_SESSION['user'][0]->id;
        if (isset($_POST['IsReward'])){
            $expence = array('type' => $this->input->post('type'), 'IsReward' => $this->input->post('IsReward'), 'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);

        }else{
            $expence = array('type' => $this->input->post('type'), 'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);
        }
        $this->db->insert('qur_expence_type', $expence);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function ExpenceTypeBy_Id($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        return $this->db->get('qur_expence_type')->result();
    }

    public function Update_Expencetype($id)
    {
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;

        $this->db->set('type', $_POST['type']);
        $this->db->set('UpdatedBy', $updatedBy);
        $this->db->set('UpdatedOn', $updatedOn);
        $this->db->where('id',$id);
        $this->db->update('qur_expence_type');
        if($this->db->affected_rows()>0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function Delete_ExpenceType($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('qur_expence_type');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}