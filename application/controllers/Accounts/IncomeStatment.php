<?php

class IncomeStatment extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ChartModel');
        $this->load->model('CalenderModel');
        $this->load->model('CompanyModel');
        $this->load->model('LinkModel');
        $this->load->model('BookModel');
    }

    public function index()
    {
        $data['accountname'] = $this->ChartModel->get_account_name('', 2);
        $data['parents'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/income/GetIcomeStatment', $data);
        $this->load->view('Accounts/footer');;
    }

    public function GetValue()
    {   

        $data['withOutZero'] = $_POST['withOutZero'];
        $data['print'] = $_POST['print'];
        $to = $_POST['from'];
        $from = $_POST['to'];
        $incomeOf = $_POST['incomeof'];
        $level = $_POST['level'];
        if(!isset($_POST['level'])){
            echo '<script>alert("لیول کو منتخب کریں!!!"); window.close();</script>';
        }else{
            $parent = $_POST['parent'];
            $account_level = $_POST['account_level'];
            $accounts = array(4,5);
            $data['pre_transactions'] = $this->BookModel->getTrailBalance_pre($accounts,$account_level,$level,$incomeOf,$to,$from);
            // echo $this->db->last_query();
            // exit()
            $data['post_transactions'] = $this->BookModel->getTrailBalance_post($accounts,$account_level,$level,$incomeOf,$to,$from);
            $data['AccountTree'] = $this->ChartModel->GetAccountTree($accounts);
            $Company_data = $this->CompanyModel->get_company_name($level);
            $data['level'] = $Company_data;
            $data['Links'] = $this->LinkModel->links($data['AccountTree'],$level);
            $data['to'] = $this->CalenderModel->getHijriDate($to);
            $data['from'] = $this->CalenderModel->getHijriDate($from);
            $data['IsZero'] = $this->LinkModel->checkForZeroData($data['AccountTree'],$data['Links'],$account_level,$data['pre_transactions'],$data['post_transactions']);
            $data['Income_Of'] = $_POST['incomeof'];            
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
//             echo '<pre>';
// print_r($data['post_transactions']);
// exit();

            if ($data['withOutZero'] == 0){
                $this->load->view('Accounts/Reports/income/IncomeStatmentWithZero',$data);
            }else{
                $this->load->view('Accounts/Reports/income/IncomeStatmentWithOutZero',$data);
            }
        }
    }
    public function getConsolidatedIncomeStatement()
    {
        $data['accountname']=$this->ChartModel->get_account_name('',2);
        $data['parents'] = $this->CompanyModel->getHead();
        $data['levelname'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/income/GetConsolidatedIncomeStatement',$data);
        $this->load->view('Accounts/footer');
    }
    public function Get_Consolidated_IncomeStatement()
    {
        $data['withOutZero'] = $_POST['withOutZero'];
        $data['print'] = $_POST['print'];
        $to = $_POST['to'];
        $from = $_POST['from'];
        $incomeOf = $_POST['incomeof'];
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
            $accounts = array(4,5);
            $data['pre_transactions'] = $this->BookModel->getTrailBalance_pre($accounts,$account_level,$level_ids,$incomeOf,$to,$from,1);
            $data['post_transactions'] = $this->BookModel->getTrailBalance_post($accounts,$account_level,$level_ids,$incomeOf,$to,$from,1);

            $data['AccountTree'] = $this->ChartModel->GetAccountTree($accounts);

            $Company_data = $this->CompanyModel->get_company_name($level_id);
            $data['level'] = $Company_data;

            $data['Links'] = $this->LinkModel->links($data['AccountTree'],$level_ids,1);

            $data['to'] = $this->CalenderModel->getHijriDate($to);
            $data['from'] = $this->CalenderModel->getHijriDate($from);

            $data['IsZero'] = $this->LinkModel->checkForZeroData($data['AccountTree'],$data['Links'],$account_level,$data['pre_transactions'],$data['post_transactions']);

            $data['Income_Of'] = $_POST['incomeof'];
            
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
                $this->load->view('Accounts/Reports/income/IncomeStatmentWithZero',$data);
            }else{
                $this->load->view('Accounts/Reports/income/IncomeStatmentWithOutZero',$data);
            }
        }
    }
}