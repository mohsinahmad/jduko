<?php

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 5/5/2017
 * Time: 10:27 AM
 */
class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemCategoryModel');
    }

    public function index()
    {
        $data['categories'] = $this->ItemCategoryModel->getParCategories();
        $data['allCategories'] = $this->ItemCategoryModel->getCategories();

        $this->load->view('Store/header');
        $this->load->view('Store/category/AddCategories',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemCategoryJs');
    }

    public function saveCategory()
    {
        $this->form_validation->set_rules("Code","Code","is_unique[item_categories.Code]",
            array(
                'is_unique'      => 'کیٹیگری کوڈ پہلے سے موجود ہے ')
        );

        $this->form_validation->set_rules("Name","Name","trim|required",
            array(
                'required'      => 'کیٹیگری کا  نام درکار ہے')
        );
       
        if($this->form_validation->run() == true)
        {
            $check = $this->ItemCategoryModel->saveParCategory();
            if($check){
                $this->session->set_flashdata('success',"کیٹیگری کامیابی سے شامل ہوگئ ہے");
                redirect('Store/Category','refresh');
            }else{
                $this->session->set_flashdata('error',"کیٹیگری  شامل نہی ہوئی ہے");
                redirect('Store/Category','refresh');
            }
        }else{
            $this->category();
        }
    }

    public function saveSubCategory()
    {
        $this->form_validation->set_rules("SubCode","SubCode","is_unique[item_categories.Code]",
            array(
                'is_unique'      => 'کیٹیگری کوڈ پہلے سے موجود ہے ')
        );

        if($this->form_validation->run() == true){

            $check = $this->ItemCategoryModel->saveSubCategory();
            if($check){
                $this->session->set_flashdata('success',"سب کیٹیگری کامیابی سے شامل ہوگئی ہے");
                redirect('Store/Category','refresh');
            }else{
                $this->session->set_flashdata('error',"سب کیٹیگری شامل نہی ہوئی ہے");
                redirect('Store/Category','refresh');
            }
        }else{
            $this->category();
        }
    }

    public function DeleteCategory($id)
    {
        $check = $this->ItemCategoryModel->deleteCategory($id);
        if($check === true){
            $response= array('success' => "ok");}
        else if($check === 404){
            $response=array('has_category' =>"ok");
        }else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }

    public function CategoryById($id)
    {
        $data = $this->ItemCategoryModel->Category_By_Id($id);
        $category_data = array('Name' =>$data[0]->Name,
            'Code' => $data[0]->Code,
            'Parent_Name' => $data[0]->Parent_name);
        echo json_encode($category_data);
    }

    public function UpdateCategory($id)
    {
        $check = $this->ItemCategoryModel->updateCategory($id);
        if($check){
            $response= array('success' => "ok");
        }else{
            $response=array('error' =>"ok");
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
        $check = $this->ItemCategoryModel->Check_Cat_Code($code);
        if($check){
            $response= array('success' => "ok");
        }else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }
}