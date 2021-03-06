<?php
/**
* 
*/
class DirectIssue extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('SupplierModel');
		$this->load->model('ItemSetupModel');
		$this->load->model('ItemIssueModel');
		$this->load->model('DepartmentModel');
		$this->load->model('CompanyModel');
	}

	public function index($id)
	{
		$data['slips'] = $this->SupplierModel->GetSupplierById($id);
		$data['supplier'] = $this->SupplierModel->GetSupplier(0);
		$data['company'] = $this->CompanyModel->getCompanies();
		$data['departments'] = $this->DepartmentModel->department_name();
		// echo "<pre>";
		// print_r($data);
		// exit();
		$this->load->view('Store/header');
		$this->load->view('Store/direct_issue/DirectIssuePage',$data);
		$this->load->view('Store/footer');
	}

	public function SaveIssueItem()
	{
//        echo '<pre>';
//        print_r($_POST);
//        exit();

        $check = $this->ItemIssueModel->save_Issue_Item();
		if($check){
            $this->session->set_flashdata('کامیاب',"اجراء ہو گیا ہے");
            redirect('Store/ItemReturn/index/1/2','refresh');
        }else{
            $this->session->set_flashdata('انتباہ',"اجراء نیہں ہوا ہے");
            redirect('Store/ItemReturn/index/1/2','refresh');
        }
	}

	public function ViewSlip($id)
	{	

		//$this->load->view('Store/header');
		$this->load->view('Store/direct_issue/DirectIssueSlip',$data);
		$this->load->view('Store/footer');
	}
}