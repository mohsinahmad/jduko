<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receipt extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurHissaModel');
        $this->load->model('QurCowModel');
        $this->load->model('QurSlipModel');
        $this->load->model('QurExpenceDetailModel');
        $this->load->model('QurExpenceEstimationModel');
    }

    public function index($to = '',$from ='')
    {
        if ($to != '' && $from != '') {
            $data['SlipNo'] = $this->QurSlipModel->Get_Slip_No('','',$to,$from);
            $table = $this->load->view('Qurbani/Receipt/ReceiptTable', $data ,true);
            echo $table;
        }else{
            $data['SlipNo'] = $this->QurSlipModel->Get_Slip_No();
            $this->load->view('Qurbani/header');
            $this->load->view('Qurbani/Receipt/Receipt',$data);
            $this->load->view('Qurbani/footer');
        }
    }

    public function get_receipt_By_CowNumber($cow_number)
    {
        $data['SlipNo'] = $this->QurSlipModel->Get_Slip_No('','','','',$cow_number);
        $table = $this->load->view('Qurbani/Receipt/ReceiptTable', $data ,true);
        echo $table;
    }

    public function NewReceipt()
    {
        $data['Per_Head'] = $this->QurHissaModel->get_All();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Receipt/ReceiptPage',$data);
        $this->load->view('Qurbani/footer');
    }

    public function SaveReceipt()
    {
        $status = $this->QurSlipModel->Save_Receipt();
        if($status){
            $this->session->set_flashdata('success', 'رسید کامیابی سے بن گیَ');
            redirect('Qurbani/Receipt','refresh');
        }else{
            $this->session->set_flashdata('error', 'رسید نہی بنی ۔۔ کیوں نہیں بنی یہ نہیں  پتہ');
            redirect('Qurbani/Receipt','refresh');
        }
    }

    public function getCowNumber()
    {
        $CowNumber = $this->QurCowModel->get_cow_number();
        echo json_encode($CowNumber);
    }

    public function getSelfCowNumber()
    {
        $CowNumber = $this->QurHissaModel->get_self_cow_number();
        $CowNumberSerial = $this->QurHissaModel->get_self_cow_number_serial();
        if($CowNumber->num_rows() > 0){
            $new_self_cow_number = ++$CowNumber->row()->Code;
        }else{
            $new_self_cow_number = $CowNumberSerial->Self_Cow_No;
        }
        echo $new_self_cow_number;
    }

    public function checkCowCount()
    {
        $CowNumber = $this->QurCowModel->check_cow_count();
        $cowInfo = array('Count' => $CowNumber[0]->Count, 'JDK_Count' => $CowNumber[0]->JDK_Count );
        echo json_encode($cowInfo);
    }

    public function QurbaniReport1()
    {
        $arr  = array(1,2,3);
        foreach ($arr as $key => $value) {
            $data['Ijtimai'][] = $this->QurSlipModel->Get_Amount_Via_Date($value , 0);
            $data['Inferadi'][] = $this->QurSlipModel->Get_Amount_Via_Date($value , 1);
            $data['Last_Cow'][] = $this->QurCowModel->getMaxCode($value);
        }

        $this->load->view('Qurbani/Reports/QurbaniReport1',$data);
        $this->load->view('Qurbani/footer');
    }

    public function EditReceipt($id,$Day)
    {
        $data['Receipt'] = $this->QurSlipModel->Get_Slip_No($id,$Day);
        $data['Per_Head'] = $this->QurHissaModel->get_All();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Receipt/ReceiptPageEdit',$data);
        $this->load->view('Qurbani/footer');
    }
    public function ViewReceipt($id)
    {
        $data['Receipt'] = $this->QurSlipModel->Get_Slip_No($id);
        $data['Per_Head'] = $this->QurHissaModel->get_All();
        $this->load->view('Qurbani/Receipt/ViewReceipt',$data);
        $this->load->view('Qurbani/footer');

    }

    public function deleteReceipt($id,$Day)
    {
        $check = $this->QurSlipModel->Delete_Slip($id,$Day);
        if($check){
            $response = array('success' => "ok", 'message' => "رسید حذف کامیاب");
        }else{
            $response = array('error' => "ok" ,'message' => "رسید حذف ناکام");
        }
        echo json_encode($response);
    }

    public function UpdateReceipt()
    {
        $id = $_POST['Slip_Id'];
        $is_updated = $this->QurSlipModel->Update_Slip($id);
        if($is_updated){
            $this->session->set_flashdata('success', 'رسید تدوین کامیاب');
            redirect('Qurbani/Receipt','refresh');
        }else{
            $this->session->set_flashdata('error', 'رسید تدوین ناکام');
            redirect('Qurbani/Receipt','refresh');
        }
    }

    public function ViewCowSlip()
    {
        $to = $_POST['to'];
        $from = $_POST['from'];
        $day = $_POST['Collection_Day'];
        $per_head_expence = 0;
        $final = 0;
        $data['Cows'] = $this->QurCowModel->Get_Cows($from,$to,$day);
        $data['Per_Head'] = $this->QurHissaModel->get_All();
        $data['No_Cows'] = $this->QurCowModel->Get_No_Cows();
        foreach ($data['Cows'] as $key => $value) {
            $data['TotalExpence'][$value[0]->Code] = $this->QurExpenceEstimationModel->GetEstimation($value[0]->Code);
        }
        if ($data['Cows'] != array()) {
            $this->load->view('Qurbani/Receipt/CowSlip',$data);
        }else{
            echo '<script>alert("مندرجہ بالا گائے نمبر بک نہیں ہوئیں۔"); window.close();</script>';
        }
    }

    public function GetCowSlot()
    {
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Receipt/GetCowSlot');
        $this->load->view('Qurbani/footer');
    }

    public function GetRemaingCowsHissa()
    {
        $data['Remaining'] = $this->QurCowModel->GetRemainingHissa();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Receipt/RemainingHissa',$data);
        $this->load->view('Qurbani/footer');
    }
}