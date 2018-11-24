<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/28/2017
 * Time: 5:13 PM
 */

class QurSaleSlipModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Get_Slips($id='')
    {
        $this->db->select('*,qur_sale_slips.Id as S_ID');
        $this->db->join('qur_khaal_vendor ','qur_sale_slips.Vendor_Id = qur_khaal_vendor.Id');
        if ($id != '') {
            $this->db->where('qur_sale_slips.Id', $id);
        }
        $this->db->order_by('`qur_sale_slips`.`Slip_DateH`', 'ASC');
        return $this->db->get('qur_sale_slips')->result();
    }

    public function Save()
    {
        $this->db->set('Vendor_Id',$_POST['Vendor_Id']);
        $this->db->set('Slip_DateG',$_POST['Slip_DateG']);
        $this->db->set('Slip_DateH',$_POST['Slip_DateH']);
        isset($_POST['Car_Number'])?$this->db->set('Car_Number',$_POST['Car_Number']):$this->db->set('Car_Number',0);
        isset($_POST['old'][0])?$this->db->set('Cow_Old',$_POST['old'][0]):$this->db->set('Cow_Old',0);
        isset($_POST['fresh'][0])?$this->db->set('Cow_Fresh',$_POST['fresh'][0]):$this->db->set('Cow_Fresh',0);
        isset($_POST['old'][1])?$this->db->set('Goat_Old',$_POST['old'][1]):$this->db->set('Goat_Old',0);
        isset($_POST['fresh'][1])?$this->db->set('Goat_Fresh',$_POST['fresh'][1]):$this->db->set('Goat_Fresh',0);
        isset($_POST['old'][2])?$this->db->set('Sheep_Old',$_POST['old'][2]):$this->db->set('Sheep_Old',0);
        isset($_POST['fresh'][2])?$this->db->set('Sheep_Fresh',$_POST['fresh'][2]):$this->db->set('Sheep_Fresh',0);
        isset($_POST['old'][3])?$this->db->set('Camel_Old',$_POST['old'][3]):$this->db->set('Camel_Old',0);
        isset($_POST['fresh'][3])?$this->db->set('Camel_Fresh',$_POST['fresh'][3]):$this->db->set('Camel_Fresh',0);
        isset($_POST['old'][4])?$this->db->set('Buffelo_Old',$_POST['old'][4]):$this->db->set('Buffelo_Old',0);
        isset($_POST['fresh'][4])?$this->db->set('Buffelo_Fresh',$_POST['fresh'][4]):$this->db->set('Buffelo_Fresh',0);
        isset($_POST['fresh'][0])?$this->db->set('Fresh_Rate_Cow',$_POST['Fresh_Rate'][0]):$this->db->set('Fresh_Rate_Cow',0);
        isset($_POST['old'][0])?$this->db->set('Old_Rate_Cow',$_POST['Old_Rate'][0]):$this->db->set('Old_Rate_Cow',0);
        isset($_POST['fresh'][1])?$this->db->set('Fresh_Rate_Goat',$_POST['Fresh_Rate'][1]):$this->db->set('Fresh_Rate_Goat',0);
        isset($_POST['old'][1])?$this->db->set('Old_Rate_Goat',$_POST['Old_Rate'][1]):$this->db->set('Old_Rate_Goat',0);
        isset($_POST['fresh'][2])?$this->db->set('Fresh_Rate_Sheep',$_POST['Fresh_Rate'][2]):$this->db->set('Fresh_Rate_Sheep',0);
        isset($_POST['old'][2])?$this->db->set('Old_Rate_Sheep',$_POST['Old_Rate'][2]):$this->db->set('Old_Rate_Sheep',0);
        isset($_POST['fresh'][3])?$this->db->set('Fresh_Rate_Camel',$_POST['Fresh_Rate'][3]):$this->db->set('Fresh_Rate_Camel',0);
        isset($_POST['old'][3])?$this->db->set('Old_Rate_Camel',$_POST['Old_Rate'][3]):$this->db->set('Old_Rate_Camel',0);
        isset($_POST['fresh'][4])?$this->db->set('Fresh_Rate_Buffelo',$_POST['Fresh_Rate'][4]):$this->db->set('Fresh_Rate_Buffelo',0);
        isset($_POST['old'][4])?$this->db->set('Old_Rate_Buffelo',$_POST['Old_Rate'][4]):$this->db->set('Old_Rate_Buffelo',0);
        $this->db->set('Total_Amount',$_POST['Total_Amount']);
        if ($_POST['Edit']){
            $this->db->set('Slip_Number',$_POST['Slip_Number']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->where('Id',$_POST['Edit']);
            $this->db->Update('qur_sale_slips');
        }else{
            $Slip_Number = $this->Get_SlipNum();
            $this->db->set('Slip_Number',$Slip_Number);
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('qur_sale_slips');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_SlipNum()
    {
        $this->db->select_max('Slip_Number');
        $SNum = $this->db->get('qur_sale_slips')->result();
        if ($SNum[0]->Slip_Number != ''){
            $num = $SNum[0]->Slip_Number + 1;
            $new_num = str_pad($num, 4, "0", STR_PAD_LEFT);
        }else{
            $new_num = '0001';
        }
        return $new_num;
    }

    public function Get_Data_Vendor_Ledger($id)
    {
        $query = $this->db->query('SELECT * FROM ( SELECT qur_cash_reciept.Reciept_Number as Number, qur_cash_reciept.Vendor_Id, qur_cash_reciept.DateG, qur_cash_reciept.DateH, qur_cash_reciept.Amount, qur_cash_reciept.Remarks, null as Total_Amount,  null as Old_Rate_Cow, null as Cow_Old, null as Fresh_Rate_Cow, null as Cow_Fresh, null as Old_Rate_Goat, null as Old_Rate_Sheep, null as Old_Rate_Camel, null as Old_Rate_Buffelo, null as Fresh_Rate_Goat, null as Fresh_Rate_Sheep, null as Fresh_Rate_Camel, null as Fresh_Rate_Buffelo, null as Goat_Old, null as Goat_Fresh, null as Camel_Fresh, null as Camel_Old, null as Buffelo_Old, null as Buffelo_Fresh, null as Sheep_Old, null as Sheep_Fresh FROM qur_cash_reciept WHERE Is_Income = 0 
        UNION ALL
        SELECT qur_sale_slips.Slip_Number as Number, qur_sale_slips.Vendor_Id, qur_sale_slips.Slip_DateG, qur_sale_slips.Slip_DateH,   null as Amount, null as Remarks, qur_sale_slips.Total_Amount, qur_sale_slips.Old_Rate_Cow, qur_sale_slips.Cow_Old, qur_sale_slips.Fresh_Rate_Cow, qur_sale_slips.Cow_Fresh, qur_sale_slips.Old_Rate_Goat, qur_sale_slips.Old_Rate_Sheep, qur_sale_slips.Old_Rate_Camel, qur_sale_slips.Old_Rate_Buffelo, qur_sale_slips.Fresh_Rate_Goat, qur_sale_slips.Fresh_Rate_Sheep, qur_sale_slips.Fresh_Rate_Camel, qur_sale_slips.Fresh_Rate_Buffelo, qur_sale_slips.Goat_Old, qur_sale_slips.Goat_Fresh, qur_sale_slips.Camel_Fresh,qur_sale_slips.Camel_Old, qur_sale_slips.Buffelo_Old, qur_sale_slips.Buffelo_Fresh, qur_sale_slips.Sheep_Old, qur_sale_slips.Sheep_Fresh
        FROM qur_sale_slips) as t WHERE Vendor_Id = '.$id.' order BY DateH ASC');
        return $query->result();

    }

    public function Delete($id)
    {
        $this->db->where('Id',$id);
        $this->db->delete('qur_sale_slips');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Data_For_SaleSlip()
    {
        $this->db->Select('SUM(Old_Rate_Cow*Cow_Old) + SUM(Fresh_Rate_Cow*Cow_Fresh) as Cow_Amount,SUM(Cow_Old) + SUM(Cow_Fresh) As Cow_Quantity,SUM(Old_Rate_Goat*Goat_Old) +  SUM(Fresh_Rate_Goat*Goat_Fresh) as Goat_Amount,Sum(Goat_Old) + SUM(Goat_Fresh) as Goat_Quantity,SUM(Old_Rate_Sheep*Sheep_Old) + SUM(Fresh_Rate_Sheep*Sheep_Fresh) as Sheep_Amount,SUM(Sheep_Old) + SUM(Sheep_Fresh) as Sheep_Quantity,SUM(Old_Rate_Camel*Camel_Old) + SUM(Fresh_Rate_Camel*Camel_Fresh) as Camel_Amount,SUM(Camel_Old)+SUM(Camel_Fresh)as Camel_Quantity,SUM(Old_Rate_Buffelo*Buffelo_Old) +  SUM(Fresh_Rate_Buffelo*Buffelo_Fresh) as Buffalo_Amount,SUM(Buffelo_Old) + SUM(Buffelo_Fresh) as Buffalo_Quantity');
        return $this->db->get('qur_sale_slips')->result();
    }

    public function Get_Data_For_Vendor_Report($id)
    {
        $this->db->select('SUM(Cow_Old) as Cow_Old,SUM(Cow_Fresh) as Cow_Fresh,Old_Rate_Cow,Fresh_Rate_Cow,SUM(Goat_Old) as Goat_Old , SUM(Goat_Fresh) as Goat_Fresh,Old_Rate_Goat,Fresh_Rate_Goat,Sum(Sheep_Old) as Sheep_Old,SUM(Sheep_Fresh) as Sheep_Fresh,Old_Rate_Sheep,Fresh_Rate_Sheep,SUM(Camel_Old) as Camel_Old,SUM(Camel_Fresh) as Camel_Fresh, Old_Rate_Camel,Fresh_Rate_Camel,SUM(Buffelo_Old) as Buffelo_Old,SUM(Buffelo_Fresh) as Buffelo_Fresh,Old_Rate_Buffelo,Fresh_Rate_Buffelo');
        $this->db->where('Vendor_Id', $id);
        return $this->db->get('qur_sale_slips')->result();
    }
}