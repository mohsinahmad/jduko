<?php
/**
 *
 */
class ExpenceEstimation extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurExpenceEstimationModel');
    }

    public function index()
    {
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Expence/Expence_Estimation');
        $this->load->view('Qurbani/footer');
    }

    public function GetDataForEstimation($from,$to,$day)
    {
        $check = $this->QurExpenceEstimationModel->Get_Data_For_Estimation($from,$to,$day);
        echo json_encode($check);
    }

    public function SaveExpenceEstimation()
    {
        $check = $this->QurExpenceEstimationModel->Save_Expence_Estimation();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/ExpenceEstimation','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/ExpenceEstimation','refresh');
        }
    }
}