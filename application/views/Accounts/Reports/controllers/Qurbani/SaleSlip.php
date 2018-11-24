<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/28/2017
 * Time: 4:27 PM
 */

class SaleSlip extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
        $this->load->model('QurSaleSlipModel');
    }

    public function index()
    {
        $data['Slips'] = $this->QurSaleSlipModel->Get_Slips();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/SaleSlip/SaleSlip',$data);
        $this->load->view('Qurbani/footer');
    }

    public function AddSlip($id='')
    {
        if ($id != ''){
            $data['SlipData'] = $this->QurSaleSlipModel->Get_Slips($id);
            $data['Edit'] = 'asdasd';
        }
        $data['Slips'] = $this->QurConfigModel->Get_All('qur_khaal_vendor');
        $data['Chrum'] = $this->QurConfigModel->Get_All('qur_chrum_amount');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/SaleSlip/SaleSlipPage',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Save()
    {
        $check = $this->QurSaleSlipModel->Save();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/SaleSlip','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/SaleSlip','refresh');
        }
    }
    public function GatePass($id,$check='')
    {
        $data['Slips'] = $this->QurSaleSlipModel->Get_Slips($id);
        if ($check == 1) {
            $this->load->view('Qurbani/SaleSlip/SaleSlipbyPayment',$data);
        }else{

            $this->load->view('Qurbani/SaleSlip/GatePass',$data);
        }
    }

    public function Delete($id)
    {
        $check = $this->QurSaleSlipModel->Delete($id);
        if($check){
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
}