<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 5/4/2017
 * Time: 10:07 AM
 */

class ApproveDemandModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemIssueModel');
    }

    public function Get_Approved_Quantity($id)
    {
        $this->db->select('sum(Approve_Quantity) as Approve_Quantity');
        $this->db->where('Item_id',$id);
        return $this->db->get('item_demand_approve_details')->result();
    }

    public function GetApproved_Quantity($id)
    {
        $this->db->select('item_demand_approve.Id as Approve_id,item_demand_approve_details.Demand_approve_id,item_demand_approve_details.Approve_Quantity, `item_demand_form`.`Id` as `d_id`,item_demand_form.Form_Number,item_demand_approve_details.Item_id');
        $this->db->from('item_demand_approve');
        $this->db->join('item_demand_approve_details', 'item_demand_approve.Id = item_demand_approve_details.Demand_approve_id');
        $this->db->join('item_demand_form', 'item_demand_approve.Demand_form_id = item_demand_form.Id');
        $this->db->where('item_demand_approve.Demand_form_id', $id);
        $this->db->order_by('item_demand_approve_details.Item_id');
        return $this->db->get()->result();
    }

    public function GetApproved_Quantity_edit($id)
    {
        $this->db->select('item_demand_approve_details.Approve_Quantity');
        $this->db->from('item_demand_approve');
        $this->db->join('item_demand_approve_details', 'item_demand_approve.Id = item_demand_approve_details.Demand_approve_id');
        $this->db->join('item_demand_form', 'item_demand_approve.Demand_form_id = item_demand_form.Id');
        $this->db->where('item_demand_approve.Demand_form_id', $id);
        return $this->db->get()->result();
    }

    public function Save_Approved_Demand()
    {

        $total_items = $_POST['Total_Items'];
//        echo $total_items;
//exit();
//
//        echo '<pre>';
//        print_r($_POST);
//        exit();
        foreach ($_POST['items'] as $key => $value) {
            if($value == ""){
                $total_items--;
            }
            $items['items'][$key] = explode(',', $value);
            $quantity['quantity'][$key] = explode(',', $_POST['quantity'][$key]);
        }
//        print_r($_POST['quantity']);
//        exit();
//        echo $_POST['demand_id'];
//        $this->db->select('id');
//        $this->db->where('Demand_form_id',$_POST['demand_id']);
//        $query =  $this->db->get('item_issue_details');
//        echo '<pre>';
//        print_r($query->result());
//        echo $total_items;
//        exit();
        $this->db->set('Approve_dateG', $_POST['CenglishDate']);
        $this->db->set('Approve_dateH', $_POST['CislamicDate']);
        $this->db->set('status','2');
        $this->db->where('id',$_POST['demand_id']);
        $this->db->update('item_issue');
        $this->db->set('Approve_dateG',$_POST['CenglishDate']);
        $this->db->set('Approve_dateH',$_POST['CislamicDate']);
        $this->db->where('demand_id',$_POST['demand_id']);
        $this->db->update('date_process');
//        $Demand_approve_id = $this->db->insert_id();
 //    if (isset($_POST['IsComplete'])) {
//        $this->ItemIssueModel->updateStatus($_POST['demand_id'], 2);
////        echo 'status two';
//    } else {
//        if ($total_items != $_POST['Total_Items'] && $_POST['Total_Items'] > 1) {
//            $this->ItemIssueModel->updateStatus($_POST['demand_id'], 1);
////            echo '<pre>'.'if';
////            print_r($total_items);
////            exit();
//        }
//        else if($total_items == 1 && $_POST['quantity'][0] == ''){
//            $this->ItemIssueModel->updateStatus($_POST['demand_id'], 0);
////            echo '<pre>'.'else if one';
////            print_r($total_items);
////            exit();
//        }
//        else if($total_items == 1 && $_POST['quantity'][0] == '0'  || $_POST['quantity'][0] == '0.00'){
//            $this->ItemIssueModel->updateStatus($_POST['demand_id'], 0);
////            echo '<pre>'.'else if two';
////            print_r($total_items);
////            exit();
//        }
//        else {
//            $this->ItemIssueModel->updateStatus($_POST['demand_id'], 2);
////            echo '<pre>'.'else';
////            print_r($total_items);
////            exit();
//        }
//    }
//    if($query->num_rows() == 0){
//        foreach ($items['items'] as $key1 => $items) {
//            foreach ($items as $key2 => $item) {
//                $this->db->set('Demand_approve_id', $Demand_approve_id);
//                $this->db->set('Item_id', $_POST['Item_Id'][$key1]);
//                $this->db->set('Approve_Quantity', $quantity['quantity'][$key1][$key2]);
//                $this->db->set('CreatedBy', $_SESSION['user'][0]->id);
//                $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
//                $this->db->insert('item_demand_approve_details');
//            }
//        }
//    }
//    else {
//        $this->db->where('Demand_approve_id', $query->result()[0]->id);
//        $this->db->delete('item_demand_approve_details');
        foreach ($items['items'] as $key1 => $items) {
            foreach ($items as $key2 => $item) {
//                echo $_POST['demand_detail_id'][$key1];
                if($quantity['quantity'][$key1][$key2] > 0){
                    $status = '1';
                }
                else{
                    $status = '0';
                }
                $this->db->set('Approve_Quantity', $quantity['quantity'][$key1][$key2]);
                $this->db->set('status', $status);
                $this->db->set('CreatedBy', $_SESSION['user'][0]->id);
                $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                $this->db->where('item_issue_details.id',$_POST['demand_detail_id'][$key1]);
                $this->db->update('item_issue_details');
            }
        }
//    }
    if ($this->db->affected_rows() > 0) {
        return true;
    } else {
        return false;
    }
 }
//    public function Update_Approved_Demand()
//    {
//        $total_items = $_POST['Total_Items'];
//        foreach ($_POST['items'] as $key => $value) {
//            if($value == ""){
//                $total_items--;
//            }
//            $items['items'][$key] = explode(',', $value);
//            $quantity['quantity'][$key] = explode(',', $_POST['quantity'][$key]);
//        }
//
////        echo '<pre>';
////        print_r($_POST);
////        exit();
//        //$this->db->set('Demand_form_id',$_POST['demand_id']);
//        $this->db->set('Approve_dateG',$_POST['CenglishDate']);
//        $this->db->set('Approve_dateH',$_POST['CislamicDate']);
//        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
//        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
//        $this->db->where('Demand_form_id',$_POST['demand_id']);
//        $this->db->update('item_demand_approve');
//        $this->db->select('id');
//        $this->db->where('Demand_form_id',$_POST['demand_id']);
//        $query =  $this->db->get('item_demand_approve')->result();
//
//        if(isset($_POST['IsComplete'])){
//            $this->ItemIssueModel->updateStatus($_POST['demand_id'],1);
//        }else{
//            if($total_items == $_POST['Total_Items']){
//                $this->ItemIssueModel->updateStatus($_POST['demand_id'],2);
//            }else{
//                $this->ItemIssueModel->updateStatus($_POST['demand_id'],3);
//            }
//        }
//        $this->db->where('Demand_approve_id', $query[0]->id);
//        $this->db->delete('item_demand_approve_details');
//        foreach ($items['items'] as $key1 => $items) {
//            foreach ($items as $key2 => $item) {
//               $this->db->set('Demand_approve_id',$query[0]->id);
//                $this->db->set('Item_id',$_POST['Item_Id'][$key1]);
//                $this->db->set('Approve_Quantity',$quantity['quantity'][$key1][$key2]);
//                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
//                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
//                $this->db->insert('item_demand_approve_details');
//            }
//        }
//        if($this->db->affected_rows() > 0){
//            return true;
//        }else{
//            return false;
//        }
//    }
}



