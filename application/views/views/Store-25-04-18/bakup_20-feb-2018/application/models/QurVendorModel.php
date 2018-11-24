<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/28/2017
 * Time: 3:49 PM
 */

class QurVendorModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Save()
    {
        if (isset($_POST['edit'])){
            $this->db->set('Name',$_POST['Name']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->update('qur_khaal_vendor');
        }else{
            $Code = $this->Get_Code();
            $this->db->set('Code',$Code);
            $this->db->set('Name',$_POST['Name']);
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('qur_khaal_vendor');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Code()
    {
        $this->db->select_max('Code');
        $Code = $this->db->get('qur_khaal_vendor')->result();
        if ($Code[0]->Code != ''){
            $Code = $Code[0]->Code + 1;
            $new_Code = str_pad($Code, 4, "0", STR_PAD_LEFT);
        }else{
            $new_Code = '0001';
        }
        return $new_Code;
    }

    public function Delete($id)
    {
        $this->db->where('Vendor_Id',$id);
        $sale_Slip = $this->db->get('qur_sale_slips')->result();

        $this->db->where('Vendor_Id',$id);
        $cashReceipt = $this->db->get('qur_cash_reciept')->result();
        if ($sale_Slip == array() && $cashReceipt == array()){
            $this->db->where('id',$id);
            $this->db->delete('qur_khaal_vendor');
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