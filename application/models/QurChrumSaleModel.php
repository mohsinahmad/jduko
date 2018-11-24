<?php
/**
* 
*/
class QurChrumSaleModel extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function GetData()
    {
        $this->db->select('`qur_chrum_sale_old_data`.`Id`, `qur_chrum_amount`.`chrum_type`,qur_chrum_amount.id as chrum_type_id , `qur_chrum_sale_old_data`.`Fresh_Quantity`, `qur_chrum_sale_old_data`.`Amount`, `qur_chrum_sale_old_data`.`Fresh_Rate`,`qur_chrum_sale_old_data`.`Old_Quantity`,`qur_chrum_sale_old_data`.`Old_Rate`');
        $this->db->join('qur_chrum_amount','qur_chrum_sale_old_data.Chrum_type_Id = qur_chrum_amount.id','RIGHT');
        return $this->db->get('qur_chrum_sale_old_data')->result();
	}

	public function Save_Sale_Chrum_old()
	{
		if (isset($_POST['edit'])){
            foreach ($_POST['Chrum_type_Id'] as $key => $value) {
                $this->db->set('Chrum_type_Id',$_POST['Chrum_type_Id'][$key]);
                 $this->db->set('Fresh_Quantity',$_POST['Fresh_Quantity'][$key]);
                $this->db->set('Old_Quantity',$_POST['Old_Quantity'][$key]);
                $this->db->set('Fresh_Rate',$_POST['Fresh_Rate'][$key]);
                $this->db->set('Old_Rate',$_POST['Old_Rate'][$key]);
                $this->db->set('Amount',$_POST['Amount'][$key]);
                $this->db->where('Id',$_POST['edit'][$key]);
                $this->db->update('qur_chrum_sale_old_data');
            }
        }else{
            foreach ($_POST['Chrum_type_Id'] as $key => $value) {
                $this->db->set('Chrum_type_Id',$_POST['Chrum_type_Id'][$key]);
                $this->db->set('Fresh_Quantity',$_POST['Fresh_Quantity'][$key]);
                $this->db->set('Old_Quantity',$_POST['Old_Quantity'][$key]);
                $this->db->set('Fresh_Rate',$_POST['Fresh_Rate'][$key]);
                $this->db->set('Old_Rate',$_POST['Old_Rate'][$key]);
                $this->db->set('Amount',$_POST['Amount'][$key]);
                $this->db->insert('qur_chrum_sale_old_data');
            }
        }
		 if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
	}
}