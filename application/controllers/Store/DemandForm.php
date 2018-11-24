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

            $status = array(0, 1, 2);
            $data['demands'] = $this->DemandFormModel->Get_Demands_For_Approval($status);
//            echo $this->db->last_query();
            $this->load->view('Store/header');
            $this->load->view('Store/demandform/DemandForm', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/demandJs');

	}
	public function get_unit_by_item($id){

//        echo $this->input->post('id');
        // $id = $this->input->post('id');
        $this->db->select('`item_setup`.`name` as Item_Name, `unit_of_measure`.`name`,item_setup.category_Id,item_sub_categories.Name as Child_Name,item_categories.Name as Parent_Name,item_sub_categories.parent_id,sum(item_setup_details.current_quantity) as current_quantity');
        $this->db->join('unit_of_measure','unit_of_measure.id = item_setup.unit_of_measure');
        $this->db->join('item_sub_categories','item_sub_categories.id = item_setup.category_Id');
        $this->db->join('item_categories','item_categories.Id = item_sub_categories.parent_id');
        $this->db->join('item_setup_details','item_setup_details.item_setup_id = item_setup.Id','left');
        $this->db->where('item_setup.id',$id);
        $result = $this->db->get('item_setup')->result();
        //echo $this->db->last_query();
        echo json_encode($result);

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
            $result = $this->DemandFormModel->saveForm();
            // echo $this->db->last_query();
            if ($result == true) {
                $this->session->set_flashdata('success', "Demand Added Successfully");
                redirect('Store/DemandForm');
            } else {                
                redirect('Store/DemandForm'); 
            }
	}
    public function FormEdit($id)
    {
            $data['Items'] = $this->ItemSetupModel->get_items();
            $data['form_data'] = $this->DemandFormModel->editForm($id);
            $data['departments'] = $this->DepartmentModel->department_name();
            $data['donation_types'] = $this->DonationTypeModel->donation_type();
            $this->load->view('Store/header');
            $this->load->view('Store/demandform/DemandFormPageEdit', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/demandJs');
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
//        echo $id;
//        exit();
       $data['demands'] = $this->DemandFormModel->Get_Demands($id);
      // echo $this->db->last_query();
       $this->load->view('Store/demandform/Demand_Voucher',$data);
       $this->load->view('Store/footer');
       $this->load->view('Store/js/demandJs');
    }
}