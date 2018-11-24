<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ApproveDemand extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('DemandFormModel');
        $this->load->model('ItemIssueModel');
        $this->load->model('ApproveDemandModel');
    }
    public function index()
    {
            $status = array(1,2);
            $data['demands'] = $this->DemandFormModel->Get_Demands_For_Approval($status);
//            echo $this->db->last_query();
            $this->load->view('Store/header');
            $this->load->view('Store/approve_demand/ApproveDemand', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/approveDemandJs');
    }
    public function ApproveDemand_done(){
        $status = array(2,3);
        $data['demands'] = $this->DemandFormModel->Get_Demands_For_Approval($status);
        $this->load->view('Store/header');
        $this->load->view('Store/approve_demand/ApprovedDemand_done', $data);
        $this->load->view('Store/footer');
       $this->load->view('Store/js/approveDemandJs');
    }
    public function getApproveDemandItems($id)
    {
            $data['demandItems'] = $this->ItemIssueModel->get_Demand_Items($id);
            $items = $this->load->view('Store/approve_demand/DemandItemsTable', $data, TRUE);
//            echo $this->db->last_query();
            echo $items;
    }
    public function getApproveDemandItemsEdit($id)
    {
        $data['demandItems'] = $this->ItemIssueModel->get_Demand_Items_edit($id);
        //echo $this->db->last_query();
        $data['approve'] = $this->ApproveDemandModel->GetApproved_Quantity_edit($id);
//        echo $this->db->last_query();
        $items = $this->load->view('Store/approve_demand/DemandItemsTable', $data, TRUE);

//        print_r($data['approve']);
//         echo $this->db->last_query();
           echo $items;
    }
    public function get_demand_item_detail($code)
    {
              $data= $this->DemandFormModel->get_demand_item_detail($code);
//              echo $this->db->last_query();
              echo json_encode($data);
    }
    public function SaveApprovedDemand()
    {
       // echo '<pre>';
       // print_r($_POST);
       //  exit();
        $check = $this->ApproveDemandModel->Save_Approved_Demand();
//        echo $this->db->last_query();
//        exit();
        if($check){
            $this->session->set_flashdata('کامیاب',"ڈیمانڈ کی منظوری کامیاب");
            redirect('Store/ApproveDemand','refresh');
        }else{
            $this->session->set_flashdata('انتباہ',"ڈیمانڈ منظور نہیں ہوئ");
            redirect('Store/ApproveDemand','refresh');
        }
    }
    public function UpdateApprovedDemand()
    {
        $check = $this->ApproveDemandModel->Update_Approved_Demand();
//        echo $this->db->last_query();
        if($check){
            $this->session->set_flashdata('کامیاب',"ڈیمانڈ کی منظور کامیاب");
            redirect('Store/ApproveDemand','refresh');
        }else{
            $this->session->set_flashdata('انتباہ',"ڈیمانڈ منظور نہیں ہوئ");
            redirect('Store/ApproveDemand','refresh');
        }
    }
}