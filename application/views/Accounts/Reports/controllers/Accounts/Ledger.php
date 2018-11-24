<?php

class Ledger extends MY_Controller
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

    public function index(){
        $this->load->view('ladger/ledger');
    }

    public function GetData()
    {
        $data['accountname'] = $this->ChartModel->get_account_name('',2);
        $data['parents'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/ladger/GetLedger', $data);
        $this->load->view('Accounts/footer');
    }

    public function getAccountName($comp)
    {
        $data = $this->ChartModel->get_account_name_2($comp);
        echo json_encode($data);
    }

    public function getAccountName_Cons()
    {
        $data = $this->ChartModel->get_account_name_2();
        echo json_encode($data);
    }

    public function getlevels($id)
    {
        $levels = $this->CompanyModel->getSubHeadAccess($id);
        echo json_encode($levels);
    }


    public function GetValue($consolidated = '')
    {


//        echo $consolidated;

        $data['print'] = $_POST['print'];

        if ($consolidated == 1){
            foreach ($this->CompanyModel->getSubHeadCon($this->input->post('level')) as $levels){
                $Level_id[] = $levels->Id;
                $level_name = $levels->LevelName;
            }
            $data['consolidated'] = 1;
        }else{
            foreach ($this->CompanyModel->get_company_name($this->input->post('level')) as $levels){
                $Level_id[] = $levels->id;
                $level_name = $levels->LevelName;
        }
        }

        if (isset($_POST['account'])){
            $account_id = $_POST['account'];
            if($account_id[0] == 'all'){
                $account_id = $_POST['all_acc'];
            }
        }


        foreach ($account_id as $A_Key => $account) {
            foreach ($Level_id as $L_key => $levels){
                $links = $this->LinkModel->getAccLinkid($account,$levels);
                (isset($links->id))?$chart_ids[$A_Key][$L_key] = $links->id:$chart_ids[$A_Key][$L_key] = 0;
            }
        }
        $to = $_POST['to'];
        $from = $_POST['from'];

        $ledgerOf = $_POST['ledgerof'];  // aal or p will post here

        $voucher_type = $_POST['voucher']; //  type of voucher will post here

        if($ledgerOf == 'all'){ //
            $data['all'] = '';
        }



        if($voucher_type != 'alll'){
            $data['VoucherType'] = $voucher_type;
        }else{
            $data['VoucherType'] = '';
        }
        $op_balance_new = array();
       // print_r($chart_ids);
        foreach ($chart_ids as $chart_Key => $chart_id) {
            $data['transactions'][$chart_Key] = $this->BookModel->get_transaction_ledger($chart_id,$to,$from,$ledgerOf,$voucher_type);

//            echo '<pre>';
////            echo  $this->db->last_query();
//            print_r($data['transactions'][$chart_Key]);
//            echo '</pre>';

            $Op_balances_cal[$chart_Key] = $this->BookModel->get_sum_debit_credit_tillDate($chart_id,$to,$ledgerOf,$voucher_type);

//            echo '<pre>';
//            echo $this->db->last_query();
//            echo '</pre>';

            $Op_balances[$chart_Key] = $this->LinkModel->Get_OpeningBalance($chart_id);

//
//            echo '<pre>';
//            echo $this->db->last_query();
//            echo '</pre>';

//            echo "<pre>";
//            print_r($data['transactions'][$chart_Key]);
//            echo "</pre>";


            foreach ($Op_balances_cal[$chart_Key] as $key => $op_balance){
                if ($Op_balances[$chart_Key][$key] == array() && ($op_balance->tdebit == '' && $op_balance->tcredit == '')){
                    $op_balance_new[] = '0.00';
                }else{
                    $op_balance_new = ($Op_balances[$chart_Key][$key]->OpeningBalance + $op_balance->tdebit) - $op_balance->tcredit;
                }
            }
            $data['openingbalance'][$chart_Key] = $op_balance_new;
        }

        $data['to'] = $this->CalenderModel->getHijriDate($to);
        $data['from'] = $this->CalenderModel->getHijriDate($from);

        $data['LevelName'] = $level_name;
        $data['AccName'] = $this->ChartModel->get_account_name_for_ledger($account_id);

//        echo "<pre>";
//        echo $this->db->last_query();
//        echo "</pre>";

        foreach ($data['AccName'] as $acc) {
            $head[] = $this->ChartModel->get_head($acc[0]->Head);

        }
        $data['Head'] = $head;
//        echo "<pre>";
//        echo $this->db->last_query();
//        print_r($data);
//        exit();
        $this->load->view('Accounts/Reports/ladger/ledger',$data);
    }



    public function Consolidated_Ledger()
    {
        $data['accountname'] = $this->ChartModel->get_account_name('',2);
        $data['parents'] = $this->CompanyModel->getHead();
        $data['levelname'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/ladger/GetConsolidatedLedger',$data);
        $this->load->view('Accounts/footer');
    }

//    public function Get_Consolidated_Ledger()
//    {
//        $level_ids = array();
//        $Accounts = array();
//
//        foreach ($this->input->post('account') as $item) {
//            $Accounts[] = $item;
//        }
//        foreach ($this->CompanyModel->getSubHeadCon($this->input->post('level')) as $levels){
//            $level_ids[] = $levels->Id;
//            $level_name = $levels->LevelName;
//        }
//
//        foreach ($Accounts as $A_Key => $account) {
//            foreach ($level_ids as $L_key => $levels){
//                $links = $this->LinkModel->getAccLinkid($account,$levels);
//                (isset($links->id))?$chart_ids[$A_Key][$L_key] = $links->id:$chart_ids[$A_Key][$L_key] = 0;
//            }
//        }
//
//        $to = $_POST['to'];
//        $from = $_POST['from'];
//        $ledgerOf = $_POST['ledgerof'];
//        if($ledgerOf == 'all'){
//            $data['all'] = '';
//        }
//
//
////        $Op_balances_cal = $this->BookModel->get_sum_debit_credit_tillDate($Accounts,$level_ids,$to,$ledgerOf);
////        $Op_balances = $this->LinkModel->Get_OpeningBalance($Accounts,$level_ids);
////
////        foreach ($Op_balances_cal as $key => $op_balance){
////            if ($Op_balances[$key] == array() && ($op_balance[0]->tdebit == '' && $op_balance[0]->tcredit == '')){
////                $op_balance_new[] = '0.00';
////            }else{
////                $op_balance_new[] = ($Op_balances[$key][0]->OpeningBalance + $op_balance[0]->tdebit) - $op_balance[0]->tcredit;
////            }
////        }
////        $data['openingbalance'] = $op_balance_new;
//
//        $op_balance_new = array();
//
//        foreach ($chart_ids as $chart_Key => $chart_id) {
//            $data['transactions'][$chart_Key] = $this->BookModel->get_transaction_ledger_Con($chart_id,$to,$from,$ledgerOf);
//
//            $Op_balances_cal[$chart_Key] = $this->BookModel->get_sum_debit_credit_tillDate($chart_id,$to,$ledgerOf);
//            $Op_balances[$chart_Key] = $this->LinkModel->Get_OpeningBalance($chart_id);
//
//            foreach ($Op_balances_cal[$chart_Key] as $key => $op_balance){
//                if ($Op_balances[$chart_Key][$key] == array() && ($op_balance->tdebit == '' && $op_balance->tcredit == '')){
//                    $op_balance_new[] = '0.00';
//                }else{
//                    $op_balance_new = ($Op_balances[$chart_Key][$key]->OpeningBalance + $op_balance->tdebit) - $op_balance->tcredit;
//                }
//            }
//            $data['openingbalance'][$chart_Key] = $op_balance_new;
//
//        }
//
//        $data['to'] = $this->CalenderModel->getHijriDate($to);
//        $data['from'] = $this->CalenderModel->getHijriDate($from);
//
//        $data['LevelName'] = $level_name;
//        $data['AccName'] = $this->ChartModel->get_account_name_for_ledger($Accounts);
//
//        foreach ($data['AccName'] as $acc) {
//            $head[] = $this->ChartModel->get_head($acc[0]->Head);
//        }
//        $data['Head'] = $head;
//
//        $this->load->view('Accounts/Reports/ladger/consolidated_ledger',$data);
//    }
}