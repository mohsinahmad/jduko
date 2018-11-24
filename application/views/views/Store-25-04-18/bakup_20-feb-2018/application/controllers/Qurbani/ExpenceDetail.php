<?php
/**
 *
 */
class ExpenceDetail extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurExpenceTypeModel');
        $this->load->model('QurExpenceDetailModel');
        $this->load->model('QurConfigModel');
    }

    public function index()
    {
        $data['Expence_Detail'] = $this->QurExpenceDetailModel->Get_Expence_Detail();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Expence/Expence_Detail',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Add_Expence()
    {
        $data['Expence_Type'] = $this->QurExpenceTypeModel->Get_type();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Expence/Add_Expence_Detail',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Edit_Expence($id)
    {
        $data['Expence_Detail'] = $this->QurExpenceDetailModel->Get_Expence_Detail($id);
        $data['Expence_Type'] = $this->QurExpenceTypeModel->Get_type();

        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Expence/Edit_Expence_Detail',$data);
        $this->load->view('Qurbani/footer');

    }

    public function Save()
    {
        $check = $this->QurExpenceDetailModel->Save_Detail();
        if($check){
            $this->session->set_flashdata('success', 'کامیابی سے اندراج ہوگیا۔');
            redirect('Qurbani/ExpenceDetail','refresh');
        }else{
            $this->session->set_flashdata('error', 'اندراج نہی ہوا');
            redirect('Qurbani/ExpenceDetail','refresh');
        }
    }

    public function UpdateExpenseDetail($id)
    {
        $check = $this->QurExpenceDetailModel->Update_Detail($id);
        if($check){
            $this->session->set_flashdata('success', 'کامیابی سے اندراج ہوگیا۔');
            redirect('Qurbani/ExpenceDetail','refresh');
        }else{
            $this->session->set_flashdata('error', 'اندراج نہی ہوسکا');
            redirect('Qurbani/ExpenceDetail','refresh');
        }
    }

    public function PrintExpenseVoucher($id)
    {
        $data['Expence_Detail'] = $this->QurExpenceDetailModel->Get_Expence_Detail($id);
        $price_sum = 0;
        foreach ($data['Expence_Detail'] as $datum) {
            $price_sum = $datum->Amount + $price_sum;
        }
        $price_sum = (string)$price_sum;
        $data['AmountInWords'] = $this->Amount($price_sum);
        $data['AmountInNumber'] = $price_sum;
        $this->load->view('Qurbani/Expence/ExpenseVoucher',$data);
    }

    public function Delete_voucher($id)
    {
        $check = $this->QurExpenceDetailModel->Delete_voucher($id);
        if($check){
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }
    public function GetExpenceName($id)
    {
        $data = $this->QurExpenceTypeModel->Get_type($id);
        $name = array('_id' => $data[0]->id, '_name' => $data[0]->type);
        echo json_encode($name);
    }

    public function ViewExpence_Voucher($id)
    {
        $data['vouchers'] = $this->QurConfigModel->Get_All('qur_expence_voucher',$id);
        $price_sum = 0;
        foreach ($data['vouchers'] as $datum) {
            $price_sum = $datum->Amount + $price_sum;
        }
        $price_sum = (string)$price_sum;
        $data['AmountInWords'] = $this->Amount($price_sum);
        $data['AmountInNumber'] = $price_sum;
        $this->load->view('Qurbani/PaymentVoucehrs/Print_PaymentVoucehrs',$data);
        $this->load->view('Qurbani/footer');
    }

    public function SaveExpenceVouvher()
    {
        $check = $this->QurExpenceDetailModel->Save_Expence_Voucher();
        if($check){
            $this->session->set_flashdata('success', 'کامیابی سے اندراج ہوگیا۔');
            redirect('Qurbani/ExpenceDetail/ExpenceVoucherList','refresh');
        }else{
            $this->session->set_flashdata('error', 'اندراج نہی ہوا');
            redirect('Qurbani/ExpenceDetail/ExpenceVoucherList','refresh');
        }
    }

    public function ExpenceVoucher($id='')
    {
        $data['hulqy'] = $this->QurConfigModel->Get_All('qur_hulqy');
        $this->load->view('Qurbani/header');
        if ($id != ''){
            $data['edit'] = $this->QurConfigModel->Get_All('qur_expence_voucher',$id);
            $this->load->view('Qurbani/PaymentVoucehrs/PaymentVoucehrsPage',$data);
        }else{
            $this->load->view('Qurbani/PaymentVoucehrs/PaymentVoucehrsPage',$data);
        }
        $this->load->view('Qurbani/footer');
    }

    public function DeleteExpenceVoucher($id)
    {
        $check = $this->QurExpenceDetailModel->Delete_exp_voucher($id);
        if($check){
            $response = array('success' => "ok", 'message' => "رسید حذف کامیاب");
        }else{
            $response = array('error' => "ok" ,'message' => "رسید حذف ناکام");
        }
        echo json_encode($response);
    }

    public function ExpenceVoucherList()
    {
        $data['vouchers'] = $this->QurConfigModel->Get_All('qur_expence_voucher');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/PaymentVoucehrs/PaymentVoucehrs',$data);
        $this->load->view('Qurbani/footer');
    }
}