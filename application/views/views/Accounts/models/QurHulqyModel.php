<?php

class QurHulqyModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_Hulqy($id = '')
    {
        if ($id != '') {
            $this->db->where('id', $id);
        }
        $this->db->order_by('qur_hulqy.Type','DESC');
        return $this->db->get('qur_hulqy')->result();
    }

    public function Save_Hulqy()
    {
        if (isset($_POST['edit'])){
            $this->db->set('hulqa_name',$this->input->post('hulqa_name'));
            $this->db->set('supervisor_name',$this->input->post('supervisor_name'));
            $this->db->set('Advance_Payments',0);
            $this->db->set('Students_Income',$this->input->post('Students_Income'));
            $this->db->set('Missing_Book_Penalty',$this->input->post('Missing_Book_Penalty'));
            $this->db->set('CreatedBy',$this->input->post('CreatedBy'));
            $this->db->set('CreatedOn',$this->input->post('CreatedOn'));
            if (isset($_POST['Package_Type'])) {
                $this->db->set('Package_Type',$this->input->post('Package_Type'));
                $this->db->set('Package_Amount',$this->input->post('Package_Amount'));
            }
            if (isset($_POST['Indivisual_Package_Type'])) {
                $this->db->set('Indivisual_Package_Type',$this->input->post('Indivisual_Package_Type'));
                $this->db->set('Indivisual_Package_Amount',$this->input->post('Indivisual_Package_Amount'));
            }
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->where('id',$_POST['edit']);
            $this->db->update('qur_hulqy');
        }else{
            $this->db->set('hulqa_name',$this->input->post('hulqa_name'));
            $this->db->set('supervisor_name',$this->input->post('supervisor_name'));
            $this->db->set('Advance_Payments',0);
            $this->db->set('Students_Income',$this->input->post('Students_Income'));
            $this->db->set('Missing_Book_Penalty',$this->input->post('Missing_Book_Penalty'));
            if (isset($_POST['Package_Type'])) {
                $this->db->set('Package_Type',$this->input->post('Package_Type'));
                $this->db->set('Package_Amount',$this->input->post('Package_Amount'));
            }
            if (isset($_POST['Indivisual_Package_Type'])) {
                $this->db->set('Indivisual_Package_Type',$this->input->post('Indivisual_Package_Type'));
                $this->db->set('Indivisual_Package_Amount',$this->input->post('Indivisual_Package_Amount'));
            }
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('qur_hulqy');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Delete_Hulqy($id)
    {
        $this->db->where('qur_hulqy_id',$id);
        $ewch = $this->db->get('qur_chrum_quantity_master')->result();
        if ($ewch == array()){
            $this->db->where('id',$id);
            $this->db->delete('qur_hulqy');
            if($this->db->affected_rows() > 0){
                return 'true';
            }else{
                return 'false';
            }
        }else{
            return 'falsess';
        }
    }
}