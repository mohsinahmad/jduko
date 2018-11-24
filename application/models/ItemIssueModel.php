<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemIssueModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DemandFormModel');
        $this->load->model('CalenderModel');
    }
    public function get_Demand_Items($id)
    {
        $this->db->select('Form_Number');
        $this->db->where('Id', $id);
        $form_numb = $this->db->get('item_issue')->result();
        $this->db->select('*,item_issue.Id as d_id,item_setup.Id as S_ID,item_issue_details.id as issued_id');
        $this->db->join('company_structure','item_issue.level_id = company_structure.id');
        $this->db->join('departments', 'departments.Id = item_issue.Department_Id', 'left');
        $this->db->join('item_issue_details','item_issue.Id = item_issue_details.Demand_Form_Id','left');
        $this->db->join('item_setup','item_issue_details.Item_Id = item_setup.id','left');
        $this->db->where('item_issue_details.Approve_Quantity >',0);
        $this->db->where('item_issue.Form_Number',$form_numb[0]->Form_Number);
        return $this->db->get('item_issue')->result();
    }
    public function get_Demand_Items_edit($id)
    {
        $this->db->select('Form_Number');
        $this->db->where('Id', $id);
        $form_numb = $this->db->get('item_issue')->result();
        $this->db->select(' *, `item_issue_details`.`Id` as `d_id`, `item_issue`.`Id` as `demand_id`, `item_setup`.`Id` as `S_ID`, SUM(item_setup_details.current_quantity) as total');
        $this->db->join('company_structure','item_issue.level_id = company_structure.id');
        $this->db->join('departments', 'departments.Id = item_issue.Department_Id', 'left');
        $this->db->join('item_issue_details','item_issue.Id = item_issue_details.Demand_Form_Id','left');
        $this->db->join('item_setup','item_issue_details.Item_Id = item_setup.Id','left');
        $this->db->join('item_setup_details', 'item_setup_details.item_setup_id = item_issue_details.Item_Id','left');
        $this->db->order_by('s_id','asc');
        $this->db->group_by('item_issue_details.Item_Id');
        $this->db->where('item_issue.Form_Number',$form_numb[0]->Form_Number);
       return $this->db->get('item_issue')->result();
    }
    public function get_Demand_Quantity($id)
    {
        $this->db->select('item_issue_details.Item_Id,item_issue_details.Issue_Quantity as issued_Quantity');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details','item_issue.Id = item_issue_details.Item_Id');
        $this->db->where('item_issue.Demand_Form_Id',$id);
        return $this->db->get()->result();
    }
    public  function get_item_for_issue($id){
       $result =  $this->db->query('SELECT `item_issue_details`.`Item_Quantity`, sum(item_setup_details.current_quantity) as remain_quantity, `item_issue_details`.`Item_remarks`, `item_issue_details`.`Item_id`, `item_issue_details`.`CreatedOn`, `item_setup`.`name`, `donation_type`.`Donation_Type`, `item_issue_details`.`Id` as `issue_id`, `donation_type`.`Id` as `donation_id`
            FROM `item_issue_details`
            left JOIN `item_setup` ON `item_setup`.`Id` = `item_issue_details`.`Item_id`
            left join item_setup_details on item_setup_details.item_setup_id = item_setup.Id
            left JOIN `donation_type` ON `donation_type`.`Id` = `item_setup_details`.`donation_type`
            WHERE `item_setup`.`Id` = '.$id.'
            AND item_setup_details.current_quantity > 0
            GROUP BY `donation_type`.`donation_type`
            ORDER BY `donation_type`.`id`');
       return $result->result();
    }
    public function save_Issue_Item()
    {
       //  echo '<pre>';
       // print_r($_POST);
       // exit();
         //$Form_Number = $this->DemandFormModel->getFormNumber('item_issue');
//
//        echo $_POST['d_id'];
        // echo '<pre>';
        // print_r($_SESSION);
        // exit();
        // if (isset($_POST['d_id'])) {
           $this->updateStatus($_POST['d_id'],3);
            // echo $this->db->last_query();
            $hijri_Date = $this->CalenderModel->getHijriDate(date('Y-m-d'))[0]->Qm_date;
        //     $this->db->set('issue_dateG',date('Y-m-d'));
        //     $this->db->set('issue_dateH',$hijri_Date);
        //     $this->db->where('demand_id',$_POST['d_id']);
        //     $this->db->update('date_process');
        // }
        $points = json_decode($_POST["points"]);
       //  echo '<pre>';
       // print_r($points);
       // exit();
        foreach ($points as $key => $val){
            foreach ($val->QuantityArr as $key1 => $item){
                if(empty($val->QuantityArr[$key1])){
                    continue;
                    echo 'sufyan';
                }                    
                if(isset($val->QuantityArr[$key1])) {
                    //echo "true";
                    $this->db->where('donation_type', $val->donation[$key1]);
                    $this->db->where('item_setup_id', $val->item_id[$key1]);
                    $this->db->where('is_delete', '0');
                    $query_result = $this->db->get('item_setup_details');
                    $item_balance = '';
                    if ($query_result->num_rows() > 0) {
                        $result = $query_result->result();
                        $current_balance = ($result[0]->current_quantity - $val->QuantityArr[$key1]);
                        $item_balance = $current_balance;
                        $this->db->set('current_quantity', $current_balance);
                        $this->db->where('donation_type', $val->donation[$key1]);
                        $this->db->where('item_setup_id', $val->item_id[$key1]);
                        $this->db->where('is_delete', '0');
                        $this->db->update('item_setup_details');
                    }
                    $this->db->set('donation_type',$val->donation[$key1]);
                    $this->db->set('issue_quantity',$val->QuantityArr[$key1]);
                    $this->db->where('item_issue_details.id', $val->detail_id[$key1]);
                    $this->db->update('item_issue_details');


                    $this->db->set('demand_id',$val->Demand_id[$key1]);
                    // $this->db->set('stock_recieve_id',$val->QuantityArr[$key1]);
                    $this->db->set('item_issue_detail_id',$val->IDArr[$key1]);
                    $this->db->set('donation_id',$val->donation[$key1]);
                    $this->db->set('item_id',$val->item_id[$key1]);
                    $this->db->set('issue_quantity',$val->QuantityArr[$key1]);
                    $this->db->insert("issued_items_details");
                    
                    // $this->db->set('comp_id',$val->donation[$key1]);
                    // $this->db->set('department_id',$val->QuantityArr[$key1]);
                    // $this->db->set('user_id',$val->donation[$key1]);

                }
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function getCurrentQuantity($id,$donation)
    {
        $this->db->select('item_setup_details.current_quantity');
        $this->db->from('item_setup_details');
        $this->db->where('item_setup_id', $id);
        $this->db->where('donation_type', $donation);
        $this->db->where('is_delete', '0');
        return $this->db->get()->result();
    }
    public function updateStatus($id,$status)
    {
        $hijri_Date = $this->CalenderModel->getHijriDate(date('Y-m-d'))[0]->Qm_date;
        $this->db->where('Id', $id);
        $this->db->set('Status',$status);
        $this->db->set('Issued_DateH',$hijri_Date);
        $this->db->set("Issued_DateG",date("Y-m-d"));
        $this->db->update('item_issue');
    }
    public function Get_DataFor_Item_Stock($item_id,$to)
    {
        $this->db->select('`item_issue_details`.`Item_Id`, item_issue_details.Issue_Quantity as Issue_Quantity,item_issue.Form_Number,company_structure.LevelName,departments.DepartmentName,users.UserName,item_issue.Issued_DateG,item_issue.Issued_DateH');
        $this->db->join('item_issue','item_issue_details.Demand_Form_Id = item_issue.Id');
        $this->db->join('company_structure','item_issue.Level_Id=company_structure.id');
        $this->db->join('departments','item_issue.Department_Id=departments.Id');
        $this->db->join('users','item_issue.CreatedBy=users.id');
        $this->db->where('item_issue_details.Item_Id',$item_id);
        $this->db->where("item_issue.Issued_DateG < " , $to);
        $this->db->order_by('item_issue.Issued_DateH' , 'ASC');
        return $this->db->get('item_issue_details')->result();
    }
    public function Get_Data_For_ItemLadger($item,$to,$from)
    {
        $query = $this->db->query("SELECT * from( 
    SELECT item_stockrecieve_slip_details.Item_id as Item_id, item_stockrecieve_slip_details.Item_quantity as recive_quantity,item_stockrecieve_slip_details.Item_price,null as Issue_Quantity,null as return_quantity,item_stockrecieve_slip.Slip_number as Number ,null as LevelName,null as DepartmentName,item_stockrecieve_slip.Item_recieve_dateG as dateG,item_stockrecieve_slip.Item_recieve_dateH as dateH,null as UserName,item_stockrecieve_slip.Buyer_name,item_suppliers.NameU,donation_type.Donation_Type
    FROM item_stockrecieve_slip 
    JOIN item_stockrecieve_slip_details ON item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id 
    JOIN item_suppliers ON item_stockrecieve_slip.Supplier_Id=item_suppliers.Id
    JOIN donation_type ON donation_type.Id = item_stockrecieve_slip_details.donation_type
    Where `item_stockrecieve_slip`.`Item_recieve_dateG` < '$to'
    UNION ALL  

      SELECT issued_items_details.item_id as Item_id,null as Item_quantity,null as Item_price,issued_items_details.Issue_Quantity as issue_quantity,null as return_quantity,item_issue.Form_Number as Number ,company_structure.LevelName,departments.DepartmentName,item_issue.Issued_DateG as dateG,item_issue.Issued_DateH as dateH,users.UserName,null as Buyer_name,null as NameU,donation_type.Donation_Type
    FROM issued_items_details 
    JOIN item_issue ON issued_items_details.demand_id = item_issue.Id 
    JOIN company_structure ON item_issue.Level_Id=company_structure.id 
    JOIN departments ON item_issue.Department_Id=departments.Id 
    JOIN users ON item_issue.CreatedBy=users.id
    JOIN donation_type on donation_type.Id = issued_items_details.donation_id
    Where `item_issue`.`Issued_DateG` < '$to'
    UNION ALL 
    
    SELECT item_return_details.Item_Id as Item_id,null as Item_quantity,null as Item_price,null as Issue_Quantity, item_return_details.return_quantity as return_quantity, item_return.Id as Number,company_structure.LevelName,departments.DepartmentName,item_return.return_dateG as dateG, item_return.return_dateH as dateH,users.UserName,null as Buyer_name,null as NameU,null as Donation_Type 
    FROM item_return_details 
    JOIN item_return ON item_return_details.return_form_id = item_return.Id 
    JOIN company_structure ON item_return.level_id=company_structure.id 
    JOIN departments ON item_return.Department_Id=departments.Id
    JOIN users ON item_return.CreatedBy=users.id 
    Where `item_return`.`return_dateG` < '$to'

)  as t WHERE Item_id = '".$item."'");

        return $query->result();
    }
    public function Get_Issued_Item($Issue_Id)
    {
        $this->db->select('donation_type.Donation_Type,item_setup.code, `item_setup`.`name`');
        $this->db->join('item_issue_details', 'item_issue.Id=item_issue_details.Demand_Form_Id');
        $this->db->join('item_setup', 'item_issue_details.Item_Id=item_setup.Id');
        $this->db->join('item_setup_details', 'item_setup_details.item_setup_id = item_setup.Id');
        $this->db->join('donation_type', '`item_setup_details`.`donation_type`=`donation_type`.`Id`');
        $this->db->where('item_issue.Id', $Issue_Id);
        $this->db->group_by('item_issue.Id');
        return $this->db->get('item_issue')->result();
    }

    public function Get_data_for_Voucher($id)
    {
        $this->db->select('item_issue.Id,item_issue.Form_Number,item_issue.Remarks,item_issue.Issued_DateG,item_issue.Issued_DateH,item_issue_details.Issue_Quantity,item_setup.name,company_structure.LevelName,departments.DepartmentName,item_setup.code');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details','item_issue.Id=item_issue_details.Item_Id');
        $this->db->join('item_setup','item_issue_details.Item_Id=item_setup.Id');
        $this->db->join('company_structure', 'item_issue.Level_Id=company_structure.id', 'left');
        $this->db->join('departments', 'item_issue.Department_Id=departments.Id', 'left');
        $this->db->where('item_issue.Id', $id);
        return $this->db->get()->result();
    }

    public function Get_Issue_Voucher($id)
    {
        $this->db->select('`item_issue`.`Id`, `item_issue`.`Status`, `item_issue`.`Form_Number` as `issue_form`, `unit_of_measure`.`name` as `unit`, `item_issue`.`Form_Number`, `item_setup`.`name`, `item_issue`.`Issued_DateG` as `Issued_DateG`, `item_issue`.`Issued_DateH` as `Issued_DateH`,issued_items_details.item_id as Item_Id,`issued_items_details`.`issue_quantity` as `issue_quantity`,(select item_issue_details.Item_remarks  from item_issue_details WHERE item_issue_details.Item_Id = issued_items_details.item_id limit 1) as Remarks');
        $this->db->from('item_issue');
        $this->db->join('issued_items_details','issued_items_details.demand_id = item_issue.Id','left');
        $this->db->join('item_setup', '`issued_items_details`.`Item_Id` = `item_setup`.`Id`','left');
        $this->db->join('unit_of_measure','`unit_of_measure`.`id` = `item_setup`.`unit_of_measure`','left');        
        $this->db->where('item_issue.Id', $id);
        //$this->db->where('item_issue_details.Approve_Quantity >', 0);
        // $this->db->group_by('item_issue_details.Item_Id');
        return $this->db->get()->result();
    }

    //sufyan work start

    public function s_Get_Approve_Quanity($id)
    {
        //$where = 'Approve_Quantity > 0';
        $this->db->select('Approve_Quantity');
        $this->db->from('item_issue_details');
        $this->db->where('item_issue_details.Demand_Form_Id', $id);

      //  $this->db->where($where);
        return $this->db->get()->result();
//        echo $this->db->last_query();
    }

    //
    public function Get_Approve_Quanity($id)
    {
        $this->db->select('`item_issue_details`.`Approve_Quantity`');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details', '`item_issue_details`.`Demand_Form_Id`=`item_issue`.`Id`');
        $this->db->where('item_issue.Id', $id);
        return $this->db->get()->result();
    }
}