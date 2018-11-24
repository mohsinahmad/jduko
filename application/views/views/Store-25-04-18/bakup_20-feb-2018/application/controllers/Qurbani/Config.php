<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/1/2017
 * Time: 11:20 AM
 */

class Config extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
    }

    public function QurbaniTime()
    {
//        echo '<pre>';
//        $this->db->where('Vendor_Id',0);
//        print_r($this->db->get('qur_cash_reciept')->result());
//        exit();
        $data['timings'] = $this->QurConfigModel->Get_All('qur_time');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Config/Time',$data);
        $this->load->view('Qurbani/footer');
    }

    public function QurbaniTime_Save()
    {
        $check = $this->QurConfigModel->Save_Time();
        if($check){
            $this->session->set_flashdata('success',"کامیابی");
            redirect('Qurbani/Config/QurbaniTime','refresh');
        }else{
            $this->session->set_flashdata('error',"خرابی");
            redirect('Qurbani/Config/QurbaniTime','refresh');
        }
    }

    public function Share_Amount()
    {
        $data['Share'] = $this->QurConfigModel->Get_All('qur_hissa','',1);
        $data['Locations'] = $this->QurConfigModel->Get_All('qur_location');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Config/Amount',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Share_Amount_Save()
    {
        $check = $this->QurConfigModel->Save_Amount();
        if($check){
            $this->session->set_flashdata('success',"کامیابی");
            redirect('Qurbani/Config/Share_Amount','refresh');
        }else{
            $this->session->set_flashdata('error',"خرابی");
            redirect('Qurbani/Config/Share_Amount','refresh');
        }
    }
}