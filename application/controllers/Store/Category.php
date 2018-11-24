<?php

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 5/5/2017
 * Time: 10:27 AM
 */
class Category extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemCategoryModel');
    }
    // parameter add by muhammad sufyan for soting data by category type
    public function index()
    {
            $type1 = $this->uri->segment(4);
            $data['allCategories'] = $this->ItemCategoryModel->getCategories($type1);
//          echo $this->db->last_query();
             $data['category'] = $this->ItemCategoryModel->getsubCategories();
           // echo $this->db->last_query();
//           echo '<pre>';
//            print_r($data['subcat']);
//               exit();
            $this->load->view('Store/header');
            $this->load->view('Store/category/AddCategories', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/itemCategoryJs');
    }
    //sufyan created fucntion
    public function bind_cat_ddl()
    {
        $type = $this->uri->segment(4);
        //echo  $_POST['sufyan'];
        $data = $this->ItemCategoryModel->getParCategoriesByTYpe($type);
        echo json_encode($data);
    }


    public function getsubCategories()
    {

        $data = $this->ItemCategoryModel->getsubCategories();
        echo json_encode($data);
    }


//end


// sufyan working
    public function saveCategory()
    {

            $this->form_validation->set_rules("Name", "Name", "trim|required|is_unique[item_categories.name]",
                array(
                    'required' => 'نام کااندراج کریں',
                    'is_unique' => 'کیٹیگری پہلے سے موجود ہے ')
                  );
        $this->form_validation->set_rules('cat-type','cat-type','trim|required',
            array('required'=>'کیٹیگری کی قسم ڈالیں')
            );
            if ($this->form_validation->run() == true) {
                $check = $this->ItemCategoryModel->saveParCategory();
                if ($check) {
                    if ($check == 'added') {
                        $this->session->set_flashdata('success', "کیٹیگری کامیابی سے شامل ہوگئ ہے");
                        redirect('Store/Category/', 'refresh');
                    } else if ($check == 'exists') {
                        $this->session->set_flashdata('error', "کیٹیگری پہلے سے موجود ہے");
                        redirect('Store/Category/' . $_SESSION['type'], 'refresh');
                    }
                }
            } else {
                $type1 = $this->uri->segment(4);
                $data['allCategories'] = $this->ItemCategoryModel->getCategories($type1);
//        echo $this->db->last_query();
                $data['subcat'] = $this->ItemCategoryModel->getsubCategories($type1);
                $this->load->view('Store/header');
                $this->load->view('Store/category/AddCategories',$data);
                $this->load->view('Store/footer');
                $this->load->view('Store/js/itemCategoryJs');
            }
    }
   // end sufyan working

public function saveSubCategory()
{                $this->form_validation->set_rules("SubName", "SubName", "trim|required|is_unique[item_categories.name]",
                    array(
                        'required' => 'نام کا یندراج کریں',
                        'is_unique' => 'کیٹیگری پہلے سے موجود ہے ')
                );
                if ($this->form_validation->run() == true) {

                    $check = $this->ItemCategoryModel->saveSubCategory();
                    if ($check) {
                        $this->session->set_flashdata('success', "سب کیٹیگری کامیابی سے شامل ہوگئی ہے");
                        redirect('Store/Category', 'refresh');
                    }
                } else {
                    $this->load->view('Store/header');
                    $this->load->view('Store/category/AddCategories');
                    $this->load->view('Store/footer');
                    $this->load->view('Store/js/itemCategoryJs');
                }

    }
    public function DeleteCategory($id)
    {

            $check = $this->ItemCategoryModel->deleteCategory($id);
//            echo $this->db->last_query();
            if ($check === true) {
                $response = array('success' => "ok");
            } else if ($check === 404) {
                $response = array('has_category' => "ok");
            } else {
                $response = array('error' => "ok");
            }
            echo json_encode($response);
    }

    public function CategoryById($id)
    {
            $data = $this->ItemCategoryModel->Category_By_Id($id);
          // echo $this->db->last_query();
            $category_data = array('Name' => $data[0]->Name,
                'sub_cat_id' => $data[0]->sub_cat_id,'sub_cat_name'=> $data[0]->Parent_name,'category_name'=>$data[0]->category_name,'category_id'=>$data[0]->category_id);
            echo json_encode($category_data);
    }

    public function UpdateCategory($id)
    {
            $this->form_validation->set_rules('Name','Name','trim|required');
            $check = $this->ItemCategoryModel->updateCategory($id);
            if ($check) {
                $response = array('success' => "ok");
            } else {
                $response = array('error' => "ok");
            }
            echo json_encode($response);
    }


    public function GetSubCategory($id)
    {

        $SubCategory = $this->ItemCategoryModel->Get_sub($id);
        echo json_encode($SubCategory);
    }

    public function CheckCatCode($code)
    {
        if(isset($_SESSION['user'])) {
            $check = $this->ItemCategoryModel->Check_Cat_Code($code);
            if ($check) {
                $response = array('success' => "ok");
            } else {
                $response = array('error' => "ok");
            }
            echo json_encode($response);
        }
        else{
            $this->load->view('login');
        }
    }
}