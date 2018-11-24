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
        if(isset($_SESSION['comp'])) {
            $status = array(0, 1, 2, 3);
            $data['demands'] = $this->DemandFormModel->Get_Demands_For_Approval($status);
            $this->load->view('Store/header');
            $this->load->view('Store/approve_demand/ApproveDemand', $data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/approveDemandJs');
        }
        else{
            $this->load->view('login');
        }
    }

    public function getApproveDemandItems($id)
    {

        if(isset($_SESSION['comp'])) {
            $data['demandItems'] = $this->ItemIssueModel->get_Demand_Items($id);
            $items = $this->load->view('Store/approve_demand/DemandItemsTable', $data, TRUE);
            echo $items;
        }

    else{
        $this->load->view('login');
    }

    }

    public function getDataToApprove($code)
    {
        if(isset($_SESSION['comp'])) {
            $data = array();
            $ItemIssueModel = $this->ItemIssueModel->getCurrentQuantity($code);
//        echo "<pre>";
//        print_r($data);
//        exit();
            foreach ($ItemIssueModel as $key => $value) {
                $data['current_quantity'][$key] = $value->current_quantity;
                $ApprovedQuantity[$key] = $this->ApproveDemandModel->Get_Approved_Quantity($value->Id);
                if (isset($ApprovedQuantity[$key][0]->Approve_Quantity)) {
                    $data['Approve_Quantity'][$key] = $ApprovedQuantity[$key][0]->Approve_Quantity;
                } else {
                    $data['Approve_Quantity'][$key] = 0.00;
                }
                $data['Available_to_approve'][$key] = $data['current_quantity'][$key] - $data['Approve_Quantity'][$key];
                $data['donation_type'][$key] = $value->Donation_Type;
                $data['Item_id'][$key] = $value->Id;
            }
            echo json_encode($data);
        }
        else{
            $this->load->view('login');
        }
    }

    public function SaveApprovedDemand()
    {
        if(isset($_SESSION['comp'])) {
        $check = $this->ApproveDemandModel->Save_Approved_Demand();
        if($check){
            $this->session->set_flashdata('کامیاب',"ڈیمانڈ کی منظور کامیاب");
            redirect('Store/ApproveDemand','refresh');
        }else{
            $this->session->set_flashdata('انتباہ',"ڈیمانڈ منظور نہیں ہوئ");
            redirect('Store/ApproveDemand','refresh');
        }
        }
        else{
            $this->load->view('login');
        }

    }
}