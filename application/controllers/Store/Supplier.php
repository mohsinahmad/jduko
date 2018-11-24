<?php
/**
* 
*/
class Supplier extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('SupplierModel');
	}

	public function index()
	{
		$data['supplier'] = $this->SupplierModel->GetSupplier(0);
		$this->load->view('Store/header');
		$this->load->view('Store/supplier/add',$data);
		$this->load->view('Store/footer');
		$this->load->view('Store/js/supplierJs');
	}

	public function SaveSupplier()
	{
		$check = $this->SupplierModel->Save_Supplier();
		if($check){
            $this->session->set_flashdata('success',"Supplier Added Successfully");
            redirect('Store/Supplier','refresh');
        }else{
            $this->session->set_flashdata('error',"Supplier Not Inserted");
            redirect('Store/Supplier','refresh');
        }
	}
	public function SupplierById($id)
    {
    	$check = $this->SupplierModel->SupplierBy_Id($id);
    	// print_r($check);
    	// exit();
    	$supplier = array(
            'NameU' =>$check[0]->NameU,
    		'NameE' =>$check[0]->NameE,
    		'NTN_number' =>$check[0]->NTN_number,
    		'STN_number' =>$check[0]->STN_number,
    		'Phone_number' =>$check[0]->Phone_number,
            'AddressE' =>$check[0]->AddressE,
    		'AddressU' =>$check[0]->AddressU,
    		'Contact_person' =>$check[0]->Contact_person);
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