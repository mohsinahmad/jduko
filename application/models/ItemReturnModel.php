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
        $this->load->model('CalenderModel');
    }

    public function GetItemIssued($is_direct = '',$is_sec = '')
    {
        $status = array(3,4);
        $this->db->select('`users`.`UserName`, `item_issue`.`Id`, `date_process`.`issue_dateG` as Issued_DateG, 
        `date_process`.`issue_dateH` as Issued_DateH, `item_issue`.`Form_Number` as `demand_no`, `item_issue`.`Form_Number`, 
        `item_issue`.`Status` ');
        if($_SESSION['user'][0]->IsAdmin != '1') {
            $this->db->where('users.UserName', $_SESSION['user'][0]->UserName);
        }
        $this->db->from('item_issue');
        $this->db->join('users', 'item_issue.CreatedBy = users.id','left');
        $this->db->join('date_process', 'date_process.demand_id = item_issue.Id','left');
        $this->db->where_in('`item_issue`.`Status`',$status);
        $this->db->where('`date_process`.`issue_dateG` !=',null);
//        $this->db->join('item_demand_form', 'item_demand_form.id = item_issue.Demand_Form_Id','left');
        if ($is_sec == '2') {
//            echo $is_sec;
            $this->db->where('item_issue.Receive_Slip_Id IS NOT NULL');
        }
        $this->db->group_by('item_issue.Form_Number');
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
        $a = 0;
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
             

        foreach ($_POST['Item_Id'] as $key => $value) {
            if($_POST['return_quantity'][$key] <= 0)
                continue;
            if ($a == 0) {
                 $this->db->insert('item_return');    
                 $return_id = $this->db->insert_id();   
            }
            $this->db->set('return_form_id',$return_id);
            $this->db->set('return_quantity',$_POST['return_quantity'][$key]);
            $this->db->set('Item_Id',$_POST['Item_Id'][$key]);
            $this->db->set('Item_remarks',$_POST['Item_remarks'][$key]);
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('item_return_details');
            $a++;
        }
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
        $this->db->select('issued_items_details.issue_quantity as Issue_Quantity');
        $this->db->from('item_return');
        $this->db->join('issued_items_details', 'item_return.Item_Issue_Form_Id = issued_items_details.demand_id');
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
        $this->db->select(' `item_return`.`Id`, `item_setup`.`name`, `item_setup`.`code`, 
        `item_return_details`.`return_quantity`, `item_return`.`return_dateG`, `item_return`.`return_dateH`, 
        `item_return_details`.`Item_remarks`, `donation_type`.`Donation_Type`, 
        `company_structure`.`LevelName`, ,item_setup_details.item_setup_id as item_id');
        $this->db->from('item_return');
        $this->db->join('item_return_details', '`item_return`.`Id` = `item_return_details`.`return_form_id`');
        $this->db->join('item_issue_details', 'item_return.Item_Issue_Form_Id = item_issue_details.Demand_Form_Id');
        $this->db->join('item_setup', 'item_return_details.Item_Id = item_setup.Id');
        $this->db->join('item_setup_details', '`item_setup_details`.`item_setup_id` = `item_setup`.`Id`');
        $this->db->join('donation_type', 'donation_type.Id = item_setup_details.donation_type');
        $this->db->join('company_structure', 'item_return.level_id = company_structure.id');
        $this->db->where('item_return.Id', $id);
        $this->db->group_by('item_setup.code');
        return $this->db->get()->result();
    }
    public function Status_Update($id)
    {
//        print_r($_POST);
        foreach ($_POST['item_id'] as $key => $value){
            $quantity = $this->db->get_where('item_stockrecieve_slip_details',
                array('id'=>$_POST['detail_id'][$key])
                )->result();
           $update_quan =   ($quantity[0]->Item_quantity + $_POST['quantity'][$key]);
           $this->db->set('Item_quantity',$update_quan);
           $this->db->where('id',$_POST['detail_id'][$key]);
           $this->db->update('item_stockrecieve_slip_details');
            $item_setup_quantity = $this->db->get_where('item_setup_details',
                array('item_setup_id'=>$_POST['item_id'][$key],
                    'donation_type'=>$_POST['donation'][$key]
                    )
            )->result();
            $quantity2 = ($item_setup_quantity[0]->current_quantity + $_POST['quantity'][$key]);
            $this->db->set('current_quantity',$quantity2);
            $this->db->where(array('item_setup_id'=>$_POST['item_id'][$key],
                'donation_type'=>$_POST['donation'][$key]
            ));
            $this->db->update('item_setup_details');


if($this->db->affected_rows() > 0){

$this->db->set('Status','1');
$this->db->where('id',$id);
$this->db->update('item_return');

}


        }
        return true;
    }
    public function Status_Update_Issue($id)
    {
        $hijri_Date = $this->CalenderModel->getHijriDate(date('Y-m-d'))[0]->Qm_date;
        $this->db->set('recieve_dateG',date('Y-m-d'));
        $this->db->set('recieve_dateH',$hijri_Date);
        $this->db->where('demand_id',$id);
        $this->db->update('date_process');
        $this->db->set('Status',4);
        $this->db->where('Id', $id);
        return $this->db->update('item_issue');
    }
}