<?php

class MoveAccount extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('ChartModel');
        $this->load->model('BookModel');
    }

    public function index()
    {
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/moveAccount');
        $this->load->view('Accounts/footer');
    }

    public function getAccountName($code,$cmp_id)
    {
        $data = $this->ChartModel->get_move_account_name($code,$cmp_id);
        if(!$data){
            $accName = false;
        }else{
            $accName = array('name' => $data[0]->AccountName );
        }
        echo json_encode($accName);
    }

    public function getTransactionForMoveAcc($level,$acc_code,$t_type,$to,$from)
    {
        $acc_id = $this->ChartModel->Get_Aid($acc_code);
        $trans = $this->BookModel->get_all_for_move_acc($level,$acc_id->id,$t_type,$to,$from);
        echo json_encode($trans);
    }

    public function checkBalances($level)
    {
        $data['balances'] = $this->ChartModel->getBalances($level);
        $data['transactions'] = $this->BookModel->get_all_for_move_acc($level);
        $data['AccountCode1'] = $this->input->post('AccountCode1');
        $data['AccountCode2'] = $this->input->post('AccountCode2');
        $name1 = $this->ChartModel->get_a_data($this->input->post('AccountCode1'));
        $name2 = $this->ChartModel->get_a_data($this->input->post('AccountCode2'));
        $data['AccountName1'] = $name1[0]->AccountName;
        $data['AccountName2'] = $name2[0]->AccountName;
        $data['ttype'] = $this->input->post('ttype');
//        echo '<pre>';
//        print_r($this->input->post());
//        exit();
//        @$data['OACBBM']->ClosingBalance = $data['OAB']->CurrentBalance;
//        @$data['DACBBM']->ClosingBalance = $data['DAB']->CurrentBalance;
//        @$data['OAOBAM']->ClosingBalance = $data['OAB']->CurrentBalance + $data['OAPDC']->Credit - $data['OAPDC']->Debit;
//        @$data['DAOBAM']->ClosingBalance = $data['DAB']->CurrentBalance + $data['OAPDC']->Debit - $data['OAPDC']->Credit;
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/moveAccTable',$data);
        $this->load->view('Accounts/footer');
        //echo  $this->load->view('Accounts/moveAccTable', $data, true);
    }

    public function updateMoveAccount()
    {
        $is_update = $this->ChartModel->update_move_account();
        if($is_update){
            $resp = array('success' => "ok" );
        }else{
            $resp = array('error' => "ok" );
        }
        echo json_encode($resp);
    }


}