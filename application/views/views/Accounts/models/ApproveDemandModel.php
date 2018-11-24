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
        return $this->db->get()->result();

    }

    public function Save_Approved_Demand()
    {
        $total_items = $_POST['Total_Items'];
        foreach ($_POST['items'] as $key => $value) {
            if($value == ""){
                $total_items--;
            }
            $items['items'][$key] = explode(',', $value);
            $quantity['quantity'][$key] = explode(',', $_POST['quantity'][$key]);
        }

        $this->db->set('Demand_form_id',$_POST['demand_id']);
        $this->db->set('Approve_dateG',$_POST['CenglishDate']);
        $this->db->set('Approve_dateH',$_POST['CislamicDate']);
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        $this->db->insert('item_demand_approve');

        $Demand_approve_id = $this->db->insert_id();

        if(isset($_POST['IsComplete'])){
            $this->ItemIssueModel->updateStatus($_POST['demand_id'],2);    
        }else{
            if($total_items == $_POST['Total_Items']){
                $this->ItemIssueModel->updateStatus($_POST['demand_id'],2);
            }else{
                $this->ItemIssueModel->updateStatus($_POST['demand_id'],3);
            }
        }

        foreach ($items['items'] as $key1 => $items) {
            foreach ($items as $key2 => $item) {
                $this->db->set('Demand_approve_id',$Demand_approve_id);
                $this->db->set('Item_id',$_POST['Item_Id'][$key1]);
                $this->db->set('Approve_Quantity',$quantity['quantity'][$key1][$key2]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('item_demand_approve_details');
            }
        }

        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}