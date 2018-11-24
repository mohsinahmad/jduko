<?php

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 5/19/2017
 * Time: 12:47 PM
 */
class Configuration extends MY_Controller
{
    private $Access_level;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ChartModel');
        $this->load->model('MaddatModel');
        $this->load->model('ReportConfigurationModel');
        if (isset($_SESSION['comp_id'])){
            $this->Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $this->Access_level = $_SESSION['comp'];
        }else{
            $this->Access_level = '';
        }
    }

    public function index()
    {
        $data['report'] =$this->ReportConfigurationModel->Reportname();
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/configuraion/index',$data);
        $this->load->view('Accounts/footer');
    }

    public function getMaddadAcc($mad_id,$report_id)
    {
        $accWithoutLevel = $this->MaddatModel->GetMaddatsAcc($mad_id,$report_id,$this->Access_level);
        if($accWithoutLevel){
            echo true;
        }else{
            echo false;
        }
    }

    public function config($report_id)
    {
        $acc = array();
        $accWithoutLevel = array();

        $report_name = $this->ReportConfigurationModel->ReportNameBy_Id($report_id);

        $data['report_id'] = $report_id;
        $data['reportName'] = $report_name[0]->ReportName;
        $data['maddat'] = $this->MaddatModel->Get_Maddat_Name($report_id);
        foreach ($data['maddat'] as $datum) {
            $acc[] = $this->MaddatModel->GetMaddat_AccountID($datum->Id,1,$this->Access_level);
            $accWithoutLevel[] = $this->MaddatModel->GetMaddat_AccountID($datum->Id,1);
        }
        $data['acc'] = $acc;
        $data['accWithoutLevel'] = $accWithoutLevel;
        // echo "<pre>";
        // print_r($data);
        // exit();

        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/configuraion/ReportConfig',$data);
        $this->load->view('Accounts/footer');
    }

    public function getIncomeAccounts($level)
    {
        $data = $this->ChartModel->get_account_name_2($level);
        echo json_encode($data);
    }

    public function saveData()
    {
        $check = $this->MaddatModel->Save_Data();
        if($check){
            $this->session->set_flashdata('success',"مدّات کامیابی سے شامل ہوگئ");
            redirect('Accounts/Configuration','refresh');
        }else{
            $this->session->set_flashdata('error',"مدّات کنفیگریشن ناکام");
            redirect('Accounts/Configuration','refresh');
        }
    }

    public function deleteMaddad($mad_id,$report_id)
    {
        $check = $this->MaddatModel->delete_maddad($mad_id,$report_id,$this->Access_level);
        if($check){
            echo true;
        }else{
            echo false;
        }
    }

    public function update()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit();
        $check = $this->MaddatModel->Update();
        if($check){
            $this->session->set_flashdata('success',"مدّات کامیابی سے شامل ہوگئ");
            redirect('Accounts/Configuration','refresh');
        }else{
            $this->session->set_flashdata('error',"مدّات کنفیگریشن ناکام");
            redirect('Accounts/Configuration','refresh');
        }
    }

    public function SetTransactions($level,$report)
    {
        $data = $this->MaddatModel->set_transactions();
    }
}