<?php
/**
 *
 */
class QurRewardListModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Save_List()
    {
        $this->db->set('Name',$this->input->post('Name'));
        $this->db->set('Location',$this->input->post('Location'));
        $this->db->set('Last_Year_Reward',$this->input->post('Last_Year_Reward'));
        $this->db->set('Increament',$this->input->post('Increament'));
        $this->db->set('Increament_Type',$this->input->post('Increament_Type'));
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        $this->db->insert('qur_reward_list');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Reward_List($loaction)
    {
        $this->db->select('*');
        $this->db->where_in('Location', $loaction);
        return $this->db->get('qur_reward_list')->result();
    }
}