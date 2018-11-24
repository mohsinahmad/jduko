<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class DemandForm extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('DonationTypeModel');
        $this->load->model('ItemSetupModel');
        $this->load->model('DemandFormModel');
        $this->load->model('DepartmentModel');
    }

    public function index()
	{
        if(isset($_SESSION['in_use'])) {
            $status = array(0, 1, 2, 3);
            $data['demands'] = $this->DemandFormModel->Get_Demands_For_Approval($status);
            $this->load->view('Store/header');
            $this->load->view('Store/demandform/DemandForm', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/demandJs');
        }
        else{
            $this->load->view('login');
        }
	}

    public function AddForm()
    {
      //  echo  '<pre>';
      //  print_r($_SESSION);
        if(isset($_SESSION['in_use'])) {
            $data['Items'] = $this->ItemSetupModel->getAllItems();
            $data['departments'] = $this->DepartmentModel->department_name();
            $data['donation_types'] = $this->DonationTypeModel->donation_type();

            $this->load->view('Store/header');

            $this->load->view('Store/demandform/DemandFormPage', $data);

            $this->load->view('Store/footer');

            $this->load->view('Store/js/demandJs');
        }
        else{
            $this->load->view('login');
        }
	}

    public function SaveDemand()
    {
        if(isset($_SESSION['in_use'])) {
            $result = $this->DemandFormModel->saveForm();
        // echo $this->db->last_query();
            if ($result) {
                $this->session->set_flashdata('success', "Demand Added Successfully");
               redirect('Store/DemandForm', 'refresh');
            } else {
                $this->session->set_flashdata('error', "Demand Not Inserted");
                redirect('Store/DemandForm', 'refresh');
            }
        }
        else{
            $this->load->view('login');
        }
	}

    public function FormEdit($id)
    {
        if(isset($_SESSION['in_use'])) {
            $data['Items'] = $this->ItemSetupModel->getAllItems();
            $data['form_data'] = $this->DemandFormModel->editForm($id);
            $data['departments'] = $this->DepartmentModel->department_name();
            $data['donation_types'] = $this->DonationTypeModel->donation_type();
            $this->load->view('Store/header');
            $this->load->view('Store/demandform/DemandFormPageEdit', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/demandJs');
        }
        else{
            $this->load->view('login');
        }
	}

    public function FormUpdate()
    {
        if(isset($_SESSION['in_use'])) {
            $result = $this->DemandFormModel->FormUpdate();
            if ($result) {
                $this->session->set_flashdata('success', "Demand Edited Successfully");
                redirect('Store/DemandForm', 'refresh');
            } else {
                $this->session->set_flashdata('error', "Demand Not Edited");
                redirect('Store/DemandForm', 'refresh');
            }
        }
        else{
            $this->load->view('login');
        }
	}
    public function ResturnForm($id)
    {
        if(isset($_SESSION['in_use'])) {
            $data['ReturnForm'] = $this->DemandFormModel->editForm($id);
            $this->load->view('Store/header');
            $this->load->view('Store/demandform/ReturnForm', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/demandJs');
        }
        else{
            $this->load->view('login');
        }
    }
     public function ViewVoucher($id)
    {
      //  echo $id;
        $data['demands'] = $this->DemandFormModel->Get_Demands($id);
       // echo $this->db->last_query();

       // print_r($data['demands']);
       $this->load->view('Store/demandform/Demand_Voucher',$data);
       $this->load->view('Store/footer');
       $this->load->view('Store/js/demandJs');
    }
}