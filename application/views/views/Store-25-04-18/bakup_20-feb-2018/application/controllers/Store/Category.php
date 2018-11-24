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
        if(isset($_SESSION['user'])) {
            $type1 = $this->uri->segment(4);
            $_SESSION['type'] = $type1;
            //echo  $type;
            $data['categories'] = $this->ItemCategoryModel->getParCategoriesByTYpe($type1);
            $data['allCategories'] = $this->ItemCategoryModel->getCategories($type1);
            $this->load->view('Store/header');
            $this->load->view('Store/category/AddCategories', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/itemCategoryJs');
        }
        else{
            $this->load->view('login');
        }
    }
    //sufyan created fucntion
    public function bind_cat_ddl()
    {
        $type = $this->uri->segment(4);
        //echo  $_POST['sufyan'];
        $data = $this->ItemCategoryModel->getParCategoriesByTYpe($type);
        echo json_encode($data);
    }


//end


// sufyan working
    public function saveCategory()
    {
        if(isset($_SESSION['user'])) {
            $this->form_validation->set_rules("Code", "Code", "is_unique[item_categories.Code]",
                array(
                    'is_unique' => 'کیٹیگری کوڈ پہلے سے موجود ہے ')
            );

            $this->form_validation->set_rules("Name", "Name", "trim|required",
                array(
                    'required' => 'کیٹیگری کا  نام درکار ہے')
            );



            if ($this->form_validation->run() == true) {
                $check = $this->ItemCategoryModel->saveParCategory();
                if ($check) {
                    if ($check == 'added') {
                        $this->session->set_flashdata('success', "کیٹیگری کامیابی سے شامل ہوگئ ہے");
                        redirect('Store/Category/index/' . $_SESSION['type'], 'refresh');
                    } else if ($check == 'exists') {
                        $this->session->set_flashdata('error', "کیٹیگری پہلے سے موجود ہے");
                        redirect('Store/Category/index/' . $_SESSION['type'], 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('error', "کیٹیگری  شامل نہی ہوئی ہے");
                    redirect('Store/Category/index/' . $_SESSION['type'], 'refresh');
                }
            } else {
                //$this->category();
            }
        }

        else{
            $this->load->view('login');
        }

    }

   // end sufyan working

public function saveSubCategory()
        {



            if(isset($_SESSION['user'])) {
                $this->form_validation->set_rules("SubCode", "SubCode", "is_unique[item_categories.Code]",
                    array(
                        'is_unique' => 'کیٹیگری کوڈ پہلے سے موجود ہے ')
                );
                if ($this->form_validation->run() == true) {

                    $check = $this->ItemCategoryModel->saveSubCategory();
                    if ($check) {
                        $this->session->set_flashdata('success', "سب کیٹیگری کامیابی سے شامل ہوگئی ہے");
                        redirect('Store/Category/index/' . $_SESSION['type'], 'refresh');
                    } else {
                        $this->session->set_flashdata('error', "سب کیٹیگری شامل نہی ہوئی ہے");
                        redirect('Store/Category/index/' . $_SESSION['type'], 'refresh');
                    }
                } else {
                    $this->category();
                }
            }

            else{
                $this->load->view('login');
            }
    }
    public function DeleteCategory($id)
    {

        if(isset($_SESSION['user'])) {
            $check = $this->ItemCategoryModel->deleteCategory($id);
            if ($check === true) {
                $response = array('success' => "ok");
            } else if ($check === 404) {
                $response = array('has_category' => "ok");
            } else {
                $response = array('error' => "ok");
            }
            echo json_encode($response);
        }
        else{
            $this->load->view('login');
        }
    }

    public function CategoryById($id)
    {
        if(isset($_SESSION['user'])) {
            $data = $this->ItemCategoryModel->Category_By_Id($id);
            $category_data = array('Name' => $data[0]->Name,
                'Code' => $data[0]->Code,
                'Parent_Name' => $data[0]->Parent_name);
            echo json_encode($category_data);
        }
        else{
            $this->load->view('login');
        }
    }

    public function UpdateCategory($id)
    {
        if(isset($_SESSION['user'])) {
            $check = $this->ItemCategoryModel->updateCategory($id);
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