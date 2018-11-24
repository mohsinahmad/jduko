<?php

class DonationType extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('DonationTypeModel');
    }

    public function index()

    {
        if(isset($_SESSION['user'])){
            $data['donationtype'] = $this->DonationTypeModel->donation_type();
            $this->load->view('Store/header');
            $this->load->view('Store/donation/add',$data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/donationtypeJs');
        }
else{
    $this->load->view('login');
}

    }

    public function DonationType()
    {
        if(isset($_SESSION['user'])) {
            $check = $this->DonationTypeModel->add_donationtype();
            $val = $_POST['donation_type'];
            if ($val == '') {
                $this->session->set_flashdata('error', "عطیہ کا نام درج کریں");
            } else {


                if ($check) {
                    $this->session->set_flashdata('success', "تعاوّن کامیابی سے شامل ہوگیا");
                    redirect('Store/DonationType','refresh');
                } else {

                    $this->session->set_flashdata('error', "تعاوّن پہلے سے موجود ہے ");
                    redirect('Store/DonationType','refresh');
                }

            }
        }
        else{
            $this->load->view('login');
        }

        // sufyan work end here
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