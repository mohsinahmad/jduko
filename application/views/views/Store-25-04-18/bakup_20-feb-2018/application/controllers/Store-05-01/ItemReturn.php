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

    public function index($is_redirect = '')
    {
        $data['issued'] = $this->ItemReturnModel->GetItemIssued($is_redirect);
        $this->load->view('Store/header');
        $this->load->view('Store/item_return/Return_item', $data);
        $this->load->view('Store/footer');
    }

    public function ReturnForm($id)
    {
        $data['ReturnForm'] = $this->DemandFormModel->get_issue($id);
        $data['Quantitty'] = $this->DemandFormModel->Get_demand_quantity($id);
        $data['departments'] = $this->DepartmentModel->department_name();
        $this->load->view('Store/header');
        $this->load->view('Store/item_return/ReturnForm',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/demandJs');
    }

    public function Save()
    {
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

        $this->load->view('Store/item_return/Return_Voucher',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/demandJs');
    }

    public function ReturnItem()
    {
        $data['Returned'] = $this->ItemReturnModel->GetItemReturned();
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
            $this->load->view('Store/item_return/combine_view',$data);
            $this->load->view('Store/footer');
        }else{
            $data['IssueSlip'] = $this->ItemIssueModel->Get_Issue_Voucher($issue_id);
            $data['ApproveQuantity'] = $this->ItemIssueModel->Get_Approve_Quanity($issue_id);

            $this->load->view('Store/items/ItemIssueVoucher',$data);
            $this->load->view('Store/footer');
        }
    }
}