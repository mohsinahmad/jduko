<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 7/31/2017
 * Time: 4:51 PM
 */

class QurSlipModel extends CI_Model
{
    private $Slip_Number;
    private $Name;
    private $Address;
    private $Phone_Number;
    private $Mobile_Number;
    private $Time;
    private $Slip_Date_G;
    private $Slip_Date_H;
    private $Collection_Day;
    private $Collection_Status;
    private $Total_Amount;
    private $CreatedBy;
    private $CreatedOn;
    private $UpdatedBy;
    private $UpdatedOn;
    private $Cow_Number;
    private $Cow_Quantity;
    private $Cow_Type;
    private $Receipt_Type;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurCowModel');
    }

    public function Save_Receipt()
    {
        //Get Maximum Slip Number
        $max_slipNo = $this->get_max_slipNo();
        if($max_slipNo->num_rows() > 0){
            $this->Slip_Number = $max_slipNo->row()->Slip_Number;
            $Slip_Number = ++$this->Slip_Number;
            $new = str_pad($Slip_Number, 5, "0", STR_PAD_LEFT);
            $this->Slip_Number = $new;
        }else{
            $this->Slip_Number = '00001';
        }

        $this->Receipt_Type = $_POST['ReceiptType'];
        $this->Cow_Number = $_POST['CowNumber'];
        $this->Cow_Quantity = $_POST['quantity'];
        $this->Time = $_POST['Time'];
        $this->Collection_Day = $_POST['Collection_Day'];
        isset($_POST['EqualyDivide'])?$Equality = 1:$Equality = 0;

        //Check Cow Number
        $verifyCow = $this->QurCowModel->verifyCowNumber($this->Cow_Number,$this->Collection_Day);

        //Opening New Cow
        if(!($verifyCow->num_rows() > 0)){ //if cow number is not fount
        }else{ // if cow number is found
            $cow_Exist = $verifyCow->result();
            $Already_Quantity = $cow_Exist[0]->Count;
            $New_quantity = $Already_Quantity + $this->Cow_Quantity;

            $this->db->set('Count',$New_quantity);
            $this->db->where('Code',$this->Cow_Number);
            $this->db->where('Day', $this->Collection_Day);
            ($this->Receipt_Type == 0)?$this->db->set('Self_Cow',1):$this->db->set('Self_Cow',0);
            $this->db->update('qur_cow');
        }
        //Saving Slip
        $this->db->set('Slip_Number',$this->Slip_Number);
        $this->Name = $_POST['name'];
        $this->db->set('Name',$this->Name);
        $this->db->set('Quantity',$this->Cow_Quantity);
        $this->Address = $_POST['Address'];
        $this->db->set('Address',$this->Address);
        $this->Phone_Number = $_POST['Phone_Number'];
        $this->db->set('Phone_Number',$this->Phone_Number);
        $this->Mobile_Number = $_POST['Mobile_Number'];
        $this->db->set('Mobile_Number',$this->Mobile_Number);
        $this->Slip_Date_G = $_POST['ReceiptDateG'];
        $this->db->set('Slip_Date_G',$this->Slip_Date_G);
        $this->Slip_Date_H = $_POST['ReceiptDateH'];
        $this->db->set('Slip_Date_H',$this->Slip_Date_H);
        $this->db->set('Collection_Day',$this->Collection_Day);
        ($this->Receipt_Type == 0)?$this->db->set('Slip_Type',1):'';
        isset($_POST['NotPaid'])?$this->db->set('Paid',0):'';
        $this->Total_Amount = $_POST['TotalAmount'];
        $this->db->set('Total_Amount',$this->Total_Amount);
        $this->CreatedBy = $_SESSION['user'][0]->id;
        $this->db->set('CreatedBy',$this->CreatedBy);
        $this->CreatedOn = date('Y-m-d H:i:s');
        $this->db->set('CreatedOn',$this->CreatedOn);
        $this->db->insert('qur_slip');
        $slip_id = $this->db->insert_id();

        //Getting Cow_id By Cow Number
        $Cow_id = $this->QurCowModel->getCowIdByNumber($this->Cow_Number,$this->Collection_Day);

        //Saving Qurbani Hissa Daran Data
        foreach ($_POST['HissaName'] as $key => $value) {
            if ($value != ''){
                $this->db->set('Name',$value);
                $this->db->set('Slip_id',$slip_id);
                $this->db->set('Cow_id',$Cow_id->id);
                isset($_POST['HissaWaqf'.$key]) ? $this->db->set('JDK_Count',$_POST['HissaWaqf'.$key]) : $this->db->set('JDK_Count',0);
                $this->db->set('Description',$_POST['HissaDescription'][$key]);
                $this->db->insert('qur_hissa_dar');
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Delete_Slip($id,$Day)
    {
        $this->db->select('qur_slip.Quantity as Quantity,qur_cow.Count,qur_cow.Code as Cow_code,qur_cow.Self_Cow');
        $this->db->join('qur_hissa_dar','qur_slip.Id = qur_hissa_dar.Slip_id');
        $this->db->join('qur_cow','qur_hissa_dar.Cow_id = qur_cow.Id');
        $this->db->where('qur_hissa_dar.Slip_id',$id);
        $this->db->where('qur_cow.Day', $Day);
        $hissa_dar = $this->db->get('qur_slip')->result();

        $quantity_delete = $hissa_dar[0]->Quantity;
        $Cow_quantity = $hissa_dar[0]->Count;
        $Updated_Quantity = $Cow_quantity - $quantity_delete;

        $this->db->set('Count',$Updated_Quantity);
        $this->db->set('Self_Cow',0);
        $this->db->where('Code',$hissa_dar[0]->Cow_code);
        $this->db->where('Day', $Day);
        $this->db->update('qur_cow');

        $this->db->where('Slip_id',$id);
        $this->db->delete('qur_hissa_dar');
        if($this->db->affected_rows() > 0){
            $this->db->where('Id',$id);
            $this->db->delete('qur_slip');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function Update_Slip($id)
    {
        $this->Collection_Day = $_POST['Collection_Day'];
        $isDeleted = $this->Delete_Slip($id,$this->Collection_Day);
        if($isDeleted){
            $isSaved = $this->Save_Receipt();
            if($isSaved){
                return true;
            }else{
                return false;
            }
//            $this->Slip_Number = $_POST['Slip_Number'];
//            $this->Receipt_Type = $_POST['ReceiptType'];
//            $this->Cow_Number = $_POST['CowNumber'];
//            $this->Cow_Quantity = $_POST['quantity'];
//            $this->Time = $_POST['Time'];;
//            if(isset($_POST['EqualyDivide'])){
//                $Equality = 1;
//            }else{
//                $Equality = 0;
//            }
//
//            //Check Cow Number
//            $verifyCow = $this->QurCowModel->verifyCowNumber($this->Cow_Number,$this->Collection_Day);
//
//            //Opening New Cow
//            if(!($verifyCow->num_rows() > 0)){ //if cow number is not fount
//                if($this->Receipt_Type == 0){ //Self Cow
//                    // $Cow_Number = $this->QurHissaModel->get_self_cow_number();
//                    // $Self_Cow_No = $Cow_Number->Self_Cow_No;
//                    $this->db->set('Code',$this->Cow_Number);
//                    $this->db->set('Time',$this->Time);
//                    $this->db->set('Day',$this->Collection_Day);
//                    $this->db->set('Count',$this->Cow_Quantity);
//                    //$this->db->set('Self_Cow_No',$Self_Cow_No);
//                    $this->db->set('Equal_Destribution',$Equality);
//                    $this->db->set('Self_Cow',1);
//                    $this->db->insert('qur_cow');
//                    $this->Cow_Quantity = 7;
//                    // if($this->db->affected_rows() > 0){
//                    //     $this->QurHissaModel->update_self_cow_number($Self_Cow_No);
//                    // }
//                }else{
//                    $this->db->set('Code',$this->Cow_Number);
//                    $this->db->set('Count',$this->Cow_Quantity);
//                    $this->db->set('Time',$this->Time);
//                    $this->db->set('Equal_Destribution',$Equality);
//                    $this->db->set('Day',$this->Collection_Day);
//                    $this->db->set('Self_Cow',0);
//                    $this->db->insert('qur_cow');
//                }
//            }else{ // if cow number is found
//                $cow_Exist = $verifyCow->result();
//                $Already_Quantity = $cow_Exist[0]->Count;
//                $New_quantity = $Already_Quantity + $this->Cow_Quantity;
//
//                $this->db->set('Count',$New_quantity);
//                $this->db->where('Day', $this->Collection_Day);
//                if($this->Receipt_Type == 1){
//                    $this->db->where('Self_Cow', 0);
//                }else{
//                    $this->db->where('Self_Cow', 1);
//                }
//                $this->db->where('Code',$this->Cow_Number);
//                $this->db->update('qur_cow');
//            }
//
//            //Saving Slip
//            $this->db->set('Slip_Number',$this->Slip_Number);
//            $this->Name = $_POST['name'];
//            $this->db->set('Name',$this->Name);
//            //$this->db->set('Quantity',$this->Cow_Quantity);
//            $this->Address = $_POST['Address'];
//            $this->db->set('Address',$this->Address);
//            $this->Phone_Number = $_POST['Phone_Number'];
//            $this->db->set('Phone_Number',$this->Phone_Number);
//            $this->Mobile_Number = $_POST['Mobile_Number'];
//            $this->db->set('Mobile_Number',$this->Mobile_Number);
//            $this->Slip_Date_G = $_POST['ReceiptDateG'];
//            $this->db->set('Slip_Date_G',$this->Slip_Date_G);
//            $this->db->set('Quantity',$_POST['quantity']);
//            $this->Slip_Date_H = $_POST['ReceiptDateH'];
//            $this->db->set('Slip_Date_H',$this->Slip_Date_H);
//            $this->db->set('Collection_Day',$this->Collection_Day);
//            ($this->Receipt_Type == 0)?$this->db->set('Slip_Type',1):'';
//            isset($_POST['NotPaid'])?$this->db->set('Paid',0):'';
//            //$this->Collection_Status = $_POST['Collection_Status'];
//            //$this->db->set('Collection_status',$this->Collection_Status);
//            $this->Total_Amount = $_POST['TotalAmount'];
//            $this->db->set('Total_Amount',$this->Total_Amount);
//            $this->CreatedBy = $_SESSION['user'][0]->id;
//            $this->db->set('CreatedBy',$this->CreatedBy);
//            $this->CreatedOn = date('Y-m-d H:i:s');
//            $this->db->set('CreatedOn',$this->CreatedOn);
//            $this->db->insert('qur_slip');
//            $slip_id = $this->db->insert_id();
//            //Getting Cow_id By Cow Number
//            $Cow_id = $this->QurCowModel->getCowIdByNumber($this->Cow_Number,$this->Collection_Day);
//            //Saving Qurbani Hissa Daran Data
//            foreach ($_POST['HissaName'] as $key => $value) {
//                if ($value != ''){
//                    $this->db->set('Name',$value);
//                    $this->db->set('Slip_id',$slip_id);
//                    $this->db->set('Cow_id',$Cow_id->id);
//                    isset($_POST['HissaWaqf'.$key]) ? $this->db->set('JDK_Count',$_POST['HissaWaqf'.$key]) : $this->db->set('JDK_Count',0);
//                    $this->db->set('Description',$_POST['HissaDescription'][$key]);
//                    $this->db->insert('qur_hissa_dar');
//                }
//            }
//            if($this->db->affected_rows() > 0){
//                return true;
//            }else{
//                return false;
//            }
        }else{
            return false;
        }
    }

    public function Get_Slip_No($id='',$Day='',$to ='',$from ='',$cow_number='')
    {
        $this->db->select('qur_slip.Paid,qur_slip.Quantity as total_quantity,qur_slip.Id as S_ID,qur_cow.Code,qur_slip.Slip_Number,qur_slip.Name,qur_slip.Slip_Date_H,qur_slip.Slip_Date_G,qur_slip.Collection_Day,qur_slip.Quantity,qur_slip.Total_Amount,Code,Count,Time,Day,Self_Cow,qur_hissa_dar.Name as NameI,qur_hissa_dar.Quantity,Phone_Number,Mobile_Number,Address,Collection_Day,qur_cow.Equal_Destribution,qur_hissa_dar.Description,qur_hissa_dar.JDK_Count as HissaWaqfCount,qur_cow.Day');
        $this->db->from('qur_slip');
        $this->db->join('qur_hissa_dar', 'qur_hissa_dar.Slip_id=qur_slip.Id','Left');
        $this->db->join('qur_cow', 'qur_hissa_dar.Cow_id=qur_cow.Id','Left');
        if($id != ''){
            $this->db->where('qur_slip.Id', $id);
        }elseif($Day != ''){
            $this->db->where('qur_cow.Day', $Day);
        }elseif($to != '' && $from != ''){
            $this->db->where("qur_slip.Slip_Date_G BETWEEN '" . $to . "' AND '" . $from . "'");
            $this->db->order_by('qur_slip.Slip_Number', 'ASC');
            $this->db->group_by('qur_slip.Slip_Number');
        }elseif($cow_number != ''){
            $this->db->where('qur_cow.Code', $cow_number);
            $this->db->order_by('qur_slip.Slip_Number', 'ASC');
            $this->db->group_by('qur_slip.Slip_Number');
        }else{
            $this->db->order_by('qur_slip.Slip_Number', 'ASC');
            $this->db->group_by('qur_slip.Slip_Number');
        }
        $this->db->order_by('qur_slip.Slip_Number', 'DESC');
        return $this->db->get()->result();
    }

    public function get_max_slipNo()
    {
        $this->db->select_max('Slip_Number');
        return $this->db->get('qur_slip');
    }

    public function Get_Amount_Via_Date($Day = '',$Is_Ijemai)
    {
        $this->db->select('IFNULL(SUM(qur_slip.Quantity),0) as Quantity, IFNULL(SUM(qur_slip.Total_Amount),0) As Total');
        if ($Day != ''){
            $this->db->where('qur_slip.Collection_Day',$Day);
        }
        $this->db->where('Slip_Type',$Is_Ijemai);
        $this->db->where('Paid',1);
        return $this->db->get('qur_slip')->result();
    }

    public function Get_Dates_For_Report($from,$to)
    {
        $this->db->select('Slip_Date_H,Slip_Date_G');
        $this->db->where("Slip_Date_G BETWEEN '".$from."' AND '".$to."'");
        $this->db->group_by('Slip_Date_H');
        return $this->db->get('qur_slip')->result();
    }

    public function getReportData($date,$days,$self)
    {
        foreach ($days as $day) {
            $this->db->select("Collection_Day, Slip_Date_G, (Slip_Number) as slip_count, (qur_slip.Quantity) as Quantity, (Total_Amount)");
            $this->db->join('qur_hissa_dar','qur_slip.Id = qur_hissa_dar.Slip_id');
            $this->db->join('qur_cow','qur_hissa_dar.Cow_id = qur_cow.Id');
            $this->db->where('qur_slip.Slip_Date_G',$date);
            $this->db->where('qur_slip.Collection_Day',$day);
            $this->db->where('qur_cow.Self_Cow',$self);
            $this->db->where('Paid',1);
            $this->db->group_by('qur_slip.Slip_Number');
            $result[] = $this->db->get('qur_slip')->result();
        }
        return $result;
    }

    public function Get_Income_Day_Wise($day,$self)
    {
        $this->db->select('Collection_Day,COUNT(Slip_Number) as Count,SUM(Quantity) as Quantity, SUM(Total_Amount) as Amount');
        $this->db->where('Collection_Day',$day);
        $this->db->where('Slip_Type',$self);
        return $this->db->get('qur_slip')->result();
    }

    public function Get_Income_Loc_Wise($Loc)
    {
        $this->db->select('SUM(Total_Amount) as Amount');
        $this->db->where('Loc_Id',$Loc);
        return $this->db->get('qur_slip')->result();
    }
}