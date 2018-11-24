<?php
/**
 *
 */
class ChrumOldData extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurChrumAmountModel');
        $this->load->model('QurChrumOldDataModel');
    }

    public function index()
    {
        $data['Old_data'] = $this->QurChrumOldDataModel->GetData();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Reports/ComparativeReport/getReport',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Save()
    {
        $check = $this->QurChrumOldDataModel->Save_Old_Data();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/ChrumOldData','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/ChrumOldData','refresh');
        }
    }
}