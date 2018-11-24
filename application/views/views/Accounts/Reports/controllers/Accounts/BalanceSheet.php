<?php

class BalanceSheet extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ChartModel');
        $this->load->model('CompanyModel');
        $this->load->model('CalenderModel');
        $this->load->model('BookModel');
    }

    public function index()
    {
        $data['accountname'] = $this->ChartModel->get_account_name('', 2);
        $data['parents'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/balancesheet/GetBalanceSheet',$data);
        $this->load->view('Accounts/footer');
    }

    public function GetBalanceSheet()
    {
        $data['withOutZero'] = $_POST['withOutZero'];
        $data['print'] = $_POST['print'];
        $to_date = $this->CalenderModel->get_min_date();
        $to = $to_date[0]->Sh_date;
        $from = $_POST['from'];
        $sheetOf = $_POST['sheetOf'];
        $level = $_POST['level'];
        if(!isset($_POST['level'])){
            echo '<script>alert("لیول کو منتخب کریں!!!"); window.close();</script>';
        }else{
            $parent = $_POST['parent'];
            $account_level = $_POST['account_level'];
            $accounts = array(1,2,3);
            $data['pre_transactions'] = $this->BookModel->getTrailBalance_pre($accounts, $account_level, $level, $sheetOf, $to, $from);
            $data['post_transactions'] = $this->BookModel->getTrailBalance_post($accounts, $account_level, $level, $sheetOf, $to, $from);

            $profit_loss = array(4,5);
            $data['profit_loss'] = $this->BookModel->getTrailBalance_post($profit_loss, 1, $level, $sheetOf, $to, $from);

            $data['AccountTree'] = $this->ChartModel->GetAccountTree($accounts);
            $data['Links'] = $this->LinkModel->links($data['AccountTree'],$level);
            $data['to'] = $this->CalenderModel->getHijriDate($to_date[0]->Sh_date);
            $data['from'] = $this->CalenderModel->getHijriDate($from);
            $Company_data = $this->CompanyModel->get_company_name($level);
            $data['level'] = $Company_data;

            $data['IsZero'] = $this->LinkModel->checkForZeroData($data['AccountTree'],$data['Links'],$account_level,$data['pre_transactions'],$data['post_transactions']);

            $data['B_Sheet_Of'] = $_POST['sheetOf'];

            if(isset($_POST['account_level'])) {
                if ($_POST['account_level'] == 1) {
                    $data['account_level'] = 1;
                } elseif ($_POST['account_level'] == 3) {
                    $data['account_level'] = 2;
                } elseif ($_POST['account_level'] == 5) {
                    $data['account_level'] = 3;
                } elseif ($_POST['account_level'] == 7) {
                    $data['account_level'] = 4;
                } elseif ($_POST['account_level'] == 9) {
                    $data['account_level'] = 5;
                } elseif ($_POST['account_level'] == 11) {
                    $data['account_level'] = 6;
                } elseif ($_POST['account_level'] == 13) {
                    $data['account_level'] = 7;
                } elseif ($_POST['account_level'] == 'detail') {
                    $data['account_level'] = "Detail";
                }
            }
            if ($data['withOutZero'] == 0){
                $this->load->view('Accounts/Reports/balancesheet/balancesheetWithZero',$data);
            }else{
                $this->load->view('Accounts/Reports/balancesheet/balancesheetWithOutZero',$data);
            }
        }
    }

    public function getConsolidatedBalanceSheet()
    {
        $data['accountname']=$this->ChartModel->get_account_name('',2);
        $data['parents'] = $this->CompanyModel->getHead();
        $data['levelname'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/balancesheet/GetConsolidatedBalanceSheet',$data);
        $this->load->view('Accounts/footer');
    }

    public function Get_Consolidated_BalanceSheet()
    {
        // echo "<pre>";
        // print_r($_POST);
        // exit();
        $data['withOutZero'] = $_POST['withOutZero'];
        $data['print'] = $_POST['print'];
        $to_date = $this->CalenderModel->get_min_date();
        $to = $to_date[0]->Sh_date;
        $from = $_POST['from'];
        $sheetOf = $_POST['sheetOf'];
        //$level = $_POST['level'];
        $level_id = $_POST['level'];

        $level = $this->CompanyModel->getSubHeadCon($level_id);
        foreach ($level as $levels){
            $level_ids[] = $levels->Id;
            $level_name = $levels->LevelName;
        }
        if(!isset($_POST['level'])){
            echo '<script>alert("لیول کو منتخب کریں!!!"); window.close();</script>';
        }else{
            $parent = $_POST['parent'];
            $account_level = $_POST['account_level'];
            $accounts = array(1,2,3);
            $data['pre_transactions'] = $this->BookModel->getTrailBalance_pre($accounts, $account_level, $level_ids, $sheetOf, $to, $from,1);
            $data['post_transactions'] = $this->BookModel->getTrailBalance_post($accounts, $account_level, $level_ids, $sheetOf, $to, $from,1);

            $profit_loss = array(4,5);
            $data['profit_loss'] = $this->BookModel->getTrailBalance_post($profit_loss, 1, $level_ids, $sheetOf, $to, $from,1);

            $data['AccountTree'] = $this->ChartModel->GetAccountTree($accounts);
            $data['Links'] = $this->LinkModel->links($data['AccountTree'],$level_ids,1);
            $data['to'] = $this->CalenderModel->getHijriDate($to_date[0]->Sh_date);
            $data['from'] = $this->CalenderModel->getHijriDate($from);
            $Company_data = $this->CompanyModel->get_company_name($level_id);
            $data['level'] = $Company_data;

            $data['IsZero'] = $this->LinkModel->checkForZeroData($data['AccountTree'],$data['Links'],$account_level,$data['pre_transactions'],$data['post_transactions']);

            $data['B_Sheet_Of'] = $_POST['sheetOf'];

            if(isset($_POST['account_level'])) {
                if ($_POST['account_level'] == 1) {
                    $data['account_level'] = 1;
                } elseif ($_POST['account_level'] == 3) {
                    $data['account_level'] = 2;
                } elseif ($_POST['account_level'] == 5) {
                    $data['account_level'] = 3;
                } elseif ($_POST['account_level'] == 7) {
                    $data['account_level'] = 4;
                } elseif ($_POST['account_level'] == 9) {
                    $data['account_level'] = 5;
                } elseif ($_POST['account_level'] == 11) {
                    $data['account_level'] = 6;
                } elseif ($_POST['account_level'] == 13) {
                    $data['account_level'] = 7;
                } elseif ($_POST['account_level'] == 'detail') {
                    $data['account_level'] = "Detail";
                }
            }
            if ($data['withOutZero'] == 0){
                $this->load->view('Accounts/Reports/balancesheet/balancesheetWithZero',$data);
            }else{
                $this->load->view('Accounts/Reports/balancesheet/balancesheetWithOutZero',$data);
            }
        }
    }
}