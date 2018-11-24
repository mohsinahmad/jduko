<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategoryModel extends CI_Model {

    public function saveParCategory()
    {
           // $data['code'] = $this->getCategoryCode('item_sub_categories');
            $data['name'] = $_POST['Name'];
            $data['CreatedBy'] = $_SESSION['user'][0]->id;
            $data['CreatedOn'] = date('Y-m-d H:i:s');
            $data['category_type'] = $_POST['cat-type'];
            if($this->db->insert('item_categories',$data)){
                return 'added';
            }
     }


     public function get_report_data($id){
        $this->db->select('item_categories.*,item_sub_categories.*,item_setup.*');
        $this->db->join('item_sub_categories','item_setup.category_Id = item_sub_categories.Id');
        $this->db->join('item_categories','item_categories.Id = item_sub_categories.parent_id');
        $this->db->where('item_sub_categories.parent_id',$id);
        return $this->db->get('item_setup')->result();
    }


    public function check_parent_category($parent_category){

        $where = "Parent_Id = 0 AND Name = '".$parent_category."'";
        $this->db->select('Name');
        $this->db->where($where);
        $result = $this->db->get('item_categories');
        if($result->num_rows() > 0){
            return true;
        }
        else{
          return false;
        }
    }
    public function getParCategoriesByTYpe($type)
    {
        $this->db->where('is_delete','0');
        return $this->db->get('item_categories')->result();
    }
    public function getParCategoriesByTYpeedit($edit_id)
    {
        $this->db->select('a.id as parent_id,a.name as parent_name,b.id, b.name');
        $this->db->from('item_categories as a');
        $this->db->join('item_categories as b','a.id = b.parent_id');
        $this->db->where('b.Id',$edit_id);
        return $this->db->get()->result();
    }
    public function getParCategories()
    {
        $this->db->where('Parent_id', 0);
        return $this->db->get('item_categories')->result();
    }
    public function getCategoryCode($table)
    {
        $this->db->select_max('code');
        $max = $this->db->get($table)->result();
        if ($max[0]->code != ''){
            $max_num = $max[0]->code;
            $max_num++;
            $new_number = str_pad($max_num,4,0,STR_PAD_LEFT);
            return $new_number;
        }else{
            return '0001';
        }
    }
    public function getCategories()
    {
        $this->db->select('item_setup.name as Name,item_setup.Id,`item_setup`.`code`, item_sub_categories.Name as Parent_name,item_categories.name as category');
        $this->db->join('item_sub_categories','item_setup.category_Id  = item_sub_categories.Id');
        $this->db->join('item_categories','item_sub_categories.parent_id = item_categories.Id');
        $this->db->where('item_setup.is_delete','0');
        return $this->db->get('item_setup')->result();
    }	
	
	public function get_categoreis_for_ladger(){		
		$this->db->select('item_sub_categories.Name as s_name,item_setup.code as Code,item_sub_categories.id as Id,item_categories.name as p_name');
        $this->db->join('item_categories','item_sub_categories.parent_id = item_categories.Id');		
        $this->db->join('item_setup','item_setup.category_Id = item_sub_categories.Id');
		$this->db->group_by('item_setup.category_Id');		
        return $this->db->get('item_sub_categories')->result();		
	}	
    public function saveSubCategory()
    {
            $values = array('CreatedBy' => $_SESSION['user'][0]->id,
            'CreatedOn' => date('Y-m-d H:i:s'),
            //'Code' => $this->getCategoryCode('item_categories',$_POST['Parent_id']),
            'Name' => $_POST['SubName'],
            'Parent_id' => $_POST['Parent_id']);
           $this->db->insert('item_sub_categories', $values);

        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
    public function deleteCategory($id)
    {
                $UpdatedBy = $_SESSION['user'][0]->id;
                $UpdatedOn = date('Y-m-d H:i:s');
                $this->db->where('id', $id);
                $this->db->set('is_delete','1');
                $this->db->set('UpdatedBy',$UpdatedBy);
                $this->db->set('UpdatedOn',$UpdatedOn);
                $this->db->update('item_setup');
                        if($this->db->affected_rows() > 0){
                            return true;
                        }else{
                            return false;
                        }
    }

//    public function verifyCategory($id)
//    {
//        $this->db->where('category_Id', $id);
//        $this->db->or_where('subCategory_id ',$id);
//        $check = $this->db->get('item_setup');
//        if($check->num_rows() > 0){
//            return true;
//        }else{
//            return false;
//        }
//    }

    public function Category_By_Id($id)
    {
        $this->db->select('`item_sub_categories`.`id` as sub_cat_id, `item_sub_categories`.`Name` as `Parent_name`, `item_setup`.`Name`,item_categories.Name as category_name,item_categories.Id as category_id');
        $this->db->from('item_setup');
        $this->db->join('item_sub_categories', '`item_sub_categories`.`Id` = item_setup.category_Id');
     $this->db->join('item_categories','item_categories.Id = item_sub_categories.parent_id');
        $this->db->where('`item_setup`.`Id`', $id);
        return $this->db->get()->result();
    }

    public function updateCategory($id)
    {


                    $this->db->set('Name',$_POST['CategoryName']);
                    $this->db->where('id',$_POST['category']);
                    $this->db->update('item_categories');
                    $this->db->set('parent_id',$_POST['category']);
                    $this->db->set('Name',$_POST['SubCategoryName']);
                    $this->db->where('Id',$_POST['sub_category']);
                    $this->db->update('item_sub_categories');
                    $UpdatedBy = $_SESSION['user'][0]->id;
                    $UpdatedOn = date('Y-m-d H:i:s');
                    $this->db->set('Name',$_POST['Name']);
                    $this->db->set('category_Id',$_POST['sub_category']);
                    $this->db->set('UpdatedBy',$UpdatedBy);
                    $this->db->set('UpdatedOn',$UpdatedOn);

                $this->db->where('Id', $id);
                $this->db->update('item_setup');
                if($this->db->affected_rows() > 0){
                    return true;
                }
                else{
                    return false;
                }
    }
    public function getsubCategories(){
        $this->db->where('is_delete','0');
        return  $this->db->get('item_categories')->result();
    }
    public function getCode($id)
    {
        $this->db->select('Code');
        $this->db->where('Id', $id);
        return $this->db->get('item_categories')->row();
    }
    public function Get_sub($id)
    {
        $this->db->select('Code,N   ame,Parent_Id,Id');
        $this->db->where('Parent_Id', $id);
        return $this->db->get('item_categories')->result();
    }
    public function Check_Cat_Code($code)
    {
        $this->db->where('Code', $code);
        $this->db->from('item_categories');
        $res = $this->db->get();
        if($res->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
//    public function Get_Sub_Category()
//    {
//        $query = $this->db->query("SELECT * from item_sub_categories");
//        return $query->result();
//
//    }
    public  function get_parent_category(){
        $this->db->where('is_delete','0');
        return $this->db->get('item_categories')->result();
    }
    public  function get_sub_category($parent_id){
        $this->db->where('is_delete','0');
        $this->db->where('parent_id',$parent_id);
        return $this->db->get('item_sub_categories')->result();
    }
    public  function get_items($cat_id = ""){
        if($cat_id != ''){
            $this->db->where('category_Id',$cat_id);
        }
        $this->db->where('is_delete','0');
        return $this->db->get('item_setup')->result();
    }
}

/* End of file ItemCategoryModel.php */
/* Location: ./application_cuurent_working_position/models/ItemCategoryModel.php */