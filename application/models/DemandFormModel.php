<?php

class DemandFormModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CalenderModel');
    }
    public function check_item($page){
        $check_quantity = array();
        $item_name = "";
        foreach ($_POST['Item_Id'] as $key => $value) {
            $this->db->select('sum(current_quantity) as quantity');
            $this->db->where('item_setup_id',$value);
            if($page != "demand"){
                $this->db->where('donation_type',$_POST['donation'][$key]);
            }
            $result = $this->db->get("item_setup_details")->result();
            //echo $this->db->last_query();
            if($page == "demand"){
               if($result[0]->quantity != null && $result[0]->quantity > 0){
                $check_quantity[$key] = 'true';    
            }else{
                $check_quantity['false'][$key] = $value;
                $check_quantity[$key] = 'false';                
            } 
            }else{
                if($result[0]->quantity != null){
                $check_quantity[$key] = 'true';    
            }else{
                $check_quantity['false'][$key] = $value;
                $check_quantity[$key] = 'false';                
            }
        }
    }
        // echo '<pre>';
        // print_r($check_quantity);
        // exit();   

        if(in_array("false", $check_quantity)){  
        //echo "true";                 
                foreach ($check_quantity["false"] as $key => $value) {
                    $result_item = $this->db->get_where("item_setup",array('id'=> $value))->result();
                    $item_name .= ' , '.$result_item[0]->name;
                }

                if($page == "demand"){
                    $message = 'معازرت: براہ مہربانی اپنی ڈیمانڈ کی جانچ پڑتال فرمائیں، اپکی ڈیمانڈ کے درجہ زیل آئٹم'.$item_name.' کی مقدار زیرو ہے یا ان آئٹم کی اوپننگ نہی  کی گئ   ';
                }
                else{
                    $message = 'معازرت : درج کردہ وصولی کے درزجہ زیل آٹم   '.' '.$item_name.' '.'  کی دی گئی مد میں اوپننگ نہیں کی گئی اوپننگ کر  کے دوبارہ کوشش کریں';
                }
              $this->session->set_flashdata('error', $message);
               return false;
                
        }
        else{
            return  true;
        }
   }
    public function saveForm()
    {



        // $check_quantity = array();
        // $item_name = "";
        // foreach ($_POST['Item_Id'] as $key => $value) {
        //     $this->db->select('sum(current_quantity) as quantity');
        //     $this->db->where('item_setup_id',$value);
        //     $result = $this->db->get("item_setup_details")->result();
        //     //echo $this->db->last_query();
        //     if($result[0]->quantity != null && $result[0]->quantity > 0){
        //         $check_quantity[$key] = 'true';    
        //     }else{
        //         $check_quantity['false'][$key] = $value;
        //         $check_quantity[$key] = 'false';                
        //     }
        // }
        // if(!$this->check_item("demand")){  
        //echo "true";                 
              //   foreach ($check_quantity["false"] as $key => $value) {
              //       $result_item = $this->db->get_where("item_setup",array('id'=> $value))->result();
              //       $item_name .= ' , '.$result_item[0]->name;
              //   }

              //   $message = 'معازرت: براہ مہربانی اپنی ڈیمانڈ کی جانچ پڑتال فرمائیں، اپکی ڈیمانڈ کے ان آئٹم  '.$item_name.' '.'کی مقدار زیرو ہے یا ان آئٹم کی اوپننگ نہی  کی گئ   ';
              
              // $this->session->set_flashdata('error', $message);
              //  return false;
                // return false;
        // }else{

        // echo '<pre>';
        // print_r($_POST);
        // exit();

          $Form_Number = $this->getFormNumber('item_issue');
        $hijri_Date = $this->CalenderModel->getHijriDate(date('Y-m-d'))[0]->Qm_date;
        $this->db->set('Form_Number',$Form_Number);
        $this->db->set('Form_DateG',date('Y-m-d'));
        $this->db->set('Form_DateH',$hijri_Date);
        $this->db->set('Status',1);
        $this->db->set('level_id',$_POST['level_id']);
        //$this->db->set('Donation_Type_Id',$_POST['Donation_type']);
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        //$this->db->set('category_type',$_SESSION['type']);
        if (isset($_POST['Department'])){
            $this->db->set('Department_Id',$_POST['Department']);
        }
        $this->db->insert('item_issue');
        if($this->db->affected_rows() > 0){
            $this->db->select('Id');
            $this->db->where('Form_Number',$Form_Number);
            $ID = $this->db->get('item_issue')->result();
            foreach ($_POST['ItemName'] as $key => $item) {
                $this->db->set('Demand_Form_Id',$ID[0]->Id);
                $this->db->set('Item_Quantity',$_POST['Item_Quantity'][$key]);
                $this->db->set('Item_remarks',$_POST['Detail'][$key]);
                $this->db->set('Item_Id',$_POST['Item_Id'][$key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('item_issue_details');
            }
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
   // }
        
}

    public function getFormNumber($table)
    {
        $this->db->select_max('Form_Number');
        $max = $this->db->get($table)->result();
        if ($max[0]->Form_Number != ''){
            $max_num = $max[0]->Form_Number;
            $max_num++;
            $new_number = str_pad($max_num,4,0,STR_PAD_LEFT);
            return $new_number;
        }else{
            return '0001';
        }
    }
    public function getSlipNumber($table)
    {
        $this->db->select_max('Slip_number');
        $max = $this->db->get($table)->result();
        if ($max[0]->Slip_number != ''){
            $max_num = $max[0]->Slip_number;
            $max_num++;
            $new_number = str_pad($max_num,4,0,STR_PAD_LEFT);
            return $new_number;
        }else{
            return '0001';
        }
    }
   public function editForm($id)
    {
        $this->db->select('`item_issue`.`Department_Id`,users.UserName,
        `departments`.`DepartmentName`,item_issue.CreatedOn,
        item_issue.CreatedBy,item_issue.Form_Number,item_issue_details.Item_Id,
        item_issue.Form_DateG, item_issue.Form_DateH,company_structure.LevelName,
        item_setup.name,item_issue_details.Item_Quantity, item_issue_details.Item_remarks'
        );
        $this->db->join('`departments`','`item_issue`.`Department_Id` = `departments`.`Id`');
        $this->db->join('item_issue_details','item_issue.Id = item_issue_details.Demand_Form_Id');
        $this->db->join('company_structure','item_issue.level_id = company_structure.id');
//        $this->db->join('donation_type','item_demand_form.Donation_Type_Id = donation_type.Id','left');
        $this->db->join('item_setup','item_issue_details.Item_Id = item_setup.Id');
        $this->db->join('users','item_issue_details.CreatedBy = users.id');
        if($_SESSION['user'][0]->UserName != 'admin'){
            $this->db->where('users.UserName',$_SESSION['user'][0]->UserName);
        }
        $this->db->where('item_issue_details.Demand_Form_Id',$id);
        return $this->db->get('item_issue')->result();
    }
    public function FormUpdate()
    {
        $this->db->select('Id');
        $this->db->where('Form_Number',$_POST['Form_Number']);
        $ID = $this->db->get('item_issue')->result();

        $this->db->where('Demand_Form_Id',$ID[0]->Id);
        $this->db->delete('item_issue_details');

        $this->db->set('Form_DateG',$_POST['Form_DateG']);
        $this->db->set('Form_DateH',$_POST['Form_DateH']);
        $this->db->set('level_id',$_POST['level_id']);
//        $this->db->set('Donation_Type_Id',$_POST['Donation_type']);
        $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
        $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));

        if (isset($_POST['Department'])){
            $this->db->set('Department_Id',$_POST['Department']);
        }
        $this->db->where('Id',$ID[0]->Id);
        $this->db->update('item_issue');
        
        if($this->db->affected_rows() > 0){
            foreach ($_POST['ItemName'] as $key => $item) {
                $this->db->set('Demand_Form_Id',$ID[0]->Id);
                $this->db->set('Item_Quantity',$_POST['Item_Quantity'][$key]);
                $this->db->set('Item_remarks',$_POST['Detail'][$key]);
                $this->db->set('Item_Id',$_POST['Item_Id'][$key]);
                $this->db->set('CreatedBy',$_POST['CreatedBy']);
                $this->db->set('CreatedOn',$_POST['CreatedOn']);
                $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('item_issue_details');
            }
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function get_issue($id)
    {
        $this->db->select('`item_issue`.`Form_Number`, `item_issue_details`.`Item_Id`,item_issue_details.donation_type, 
        `item_issue_details`.`Approve_Quantity` as `issue_quantity`, date_process.issue_dateG as Issued_DateG,
        date_process.issue_dateH as Issued_DateH,  `item_issue`.`Id`, item_issue_details.Approve_Quantity,
        `item_setup`.`name`, `item_issue_details`.`Item_remarks` as Remarks,issued_items_details.issue_quantity');
        $this->db->from('item_issue_details');
        $this->db->join('item_issue','`item_issue_details`.`Demand_Form_Id` = `item_issue`.`Id`','left');
        $this->db->join('item_setup','item_issue_details.Item_Id = item_setup.Id','left');
        $this->db->join('date_process','date_process.demand_id = item_issue.Id','left');
        $this->db->join('donation_type','item_issue_details.donation_type = donation_type.Id','left');
        $this->db->join('issued_items_details','issued_items_details.demand_id = item_issue.Id','left');
        $this->db->where('item_issue.Id',$id);
        $this->db->where('item_issue_details.Approve_Quantity >',0);
        $this->db->group_by('`item_issue_details`.`Item_Id`');
        return $this->db->get()->result();
    }

    public function Get_demand_quantity($id)
    {
        $this->db->select(' `departments`.`DepartmentName`, `item_issue_details`.`Item_Id`, sum(`issued_items_details`.`issue_quantity`) as `issue_quantity`, `item_issue_details`.`Item_Quantity` as `Demand_Quantity`, `item_issue`.`Department_Id`, `company_structure`.`LevelName`, `company_structure`.`id` as `Level_Id`, `item_issue_details`.`Item_remarks` as `Remarks`, `date_process`.`issue_dateG` as `Issued_DateG`, `date_process`.`issue_dateH` as `Issued_DateH`');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details','`item_issue`.`Id` = `item_issue_details`.`Demand_Form_Id`');
        $this->db->join('company_structure','`item_issue`.`level_id` = `company_structure`.`id`');
        $this->db->join('departments','`item_issue`.`Department_Id` = `departments`.`Id`');
        $this->db->join('date_process','date_process.demand_id  = item_issue.Id');
        $this->db->join('issued_items_details','item_issue_details.Id = issued_items_details.item_issue_detail_id','left');
        // LEFT JOIN departments ON item_demand_form.Department_Id = departments.Id
//        $this->db->join('departments','item_demand_form.Department_Id = departments.Id','left');
        $this->db->where('item_issue_details.Demand_Form_Id',$id);
        $this->db->group_by('`item_issue_details`.`Item_Id`');
        return $this->db->get()->result();
    }
    public function Get_Demands($id)
    {
        $this->db->select('item_issue.Id,item_issue.Form_Number,
        item_setup.name as item_name ,unit_of_measure.name as unit, item_issue_details.Item_Quantity,
        item_issue_details.Item_remarks,company_structure.LevelName,item_issue.Form_DateG,
        item_issue.Form_DateH,item_issue_details.Approve_Quantity');
        $this->db->from('item_issue_details');
        $this->db->join('item_issue', 'item_issue.id = item_issue_details.Demand_Form_Id');
        $this->db->join('item_setup', 'item_setup.id = item_issue_details.Item_Id');
        $this->db->join('unit_of_measure', 'item_setup.unit_of_measure = unit_of_measure.id');
        $this->db->join('company_structure', 'company_structure.id = item_issue.level_id');
        $this->db->where('item_issue.Id', $id);
        return $this->db->get()->result();
    }
    public function Get_Demands_For_Approval($status='')
    {
        $this->db->select('*,item_issue.id as d_id');
        $this->db->from('item_issue');
        $this->db->join('company_structure', 'company_structure.id = item_issue.level_id');
        $this->db->join('departments', 'item_issue.Department_Id = departments.Id');
        $this->db->where_in('item_issue.Status', $status);
        $this->db->group_by('Form_Number');
        return $this->db->get()->result();
    }
    public function get_demand_item_detail($code){
        $this->db->select('donation_type.Donation_Type,sum(item_setup_details.current_quantity) as total_quantity');
        $this->db->from('item_setup_details');
        $this->db->join('donation_type', 'donation_type.Id = item_setup_details.donation_type');
        $this->db->where('item_setup_details.item_setup_id', $code);
        $this->db->group_by('item_setup_details.donation_type');
        return $this->db->get()->result();
  }
}
