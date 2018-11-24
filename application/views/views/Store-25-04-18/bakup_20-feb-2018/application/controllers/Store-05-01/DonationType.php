<?php

class DonationType extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('DonationTypeModel');
    }

    public function index()

    {	$data['donationtype'] = $this->DonationTypeModel->donation_type();
        $this->load->view('Store/header');
        $this->load->view('Store/donation/add',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/donationtypeJs');
    }


    public function DonationType()
    {
        $check = $this->DonationTypeModel->add_donationtype();
        if($check){
            $this->session->set_flashdata('success',"عطیہ کامیابی سے شامل ہوگیا");
            redirect('Store/DonationType','refresh');
        }else{
            $this->session->set_flashdata('error',"عطیہ کا نام درج کریں");
            redirect('Store/DonationType','refresh');
        }
    }

    public function DeleteDonationType($id)
    {   

        $check = $this->DonationTypeModel->Delete_DonationType($id);
        if ($check) {
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }

    public function DonationTypeById($id)
    {
        $check = $this->DonationTypeModel->DonationTypeBy_Id($id);
        $donation = array('Donation_Type' =>$check[0]->Donation_Type);
        echo json_encode($donation);

    }

    public function UpdateDonationType($id)
    {
        $check = $this->DonationTypeModel->Update_donationtype($id);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }
}