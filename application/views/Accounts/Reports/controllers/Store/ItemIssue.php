<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ItemIssue extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('ItemIssueModel');
        $this->load->model('DemandFormModel');
        $this->load->model('ApproveDemandModel');
    }

    public function index()
    {
        $status = array(2,3);
        $data['demands'] = $this->DemandFormModel->Get_Demands_For_Approval($status);
//        echo $this->db->last_query();
        $this->load->view('Store/header');
        $this->load->view('Store/items/ItemIssue',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }

    public function getDemandItems($id)
    {
        $data['demandItems'] = $this->ItemIssueModel->get_Demand_Items($id);
//        echo $this->db->last_query();
        $data['demandQuantity'] = $this->ItemIssueModel->get_Demand_Quantity($id);
        $data['approvequantity'] = $this->ApproveDemandModel->GetApproved_Quantity($id);
        $items = $this->load->view('Store/items/DemandItemsTable', $data, TRUE);
        echo $items;
    }

    public function ItemIssueed()
    {
        $this->load->view('Store/header');
        $this->load->view('Store/items/ItemIssueTable');
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }

    public function SaveIssueItem()
    {
        $check = $this->ItemIssueModel->save_Issue_Item();
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }

    public function GetIssuedItem($id)
    {
        $data['Issued']= $this->ItemIssueModel->Get_Issued_Item($id);

       //echo $this->db->last_query();

        $data['thisId'] = $id;
        $data['ApprovedQuantity']= $this->ItemIssueModel->s_Get_Approve_Quanity($id);
//        echo "<pre>";
//        print_r($data);
//        exit();
        $items = $this->load->view('Store/item_return/IssueViewTable ', $data, TRUE);
     echo $items;
    }

}