<?php
class ExpenceType extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurExpenceTypeModel');
    }

    public function index()
    {
        $data['Expence_Type'] = $this->QurExpenceTypeModel->Get_type();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Expence/Add_Expence_Type',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Save()
    {
        $check = $this->QurExpenceTypeModel->Save_Type();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/ExpenceType','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/ExpenceType','refresh');
        }
    }
    public function ExpenceTypeById($id)
    {
        $check = $this->QurExpenceTypeModel->ExpenceTypeBy_Id($id);
        $expence = array('type' =>$check[0]->type);
        echo json_encode($expence);

    }

    public function UpdateExpenceType($id)
    {
        $check = $this->QurExpenceTypeModel->Update_Expencetype($id);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }

    public function DeleteExpenceType($id)
    {
        $check = $this->QurExpenceTypeModel->Delete_ExpenceType($id);
        if ($check) {
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
}