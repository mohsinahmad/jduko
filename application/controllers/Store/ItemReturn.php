<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *  change its name as => ItemReturn
 */
class ItemReturn extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ItemReturnModel');
        $this->load->model('DemandFormModel');
        $this->load->model('DepartmentModel');
        $this->load->model('StockReciveModel');
        $this->load->model('ItemIssueModel');
    }
    public function index($is_redirect = '',$is_sec = '')
    {
        $data['issued'] = $this->ItemReturnModel->GetItemIssued($is_redirect,$is_sec);
//         echo $this->db->last_query();
//        echo '<pre>';
//       echo $_SESSION['user'][0]->UserName;
//     //   print_r($_SESSION['user']);
//        echo '</pre>';
        $this->load->view('Store/header');
        $this->load->view('Store/item_return/Return_item', $data);
        $this->load->view('Store/footer');
    }
    public function ReturnForm($id)
    {
        $data['ReturnForm'] = $this->ItemIssueModel->Get_Issue_Voucher($id);
       // echo '<pre>'.$this->db->last_query();
       // print_r($data['ReturnForm']);
       // echo exit();
        $data['Quantitty'] = $this->DemandFormModel->Get_demand_quantity($id);
        // echo '<pre>';
       // print_r($data['ReturnForm']);
        //  echo '<pre>';
        // echo $this->db->last_query();
        // exit();
        $data['departments'] = $this->DepartmentModel->department_name();
        // echo '<pre>';
        // print_r($data);
        // echo exit();
        $this->load->view('Store/header');
        $this->load->view('Store/item_return/ReturnForm',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/demandJs');
    }
    public function Save()
    {

       // echo '<pre>';
       // print_r($_POST);
       // echo exit();
        $check = $this->ItemReturnModel->SaveReturn();
        if($check){
            $this->session->set_flashdata('success',"Added Successfully");
            redirect('Store/ItemReturn/ReturnItem','refresh');
        }else{
            $this->session->set_flashdata('error',"Not Inserted");
            redirect('Store/ItemReturn','refresh');
        }
    }

    public function ViewVoucher($id)
    {
        
        $data['return']= $this->ItemReturnModel->Get_Return($id);
        $data['ItemIssuedDetailed']= $this->ItemReturnModel->GetItemIssuedDetailed($id);
        
              // echo '<pre>';
             //   echo $this->db->last_query();   
            // print_r($data);
            // exit();

        $this->load->view('Store/item_return/Return_Voucher',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/demandJs');
    }
    public function ReturnItem()
    {
        $data['Returned'] = $this->ItemReturnModel->GetItemReturned();
        // echo '<pre>'.$this->db->last_query();;
        // print_r($data);
        // exit();
        $this->load->view('Store/header');
        $this->load->view('Store/item_return/ReturnView', $data);
        $this->load->view('Store/footer');
    }
    public function ReturnEdit()
    {
        $this->load->view('Store/header');
        $this->load->view('Store/item_return/ReturnEdit');
        $this->load->view('Store/footer');
        $this->load->view('Store/js/demandJs');
    }
    public function RecivedItems($id)
    {
        $data['revived']= $this->ItemReturnModel->Get_Recived($id);
//         echo $this->db->last_query();
        $items = $this->load->view('Store/item_return/ReturnViewTable', $data, TRUE);
        echo $items;
    }
    public function StatusUpadte($id)
    {
        $check = $this->ItemReturnModel->Status_Update($id);
        if ($check) {
            $response = array('success' => "ok");

        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
   public function StatusUpadteIssue($id)
    {
        $check = $this->ItemReturnModel->Status_Update_Issue($id);
        if ($check) {
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
    public function View_Voucher($issue_id,$Receive_Slip_Id = '')
    {   
        $data = array();
        if ($Receive_Slip_Id != '' && isset($issue_id) ) {
            $data['viewstock'] = $this->StockReciveModel->Get_Stock_Voucher($Receive_Slip_Id);
           // echo $this->db->last_query();
            $data['IssueSlip'] = $this->ItemIssueModel->Get_data_for_Voucher($issue_id);
          //  echo $this->db->last_query();
            $price_sum = 0;
            foreach ($data['viewstock'] as $datum) {
                $price_sum = $datum->Item_price + $price_sum;
            }
            $price_sum = (string)$price_sum;
            $data['AmountInWords'] = $this->Amount($price_sum);
            $data['AmountInNumber'] = $price_sum;
             $this->load->view('Store/item_return/combine_view',$data);
            $this->load->view('Store/footer');
        }else{

            $data['IssueSlip'] = $this->ItemIssueModel->Get_Issue_Voucher($issue_id);
            $data['demand_no'] = $data['IssueSlip'][0]->issue_form;
            $data['status'] = $data['IssueSlip'][0]->Status;
          // $data['ApproveQuantity'] = $this->ItemIssueModel->Get_Approve_Quanity($issue_id);
//          echo $this->db->last_query();
             $this->load->view('Store/items/ItemIssueVoucher',$data);
            $this->load->view('Store/footer');
        }
    }


    public function get_details_return(){
//
//        print_r($_POST);
              $result =   $this->db->query('SELECT *,item_suppliers.NameU, unit_of_measure.name as unit_name, item_setup.name as item_name, item_stockrecieve_slip_details.Item_id,
        item_stockrecieve_slip_details.Item_quantity as quantity,item_stockrecieve_slip_details.id as detail_id, item_stockrecieve_slip_details.donation_type as detail_donation
        FROM `item_stockrecieve_slip`
        left join item_stockrecieve_slip_details on item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id
        left join item_setup on item_setup.id = item_stockrecieve_slip_details.Item_id
        left JOIN unit_of_measure on item_setup.unit_of_measure = unit_of_measure.id
        left JOIN item_suppliers on item_stockrecieve_slip.Supplier_Id = item_suppliers.Id
        WHERE `item_stockrecieve_slip_details`.`Item_id` = '.$_POST['item_id'].' AND `item_stockrecieve_slip_details`.`donation_type` = '.$_POST['donation'].'')->result();

//      echo $this->db->last_query();

         echo json_encode($result);
    }


   /*
   comment by muhammad sufyan old function

   public function View_Voucher($issue_id,$Receive_Slip_Id = '')
    {   $data = array();
        if ($Receive_Slip_Id != '' && isset($issue_id) ) {
            $data['viewstock'] = $this->StockReciveModel->Get_Stock_Voucher($Receive_Slip_Id);
            $data['IssueSlip'] = $this->ItemIssueModel->Get_data_for_Voucher($issue_id);
            $price_sum = 0;
            foreach ($data['viewstock'] as $datum) {
                $price_sum = $datum->Item_price + $price_sum;
            }
            $price_sum = (string)$price_sum;
            $data['AmountInWords'] = $this->Amount($price_sum);
            $data['AmountInNumber'] = $price_sum;
           // $this->load->view('Store/item_return/combine_view',$data);
            $this->load->view('Store/footer');
        }else{
            $data['IssueSlip'] = $this->ItemIssueModel->Get_Issue_Voucher($issue_id);
            $data['ApproveQuantity'] = $this->ItemIssueModel->Get_Approve_Quanity($issue_id);
            $this->load->view('Store/items/ItemIssueVoucher',$data);
            $this->load->view('Store/footer');
        }
    }*/
}