<?php

/**
 * Class ItemSetupModel
 */

class ItemSetupModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function GetItems()
    {
        if($_SESSION['user'][0]->id != 1){

            $this->db->where('CreatedBy',$_SESSION['user'][0]->id);

        }
        return $this->db->get('item_setup')->result();
    }

    // added by sufyan


    public function Save_Item()
    {
        $Item_Code = $this->Check_Item_Code();

        foreach ($_POST['OpeningQuanity'] as $key => $value) {
            if ($value != '') {
                $this->db->set('name',$_POST['ItemName']);
                $this->db->set('code',$Item_Code);
                $this->db->set('unit_of_measure',$_POST['UnitOfMeasure']);
                $this->db->set('opening_quantity',$_POST['OpeningQuanity'][$key]);
                $this->db->set('current_quantity',$_POST['CurrentQuanity'][$key]);
                $this->db->set('donation_type',$_POST['DonationType'][$key]);
                $this->db->set('category_id',$_POST['ItemsubCategory']);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            }
            if($this->db->insert('item_setup')){
                echo 'duplicate entries accured';
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Check_Item_Code()
    {
        $new = '';
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
      //  $this->db->order_by('id','desc');
        $this->db->limit('1');
        return $this->db->get()->result_array();
    }

    public function getAllItems()
    {
        /*$this->db->select('*,item_setup.id as a_id,b.Name as p_name,a.Name as s_name');
        $this->db->from('item_setup');
        $this->db->join('item_categories a', 'item_setup.category_Id = a.id');
        $this->db->join('item_categories b', 'a.Parent_Id = b.Id','left');
        $this->db->join('donation_type', 'item_setup.donation_type = donation_type.id');
        // by Sufiyan 05-01-2018
        //$this->db->where('category_Id',$cat_id);
        $this->db->group_by('item_setup.Code');
        $this->db->order_by('`item_setup`.`code`','asc');
        return $this->db->get()->result();*/
        // working of sufyan
        $type = $_SESSION['type'];
        $query = $this->db->query("SELECT *,item_setup.name as item_name,item_setup.id as item_setup_id,unit_of_measure.name as unit_of_measure_name, `item_setup`.`id` as `a_id`, `b`.`Name` as `p_name`, `a`.`Name` as `s_name` FROM `item_setup` JOIN `item_categories` `a` ON `item_setup`.`category_Id` = `a`.`id` LEFT JOIN `item_categories` `b` ON `a`.`Parent_Id` = `b`.`Id` JOIN `donation_type` ON `item_setup`.`donation_type` = `donation_type`.`id` JOIN unit_of_measure on unit_of_measure.id = item_setup.unit_of_measure WHERE `a`.`Parent_Id` in (SELECT Id from item_categories WHERE category_type = '$type' and Parent_Id = 0) GROUP BY `item_setup`.`Code` ORDER BY `item_setup`.`code` DESC");
        return $query->result();
    }
    //work by sufyan
    public function getAllItems_by_catid($cat_id)
    {
        $this->db->select('*,item_setup.id as a_id,b.Name as p_name,a.Name as s_name');
        $this->db->from('item_setup');
        $this->db->join('item_categories a', 'item_setup.category_Id = a.id');
        $this->db->join('item_categories b', 'a.Parent_Id = b.Id','left');
        $this->db->join('donation_type', 'item_setup.donation_type = donation_type.id');
        // by Sufiyan 05-01-2018
        $this->db->where('category_Id',$cat_id);
        $this->db->group_by('item_setup.Code');
        $this->db->order_by('`item_setup`.`code`','asc');
        return $this->db->get()->result();
    }

    //end



    public  function getunit(){

        $this->db->select('*');
        return $this->db->get('unit_of_measure')->result();

    }


    public function delete_Item($id)
    {
        $this->db->select('Item_id');
        $this->db->where('Item_id',$id);
        $item_demand_approve_details = $this->db->get('item_demand_approve_details');

        $this->db->select('Item_Id');
        $this->db->where('Item_Id',$id);
        $item_demand_form_details = $this->db->get('item_demand_form_details');

        $this->db->select('Item_Id');
        $this->db->where('Item_Id',$id);
        $item_issue_details = $this->db->get('item_issue_details');

        $this->db->select('Item_Id');
        $this->db->where('Item_Id',$id);
        $item_return_details = $this->db->get('item_return_details');

        $this->db->select('Item_id');
        $this->db->where('Item_id',$id);
        $item_stockrecieve_slip_details = $this->db->get('item_stockrecieve_slip_details');

        $this->db->select('Item_Id');
        $this->db->where('Item_Id',$id);
        $item_stockreturn_slip_details = $this->db->get('item_stockreturn_slip_details');
        if(($item_demand_approve_details->num_rows() > 0) || ($item_demand_form_details->num_rows() > 0) || ($item_issue_details->num_rows() > 0) || ($item_return_details->num_rows() > 0) || ($item_stockrecieve_slip_details->num_rows() > 0) || ($item_stockreturn_slip_details->num_rows() > 0)){
            return 12;
        }else{
            $this->db->where('Id', $id);
            $this->db->delete('item_setup');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function Item_By_Id($id)
    {
        $this->db->select('*,unit_of_measure.name as unit_of_measure_name,unit_of_measure.id,item_setup.Id as a_id,a.Name as p_name,b.Name as s_name');
        $this->db->from('item_setup');
        $this->db->join('item_categories a', 'item_setup.category_Id = a.id');
        $this->db->join('item_categories b', 'item_setup.subCategory_id = b.id','left');
        $this->db->join('donation_type', 'item_setup.donation_type = donation_type.id');
        $this->db->join('unit_of_measure','item_setup.unit_of_measure = unit_of_measure.id');
        $this->db->where('item_setup.code', $id);
        return $this->db->get()->result();
    }

    public function updateItems()
    {
        $this->db->where('code',$this->input->post('ItemCode'));
        $this->db->delete('item_setup');
//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
        if($this->db->affected_rows() > 0){
            $UpdatedBy = $_SESSION['user'][0]->id;
            $UpdatedOn = date('Y-m-d H:i:s');
            foreach ($this->input->post('DonationType') as $keys => $item) {
                $this->db->set('code',$this->input->post('ItemCode'));
                $this->db->set('name',$this->input->post('ItemName'));
                $this->db->set('category_id',$this->input->post('ItemsubCategory'));
                $this->db->set('unit_of_measure',$this->input->post('unit_of_measure'));
                $this->db->set('UpdatedBy',$UpdatedBy);
                $this->db->set('UpdatedOn',$UpdatedOn);

                $this->db->set('CreatedBy',$this->input->post('CreatedBy'));
                $this->db->set('CreatedOn',$this->input->post('CreatedOn'));
                $this->db->set('Id',$this->input->post('ItemsetupId'));

                $this->db->set('opening_quantity',$_POST['OpeningQuanity'][$keys]);
                $this->db->set('current_quantity',$_POST['CurrentQuanity'][$keys]);
                $this->db->set('donation_type',$_POST['DonationType'][$keys]);
                $this->db->insert('item_setup');
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
        $this->db->select('item_setup.Id,item_setup.code,item_setup.name,donation_type.Donation_Type,item_setup.unit_of_measure,item_setup.opening_quantity');
        $this->db->join('donation_type','item_setup.donation_type = donation_type.Id');
        $this->db->where('item_setup.Id',$item_id);
        $this->db->order_by('`item_setup`.`code`',' ASC');
        return $this->db->get('item_setup')->result();
    }

    public function Get_Items_By_Cdoe($code)
    {
        echo $code;

        foreach ($code as $item) {
            $this->db->select('Id');
            $this->db->where('item_setup.code',$item);
            $result[] = $this->db->get('item_setup')->result();
        }

//        print_r($result);
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
//        print_r($id);
        $this->db->select('*');
        $this->db->where('donation_type', $id);
        $this->db->group_by('name');
        return $this->db->get('item_setup')->result();
    }

    public function get_category_vise_items($category)
    {
        $this->db->select('*,item_setup.Id as I_ID');
        $this->db->from('item_setup');
        $this->db->join('item_categories a', 'item_setup.category_Id = a.Id');
        $this->db->join('item_categories b', 'a.Parent_Id = b.Id');
        $this->db->where('b.category_type', $category);
        return $this->db->get()->result();
    }

    /*SELECT * FROM `item_setup`
JOIN item_categories a ON item_setup.category_Id = a.Id
JOIN item_categories b on a.Parent_Id = b.Id
WHERE b.category_type = 0*/
}