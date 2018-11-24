<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/1/2017
 * Time: 5:21 PM
 */

class QurTransferModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_All()
    {
        $this->db->select('IFNULL(SUM(qur_incomereport.This_Transfer_Amount),0) as Total_transfer');
        return $this->db->get('qur_incomereport')->result();
    }

    public function Max_SlipNumber()
    {
        $this->db->select('IFNULL(MAX(Slip_Number),"38/0") as Slip_Number');
        $max = $this->db->get('qur_incomereport')->result();
        if ($max == array()){
            return '38/1';
        }else{
            $max_num = explode("/",$max[0]->Slip_Number);
            $newSlip = (int)$max_num[1]+1;
            return $max_num[0].'/'.$newSlip;
        }
    }

    public function Save()
    {
        if (isset($_POST['edit'])){
            $this->db->where('Slip_Number',$_POST['edit']);
            $this->db->delete('qur_incomereport');
        }

        $this->db->set('DateG',$_POST['Date']);
        $this->db->set('Total_Collection',$_POST['Total_Collection']);
        $this->db->set('Total_Transfer',$_POST['Total_Transfer']);
        $this->db->set('This_Transfer_Amount',$_POST['This_Transfer_Amount']);
        $this->db->set('Cash_In_Hand_After_This',$_POST['Cash_In_Hand_After_This']);
        if (isset($_POST['edit'])){
            $this->db->set('Slip_Number',$_POST['edit']);
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
        }else{
            $this->db->set('Slip_Number',$this->Max_SlipNumber());
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        }
        $this->db->Insert('qur_incomereport');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}