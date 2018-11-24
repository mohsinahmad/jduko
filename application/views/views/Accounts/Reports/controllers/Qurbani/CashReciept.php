<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/29/2017
 * Time: 9:53 AM
 */

class CashReciept extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
        $this->load->model('QurCashRecieptModel');
    }

    public function index()
    {
        $data['Reciepts'] = $this->QurCashRecieptModel->Get_All_Reciepts();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/CashReciept/CashReciept',$data);
        $this->load->view('Qurbani/footer');
    }

    public function AddReciept($id='')
    {
        if ($id != ''){
            $data['Reciepts'] = $this->QurCashRecieptModel->Get_All_Reciepts($id);
        }
        $data['Vendors'] = $this->QurConfigModel->Get_All('qur_khaal_vendor');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/CashReciept/CashRecieptPage',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Save()
    {
        $check = $this->QurCashRecieptModel->Save();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/CashReciept','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/CashReciept','refresh');
        }
    }

    public function Delete($id)
    {
        $check = $this->QurCashRecieptModel->Delete($id);
        if($check){
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
}