<?php

class DemandFormModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function saveForm()
    {
        $Form_Number = $this->getFormNumber('item_demand_form');
        $this->db->set('Form_Number',$Form_Number);
        $this->db->set('Form_DateG',$_POST['Form_DateG']);
        $this->db->set('Form_DateH',$_POST['Form_DateH']);
        $this->db->set('Status',0);
        $this->db->set('level_id',$_POST['level_id']);
        $this->db->set('Donation_Type_Id',$_POST['Donation_type']);
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        if (isset($_POST['Department'])){
            $this->db->set('Department_Id',$_POST['Department']);
        }
        $this->db->insert('item_demand_form');
        if($this->db->affected_rows() > 0){
            $this->db->select('Id');
            $this->db->where('Form_Number',$Form_Number);
            $ID = $this->db->get('item_demand_form')->result();

            foreach ($_POST['ItemName'] as $key => $item) {
                $this->db->set('Demand_Form_Id',$ID[0]->Id);
                $this->db->set('Item_Quantity',$_POST['Item_Quantity'][$key]);
                $this->db->set('Item_remarks',$_POST['Detail'][$key]);
                $this->db->set('Item_Id',$_POST['Item_Id'][$key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('item_demand_form_details');
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
        $this->db->select('`item_demand_form`.`Department_Id`,`departments`.`DepartmentName`,item_demand_form.Donation_Type_Id,item_demand_form.CreatedOn,item_demand_form.CreatedBy,item_demand_form.Form_Number,item_demand_form_details.Item_Id,item_demand_form.Form_DateG, item_demand_form.Form_DateH,company_structure.LevelName,item_setup.name,item_demand_form_details.Item_Quantity, item_demand_form_details.Item_remarks,donation_type.Donation_type');
        $this->db->join('`departments`','`item_demand_form`.`Department_Id` = `departments`.`Id`');
        $this->db->join('item_demand_form_details','item_demand_form.Id = item_demand_form_details.Demand_Form_Id');
        $this->db->join('company_structure','item_demand_form.level_id = company_structure.id');
        $this->db->join('donation_type','item_demand_form.Donation_Type_Id = donation_type.Id','left');
        $this->db->join('item_setup','item_demand_form_details.Item_Id = item_setup.Id');
        $this->db->where('item_demand_form_details.Demand_Form_Id',$id);
        return $this->db->get('item_demand_form')->result();
    }

    public function FormUpdate()
    {
        $this->db->select('Id');
        $this->db->where('Form_Number',$_POST['Form_Number']);
        $ID = $this->db->get('item_demand_form')->result();

        $this->db->where('Demand_Form_Id',$ID[0]->Id);
        $this->db->delete('item_demand_form_details');

        $this->db->set('Form_DateG',$_POST['Form_DateG']);
        $this->db->set('Form_DateH',$_POST['Form_DateH']);
        $this->db->set('level_id',$_POST['level_id']);
        $this->db->set('Donation_Type_Id',$_POST['Donation_type']);
        $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
        $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));

        if (isset($_POST['Department'])){
            $this->db->set('Department_Id',$_POST['Department']);
        }
        $this->db->where('Id',$ID[0]->Id);
        $this->db->update('item_demand_form');
        
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
                $this->db->insert('item_demand_form_details');
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
        $this->db->select('item_issue.Form_Number, item_issue_details.Item_Id, item_issue_details.Issue_Quantity as issue_quantity, item_issue.Issued_DateG,item_issue.Issued_DateH,item_issue.Id,item_setup.name,item_issue.Remarks');
        $this->db->from('item_issue_details');
        $this->db->join('item_issue','item_issue_details.Item_Issue_Id = item_issue.Id');
        $this->db->join('item_setup','item_issue_details.Item_Id = item_setup.Id');
        $this->db->where('item_issue.Id',$id);
        return $this->db->get()->result();
    }

    public function Get_demand_quantity($id)
    {
        $this->db->select('departments.DepartmentName,item_demand_form_details.Item_Id,item_demand_form_details.Item_Quantity as Demand_Quantity, item_demand_form.Department_Id,company_structure.LevelName,company_structure.id as Level_Id');
        $this->db->from('item_demand_form');
        $this->db->join('item_demand_form_details','item_demand_form.Id = item_demand_form_details.Demand_Form_Id');
        $this->db->join('item_issue','item_demand_form_details.Demand_Form_Id=item_issue.Demand_Form_Id');
        $this->db->join('company_structure','item_demand_form.level_id = company_structure.id');
        // LEFT JOIN departments ON item_demand_form.Department_Id = departments.Id
        $this->db->join('departments','item_demand_form.Department_Id = departments.Id','left');
        $this->db->where('item_issue.Id',$id);
        return $this->db->get()->result();
    }

    public function Get_Demands($id)
    {
        $this->db->select('*,item_demand_form.Id as d_id');
        $this->db->from('item_demand_form_details');
        $this->db->join('item_demand_form', 'item_demand_form_details.Demand_Form_Id = item_demand_form.Id');
        $this->db->join('item_setup', 'item_demand_form_details.Item_Id = item_setup.Id');
        $this->db->join('company_structure', 'item_demand_form.level_id = company_structure.id');
        $this->db->where('item_demand_form.Id', $id);
        return $this->db->get()->result();
    }

    public function Get_Demands_For_Approval($status='')
    {
        $this->db->select('*,item_demand_form.Id as d_id');
        $this->db->from('item_demand_form');
        $this->db->join('company_structure', 'company_structure.id = item_demand_form.level_id');
        $this->db->join('donation_type', 'item_demand_form.Donation_Type_Id = donation_type.Id','left');
        $this->db->where_in('item_demand_form.Status', $status);

        $this->db->group_by('Form_Number');
        return $this->db->get()->result();
    }
}