<?php
/**
 *  change its name as => ItemReturnModel
 */
class ItemReturnModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('ItemIssueModel');
    }

    public function GetItemIssued($is_direct = '')
    {
        $this->db->select('users.UserName,item_issue.Id,item_issue.Issued_DateG,item_issue.Issued_DateH,item_issue.Remarks, item_issue.Form_Number,item_issue.Status, item_issue.is_returned,item_issue.Receive_Slip_Id');
        if($_SESSION['user'][0]->IsAdmin != '1') {
            $this->db->where('users.UserName', $_SESSION['user'][0]->UserName);
        }
        $this->db->from('item_issue');
        $this->db->join('users', 'item_issue.CreatedBy = users.id');
        if ($is_direct != '') {
//            $this->db->where('item_issue.Receive_Slip_Id IS NOT NULL');
        }
//        $this->db->where('item_issue.is_returned', 0);
        return $this->db->get()->result();
    }

    public function GetItemReturned()
    {
        $this->db->select('item_return.return_dateG,users.UserName,item_return.return_dateH, item_return.Id as r_ID,item_return.Item_Issue_Form_Id, item_return.level_id, company_structure.LevelName,item_return.Status');
        $this->db->from('item_return');
        $this->db->join('company_structure', 'item_return.level_id = company_structure.id');
        $this->db->join('users', 'users.id = item_return.CreatedBy');
        if($_SESSION['user'][0]->UserName != 'admin') {
            $this->db->where('users.UserName', $_SESSION['user'][0]->UserName);
        }
//       echo $this->db->last_query();
        return $this->db->get()->result();
    }

    public function SaveReturn()
    {
        $id = $_POST['Item_Issue_Form_Id'];
        $this->db->set('return_dateG',$_POST['return_dateG']);
        $this->db->set('return_dateH',$_POST['return_dateH']);
        $this->db->set('Item_Issue_Form_Id',$_POST['Item_Issue_Form_Id']);
        $this->db->set('Status',0);
        if (isset($_POST['Department_Id'])){
            $this->db->set('Department_Id',$_POST['Department_Id']);
        }
        $this->db->set('level_id',$_POST['level_id']);
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        $this->db->insert('item_return');
        $return_id = $this->db->insert_id();
        foreach ($_POST['Item_Id'] as $key => $value) {
            $this->db->set('return_form_id',$return_id);
            $this->db->set('return_quantity',$_POST['return_quantity'][$key]);
            $this->db->set('Item_Id',$_POST['Item_Id'][$key]);
            $this->db->set('Item_remarks',$_POST['Item_remarks'][$key]);
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $currQ =  $this->ItemIssueModel->getCurrentQuantity($value);
            $this->db->insert('item_return_details');
            $updateQuan = $currQ[0]->current_quantity + $_POST['return_quantity'][$key];
            if($this->db->affected_rows() > 0){
                $this->db->where('Id', $value);
                $this->db->set('current_quantity',$updateQuan);
                $this->db->update('item_setup');
            }
        }
        $this->IsReturn($id);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function IsReturn($id)
    {
        $this->db->where('Id' ,$id);
        $this->db->set('is_returned',1);
        return $this->db->update('item_issue');
    }

    public function Get_Return($id)
    {
        $this->db->select('item_return.Id,item_setup.name,item_setup.code,item_return_details.return_quantity,item_return.return_dateG,item_return.return_dateH,item_return_details.Item_remarks');
        $this->db->from('item_return');
        $this->db->join('item_return_details', 'item_return.Id = item_return_details.return_form_id');
        $this->db->join('item_setup', 'item_return_details.Item_Id = item_setup.Id');
        $this->db->where('item_return.Id', $id);
        return $this->db->get()->result();
    }

    public function GetItemIssuedDetailed($id)
    {
        $this->db->select('item_issue_details.Issue_Quantity');
        $this->db->from('item_return');
        $this->db->join('item_issue_details', 'item_return.Item_Issue_Form_Id = item_issue_details.Item_Issue_Id');
        $this->db->where('item_return.Id', $id);
        return $this->db->get()->result();
    }

    public function Get_DataFor_Item_Stock($item_id,$to)
    {
        $this->db->select('`item_return_details`.`Item_Id`,(item_return_details.return_quantity) as Return_Quantity,,item_return.Id,item_return.return_dateG,item_return.return_dateH,company_structure.LevelName,departments.DepartmentName,users.UserName');
        $this->db->join('item_return','item_return_details.return_form_id = item_return.Id');
        $this->db->join('company_structure','item_return.level_id=company_structure.id');
        $this->db->join('departments','item_return.Department_Id=departments.Id');
        $this->db->join('users','item_return.CreatedBy=users.id');
        $this->db->where('item_return_details.Item_Id',$item_id);
        $this->db->where("item_return.return_dateG < " , $to);
        $this->db->order_by('item_return.return_dateH' , 'ASC');
        return $this->db->get('item_return_details')->result();
    }

    public function Get_Recived($id)
    {
//        $this->db->select('item_return.Id,item_setup.name,item_setup.code, item_issue_details.Issue_Quantity,item_return_details.return_quantity,item_return.return_dateG,item_return.return_dateH,item_return_details.Item_remarks,donation_type.Donation_Type,company_structure.LevelName');
        $this->db->select('item_return.Id,item_setup.name,item_setup.code,item_return_details.return_quantity,item_return.return_dateG,item_return.return_dateH,item_return_details.Item_remarks,donation_type.Donation_Type,company_structure.LevelName');
        $this->db->from('item_return');
        $this->db->join('item_return_details', 'item_return.Id = item_return_details.return_form_id');
//        $this->db->join('item_issue_details', 'item_return.Item_Issue_Form_Id = item_issue_details.Item_Issue_Id');
        $this->db->join('item_setup', 'item_return_details.Item_Id = item_setup.Id');
        $this->db->join('donation_type', 'item_setup.donation_type = donation_type.Id');
        $this->db->join('company_structure', 'item_return.level_id = company_structure.id');
        $this->db->where('item_return.Id', $id);
        return $this->db->get()->result();
    }

    public function Status_Update($id)
    {
        $this->db->set('Status',1);
        $this->db->where('Id', $id);
        $this->db->update('item_return');
    }

    public function Status_Update_Issue($id)
    {
        $this->db->set('Status',1);
        $this->db->where('Id', $id);
        $this->db->update('item_issue');

        $this->db->select('Demand_Form_Id');
        $this->db->where('Id', $id);
        $result = $this->db->get('item_issue')->result();

        $this->db->set('Status',5);
        $this->db->where('Id', $result[0]->Demand_Form_Id);
        return $this->db->update('item_demand_form');
    }
}