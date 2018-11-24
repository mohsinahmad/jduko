<?php
/**
* 
*/
class AmountDescription extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('AmountDescriptionModel');
		$this->load->model('IncomeModel');
	}
	public function index($id)
	{
		$data['Cheque'] = $this->AmountDescriptionModel->Get_Edit_Cheque_Detail($id);
		$data['TotalChequeAmount'] = $this->AmountDescriptionModel->Get_total_cheque_amount($id);
		$data['Currency'] = $this->AmountDescriptionModel->Get_Edit_Currency_Detail($id);
		$data['TotalCurrencyAmount'] = $this->AmountDescriptionModel->Get_total_currency_amount($id);
		$data['currency_name'] = $this->AmountDescriptionModel->GetCurrency();
		$data['DebitSum'] = $this->IncomeModel->GetSumDebitForAmountDescription($id);
		$data['Voucher_No'] = $this->IncomeModel->Get_Voucher_No_For_AmountDescription($id);

       // echo "<pre>";
       // print_r($data);
       // exit();

		if ($data['Cheque'] != 0 || $data['Currency'] != 0)  {
			$this->load->view('Accounts/header');
	        $this->load->view('Accounts/Reports/incomereport/AmountDescriptionEdit',$data);
	        $this->load->view('Accounts/footer');
		}else{
			$data['Currency'] = $this->AmountDescriptionModel->GetCurrency();
			$this->load->view('Accounts/header');
	        $this->load->view('Accounts/Reports/incomereport/AmountDescription',$data);
	        $this->load->view('Accounts/footer');
		}
	}

	public function SaveChequeCurrencyDetail($income_id)
	{
		$check = $this->AmountDescriptionModel->Save_Cheque_Currency_Detail($income_id);
		if($check){
            $this->session->set_flashdata('success',"Added Successfully");
            redirect('Accounts/AmountDescription/AmountDescription_Report/'.$income_id,'refresh');
        }else{
            $this->session->set_flashdata('error',"Not Inserted");
            redirect('Accounts/AmountDescription/index/'.$income_id,'refresh');
        }
	}

	public function EditChequeCurrencyDetail($income_id)
	{	
		if (isset($_SESSION['comp_id'])){
            $Level_id = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Level_id = $_SESSION['comp'];
        }else{
            $Level_id = '';
        }
	    $check = $this->AmountDescriptionModel->Update_Income_Amount_Details($income_id);
		if($check){
            $this->session->set_flashdata('success',"Added Successfully");
            redirect('Accounts/Books/AllBooks/inc/'.$Level_id,'refresh');
        }else{
            $this->session->set_flashdata('error',"Not Inserted");
            redirect('Accounts/Books/AllBooks/inc/'.$Level_id,'refresh');
        }
	}

	public function AmountDescription_Report($income_id)
	{
//        foreach ($income_id as $item) {
//            echo 'sdasd';
//	    }
		$data['Cheque'] = $this->AmountDescriptionModel->Get_Edit_Cheque_Detail($income_id);
		$data['Currency'] = $this->AmountDescriptionModel->Get_Edit_Currency_Detail($income_id);
//		 echo "<pre>";
//		 print_r($data);
//		 exit();
		$this->load->view('Accounts/Reports/incomereport/AmountDescriptionReport',$data);
	}
}