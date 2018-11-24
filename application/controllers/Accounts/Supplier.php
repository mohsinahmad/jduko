<?php
/**
*
*/
class Supplier extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('SupplierModel');
	}

	public function index()
	{
	    $data['accounts'] = $this->ChartModel->get_account_name_2(41,2);
		$data['supplier'] = $this->SupplierModel->GetSupplier(1);

		$this->load->view('Accounts/header');
		$this->load->view('Accounts/supplier/add',$data);
		$this->load->view('Accounts/footer');
		$this->load->view('Accounts/js/supplierJs');
	}

	public function Save()
	{
		$check = $this->SupplierModel->Save_Supplier();
		if($check){
            $this->session->set_flashdata('success'," سپلائر کامیابی سے شامل ہوگیا");
            redirect('Accounts/Supplier','refresh');
        }else{
            $this->session->set_flashdata('error',"Supplier Not Inserted");
            redirect('Accounts/Supplier','refresh');
        }
	}

	public function SupplierById($id)
    {
    	$check = $this->SupplierModel->SupplierBy_Id($id);
    	$supplier = array(
            'NameU' =>$check[0]->NameU,
    		'NameE' =>$check[0]->NameE,
    		'NTN_number' =>$check[0]->NTN_number,
    		'CNIC' =>$check[0]->CNIC,
    		'Phone_number' =>$check[0]->Phone_number,
            'AddressE' =>$check[0]->AddressE,
    		'AddressU' =>$check[0]->AddressU,
    		'Contact_person' =>$check[0]->Contact_person,
    		'ChartOfAcc_id' =>$check[0]->ChartOfAcc_id,
    		'Nature_Of_Payment' =>$check[0]->Nature_Of_Payment);
    	 echo json_encode($supplier);
    }

    public function UpdateSupplier($id)
    {
    	$check = $this->SupplierModel->Update_Supplier($id);
            if($check){
                $response= array('success' => "ok");
            } else{
                $response=array('error' =>"ok");
            }
        echo json_encode($response);
    }

    public function DeleteSupplier($id)
    {
    	$check = $this->SupplierModel->Delete_Supplier($id);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }

}