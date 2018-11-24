<?php

class TrialBalance extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LinkModel');
        $this->load->model('ChartModel');
        $this->load->model('BookModel');
        $this->load->model('CalenderModel');
        $this->load->model('CompanyModel');
    }

    public function index()
    {
        $this->load->view('Accounts/Reports/trialbalance/TrialBalance');
    }

    public function GetData()
    {
        $data['parents'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/trialbalance/GetTrialBalance', $data);
        $this->load->view('Accounts/footer');
    }

    public function getTrailBalance()
    {
        $data['withOutZero'] = $_POST['withOutZero'];
        $data['print'] = $_POST['print'];
        $level_id = $_POST['level'];
        $tb_of = $_POST['tbof'];
        $to = $_POST['to'];
        $from = $_POST['from'];
        $account_level = $_POST['account_level'];
        $accounts = array(1,2,3,4,5);

        $data['pre_transactions'] = $this->BookModel->getTrailBalance_pre($accounts,$account_level,$level_id,$tb_of,$to,$from);
        $data['post_transactions'] = $this->BookModel->getTrailBalance_post($accounts,$account_level,$level_id,$tb_of,$to,$from);

        $data['AccountTree'] = $this->ChartModel->GetAccountTree($accounts);
        $Company_data = $this->CompanyModel->get_company_name($level_id);
        $data['level'] = $Company_data;
        $data['Links'] = $this->LinkModel->links($data['AccountTree'],$level_id);

        $data['to'] = $this->CalenderModel->getHijriDate($to);
        $data['from'] = $this->CalenderModel->getHijriDate($from);

        $data['Trail_Balance_Of'] = $_POST['tbof'];

        $data['IsZero'] = $this->LinkModel->checkForZeroData($data['AccountTree'],$data['Links'],$account_level,$data['pre_transactions'],$data['post_transactions']);

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
            $this->load->view('Accounts/Reports/trialbalance/TrailBalanceWithZero',$data);
        }else{
            $this->load->view('Accounts/Reports/trialbalance/TrailBalanceWithOutZero',$data);
        }
    }

    public function getConsolidatedTrailBalance()
    {
        $data['accountname']=$this->ChartModel->get_account_name('',2);
        $data['parents'] = $this->CompanyModel->getHead();
        $data['levelname'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/trialbalance/GetConsolidatedTrailBalance',$data);
        $this->load->view('Accounts/footer');
    }

    public function Get_Consolidated_trailBalance()
    {
        $data['withOutZero'] = $_POST['withOutZero'];
        $data['print'] = $_POST['print'];
        $level_id = $_POST['level'];
        $tb_of = $_POST['tbof'];
        $to = $_POST['to'];
        $from = $_POST['from'];
        $account_level = $_POST['account_level'];
        $accounts = array(1,2,3,4,5);

        $level = $this->CompanyModel->getSubHeadCon($level_id);
        foreach ($level as $levels){
            $level_ids[] = $levels->Id;
            $level_name = $levels->LevelName;
        }

        $data['pre_transactions'] = $this->BookModel->getTrailBalance_pre($accounts,$account_level,$level_ids,$tb_of,$to,$from,1);
        $data['post_transactions'] = $this->BookModel->getTrailBalance_post($accounts,$account_level,$level_ids,$tb_of,$to,$from,1);
        $data['AccountTree'] = $this->ChartModel->GetAccountTree($accounts);
        $Company_data = $this->CompanyModel->get_company_name($level_id);
        $data['level'] = $Company_data;
        $data['Links'] = $this->LinkModel->links($data['AccountTree'],$level_ids,1);
        $data['to'] = $this->CalenderModel->getHijriDate($to);
        $data['from'] = $this->CalenderModel->getHijriDate($from);

        $data['Trail_Balance_Of'] = $_POST['tbof'];

        $data['IsZero'] = $this->LinkModel->checkForZeroData($data['AccountTree'],$data['Links'],$account_level,$data['pre_transactions'],$data['post_transactions']);

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
            $this->load->view('Accounts/Reports/trialbalance/TrailBalanceWithZero',$data);
        }else{
            $this->load->view('Accounts/Reports/trialbalance/TrailBalanceWithOutZero',$data);
        }
    }
}