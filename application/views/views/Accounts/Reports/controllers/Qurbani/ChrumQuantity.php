<?php
/**
 *
 */
class ChrumQuantity extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurChrumAmountModel');
        $this->load->model('QurHulqyModel');
        $this->load->model('QurChrumQuantityModel');
        $this->load->model('QurChrumOldDataModel');
        $this->load->model('QurSaleSlipModel');
        $this->load->model('QurChrumSaleModel');
    }

    public function index()
    {
        $data['Chrum_Quantity'] = $this->QurChrumQuantityModel->Get_Chrum_Quantity();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Chrum/chrum_quantity',$data);
        $this->load->view('Qurbani/footer');

    }
    public function Add_Quantity($id='')
    {
        if ($id != ''){
            $data['quantity_data'] = $this->QurChrumQuantityModel->Get_data_For_Chrum_Slip($id);
        }
        $data['Chrum_Amount'] = $this->QurChrumAmountModel->Get_Chrum_Amount();
        $data['Hulqy'] = $this->QurHulqyModel->Get_Hulqy();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Chrum/add_quantity',$data);
        $this->load->view('Qurbani/footer');
    }

    public function Getsupervisorname($id)
    {
        $hulqy = $this->QurHulqyModel->Get_Hulqy($id);
        echo json_encode($hulqy);
    }

    public function Save()
    {
        $check = $this->QurChrumQuantityModel->Save_Quantity();
        if($check){
            $this->session->set_flashdata('success', 'Inserted');
            redirect('Qurbani/ChrumQuantity','refresh');
        }else{
            $this->session->set_flashdata('error', 'Not Inserted');
            redirect('Qurbani/ChrumQuantity','refresh');
        }
    }

    public function ViewChrumQuantitySlip($id)
    {
        $data['Quantity'] = $this->QurChrumQuantityModel->Get_data_For_Chrum_Slip($id);
        $this->load->view('Qurbani/Chrum/ViewChrumSlip',$data);
    }

    public function ViewComparative_report()
    {
        $this->QurChrumOldDataModel->Save_Old_Data();
        $data['amount'] = $this->QurChrumAmountModel->Get_Data_Chrum_Quantity_Amount();
        $data['old'] = $this->QurChrumOldDataModel->Get_old_data();
        $data['per_Chrum'] = $this->QurChrumAmountModel->Get_Chrum_Amount();
        $data['details'] = $_POST['details'];
        $this->load->view('Qurbani/Reports/ComparativeReport/Comparative_report',$data);
    }

    public function GetOldDataSale()
    {
        $data['OLD_Data'] = $this->QurChrumSaleModel->GetData();
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Reports/ComparativeReport/Get_Report2',$data);
        $this->load->view('Qurbani/footer');
    }

    public function ViewComparativeSale_Report()
    {
        $this->QurChrumSaleModel->Save_Sale_Chrum_old();
        $data['SaleSlip'] = $this->QurSaleSlipModel->Get_Data_For_SaleSlip();
        $data['amount'] = $this->QurChrumAmountModel->Get_Data_Chrum_Quantity_Amount();
        $data['old'] = $this->QurChrumOldDataModel->Get_old_data();
        $data['OLD_Data'] = $_POST;
        // echo "<pre>";
        // print_r($data);
        // exit();
        $this->load->view('Qurbani/Reports/ComparativeReport/Comparative_report2',$data);

    }
}