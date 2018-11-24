<?php
/**
* 
*/
class StockSlip extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('ItemSetupModel');
		$this->load->model('StockReciveModel');
		$this->load->model('SupplierModel');
	}

	public function index()
	{
		$data['stock'] = $this->StockReciveModel->Get_Stock();

//        echo '<pre>';
////        print_r($data['stock']);
//        echo $this->db->last_query();
//        echo '</pre>';
        $this->load->view('Store/header');
		$this->load->view('Store/stockslip/StockReciveSlip',$data);
		$this->load->view('Store/footer');
		$this->load->view('Store/js/stockJs');
	}

	public function AddStock()
	{
        // work by sufyan
        $type = $_SESSION['type'];
		$data['items'] = $this->ItemSetupModel->getAllItems();
//        echo '<pre>';
//        echo $this->db->last_query();
//        echo '</pre>';
        $data['supplier'] = $this->SupplierModel->GetSupplier(0);
		$this->load->view('Store/header');
		$this->load->view('Store/stockslip/StockReciveSlipPage',$data);
		$this->load->view('Store/footer');
	}

	public function GetItemCode($id)
	{
		$data = $this->ItemSetupModel->GetCode($id);
		$ids =  array('_id' => $data[0]->Id);
		echo json_encode($ids);
	}

	public function Save()
	{

//        echo '<pre>';
//        print_r($_POST);
//        echo '</pre>';
	    isset($_POST['IssueNow'])?$IssueNow = 1:$IssueNow=0;
	    // if (isset($_POST['IssueNow'])) {
	    // 	$IssueNow = 1;
	    // }else{
	    // 	$IssueNow = 0;
	    // }
		$result = $this->StockReciveModel->Save_Stock();
//        echo '<pre>';
//        echo $this->db->last_query();
//        echo '</pre>';
        if($result){
            $this->session->set_flashdata('success',"Stock Added Successfully");
            if ($IssueNow == 1){
                $this->db->select_max('id');
                $this_id = $this->db->get('item_stockrecieve_slip')->result();
                redirect('Store/DirectIssue/index/'.$this_id[0]->id,'refresh');
            }else{
                redirect('Store/StockSlip','refresh');
            }
        }else{
            $this->session->set_flashdata('error',"Stock Not Inserted");
            if ($IssueNow == 1){
                redirect('Store/StockSlip','refresh');
            }else{
                redirect('Store/StockSlip','refresh');
            }
        }
	}

	public function Update_Stock($id)
	{
		$data['items'] = $this->ItemSetupModel->GetItems();
		$data['slips'] = $this->SupplierModel->GetSupplierById($id);
		$data['supplier'] = $this->SupplierModel->GetSupplier(0);
		$this->load->view('Store/header');
		$this->load->view('Store/stockslip/StockReciveSlipPageEdit',$data);
		$this->load->view('Store/footer');
	}

	public function ViewVoucher($id)
	{
		$data['viewstock'] = $this->StockReciveModel->Get_Stock_Voucher($id);


        $price_sum = 0;
		foreach ($data['viewstock'] as $datum) {
		    $price_sum = $datum->Item_price + $price_sum;
        }
        $price_sum = (string)$price_sum;
        $data['AmountInWords'] = $this->Amount($price_sum);
        $data['AmountInNumber'] = $price_sum;
        $this->load->view('Store/stockslip/StockVoucher',$data);
		$this->load->view('Store/footer');
	}

	public function UpdateStockSlip()
	{
		$result = $this->StockReciveModel->UpdateStock();
        if($result){
            $this->session->set_flashdata('success',"Stock Slip Edited Successfully");
            redirect('Store/StockSlip','refresh');
        }else{
            $this->session->set_flashdata('error',"Stock Slip Not Edited");
            redirect('Store/StockSlip','refresh');
        }
	}

	public function DeleteStockSlip($id)
	{
		$check = $this->StockReciveModel->DeleteStock($id);
        if ($check) {
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
	}
}