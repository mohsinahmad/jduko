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
        $status = array(2);
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
//        exit();
//        echo '<pre>';
//
//        print_r($data['demandItems']);
//        exit();
        $items = $this->load->view('Store/items/DemandItemsTable', $data, TRUE);
        echo $items;
    }
    public function get_item_for_issue($id){
        $data['item_issue'] = $this->ItemIssueModel->get_item_for_issue($id);
        // echo $this->db->last_query();
        echo json_encode($data['item_issue']);
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
//        $data_array = explode(',',$_POST['points']);
        $check = $this->ItemIssueModel->save_Issue_Item();
        // echo $this->db->last_query();
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
//        $this->db->last_query();
//        echo "<pre>";
//        print_r($data['ApprovedQuantity']);
//        exit();
        $items = $this->load->view('Store/item_return/IssueViewTable ', $data, TRUE);
     echo $items;
    }

}