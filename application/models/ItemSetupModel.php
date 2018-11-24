<?php
class ItemSetupModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('CalenderModel');
        $this->load->model('DemandFormModel');
    }
    public function GetItems()
    {
       if($_SESSION['user'][0]->id != 1){
            $this->db->where('CreatedBy',$_SESSION['user'][0]->id);
        }
        return $this->db->get('item_setup')->result();
    }
    public function add_unit($unit_name){
        $data['name'] = $unit_name;
        $data['CreatedBy'] =  $_SESSION['user'][0]->id;
        $data['CreatedOn'] =  date('Y-m-d H:i:s');
        $this->db->insert('unit_of_measure',$data);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function add_item($item_name,$catid,$unit){
        $Item_Code = $this->Check_Item_Code();
        $data['name'] = $item_name;
        $data['unit_of_measure'] = $unit;
        $data['code'] = $Item_Code;
        $data['category_Id'] = $catid;
        $data['CreatedBy'] =  $_SESSION['user'][0]->id;
        $data['CreatedOn'] =  date('Y-m-d H:i:s');
        $this->db->insert('item_setup',$data);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function  get_item_code($id){
            $this->db->select('code');
            $this->db->where('id',$id);
            return $this->db->get('item_setup')->result_array();
    }



    public function Save_Item()
    {
          $this->form_validation->set_rules('items','items','trim|required');
          $this->form_validation->set_rules('sub-category','sub-category','trim|required');
          $this->form_validation->set_rules('DonationType[]','DonationType','trim|required');
          $this->form_validation->set_rules('OpeningQuanity[]','OpeningQuanity','trim|required');
          $this->form_validation->set_rules('CurrentQuanity[]','CurrentQuanity','trim|required');
        $hijri_Date = $this->CalenderModel->getHijriDate(date('Y-m-d'))[0]->Qm_date;
        $Slip_number = $this->DemandFormModel->getSlipNumber('item_stockrecieve_slip');
        if($this->form_validation->run() == true){
              $data['Slip_number'] = $Slip_number;
              $data['Supplier_Id'] = $_SESSION['user'][0]->id;
              $data['Purchase_dateG'] = date('Y-m-d');
              $data['Purchase_dateH'] = $hijri_Date;
              $data['Item_recieve_dateG'] = date('Y-m-d');
              $data['Item_recieve_dateH'] = $hijri_Date;
              $data['Buyer_name'] = $_SESSION['user'][0]->UserName;
              $data['CreatedBy'] =  $_SESSION['user'][0]->id;
              $data['CreatedOn'] = date('Y-m-d H:i:s');
              // $this->db->insert('item_stockrecieve_slip',$data);
              // $id = $this->db->insert_id();
              foreach ($_POST['OpeningQuanity'] as $key => $value) {
              if ($value != '') {
                $this->db->set('item_setup_id',$this->input->post('items'));
                $this->db->set('opening_quantity',$_POST['OpeningQuanity'][$key]);
                $this->db->set('current_quantity',$_POST['CurrentQuanity'][$key]);
                $this->db->set('donation_type',$_POST['DonationType'][$key]);
                // $this->db->set('date_H',$hijri_Date);
                // $this->db->set('date_g',date('Y-m-d'));
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
              }
              $this->db->insert('item_setup_details');
           }
          }
          else{
          }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function check_duplicate_item_details($itemcode,$donation_type){
        $this->db->where('code',$itemcode);
        $this->db->where('donation_type',$donation_type);
        return $this->db->get('item_setup_details')->result_array();
    }
    public function Check_Item_Code()
    {
        $this->db->select_max('code');
        $Code = $this->db->get('item_setup');
        if($Code->num_rows() > 0){
            $Item_Code = $Code->row()->code;
            $NEW_Code = ++$Item_Code;
            $new = str_pad($NEW_Code, 4, "0", STR_PAD_LEFT);
        }else{
            $new = '0001';
        }
        return $new;
    }
    public function get_last_nubmer_for_form(){
        $this->db->select('id');
        $this->db->from('item_setup');
        $this->db->order_by('id','desc');
        $this->db->limit('1');
        return $this->db->get()->result_array();
    }
    public function getAllItems()
    {
        $this->db->select('`item_setup`.`code`, `item_setup_details`.`id` as `a_id`, 
        `item_setup_details`.`opening_quantity` as total_opening_quantity, `item_setup_details`.`current_quantity` as total_current_quantity, 
        `item_setup_details`.`item_setup_id`, `item_setup`.`name` `item_name`, 
        `item_sub_categories`.`Name` as `s_name`, `unit_of_measure`.`name`, `donation_type`.`Id` as `donation_id`,
        `unit_of_measure`.`id` as `unit`, `donation_type`.`Donation_Type`, `item_categories`.`Name` as `p_name`,
        `unit_of_measure`.`name` as `unit_of_measure_name`, `donation_type`.`Donation_Type`');
        $this->db->join('item_setup','item_setup.Id = item_setup_details.item_setup_id');
        $this->db->join('item_sub_categories','item_sub_categories.Id = item_setup.category_Id');
        $this->db->join('item_categories','item_categories.Id = item_sub_categories.parent_id');
        $this->db->join('unit_of_measure','unit_of_measure.id = item_setup.unit_of_measure');
        $this->db->join('donation_type','donation_type.Id = item_setup_details.donation_type');
        $this->db->where('item_setup_details.is_delete','0');
        return $this->db->get('item_setup_details')->result();
    }


    public function get_report_data($id){
        $this->db->select('item_setup.Code as Item_Code,item_categories.Name as category_name,
    item_sub_categories.Name as sub_category,item_setup.name as item_name,item_setup.code,
    item_setup_details.current_quantity,item_setup_details.opening_quantity,
    item_setup_details.donation_type,item_setup.unit_of_measure,unit_of_measure.name as unit,
    donation_type.Donation_Type as donation_name');
        $this->db->join('item_sub_categories','item_sub_categories.parent_id = item_categories.Id');
        $this->db->join('item_setup','item_setup.category_Id = item_sub_categories.Id');
        $this->db->join('item_setup_details','item_setup_details.item_setup_id = item_setup.Id');
        $this->db->join('donation_type','donation_type.Id = item_setup_details.donation_type');
        $this->db->join('unit_of_measure','unit_of_measure.id = item_setup.unit_of_measure');
        $this->db->where('item_setup_details.is_delete','0');
        $this->db->where('item_categories.id',$id);
//        $this->db->group_by('donation_type.Donation_Type, unit_of_measure.name');
        return $this->db->get('item_categories')->result();
    }

    public function get_items_details($id,$unit,$donation){
        $this->db->select('item_setup_details.id as a_id, item_setup_details.opening_quantity,
        item_setup_details.current_quantity,item_setup.name item_name,item_sub_categories.Name as s_name,
        unit_of_measure.name,donation_type.Donation_Type,item_categories.Name as p_name,
        unit_of_measure.name as unit_of_measure_name,donation_type.Donation_Type ');
        $this->db->join('item_setup','item_setup.Id = item_setup_details.item_setup_id');
        $this->db->join('item_sub_categories','item_sub_categories.Id = item_setup.category_Id');
        $this->db->join('item_categories','item_categories.Id = item_sub_categories.parent_id');
        $this->db->join('unit_of_measure','unit_of_measure.id = item_setup.unit_of_measure');
        $this->db->join('donation_type','donation_type.Id = item_setup_details.donation_type');
        $this->db->where('item_setup_details.is_delete','0');
        $this->db->where('item_setup.id',$id);
        $this->db->where('unit_of_measure.id',$unit);
        $this->db->where('donation_type.Id',$donation);
        return $this->db->get('item_setup_details')->result();
    }



    public function getAllItems_by_catid($cat_id)
    {
        $this->db->select('*,item_setup.id as a_id,b.Name as p_name,a.Name as s_name');
        $this->db->from('item_setup');
        $this->db->join('item_categories a', 'item_setup.category_Id = a.id');
        $this->db->join('item_categories b', 'a.Parent_Id = b.Id','left');
        $this->db->join('donation_type', 'item_setup.donation_type = donation_type.id');
        $this->db->where('category_Id',$cat_id);
        $this->db->group_by('item_setup.Code');
        $this->db->order_by('`item_setup`.`code`','asc');
        return $this->db->get()->result();
    }
    public  function getunit(){
        $this->db->select('*');
        return $this->db->get('unit_of_measure')->result();
    }
    public  function getunite_dit($unit){
        $this->db->select('*');
        $this->db->where('id',$unit);
        return $this->db->get('unit_of_measure')->result();
    }
    public function delete_Item($id)
    {
        $UpdatedBy = $_SESSION['user'][0]->id;
        $UpdatedOn = date('Y-m-d H:i:s');
        $this->db->set('UpdatedBy',$UpdatedBy);
        $this->db->set('UpdatedOn',$UpdatedOn);
        $this->db->set('is_delete','1');
        $this->db->where('Id',$id);
        $this->db->update('item_setup_details');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function Item_By_Id($id)
    {
        $this->db->select('`item_setup`.`code`, `item_setup_details`.`id` as `a_id`, 
        `item_setup_details`.`opening_quantity`, `item_setup_details`.`current_quantity`, `item_setup`.`name` `item_name`, 
        `item_sub_categories`.`Name` as `s_name`, `unit_of_measure`.`name`, `donation_type`.`Donation_Type`,
        `item_categories`.`Name` as `p_name`, `unit_of_measure`.`name` as `unit_of_measure_name`, 
        `donation_type`.`Donation_Type`,donation_type.Id as d_id,unit_of_measure.id as unit_id,item_categories.Id as p_id,
         item_sub_categories.Id  as s_id');
        $this->db->join('item_setup','item_setup.Id = item_setup_details.item_setup_id');
        $this->db->join('item_sub_categories','item_sub_categories.Id = item_setup.category_Id');
        $this->db->join('item_categories','item_categories.Id = item_sub_categories.parent_id');
        $this->db->join('unit_of_measure','unit_of_measure.id = item_setup_details.unit_of_measure');
        $this->db->join('donation_type','donation_type.Id = item_setup_details.donation_type');
        $this->db->where('item_setup_details.is_delete','0');
        $this->db->where('item_setup_details.Id',$id);
        return $this->db->get('item_setup_details')->result();
    }
    public function updateItems()
    {
                $UpdatedBy = $_SESSION['user'][0]->id;
                $UpdatedOn = date('Y-m-d H:i:s');
                $this->db->set('item_setup_id',$_POST['item']);
                $this->db->set('UpdatedBy',$UpdatedBy);
                $this->db->set('UpdatedOn',$UpdatedOn);
                $this->db->set('opening_quantity',$_POST['opening_quantity']);
                $this->db->set('current_quantity',$_POST['current_quantity']);
                $this->db->set('donation_type',$_POST['donation']);
                $this->db->where('Id',$_POST['item_id']);
                $this->db->update('item_setup_details');
                if($this->db->affected_rows() > 0)
                {
                //print_r($_POST);
                // exit();
                $this->db->set('opening_quantity',$_POST['opening_quantity']);
                $this->db->set('item_id',$this->input->post('items'));
                $this->db->update('store_ledger');
                $this->db->set('Item_id',$_POST['item']);
                $this->db->set('UpdatedBy',$UpdatedBy);
                $this->db->set('UpdatedOn',$UpdatedOn);
                $this->db->set('Item_quantity',$_POST['opening_quantity']);
                $this->db->set('remain_quantity',$_POST['current_quantity']);
                $this->db->set('donation_type',$_POST['donation']);
                $this->db->where('item_setup_detail_id',$_POST['item_id']);
                $this->db->update('item_stockrecieve_slip_details');
                return true;
            }else{
                return false;
            }
    }
    public function get_donation_vise_items($id)
    {
        $data = $this->db->query("SELECT name,item_setup.Id FROM `item_setup` JOIN donation_type ON item_setup.donation_type = donation_type.id WHERE donation_type.id = ".$id);
        return $data->result();
    }
    public function GetCode($id)
    {
        $this->db->where('Id', $id);
        return $this->db->get('item_setup')->result();
    }
    public function Get_Item_Stock($item_id)
    {
        $this->db->select('item_setup.Id,item_setup.code,item_setup.name,donation_type.Donation_Type,item_setup.unit_of_measure,unit_of_measure.name as unit,item_setup_details.opening_quantity');
        $this->db->join('item_setup_details','item_setup_details.item_setup_id = item_setup.Id');
        $this->db->join('donation_type','item_setup.donation_type = donation_type.Id');
        $this->db->join('unit_of_measure','item_setup.unit_of_measure = unit_of_measure.id');
        $this->db->where('item_setup.Id',$item_id);
        $this->db->order_by('`item_setup`.`code`',' ASC');
        return $this->db->get('item_setup')->result();
    }
    public function Get_Items_By_Cdoe($code)
    {
        foreach ($code as $item) {
            $this->db->select('Id');
            $this->db->where('item_setup.code',$item);
            $result[] = $this->db->get('item_setup')->result();
        }
        return $result;
    }
    public function Get_By_Category($id)
    {
        $this->db->select('*');
        $this->db->where('category_Id', $id);
        $this->db->group_by('name');
        return $this->db->get('item_setup')->result();
    }
    public function Get_By_Category_donation($category,$donation)
    {
        $this->db->select('*');
        $this->db->where('category_Id', $category);
        $this->db->where('donation_type', $donation);
        $this->db->group_by('name');
        return $this->db->get('item_setup')->result();
    }
    public function Get_By_Donation($id)
    {

        $this->db->select('*');
        $this->db->where('donation_type', $id);
        $this->db->group_by('name');
        return $this->db->get('item_setup')->result();

    }
    public function get_category_vise_items($category)
    {
        $this->db->select('item_setup.name,item_setup.id');
        return $this->db->get('item_setup')->result();
    }
    public function get_items(){
        $this->db->select('id,name');
        return $this->db->get('item_setup')->result_array();
    }
    public function get_edit_items($id){
        $this->db->select('`item_setup`.`name` as `item_name`, `unit_of_measure`.`name` as `unit_name`, 
        `unit_of_measure`.`id` as `unit_id`, `item_setup`.`id` as item_id, `item_setup_details`.`current_quantity`,
         `item_categories`.`Id` as `parent_id`, `item_categories`.`Name` as `parent_name`, item_setup_details.id as item_detail_id,
         `item_sub_categories`.`Id` as `sub_parent_id`, `item_sub_categories`.`Name` as `sub_parent_name`, 
         `item_setup_details`.`opening_quantity`, `donation_type`.`Donation_Type`, `donation_type`.`Id` as donation_id');
        $this->db->join('item_setup','item_setup.Id = item_setup_details.item_setup_id');
        $this->db->join('unit_of_measure','`unit_of_measure`.`id` = `item_setup`.`unit_of_measure`');
        $this->db->join('donation_type','donation_type.Id = item_setup_details.donation_type');
        $this->db->join('item_sub_categories','item_setup.category_Id = item_sub_categories.Id');
        $this->db->join('item_categories','item_sub_categories.parent_id = item_categories.Id');
        $this->db->where('item_setup_details.id',$id);
        return $this->db->get('item_setup_details')->result();
    }
}