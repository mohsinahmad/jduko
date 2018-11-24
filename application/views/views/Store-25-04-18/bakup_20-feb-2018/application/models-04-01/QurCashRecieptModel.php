<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/29/2017
 * Time: 10:05 AM
 */

class QurCashRecieptModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Get_All_Reciepts($id='')
    {
        $this->db->select('*,qur_cash_reciept.Id as Reciept_Id');
        $this->db->join('qur_khaal_vendor','qur_cash_reciept.Vendor_Id = qur_khaal_vendor.Id');
        if ($id != ''){
            $this->db->where('qur_cash_reciept.Id',$id);
        }
        return $this->db->get('qur_cash_reciept')->result();
    }

    public function Get_Misc_Icome($loc='')
    {
        ($loc != '')?$this->db->select('SUM(Amount) as Amount'):'';
        ($loc != '')?$this->db->where('Loc_Id',$loc):'';
        $this->db->where('Is_Income',1);
        return $this->db->get('qur_cash_reciept')->result();
    }

    public function Save()
    {
        isset($_POST['Vendor_Id'])?$this->db->set('Vendor_Id',$_POST['Vendor_Id']):'';
        isset($_POST['Is_Income'])?$this->db->set('Is_Income',$_POST['Is_Income']):'';
        $this->db->set('DateG',$_POST['DateG']);
        $this->db->set('DateH',$_POST['DateH']);
        $this->db->set('Amount',$_POST['Amount']);
        $this->db->set('Remarks',$_POST['Remarks']);
        if (isset($_POST['Edit'])){
            $this->db->set('Reciept_Number',$_POST['Reciept_Number']);
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->where('Id',$_POST['Edit']);
            $this->db->Update('qur_cash_reciept');
        }else{
            $Reciept_Number = $this->Get_RecieptNum();
            $this->db->set('Reciept_Number',$Reciept_Number);
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('qur_cash_reciept');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_RecieptNum()
    {
        $this->db->select_max('Reciept_Number');
        $SNum = $this->db->get('qur_cash_reciept')->result();
        if ($SNum[0]->Reciept_Number != ''){
            $num = $SNum[0]->Reciept_Number + 1;
            $new_num = str_pad($num, 4, "0", STR_PAD_LEFT);
        }else{
            $new_num = '0001';
        }
        return $new_num;
    }
    public function GetVendorsName($id)
    {
        $this->db->select('qur_khaal_vendor.Name');
        $this->db->from('qur_khaal_vendor');
        $this->db->where('qur_khaal_vendor.id', $id);
        return $this->db->get()->result();
    }

    public function GetVendorsNameAndCash($id)
    {
        $this->db->select('qur_khaal_vendor.Name,SUM(qur_cash_reciept.Amount) as Amount');
        $this->db->from('qur_cash_reciept');
        $this->db->join('qur_khaal_vendor', 'qur_cash_reciept.Vendor_Id=qur_khaal_vendor.Id');
        $this->db->where('qur_cash_reciept.Vendor_Id', $id);
        return $this->db->get()->result();
    }

    public function Delete($id)
    {
        $this->db->where('Id',$id);
        $this->db->delete('qur_cash_reciept');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}