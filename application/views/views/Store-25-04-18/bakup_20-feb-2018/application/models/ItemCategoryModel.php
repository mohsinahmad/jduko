<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemCategoryModel extends CI_Model {

    public function saveParCategory()
    {
        $_POST['Code'] = $this->getCategoryCode('item_categories');
        $_POST['CreatedBy'] = $_SESSION['user'][0]->id;
        $_POST['CreatedOn'] = date('Y-m-d H:i:s');
        $_POST['Parent_id'] = 0;
        $_POST['Name'];
//      /  echo $_POST['Name'];
// this work added by sufyan
        if($this->check_parent_category($_POST['Name']) == false){
            //echo 'data nahe hy';
            $this->db->insert('item_categories', $_POST);
           if($this->db->affected_rows() > 0){
                return 'added';
                return true;
            }else{
                return false;
            }
        }
        else{
            return "exists";
        }
       // echo $this->db->last_query();
// sufyan work ended
        /*$this->db->insert('item_categories', $_POST);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }*/
    }

    //sufyan work start here

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
        $this->db->where('Parent_id', 0);
        $this->db->where('category_type',$type);
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

    // sufyan work end here

    public function getParCategories()
    {
        $this->db->where('Parent_id', 0);
        return $this->db->get('item_categories')->result();
    }

    public function getCategoryCode($table,$is_Sub='')
    {
        $this->db->select_max('code');
        if ($is_Sub == ''){
            $this->db->where('Parent_id',0);
        }else{
            $this->db->where('Parent_id',$is_Sub);
        }
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
// work will start tomorow from here
    public function getCategories($type)
    {
        /*$query = $this->db->query('SELECT a.Id,a.code as Code,a.Name,b.Name as Parent_name
							FROM `item_categories` as a
							LEFT JOIN `item_categories` as b ON b.Id = a.Parent_Id
							ORDER BY `Id` ASC');*/
        $query = $this->db->query("SELECT a.Id,a.code as Code,a.Name,b.Name as Parent_name FROM `item_categories` as a LEFT JOIN `item_categories` as b ON b.Id = a.Parent_Id where a.Parent_Id in (SELECT Id from item_categories WHERE category_type = '$type' and Parent_Id = 0) ORDER BY `Id` ASC");
        return $query->result();
    }
    public function saveSubCategory()
    {
        $_POST['Code'] = $this->getCategoryCode('item_categories',$_POST['Parent_id']);
        $values = array('CreatedBy' => $_SESSION['user'][0]->id,
            'CreatedOn' => date('Y-m-d H:i:s'),
            'Code' => $_POST['Code'],
            'Name' => $_POST['SubName'],
            'Parent_id' => $_POST['Parent_id'] );
        $this->db->insert('item_categories', $values);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function deleteCategory($id)
    {
        $this->db->where('Parent_id', $id);
        $data = $this->db->get('item_categories');
        if($data->num_rows() > 0){
            return false;
        }else{
            $has_Cat =  $this->verifyCategory($id);
            if($has_Cat){
                return 404;
            }else{
                $this->db->where('Id', $id);
                $this->db->delete('item_categories');
                if($this->db->affected_rows() > 0){
                    return true;
                }else{
                    return false;
                }
            }
        }
    }

    public function verifyCategory($id)
    {
        $this->db->where('category_Id', $id);
        $this->db->or_where('subCategory_id ',$id);
        $check = $this->db->get('item_setup');
        if($check->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Category_By_Id($id)
    {
        $this->db->select('*,a.Id,a.code as Code,a.Name,b.Name as Parent_name');
        $this->db->from('item_categories a');
        $this->db->join('item_categories b', 'b.Id = a.Parent_id','left');
        $this->db->where('a.Id', $id);
        return $this->db->get()->result();
    }

    public function updateCategory($id)
    {
        $UpdatedBy = $_SESSION['user'][0]->id;
        $UpdatedOn = date('Y-m-d H:i:s');
        $this->db->where('Parent_id', $id);
        $data = $this->db->get('item_categories');
        $CatC = $this->getCode($id);
        if($data->num_rows() > 0){
            if($CatC->Code == $_POST['Name'] || $CatC->Name != $_POST['Name']){
                $this->db->set('Name',$_POST['Name']);
                $this->db->set('UpdatedBy',$UpdatedBy);
                $this->db->set('UpdatedOn',$UpdatedOn);
                $this->db->where('Id', $id);
                $this->db->update('item_categories');
                if($this->db->affected_rows() > 0){
                    return true;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }else{
            $this->db->set('UpdatedBy',$UpdatedBy);
            $this->db->set('UpdatedOn',$UpdatedOn);
            $this->db->set('Name',$_POST['Name']);
            $this->db->set('Code',$_POST['Code']);
            $this->db->where('Id', $id);
            $this->db->update('item_categories');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function getCode($id)
    {
        $this->db->select('Code,Name');
        $this->db->where('Id', $id);
        return $this->db->get('item_categories')->row();
    }

    public function Get_sub($id)
    {
        $this->db->select('Code,Name,Parent_Id,Id');
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

    public function Get_Sub_Category()
    {
        /*$this->db->select('a.Id,a.Code,a.Parent_Id,b.Name as p_name,a.Name as s_name');
        $this->db->from('item_categories a');
        $this->db->join('item_categories b', 'a.Parent_Id = b.Id');
        $this->db->where('a.Parent_Id !=', 0 );
        $this->db->order_by('a.Parent_Id','asc');*/
        // work of sufyan
        $type = $_SESSION['type'];
        $query = $this->db->query("SELECT `a`.`Id`, `a`.`Code`, `a`.`Parent_Id`, `b`.`Name` as `p_name`, `a`.`Name` as `s_name` FROM `item_categories` `a` JOIN `item_categories` `b` ON `a`.`Parent_Id` = `b`.`Id` WHERE `a`.`Parent_Id` in (SELECT Id from item_categories WHERE category_type = '$type' and Parent_Id = 0)");
        return $query->result();

    }
}

/* End of file ItemCategoryModel.php */
/* Location: ./application/models/ItemCategoryModel.php */