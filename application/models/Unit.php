<?php

class Unit extends CI_Model
{
	function __construct()
    {
		parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
	}

	public function unit_of_measure()
    {
        return $this->db->get('unit_of_measure')->result();
    }

	public function add_donationtype()
    {
        $createdOn = date('Y-m-d H:i:s');
        $createdBy = $_SESSION['user'][0]->id;

        $donationtype = array('Donation_Type' => $_POST['donation_type'], 'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);
        if(empty($donationtype)){
            return false;
        }else{
         // worked by sufyan
            $this->form_validation->set_rules('donation_type', 'donation_type', 'trim|required|is_unique[donation_type.Donation_Type]');
            if($this->form_validation->run())
            {
                $this->db->insert('donation_type', $donationtype);
                return true;
            }
            else{
                return false;
            }
            // end sufyan work
           /* if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }*/
        }
    }
     public function Delete_unit($id)
    {
            $this->db->where('Id', $id);
            $this->db->delete('unit_of_measure');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
    }
   public function unitBy_Id($id)
    {
        $this->db->select('*');
        $this->db->where('id', $id);
        return $this->db->get('unit_of_measure')->result();
    }
     public function Update_unit($id)
    {   
//        $updatedOn = date('Y-m-d H:i:s');
//        $updatedBy = $_SESSION['user'][0]->id;

        $this->db->set('name', $_POST['unit']);
//        $this->db->set('UpdatedBy', $updatedBy);
//        $this->db->set('UpdatedOn', $updatedOn);
        $this->db->where('id',$id);
        $this->db->update('unit_of_measure');
        if($this->db->affected_rows()>0)
        {
           return true;
        }
        else
        {
          return false;
        }
    }

    public function  get_donation(){

	    return $this->db->get('donation_type')->result();

    }

}