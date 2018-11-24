<?php

class Unit_of_measure extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('Unit');
    }

    public function index()

    {
        if(isset($_SESSION['user'])){
            $data['Unit'] = $this->Unit->unit_of_measure();
            $this->load->view('Store/header');
            $this->load->view('Store/unit_of_measure/add',$data);
            $this->load->view('Store/footer');
            $this->load->view('Store/js/donationtypeJs');
        }
else{
    $this->load->view('login');
}

    }

    public function UnitOfMeasure()
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

    public function Delete_unit($id)
    {   

        $check = $this->Unit->Delete_unit($id);
        if ($check) {
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }

    public function unitById($id)
    {
        $check = $this->Unit->unitBy_Id($id);
        $donation = array('unit_of_measure' =>$check[0]->name);
        echo json_encode($donation);

    }

    public function update_unit($id)
    {
        $check = $this->Unit->Update_unit($id);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }
}