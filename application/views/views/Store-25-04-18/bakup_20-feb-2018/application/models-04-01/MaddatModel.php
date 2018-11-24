<?php

class MaddatModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BookModel');
        $this->load->model('LinkModel');
        date_default_timezone_set('Asia/Karachi');
    }

    public function Save_Data()
    {
        $Mad_name = $this->input->post('Mad_name');
        $accounts = $this->input->post('accounts');
        $Report_Id = $this->input->post('reportnum');
        $Sr_number = $this->input->post('Sr_number');

        foreach ($Mad_name as $key => $item) {
            $data = array(
                'Mad_name' => $item,
                'Report_Id' => $Report_Id,
                'Sr_number' => $Sr_number[$key],
                'CreatedBy' => $_SESSION['user'][0]->id,
                'CreatedOn' => date('Y-m-d H:i:s')
            );
            $this->db->insert('maddat',$data);
            if ($this->db->affected_rows() > 0) {
                $mad_id = $this->db->insert_id();
                foreach ($accounts[$key] as $value){
                    $mad_details = array(
                        'Mad_id' => $mad_id,
                        'Chart_Of_Account_Id' => $value,
                        'CreatedBy' => $_SESSION['user'][0]->id,
                        'CreatedOn' => date('Y-m-d H:i:s')
                    );
                    $this->db->insert('maddat_details',$mad_details);
                    if ($this->db->affected_rows() > 0) {
                        $result = true;
                    }else{
                        $result = false;
                    }
                }
            }else{
                $result = false;
            }
        }
        return $result;
    }

    public function Update()
    {
        //echo "<pre>";
        //print_r($_POST);
        $result = '';
        $array = array();
        $Mad_name = $this->input->post('Mad_name');
        $Mad_id = $this->input->post('Mad_id');
        $accounts = $this->input->post('accounts');
        $Report_Id = $this->input->post('reportnum');
        $level_id = $this->input->post('level_id');
        $Sr_number = $this->input->post('Sr_number');
        //exit();

        $this->db->select('id');
        $this->db->where('Report_Id', $Report_Id);
        $maddats = $this->db->get('maddat')->result();

        foreach ($maddats as $m_Key => $maddad) {
            $madd_id = $maddad->id;
            $query =  $this->db->query('SELECT maddat_details.id FROM `maddat_details` JOIN chart_of_account ON maddat_details.Chart_Of_Account_Id = chart_of_account.id WHERE chart_of_account.levelid ='.$level_id.' AND Mad_id = '.$madd_id);
            if($query->num_rows() > 0){
                $maddad_accounts = $query->result();
                foreach ($maddad_accounts as $key => $maddad_account) {
                    $array[] = $maddad_account->id;
                }
                $this->db->where_in('id', $array);
                $this->db->delete('maddat_details');
            }
        }

        foreach ($Mad_name as $key => $item) {
            $IsPresent = $this->checkMad($Mad_id[$key]);
            if(!$IsPresent){
                $data = array(
                    'Mad_name' => $item,
                    'Report_Id' => $Report_Id,
                    'Sr_number' => $Sr_number[$key],
                    'CreatedBy' => $_SESSION['user'][0]->id,
                    'CreatedOn' => date('Y-m-d H:i:s')
                );
                $this->db->insert('maddat',$data);
                if ($this->db->affected_rows() > 0) {
                    $mad_id = $this->db->insert_id();
                    if(isset($accounts[$key])){
                        foreach ($accounts[$key] as $value){
                            $mad_details = array(
                                'Mad_id' => $mad_id,
                                'Chart_Of_Account_Id' => $value,
                                'CreatedBy' => $_SESSION['user'][0]->id,
                                'CreatedOn' => date('Y-m-d H:i:s')
                            );
                            $this->db->insert('maddat_details',$mad_details);
                            if ($this->db->affected_rows() > 0) {
                                $result = true;
                            }else{
                                $result = false;
                            }
                        }
                    }
                }else{
                    $result = false;
                }
            }else{
                $this->db->set('Mad_name',$item);
                $this->db->set('Sr_number',$Sr_number[$key]);
                $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
                $this->db->where('Id', $Mad_id[$key]);
                $this->db->update('maddat');

                $mad_id = $Mad_id[$key];
                if(isset($accounts[$key])){
                    foreach ($accounts[$key] as $value){
                        $mad_details = array(
                            'Mad_id' => $mad_id,
                            'Chart_Of_Account_Id' => $value,
                            'CreatedBy' => $_SESSION['user'][0]->id,
                            'CreatedOn' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('maddat_details',$mad_details);
                    }
                    if ($this->db->affected_rows() > 0) {
                        $result = true;
                    }else{
                        $result = false;
                    }
                }
            }
        }
        return $result;
    }

    public function delete_maddad($madd_id,$report_id,$levelid)
    {
        $query =  $this->db->query('SELECT maddat_details.id FROM `maddat_details` JOIN chart_of_account ON maddat_details.Chart_Of_Account_Id = chart_of_account.id WHERE chart_of_account.levelid ='.$levelid.' AND Mad_id = '.$madd_id);
        if($query->num_rows() > 0){
            $maddad_accounts = $query->result();
            foreach ($maddad_accounts as $key => $maddad_account) {
                $array[] = $maddad_account->id;
            }
            $this->db->where_in('id', $array);
            $this->db->delete('maddat_details');
            if ($this->db->affected_rows() > 0) {
                $this->db->where('Id', $madd_id);
                $this->db->delete('maddat');
                return true;
            }else{
                return false;
            }
        }else{
            $this->db->where('Id', $madd_id);
            $this->db->delete('maddat');
            return true;
        }

    }

    public function checkMad($mad_id)
    {
        $this->db->where('Id', $mad_id);
        $result = $this->db->get('maddat');
        if($result->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Maddat_Name($report_Id)
    {
        $this->db->select('Mad_name,Id,Sr_number');
        $this->db->from('maddat');
        $this->db->where('Report_Id', $report_Id);
        $this->db->group_by('Mad_name');
        $this->db->order_by('Sr_number','ASC');
        return $this->db->get()->result();
    }

    public function GetMaddat_AccountID($maddat_id,$config='',$level='')
    {
        if ($config != ''){
            $this->db->select('maddat_details.`Chart_Of_Account_Id` ,`b`.`AccountName` as `ParentName`, a.AccountName ,a.AccountCode');
            $this->db->from('maddat_details');
            $this->db->join('chart_of_account', 'chart_of_account.id = maddat_details.Chart_Of_Account_Id');
            $this->db->join('account_title a','chart_of_account.AccountId = a.id');
            $this->db->join('account_title b ', 'b.AccountCode = a.ParentCode');
            $this->db->where('Mad_id', $maddat_id);
            if ($level != '') {
                $this->db->where('chart_of_account.LevelId', $level);
            }
            return $this->db->get()->result();
        }else{
            $this->db->select('chart_of_account.LevelId,chart_of_account.AccountId,chart_of_account.id');
            $this->db->join('chart_of_account','maddat_details.Chart_Of_Account_Id = chart_of_account.id');
            $this->db->where('maddat_details.Mad_id',$maddat_id);
            $this->db->where('chart_of_account.LevelId',$level);
            return $this->db->get('maddat_details')->result();
        }
    }

    public function GetMaddatsAcc($mad_id,$rep_id,$level)
    {
        $this->db->select('*');
        $this->db->from('maddat_details');
        $this->db->join('maddat', 'maddat.Id = maddat_details.Mad_id');
        $this->db->join('chart_of_account','maddat_details.Chart_Of_Account_Id = chart_of_account.id');
        $this->db->where('maddat_details.Mad_id', $mad_id);
        $this->db->where('maddat.report_id', $rep_id);
        $this->db->where_not_in('chart_of_account.LevelId',$level);
        $data = $this->db->get();
        if($data->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_AccountCode($id)
    {
        $this->db->select('id,AccountCode,ParentCode');
        $this->db->where('id', $id);
        return $this->db->get('account_title')->result();
    }

    public function Get_Maddat($to ='',$from,$levelids,$report_Id,$IsReversed = '')
    {
        $maddat_accounts = array();
        $trans = array();
        $accountIds = array();
        $maddats = $this->Get_Maddat_Name($report_Id);
        foreach ($levelids as $l_key => $level_id) {
            foreach ($maddats as $key => $maddat) {
                $acc[$l_key][$key] = $this->GetMaddat_AccountID($maddat->Id,null,$level_id);
                if ($acc[$l_key][$key] == array()){
//                    $maddat_accounts[$l_key][$key][0] = (object)array('AccountId' => 0);
                    $maddat_accounts[$l_key][$key][0] = (object)array('id' => 0);
                }else{
                    $maddat_accounts[$l_key][$key] = $acc[$l_key][$key];
                }
            }
        }
        foreach ($maddat_accounts as $L_key => $maddat_account) {
            foreach ($maddat_account as $M_key => $items) {
                foreach ($items as $A_ley => $item) {
//                    $accountIds[$L_key][$M_key][$A_ley] = $item->AccountId;
                    $accountIds[$L_key][$M_key][$A_ley] = $item->id;
                }
            }
        }

        foreach ($maddat_accounts as $L_key => $maddat_account) {
            foreach ($maddat_account as $M_key => $accounts) {
                foreach ($accounts as $acc_key => $account) {
                    if ($account->id == 0){
                        $trans[$L_key][$M_key][0] = (object)array('Credit' => 0.00, 'Debit' => 0.00);
                    }else{
                        $this->db->select('IFNULL(SUM(transactions.Debit),0) as Debit, IFNULL(SUM(transactions.Credit),0)  as Credit');
                        $this->db->where_in('transactions.LinkID', $accountIds[$L_key][$M_key]);
                        ($IsReversed == 1)?$this->db->where('transactions.IsReverse','1'):$this->db->where('transactions.IsReverse','0');

                        if ($to == '') {
                            $this->db->where('transactions.VoucherDateG', $from);
                        }else{
                            $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                        }

//                        if ($IsReversed == 1){
//                            $this->db->where('IsReverse',1);
//                        }else{
//                            $this->db->where('IsReverse',0);
//                        }
                        $this->db->from('transactions');
                        $query1 = $this->db->get_compiled_select();

                        $this->db->select('IFNULL(SUM(income.Debit),0) as Debit, IFNULL(SUM(income.Credit),0)  as Credit');
//                        $this->db->where_in('income.AccountID', $accountIds[$L_key][$M_key]);
//                        $this->db->where('income.LevelID', $account->LevelId);
                        $this->db->where_in('income.LinkID', $accountIds[$L_key][$M_key]);
                        ($IsReversed == 1)?$this->db->where('income.IsReverse','1'):$this->db->where('income.IsReverse','0');
                        if ($to == '') {
                            $this->db->where('income.VoucherDateG', $from);
                        }else{
                            $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                        }
                        $this->db->from('income');
                        $query2 = $this->db->get_compiled_select();

                        $trans[$L_key][$M_key] = $this->db->query('Select SUM(Debit) as Debit,SUM(Credit) as Credit From('. $query1 . ' UNION ' . $query2.')'.'as t')->result();
                    }
                }
            }
        }
        return $trans;
    }

    public function getAccountTrans()
    {
        $trans = array();
        $ChartOfaccountIds = array();
        if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        }
        $to = $_POST['to'];
        $from = $_POST['from'];
        $trans_of = $_POST['tran_of'];
        $maddat_accounts = $_POST['accounts'][0];
        foreach ($maddat_accounts as $key => $maddat_account) {
            $ChartOfaccountIds[$key] = $this->getAccountID($maddat_account);
        }
        foreach ($ChartOfaccountIds as $key => $ChartOfaccountId) {
            $accountIds[$key][] = $ChartOfaccountId->AccountId;
            $this->db->select('a.AccountName,IFNULL(Debit,0) as Debit, IFNULL(Credit,0)  as Credit,t.Remarks');
            $this->db->from('transactions as t');
            $this->db->join('account_title as a', 'a.id = t.AccountID');
            $this->db->where_in('AccountID', $ChartOfaccountId->AccountId);
            $this->db->where_in('LevelID', $Access_level);
            if ($trans_of == 'p') {
                $this->db->where('t.Permanent_VoucherDateG IS NOT NULL');
                $this->db->where("t.Permanent_VoucherDateG < ",$to);
            } else {
                $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            $trans[$key] = $this->db->get()->result();
        }
        return $trans;
    }

    public function Get_Maddad_Acc_transactions($level_id,$to,$from,$report_id,$trans_of)
    {
        $NoAccount = 0;
        $closingBalance = array();
        $maddats = $this->Get_Maddat_Name($report_id);
        if($maddats != array()){
            foreach ($maddats as $key => $maddat) {
                $maddat_accounts[] = $this->GetMaddat_AccountID($maddat->Id,1,$level_id);
            }
            foreach ($maddat_accounts as $key => $maddat_account) {
                if($maddat_account != array()){
                    foreach ($maddat_account as $item) {
                        $ChartOfaccountIds[$key][] = $item->Chart_Of_Account_Id;
                    }
                }else{
                    $ChartOfaccountIds[$key][] = 0;
                    $NoAccount = 1;
                }
            }
            //echo "<pre>";
            // if($NoAccount == 0){
            foreach ($ChartOfaccountIds as $key => $ChartOfaccountId) {
                foreach ($ChartOfaccountId as $item) {
                    $accountIds[$key] = $item;
                }
            }
            //print_r($accountIds);
            foreach ($maddat_accounts as $M_key => $maddat_account) {
                $OpeningBal[$M_key] = $this->BookModel->getOpeningBal($level_id,$accountIds[$M_key]);
                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('transactions');
                $this->db->where('transactions.LinkID', $accountIds[$M_key]);
                //$this->db->where('transactions.LevelID', $level_id);
                if ($trans_of == 'p') {
                    $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                    $this->db->where('transactions.Permanent_VoucherDateG < ', $to);
                    //$this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                } else {
                    //$this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    $this->db->where('transactions.VoucherDateG < ', $to);
                }
                $transactions = $this->db->get_compiled_select();

                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('income');
                $this->db->where('income.LinkID', $accountIds[$M_key]);
                //$this->db->where('income.LevelID', $level_id);
                if ($trans_of == 'p') {
                    $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                    $this->db->where('income.Permanent_VoucherDateG < ', $to);
                    //$this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                } else {
                    $this->db->where('income.VoucherDateG < ', $to);
                    //$this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                }
                $income = $this->db->get_compiled_select();

                $query = $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t');

                $trans[$M_key] = $query->result();
                $closingBalance[$M_key] = $OpeningBal[$M_key]->OpeningBalance + $trans[$M_key][0]->Debit - $trans[$M_key][0]->Credit;
            }
            // }
        }
        return $closingBalance;
    }

    public function getAccountID($ch_id)
    {
        $this->db->select('AccountId');
        $this->db->where('id', $ch_id);
        return $this->db->get('chart_of_account')->row();
    }

    public function Get_weekly_1($to,$from,$levelid,$report_Id,$previous,$trans_of,$IsReversed='')
    {
        // print_r($previous);
        // var_dump($previous);
        // exit();
        $maddat_accounts = array();
        $Income_acc = array();
        $Expense_acc = array();
        $Capital_acc = array();
        $accountIds = array();
        $transdata = array();
        $ChartOfaccountIds = array();

        $maddats = $this->Get_Maddat_Name($report_Id);
        foreach ($maddats as $key => $maddat) {
            $maddat_accounts[] = $this->GetMaddat_AccountID($maddat->Id,1);
        }
        foreach ($maddat_accounts as $K_key => $maddat_account) {
            foreach ($maddat_account as $item) {
                $ChartOfaccountIds[$K_key][] = $this->getAccountID($item->Chart_Of_Account_Id);
            }
        }

        // echo "<pre>";
        // print_r($ChartOfaccountIds);
        // exit();
        // foreach ($ChartOfaccountIds as $C_key => $ChartOfaccountId) {
        //      foreach ($ChartOfaccountId as $item) {
        //          $accountIds[$C_key][] = $this->Get_AccountCode($item->AccountId);
        //      }
        //  }
        foreach ($ChartOfaccountIds as $M_key => $maddat_value) {
            foreach ($maddat_value as $A_key => $value) {
                $accountIds[$M_key][] = $value->AccountId;

            }
        }

        foreach ($maddat_accounts as $N_key => $maddat_account) {
            foreach ($maddat_account as $Z_key => $acc_code) {
                $OpeningBal[$N_key][$Z_key] = $acc_code->Chart_Of_Account_Id;
            }
        }

        foreach ($maddat_accounts as $N_key => $maddat_account) {
            foreach ($maddat_account as $Z_key => $acc_code) {
                if ($previous == 1) {
                    $transdata['OpeningBal'][$N_key] = $this->BookModel->getOpeningBal($levelid,$OpeningBal[$N_key]);
                }

                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('transactions');
                $this->db->where_in('transactions.LevelID', $levelid);
                // if ($accountIds[$N_key][$Z_key][0]->AccountCode[0] == 4) {
                //     $this->db->where_in('AccountID', $Income_acc[$N_key]);              
                // }elseif ($accountIds[$N_key][$Z_key][0]->AccountCode[0] == 5) {              
                $this->db->where_in('transactions.AccountID', $accountIds[$N_key]);
                //} 
                ($IsReversed == 1)?$this->db->where('transactions.IsReverse','1'):$this->db->where('transactions.IsReverse','0');
                if ($previous == 1) {
                    $this->db->where('transactions.VoucherDateG <', $to);
                }elseif ($previous == 2) {
                    $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                }
                if ($trans_of == 'p') {
                    $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                }
                $transactions = $this->db->get_compiled_select();

                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('income');
                $this->db->where_in('income.LevelID', $levelid);
                // if ($accountIds[$N_key][$Z_key][0]->AccountCode[0] == 4) {
                //     $this->db->where_in('AccountID', $Income_acc[$N_key]);              
                // }elseif ($accountIds[$N_key][$Z_key][0]->AccountCode[0] == 5) {              
                $this->db->where_in('income.AccountID', $accountIds[$N_key]);
                //} 
                ($IsReversed == 1)?$this->db->where('income.IsReverse',1):$this->db->where('income.IsReverse',0);
                if ($previous == 1) {
                    $this->db->where('income.VoucherDateG <', $to);
                }elseif ($previous == 2) {
                    $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                }
                if ($trans_of == 'p') {
                    $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                }
                $income = $this->db->get_compiled_select();
                $query = $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t');
                $transdata[$N_key] = $query->result();
            }
        }
        // echo "<pre>";
        // print_r($transdata);
        // exit();
        return $transdata;
    }

    public function GetTrans($to,$from,$levelid,$report_Id,$previous)
    {
        $maddat_accounts = array();
        $AccountIds = array();
        $Expense_acc = array();
        $trans = array();
        $maddats = $this->Get_Maddat_Name($report_Id);
        foreach ($maddats as $key => $maddat) {
            $maddat_accounts[] = $this->GetMaddat_AccountID($maddat->Id,1);
        }
        foreach ($maddat_accounts as $key => $maddat_account) {
            if($maddat_account != array()){
                foreach ($maddat_account as $item) {
                    $ChartOfaccountIds[$key][] = $this->getAccountID($item->Chart_Of_Account_Id);
                }
            }else{
                $NoAccount = 1;
            }
        }
        foreach ($ChartOfaccountIds as $key => $ChartOfaccountId) {
            foreach ($ChartOfaccountId as $item) {
                $accountIds[$key][] = $item->AccountId;
            }
        }

        foreach ($maddat_accounts as $N_key => $maddat_account) {
            if(isset($accountIds[$N_key])){
                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('transactions');
                $this->db->where_in('AccountID', $accountIds[$N_key]);
                $this->db->where_in('LevelID', $levelid);

                if ($previous == 1) {
                    $this->db->where('VoucherDateG <', $to);
                }else{
                    $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                }

                $transactions = $this->db->get_compiled_select();

                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('income');
                $this->db->where_in('AccountID', $accountIds[$N_key]);
                $this->db->where_in('LevelID', $levelid);
                if ($previous == 1) {
                    $this->db->where('VoucherDateG <', $to);
                }else{
                    $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                }
                $income = $this->db->get_compiled_select();

                $query = $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t');

                $trans[$N_key] = $query->result();
            }
        }
        return $trans;
    }

    public function GetDepartmentReportTrans($to,$from,$levelid,$report_Id,$previous,$tran_of,$IsReversed)
    {
        $maddat_accounts = array();
        $AccountIds = array();
        $Expense_acc = array();
        $trans = array();
        $maddats = $this->Get_Maddat_Name($report_Id);
        foreach ($maddats as $key => $maddat) {
            $maddat_accounts[] = $this->GetMaddat_AccountID($maddat->Id,1,$levelid);
        }
        //return $maddat_accounts;
        foreach ($maddat_accounts as $key => $maddat_account) {
            if(!empty($maddat_account)){
                foreach ($maddat_account as $item) {
                    $ChartOfaccountIds[$key][] = $this->getAccountID($item->Chart_Of_Account_Id);
                }
            }else{
                $ChartOfaccountIds[$key][] = 0;
            }
        }

        foreach ($ChartOfaccountIds as $key => $ChartOfaccountId) {
            foreach ($ChartOfaccountId as $item) {
                if(isset($item->AccountId)){
                    $accountIds[$key][] = $item->AccountId;
                }
            }
        }
        //return $accountIds;
        foreach ($maddat_accounts as $N_key => $maddat_account) {
            if(!empty($accountIds[$N_key])){
                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('transactions');
                $this->db->where_in('AccountID', $accountIds[$N_key]);
                $this->db->where_in('LevelID', $levelid);
                if($tran_of != ''){
                    if ($tran_of == 'p') {
                        $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                        $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    } else {
                        $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    }
                }else{
                    if ($previous == 1) {
                        $this->db->where('VoucherDateG <', $to);
                    }else{
                        $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    }
                }
                ($IsReversed == 1)?$this->db->where('transactions.IsReverse','1'):$this->db->where('transactions.IsReverse','0');

                $transactions = $this->db->get_compiled_select();

                $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
                $this->db->from('income');
                $this->db->where_in('AccountID', $accountIds[$N_key]);
                $this->db->where_in('LevelID', $levelid);
                if($tran_of != ''){
                    if ($tran_of == 'p') {
                        $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                        $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    } else {
                        $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    }
                }else{
                    if ($previous == 1) {
                        $this->db->where('VoucherDateG <', $to);
                    }else{
                        $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                    }
                }
                ($IsReversed == 1)?$this->db->where('income.IsReverse','1'):$this->db->where('income.IsReverse','0');
                $income = $this->db->get_compiled_select();

                $query = $this->db->query('Select IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0) as Credit From(' . $transactions . ' UNION ' . $income . ') as t');

                $trans[$N_key] = $query->result();
            }else{
                $trans[$N_key] = array((object)array('Debit' => 0,'Credit'=>0));
            }
        }
        return $trans;
    }

    public function Get_weekly_1_Bank($to,$from,$levelid,$report_Id_bank,$previous,$trans_of,$IsReversed='')
    {
        $maddat_accounts = array();
        $Capital_acc = array();
        $transdata = array();
        $Income_acc = array();
        $Expense_acc = array();
        $accountIds = array();
        $ChartOfaccountIds = array();
        $maddats = $this->Get_Maddat_Name($report_Id_bank);

        foreach ($maddats as $key => $maddat) {
            $maddat_accounts[] = $this->GetMaddat_AccountID($maddat->Id,1,'');
        }

        foreach ($maddat_accounts as $F_key => $maddat_account) {
            foreach ($maddat_account as $item) {
                $ChartOfaccountIds[$F_key][] = $this->getAccountID($item->Chart_Of_Account_Id);
            }
        }
        foreach ($ChartOfaccountIds as $E_key => $ChartOfaccountId) {
            foreach ($ChartOfaccountId as $item) {
                $accountIds[$E_key][] = $this->Get_AccountCode($item->AccountId);
            }
        }

        foreach ($accountIds as $M_key => $maddat_value) {
            foreach ($maddat_value as $A_key => $value) {
                if ($value[0]->AccountCode[0].$value[0]->AccountCode[1].$value[0]->AccountCode[2].$value[0]->AccountCode[3].$value[0]->AccountCode[4] == 10202) {
                    $Capital_acc[$M_key][] = $value[0]->id;
                }else{
                    $other[$M_key][] = $value[0]->id;
                }
            }
        }
        // echo "<pre>";
        // print_r($other);
        // exit();

        foreach ($maddat_accounts as $N_key => $maddat_account) {
            foreach ($maddat_account as $Z_key => $acc_code) {
                if ($accountIds[$N_key][$Z_key][0]->AccountCode[0].$accountIds[$N_key][$Z_key][0]->AccountCode[1].$accountIds[$N_key][$Z_key][0]->AccountCode[2].$accountIds[$N_key][$Z_key][0]->AccountCode[3].$accountIds[$N_key][$Z_key][0]->AccountCode[4] == 10202) {
                    $OpeningBal['Captial'][$N_key][$Z_key] = $acc_code->Chart_Of_Account_Id;
                }else{
                    $OpeningBal['Other'][$N_key][$Z_key] = $acc_code->Chart_Of_Account_Id;
                }
            }
        }
        // echo "<pre>";
        // print_r($OpeningBal);
        // exit();
        foreach ($maddat_accounts as $N_key => $maddat_account) {
            foreach ($maddat_account as $A_key => $acc_code_id) {
                if($accountIds[$N_key][$A_key][0]->AccountCode[0].$accountIds[$N_key][$A_key][0]->AccountCode[1].$accountIds[$N_key][$A_key][0]->AccountCode[2].$accountIds[$N_key][$A_key][0]->AccountCode[3].$accountIds[$N_key][$A_key][0]->AccountCode[4] == 10202){
                    if ($previous == 1) {
                        $transdata['OpeningBal']['Captial'][$N_key] = $this->BookModel->getOpeningBal($levelid,$OpeningBal['Captial'][$N_key]);
                    }
                }else{
                    if ($previous == 1) {
                        $transdata['OpeningBal']['Other'][$N_key] = $this->BookModel->getOpeningBal($levelid,$OpeningBal['Other'][$N_key]);
                    }
                }
                if($accountIds[$N_key][$A_key][0]->AccountCode[0].$accountIds[$N_key][$A_key][0]->AccountCode[1].$accountIds[$N_key][$A_key][0]->AccountCode[2].$accountIds[$N_key][$A_key][0]->AccountCode[3].$accountIds[$N_key][$A_key][0]->AccountCode[4] == 10202){
                    $transdata['Captial'][$N_key]=$this->GetDataForCashBank($levelid,$accountIds[$N_key][$A_key][0]->AccountCode,$Capital_acc[$N_key],$previous,$to,$from,$trans_of,1,$IsReversed);
                }else{
                    $transdata['Other'][$N_key]= $this->GetDataForCashBank($levelid,$accountIds[$N_key][$A_key][0]->AccountCode,$other[$N_key],$previous,$to,$from,$trans_of,2,$IsReversed);
                }
            }
        }
        // echo "<pre>";
        // print_r($transdata);
        // exit();
        return $transdata;
    }

    public function GetDataForCashBank($levelid,$account_code,$accountIds,$previous,$to,$from,$trans_of,$is_Capital,$IsReversed = '' )
    {

        $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
        $this->db->from('transactions');
        $this->db->where_in('LevelID', $levelid);
        if ($is_Capital == 1) {
            if($account_code[0].$account_code[1].$account_code[2].$account_code[3].$account_code[4] == 10202) {
                $this->db->where_in('AccountID', $accountIds);
            }
        }else{
            $this->db->where_in('AccountID', $accountIds);
        }

        ($IsReversed == 1)?$this->db->where('transactions.IsReverse','1'):$this->db->where('transactions.IsReverse','0');
        if ($previous == 1) {
            $this->db->where('VoucherDateG <', $to);
        }elseif ($previous == 2) {
            $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
        }
        if ($trans_of == 'p') {
            $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
        }
        $transactions = $this->db->get_compiled_select();

        $this->db->select('IFNULL(SUM(Debit),0) as Debit, IFNULL(SUM(Credit),0)  as Credit');
        $this->db->from('income');
        $this->db->where_in('LevelID', $levelid);
        if ($is_Capital == 1) {
            if($account_code[0].$account_code[1].$account_code[2].$account_code[3].$account_code[4] === 10202) {
                $this->db->where_in('AccountID', $accountIds);
            }
        }else{
            $this->db->where_in('AccountID', $accountIds);
        }
        ($IsReversed == 1)?$this->db->where('income.IsReverse','1'):$this->db->where('income.IsReverse','0');
        if ($previous == 1) {
            $this->db->where('VoucherDateG <', $to);
        }elseif ($previous == 2) {
            $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
        }
        if ($trans_of == 'p') {
            $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
        }
        $income = $this->db->get_compiled_select();

        $query = $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t');
        return $transdata = $query->result();
    }

    public function set_transactions($level,$report)
    {

    }

    public function getExpenseMaddadAcc($report_Id,$level='')
    {
        $Expense_acc = null;
        $maddat_accounts = array();
        $maddats = $this->Get_Maddat_Name($report_Id);
        foreach ($maddats as $key => $maddat) {
            $maddat_accounts[] = $this->GetMaddat_AccountID($maddat->Id,1);
        }
        foreach ($maddat_accounts as $M_key => $maddat_value) {
            foreach ($maddat_value as $A_key => $value) {
                $this->db->select('Debit,Credit');
                $this->db->from('transactions');
                $this->db->where('transactions.LinkID', $value->Chart_Of_Account_Id);
                $trans = $this->db->get()->row();
                if ($trans->Credit == 0.00){
                    $Expense_acc[$A_key]['id'] = $value->Chart_Of_Account_Id;
                    $Expense_acc[$A_key]['AccountName'] = $value->AccountName;
                }
            }
        }
        return $Expense_acc;
    }
}