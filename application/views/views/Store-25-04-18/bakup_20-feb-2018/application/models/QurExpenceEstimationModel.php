<?php

class QurExpenceEstimationModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_Data_For_Estimation($from,$to,$day)
    {
        $this->db->select('qur_cow.Id as Cow_ID,qur_cow.Code,qur_cow.Count ,qur_expence_estimation.Estimation,qur_expence_estimation.Purchase_Amount as per_Cow');
        $this->db->from('qur_cow');
        $this->db->join('qur_expence_estimation', 'qur_cow.Id=qur_expence_estimation.Cow_Id', 'left');
        $this->db->where("qur_cow.Day" ,$day);
        $this->db->where("qur_cow.Count" ,'7');
        $this->db->where("qur_cow.Self_Cow" ,'0');
        $this->db->where("qur_cow.Code BETWEEN '" . $from . "' AND '" . $to . "'");
        return $this->db->get()->result();
    }

    public function Save_Expence_Estimation()
    {
        foreach ($_POST['Cow_Id'] as $key => $value) {
            $this->db->where('Cow_Id', $value);
            $esti = $this->db->get('qur_expence_estimation');

            if ($esti->num_rows() > 0) {
                $this->db->set('Cow_Id',$_POST['Cow_Id'][$key]);
                $this->db->set('Estimation',$_POST['Estimation'][$key]);
                $this->db->set('Purchase_Amount',$_POST['Purchase_Amount'][$key]);
                $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
                $this->db->where('Cow_Id', $value);
                $this->db->update('qur_expence_estimation');
            }else{
                $this->db->set('Cow_Id',$_POST['Cow_Id'][$key]);
                $this->db->set('Estimation',$_POST['Estimation'][$key]);
                $this->db->set('Purchase_Amount',$_POST['Purchase_Amount'][$key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('qur_expence_estimation');
            }
            $esti = array();
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function GetEstimation($code)
    {
        $this->db->select('qur_cow.Code as CowCode, ( IFNULL(qur_expence_estimation.Purchase_Amount,0) + IFNULL(qur_expence_estimation.Estimation,0))/7 as Per_hissa_amoun');
        $this->db->from('qur_cow');
        $this->db->join('qur_expence_estimation', 'qur_cow.Id = qur_expence_estimation.Cow_Id', 'left');
        $this->db->where("qur_cow.Code",$code);
        return $this->db->get()->result();
    }
}