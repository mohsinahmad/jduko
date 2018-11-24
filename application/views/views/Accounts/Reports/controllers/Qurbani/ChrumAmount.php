<?php
/**
 *
 */
class ChrumAmount extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurChrumAmountModel');
    }

    public function index()
    {
        $data['Chrum_Amount'] = $this->QurChrumAmountModel->Get_Chrum_Amount();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Chrum/add_amount',$data);
        $this->load->view('Qurbani/footer');
    }

    public function getById($id)
    {
        $Chrum_Amount = $this->QurChrumAmountModel->Get_Chrum_Amount($id);
        echo json_encode($Chrum_Amount);
    }

    public function Save()
    {
        $check = $this->QurChrumAmountModel->Save_Chrum_amount();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/ChrumAmount','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/ChrumAmount','refresh');
        }
    }

    public function Update()
    {
        $check = $this->QurChrumAmountModel->Save_Chrum_amount();
        if($check){
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
}