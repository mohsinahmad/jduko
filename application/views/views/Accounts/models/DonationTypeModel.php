<?php

class DonationTypeModel extends CI_Model
{
	function __construct()
    {
		parent::__construct();
	}

	public function donation_type()
    {
        return $this->db->get('donation_type')->result();
    }

	public function add_donationtype()
    {
        $createdOn = date('Y-m-d H:i:s');
        $createdBy = $_SESSION['user'][0]->id;

        $donationtype = array('Donation_Type' => $_POST['donation_type'], 'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);
        if(empty($donationtype)){
            return false;
        }else{
            $this->db->insert('donation_type', $donationtype);
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

     public function Delete_DonationType($id)
    {
        $this->db->select('donation_type');
        $this->db->where('donation_type', $id);
        $item_setup = $this->db->get('item_setup')->result();

        $this->db->select('Donation_Type_Id');
        $this->db->where('Donation_Type_Id', $id);
        $demand_form = $this->db->get('item_demand_form')->result();
        
        if ($demand_form != array() || $item_setup != array()) {
            return false;
        }else{
            $this->db->where('Id', $id);
            $this->db->delete('donation_type');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function DonationTypeBy_Id($id)
    {
        $this->db->select('*');
        $this->db->where('Id', $id);
        return $this->db->get('donation_type')->result();
    }
    
    public function Update_donationtype($id)
    {   
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;

        $this->db->set('Donation_Type', $_POST['Dtype']);
        $this->db->set('UpdatedBy', $updatedBy);
        $this->db->set('UpdatedOn', $updatedOn);
        $this->db->where('Id',$id);
        $this->db->update('donation_type');
        if($this->db->affected_rows()>0)
        {
           return true;
        }
        else
        {
          return false;
        }
    }
}