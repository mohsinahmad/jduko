<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Department extends MY_Controller
{
	 public function __construct()
    {
        parent::__construct();
        $this->load->model('DepartmentModel');
    }

    public function index()
    {
    		$data['name'] = $this->DepartmentModel->department_name();
        	$this->load->view('Accounts/header');
            $this->load->view('Accounts/departments/add',$data);
            $this->load->view('Accounts/footer');
            $this->load->view('Accounts/js/departmentJs');
    }

    public function DepartmentName()
    {
    	$check = $this->DepartmentModel->add_department();
        if($check){
            $this->session->set_flashdata('success',"شعبہ کامیابی سے شامل ہوگیا");
            redirect('Accounts/Department','refresh');
        }else{
            $this->session->set_flashdata('error',"شعبے کا نام درج کریں");
            redirect('Accounts/Department','refresh');
        }
    }

    public function DeleteDepart($id)
    {
    	$check = $this->DepartmentModel->Delete_Depart($id);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }

    public function DepartNameById($id)
    {
    	$check = $this->DepartmentModel->DepartNameBy_Id($id);
    	$name = array('name' =>$check[0]->DepartmentName);
    	 echo json_encode($name);
    }

    public function UpdateDepart($id)
    {
    	$check = $this->DepartmentModel->Update_Depart($id);
        if($check){
            $response= array('success' => "ok");
        } else{
            $response=array('error' => "ok");
        }
        echo json_encode($response);
    }
}