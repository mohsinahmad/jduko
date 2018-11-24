<?php
/**
 *
 */
class Hulqy extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurHulqyModel');
        $this->load->model('QurHissaModel');
    }

    public function index($id='')
    {
        ($id != '')?$data['edit']='':'';
        $data['Hulqy'] = $this->QurHulqyModel->Get_Hulqy($id);
        $data['checkType'] = $this->QurHissaModel->get_All();

        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Hulqy/add_hulqy',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Save()
    {
        $check = $this->QurHulqyModel->Save_Hulqy();
        if($check){
            $this->session->set_flashdata('success', 'شامل کردیا گیا۔۔!!');
            redirect('Qurbani/Hulqy','refresh');
        }else{
            $this->session->set_flashdata('error', 'شامل نہیں کیا جاسکا، دوبارہ کوشش کیجیئے۔۔!!');
            redirect('Qurbani/Hulqy','refresh');
        }
    }

    public function delete($id)
    {
        $check = $this->QurHulqyModel->Delete_Hulqy($id);
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