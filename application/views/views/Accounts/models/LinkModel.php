<?php
defined('BASEPATH') OR exit('No direct script access allowed');

Class LinkModel extends CI_Model
{
    private $table="company_structure";
    private $table1="account_title";
    private $table2="chart_of_account";
    private $ActiveYear;
    private $yearState;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('YearModel');
        $this->ActiveYear = $this->session->userdata('current_year');
        $this->yearState =  $this->YearModel->getYearStatus($this->ActiveYear);

    }

    public function save_data()
    {
        $accountname1 = $_POST['chart1'];
        $accounts = explode(',', $accountname1,-1);

        $levelid1 = $_POST['comm1'];
        $openingbalance = $_POST['OpeningBalance'];
        $openingbalance = $_POST['OpeningBalance'];

        foreach ($accounts as $key => $value) {

            $this->db->Select("id");
            $this->db->where("LevelCode", $_POST['comm1']);
            $ch = $this->db->get($this->table)->result();

            $this->db->Select("id");
            $this->db->where("AccountCode", $value);
            $ch1 = $this->db->get($this->table1)->result();

            $this->db->where('AccountCode', $value);
            $data1 = $this->db->get($this->table1)->result();

            $array = array('LevelId' => $ch[0]->id, 'AccountId' => $ch1[0]->id);
            $this->db->where($array);
            $match=$this->db->get('chart_of_account');

            $this->db->where("ParentCode", $_POST['comm1']);
            $get5=$this->db->get('company_structure');

            $this->db->select('AccountName');
            $this->db->where('id',$ch1[0]->id);
            $name = $this->db->get('account_title')->result();

            $this->db->select_max('Separate_Series');
            $seprate_series = $this->db->get('chart_of_account_years')->result();

            if ($get5->num_rows()>0) {
                return 102;
            }else{
                if($match->num_rows() > 0){
                    $error1 = array('errorCode1' => 203, 'Aname' => $name[0]->AccountName);
                    return $error1;
                }else{
                    if ($data1[0]->Category == 2) {
                        isset($_POST['Active'])?$active = 0:$active = 1;   // Active
                        isset($_POST['Series'])?$Series=$seprate_series[0]->Separate_Series+1:$Series=0;        // Seprate Series
                        $createdOn = date('Y-m-d H:i:s');
                        $createdBy = $_SESSION['user'][0]->id;
                        $data = array('LevelId' => $ch[0]->id, 'AccountId' => $ch1[0]->id,'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);
                        $coa = $this->db->insert('chart_of_account', $data);
                        $year = date('Y-m-d');
                        $last_Id = $this->db->insert_id();
                        $this->load->model('CalenderModel');
                        $Hdates = $this->CalenderModel->getHijriDate($year);
                        foreach ($Hdates as $hdate) {
                            $Qmdate = $hdate->Qm_date;
                        }
                        $asdas = strpos($Qmdate,"-");
                        $QmYear = substr($Qmdate,0,$asdas);

                        if($this->yearState->Active == 1){
                            $data = array('ChartOfAccountId' => $last_Id, 'Year' => $QmYear, 'OpeningBalance' => $openingbalance, 'CurrentBalance' => $openingbalance, 'Active' => $active, 'Separate_Series' => $Series,'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn );
                            $coaYear = $this->db->insert('chart_of_account_years', $data);
                        }else{
                            $data = array('ChartOfAccountId' => $last_Id, 'Year' => $QmYear, 'OpeningBalance' => $openingbalance, 'CurrentBalance' => $openingbalance, 'Active' => $active,'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn, 'Year' => $this->ActiveYear );
                            $coaYear = $this->db->insert('archived_chart_of_account_years', $data);
                        }
                    }else{
                        return false;
                    }
                }
            }
        }
        if($this->db->affected_rows() > 0) {
            return true;
        }else{
            return false;
        }
    }

    public function get_LastInserted()
    {
        $createdBy = $_SESSION['user'][0]->id;
        $this->db->select('*');
        $this->db->from('chart_of_account');
        $this->db->join('company_structure', 'chart_of_account.levelId = company_structure.id');
        $this->db->where('chart_of_account.CreatedBy', $createdBy);
        $this->db->order_by('chart_of_account.id', 'desc');
        $this->db->limit(1);
        return $this->db->get()->result();
    }

    public function get_by_account_code($code='')
    {
        if (isset($_SESSION['comp_id'])){
            $company = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $company = $_SESSION['comp'];
        }else{
            $company = '';
        }
        $this->db->select('*,a.AccountName,b.AccountName as Parent');
        $this->db->from('chart_of_account');
        $this->db->join('account_title a','chart_of_account.AccountId = a.id');
        $this->db->join('account_title b',' b.AccountCode = a.ParentCode');
        $this->db->join('company_structure','chart_of_account.LevelId = company_structure.id');
        if($this->yearState->Active == 1){
            $this->db->join('chart_of_account_years','chart_of_account.id = chart_of_account_years.ChartOfAccountId');
        }else{
            $this->db->join('archived_chart_of_account_years','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
        }
        $this->db->like('a.AccountCode', $code);
        if ($company != ''){
            $this->db->where('chart_of_account.LevelId',$company);
        }
        return $this->db->get()->result();
    }

    public function getdata()
    {
        if (isset($_SESSION['comp_id'])){
            $company = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $company = $_SESSION['comp'];
        }else{
            $company = '';
        }
        $this->db->select('*,a.AccountName,b.AccountName as Parent');
        $this->db->from('chart_of_account');
        $this->db->join('account_title a','chart_of_account.AccountId = a.id');
        $this->db->join('account_title b',' b.AccountCode = a.ParentCode');
        $this->db->join('company_structure','chart_of_account.LevelId = company_structure.id');
        if($this->yearState->Active == 1){
            $this->db->join('chart_of_account_years','chart_of_account.id = chart_of_account_years.ChartOfAccountId');
        }else{
            $this->db->join('archived_chart_of_account_years','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
        }
        if ($company != ''){
            $this->db->where('chart_of_account.LevelId',$company);
        }
        return $this->db->get()->result();
    }

    public function delete_data($id)
    {
        $this->db->select('AccountId,LevelId');
        $this->db->where('id', $id);
        $data=$this->db->get('chart_of_account')->result();
        $this->db->where('AccountID', $data[0]->AccountId);
        $this->db->where('LevelID', $data[0]->LevelId);
        if($this->yearState->Active == 1){
            $transaction=$this->db->get('transactions')->result();
        }else{
            $transaction=$this->db->get('archived_transactions')->result();
        }

        if(count($transaction) > 0){
            return false;
        }else{
            $this->db->where('id', $id);
            $this->db->delete($this->table2);
            if($this->yearState->Active == 1){
                $this->db->where('chart_of_account_years.ChartOfAccountId', $id);
                $this->db->delete('chart_of_account_years');
            }else{
                $this->db->where('archived_chart_of_account_years.ChartOfAccountId', $id);
                $this->db->delete('archived_chart_of_account_years');
            }

            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function max_seprate_series()
    {
        $this->db->select_max('Separate_Series');
        return $this->db->get('chart_of_account_years')->result();
    }

    public function editdata($id)
    {
        $this->db->select('*');
        $this->db->from($this->table2);
        $this->db->join('account_title','chart_of_account.AccountId=account_title.id');
        $this->db->join('company_structure','chart_of_account.LevelId=company_structure.id');
        if($this->yearState->Active == 1){
            $this->db->join('chart_of_account_years','chart_of_account.id=chart_of_account_years.ChartOfAccountId');
        }else{
            $this->db->join('archived_chart_of_account_years','chart_of_account.id=archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
        }
        $this->db->where('chart_of_account.id', $id);
        return $this->db->get()->result();
    }

    public function update_link($id,$Obalance,$Active,$Seprate)
    {
        $this->db->select('OpeningBalance,CurrentBalance');
        $this->db->where('ChartOfAccountId', $id);
        if($this->yearState->Active == 1){
            $ob = $this->db->get('chart_of_account_years')->result();
        }else{
            $ob = $this->db->get('archived_chart_of_account_years')->result();
        }
        foreach ($ob as $item) {
            $obold = $item->OpeningBalance;
            $cbold = $item->CurrentBalance;
        }
        if($Obalance !== $obold) {
            $obnew = $Obalance;
            $diff = $obnew - $obold;
            $cbnew = $cbold + ($diff);
            $this->db->set('OpeningBalance', $obnew);
            $this->db->set('CurrentBalance', $cbnew);
        }
        if(isset($Active)){
            $active = (int)$Active;
            $this->db->set('Active', $active);
        }
        if(isset($Seprate)){
            $seprate = (int)$Seprate;
            $this->db->set('Separate_Series', $seprate);
        }
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;

        $this->db->set('UpdatedBy', $updatedBy);
        $this->db->set('UpdatedOn', $updatedOn);

        $this->db->where('ChartOfAccountId',$id);
        if($this->yearState->Active == 1){
            $this->db->update('chart_of_account_years');
        }else{
            $this->db->update('archived_chart_of_account_years');
        }

        if($this->db->affected_rows()>0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_OpeningBalance($chart_id)
    {
        $this->db->select('SUM(OpeningBalance) as OpeningBalance');
        if($this->yearState->Active == 1){
            $this->db->from('chart_of_account_years');
            $this->db->join('chart_of_account','chart_of_account.id = chart_of_account_years.ChartOfAccountId');
        }else{
            $this->db->from('archived_chart_of_account_years');
            $this->db->join('chart_of_account','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
        }
        $this->db->where_in('chart_of_account_years.ChartOfAccountId',$chart_id);
        $result = $this->db->get('')->result();
        return $result;
    }

    public function SetBalancesForClosing()
    {

        $companies = $this->CompanyModel->getCompanies();
        $IncomeAccs = $this->ChartModel->getIncomeAcc();
        //return $IncomeAccs;
        $ExpenseAccs = $this->ChartModel->getExpenseAcc();
        foreach ($IncomeAccs as $key => $IncomeAcc) {
            $IncomeAccounts[] = $IncomeAcc->id;
        }
        foreach ($ExpenseAccs as $key => $ExpenseAcc) {
            $ExpenseAccounts[] = $ExpenseAcc->id;
        }

        foreach ($companies as $key => $company) {
            //Income Account Opening Balances
            $this->db->select('IFNULL(SUM(OpeningBalance),0) as OpeningBalance');
            $this->db->from('archived_chart_of_account_years');
            $this->db->join('chart_of_account','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where_in('chart_of_account.AccountId',$IncomeAccounts);
            $this->db->where('chart_of_account.LevelId', $company->id);
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
            $result['income'][$company->id] = $this->db->get()->result();

            //Expense Account Opening Balances
            $this->db->select('IFNULL(SUM(OpeningBalance),0) as OpeningBalance');
            $this->db->from('archived_chart_of_account_years');
            $this->db->join('chart_of_account','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where_in('chart_of_account.AccountId',$ExpenseAccounts);
            $this->db->where('chart_of_account.LevelId', $company->id);
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
            $result['expense'][$company->id] = $this->db->get()->result();
            // //Income Transactions Sum
            $result['income'][$company->id]['DCSum'] = $this->BookModel->GetTransactionsForClosing($IncomeAccounts,$company->id);
            // //Expense Transactions Sum
            $result['expense'][$company->id]['DCSum']= $this->BookModel->GetTransactionsForClosing($ExpenseAccounts,$company->id);

            //Income Closing Balance
            $result['income'][$company->id]['ClosingBalance'] = $result['income'][$company->id][0]->OpeningBalance + $result['income'][$company->id]['DCSum'][0]->Debit - $result['income'][$company->id]['DCSum'][0]->Credit;

            //Expense Closing Balance
            $result['expense'][$company->id]['ClosingBalance'] = $result['expense'][$company->id][0]->OpeningBalance + $result['expense'][$company->id]['DCSum'][0]->Debit - $result['expense'][$company->id]['DCSum'][0]->Credit;

            //Subtracting Income and Expense Closing Balance
            $NewAccountBalance[$company->id] = $result['income'][$company->id]['ClosingBalance'] + $result['expense'][$company->id]['ClosingBalance'];
            //$result[$company->id]['DiffClosingBalance'] = $result['income'][$company->id]['ClosingBalance'] + $result['expense'][$company->id]['ClosingBalance'];

            //Updating Balances of Closing Income And Expense Account
            $isUpdate = $this->updateBalanceOfIncomeAndExpenseAcc($company->id,$NewAccountBalance[$company->id]);

            $Income_Links_id =  $this->get_comp_links($IncomeAccounts,$company->id);
            $Expense_Links_id =  $this->get_comp_links($ExpenseAccounts,$company->id);

            foreach ($Income_Links_id as $key => $IncomeLinkAcc) {
                $IncomeLinkAccounts[] = $IncomeLinkAcc->id;
            }
            foreach ($Expense_Links_id as $key => $ExpenseLinkAcc) {
                $ExpenseLinkAccounts[] = $ExpenseLinkAcc->id;
            }
            //Reset Income Account Opening Balances
            $this->db->set('OpeningBalance',0.00);
            $this->db->set('CurrentBalance',0.00);
            $this->db->where_in('chart_of_account_years.ChartOfAccountId', $IncomeLinkAccounts);
            $this->db->update('chart_of_account_years');
            //Reset Expense Account Opening Balances
            $this->db->set('OpeningBalance',0.00);
            $this->db->set('CurrentBalance',0.00);
            $this->db->where_in('chart_of_account_years.ChartOfAccountId',$ExpenseLinkAccounts);
            $this->db->update('chart_of_account_years');
        }
        return $result;
    }

    public function updateBalanceOfIncomeAndExpenseAcc($levelId,$balance)
    {
        $Acc_id =  $this->ChartModel->Get_Aid(31401);
        $Link_id =  $this->get_links($Acc_id->id,$levelId);
        $this->db->set('OpeningBalance',$balance);
        $this->db->set('CurrentBalance',$balance);
        $this->db->where('ChartOfAccountId', $Link_id[0]->id);
        $this->db->update('chart_of_account_years');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getAccLinkid($Aid='',$level)
    {
        $this->db->select('id');
        if($Aid != ''){
            $this->db->where('AccountId', $Aid);
        }
        $this->db->where('LevelId', $level);
        $this->db->from('chart_of_account');
        if($Aid != ''){
            $a_id = $this->db->get()->row();
        }else{
            $a_id = $this->db->get()->result();
        }
        return $a_id;
    }

    public function get_links($Acc_id='',$Level_id='',$check='',$is_cons='')
    {
        $this->db->select('*');
        $this->db->from('chart_of_account');
        if ($check == ''){
            if($is_cons != ''){
                $this->db->where_in('levelId', $Level_id);
            }else{
                $this->db->where('levelId', $Level_id);
            }
            $this->db->where('AccountId', $Acc_id);
        }else{
            $this->db->where('chart_of_account.id', $check);
        }
        return $this->db->get()->result();
    }

    public function get_heads()
    {
        $this->db->select('*');
        $this->db->where('ParentCode',0);
        $this->db->from('account_title');
        return $this->db->get()->result();
    }

    public function get_link_sub($accounts)
    {
        $this->db->select('*');
        $this->db->where_in('AccountCode', $accounts);
        $subhead = $this->db->get('account_title')->result();
        foreach ($subhead as $key => $account) {
            $this->db->like('AccountCode', $account->AccountCode, 'after');
            $child[$key] = $this->db->get('account_title')->result();
        }
        return $child;
    }

    public function get_sub($acc_code)
    {
        $this->db->select('*');
        $this->db->where('ParentCode',$acc_code);
        $this->db->from('account_title');
        return $this->db->get()->result();
    }

    public function get_assets_links($parent,$level_id)
    {
        $this->db->select('*');
        $this->db->from('chart_of_account');
        $this->db->join('account_title', 'account_title.id = chart_of_account.AccountId');
        if($this->yearState->Active == 1){
            $this->db->join('chart_of_account_years','chart_of_account.id = chart_of_account_years.ChartOfAccountId');
        }else{
            $this->db->join('archived_chart_of_account_years','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
        }
        $this->db->where('chart_of_account.levelId', $level_id);
        $this->db->like('account_title.AccountCode', '1', 'after');
        return $this->db->get()->result();
    }

    public function get_level2($acc_code1,$acc_code2)
    {
        $this->db->select('*');
        $this->db->where('ParentCode',$acc_code1);
        $this->db->where('AccountCode', $acc_code2);
        $this->db->from('account_title');
        return $this->db->get()->result();
    }

    public function get_level3($acc_code)
    {
        $this->db->select('*');
        $this->db->where('ParentCode',$acc_code);
        $this->db->from('account_title');
        return $this->db->get()->result();
    }

    public function links($Tree_Accounts,$Level_id,$is_cons='')
    {
        $Is_link = 1;
        $int = 0;
        $checking_array = array();
        foreach ($Tree_Accounts as $key => $levels) {
            foreach ($levels as $key_1 => $level_1) {
                if (isset($level_1)){
                    if (is_numeric($key_1)){
                        $checking_array[$key][$key_1] = 'n';
                    }else{
                        foreach ($level_1 as $key_2 => $level_2) {
                            if (isset($level_2)){
                                if (is_numeric($key_2)){
                                    if ($level_2->Category == 2){
                                        $Is_link = $this->CheckLinks($level_2->id,$Level_id,$is_cons);
                                        if ($Is_link == 1){
                                            $int = preg_replace('/\D+/', '', $key_1);
                                            $checking_array[$key][$int] = 'y';

                                            $checking_array[$key][$key_1][$key_2] = 'y';
                                        }else{
                                            $checking_array[$key][$key_1][$key_2] = 'n';
                                        }
                                    }else{
                                        $checking_array[$key][$key_1][$key_2] = 'n';
                                    }
                                }else{
                                    foreach ($level_2 as $key_3 => $level_3) {
                                        if (isset($level_3)){
                                            if (is_numeric($key_3)){
                                                if ($level_3->Category == 2){
                                                    $Is_link = $this->CheckLinks($level_3->id,$Level_id,$is_cons);
                                                    if ($Is_link == 1){
                                                        $int = preg_replace('/\D+/', '', $key_1);
                                                        $checking_array[$key][$int] = 'y';

                                                        $int = preg_replace('/\D+/', '', $key_2);
                                                        $checking_array[$key][$key_1][$int] = 'y';

                                                        $checking_array[$key][$key_1][$key_2][$key_3] = 'y';
                                                    }else{
                                                        $checking_array[$key][$key_1][$key_2][$key_3] = 'n';
                                                    }
                                                }else{
                                                    $checking_array[$key][$key_1][$key_2][$key_3] = 'n';
                                                }
                                            }else{
                                                foreach ($level_3 as $key_4 => $level_4) {
                                                    if (isset($level_4)){
                                                        if (is_numeric($key_4)){
                                                            if ($level_4->Category == 2){
                                                                $Is_link = $this->CheckLinks($level_4->id,$Level_id,$is_cons);
                                                                if ($Is_link == 1){
                                                                    $int = preg_replace('/\D+/', '', $key_1);
                                                                    $checking_array[$key][$int] = 'y';

                                                                    $int = preg_replace('/\D+/', '', $key_2);
                                                                    $checking_array[$key][$key_1][$int] = 'y';

                                                                    $int = preg_replace('/\D+/', '', $key_3);
                                                                    $checking_array[$key][$key_1][$key_2][$int] = 'y';

                                                                    $checking_array[$key][$key_1][$key_2][$key_3][$key_4] = 'y';
                                                                }else{
                                                                    $checking_array[$key][$key_1][$key_2][$key_3][$key_4] = 'n';
                                                                }
                                                            }else{
                                                                $checking_array[$key][$key_1][$key_2][$key_3][$key_4] = 'n';
                                                            }
                                                        }else{
                                                            foreach ($level_4 as $key_5 => $level_5) {
                                                                if (isset($level_5)){
                                                                    if (is_numeric($key_5)){
                                                                        if ($level_5->Category == 2){
                                                                            $Is_link = $this->CheckLinks($level_5->id,$Level_id,$is_cons);
                                                                            if ($Is_link == 1){
                                                                                $int = preg_replace('/\D+/', '', $key_1);
                                                                                $checking_array[$key][$int] = 'y';

                                                                                $int = preg_replace('/\D+/', '', $key_2);
                                                                                $checking_array[$key][$key_1][$int] = 'y';

                                                                                $int = preg_replace('/\D+/', '', $key_3);
                                                                                $checking_array[$key][$key_1][$key_2][$int] = 'y';

                                                                                $int = preg_replace('/\D+/', '', $key_4);
                                                                                $checking_array[$key][$key_1][$key_2][$key_3][$int] = 'y';

                                                                                $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5] = 'y';
                                                                            }else{
                                                                                $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5] = 'n';
                                                                            }
                                                                        }else{
                                                                            $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5] = 'n';
                                                                        }
                                                                    }else{
                                                                        foreach ($level_5 as $key_6 => $level_6) {
                                                                            if (isset($level_6)){
                                                                                if (is_numeric($key_6)){
                                                                                    if ($level_6->Category == 2){
                                                                                        $Is_link = $this->CheckLinks($level_6->id,$Level_id,$is_cons);
                                                                                        if ($Is_link == 1){
                                                                                            $int = preg_replace('/\D+/', '', $key_1);
                                                                                            $checking_array[$key][$int] = 'y';

                                                                                            $int = preg_replace('/\D+/', '', $key_2);
                                                                                            $checking_array[$key][$key_1][$int] = 'y';

                                                                                            $int = preg_replace('/\D+/', '', $key_3);
                                                                                            $checking_array[$key][$key_1][$key_2][$int] = 'y';

                                                                                            $int = preg_replace('/\D+/', '', $key_4);
                                                                                            $checking_array[$key][$key_1][$key_2][$key_3][$int] = 'y';

                                                                                            $int = preg_replace('/\D+/', '', $key_5);
                                                                                            $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$int] = 'y';

                                                                                            $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6] = 'y';
                                                                                        }else{
                                                                                            $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6] = 'n';
                                                                                        }
                                                                                    }else{
                                                                                        $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6] = 'n';
                                                                                    }
                                                                                }else{
                                                                                    foreach ($level_6 as $key_7 => $level_7) {
                                                                                        if (isset($level_7)){
                                                                                            if (is_numeric($key_7)){
                                                                                                if ($level_7->Category == 2){
                                                                                                    $Is_link = $this->CheckLinks($level_7->id,$Level_id,$is_cons);
                                                                                                    if ($Is_link == 1){
                                                                                                        $int = preg_replace('/\D+/', '', $key_1);
                                                                                                        $checking_array[$key][$int] = 'y';

                                                                                                        $int = preg_replace('/\D+/', '', $key_2);
                                                                                                        $checking_array[$key][$key_1][$int] = 'y';

                                                                                                        $int = preg_replace('/\D+/', '', $key_3);
                                                                                                        $checking_array[$key][$key_1][$key_2][$int] = 'y';

                                                                                                        $int = preg_replace('/\D+/', '', $key_4);
                                                                                                        $checking_array[$key][$key_1][$key_2][$key_3][$int] = 'y';

                                                                                                        $int = preg_replace('/\D+/', '', $key_5);
                                                                                                        $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$int] = 'y';

                                                                                                        $int = preg_replace('/\D+/', '', $key_6);
                                                                                                        $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$int] = 'y';

                                                                                                        $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6][$key_7] = 'y';
                                                                                                    }else{
                                                                                                        $checking_array[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6][$key_7] = 'n';
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
//        echo '<pre>';
//        print_r($checking_array);
//        exit();
        return $checking_array;
    }

    public function CheckLinks($Acc_id,$level_id,$is_cons='')
    {
        if($is_cons != ''){
            $links = $this->LinkModel->get_links($Acc_id,$level_id,'',$is_cons);
        }else{
            $links = $this->LinkModel->get_links($Acc_id,$level_id);
        }
        if ($links == array()){
            return 0;
        }else{
            return 1;
        }
    }

    public function checkForZeroData($AccountTree,$Links,$account_level,$pre_transactions,$post_transactions)
    {
        $all_transactions = array_merge($post_transactions,$pre_transactions);
        $IsZero = array();
        $Is_link = 0;
        $int = 0;

        foreach ($AccountTree as $key => $levels) {
            foreach ($levels as $key_1 => $level_1) {
                if (isset($level_1)){
                    if (is_numeric($key_1)){
                        if (($level_1->Category == 2 && $account_level == 'detail') || $account_level >= 1){
                            if ($Links[$key][$key_1] == 'y'){
                                $Zero = $this->searchForZeroData($level_1->AccountCode,$all_transactions);
//                                $Zero = $this->searchForZeroData($level_1->AccountCode,$post_transactions);
//                                if ($Zero != 0 && $Zero != 1){
//                                    $Zero = $this->searchForZeroData($level_1->AccountCode,$pre_transactions);
//                                }
                                if ($Zero == 1){
                                    $IsZero[$key][$key_1] = 'y';
                                }elseif ($Zero == 0){
                                    $IsZero[$key][$key_1] = 'n';
                                }
                            }
                        }
                    } else{
                        foreach ($level_1 as $key_2 => $level_2){
                            if (isset($level_2)){
                                if (is_numeric($key_2)){
                                    if (($level_2->Category == 2 && $account_level == 'detail') || $account_level >= 3){
                                        if ($Links[$key][$key_1][$key_2] == 'y'){
                                            $Zero = $this->searchForZeroData($level_2->AccountCode,$all_transactions);
//                                            $Zero = $this->searchForZeroData($level_2->AccountCode,$post_transactions);
//                                            if ($Zero != 0 && $Zero != 1){
//                                                $Zero = $this->searchForZeroData($level_2->AccountCode,$pre_transactions);
//                                            }
                                            if ($Zero == 1){
                                                $int = preg_replace('/\D+/', '', $key_1);
                                                $IsZero[$key][$int] = 'y';

                                                $IsZero[$key][$key_1][$key_2] = 'y';
                                            }elseif ($Zero == 0){
                                                $IsZero[$key][$key_1][$key_2] = 'n';
                                            }
                                        }
                                    }
                                } else{
                                    foreach ($level_2 as $key_3 => $level_3){
                                        if (isset($level_3)){
                                            if (is_numeric($key_3)){
                                                if (($level_3->Category == 2 && $account_level == 'detail') || $account_level >= 5){
                                                    if ($Links[$key][$key_1][$key_2][$key_3] == 'y'){
                                                        $Zero = $this->searchForZeroData($level_3->AccountCode,$all_transactions);
//                                                        $Zero = $this->searchForZeroData($level_3->AccountCode,$post_transactions);
//                                                        if ($Zero != 0 && $Zero != 1){
//                                                            $Zero = $this->searchForZeroData($level_3->AccountCode,$pre_transactions);
//                                                        }
                                                        if ($Zero == 1){
                                                            $int = preg_replace('/\D+/', '', $key_1);
                                                            $IsZero[$key][$int] = 'y';

                                                            $int = preg_replace('/\D+/', '', $key_2);
                                                            $IsZero[$key][$key_1][$int] = 'y';

                                                            $IsZero[$key][$key_1][$key_2][$key_3] = 'y';
                                                        }elseif ($Zero == 0){
                                                            $IsZero[$key][$key_1][$key_2][$key_3] = 'n';
                                                        }
                                                    }
                                                }
                                            }else{
                                                foreach ($level_3 as $key_4 => $level_4) {
                                                    if (isset($level_4)){
                                                        if (is_numeric($key_4)){
                                                            if (($level_4->Category == 2 && $account_level == 'detail') || $account_level >= 7){
                                                                if ($Links[$key][$key_1][$key_2][$key_3][$key_4] == 'y'){
                                                                    $Zero = $this->searchForZeroData($level_4->AccountCode,$all_transactions);
//                                                                    $Zero = $this->searchForZeroData($level_4->AccountCode,$post_transactions);
//                                                                    if ($Zero != 0 && $Zero != 1){
//                                                                        $Zero = $this->searchForZeroData($level_4->AccountCode,$pre_transactions);
//                                                                    }
                                                                    if ($Zero == 1){
                                                                        $int = preg_replace('/\D+/', '', $key_1);
                                                                        $IsZero[$key][$int] = 'y';

                                                                        $int = preg_replace('/\D+/', '', $key_2);
                                                                        $IsZero[$key][$key_1][$int] = 'y';

                                                                        $int = preg_replace('/\D+/', '', $key_3);
                                                                        $IsZero[$key][$key_1][$key_2][$int] = 'y';

                                                                        $IsZero[$key][$key_1][$key_2][$key_3][$key_4] = 'y';
                                                                    } elseif ($Zero == 0){
                                                                        $IsZero[$key][$key_1][$key_2][$key_3][$key_4] = 'n';
                                                                    }
                                                                }
                                                            }
                                                        } else{
                                                            foreach ($level_4 as $key_5 => $level_5) {
                                                                if (isset($level_5)){
                                                                    if (is_numeric($key_5)){
                                                                        if (($level_5->Category == 2 && $account_level == 'detail') || $account_level >= 9){
                                                                            if ($Links[$key][$key_1][$key_2][$key_3][$key_4][$key_5] == 'y'){
                                                                                $Zero = $this->searchForZeroData($level_5->AccountCode,$all_transactions);
//                                                                                $Zero = $this->searchForZeroData($level_5->AccountCode,$post_transactions);
//                                                                                if ($Zero != 0 && $Zero != 1){
//                                                                                    $Zero = $this->searchForZeroData($level_5->AccountCode,$pre_transactions);
//                                                                                }
                                                                                if ($Zero == 1){
                                                                                    $int = preg_replace('/\D+/', '', $key_1);
                                                                                    $IsZero[$key][$int] = 'y';

                                                                                    $int = preg_replace('/\D+/', '', $key_2);
                                                                                    $IsZero[$key][$key_1][$int] = 'y';

                                                                                    $int = preg_replace('/\D+/', '', $key_3);
                                                                                    $IsZero[$key][$key_1][$key_2][$int] = 'y';

                                                                                    $int = preg_replace('/\D+/', '', $key_4);
                                                                                    $IsZero[$key][$key_1][$key_2][$key_3][$int] = 'y';

                                                                                    $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5] = 'y';
                                                                                }elseif ($Zero == 0){
                                                                                    $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5] = 'n';
                                                                                }
                                                                            }
                                                                        }
                                                                    }else{
                                                                        foreach ($level_5 as $key_6 => $level_6) {
                                                                            if (isset($level_6)){
                                                                                if (is_numeric($key_6)){
                                                                                    if (($level_6->Category == 2 && $account_level == 'detail') || $account_level >= 11){
                                                                                        if ($Links[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6] == 'y'){
                                                                                            $Zero = $this->searchForZeroData($level_6->AccountCode,$all_transactions);
//                                                                                            $Zero = $this->searchForZeroData($level_6->AccountCode,$post_transactions);
//                                                                                            if ($Zero != 0 && $Zero != 1){
//                                                                                                $Zero = $this->searchForZeroData($level_6->AccountCode,$pre_transactions);
//                                                                                            }
                                                                                            if ($Zero == 1){
                                                                                                $int = preg_replace('/\D+/', '', $key_1);
                                                                                                $IsZero[$key][$int] = 'y';

                                                                                                $int = preg_replace('/\D+/', '', $key_2);
                                                                                                $IsZero[$key][$key_1][$int] = 'y';

                                                                                                $int = preg_replace('/\D+/', '', $key_3);
                                                                                                $IsZero[$key][$key_1][$key_2][$int] = 'y';

                                                                                                $int = preg_replace('/\D+/', '', $key_4);
                                                                                                $IsZero[$key][$key_1][$key_2][$key_3][$int] = 'y';

                                                                                                $int = preg_replace('/\D+/', '', $key_5);
                                                                                                $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$int] = 'y';

                                                                                                $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6] = 'y';
                                                                                            }elseif ($Zero == 0){
                                                                                                $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6] = 'n';
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }else{
                                                                                    foreach ($level_6 as $key_7 => $level_7) {
                                                                                        if (isset($level_7)){
                                                                                            if (is_numeric($key_7)){
                                                                                                if (($level_7->Category == 2 && $account_level == 'detail') || $account_level >= 13){
                                                                                                    if ($Links[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6][$key_7] == 'y'){
                                                                                                        $Zero = $this->searchForZeroData($level_7->AccountCode,$all_transactions);
//                                                                                                        $Zero = $this->searchForZeroData($level_7->AccountCode,$post_transactions);
//                                                                                                        if ($Zero != 0 && $Zero != 1){
//                                                                                                            $Zero = $this->searchForZeroData($level_7->AccountCode,$pre_transactions);
//                                                                                                        }
                                                                                                        if ($Zero == 1){
                                                                                                            $int = preg_replace('/\D+/', '', $key_1);
                                                                                                            $IsZero[$key][$int] = 'y';

                                                                                                            $int = preg_replace('/\D+/', '', $key_2);
                                                                                                            $IsZero[$key][$key_1][$int] = 'y';

                                                                                                            $int = preg_replace('/\D+/', '', $key_3);
                                                                                                            $IsZero[$key][$key_1][$key_2][$int] = 'y';

                                                                                                            $int = preg_replace('/\D+/', '', $key_4);
                                                                                                            $IsZero[$key][$key_1][$key_2][$key_3][$int] = 'y';

                                                                                                            $int = preg_replace('/\D+/', '', $key_5);
                                                                                                            $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$int] = 'y';

                                                                                                            $int = preg_replace('/\D+/', '', $key_6);
                                                                                                            $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$int] = 'y';

                                                                                                            $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6][$key_7] = 'y';
                                                                                                        }elseif ($Zero == 0){
                                                                                                            $IsZero[$key][$key_1][$key_2][$key_3][$key_4][$key_5][$key_6][$key_7] = 'n';
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $IsZero;
    }

    public function searchForZeroData($Code, $array)
    {
        $result = '';
        if ($array != array()) {
            foreach ($array as $key1 => $val) {
                foreach ($val as $key2 => $item){
                    if (isset($item[0]->AccountCode)) {
                        if ($item[0]->AccountCode == $Code) {
                            if ($item[0]->Debit == 0 && $item[0]->Credit == 0 && $item[0]->Balance == 0) {
                                $result = 0;
                            } else {
                                $result = 1;
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function Getopenandcurrent($levelid) //balance check
    {
        if($this->yearState->Active == 1){
            $this->db->select('ChartOfAccountId,OpeningBalance,CurrentBalance');
            $this->db->from('chart_of_account_years');
            $this->db->join('chart_of_account','chart_of_account.id = chart_of_account_years.ChartOfAccountId');
            $this->db->where('chart_of_account.LevelId', $levelid);
            $this->db->order_by('ChartOfAccountId', 'ASC');
        }else{
            $this->db->select('ChartOfAccountId,OpeningBalance,CurrentBalance');
            $this->db->from('archived_chart_of_account_years');
            $this->db->join('chart_of_account','chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('chart_of_account.LevelId', $levelid);
            $this->db->order_by('ChartOfAccountId', 'ASC');
            $this->db->where('archived_chart_of_account_years.Year', $this->ActiveYear);
        }
        return $this->db->get()->result();
    }
}