<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemIssueModel extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DemandFormModel');
    }

    public function get_Demand_Items($id)
    {
        $this->db->select('Form_Number');
        $this->db->where('Id', $id);
        $form_numb = $this->db->get('item_demand_form')->result();
        $this->db->select('*,item_demand_form.Id as d_id,item_setup.Id as S_ID');
        $this->db->join('company_structure','item_demand_form.level_id = company_structure.id');
        $this->db->join('departments', 'departments.Id = item_demand_form.Department_Id', 'left');
        $this->db->join('item_demand_form_details','item_demand_form.Id = item_demand_form_details.Demand_Form_Id','left');
        $this->db->join('item_setup','item_demand_form_details.Item_Id = item_setup.Id','left');
        $this->db->join('donation_type', 'donation_type.Id = item_setup.donation_type', 'left');
        $this->db->where('item_demand_form.Form_Number',$form_numb[0]->Form_Number);
        return $this->db->get('item_demand_form')->result();
    }

    public function get_Demand_Quantity($id)
    {
        $this->db->select('item_issue_details.Item_Id,item_issue_details.Issue_Quantity as issued_Quantity');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details','item_issue.Id = item_issue_details.Item_Issue_Id');
        $this->db->where('item_issue.Demand_Form_Id',$id);
        return $this->db->get()->result();
    }

    public function save_Issue_Item()
    {
        $count = 0;
        $CreatedBy = $_SESSION['user'][0]->id;
        $CreatedOn = date('Y-m-d H:i:s');
        $Form_Number = $this->DemandFormModel->getFormNumber('item_issue');
        $this->db->set('Form_Number',$Form_Number);
        isset($_POST['Demand_id'])?$this->db->set('Demand_Form_Id',$_POST['Demand_id']):"";
        isset($_POST['Approved_id'])?$this->db->set('Approved_form_Id',$_POST['Approved_id']):"";
        isset($_POST['Receive_Slip_Id'])?$this->db->set('Receive_Slip_Id',$_POST['Receive_Slip_Id']):"";
        isset($_POST['Status'])?$this->db->set('Status',$_POST['Status']):$this->db->set('Status',0);
        $this->db->set('Issued_DateG',$_POST['DateG']);
        $this->db->set('Issued_DateH',$_POST['DateH']);
        isset($_POST['Remarks'])?$this->db->set('Remarks',$_POST['Remarks']):"";
        isset($_POST['Department_Id'])?$this->db->set('Department_Id',$_POST['Department_Id']):"";
        isset($_POST['Level_Id'])?$this->db->set('Level_Id',$_POST['Level_Id']):"";

        $this->db->set('CreatedBy',$CreatedBy);
        $this->db->set('CreatedOn',$CreatedOn);
        $this->db->insert('item_issue');
        $issue_id = $this->db->insert_id();
        if (isset($_POST['Demand_id'])) {
            $this->updateStatus($_POST['Demand_id'],4);
        }

        foreach ($_POST['ItemArr'] as $key => $item) {
            $this->db->set('Item_Issue_Id',$issue_id);
            $this->db->set('Item_Id',$item);
            $this->db->set('CreatedBy',$CreatedBy);
            $this->db->set('CreatedOn',$CreatedOn);
            $this->db->set('Issue_Quantity',$_POST['QuantityArr'][$key]);
            $this->db->insert('item_issue_details');
            $currQ =  $this->getCurrentQuantity($item);
            $updateQuan = $currQ[0]->current_quantity - $_POST['QuantityArr'][$key];

            if($this->db->affected_rows() > 0){
                $this->db->where('Id', $item);
                $this->db->set('current_quantity',$updateQuan);
                $this->db->update('item_setup');
            }
        }

        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getCurrentQuantity($id)
    {
        $this->db->select('item_setup.Id,current_quantity,item_setup.donation_type,donation_type.Donation_Type');
        $this->db->from('item_setup');
        $this->db->join('donation_type', 'donation_type.Id = item_setup.donation_type', 'left');
        $this->db->where('item_setup.Id', $id);
        return $this->db->get()->result();
    }

    public function updateStatus($id,$status)
    {
        $this->db->where('Id', $id);
        $this->db->set('Status',$status);
        $this->db->update('item_demand_form');
    }

    public function Get_DataFor_Item_Stock($item_id,$to)
    {
        $this->db->select('`item_issue_details`.`Item_Id`, (item_issue_details.Issue_Quantity) as Issue_Quantity,item_issue.Form_Number,company_structure.LevelName,departments.DepartmentName,users.UserName,item_issue.Issued_DateG,item_issue.Issued_DateH');
        $this->db->join('item_issue','item_issue_details.Item_Issue_Id = item_issue.Id');
        $this->db->join('item_demand_form','item_issue.Demand_Form_Id=item_demand_form.Id');
        $this->db->join('company_structure','item_demand_form.Level_Id=company_structure.id');
        $this->db->join('departments','item_demand_form.Department_Id=departments.Id');
        $this->db->join('users','item_demand_form.CreatedBy=users.id');
        $this->db->where('item_issue_details.Item_Id',$item_id);
        $this->db->where("item_issue.Issued_DateG < " , $to);
        $this->db->order_by('item_issue.Issued_DateH' , 'ASC');
        return $this->db->get('item_issue_details')->result();
    }

    public function Get_Data_For_ItemLadger($item,$to,$from)
    {
        $query = $this->db->query("SELECT * from( 
    SELECT item_stockrecieve_slip_details.Item_id as Item_id, item_stockrecieve_slip_details.Item_quantity as recive_quantity,item_stockrecieve_slip_details.Item_price,null as Issue_Quantity,null as return_quantity,item_stockrecieve_slip.Slip_number as Number ,null as LevelName,null as DepartmentName,item_stockrecieve_slip.Item_recieve_dateG as dateG,item_stockrecieve_slip.Item_recieve_dateH as dateH,null as UserName,item_stockrecieve_slip.Buyer_name,item_suppliers.NameU 
    FROM item_stockrecieve_slip 
    JOIN item_stockrecieve_slip_details ON item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id 
    JOIN item_suppliers ON item_stockrecieve_slip.Supplier_Id=item_suppliers.Id 
    
    UNION ALL 
    
    SELECT item_issue_details.Item_Id as Item_id,null as Item_quantity,null as Item_price,item_issue_details.Issue_Quantity as issue_quantity,null as return_quantity,item_issue.Form_Number as Number ,company_structure.LevelName,departments.DepartmentName,item_issue.Issued_DateG as dateG,item_issue.Issued_DateH as dateH,users.UserName,null as Buyer_name,null as NameU 
    FROM item_issue_details 
    JOIN item_issue ON item_issue_details.Item_Issue_Id = item_issue.Id 
    JOIN item_demand_form ON item_issue.Demand_Form_Id=item_demand_form.Id 
    JOIN company_structure ON item_demand_form.Level_Id=company_structure.id 
    JOIN departments ON item_demand_form.Department_Id=departments.Id 
    JOIN users ON item_demand_form.CreatedBy=users.id 
    
    UNION ALL 
    
    SELECT item_return_details.Item_Id as Item_id,null as Item_quantity,null as Item_price,null as Issue_Quantity, item_return_details.return_quantity as return_quantity, item_return.Id as Number,company_structure.LevelName,departments.DepartmentName,item_return.return_dateG as dateG, item_return.return_dateH as dateH,users.UserName,null as Buyer_name,null as NameU 
    FROM item_return_details 
    JOIN item_return ON item_return_details.return_form_id = item_return.Id 
    JOIN company_structure ON item_return.level_id=company_structure.id 
    JOIN departments ON item_return.Department_Id=departments.Id 
    JOIN users ON item_return.CreatedBy=users.id 

) as t WHERE Item_id = '".$item."' AND dateG BETWEEN '.$to.' AND '".$from."' ORDER BY dateH ASC");

        return $query->result();
    }

    public function Get_Issued_Item($Issue_Id)
    {
        $this->db->select('donation_type.Donation_Type,item_setup.code, `item_setup`.`name`');
        $this->db->join('item_issue_details', 'item_issue.Id=item_issue_details.Item_Issue_Id');
        $this->db->join('item_setup', 'item_issue_details.Item_Id=item_setup.Id');
        $this->db->join('donation_type', 'item_setup.donation_type=donation_type.Id');
        $this->db->where('item_issue.Id', $Issue_Id);
        return $this->db->get('item_issue')->result();
    }

    public function Get_data_for_Voucher($id)
    {
        $this->db->select('item_issue.Id,item_issue.Form_Number,item_issue.Remarks,item_issue.Issued_DateG,item_issue.Issued_DateH,item_issue_details.Issue_Quantity,item_setup.name,company_structure.LevelName,departments.DepartmentName,item_setup.code');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details','item_issue.Id=item_issue_details.Item_Issue_Id');
        $this->db->join('item_setup','item_issue_details.Item_Id=item_setup.Id');
        $this->db->join('company_structure', 'item_issue.Level_Id=company_structure.id', 'left');
        $this->db->join('departments', 'item_issue.Department_Id=departments.Id', 'left');
        $this->db->where('item_issue.Id', $id);
        return $this->db->get()->result();
    }

    public function Get_Issue_Voucher($id)
    {
        $this->db->select('item_issue.Id,unit_of_measure.name as unit,item_issue.Form_Number,item_setup.name,item_issue.Issued_DateG,item_issue.Issued_DateH,item_issue.Remarks');
        $this->db->from('item_issue');
        $this->db->join('item_issue_details', 'item_issue.Id=item_issue_details.Item_Issue_Id');
        $this->db->join('item_setup', 'item_issue_details.Item_Id=item_setup.Id');
        $this->db->join('unit_of_measure','unit_of_measure.id = item_setup.unit_of_measure');
        $this->db->where('item_issue.Id', $id);
        return $this->db->get()->result();
    }

    //sufyan work start

    public function s_Get_Approve_Quanity($id)
    {
        $where = 'Approve_Quantity > 0';
        $this->db->select('item_demand_approve_details.Approve_Quantity');
        $this->db->from('item_issue');
        $this->db->join('item_demand_approve', 'item_issue.Approved_form_Id=item_demand_approve.Id');
        $this->db->join('item_demand_approve_details', 'item_demand_approve.Id= item_demand_approve_details.Demand_approve_id');
        $this->db->where('item_issue.Id', $id);
      //  $this->db->where($where);
        return $this->db->get()->result();
    }

    //
    public function Get_Approve_Quanity($id)
    {
        $this->db->select('item_demand_approve_details.Approve_Quantity');
        $this->db->from('item_issue');
        $this->db->join('item_demand_approve', 'item_issue.Approved_form_Id=item_demand_approve.Id');
        $this->db->join('item_demand_approve_details', 'item_demand_approve.Id= item_demand_approve_details.Demand_approve_id');
        $this->db->where('item_issue.Id', $id);
        return $this->db->get()->result();
    }
}