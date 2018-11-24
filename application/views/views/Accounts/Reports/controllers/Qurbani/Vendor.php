<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/28/2017
 * Time: 3:41 PM
 */

class Vendor extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
        $this->load->model('QurVendorModel');
    }

    public function index($id='')
    {
        ($id!='')?$data['edit']='asd':'';
        $data['vendors'] = $this->QurConfigModel->Get_All('qur_khaal_vendor',$id);
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Vendor/index',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Save()
    {
        $check = $this->QurVendorModel->Save();
        if($check){
            $this->session->set_flashdata('success',"کامیابی");
            redirect('Qurbani/Vendor','refresh');
        }else{
            $this->session->set_flashdata('error',"خرابی");
            redirect('Qurbani/Vendor','refresh');
        }
    }

    public function delete($id)
    {
        $check = $this->QurVendorModel->Delete($id);
        if($check == 'true'){
            $response = array('success' => "ok");
        }elseif ($check == 'falsess'){
            $response = array('exist' => "ok");
        }
        else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
}