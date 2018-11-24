<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/1/2017
 * Time: 3:15 PM
 */

class Transfer extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
        $this->load->model('QurSlipModel');
        $this->load->model('QurTransferModel');
    }

    public function index()
    {
        $data['transafer_data'] = $this->QurConfigModel->Get_All('qur_incomereport');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Transfer/index',$data);
        $this->load->view('Qurbani/footer');
    }

    public function AddNew($id = '')
    {
        if ($id != ''){
            $data['edit'] = $this->QurConfigModel->Get_All('qur_incomereport',$id);
        }else{
            $data['Ijtemai'] = $this->QurSlipModel->Get_Amount_Via_Date('',0);
            $data['Infiradi'] = $this->QurSlipModel->Get_Amount_Via_Date('',1);
            $data['total_transfer'] = $this->QurTransferModel->get_All();
        }
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Transfer/Transfer',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Print_recpt($id)
    {
        $data['Ijtemai'] = $this->QurSlipModel->Get_Amount_Via_Date('',0);
        $data['Infiradi'] = $this->QurSlipModel->Get_Amount_Via_Date('',1);
        $data['slip_data'] = $this->QurConfigModel->Get_All('qur_incomereport',$id);
        $this->load->view('Qurbani/Transfer/Slip',$data);
    }

    public function Save()
    {
        $check = $this->QurTransferModel->Save();
        if($check){
            $this->session->set_flashdata('success',"کامیابی");
            redirect('Qurbani/Transfer','refresh');
        }else{
            $this->session->set_flashdata('error',"خرابی");
            redirect('Qurbani/Transfer','refresh');
        }
    }
}