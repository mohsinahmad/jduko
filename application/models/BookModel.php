<?php

class BookModel extends CI_Model
{
    private $activeyear;
    private $year_status;
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ChartModel');
        $this->load->model('YearModel');
        date_default_timezone_set('Asia/Karachi');
        $this->activeyear = $this->session->userdata('current_year');
        $this->year_status = $this->YearModel->getYearStatus($this->activeyear);
    }

    public function transaction_check()
    {
        $this->db->trans_begin();
        $this->db->query("INSERT INTO `transactions`( `VoucherType`, `VoucherNo`, `VoucherDateG`, `VoucherDateH`,`LinkID`, `LevelID`, `AccountID`, `Debit`, `Credit`, `DepartmentId`, `PaidTo`, `SequenceNo`, `Createdby`, `CreatedOn`) VALUES ('ts','00001','2017-06-23','1438-09-27',111,111,111,1699,1699,1,'Mohsin AHmad',1,1,'2017-06-23 00:00:00')");
        $this->db->query("INSERT INTO `transactions`( `VoucherType`, `VoucherNo`, `VoucherDateG`, `VoucherDateH`,`LinkID`, `LevelID`, `AccountID`, `Debit`, `Credit`, `DepartmentId`, `PaidTo`, `SequenceNo`, `Createdby`, `CreatedOn`) VALUES ('ts','00002','2017-06-23','1438-09-27',111,111,111,1699,1699,1,'Burhan',1,1,'2017-06-23 00:00:00')");
        $this->db->query("INSERT INTO `transactions`( `VoucherType`, `VoucherNo`, `VoucherDateG`, `VoucherDateH`,`LinkID`, `LevelID`, `AccountID`, `Debit`, `Credit`, `DepartmentId`, `PaidTo`, `SequenceNo`, `Createdby`, `CreatedOn`) VALUES ('ts','00003','2017-06-23','1438-09-27',111,111,111,1699,1699,1,'Hammad',1,1,'2017-06-23 00:00:00')");
        $this->db->query("UPDATE `transactions` SET `VoucherType`= '878' WHERE `VoucherType`= 'ts' AND `VoucherNo` = 00005");
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo '<script>alert("rollBack")</script>';
        } else {
            $this->db->trans_commit();
            echo '<script>alert("commit")</script>';
        }
    }
    public function get_date($date)
    {
        $this->db->select('Qm_date');
        $this->db->where('Sh_date', $date);
        return $this->db->get('calender')->result();
    }
    public function get_company()
    {
        $Isadmin = $_SESSION['user'][0]->IsAdmin;
        if ($Isadmin == 1) {
            $this->db->select('*');
            $this->db->from('company_structure');
            $this->db->where('LENGTH(company_structure.LevelCode) > 3');
        } else {
            $id = $_SESSION['user'][0]->id;
            $this->db->select('*');
            $this->db->from('company_structure');
            $this->db->where('LENGTH(company_structure.LevelCode) > 3');
        }
        return $this->db->get()->result();
    }
    public function get_account($id)
    {
        $this->db->select('account_title.id as acc_id,AccountName');
        $this->db->from('chart_of_account');
        $this->db->join('account_title', 'chart_of_account.AccountId = account_title.id');
        $this->db->where('chart_of_account.levelId', $id);
        return $this->db->get()->result();
    }
    public function save_transaction($company_id, $book_type)
    {



        $Seperate_Series = 0;
        foreach ($_POST['accountId'] as $key => $value) {
            if($this->year_status->Active == 1) {
              $this->db->select('chart_of_account.id, chart_of_account_years.Separate_Series');
            $this->db->join('chart_of_account_years', 'chart_of_account.id = chart_of_account_years.ChartOfAccountId');
            }else{
                $this->db->select('chart_of_account.id, archived_chart_of_account_years.Separate_Series');
            $this->db->join('archived_chart_of_account_years', 'chart_of_account.id = archived_chart_of_account_years.ChartOfAccountId');            
            }            
            $this->db->where('LevelId', $company_id);
            $this->db->where('AccountId', $value);
            $result[$key] = $this->db->get('chart_of_account')->result();
        }
                foreach ($result as $item) {            
            if ($item[0]->Separate_Series != 0) {
                $this->db->where('ChartOfAccountId', $item[0]->id);
                if($this->year_status->Active == 1) {
                $sep_seq_num[] = $this->db->get('chart_of_account_years')->result();
                }else{
                    $sep_seq_num[] = $this->db->get('archived_chart_of_account_years')->result();
                }
                $Seperate_Series = 1;
            }
        }
            

            
        if ($Seperate_Series == 1) {
            $voucher_no = $this->Seprate_serial_VoucherNum($result, $book_type);
        } else {
            $voucher_no = $this->get_voucher_no($book_type, $company_id, $book_type);
        }


            // echo '<pre>';
            // print_r($sep_seq_num);
            // exit();



        $voucherType = strtoupper($book_type);
        $seqno = 1;
        foreach ($_POST['accountId'] as $key => $value) {
            if (isset($_POST['IsReverse'])) {
                $this->db->set('IsReverse', $_POST['IsReverse']);
            } else {
                $this->db->set('IsReverse', 0);
            }
            isset($sep_seq_num[0][0]->Separate_Series) ? $this->db->set('Seprate_series_num', $sep_seq_num[0][0]->Separate_Series) : '';
            $this->db->set('SequenceNo', $seqno);
            $this->db->set('LevelID', $_POST['companyId']);
            $this->db->set('AccountID', $value);
            $this->db->set('LinkID', $result[$key][0]->id);
            if (isset($_POST['departId'])) {
                $this->db->set('DepartmentId', $_POST['departId']);
            }
            if (isset($_POST['caldebit'][$key])) {
                $this->db->set('TaxDebit', $_POST['caldebit'][$key]);
            }
            $this->db->set('VoucherType', $voucherType);
            $this->db->set('VoucherNo', $voucher_no);
            $this->db->set('Remarks', $_POST['transac_details']);
            $this->db->set('VoucherDateG', $_POST['englishDate']);
            $this->db->set('VoucherDateH', $_POST['islamicDate']);
            $this->db->set('PaidTo', $_POST['paidTo']);
            $this->db->set('ChequeNumber', $_POST['bchequeno'][$key]);
            $this->db->set('ChequeDate', $_POST['bchequedate'][$key]);
            $this->db->set('Description', $_POST['details'][$key]);
            $this->db->set('Debit', $_POST['recieved'][$key]);
            $this->db->set('Credit', $_POST['payment'][$key]);
            $this->db->set('Createdby', $_SESSION['user'][0]->id);
            $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
            $this->db->set('year', $_SESSION['current_year']);
            if($this->year_status->Active == 1) {
             $this->db->insert('transactions');
                } else {
                $this->db->insert('archived_transactions');
                }
                $seqno++;
        }

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function Seprate_serial_VoucherNum($result, $book_type)
    {
        //$where = 'permanent_vouchernumber is null';
        foreach ($result as $item) {
            if ($item[0]->Separate_Series != 0) {
                $this->db->select('IFNULL(MAX(`VoucherNo`),0) AS `VoucherNo`');
                $this->db->where('VoucherType', $book_type);
                $this->db->where('Seprate_series_num', $item[0]->Separate_Series);
                $this->db->where('LinkID', isset($item[0]->id) ? $item[0]->id : $item[0]->LinkID);
                $this->db->where('is_delete','0');
               // $this->db->where($where);
                if($this->year_status->Active == 1) {
                $voucher = $this->db->get('transactions')->result();
                }else{
                    $voucher = $this->db->get('archived_transactions')->result();
                }
                break;
            }
        }
        $voucher_Num = $voucher[0]->VoucherNo;
        $v_number = $voucher_Num + 1;
        return str_pad($v_number, 5, 0, STR_PAD_LEFT);
    }

    public function get_voucher_no($book_type, $company_id, $type)
    {
        $voucher_no = "";
        $vouch = "";
        //$voucherType = "";
        $vouchNumb = "";
        $ser = $this->checkSer($company_id);
        if ($ser[0]->IsSerealized == 0) {   //Merkaz
            $code = $this->get_code($book_type);
        } else {                            //Khud Kafeel
            $code = $this->getDepartMax($book_type, $company_id);
        }
        if ($code->num_rows() > 0) {
            $voucherNo = $code->result();
            if (empty($voucherNo[0]->VoucherNo)) {
                if ($book_type == $type) {
                    //$voucherType = 'BR';
                    $vouchNumb = 1;
                    $voucher_no = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                } else {
                    //$voucherType = 'BP';
                    $vouchNumb = 1;
                    $voucher_no = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                }
            } else {
                if ($voucherNo[0]->VoucherType == $type) {
                    if ($voucherNo[0]->IsSerealized == 0) {
                        $vouchNo = $voucherNo[0]->VoucherNo;
                        $vouchNumb = ++$vouchNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        //$voucherType = 'BR';
                        $voucher_no = $vouchNumber;
                    } else {
                        $vouchNo = $voucherNo[0]->VoucherNo;
                        $vouchNumb = ++$vouchNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        //$voucherType = 'BR';
                        $voucher_no = $vouchNumber;
                    }
                } else {
                    if ($voucherNo[0]->IsSerealized == 0) {
                        $vouchNo = $voucherNo[0]->VoucherNo;
                        $vouchNumb = ++$vouchNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        //$voucherType = 'BP';
                        $voucher_no = $vouchNumber;
                    } else {
                        $vouchNo = $voucherNo[0]->VoucherNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        $voucher_no = $vouchNumber;
                    }
                }
                $vouchNo = $voucherNo[0]->VoucherNo;
                $vouchNumb = ++$vouchNo;
                $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                if ($book_type == $type) {
                    //$voucherType = 'BR';
                    $voucher_no = $vouchNumber;
                } else {
                    //$voucherType = 'BP';
                    $voucher_no = $vouchNumber;
                }
            }
        }
        return $voucher_no;
    }

    public function checkSer($comp_id)
    {
        $this->db->select('IsSerealized');
        $this->db->where('id', $comp_id);
        return $this->db->get('company_structure')->result();
    }
    public function get_code($book_type)
    {
        //$where = 'permanent_vouchernumber is null';
        $type = strtoupper($book_type);
        $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
        $this->db->select_max('VoucherNo');
        if($this->year_status->Active == 1) {
                    $this->db->from('transactions');
                }else{
                    $this->db->from('archived_transactions');
                } 
        $this->db->where('is_delete', 0);
        $this->db->where('VoucherType', $type);
        $this->db->where('company_structure.IsSerealized !=', 1);
        //$this->db->where($where);
        if($this->year_status->Active == 1) {
        $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
        }else{
        $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
        }
        return $this->db->get();   
}
    public function getDepartMax($book_type, $comp_id)
    {
         $where = 'permanent_vouchernumber is null';
        if($this->year_status->Active == 1) {
            if ($book_type == 'cr') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('transactions.LevelID', $comp_id);
                $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            } else if ($book_type == 'cp') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('transactions.LevelID', $comp_id);
                $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            } else if ($book_type == 'br') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('transactions');
                $this->db->where('is_delete', 0);
                $this->db->where('VoucherType', $type);
                $this->db->where($where);
                $this->db->where('transactions.LevelID', $comp_id);
                $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            } else if ($book_type == 'bp') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('transactions');
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('VoucherType', $type);
                $this->db->where('transactions.LevelID', $comp_id);
                $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            } else if ($book_type == 'jv') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('transactions');
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('VoucherType', $type);
                $this->db->where('transactions.LevelID', $comp_id);
                $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            }
        } else {
            if ($book_type == 'cr') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('archived_transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('archived_transactions.LevelID', $comp_id);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            } else if ($book_type == 'cp') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('archived_transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('archived_transactions.LevelID', $comp_id);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            } else if ($book_type == 'br') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('archived_transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('archived_transactions.LevelID', $comp_id);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            } else if ($book_type == 'bp') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('archived_transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('archived_transactions.LevelID', $comp_id);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            } else if ($book_type == 'jv') {
                $type = strtoupper($book_type);
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('VoucherNo');
                $this->db->from('archived_transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete', 0);
                $this->db->where($where);
                $this->db->where('archived_transactions.LevelID', $comp_id);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            }
        }
        return $this->db->get();
    }
    public function Update_Transactions()
    {



        foreach ($_POST['AccountID'] as $key => $value) {
            $this->db->select('id');
            $this->db->where('LevelId', $_POST['LevelID']);
            $this->db->where('AccountId', $value);
            $result[$key] = $this->db->get('chart_of_account')->result();
        }
        $arr = array(
            'VoucherType' => $_POST['VoucherType'],
            'VoucherNo' => $_POST['VoucherNo'],
            'LevelID' => $_POST['LevelID']
        );
         
        if($this->year_status->Active == 1) {
           $this->db->where($arr);
           $this->db->set('is_delete','1');
            $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));
            $this->db->update('transactions');
        } else {
            $this->db->where($arr);
            $this->db->set('is_delete','1');
            $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));
            $this->db->update('archived_transactions');
        }
        if ($this->db->affected_rows() > 0) {
            $seqno = 0;
            foreach ($_POST['AccountID'] as $key => $value) {
                $seqno++;
                $this->db->set('LevelID', $_POST['LevelID']);
                if (isset($_POST['IsReverse'])) {
                    $this->db->set('IsReverse', $_POST['IsReverse']);
                } else {
                    $this->db->set('IsReverse', 0);
                }
                $this->db->set('VoucherType', $_POST['VoucherType']);
                $this->db->set('VoucherNo', $_POST['VoucherNo']);
                if (isset($_POST['caldebit'][$key])) {
                    $this->db->set('TaxDebit', $_POST['caldebit'][$key]);
                }
                if (isset($_POST['DepartmentId'])) {
                    $this->db->set('DepartmentId', $_POST['DepartmentId']);
                } else {
                    $this->db->set('DepartmentId', 0);
                }
                $this->db->set('Remarks', $_POST['Remarks']);
                $this->db->set('PaidTo', $_POST['PaidTo']);
                $this->db->set('VoucherDateG', $_POST['VoucherDateG']);
                $this->db->set('VoucherDateH', $_POST['VoucherDateH']);
                $this->db->set('Createdby', $_POST['Createdby']);
                $this->db->set('CreatedOn', $_POST['CreatedOn']);
                $this->db->set('SequenceNo', $seqno);
                $this->db->set('AccountID', $value);
                $this->db->set('LinkID', $result[$key][0]->id);
                $this->db->set('Debit', $_POST['Debit'][$key]);
                $this->db->set('Credit', $_POST['Credit'][$key]);
                $this->db->set('Description', $_POST['Description'][$key]);
                $this->db->set('Seprate_series_num', $_POST['sep_seq']);
                $this->db->set('ChequeNumber', $_POST['ChequeNumber'][$key]);
                $this->db->set('ChequeDate', $_POST['ChequeDate'][$key]);
                $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));
                 
        if($this->year_status->Active == 1) {
                    $this->db->insert('transactions');
                } else {
                    // $this->db->set('Year', $this->activeyear);
                    $this->db->insert('archived_transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function Per_Update_Transactions()
    {
            // echo '<pre>';
            // print_r($_POST);
            // exit();

        foreach ($_POST['AccountID'] as $key => $value) {
            $this->db->select('id');
            $this->db->where('LevelId', $_POST['LevelID']);
            $this->db->where('AccountId', $value);
            $result[$key] = $this->db->get('chart_of_account')->result();
        }
        $arr = array(
            'VoucherType' => $_POST['VoucherType'],
            'VoucherNo' => $_POST['VoucherNo'],
            'LevelID' => $_POST['LevelID']
        );
         
        if($this->year_status->Active == 1) {
            $this->db->where($arr);
            $this->db->set('is_delete','1');
            $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));
            $this->db->update('transactions');
        } else {
            $this->db->where($arr);
            $this->db->set('is_delete','1');
            $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));
            $this->db->update('archived_transactions');
            //$this->db->delete('archived_transactions');
        }
        if ($this->db->affected_rows() > 0) {
            $seqno = 0;
            foreach ($_POST['AccountID'] as $key => $value) {
                $balance = $this->get_account_balance($value, $_POST['LevelID']);
                $ch_id = $this->get_chart_of_account_id($value, $_POST['LevelID']);
                if ($_POST['Debit'][$key] != 0.00) {
                    if (isset($_POST['PreviousDebit'][$key]) && $_POST['PreviousDebit'][$key] != 0.00) {
                        $debit = $_POST['PreviousDebit'][$key] - $_POST['Debit'][$key];
                        $newDebit = ($balance[0]->CurrentBalance) - ($debit);
                    } else if (isset($_POST['PreviousCredit'][$key])) {
                        $debit = $_POST['PreviousCredit'][$key] + $_POST['Debit'][$key];
                        $newDebit = ($balance[0]->CurrentBalance) + ($debit);
                    } else {
                        $newDebit = $balance[0]->CurrentBalance + $_POST['Debit'][$key];
                    }
                    $balanceUpdated = $this->update_current_balance($ch_id[0]->id, $newDebit, '', '', 1);
                } else {
                    if (isset($_POST['PreviousCredit'][$key]) && $_POST['PreviousCredit'][$key] != 0.00) {
                        $credit = $_POST['PreviousCredit'][$key] - $_POST['Credit'][$key];
                        $newCredit = ($balance[0]->CurrentBalance) + ($credit);
                    } else if (isset($_POST['PreviousDebit'][$key])) {
                        $credit = $_POST['PreviousDebit'][$key] + $_POST['Credit'][$key];
                        $newCredit = ($balance[0]->CurrentBalance) - ($credit);
                    } else {
                        $newCredit = $balance[0]->CurrentBalance - $_POST['Credit'][$key];
                    }
                    $balanceUpdated = $this->update_current_balance($ch_id[0]->id, $newCredit, '', '', 1);
                }
                $seqno++;
                if (isset($_POST['IsReverse'])) {
                    $this->db->set('IsReverse', $_POST['IsReverse']);
                } else {
                    $this->db->set('IsReverse', 0);
                }
                $this->db->set('LevelID', $_POST['LevelID']);

                $this->db->set('VoucherType', $_POST['VoucherType']);
                $this->db->set('VoucherNo', $_POST['VoucherNo']);
                if (isset($_POST['caldebit'][$key])) {
                    $this->db->set('TaxDebit', $_POST['caldebit'][$key]);
                }
                $this->db->set('Permanent_VoucherDateH', $_POST['Permanent_VoucherDateH']);
                $this->db->set('Permanent_VoucherDateG', $_POST['Permanent_VoucherDateG']);
                $this->db->set('Permanent_VoucherNumber', $_POST['Permanent_VoucherNumber']);
                if (isset($_POST['DepartmentId'])) {
                    $this->db->set('DepartmentId', $_POST['DepartmentId']);
                } else {
                    $this->db->set('DepartmentId', 0);
                }
                $this->db->set('Remarks', $_POST['Remarks']);
                $this->db->set('PaidTo', $_POST['PaidTo']);
                $this->db->set('VoucherDateG', $_POST['VoucherDateG']);
                $this->db->set('VoucherDateH', $_POST['VoucherDateH']);
                $this->db->set('Createdby', $_POST['Createdby']);
                $this->db->set('CreatedOn', $_POST['CreatedOn']);
                $this->db->set('SequenceNo', $seqno);
                $this->db->set('AccountID', $value);
                $this->db->set('LinkID', $result[$key][0]->id);
                $this->db->set('Debit', $_POST['Debit'][$key]);
                $this->db->set('Credit', $_POST['Credit'][$key]);
                $this->db->set('Description', $_POST['Description'][$key]);
                $this->db->set('Seprate_series_num', $_POST['sep_seq']);
                $this->db->set('ChequeNumber', $_POST['ChequeNumber'][$key]);
                $this->db->set('ChequeDate', $_POST['ChequeDate'][$key]);
                $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));                 
        if($this->year_status->Active == 1) {
                    $this->db->insert('transactions');
                } else {
                    // $this->db->set('Year', $this->activeyear);
                    $this->db->insert('archived_transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function seq_max($id)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('VoucherNo,VoucherType');
            $this->db->where('Id', $id);
            $v_num_type = $this->db->get('transactions')->result();

            $this->db->select('id');
            $this->db->select_max('SequenceNo');
            $this->db->where('VoucherNo', $v_num_type[0]->VoucherNo);
            $this->db->where('VoucherType', $v_num_type[0]->VoucherType);
            return $this->db->get('transactions')->result();
        } else {
            $this->db->select('VoucherNo,VoucherType');
            $this->db->where('Id', $id);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $v_num_type = $this->db->get('archived_transactions')->result();

            $this->db->select('id');
            $this->db->select_max('SequenceNo');
            $this->db->where('VoucherNo', $v_num_type[0]->VoucherNo);
            $this->db->where('VoucherType', $v_num_type[0]->VoucherType);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }
    public function getCurrentBalance($c_id, $b_id)
    {
        $this->db->select('CurrentBalance');
        $this->db->where('LevelId', $c_id);
        $this->db->where('AccountId', $b_id);
        return $this->db->get('chart_of_account')->result();
    }

    public function check_rec_seg()
    {
        $this->db->select_max('SequenceNo');
         
        if($this->year_status->Active == 1) {
            return $this->db->get('transactions')->result();
        } else {
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }
    public function get_books_acc_to_user($id)
    {
        $this->db->select('*');
        $this->db->from('chart_of_account');
        $this->db->join('account_title', 'account_title.id = chart_of_account.AccountId');
        $this->db->where('LevelId', $id);
        $this->db->where('account_title.type', 1);
        return $this->db->get()->result();
    }
    public function getAccount($id, $a_id)
    {
        $this->db->select('*,chart_of_account.id as c_id');
        $this->db->from('chart_of_account');
        $this->db->join('account_title', 'chart_of_account.AccountId = account_title.id');
        $this->db->where('LevelId', $id);
        $this->db->where('AccountId !=', $a_id);
        return $this->db->get()->result();
    }
    public function get_account_name($id, $a_id)
    {
        $this->db->select('LevelId,AccountId,CurrentBalance,AccountName');
        $this->db->from('chart_of_account');
        $this->db->join('account_title', 'account_title.id = chart_of_account.AccountId');
        $this->db->where('chart_of_account.AccountId', $a_id);
        $this->db->where('chart_of_account.LevelId', $id);
        return $this->db->get()->result();
    }
    public function get_company_name($id)
    {
        $this->db->select('*');
        $this->db->join('company_structure a', 'a.ParentCode = b.LevelCode');
        $this->db->where('a.id', $id);
        return $this->db->get('company_structure b')->result();
    }
    public function get_account_balance($id, $compId)
    {

        $this->db->select('id');
        $this->db->where('LevelId', $compId);
        $this->db->where('AccountId', $id);
        $coa_id = $this->db->get('chart_of_account')->result();
        $this->db->select('CurrentBalance');
        $this->db->where('ChartOfAccountId', $coa_id[0]->id);
        // $result1 = $this->db->query('SELECT year from closing_year')->result();
        // $previous_year_date = $result1[0]->year;
        if($this->year_status->Active == 1) {
            // $this->db->where('archived_chart_of_account_years.Year', $this->activeyear);s
            return $this->db->get('chart_of_account_years')->result();
        } else {
            // $this->db->where('chart_of_account_years.Year', $this->activeyear);
            return $this->db->get('archived_chart_of_account_years')->result();
        }
    }
    public function get_all_for_move_acc($level)
    {
        $OriginAccount = $this->input->post('AccountCode1');
        $DestAccount = $this->input->post('AccountCode2');
        $to = $this->input->post('to');
        $from = $this->input->post('from');
        $t_type = $this->input->post('ttype');
        $result = array();
        $O_id = $this->ChartModel->Get_Aid($OriginAccount);
        $D_id = $this->ChartModel->Get_Aid($DestAccount);
        $ids = array($O_id->id, $D_id->id);
        foreach ($ids as $key => $id) {
            $this->db->select('transactions.Id,transactions.AccountID,transactions.Permanent_VoucherNumber, transactions.VoucherType, transactions.VoucherNo, transactions.VoucherDateG, transactions.Permanent_VoucherDateG, transactions.Debit,transactions.Credit');
            $this->db->where('transactions.LevelID', $level);
            $this->db->where('transactions.AccountID', $id);
            if ($t_type == 'p') {
                $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
                $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            } else {
                $this->db->where('transactions.Permanent_VoucherNumber', NULL);
                $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            }
            $this->db->from('transactions');
            $query1 = $this->db->get_compiled_select();

            $this->db->select('income.Id, income.AccountID, income.Permanent_VoucherNumber,income.VoucherType, income.VoucherNo, income.VoucherDateG, income.Permanent_VoucherDateG, income.Debit, income.Credit');
            $this->db->where('income.LevelID', $level);
            $this->db->where('income.AccountID', $id);
            if ($t_type == 'p') {
                $this->db->where('income.Permanent_VoucherNumber !=', NULL);
                $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            } else {
                $this->db->where('income.Permanent_VoucherNumber', NULL);
                $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            }
            $this->db->from('income');
            $query2 = $this->db->get_compiled_select();

            $result[] = $this->db->query('Select Id, AccountID, Permanent_VoucherNumber, VoucherType, VoucherNo, VoucherDateG, Permanent_VoucherDateG, Debit,Credit From(' . $query1 . ' UNION ' . $query2 . ')' . 'as t')->result();
        }
        return $result;
    }
    public function get_all_transactions($book_type = '', $Level_id = '')
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit)as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId');
            $this->db->where('transactions.LevelID', $Level_id);
            if ($book_type != '') {
                $this->db->where('transactions.VoucherType', $book_type);
            }
            //$this->db->like('transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('transactions.VoucherType');
            $this->db->group_by('transactions.VoucherNo');
        } else {
            $this->db->select('SUM(archived_transactions.Debit)as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
            $this->db->where('archived_transactions.LevelID', $Level_id);
            if ($book_type != '') {
                $this->db->where('archived_transactions.VoucherType', $book_type);
            }
            $this->db->limit(50);
            // $this->db->like('archived_transactions.VoucherDateH', $this->session->userdata('current_year'));
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->group_by('archived_transactions.VoucherType');
            $this->db->group_by('archived_transactions.VoucherNo');
        }
        return $this->db->get()->result();
    }
    public function get_transactions($book_type, $Level_id)
    {
        
        if($this->year_status->Active == 1) {

            $this->db->select('SUM(transactions.Debit)as debit,transactions.Id as t_id,transactions.VoucherType, 
            transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, 
            transactions.PaidTo, departments.DepartmentName, transactions.Remarks, transactions.Seprate_series_num,permanent_vouchernumber');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId', 'left');
            $this->db->where('transactions.Permanent_VoucherNumber =', NUll);
            $this->db->where('transactions.LevelID', $Level_id);
            $this->db->where('transactions.is_delete', '0');
            if ($book_type != '') {
                $this->db->like('transactions.VoucherType', strtoupper($book_type), 'after');
            }
//            $this->db->like('transactions.VoucherDateH', $this->session->userdata('current_year'),'after');
            $this->db->group_by('transactions.VoucherNo');
            $this->db->group_by('transactions.Seprate_series_num');
//            $this->db->group_by('transactions.departmentid');
        } else {
            $this->db->select('SUM(archived_transactions.Debit)as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, permanent_vouchernumber,archived_transactions.Remarks,archived_transactions.Seprate_series_num');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId', 'left');
            $this->db->where('archived_transactions.Permanent_VoucherNumber =', NUll);
            $this->db->where('archived_transactions.LevelID', $Level_id);
            $this->db->where('archived_transactions.is_delete', '0');
            if ($book_type != '') {
                $this->db->like('archived_transactions.VoucherType', strtoupper($book_type), 'after');
            }
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            // $this->db->like('archived_transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('archived_transactions.VoucherNo');
            $this->db->group_by('archived_transactions.Seprate_series_num');

        }
        return $this->db->get()->result();
    }

    public function get_per_transactions($book_type, $Level_id)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit)as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId');
            $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
            $this->db->where('transactions.LevelID', $Level_id);
            $this->db->where('transactions.VoucherType', $book_type);
//            $this->db->like('transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('transactions.VoucherNo');
        } else {
            $this->db->select('SUM(archived_transactions.Debit)as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
            $this->db->where('archived_transactions.Permanent_VoucherNumber !=', NULL);
            $this->db->where('archived_transactions.LevelID', $Level_id);
            $this->db->where('archived_transactions.VoucherType', $book_type);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->like('archived_transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('archived_transactions.VoucherNo');
        }
        return $this->db->get()->result();
    }

    public function transactions()
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('*');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
        } else {
            $this->db->select('*');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            // $this->db->where('archived_transactions.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }

    public function get_type($id)
    {
         
        $this->db->select('VoucherType');
        $this->db->where('id', $id);
        if($this->year_status->Active == 1) {
            return $this->db->get('transactions')->result();
        } else {
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }

    public function getDepartmentName($id)
    {
        $this->db->select('DepartmentName');
        $this->db->where('id', $id);
        return $this->db->get('departments')->result();
    }

    public function get_edit_transaction($id, $company_id)
    {

            $is_permit = $this->uri->segment(6);
            $data = $this->getVoucherAndType($id); 
            if($this->year_status->Active == 1) {
            $this->db->select('transactions.TaxDebit,transactions.IsReverse,transactions.LinkID,transactions.CreatedOn,
            transactions.Createdby,transactions.Permanent_VoucherDateH, transactions.Permanent_VoucherDateG, 
            transactions.Permanent_VoucherNumber, transactions.Id as t_id,company_structure.id as level_id,
            company_structure.LevelName,transactions.VoucherType,transactions.VoucherNo,transactions.VoucherDateG,
            transactions.VoucherDateH, departments.Id as DepartmentId,departments.DepartmentName,transactions.PaidTo,
            transactions.Remarks,transactions.Debit,transactions.Credit,transactions.Description,account_title.id as AccountID, 
            account_title.AccountName, account_title.Type as AccountType, transactions.SequenceNo,transactions.ChequeNumber,
            transactions.ChequeDate,transactions.Seprate_series_num');
//            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.Id = transactions.DepartmentId', 'left');
            $this->db->where('transactions.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('transactions.VoucherType', $data[0]->VoucherType);
            $this->db->where('transactions.Seprate_series_num', $data[0]->Seprate_series_num);
            $this->db->where('transactions.LevelID', $company_id);
            $this->db->where('is_delete','0');
            if($is_permit == '0'){
                    $where = 'Permanent_VoucherNumber IS NULL';
                    $this->db->where($where);
            }
            else{
                $where = 'Permanent_VoucherNumber IS NOT NULL';
                $this->db->where($where);
            }
            return $this->db->get('transactions')->result();
        } else {
            $this->db->select('archived_transactions.TaxDebit,archived_transactions.LinkID,archived_transactions.CreatedOn,archived_transactions.Createdby,archived_transactions.Permanent_VoucherDateH, archived_transactions.Permanent_VoucherDateG, archived_transactions.Permanent_VoucherNumber, archived_transactions.Id as t_id,company_structure.id as level_id,company_structure.LevelName,archived_transactions.VoucherType,archived_transactions.VoucherNo,archived_transactions.VoucherDateG,archived_transactions.VoucherDateH, departments.Id as DepartmentId,departments.DepartmentName,archived_transactions.PaidTo,archived_transactions.Remarks,archived_transactions.Debit,archived_transactions.Credit,archived_transactions.Description,account_title.id as AccountID, account_title.AccountName, account_title.Type as AccountType, archived_transactions.SequenceNo,archived_transactions.ChequeNumber,archived_transactions.ChequeDate,archived_transactions.Seprate_series_num,archived_transactions.IsReverse');
            // $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.Id = archived_transactions.DepartmentId', 'left');
            $this->db->where('archived_transactions.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('archived_transactions.VoucherType', $data[0]->VoucherType);
            $this->db->where('archived_transactions.LevelID', $company_id);
            $this->db->where('archived_transactions.is_delete', '0');
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            if($is_permit == '0'){

                    $where = 'Permanent_VoucherNumber IS NULL';
                    $this->db->where($where);
            }
            else{

                $where = 'Permanent_VoucherNumber IS NOT NULL';
                $this->db->where($where);
            }
            return $this->db->get('archived_transactions')->result();
        }
    }

    public function getVoucherAndType($id)
    {

         
        if($this->year_status->Active == 1) {
            $this->db->select('VoucherNo, VoucherType, Seprate_series_num');
            $this->db->where('transactions.id', $id);
            return $this->db->get('transactions')->result();
        } else {
            $this->db->select('VoucherNo, VoucherType, Seprate_series_num');
            $this->db->where('archived_transactions.id', $id);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }
    public function delete_transaction($voucherno, $voucherType, $levelid)
    {
        $delete = array('VoucherNo' => $voucherno, 'VoucherType' => strtoupper($voucherType), 'LevelID' => $levelid);
         
        if($this->year_status->Active == 1) {
            $this->db->where($delete);
            $this->db->set('is_delete','1');
            $this->db->update('transactions');
        } else {
            $this->db->where($delete);
            $this->db->set('is_delete','1');
            $this->db->update('archived_transactions');
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function  get_hijri_date($date = ''){

        $hijri_date = $this->db->get_where('calender',array(
            'Sh_date'=>$date,
        ))->result();
        return $hijri_date;

    }
    public function get_transaction_by_date($book_type, $id, $to, $from)
    {

        $result1 = $this->db->query('SELECT year from closing_year')->result();
        $previous_year_date = $result1[0]->year;
        $hijri_date = $this->get_hijri_date($from);
        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit) as debit,transactions.Id as t_id,
            transactions.VoucherType,transactions.Seprate_series_num, transactions.Permanent_VoucherNumber, transactions.VoucherNo, 
            transactions.VoucherDateG,transactions.LevelID,transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, 
            transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID','left');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID','left');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId','left');
            $array = array('transactions.Permanent_VoucherNumber' => NULL, 'transactions.VoucherType' => $book_type, 'transactions.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            $this->db->group_by('transactions.VoucherNo');
        } else {
            $this->db->select('SUM(archived_transactions.Debit) as debit,archived_transactions.Id as t_id,
            archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, 
            archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo,archived_transactions.LevelID,departments.DepartmentName, 
            archived_transactions.Remarks,archived_transactions.Seprate_series_num');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID','left');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID','left');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId','left');
            $array = array('archived_transactions.Permanent_VoucherNumber' => NULL, 'archived_transactions.VoucherType' => $book_type, 'archived_transactions.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
           // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->group_by('archived_transactions.VoucherNo');
        }
        return $this->db->get()->result();
    }
    public function get_transaction_date($to, $from, $book_type = '', $Level_id = '') /*for dashborad only*/
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit) as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId');
            if ($book_type != '') {
                $this->db->where('transactions.VoucherType', $book_type);
            }
            if ($Level_id != '') {
                $this->db->where('transactions.LevelID', $Level_id);
            }
            $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            $this->db->group_by('transactions.VoucherNo');
            $this->db->group_by('transactions.VoucherType');
        } else {
            $this->db->select('SUM(archived_transactions.Debit) as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
            if ($book_type != '') {
                $this->db->where('archived_transactions.VoucherType', $book_type);
            }
            if ($Level_id != '') {
                $this->db->where('archived_transactions.LevelID', $Level_id);
            }
            $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->group_by('archived_transactions.VoucherNo');
            $this->db->group_by('archived_transactions.VoucherType');
        }
        return $this->db->get()->result();
    }

    public function get_company_by_user()
    {
//        $result1 = $this->db->query('SELECT year from closing_year')->result();
//        $previous_year_date = $result1[0]->year;
//        $hijri_date = $this->get_hijri_date(date('Y-m-d'));
        if ($this->year_status->Active == 1) {
            $user_id = $_SESSION['user'][0]->id;
            $this->db->select('company_structure.LevelName,transactions.Id,transactions.LevelID,company_structure.ParentCode');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->where('transactions.Createdby', $user_id);
            $this->db->order_by('transactions.Id', 'desc');
        } else {
            $user_id = $_SESSION['user'][0]->id;
            $this->db->select('company_structure.LevelName,archived_transactions.Id,archived_transactions.LevelID,company_structure.ParentCode');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->where('archived_transactions.Createdby', $user_id);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->order_by('archived_transactions.Id', 'desc');
        }
        return $this->db->get()->result();
    }

    public function get_by_voucher_no($code = '', $type, $id)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('*,transactions.Id as t_id');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->like('VoucherNo', $code);
            $this->db->where('transactions.LevelID', $id);
            $this->db->where('transactions.voucherType', $type);
        } else {
            $this->db->select('*,archived_transactions.Id as t_id');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->like('VoucherNo', $code);
            $this->db->where('archived_transactions.LevelID', $id);
            $this->db->where('archived_transactions.voucherType', $type);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }

    public function getVoucher($voucherno = '') /*ya function dashboard pe voucher ky search ky liny ha*/
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId');
            $this->db->like('VoucherNo', $voucherno);
        } else {
            $this->db->select('debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->like('VoucherNo', $voucherno);
        }
        return $this->db->get()->result();
    }

    public function get_by_per_voucher_no($code = '', $type, $id)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit)as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId');
            $this->db->like('VoucherNo', $code);
            $this->db->where('transactions.LevelID', $id);
            $this->db->where('transactions.voucherType', $type);
            $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
        } else {
            $this->db->select('SUM(archived_transactions.Debit)as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
            $this->db->like('VoucherNo', $code);
            $this->db->where('archived_transactions.LevelID', $id);
            $this->db->where('archived_transactions.voucherType', $type);
            $this->db->where('archived_transactions.Permanent_VoucherNumber !=', NULL);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }

    public function get_by_account_code($code, $type, $id)
    {
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $code);
        $acc_code = $this->db->get('account_title')->result();
        if (!empty($acc_code)) {
            foreach ($acc_code as $ac_c) {
                 
        if($this->year_status->Active == 1) {
                    $this->db->select('*,transactions.Id as t_id,transactions.Debit as debit');
                    $this->db->from('transactions');
                    $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                    $this->db->join('departments', 'departments.id = transactions.DepartmentId');
                    $this->db->where('transactions.AccountID', $ac_c->id);
                    $this->db->where('transactions.LevelID', $id);
                    $this->db->where('transactions.VoucherType', $type);
                } else {
                    $this->db->select('*,archived_transactions.Id as t_id,archived_transactions.Debit as debit');
                    $this->db->from('archived_transactions');
                    $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                    $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
                    $this->db->where('archived_transactions.AccountID', $ac_c->id);
                    $this->db->where('archived_transactions.LevelID', $id);
                    $this->db->where('archived_transactions.VoucherType', $type);
                    // $this->db->where('archived_transactions.Year', $this->activeyear);
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        } else {
            return null;
        }
    }

    public function get_transaction_by_VouchernoAndType($voucherno = '', $book_type = '', $Level_id = '')
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit)as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.VoucherDateG, transactions.VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId');
            $this->db->where('transactions.Permanent_VoucherNumber', NULL);
            $this->db->where('transactions.LevelID', $Level_id);

            if ($book_type != '') {
                $this->db->where('transactions.VoucherType', $book_type);
            }
            if ($voucherno != '') {
                $this->db->Like('transactions.VoucherNo', $voucherno);
            }
            $this->db->group_by('transactions.VoucherType');
            $this->db->group_by('transactions.VoucherNo');
        } else {
            $this->db->select('SUM(archived_transactions.Debit)as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.VoucherDateG, archived_transactions.VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
            $this->db->where('archived_transactions.Permanent_VoucherNumber', NULL);
            $this->db->where('archived_transactions.LevelID', $Level_id);
            if ($book_type != '') {
                $this->db->where('archived_transactions.VoucherType', $book_type);
            }
            if ($voucherno != '') {
                $this->db->Like('archived_transactions.VoucherNo', $voucherno);
            }
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->like('archived_transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('archived_transactions.VoucherType');
            $this->db->group_by('archived_transactions.VoucherNo');
        }
        return $this->db->get()->result();
    }

    public function get_transaction_by_dateAndAccountCode($type = '', $id = '')
    {
        $to = $_POST['to'];
        $from = $_POST['from'];
        $account_code = $_POST['AccountCode'];
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $account_code);
        $acc_code = $this->db->get('account_title')->result();
        if (!empty($acc_code)) {
            foreach ($acc_code as $ac_c) {
                 
        if($this->year_status->Active == 1) {
                    $this->db->select('*,transactions.Id as t_id');
                    $this->db->from('transactions');
                    $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                    $this->db->join('departments', 'departments.id = transactions.DepartmentId');
                    $this->db->where('transactions.AccountID', $ac_c->id);
                    $this->db->where('transactions.LevelID', $id);
                    $this->db->where('transactions.voucherType', $type);
                    $this->db->where('transactions.VoucherDateG >=', $to);
                    $this->db->where('transactions.VoucherDateG <=', $from);
                    //$this->db->like('transactions.VoucherDateH', $this->session->userdata('current_year'));
                } else {
                    $this->db->select('*,archived_transactions.Id as t_id');
                    $this->db->from('archived_transactions');
                    $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                    $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
                    $this->db->where('archived_transactions.AccountID', $ac_c->id);
                    $this->db->where('archived_transactions.LevelID', $id);
                    $this->db->where('archived_transactions.voucherType', $type);
                    $this->db->where('archived_transactions.VoucherDateG >=', $to);
                    $this->db->where('archived_transactions.VoucherDateG <=', $from);
                    // $this->db->where('archived_transactions.Year', $this->activeyear);
                    $this->db->like('archived_transactions.VoucherDateH', $this->session->userdata('current_year'));
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        } else {
            return null;
        }
    }

    public function get_move_transaction($id)

    {
        if($this->year_status->Active == 1) {
            $this->db->select('VoucherType,VoucherNo,LevelID,Seprate_series_num,VoucherType');
            $this->db->where('Id', $id);
            $this->db->from('transactions');
            $data = $this->db->get()->result();
//            print_r($data);
//            exit();
//        print_r($data[0]->VoucherType); exit();
            $where = 'transactions.permanent_vouchernumber is null';
            $this->db->select('transactions.Seprate_series_num,transactions.LevelID, `transactions`.`Id` as `t_id`, transactions.VoucherType, transactions.VoucherNo, transactions.AccountID, account_title.Type, account_title.AccountName, transactions.Debit, transactions.Credit, transactions.ChequeNumber, transactions.ChequeDate');
            $this->db->from('transactions');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->where('transactions.VoucherType', $data[0]->VoucherType);
            $this->db->where('transactions.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('transactions.LevelID ', $data[0]->LevelID);
            $this->db->where('transactions.Seprate_series_num ', $data[0]->Seprate_series_num);
            $this->db->where($where);
            $this->db->where('transactions.is_delete', '0');
        } else {
            $this->db->select('VoucherType,VoucherNo,LevelID ');
            $this->db->where('Id', $id);
            $this->db->from('archived_transactions');
            $data = $this->db->get()->result();
//        print_r($data[0]->VoucherType); exit();
            $where = 'archived_transactions.permanent_vouchernumber is null';
            $this->db->select('archived_transactions.Seprate_series_num,archived_transactions.LevelID, `archived_transactions`.`Id` as `t_id`, archived_transactions.VoucherType, archived_transactions.VoucherNo, archived_transactions.AccountID, account_title.Type, account_title.AccountName, archived_transactions.Debit, archived_transactions.Credit, archived_transactions.ChequeNumber, archived_transactions.ChequeDate');
            $this->db->from('archived_transactions');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->where('archived_transactions.VoucherType', $data[0]->VoucherType);
            $this->db->where('archived_transactions.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('archived_transactions.LevelID ', $data[0]->LevelID);
            $this->db->where('archived_transactions.is_delete', '0');
            $this->db->where($where);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }

    public function move_transaction($id, $type)
    {


            // print_r($_POST);
            // exit();



        $company_id = $_POST['level'];
        $book_type = $_POST['v_type'];
        $Seprate_series_num = $_POST['Seprate_series_num'];
        if (isset($_POST['ChequeDate'])) {
            foreach ($_POST['ChequeDate'] as $value => $ChequeDates) {
                $ChequeDate[] = $ChequeDates;
                $ChequeNumber[] = $_POST['ChequeNumber'][$value];
                $bt_id[] = $_POST['bt_id'][$value];
            }
        }
        $Edate = $_POST['Edate'];
        $Idate = $_POST['Idate'];
        $v_no = $_POST['v_no'];
        $voucher_no = "";
        $seprate = 0;
        $vouch = "";
        $vouchNumb = "";
        $accnts = $this->get_transaction_account($v_no, $book_type, $company_id, $Seprate_series_num);
        $tranCount = $this->get_max_tranCount($v_no, $company_id, $book_type);
        foreach ($accnts as $accnt) {
            $balance = $this->get_account_balance($accnt->AccountID, $company_id);
            $ch_id = $this->get_chart_of_account_id($accnt->AccountID, $company_id);
            if ($accnt->Debit != 0.00) {
                $debit = $accnt->Debit;
                $credit = '';
                $balanceUpdated = $this->update_current_balance($ch_id[0]->id, $balance[0]->CurrentBalance, $debit, $credit);
        } else {
                $debit = '';
                $credit = $accnt->Credit;
                $balanceUpdated = $this->update_current_balance($ch_id[0]->id, $balance[0]->CurrentBalance, $debit, $credit);
            }
            if ($accnt->Separate_Series != 0) {
                $seprate = 1;
            }
        }
        if ($seprate == 1) {
            $voucher_no = $this->Seprate_serial_PerminentVoucherNum($accnts, $book_type, $Seprate_series_num);
//
//            echo $voucher_no.'in if';
//            exit();
        } else {
            $voucher_no = $this->GetPerminentVoucherNumber($company_id, $book_type, $type);
//            echo $voucher_no.'in else';
//            exit();
        }
        // $result1 = $this->db->query('SELECT year from closing_year')->result();
        // $previous_year_date = $result1[0]->year;
       
        $where = 'permanent_vouchernumber is null';
        $this->db->where('VoucherNo', $v_no);
        $this->db->where('VoucherType', $book_type);
        $this->db->where('LevelID', $company_id);
        $this->db->where('is_delete','0');
        $this->db->where('Seprate_series_num', $Seprate_series_num);
        $this->db->where($where);
        $this->db->set('Permanent_VoucherNumber', $voucher_no);
        $this->db->set('Permanent_VoucherDateG', $Edate);
        $this->db->set('Permanent_VoucherDateH', $Idate);        
        if($this->year_status->Active == 1) {
            $this->db->update('transactions');
        }
        else{
            $this->db->update('archived_transactions');
        }
       //echo $this->db->last_query();
        if ($this->db->affected_rows() > 0) {
            if (isset($ChequeNumber)) {
                foreach ($ChequeNumber as $key => $value) {
                    $this->db->where('id', $bt_id[$key]);
                    $this->db->set('ChequeNumber', $ChequeNumber[$key]);
                    $this->db->set('ChequeDate', $ChequeDate[$key]);
                    $this->db->update('transactions');
                }
                if ($this->db->affected_rows() > 0) {
                    $MyResult = true;
                } else {
                    $MyResult = false;
                }
            }
            $MyResult = true;
        } else {
            $MyResult = false;
        }
        $voucher_no = "";
        return $MyResult;
    }

    public function Seprate_serial_PerminentVoucherNum($result, $book_type, $Seprate_series_num)
    {
        foreach ($result as $item) {
            if ($item->Separate_Series != 0) {
                $this->db->select('IFNULL(MAX(`Permanent_VoucherNumber`),0) AS `Permanent_VoucherNumber`');
                $this->db->where('VoucherType', $book_type);
                $this->db->where('LinkID', $item->LinkID);
                $this->db->where('is_delete','0');
                $this->db->where('Seprate_series_num', $Seprate_series_num);
                // $result1 = $this->db->query('SELECT year from closing_year')->result();
                // $previous_year_date = $result1[0]->year;
                if($this->year_status->Active == 1) {
                    $voucher = $this->db->get('transactions')->result();
                }
                else {
                    $voucher = $this->db->get('archived_transactions')->result();
                }
                break;
            }
        }
        $voucher_Num = $voucher[0]->Permanent_VoucherNumber;
        $v_number = $voucher_Num + 1;
        return str_pad($v_number, 5, 0, STR_PAD_LEFT);
    }
    public function GetPerminentVoucherNumber($company_id, $book_type, $type)
    {

        $ser = $this->checkSer($company_id);
        if ($ser[0]->IsSerealized == 0) {
            $code = $this->get_max_permanent($book_type);
        } else {
            $code = $this->get_dept_max_permanet($book_type, $company_id);
        }
        if ($code->num_rows() > 0) {
            $voucherNo = $code->result();
            //return $voucherNo;
            if (empty($voucherNo[0]->Permanent_VoucherNumber)) {
                    if ($book_type == $type) {
                    // $voucherType = 'CR';
                    $vouchNumb = 1;
                    $voucher_no = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                } else {
                    // $voucherType = 'CP';
                    $vouchNumb = 1;
                    $voucher_no = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                }
            } else {
                if ($voucherNo[0]->VoucherType == $type) {
                    if ($voucherNo[0]->IsSerealized == 0) {
                        $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                        $vouchNumb = ++$vouchNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        // $voucherType = 'CR';
                        $voucher_no = $vouchNumber;
                    } else {
                        $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                        $vouchNumb = ++$vouchNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        // $voucherType = 'CR';
                        $voucher_no = $vouchNumber;
                    }
                } else {
                    if ($voucherNo[0]->IsSerealized == 0) {
                        $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                        $vouchNumb = ++$vouchNo;
                        $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                        // $voucherType = 'CP';
                        $voucher_no = $vouchNumber;
                    } else {
                    }
                }
                $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                $vouchNumb = ++$vouchNo;
                $vouchNumber = str_pad($vouchNumb, 5, 0, STR_PAD_LEFT);
                if ($book_type == $type) {
                    // $voucherType = 'CR';
                    $voucher_no = $vouchNumber;
                } else {
                    // $voucherType = 'CP';
                    $voucher_no = $vouchNumber;
                }
               // $voucher_no ++;
            }
        }
        return $voucher_no;
    }
    public function get_max_tranCount($voucher_no, $level_id, $type)
    {

        if($this->year_status->Active == 1) {
            $this->db->where('VoucherNo', $voucher_no);
            $this->db->where('LevelID', $level_id);
            $this->db->where('voucherType', $type);
            $this->db->where('is_delete','0');
            //$this->db->where($where);
            // $this->db->where('transactions.Year', $this->activeyear);
            $this->db->select_max('count');
            return $this->db->get('transactions')->result();
        } else {
            $this->db->where('VoucherNo', $voucher_no);
            $this->db->where('LevelID', $level_id);
            $this->db->where('voucherType', $type);
            $this->db->where('is_delete','0');
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->select_max('count');
           return $this->db->get('archived_transactions')->result();
        }
    }
    public function get_chart_of_account_id($acc_id, $level_id)
    {
        $this->db->select('id');
        $this->db->where('AccountId', $acc_id);
        $this->db->where('LevelId', $level_id);
        return $this->db->get('chart_of_account')->result();
    }
    public function update_current_balance($id, $balance, $debit = '', $credit = '', $per = '')
    {
//        echo 'agaya';
//        exit();
        if ($per == '') {
            if ($debit != '') {
                $updatedBalance = $balance + $debit;
            } else {
                $updatedBalance = $balance - $credit;
            }
        } else {
            $updatedBalance = $balance;
        }
        $this->db->where('ChartOfAccountId', $id);
        $this->db->set('CurrentBalance', $updatedBalance);
       if($this->year_status->Active == 1) {
            return $this->db->update('chart_of_account_years');
        }
        else {
            return $this->db->update('archived_chart_of_account_years');
        }
    }
    public function get_transaction_account($v_no, $book_type, $company_id, $Seprate_series_num)
    {
        //$where = 'permanent_vouchernumber is null';
        if($this->year_status->Active == 1) {           
            $this->db->select('LinkID,Separate_Series,Debit,Credit,AccountID');
            $this->db->join('chart_of_account_years', 'transactions.LinkID = chart_of_account_years.ChartOfAccountId');
            $this->db->where('transactions.VoucherNo', $v_no);
            $this->db->where('transactions.VoucherType', $book_type);
            $this->db->where('transactions.LevelID', $company_id);
            $this->db->where('is_delete','0');
            //    $this->db->where($where);
            $this->db->where('transactions.Seprate_series_num', $Seprate_series_num);
            return $this->db->get('transactions')->result();
        } else {
             $this->db->select('LinkID,Separate_Series,Debit,Credit,AccountID');
            $this->db->join('archived_chart_of_account_years', 'archived_transactions.LinkID = archived_chart_of_account_years.ChartOfAccountId');
            $this->db->where('archived_transactions.VoucherNo', $v_no);
            $this->db->where('archived_transactions.VoucherType', $book_type);
            $this->db->where('archived_transactions.LevelID', $company_id);
            $this->db->where('is_delete','0');
               // $this->db->where($where);
            $this->db->where('archived_transactions.Seprate_series_num', $Seprate_series_num);
            return $this->db->get('archived_transactions')->result();            
        }
    }
    public function get_max_permanent($book_type)
    {
        $type = strtoupper($book_type);
        if($this->year_status->Active == 1) {
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('Permanent_VoucherNumber');
            $this->db->from('transactions');
            $this->db->where('VoucherType', $type);
            $this->db->where('is_delete','0');
            $this->db->where('company_structure.IsSerealized !=', 1);
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
        } else {

            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('Permanent_VoucherNumber');
            $this->db->from('archived_transactions');
            $this->db->where('VoucherType', $type);
            $this->db->where('is_delete','0');
            $this->db->where('company_structure.IsSerealized !=', 1);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
        }
        return $this->db->get();
    }
    public function get_dept_max_permanet($book_type, $comp_id)
    {
        $type = strtoupper($book_type);
        if($this->year_status->Active == 1) {
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('Permanent_VoucherNumber');
                $this->db->from('transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete','0');
                $this->db->where('transactions.LevelID', $comp_id);
                $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            } else {
                $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
                $this->db->select_max('Permanent_VoucherNumber');
                $this->db->from('archived_transactions');
                $this->db->where('VoucherType', $type);
                $this->db->where('is_delete','0');
                $this->db->where('archived_transactions.LevelID', $comp_id);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            }
        return $this->db->get();
    }

    public function get_permanent_voucher($book_type, $Level_id)
    {

         
        if($this->year_status->Active == 1) {
            $this->db->select('transactions.Seprate_series_num,SUM(transactions.Debit)as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId', 'left');
            $this->db->where('transactions.Permanent_VoucherNumber !=', NUll);
            $this->db->where('transactions.LevelID', $Level_id);
            $this->db->where('transactions.VoucherType', $book_type);
            $this->db->where('is_delete','0');
            //$this->db->like('transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('transactions.permanent_vouchernumber');
            $this->db->group_by('transactions.Seprate_series_num');
            $this->db->group_by('transactions.voucherno');
        } else {
            $this->db->select('SUM(archived_transactions.Debit)as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.Permanent_VoucherDateG, archived_transactions.Permanent_VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks,archived_transactions.Seprate_series_num');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId', 'left');
            $this->db->where('archived_transactions.Permanent_VoucherNumber !=', NUll);
            $this->db->where('archived_transactions.LevelID', $Level_id);
            $this->db->where('archived_transactions.VoucherType', $book_type);
            $this->db->where('is_delete','0');
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            //$this->db->like('archived_transactions.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('archived_transactions.permanent_vouchernumber');
            $this->db->group_by('archived_transactions.Seprate_series_num');
            $this->db->group_by('archived_transactions.voucherno');
        }
        return $this->db->get()->result();
    }

    public function update_Permanent_Voucher($type, $TokeepVoucher, $level_id)
    {

            //     echo '<pre>';
            // print_r($_POST);
            // exit();

        if ($type == 'cr') {
            foreach ($_POST['data']['toTemp'] as $key => $value) {
                $count = $this->get_max_tranCount($_POST['vouch_no']['VoucherNo'][$key], $level_id);
                //return $TokeepVoucher;
                if ($TokeepVoucher == 0) {
                    //return $TokeepVoucher;
                    $count = 1;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('Permanent_VoucherNumber', NULL);
                    $this->db->set('Permanent_VoucherDateH', NULL);
                    $this->db->set('Permanent_VoucherDateG', NULL);
                    $this->db->set('count', $count);
                    $this->db->update('transactions');
                } else {
                    //return $TokeepVoucher;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('count', ++$count[0]->count);
                    $this->db->update('transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'cp') {
            foreach ($_POST['data']['toTemp'] as $key => $value) {
                $count = $this->get_max_tranCount($_POST['vouch_no']['VoucherNo'][$key], $level_id);
                //return $TokeepVoucher;
                if ($TokeepVoucher == 0) {
                    //return $TokeepVoucher;
                    $count = 1;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('Permanent_VoucherNumber', NULL);
                    $this->db->set('Permanent_VoucherDateH', NULL);
                    $this->db->set('Permanent_VoucherDateG', NULL);
                    $this->db->set('count', $count);
                    $this->db->update('transactions');
                } else {
                    //return $TokeepVoucher;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('count', ++$count[0]->count);
                    $this->db->update('transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'br') {
            //return $level_id;
            foreach ($_POST['data']['toTemp'] as $key => $value) {
                $count = $this->get_max_tranCount($_POST['vouch_no']['VoucherNo'][$key], $level_id);
                //return $TokeepVoucher;
                if ($TokeepVoucher == 0) {
                    //return $TokeepVoucher;
                    $count = 1;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('Permanent_VoucherNumber', NULL);
                    $this->db->set('Permanent_VoucherDateH', NULL);
                    $this->db->set('Permanent_VoucherDateG', NULL);
                    $this->db->set('count', $count);
                    $this->db->update('transactions');
                } else {
                    //return $TokeepVoucher;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('count', ++$count[0]->count);
                    $this->db->update('transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'bp') {
            foreach ($_POST['data']['toTemp'] as $key => $value) {
                $count = $this->get_max_tranCount($_POST['vouch_no']['VoucherNo'][$key], $level_id);
                if ($TokeepVoucher == 0) {
                    $count = 1;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('Permanent_VoucherNumber', NULL);
                    $this->db->set('Permanent_VoucherDateH', NULL);
                    $this->db->set('Permanent_VoucherDateG', NULL);
                    $this->db->set('count', $count);
                    $this->db->update('transactions');
                } else {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('count', ++$count[0]->count);
                    $this->db->update('transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } elseif ($type == 'jv') {
            foreach ($_POST['data']['toTemp'] as $key => $value) {
                $count = $this->get_max_tranCount($_POST['vouch_no']['VoucherNo'][$key], $level_id);
                //return $TokeepVoucher;
                if ($TokeepVoucher == 0) {
                    //return $TokeepVoucher;
                    $count = 1;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('Permanent_VoucherNumber', NULL);
                    $this->db->set('Permanent_VoucherDateH', NULL);
                    $this->db->set('Permanent_VoucherDateG', NULL);
                    $this->db->set('count', $count);
                    $this->db->update('transactions');
                } else {
                    //return $TokeepVoucher;
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->set('count', ++$count[0]->count);
                    $this->db->update('transactions');
                }
            }
            if ($this->db->affected_rows() > 0) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function copy_voucher($type, $level_id)
    {
         
        if($this->year_status->Active == 1){
            if ($type == 'cr') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->select('*,Seprate_series_num as Separate_Series');
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $this->db->where('Seprate_series_num', $_POST['Seprate_series']['Seprate_series'][$key]);
                    $trans[$key] = $this->db->get('transactions')->result();
                    if ($_POST['Seprate_series']['Seprate_series'][$key] != 0) {
                        $voucher_no = $this->Seprate_serial_VoucherNum($trans, $type);
                    } else {
                        $voucher_no = $this->get_voucher_no($type, $level_id, 'cr');
                    }
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        isset($data->Separate_Series) ? $this->db->set('Seprate_series_num', $data->Separate_Series) : '';
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'cp') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->select('*,Seprate_series_num as Separate_Series');
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $this->db->where('Seprate_series_num', $_POST['Seprate_series']['Seprate_series'][$key]);
                    $trans[$key] = $this->db->get('transactions')->result();
                    if ($_POST['Seprate_series']['Seprate_series'][$key] != 0) {
                        $voucher_no = $this->Seprate_serial_VoucherNum($trans, $type);
                    } else {
                        $voucher_no = $this->get_voucher_no($type, $level_id, 'cp');
                    }
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                   isset($data->Separate_Series) ? $this->db->set('Seprate_series_num', $data->Separate_Series) : '';
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'br') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->select('*,Seprate_series_num as Separate_Series');
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $this->db->where('Seprate_series_num', $_POST['Seprate_series']['Seprate_series'][$key]);
                    $trans[$key] = $this->db->get('transactions')->result();
                    if ($_POST['Seprate_series']['Seprate_series'][$key] != 0) {
                        $voucher_no = $this->Seprate_serial_VoucherNum($trans, $type);
                    } else {
                        $voucher_no = $this->get_voucher_no($type, $level_id, 'br');
                    }
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                    isset($data->Separate_Series) ? $this->db->set('Seprate_series_num', $data->Separate_Series) : '';
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'bp') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->select('*,Seprate_series_num as Separate_Series');
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where("is_delete",'0');
                    $this->db->where('LevelID', $level_id);
                    $this->db->where('Seprate_series_num', $_POST['Seprate_series']['Seprate_series'][$key]);
                    $trans[$key] = $this->db->get('transactions')->result();
                    //echo "<pre>";
                    //print_r($trans[$key]);
                    //print_r($_POST['Seprate_series']['Seprate_series'][$key]);
                    if ($_POST['Seprate_series']['Seprate_series'][$key] != 0) {
                        $voucher_no = $this->Seprate_serial_VoucherNum($trans, $type);
                    } else {
                        $voucher_no = $this->get_voucher_no($type, $level_id, 'bp');
                    }
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                    isset($data->Separate_Series) ? $this->db->set('Seprate_series_num', $data->Separate_Series) : '';
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        //    exit();
                        $this->db->insert('transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'jv') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $trans[$key] = $this->db->get('transactions')->result();
                    $voucher_no = $this->get_voucher_no($type, $level_id, 'jv');
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            if ($type == 'cr') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $trans[$key] = $this->db->get('archived_transactions')->result();
                    $voucher_no = $this->get_voucher_no($type, $level_id, 'cr');
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('archived_transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'cp') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $trans[$key] = $this->db->get('archived_transactions')->result();
                    $voucher_no = $this->get_voucher_no($type, $level_id, 'cp');
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        isset($data->DepartmentId) ? $this->db->set('DepartmentId', $data->DepartmentId) : '';
                        isset($data->Description) ? $this->db->set('Description', $data->Description) : '';
                        isset($data->Debit) ? $this->db->set('Debit', $data->Debit) : '';
                        isset($data->Credit) ? $this->db->set('Credit', $data->Credit) : '';
                        $this->db->set('count', 1);
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->insert('archived_transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'br') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $trans[$key] = $this->db->get('archived_transactions')->result();
                    $voucher_no = $this->get_voucher_no($type, $level_id, 'br');
                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        if (isset($data->DepartmentId)) {
                            $this->db->set('DepartmentId', $data->DepartmentId);
                        }
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        if (isset($data->Description)) {
                            $this->db->set('Description', $data->Description);
                        }
                        if (isset($data->Debit)) {
                            $this->db->set('Debit', $data->Debit);
                        }
                        if (isset($data->Credit)) {
                            $this->db->set('Credit', $data->Credit);
                        }
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('archived_transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'bp') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $trans[$key] = $this->db->get('archived_transactions')->result();
                    $voucher_no = $this->get_voucher_no($type, $level_id, 'bp');

                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        if (isset($data->DepartmentId)) {
                            $this->db->set('DepartmentId', $data->DepartmentId);
                        }
                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);
                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        if (isset($data->Description)) {
                            $this->db->set('Description', $data->Description);
                        }
                        if (isset($data->Debit)) {
                            $this->db->set('Debit', $data->Debit);
                        }
                        if (isset($data->Credit)) {
                            $this->db->set('Credit', $data->Credit);
                        }
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('archived_transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            } elseif ($type == 'jv') {
                $trans[] = '';
                $seqno = 1;
                foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                    $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                    $this->db->where('VoucherType', $type);
                    $this->db->where('LevelID', $level_id);
                    $this->db->where("is_delete",'0');
                    $trans[$key] = $this->db->get('archived_transactions')->result();
                    $voucher_no = $this->get_voucher_no($type, $level_id, 'jv');

                    foreach ($trans[$key] as $key => $data) {
                        $this->db->set('SequenceNo', $seqno);
                        $this->db->set('LevelID', $data->LevelID);
                        $this->db->set('AccountID', $data->AccountID);
                        $this->db->set('LinkID', $data->LinkID);
                        if (isset($data->DepartmentId)) {
                            $this->db->set('DepartmentId', $data->DepartmentId);
                        }

                        $this->db->set('VoucherType', $data->VoucherType);
                        $this->db->set('VoucherNo', $voucher_no);
                        $this->db->set('Remarks', $data->Remarks);
                        $this->db->set('PaidTo', $data->PaidTo);
                        $this->db->set('VoucherDateG', $data->VoucherDateG);

                        $this->db->set('VoucherDateH', $data->VoucherDateH);
                        $this->db->set('ChequeNumber', $data->ChequeNumber);
                        $this->db->set('ChequeDate', $data->ChequeDate);
                        if (isset($data->Description)) {
                            $this->db->set('Description', $data->Description);
                        }
                        if (isset($data->Debit)) {
                            $this->db->set('Debit', $data->Debit);
                        }
                        if (isset($data->Credit)) {
                            $this->db->set('Credit', $data->Credit);
                        }
                        $this->db->set('Createdby', $_SESSION['user'][0]->id);
                        $this->db->set('CreatedOn', date('Y-m-d H:i:s'));
                        $this->db->set('count', 1);
                        $this->db->insert('archived_transactions');
                        $seqno++;
                    }
                }
                if ($this->db->affected_rows() > 0) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    public function get_permanent_trans_bydate($from,$book_type, $id, $to)
    {

        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit) as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo,transactions.LevelId, departments.DepartmentName,transactions.Seprate_series_num, transactions.Remarks');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'company_structure.id = transactions.LevelID','left');
            $this->db->join('account_title', 'account_title.id = transactions.AccountID','left');
            $this->db->join('departments', 'departments.id = transactions.DepartmentId','left');
            $array = array('transactions.VoucherType' => $book_type, 'transactions.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            $this->db->group_by('transactions.VoucherNo');
        } else {
            $this->db->select('SUM(archived_transactions.Debit) as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.Permanent_VoucherDateG, archived_transactions.Permanent_VoucherDateH,archived_transactions.Seprate_series_num, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks,
                archived_transactions.LevelId'
        );
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID','left');
            $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID','left');
            $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId','left');
            $array = array('archived_transactions.VoucherType' => $book_type, 'archived_transactions.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $this->db->group_by('archived_transactions.VoucherNo');
        }
        return $this->db->get()->result();
    }

    public function get_permanent_trans_by_dateAndAccountCode($type = '', $id = '')
    {
        $to = $_POST['to'];
        $from = $_POST['from'];
        $account_code = $_POST['AccountCode'];
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $account_code);
        $acc_code = $this->db->get('account_title')->result();
        if (!empty($acc_code)) {
            foreach ($acc_code as $ac_c) {
                 
        if($this->year_status->Active == 1) {
                    $this->db->select('SUM(transactions.Debit) as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
                    $this->db->from('transactions');
                    $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                    $this->db->join('departments', 'departments.id = transactions.DepartmentId');
                    $this->db->where('transactions.AccountID', $ac_c->id);
                    $this->db->where('transactions.LevelID', $id);
                    $this->db->where('transactions.voucherType', $type);
                    $this->db->where('transactions.Permanent_VoucherDateG >=', $to);
                    $this->db->where('transactions.Permanent_VoucherDateG <=', $from);
                    $this->db->like('transactions.Permanent_VoucherDateG', $this->session->userdata('current_year'));
                } else {
                    $this->db->select('SUM(archived_transactions.Debit) as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.Permanent_VoucherDateG, archived_transactions.Permanent_VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
                    $this->db->from('archived_transactions');
                    $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                    $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
                    $this->db->where('archived_transactions.AccountID', $ac_c->id);
                    $this->db->where('archived_transactions.LevelID', $id);
                    $this->db->where('archived_transactions.voucherType', $type);
                    $this->db->where('archived_transactions.Permanent_VoucherDateG >=', $to);
                    $this->db->where('archived_transactions.Permanent_VoucherDateG <=', $from);
                    // $this->db->where('archived_transactions.Year', $this->activeyear);
                    $this->db->like('archived_transactions.Permanent_VoucherDateG', $this->session->userdata('current_year'));
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        } else {
            return null;
        }
    }

    public function get_per_trans_by_account_code($code, $type, $id)
    {
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $code);
        $acc_code = $this->db->get('account_title')->result();
        if (!empty($acc_code)) {
            foreach ($acc_code as $ac_c) {
                 
        if($this->year_status->Active == 1) {
                    $this->db->select('SUM(transactions.Debit) as debit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks');
                    $this->db->from('transactions');
                    $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                    $this->db->join('departments', 'departments.id = transactions.DepartmentId');
                    $this->db->where('transactions.AccountID', $ac_c->id);
                    //$this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
                    $this->db->where('transactions.LevelID', $id);
                    $this->db->where('transactions.VoucherType', $type);
                } else {
                    $this->db->select('SUM(archived_transactions.Debit) as debit,archived_transactions.Id as t_id,archived_transactions.VoucherType, archived_transactions.Permanent_VoucherNumber, archived_transactions.VoucherNo, archived_transactions.Permanent_VoucherDateG, archived_transactions.Permanent_VoucherDateH, archived_transactions.PaidTo, departments.DepartmentName, archived_transactions.Remarks');
                    $this->db->from('archived_transactions');
                    $this->db->join('company_structure', 'company_structure.id = archived_transactions.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                    $this->db->join('departments', 'departments.id = archived_transactions.DepartmentId');
                    $this->db->where('archived_transactions.AccountID', $ac_c->id);
                    //$this->db->where('archived_transactions.Permanent_VoucherNumber !=', NULL);
                    $this->db->where('archived_transactions.LevelID', $id);
                    $this->db->where('archived_transactions.VoucherType', $type);
                    // $this->db->where('archived_transactions.Year', $this->activeyear);
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        } else {
            return null;
        }
    }

    public function get_level($id)
    {
        $this->db->select('LevelID');
        $this->db->where('Id', $id);
         
        if($this->year_status->Active == 1) {
            return $this->db->get('transactions')->result();
        } else {
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }

    public function get_voucher_details($tran_id, $level_id)
    {

        // $result1 = $this->db->query('SELECT year from closing_year')->result();
        // $previous_year_date = $result1[0]->year;
        // $hijri_date = $this->get_hijri_date($date);
        $this->db->select('VoucherNo,VoucherType,LevelID,Seprate_series_num');
        $this->db->where('id', $tran_id);
        $this->db->where('LevelID', $level_id);
        $this->db->where('is_delete', '0');
        if($this->year_status->Active == 1) {
           // $this->db->where('transactions.Year', $this->activeyear);
            return $this->db->get('transactions')->result();
        } else {
           // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }
    public function get_voucher($vouch_no, $vouch_type, $level_id, $Seprate_series_num,$is_permit='')
    {

        // $this->db->select('CASE WHEN transactions.Debit > 0 THEN 1 ELSE 2 END AS amounttype,transactions.ChequeNumber,transactions.ChequeDate,company_structure.ParentCode as Comp_Parent,company_structure.LevelName,transactions.PaidTo,transactions.Remarks,transactions.Description,transactions.Debit,transactions.Credit,
        // transactions.Permanent_VoucherNumber,transactions.Permanent_VoucherDateH,transactions.Permanent_VoucherDateG,a.AccountName,transactions.Createdby,
        // transactions.CreatedOn,transactions.UpdatedBy,transactions.UpdatedOn,a.Type,transactions.count,departments.DepartmentName,a.AccountCode,
        // transactions.VoucherType,transactions.VoucherNo,transactions.VoucherDateH,transactions.VoucherDateG,`b`.`AccountName` as `ParentName` ');
        // $this->db->from('transactions');
        // $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
        // $this->db->join('account_title a ', 'a.id = transactions.AccountID');
        // $this->db->join('departments', 'departments.id = transactions.DepartmentId','left');
        // $this->db->join('account_title b', 'b.AccountCode = a.ParentCode');
        // $this->db->where('transactions.VoucherNo', $vouch_no);
        // //$this->db->where('transactions.Permanent_VoucherNumber', NULL);
        // $this->db->where('transactions.LevelID', $level_id);
        // $this->db->where('transactions.VoucherType', $vouch_type);
        // //$this->db->order_by('amounttype');
        // $this->db->order_by('transactions.SequenceNo');
        // return $this->db->get()->result();
        // $result1 = $this->db->query('SELECT year from closing_year')->result();
        // $previous_year_date = $result1[0]->year;
        // $hijri_date = $this->get_hijri_date($date);
        // print_r($hijri_date);
        //exit();
        // echo $is_permit;
        // exit()
        $where = '';
        if($is_permit == ''){
            $where .= 'AND Permanent_VoucherNumber IS NULL';
            // echo 'true';
        }
        else{
           $where =  'and Permanent_VoucherNumber = '.$is_permit.'';
           // echo 'false'.$is_permit;
        }
        // exit();
        if($this->year_status->Active == 1) {
            $query = $this->db->query("SELECT 
            CASE WHEN transactions.Debit > 0 THEN 1 ELSE 2 END AS amounttype,
            `transactions`.`ChequeNumber`, `transactions`.`ChequeDate`, 
            `company_structure`.`ParentCode` as `Comp_Parent`, `company_structure`.`LevelName`, 
            `transactions`.`PaidTo`, `transactions`.`Remarks`, `transactions`.`Description`, `transactions`.`Debit`, 
            `transactions`.`Credit`, `transactions`.`Permanent_VoucherNumber`, `transactions`.`Permanent_VoucherDateH`, 
            `transactions`.`Permanent_VoucherDateG`, `a`.`AccountName`, `transactions`.`Createdby`, `transactions`.`CreatedOn`, 
            `transactions`.`UpdatedBy`, `transactions`.`UpdatedOn`, `a`.`Type`, `transactions`.`count`, 
            `departments`.`DepartmentName`, `a`.`AccountCode`, `transactions`.`VoucherType`, `transactions`.`VoucherNo`, 
            `transactions`.`VoucherDateH`, `transactions`.`VoucherDateG`, 
            `b`.`AccountName` as `ParentName` FROM `transactions` JOIN `company_structure` 
            ON `company_structure`.`id` = `transactions`.`LevelID` JOIN `account_title` `a` 
            ON `a`.`id` = `transactions`.`AccountID` LEFT JOIN `departments` 
            ON `departments`.`id` = `transactions`.`DepartmentId` JOIN `account_title` `b` 
            ON `b`.`AccountCode` = `a`.`ParentCode` WHERE 
            `transactions`.`VoucherNo` = '$vouch_no' AND `transactions`.`LevelID` = '$level_id' 
            AND `transactions`.`VoucherType` = '$vouch_type' 
            ".$where."
            AND `transactions`.`Seprate_series_num` =  '" . $Seprate_series_num . "' AND transactions.is_delete = 0 ORDER BY `amounttype` , transactions.SequenceNo");
        } else {
            $query = $this->db->query("SELECT 
            CASE WHEN archived_transactions.Debit > 0 THEN 1 ELSE 2 END AS amounttype,
            `archived_transactions`.`ChequeNumber`, `archived_transactions`.`ChequeDate`, `company_structure`.`ParentCode` as `Comp_Parent`, `company_structure`.`LevelName`, `archived_transactions`.`PaidTo`, `archived_transactions`.`Remarks`, `archived_transactions`.`Description`, `archived_transactions`.`Debit`, `archived_transactions`.`Credit`, `archived_transactions`.`Permanent_VoucherNumber`, `archived_transactions`.`Permanent_VoucherDateH`, `archived_transactions`.`Permanent_VoucherDateG`, `a`.`AccountName`, `archived_transactions`.`Createdby`, `archived_transactions`.`CreatedOn`, `archived_transactions`.`UpdatedBy`, `archived_transactions`.`UpdatedOn`, `a`.`Type`,`departments`.`DepartmentName`, `a`.`AccountCode`, `archived_transactions`.`VoucherType`, `archived_transactions`.`VoucherNo`, `archived_transactions`.`VoucherDateH`, `archived_transactions`.`VoucherDateG`, `b`.`AccountName` as `ParentName` FROM `archived_transactions` JOIN `company_structure` ON `company_structure`.`id` = `archived_transactions`.`LevelID` JOIN `account_title` `a` ON `a`.`id` = `archived_transactions`.`AccountID` LEFT JOIN `departments` ON `departments`.`id` = `archived_transactions`.`DepartmentId` JOIN `account_title` `b` ON `b`.`AccountCode` = `a`.`ParentCode` WHERE `archived_transactions`.`VoucherNo` = '$vouch_no' AND `archived_transactions`.`LevelID` = '$level_id' AND `archived_transactions`.`VoucherType` = '$vouch_type' 
".$where."
            AND
                `archived_transactions`.`Seprate_series_num` = '" . $Seprate_series_num . "'
                AND `archived_transactions`.is_delete = 0 ORDER BY `amounttype` ,
                `archived_transactions`.`SequenceNo`
             ");
        }
        return $query->result();
    }
 

    public function get_book_Amount($voucherType, $VoucherNumber, $level, $Seprate_series_num,$permit = '')
    {
        if($this->year_status->Active == 1) {
            if ($voucherType == 'BR' || $voucherType == 'CR') {
                $this->db->select('transactions.Debit as BookAmount');                
            } elseif ($voucherType == 'BP' || $voucherType == 'CP') {
                $this->db->select('transactions.Credit as BookAmount');
            }
            $this->db->join('account_title', 'transactions.AccountID = account_title.id');
            if ($voucherType == 'BP' || $voucherType == 'BR') {
                $this->db->where('account_title.Type', 2);
            } elseif ($voucherType == 'CP' || $voucherType == 'CR') {
                $this->db->where('account_title.Type', 1);
            }
            $this->db->where('transactions.LevelID', $level);
            $this->db->where('transactions.VoucherType', $voucherType);
            $this->db->where('transactions.VoucherNo', $VoucherNumber);
            $this->db->where('transactions.is_delete', '0');
            $this->db->where('transactions.Seprate_series_num', $Seprate_series_num);
            if($permit == ''){
                $where = '`transactions`.`Permanent_VoucherNumber` IS NOT NULL';
                $this->db->where($where);
            }
            else{
                $where = '`transactions`.`Permanent_VoucherNumber` IS NULL';
                $this->db->where($where);
            }
           return  $this->db->get('transactions')->result(); 
        }
        else{
            if ($voucherType == 'BR' || $voucherType == 'CR') {
                $this->db->select('archived_transactions.Debit as BookAmount');
                $this->db->where('archived_transactions.is_delete', '0');
            } elseif ($voucherType == 'BP' || $voucherType == 'CP') {
                $this->db->select('archived_transactions.Credit as BookAmount');
            }
            $this->db->join('account_title', 'archived_transactions.AccountID = account_title.id');
            if ($voucherType == 'BP' || $voucherType == 'BR') {
                $this->db->where('account_title.Type', 2);
            } elseif ($voucherType == 'CP' || $voucherType == 'CR') {
                $this->db->where('account_title.Type', 1);
            }
            $this->db->where('archived_transactions.LevelID', $level);
            $this->db->where('archived_transactions.is_delete', '0');
            $this->db->where('archived_transactions.VoucherType', $voucherType);
            $this->db->where('archived_transactions.VoucherNo', $VoucherNumber);
            $this->db->where('archived_transactions.Seprate_series_num', $Seprate_series_num);
            if($permit == ''){
                $where = '`archived_transactions`.`Permanent_VoucherNumber` IS NOT NULL';
                $this->db->where($where); 
            }
            else{
                $where = '`archived_transactions`.`Permanent_VoucherNumber` IS NULL';
                $this->db->where($where);
            }    
          return  $this->db->get('archived_transactions')->result();
       }
   }

    public function get_transaction_ledger($chart_id, $to, $from, $ledgerof, $voucher_type)
    {
        if($this->year_status->Active == 1) {
            $this->db->select('company_structure.LevelName,transactions.LevelID,transactions.AccountID,transactions.VoucherNo,transactions.Permanent_VoucherNumber,transactions.VoucherType, transactions.VoucherDateG,transactions.Permanent_VoucherDateG, transactions.VoucherDateH,transactions.Permanent_VoucherDateH,transactions.Remarks, 
                transactions.Debit as Debit,transactions.Credit Credit');
            $this->db->from('transactions');
            $this->db->join('company_structure', 'transactions.LevelID = company_structure.id');
            $this->db->where_in('transactions.LinkID', $chart_id);
            $this->db->where('transactions.is_delete', '0');
            if ($ledgerof == 'p') {
                $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            if ($voucher_type == 'CP') {
                $this->db->where("transactions.VoucherType", "CP");
            } elseif ($voucher_type == 'CR') {
                $this->db->where("transactions.VoucherType", "CR");
            } elseif ($voucher_type == 'BR') {
                $this->db->where("transactions.VoucherType", "BR");
            } elseif ($voucher_type == 'BP') {
                $this->db->where("transactions.VoucherType", "BP");
            } elseif ($voucher_type == 'JV') {
                $this->db->where("transactions.VoucherType", "JV");
            } elseif ($voucher_type == 'IC') {
                $this->db->where("transactions.VoucherType", "IC");
            }
            $this->db->order_by("transactions.Permanent_VoucherNumber");
            //$this->db->group_by('transactions.permanent_vouchernumber');
            $trans_query = $this->db->get();
            // $this->db->select('company_structure.LevelName,income.LevelID,income.AccountID,income.VoucherNo,income.Permanent_VoucherNumber, income.VoucherType, income.VoucherDateG,income.Permanent_VoucherDateG, income.VoucherDateH,income.Permanent_VoucherDateH, income.Remarks, 
            //     sum(income.Debit) as Debit, sum(income.Credit) as Credit');
            // $this->db->from('income');
            // $this->db->join('company_structure', 'income.LevelID = company_structure.id');
            // $this->db->where('income.is_delete', '0');
            // $this->db->where_in('income.LinkID', $chart_id);
            // if ($ledgerof == 'p') {
            //     $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            // } else {
            //     $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            // }
            // $this->db->group_by('income.Permanent_VoucherNumber');
            // $income_query = $this->db->get_compiled_select();

            // if ($ledgerof == 'p') {
            //     $query = $this->db->query('Select `LevelID`,LevelName, `AccountID`, `VoucherNo`, `Permanent_VoucherNumber`, `VoucherType`, `VoucherDateG`, `Permanent_VoucherDateG`, `VoucherDateH`, `Permanent_VoucherDateH`, `Remarks`, `Debit`, `Credit` From(' . $trans_query . ' UNION ' . $income_query . ') as t ORDER by Permanent_VoucherDateG,Permanent_VoucherNumber,VoucherType ASC');
            // } else {
            //     $query = $this->db->query('Select `LevelID`,LevelName, `AccountID`, `VoucherNo`, `Permanent_VoucherNumber`, `VoucherType`, `VoucherDateG`, `Permanent_VoucherDateG`, `VoucherDateH`, `Permanent_VoucherDateH`, `Remarks`, `Debit`, `Credit` From(' . $trans_query . ' UNION ' . $income_query . ') as t ORDER by Permanent_VoucherDateG,Permanent_VoucherNumber,VoucherDateG,VoucherNo,VoucherType ASC');
            // }
            //$query = $this->db->query('Select `LevelID`,LevelName, `AccountID`, `VoucherNo`, `Permanent_VoucherNumber`, `VoucherType`, `VoucherDateG`, `Permanent_VoucherDateG`, `VoucherDateH`, `Permanent_VoucherDateH`, `Remarks`, `Debit`, `Credit` From(' . $trans_query . ' UNION ' . $income_query . ') as t ORDER by VoucherDateG,Permanent_VoucherNumber,VoucherType ASC');
            $result[] = $trans_query->result();
//            }
        } else {
            $this->db->select('company_structure.LevelName,archived_transactions.LevelID,archived_transactions.AccountID,
            archived_transactions.VoucherNo,archived_transactions.Permanent_VoucherNumber,archived_transactions.VoucherType, 
            archived_transactions.VoucherDateG,archived_transactions.Permanent_VoucherDateG, archived_transactions.VoucherDateH,
            archived_transactions.Permanent_VoucherDateH,archived_transactions.Remarks, 
            archived_transactions.Debit as Debit,archived_transactions.Credit as Credit');
            $this->db->from('archived_transactions');
            $this->db->join('company_structure', 'archived_transactions.LevelID = company_structure.id');
            $this->db->where_in('archived_transactions.LinkID', $chart_id);
            $this->db->where('archived_transactions.is_delete', '0');
            if ($ledgerof == 'p') {
                $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            if ($voucher_type == 'CP') {
                $this->db->where("archived_transactions.VoucherType", "CP");
            } elseif ($voucher_type == 'CR') {
                $this->db->where("archived_transactions.VoucherType", "CR");
            } elseif ($voucher_type == 'BR') {
                $this->db->where("archived_transactions.VoucherType", "BR");
            } elseif ($voucher_type == 'BP') {
                $this->db->where("archived_transactions.VoucherType", "BP");
            } elseif ($voucher_type == 'JV') {
                $this->db->where("archived_transactions.VoucherType", "JV");
            } elseif ($voucher_type == 'IC') {
                $this->db->where("archived_transactions.VoucherType", "IC");
            }
           // $this->db->group_by('archived_transactions.Permanent_VoucherNumber');
            $this->db->order_by("archived_transactions.Permanent_VoucherNumber");

            $trans_query = $this->db->get();
            // $this->db->select('company_structure.LevelName,archived_income.LevelID,archived_income.AccountID,archived_income.VoucherNo,
            // archived_income.Permanent_VoucherNumber, archived_income.VoucherType, archived_income.VoucherDateG,
            // archived_income.Permanent_VoucherDateG, archived_income.VoucherDateH,archived_income.Permanent_VoucherDateH, 
            // archived_income.Remarks, sum(archived_income.Debit) as Debit, sum(archived_income.Credit) as Credit');
            // $this->db->from('archived_income');
            // $this->db->join('company_structure', 'archived_income.LevelID = company_structure.id');
            // $this->db->where('archived_income.is_delete', '0');
            // $this->db->where_in('archived_income.LinkID', $chart_id);
            // if ($ledgerof == 'p') {
            //     $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            // } else {
            //     $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            // }
            // $this->db->group_by('archived_income.Permanent_VoucherNumber');
            // $income_query = $this->db->get_compiled_select();
            // if ($ledgerof == 'p') {
            //     $query = $this->db->query('Select `LevelID`,LevelName, `AccountID`, `VoucherNo`, `Permanent_VoucherNumber`, `VoucherType`, `VoucherDateG`, `Permanent_VoucherDateG`, `VoucherDateH`, `Permanent_VoucherDateH`, `Remarks`, `Debit`, `Credit` From(' . $trans_query . ' UNION ' . $income_query . ') as t ORDER by Permanent_VoucherDateG,Permanent_VoucherNumber,VoucherType ASC');
            // } else {
            //     $query = $this->db->query('Select `LevelID`,LevelName, `AccountID`, `VoucherNo`, `Permanent_VoucherNumber`, `VoucherType`, `VoucherDateG`, `Permanent_VoucherDateG`, `VoucherDateH`, `Permanent_VoucherDateH`, `Remarks`, `Debit`, `Credit` From(' . $trans_query . ' UNION ' . $income_query . ') as t ORDER by Permanent_VoucherDateG,Permanent_VoucherNumber,VoucherDateG,VoucherNo,VoucherType ASC');
            // }
            //$query = $this->db->query('Select `LevelID`,LevelName, `AccountID`, `VoucherNo`, `Permanent_VoucherNumber`, `VoucherType`, `VoucherDateG`, `Permanent_VoucherDateG`, `VoucherDateH`, `Permanent_VoucherDateH`, `Remarks`, `Debit`, `Credit` From(' . $trans_query . ' UNION ' . $income_query . ') as t ORDER by VoucherDateG,Permanent_VoucherNumber,VoucherType ASC');
            $result[] = $trans_query->result();
//            }
        }
        return $result;
    }

//    public function get_transaction_ledger_Con($chart_id, $to, $from, $ledgerof, $b_level = '')
//    {
//        $this->db->join('company_structure','transactions.LevelID = company_structure.id');
//        $this->db->where_in('LinkID',$chart_id);
//        if ($ledgerof == 'p') {
//            $this->db->where("Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
//            $this->db->order_by('Permanent_VoucherDateG', 'ASC');
//        } else {
//            $this->db->where("VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
//            $this->db->order_by('VoucherDateG', 'ASC');
//        }
//        $this->db->order_by('AccountID', 'ASC');
//
//        $result['transactions'] = $this->db->get('transactions')->result();
//        return $result;
//    }

    public function get_sum_debit_credit_tillDate($chart_id, $to, $ledgerof, $voucher_type)
    {

        if($this->year_status->Active == 1) {
            $this->db->select('SUM(Debit) as tdebit, SUM(Credit) as tcredit');
            $this->db->from('transactions');
            $this->db->where('transactions.is_delete', '0');
            $this->db->where_in('LinkID', $chart_id);
            if ($ledgerof == 'p') {
                $this->db->where("`Permanent_VoucherDateG` <", $to);
            } else {
                $this->db->where("transactions.VoucherDateG <", $to);
            }
            if ($voucher_type == 'CP') {
                $this->db->where("transactions.VoucherType", "CP");
            } elseif ($voucher_type == 'CR') {
                $this->db->where("transactions.VoucherType", "CR");
            } elseif ($voucher_type == 'BR') {
                $this->db->where("transactions.VoucherType", "BR");
            } elseif ($voucher_type == 'BP') {
                $this->db->where("transactions.VoucherType", "BP");
            } elseif ($voucher_type == 'JV') {
                $this->db->where("transactions.VoucherType", "JV");
            } elseif ($voucher_type == 'IC') {
                $this->db->where("transactions.VoucherType", "IC");
            }
            $result = $this->db->get()->result();
            return $result;
//            }
        } else {
            $this->db->select('SUM(Debit) as tdebit, SUM(Credit) as tcredit');
            $this->db->from('archived_transactions');
            $this->db->where('archived_transactions.is_delete', '0');
            $this->db->where_in('LinkID', $chart_id);
            if ($ledgerof == 'p') {
                $this->db->where("`Permanent_VoucherDateG` <", $to);
            } else {
                $this->db->where("archived_transactions.VoucherDateG <", $to);
            }
            if ($voucher_type == 'CP') {
                $this->db->where("archived_transactions.VoucherType", "CP");
            } elseif ($voucher_type == 'CR') {
                $this->db->where("archived_transactions.VoucherType", "CR");
            } elseif ($voucher_type == 'BR') {
                $this->db->where("archived_transactions.VoucherType", "BR");
            } elseif ($voucher_type == 'BP') {
                $this->db->where("archived_transactions.VoucherType", "BP");
            } elseif ($voucher_type == 'JV') {
                $this->db->where("archived_transactions.VoucherType", "JV");
            } elseif ($voucher_type == 'IC') {
                $this->db->where("archived_transactions.VoucherType", "IC");
            }
            $result = $this->db->get()->result();
            return $result;
        }
    }
    public function getTransactionsForAudit($v_rype, $auditOf, $to, $from, $level)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('transactions.VoucherType,transactions.Permanent_VoucherNumber,transactions.VoucherNo');
            $this->db->from('transactions');
            $this->db->where('LevelID', $level);
            if ($auditOf == 'p') {
                $this->db->where('transactions.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('transactions.Permanent_VoucherNumber', null);
                $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            if ($v_rype != 'all') {
                $this->db->where('transactions.VoucherType', $v_rype);
            }
            $trans_query = $this->db->get_compiled_select();

            $this->db->select('income.VoucherType,income.Permanent_VoucherNumber,income.VoucherNo');
            $this->db->from('income');
            $this->db->where('LevelID', $level);
            if ($auditOf == 'p') {
                $this->db->where('income.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('income.Permanent_VoucherNumber', null);
                $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            if ($v_rype != 'all') {
                $this->db->where('income.VoucherType', $v_rype);
            }
            $income_query = $this->db->get_compiled_select();

            $query = $this->db->query('Select Permanent_VoucherNumber,VoucherNo,VoucherType From(' . $trans_query . ' UNION ' . $income_query . ')' . 'as t');
        } else {
            $this->db->select('archived_transactions.VoucherType,archived_transactions.Permanent_VoucherNumber,archived_transactions.VoucherNo');
            $this->db->from('archived_transactions');
            $this->db->where('LevelID', $level);
            if ($auditOf == 'p') {
                $this->db->where('archived_transactions.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('archived_transactions.Permanent_VoucherNumber', null);
                $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            if ($v_rype != 'all') {
                $this->db->where('archived_transactions.VoucherType', $v_rype);
            }
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $trans_query = $this->db->get_compiled_select();

            $this->db->select('archived_income.VoucherType,archived_income.Permanent_VoucherNumber,archived_income.VoucherNo');
            $this->db->from('archived_income');
            $this->db->where('LevelID', $level);
            if ($auditOf == 'p') {
                $this->db->where('archived_income.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('archived_income.Permanent_VoucherNumber', null);
                $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            if ($v_rype != 'all') {
                $this->db->where('archived_income.VoucherType', $v_rype);
            }
            // $this->db->where('archived_income.Year', $this->activeyear);
            $income_query = $this->db->get_compiled_select();

            $query = $this->db->query('Select Permanent_VoucherNumber,VoucherNo,VoucherType From(' . $trans_query . ' UNION ' . $income_query . ')' . 'as t');
        }

        return $query->result();
    }

    public function getTransactionsDataForAudit($v_rype, $v_number, $auditOf, $to, $from, $level)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('Debit,Credit,VoucherNo,Permanent_VoucherNumber,VoucherType,Permanent_VoucherDateG,Permanent_VoucherDateH,VoucherDateG,VoucherDateH,ChequeDate,account_title.AccountName,ChequeNumber,Description,Remarks');
            $this->db->from('transactions');
            $this->db->join('`account_title`', '`transactions`.`AccountID` = `account_title`.`id`');
            $this->db->where('transactions.LevelID', $level);
            $this->db->where('transactions.VoucherType', $v_rype);
            if ($auditOf == 'p') {
                $this->db->where('transactions.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where('transactions.Permanent_VoucherNumber', $v_number);
                $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('transactions.Permanent_VoucherNumber', null);
                $this->db->where('transactions.VoucherNo', $v_number);
                $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where('transactions.VoucherNo', $v_number);
                $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            $trans_query = $this->db->get_compiled_select();

            $this->db->select('Debit,Credit,VoucherNo,Permanent_VoucherNumber,VoucherType,Permanent_VoucherDateG,Permanent_VoucherDateH,VoucherDateG,VoucherDateH,ChequeDate,account_title.AccountName,ChequeNumber,Description,Remarks');
            $this->db->from('income');
            $this->db->join('`account_title`', '`income`.`AccountID` = `account_title`.`id`');
            $this->db->where('income.LevelID', $level);
            $this->db->where('income.VoucherType', $v_rype);
            if ($auditOf == 'p') {
                $this->db->where('income.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where('income.Permanent_VoucherNumber', $v_number);
                $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('income.Permanent_VoucherNumber', null);
                $this->db->where('income.VoucherNo', $v_number);
                $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where('income.VoucherNo', $v_number);
                $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            $incom_query = $this->db->get_compiled_select();

            $query = $this->db->query('Select Debit,Credit,Permanent_VoucherNumber,VoucherNo,Permanent_VoucherNumber,VoucherType,Permanent_VoucherDateG,Permanent_VoucherDateH,VoucherDateG,VoucherDateH,ChequeDate,AccountName,ChequeNumber,Description,Remarks From(' . $trans_query . ' UNION ' . $incom_query . ')' . 'as t ORDER BY VoucherNo,VoucherDateG');
        } else {
            $this->db->select('Debit,Credit,VoucherNo,Permanent_VoucherNumber,VoucherType,Permanent_VoucherDateG,Permanent_VoucherDateH,VoucherDateG,VoucherDateH,ChequeDate,account_title.AccountName,ChequeNumber,Description,Remarks');
            $this->db->from('archived_transactions');
            $this->db->join('`account_title`', '`archived_transactions`.`AccountID` = `account_title`.`id`');
            $this->db->where('archived_transactions.LevelID', $level);
            $this->db->where('archived_transactions.VoucherType', $v_rype);
            if ($auditOf == 'p') {
                $this->db->where('archived_transactions.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where('archived_transactions.Permanent_VoucherNumber', $v_number);
                $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('archived_transactions.Permanent_VoucherNumber', null);
                $this->db->where('archived_transactions.VoucherNo', $v_number);
                $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where('archived_transactions.VoucherNo', $v_number);
                $this->db->where("archived_transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $trans_query = $this->db->get_compiled_select();

            $this->db->select('Debit,Credit,VoucherNo,Permanent_VoucherNumber,VoucherType,Permanent_VoucherDateG,Permanent_VoucherDateH,VoucherDateG,VoucherDateH,ChequeDate,account_title.AccountName,ChequeNumber,Description,Remarks');
            $this->db->from('archived_income');
            $this->db->join('`account_title`', '`archived_income`.`AccountID` = `account_title`.`id`');
            $this->db->where('archived_income.LevelID', $level);
            $this->db->where('archived_income.VoucherType', $v_rype);
            if ($auditOf == 'p') {
                $this->db->where('archived_income.Permanent_VoucherNumber IS NOT NULL');
                $this->db->where('archived_income.Permanent_VoucherNumber', $v_number);
                $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } elseif ($auditOf == 't') {
                $this->db->where('archived_income.Permanent_VoucherNumber', null);
                $this->db->where('archived_income.VoucherNo', $v_number);
                $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            } else {
                $this->db->where('archived_income.VoucherNo', $v_number);
                $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
            }
            // $this->db->where('archived_income.Year', $this->activeyear);
            $incom_query = $this->db->get_compiled_select();

            $query = $this->db->query('Select Debit,Credit,Permanent_VoucherNumber,VoucherNo,Permanent_VoucherNumber,VoucherType,Permanent_VoucherDateG,Permanent_VoucherDateH,VoucherDateG,VoucherDateH,ChequeDate,AccountName,ChequeNumber,Description,Remarks From(' . $trans_query . ' UNION ' . $incom_query . ')' . 'as t ORDER BY VoucherNo,VoucherDateG');
        }
        return $query->result();
    }

    public function getTrailBalance_post($accounts, $account_level, $level_id, $tb_of, $to, $from, $is_cons = '')
    {



        $childstransactions_d_c = array();
        $childstransactions_balances = array();
        $transactions = array();
        if($this->year_status->Active == 1) {
            foreach ($accounts as $account) {
                $this->db->select('*');
                $this->db->from('account_title');
                if ($account_level == 'detail') {
                    $this->db->where('Category', '2');
                    $this->db->like('ParentCode', $account, 'after');
                } elseif ($account_level == '1') {
                    $this->db->where('AccountCode', $account);
                } else {
                    $this->db->like('ParentCode', $account, 'after');
                    $this->db->where('LENGTH(AccountCode)', $account_level);
                }
                $this->db->order_by('AccountCode', 'asc');
                $results[] = $this->db->get()->result();  // $results has accounts of level 1
            }
            foreach ($results as $key_1 => $result) {
                if ($result != array()) {
                    foreach ($result as $key_2 => $res) {
                        if ($res->Category == 2) {
                            $this->db->select('IFNULL(SUM(chart_of_account_years.OpeningBalance),0) as OpeningBalance');
                            $this->db->from('chart_of_account_years');
                            $this->db->join('chart_of_account', 'chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                            $this->db->where('chart_of_account.AccountId', $res->id);
                            if ($is_cons != '') {
                                $this->db->where_in('chart_of_account.LevelId', $level_id);
                            } else {
                                $this->db->where('chart_of_account.LevelId', $level_id);
                            }
                            $opening_balance = $this->db->get()->result();

                            if ($opening_balance != array()) {
                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('transactions');
                                $this->db->join('account_title', 'transactions.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('transactions.LevelID', $level_id);
                                }
                                $this->db->where('transactions.AccountID', $res->id);
                                $this->db->where('transactions.is_delete','0');
                                $this->db->where('transactions.VoucherDateG <', $from);
                                $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();
                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('income');
                                $this->db->join('account_title', 'income.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('income.LevelID', $level_id);
                                } else {
                                    $this->db->where('income.LevelID', $level_id);
                                }
                                $this->db->where('income.AccountID', $res->id);
                                $this->db->where('income.is_delete','0');
                                $this->db->where('income.VoucherDateG < ', $from);
                                $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                /**
                                 * Calculating Opening Balance with past Debit Credit Values
                                 */

                                $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                /** */
                                $childstransactions_balances[$key_1][$key_2] = '';
                                $childstransactions_balances[$key_1][$key_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                $childstransactions_balances[$key_1][$key_2][0]->id = $deb_cre_fr_balance[0]->id;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                $childstransactions_balances[$key_1][$key_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                $childstransactions_balances[$key_1][$key_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                $childstransactions_balances[$key_1][$key_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                $childstransactions_balances[$key_1][$key_2][0]->Balance = $balance;

                                $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(transactions.Debit),0) as Debit,IFNULL(SUM(transactions.Credit),0) as Credit');
                                $this->db->from('transactions');
                                $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                                $this->db->where('transactions.AccountID', $res->id);
                                $this->db->where('transactions.is_delete','0');
                                if ($is_cons != '') {
                                    $this->db->where_in('transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('transactions.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                }
                                $query1_1 = $this->db->get_compiled_select();

                                $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`income`.Debit),0) as Debit,IFNULL(SUM(`income`.Credit),0) as Credit');
                                $this->db->from('income');
                                $this->db->join('account_title', 'account_title.id = income.AccountID');
                                $this->db->where('income.AccountID', $res->id);
                                $this->db->where('income.is_delete','0');
                                if ($is_cons != '') {
                                    $this->db->where_in('income.LevelID', $level_id);
                                } else {
                                    $this->db->where('income.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                }
                                $query2_1 = $this->db->get_compiled_select();

                                $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                $childstransactions_d_c[$key_1][$key_2] = $query_1->result();
                            }
                        } else {
                            $this->db->select('*');
                            $this->db->from('account_title');
                            $this->db->like('AccountCode', $res->AccountCode, 'after');
                            $this->db->where('Category', '2');
                            $accs[] = $this->db->get()->result();

                            foreach ($accs as $akey_1 => $acc) {
                                foreach ($acc as $akey_2 => $aitem) {
                                    $this->db->select('IFNULL(SUM(chart_of_account_years.OpeningBalance),0) as OpeningBalance');
                                    $this->db->from('chart_of_account_years');
                                    $this->db->join('chart_of_account', 'chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                                    $this->db->where('chart_of_account.AccountId', $aitem->id);
                                    if ($is_cons != '') {
                                        $this->db->where_in('chart_of_account.LevelId', $level_id);
                                    } else {
                                        $this->db->where('chart_of_account.LevelId', $level_id);
                                    }
                                    $opening_balance = $this->db->get()->result();

                                    if ($opening_balance != array()) {
                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('transactions');
                                        $this->db->join('account_title', 'transactions.AccountID = account_title.id');
                                        $this->db->where('transactions.is_delete','0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('transactions.LevelID', $level_id);
                                        }
                                        $this->db->where('transactions.AccountID', $aitem->id);
                                        $this->db->where('transactions.VoucherDateG <', $from);
                                        $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();

                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('income');
                                        $this->db->join('account_title', 'income.AccountID = account_title.id');
                                        $this->db->where('income.is_delete','0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('income.LevelID', $level_id);
                                        }
                                        $this->db->where('income.AccountID', $aitem->id);
                                        $this->db->where('income.VoucherDateG < ', $from);
                                        $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                        $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                        $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                        $childstransactions_balances[$akey_1][$akey_2] = '';
                                        $childstransactions_balances[$akey_1][$akey_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                        $childstransactions_balances[$akey_1][$akey_2][0]->id = $deb_cre_fr_balance[0]->id;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Balance = $balance;

                                        $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(transactions.Debit),0) as Debit,IFNULL(SUM(transactions.Credit),0) as Credit');
                                        $this->db->from('transactions');
                                        $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                                        $this->db->where('transactions.AccountID', $aitem->id);
                                        $this->db->where('transactions.is_delete','0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('transactions.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                        }
                                        $query1_1 = $this->db->get_compiled_select();

                                        $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`income`.Debit),0) as Debit,IFNULL(SUM(`income`.Credit),0) as Credit');
                                        $this->db->from('income');
                                        $this->db->join('account_title', 'account_title.id = income.AccountID');
                                        $this->db->where('income.AccountID', $aitem->id);
                                        $this->db->where('income.is_delete','0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('income.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        }
                                        $query2_1 = $this->db->get_compiled_select();

                                        $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                        $childstransactions_d_c[$akey_1][$akey_2] = $query_1->result();
                                    }
                                }
                            }
                        }
                        $accs = array();
                        $balance_sum = 0;
                        $debit_sum = 0;
                        $credit_sum = 0;
                        if ($childstransactions_d_c == array()) {
                            $debit_sum = 0.00;
                            $credit_sum = 0.00;
                        } else {
                            foreach ($childstransactions_d_c as $childs_d_c) {
                                if (isset($childs_d_c)) {
                                    foreach ($childs_d_c as $debit_credit) {
                                        if (isset($debit_credit[0])) {
                                            $debit_sum += $debit_credit[0]->Debit;
                                            $credit_sum += $debit_credit[0]->Credit;
                                            $debit_credit[0]->Debit = 0;
                                            $debit_credit[0]->Credit = 0;
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($childstransactions_balances as $childstransaction) {
                            foreach ($childstransaction as $kkey => $aitems) {
                                $balance_sum += $aitems[0]->Balance;
                                $aitems[0]->Balance = 0;
                            }
                        }

                        $childstransactions_balances = array();
                        $childstransactions_d_c = array();
                        $transactions[$key_1][$key_2][0] = (object)array('AccountName' => '', 'AccountCode' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                        $transactions[$key_1][$key_2][0]->AccountName = $res->AccountName;
                        $transactions[$key_1][$key_2][0]->AccountCode = $res->AccountCode;
                        $transactions[$key_1][$key_2][0]->Debit = $debit_sum;
                        $transactions[$key_1][$key_2][0]->Credit = $credit_sum;
                        $transactions[$key_1][$key_2][0]->Balance = $balance_sum;
                    }
                }
            }
        } else {
            foreach ($accounts as $account) {
                $this->db->select('*');
                $this->db->from('account_title');
                if ($account_level == 'detail') {
                    $this->db->where('Category', '2');
                    $this->db->like('ParentCode', $account, 'after');
                } elseif ($account_level == '1') {
                    $this->db->where('AccountCode', $account);
                } else {
                    $this->db->like('ParentCode', $account, 'after');
                    $this->db->where('LENGTH(AccountCode)', $account_level);
                }
                $this->db->order_by('AccountCode', 'asc');
                $results[] = $this->db->get()->result();  // $results has accounts of level 1
            }
            foreach ($results as $key_1 => $result) {
                if ($result != array()) {
                    foreach ($result as $key_2 => $res) {
                        if ($res->Category == 2) {
                            $this->db->select('archived_chart_of_account_years.OpeningBalance');
                            $this->db->from('archived_chart_of_account_years');
                            $this->db->join('chart_of_account', 'archived_chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                            $this->db->where('chart_of_account.AccountId', $res->id);
                            if ($is_cons != '') {
                                $this->db->where_in('chart_of_account.LevelId', $level_id);
                            } else {
                                $this->db->where('chart_of_account.LevelId', $level_id);
                            }
                            // $this->db->where('archived_chart_of_account_years.Year', $this->activeyear);
                            $opening_balance = $this->db->get()->result();

                            if ($opening_balance != array()) {
                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('archived_transactions');
                                $this->db->join('account_title', 'archived_transactions.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_transactions.LevelID', $level_id);
                                }
                                $this->db->where('archived_transactions.AccountID', $res->id);
                                $this->db->where('archived_transactions.VoucherDateG <', $to);
                                $this->db->where('archived_transactions.is_delete', '0');
                                // $this->db->where('archived_transactions.Year', $this->activeyear);

                                $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();

                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('archived_income');
                                $this->db->join('account_title', 'archived_income.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_income.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_income.LevelID', $level_id);
                                }
                                $this->db->where('archived_income.AccountID', $res->id);
                                $this->db->where('archived_income.VoucherDateG < ', $to);
                                // $this->db->where('archived_income.Year', $this->activeyear);
                                $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                /**
                                 * Calculating Opening Balance with past Debit Credit Values
                                 */

                                $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                /** */
                                $childstransactions_balances[$key_1][$key_2] = '';
                                $childstransactions_balances[$key_1][$key_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                $childstransactions_balances[$key_1][$key_2][0]->id = $deb_cre_fr_balance[0]->id;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                $childstransactions_balances[$key_1][$key_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                $childstransactions_balances[$key_1][$key_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                $childstransactions_balances[$key_1][$key_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                $childstransactions_balances[$key_1][$key_2][0]->Balance = $balance;

                                $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(archived_transactions.Debit),0) as Debit,IFNULL(SUM(archived_transactions.Credit),0) as Credit');
                                $this->db->from('archived_transactions');
                                $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                                $this->db->where('archived_transactions.is_delete', '0');
                                $this->db->where('archived_transactions.AccountID', $res->id);
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_transactions.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('archived_transactions.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("archived_transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                }
                                // $this->db->where('archived_transactions.Year', $this->activeyear);
                                $query1_1 = $this->db->get_compiled_select();

                                $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`archived_income`.Debit),0) as Debit,IFNULL(SUM(`archived_income`.Credit),0) as Credit');
                                $this->db->from('archived_income');
                                $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                                $this->db->where('archived_income.AccountID', $res->id);
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_income.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_income.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('archived_income.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                }
                                // $this->db->where('archived_income.Year', $this->activeyear);
                                $query2_1 = $this->db->get_compiled_select();

                                $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                $childstransactions_d_c[$key_1][$key_2] = $query_1->result();
                            }
                        } else {
                            $this->db->select('*');
                            $this->db->from('account_title');
                            $this->db->like('AccountCode', $res->AccountCode, 'after');
                            $this->db->where('Category', '2');
                            $accs[] = $this->db->get()->result();

                            foreach ($accs as $akey_1 => $acc) {
                                foreach ($acc as $akey_2 => $aitem) {
                                    $this->db->select('archived_chart_of_account_years.OpeningBalance');
                                    $this->db->from('archived_chart_of_account_years');
                                    $this->db->join('chart_of_account', 'archived_chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                                    $this->db->where('chart_of_account.AccountId', $aitem->id);
                                    $this->db->where('chart_of_account.LevelId', $level_id);
                                    if ($is_cons != '') {
                                        $this->db->where_in('chart_of_account.LevelId', $level_id);
                                    } else {
                                        $this->db->where('chart_of_account.LevelId', $level_id);
                                    }
                                    // $this->db->where('archived_chart_of_account_years.Year', $this->activeyear);
                                    $opening_balance = $this->db->get()->result();

                                    if ($opening_balance != array()) {
                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('archived_transactions');
                                        $this->db->join('account_title', 'archived_transactions.AccountID = account_title.id');
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_transactions.LevelID', $level_id);
                                        }
                                        $this->db->where('archived_transactions.AccountID', $aitem->id);
                                        $this->db->where('archived_transactions.VoucherDateG <', $to);
                                            $this->db->where('archived_transactions.is_delete', '0');

                                        // $this->db->where('archived_transactions.Year', $this->activeyear);
                                        $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();
                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('archived_income');
                                        $this->db->join('account_title', 'archived_income.AccountID = account_title.id');
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_income.LevelID', $level_id);
                                        }
                                        $this->db->where('archived_income.AccountID', $aitem->id);
                                        $this->db->where('archived_income.VoucherDateG < ', $to);
                                        // $this->db->where('archived_income.Year', $this->activeyear);
                                        $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                        $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                        $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                        $childstransactions_balances[$akey_1][$akey_2] = '';
                                        $childstransactions_balances[$akey_1][$akey_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                        $childstransactions_balances[$akey_1][$akey_2][0]->id = $deb_cre_fr_balance[0]->id;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Balance = $balance;

                                        $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(archived_transactions.Debit),0) as Debit,IFNULL(SUM(archived_transactions.Credit),0) as Credit');
                                        $this->db->from('archived_transactions');
                                        $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                                        $this->db->where('archived_transactions.is_delete', '0');

                                        $this->db->where('archived_transactions.AccountID', $aitem->id);
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_transactions.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('archived_transactions.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("archived_transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                        }
                                        // $this->db->where('archived_transactions.Year', $this->activeyear);
                                        $query1_1 = $this->db->get_compiled_select();

                                        $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`archived_income`.Debit),0) as Debit,IFNULL(SUM(`archived_income`.Credit),0) as Credit');
                                        $this->db->from('archived_income');
                                        $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                                        $this->db->where('archived_income.AccountID', $aitem->id);
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_income.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('archived_income.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        }
                                        // $this->db->where('archived_income.Year', $this->activeyear);
                                        $query2_1 = $this->db->get_compiled_select();

                                        $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                        $childstransactions_d_c[$akey_1][$akey_2] = $query_1->result();
                                    }
                                }
                            }
                        }
                        $accs = array();
                        $balance_sum = 0;
                        $debit_sum = 0;
                        $credit_sum = 0;
                        if ($childstransactions_d_c == array()) {
                            $debit_sum = 0.00;
                            $credit_sum = 0.00;
                        } else {
                            foreach ($childstransactions_d_c as $childs_d_c) {
                                if (isset($childs_d_c)) {
                                    foreach ($childs_d_c as $debit_credit) {
                                        if (isset($debit_credit[0])) {
                                            $debit_sum += $debit_credit[0]->Debit;
                                            $credit_sum += $debit_credit[0]->Credit;
                                            $debit_credit[0]->Debit = 0;
                                            $debit_credit[0]->Credit = 0;
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($childstransactions_balances as $childstransaction) {
                            foreach ($childstransaction as $kkey => $aitems) {
                                $balance_sum += $aitems[0]->Balance;
                                $aitems[0]->Balance = 0;
                            }
                        }

                        $childstransactions_balances = array();
                        $childstransactions_d_c = array();
                        $transactions[$key_1][$key_2][0] = (object)array('AccountName' => '', 'AccountCode' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                        $transactions[$key_1][$key_2][0]->AccountName = $res->AccountName;
                        $transactions[$key_1][$key_2][0]->AccountCode = $res->AccountCode;
                        $transactions[$key_1][$key_2][0]->Debit = $debit_sum;
                        $transactions[$key_1][$key_2][0]->Credit = $credit_sum;
                        $transactions[$key_1][$key_2][0]->Balance = $balance_sum;
                    }
                }
            }
        }
        return $transactions;
    }

    public function getTrailBalance_pre($accounts, $account_level, $level_id, $tb_of, $to, $from, $is_cons = '')
    {
        // echo $from;
        // exit();
        $childstransactions_d_c = array();
        $childstransactions_balances = array();
        $transactions = array();
        if($this->year_status->Active == 1) {
            // echo $to;
            // exit();
            foreach ($accounts as $account) {
                $this->db->select('*');
                $this->db->from('account_title');
                if ($account_level == 'detail') {
                    //$this->db->where('Category','2');
                    $this->db->like('ParentCode', $account, 'after');
                } elseif ($account_level == '1') {
                    $this->db->where('AccountCode', $account);
                } else {
                    $this->db->like('ParentCode', $account, 'after');
                    $this->db->where('LENGTH(AccountCode)<', $account_level);
                }
                $this->db->where('Category', '2');
                $this->db->order_by('AccountCode', 'asc');
                $results[] = $this->db->get()->result();                    // $results has accounts of level 1
            }

// echo '<pre>';
// print_r($results);
// exit();

            foreach ($results as $key_1 => $result) {
                if ($result != array()) {
                    foreach ($result as $key_2 => $res) {
                        if ($res->Category == 2) {
                            $this->db->select('IFNULL(SUM(chart_of_account_years.OpeningBalance),0) as OpeningBalance');
                            $this->db->from('chart_of_account_years');
                            $this->db->join('chart_of_account', 'chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                            $this->db->where('chart_of_account.AccountId', $res->id);
                            if ($is_cons != '') {
                                $this->db->where_in('chart_of_account.LevelId', $level_id);
                            } else {
                                $this->db->where('chart_of_account.LevelId', $level_id);
                            }
                            $opening_balance = $this->db->get()->result();

                            if ($opening_balance != array()) {
                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('transactions');
                                $this->db->join('account_title', 'transactions.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('transactions.LevelID', $level_id);
                                }
                                $this->db->where('transactions.AccountID', $res->id);
                                $this->db->where('transactions.VoucherDateG <', $from);
                                $this->db->where('transactions.is_delete', '0');
                                $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();

                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('income');
                                $this->db->join('account_title', 'income.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('income.LevelID', $level_id);
                                } else {
                                    $this->db->where('income.LevelID', $level_id);
                                }
                                $this->db->where('income.AccountID', $res->id);
                                $this->db->where('income.VoucherDateG < ', $from);
                                $this->db->where('income.is_delete', '0');
                                $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();


                                $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);
// echo '<pre>';
// print_r($balance);
// echo '</pre>';

                                $childstransactions_balances[$key_1][$key_2] = '';
                                $childstransactions_balances[$key_1][$key_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                $childstransactions_balances[$key_1][$key_2][0]->id = $deb_cre_fr_balance[0]->id;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                $childstransactions_balances[$key_1][$key_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                $childstransactions_balances[$key_1][$key_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                $childstransactions_balances[$key_1][$key_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                $childstransactions_balances[$key_1][$key_2][0]->Balance = $balance;

                                $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(transactions.Debit),0) as Debit,IFNULL(SUM(transactions.Credit),0) as Credit');
                                $this->db->from('transactions');
                                $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                                $this->db->where('transactions.AccountID', $res->id);
                                $this->db->where('transactions.is_delete', '0');
                                if ($is_cons != '') {
                                    $this->db->where_in('transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('transactions.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                }
                                $query1_1 = $this->db->get_compiled_select();

                                $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`income`.Debit),0) as Debit,IFNULL(SUM(`income`.Credit),0) as Credit');
                                $this->db->from('income');
                                $this->db->join('account_title', 'account_title.id = income.AccountID');
                                $this->db->where('income.AccountID', $res->id);
                                $this->db->where('income.is_delete', '0');
                                if ($is_cons != '') {
                                    $this->db->where_in('income.LevelID', $level_id);
                                } else {
                                    $this->db->where('income.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                }
                                $query2_1 = $this->db->get_compiled_select();

                                $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                $childstransactions_d_c[$key_1][$key_2] = $query_1->result();
// echo '<pre>'.print_r(echo '<pre>';
// print_r($deb_cre_fr_balance);
// echo '</pre>';).'<pre>';
                            

                            }
                        } else {
                            $this->db->select('*');
                            $this->db->from('account_title');
                            $this->db->like('AccountCode', $res->AccountCode, 'after');
                            $this->db->where('Category', '2');
                            $accs[] = $this->db->get()->result();
                            foreach ($accs as $akey_1 => $acc) {
                                foreach ($acc as $akey_2 => $aitem) {
                                    $this->db->select('IFNULL(SUM(chart_of_account_years.OpeningBalance),0) as OpeningBalance');
                                    $this->db->from('chart_of_account_years');
                                    $this->db->join('chart_of_account', 'chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                                    $this->db->where('chart_of_account.AccountId', $aitem->id);
                                    if ($is_cons != '') {
                                        $this->db->where_in('chart_of_account.LevelId', $level_id);
                                    } else {
                                        $this->db->where('chart_of_account.LevelId', $level_id);
                                    }
                                    $opening_balance = $this->db->get()->result();



                                    if ($opening_balance != array()) {
                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('transactions');
                                        $this->db->join('account_title', 'transactions.AccountID = account_title.id');
                                        if ($is_cons != '') {
                                            $this->db->where_in('transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('transactions.LevelID', $level_id);
                                        }
                                        $this->db->where('transactions.AccountID', $aitem->id);
                                        $this->db->where('transactions.is_delete', '0');
                                        $this->db->where('transactions.VoucherDateG <',$from);
                                        $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();

                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('income');
                                        $this->db->join('account_title', 'income.AccountID = account_title.id');
                                        if ($is_cons != '') {
                                            $this->db->where_in('income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('income.LevelID', $level_id);
                                        }
                                        $this->db->where('income.AccountID', $aitem->id);
                                        $this->db->where('income.is_delete', '0');
                                        $this->db->where('income.VoucherDateG < ', $from);
                                        $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                        $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                        $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                        $childstransactions_balances[$akey_1][$akey_2] = '';
                                        $childstransactions_balances[$akey_1][$akey_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                        $childstransactions_balances[$akey_1][$akey_2][0]->id = $deb_cre_fr_balance[0]->id;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Balance = $balance;

                                        $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(transactions.Debit),0) as Debit,IFNULL(SUM(transactions.Credit),0) as Credit');
                                        $this->db->from('transactions');
                                        $this->db->join('account_title', 'account_title.id = transactions.AccountID');
                                        $this->db->where('transactions.AccountID', $aitem->id);
                                        $this->db->where('transactions.is_delete', '0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('transactions.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                        }
                                        $query1_1 = $this->db->get_compiled_select();

                                        $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`income`.Debit),0) as Debit,IFNULL(SUM(`income`.Credit),0) as Credit');
                                        $this->db->from('income');
                                        $this->db->join('account_title', 'account_title.id = income.AccountID');
                                        $this->db->where('income.AccountID', $aitem->id);
                                        $this->db->where('income.is_delete', '0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('income.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        }
                                        $query2_1 = $this->db->get_compiled_select();

                                        $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                        $childstransactions_d_c[$akey_1][$akey_2] = $query_1->result();
                                    }
                                }
                            }
                        }
                        $accs = array();
                        $balance_sum = 0;
                        $debit_sum = 0;
                        $credit_sum = 0;
                        if ($childstransactions_d_c == array()) {
                            $debit_sum = 0.00;
                            $credit_sum = 0.00;
                        } else {
                            foreach ($childstransactions_d_c as $childs_d_c) {
                                if (isset($childs_d_c)) {
                                    foreach ($childs_d_c as $debit_credit) {
                                        if (isset($debit_credit[0])) {
                                            $debit_sum += $debit_credit[0]->Debit;
                                            $credit_sum += $debit_credit[0]->Credit;
                                            $debit_credit[0]->Debit = 0;
                                            $debit_credit[0]->Credit = 0;
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($childstransactions_balances as $childstransaction) {
                            foreach ($childstransaction as $kkey => $aitems) {
                                $balance_sum += $aitems[0]->Balance;
                                $aitems[0]->Balance = 0;
                            }
                        }

                        $childstransactions_balances = array();
                        $childstransactions_d_c = array();
                        $transactions[$key_1][$key_2][0] = (object)array('AccountName' => '', 'AccountCode' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                        $transactions[$key_1][$key_2][0]->AccountName = $res->AccountName;
                        $transactions[$key_1][$key_2][0]->AccountCode = $res->AccountCode;
                        $transactions[$key_1][$key_2][0]->Debit = $debit_sum;
                        $transactions[$key_1][$key_2][0]->Credit = $credit_sum;
                        $transactions[$key_1][$key_2][0]->Balance = $balance_sum;
                    }
                }
            }
        } else {
            foreach ($accounts as $account) {
                $this->db->select('*');
                $this->db->from('account_title');
                if ($account_level == 'detail') {
                    //$this->db->where('Category','2');
                    $this->db->like('ParentCode', $account, 'after');
                } elseif ($account_level == '1') {
                    $this->db->where('AccountCode', $account);
                } else {
                    $this->db->like('ParentCode', $account, 'after');
                    $this->db->where('LENGTH(AccountCode)<', $account_level);
                }
                $this->db->where('Category', '2');
                $this->db->order_by('AccountCode', 'asc');
                $results[] = $this->db->get()->result();                    // $results has accounts of level 1
            }

            foreach ($results as $key_1 => $result) {
                if ($result != array()) {
                    foreach ($result as $key_2 => $res) {
                        if ($res->Category == 2) {
                            $this->db->select('archived_chart_of_account_years.OpeningBalance');
                            $this->db->from('archived_chart_of_account_years');
                            $this->db->join('chart_of_account', 'archived_chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                            $this->db->where('chart_of_account.AccountId', $res->id);
                            if ($is_cons != '') {
                                $this->db->where_in('chart_of_account.LevelId', $level_id);
                            } else {
                                $this->db->where('chart_of_account.LevelId', $level_id);
                            }
                            // $this->db->where('archived_chart_of_account_years.Year', $this->activeyear);
                            $opening_balance = $this->db->get()->result();

                            if ($opening_balance != array()) {
                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('archived_transactions');
                                $this->db->join('account_title', 'archived_transactions.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_transactions.LevelID', $level_id);
                                }
                                $this->db->where('archived_transactions.AccountID', $res->id);
                                $this->db->where('archived_transactions.VoucherDateG <', $to);
                                $this->db->where('archived_transactions.is_delete', '0');
                                // $this->db->where('archived_transactions.Year', $this->activeyear);
                                $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();

                                $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                $this->db->from('archived_income');
                                $this->db->join('account_title', 'archived_income.AccountID = account_title.id');
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_income.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_income.LevelID', $level_id);
                                }
                                $this->db->where('archived_income.AccountID', $res->id);
                                $this->db->where('archived_income.VoucherDateG < ', $to);
                                // $this->db->where('archived_income.Year', $this->activeyear);
                                $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                $childstransactions_balances[$key_1][$key_2] = '';
                                $childstransactions_balances[$key_1][$key_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                $childstransactions_balances[$key_1][$key_2][0]->id = $deb_cre_fr_balance[0]->id;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                $childstransactions_balances[$key_1][$key_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                $childstransactions_balances[$key_1][$key_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                $childstransactions_balances[$key_1][$key_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                $childstransactions_balances[$key_1][$key_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                $childstransactions_balances[$key_1][$key_2][0]->Balance = $balance;

                                $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(archived_transactions.Debit),0) as Debit,IFNULL(SUM(archived_transactions.Credit),0) as Credit');
                                $this->db->from('archived_transactions');
                                $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                                $this->db->where('archived_transactions.is_delete', '0');
                                $this->db->where('archived_transactions.AccountID', $res->id);
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_transactions.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_transactions.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('archived_transactions.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("archived_transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                }
                                // $this->db->where('archived_transactions.Year', $this->activeyear);
                                $query1_1 = $this->db->get_compiled_select();

                                $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`archived_income`.Debit),0) as Debit,IFNULL(SUM(`archived_income`.Credit),0) as Credit');
                                $this->db->from('archived_income');
                                $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                                $this->db->where('archived_income.AccountID', $res->id);
                                if ($is_cons != '') {
                                    $this->db->where_in('archived_income.LevelID', $level_id);
                                } else {
                                    $this->db->where('archived_income.LevelID', $level_id);
                                }
                                if ($tb_of == 'p') {
                                    $this->db->where('archived_income.Permanent_VoucherDateG IS NOT NULL');
                                    $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                } else {
                                    $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                }
                                // $this->db->where('archived_income.Year', $this->activeyear);
                                $query2_1 = $this->db->get_compiled_select();

                                $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                $childstransactions_d_c[$key_1][$key_2] = $query_1->result();
                            }
                        } else {
                            $this->db->select('*');
                            $this->db->from('account_title');
                            $this->db->like('AccountCode', $res->AccountCode, 'after');
                            $this->db->where('Category', '2');
                            $accs[] = $this->db->get()->result();

                            foreach ($accs as $akey_1 => $acc) {
                                foreach ($acc as $akey_2 => $aitem) {
                                    $this->db->select('archived_chart_of_account_years.OpeningBalance');
                                    $this->db->from('archived_chart_of_account_years');
                                    $this->db->join('chart_of_account', 'archived_chart_of_account_years.ChartOfAccountId = chart_of_account.id');
                                    $this->db->where('chart_of_account.AccountId', $aitem->id);
                                    if ($is_cons != '') {
                                        $this->db->where_in('chart_of_account.LevelId', $level_id);
                                    } else {
                                        $this->db->where('chart_of_account.LevelId', $level_id);
                                    }
                                    // $this->db->where('archived_chart_of_account_years.Year', $this->activeyear);
                                    $opening_balance = $this->db->get()->result();

                                    if ($opening_balance != array()) {
                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('archived_transactions');
                                        $this->db->join('account_title', 'archived_transactions.AccountID = account_title.id');
                                        $this->db->where('archived_transactions.is_delete', '0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_transactions.LevelID', $level_id);
                                        }
                                        $this->db->where('archived_transactions.AccountID', $aitem->id);
                                        $this->db->where('archived_transactions.VoucherDateG <', $to);
                                        // $this->db->where('archived_transactions.Year', $this->activeyear);
                                        $deb_cre_fr_balance_transactions = $this->db->get_compiled_select();

                                        $this->db->select('account_title.id,account_title.AccountCode,account_title.AccountName,IFNULL(SUM(Debit),0) as Debit,IFNULL(SUM(Credit),0) as Credit');
                                        $this->db->from('archived_income');
                                        $this->db->join('account_title', 'archived_income.AccountID = account_title.id');
                                        $this->db->where('archived_transactions.is_delete', '0');
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_income.LevelID', $level_id);
                                        }
                                        $this->db->where('archived_income.AccountID', $aitem->id);
                                        $this->db->where('archived_income.VoucherDateG < ', $to);
                                        // $this->db->where('archived_income.Year', $this->activeyear);
                                        $deb_cre_fr_balance_income = $this->db->get_compiled_select();

                                        $deb_cre_fr_balance = $this->db->query('Select id,AccountName,AccountCode, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $deb_cre_fr_balance_transactions . ' UNION ' . $deb_cre_fr_balance_income . ') as t')->result();

                                        $balance = (($opening_balance[0]->OpeningBalance + $deb_cre_fr_balance[0]->Debit) - $deb_cre_fr_balance[0]->Credit);

                                        $childstransactions_balances[$akey_1][$akey_2] = '';
                                        $childstransactions_balances[$akey_1][$akey_2][0] = (object)array('id' => '', 'AccountName' => '', 'AccountCode' => '', 'OpeningBalance' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                                        $childstransactions_balances[$akey_1][$akey_2][0]->id = $deb_cre_fr_balance[0]->id;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountName = $deb_cre_fr_balance[0]->AccountName;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->AccountCode = $deb_cre_fr_balance[0]->AccountCode;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->OpeningBalance = $opening_balance[0]->OpeningBalance;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Debit = $deb_cre_fr_balance[0]->Debit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Credit = $deb_cre_fr_balance[0]->Credit;
                                        $childstransactions_balances[$akey_1][$akey_2][0]->Balance = $balance;

                                        $this->db->select('`account_title`.`AccountCode`,account_title.AccountName,IFNULL(SUM(archived_transactions.Debit),0) as Debit,IFNULL(SUM(archived_transactions.Credit),0) as Credit');
                                        $this->db->from('archived_transactions');
                                        $this->db->join('account_title', 'account_title.id = archived_transactions.AccountID');
                                        $this->db->where('archived_transactions.is_delete', '0');
                                        $this->db->where('archived_transactions.AccountID', $aitem->id);
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_transactions.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_transactions.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('archived_transactions.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("archived_transactions.VoucherDateG BETWEEN  '" . $to . "' AND '" . $from . "'");
                                        }
                                        // $this->db->where('archived_transactions.Year', $this->activeyear);
                                        $query1_1 = $this->db->get_compiled_select();

                                        $this->db->select('`account_title`.`AccountCode`,`account_title`.AccountName,IFNULL(SUM(`archived_income`.Debit),0) as Debit,IFNULL(SUM(`archived_income`.Credit),0) as Credit');
                                        $this->db->from('archived_income');
                                        $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                                        $this->db->where('archived_income.AccountID', $aitem->id);
                                        if ($is_cons != '') {
                                            $this->db->where_in('archived_income.LevelID', $level_id);
                                        } else {
                                            $this->db->where('archived_income.LevelID', $level_id);
                                        }
                                        if ($tb_of == 'p') {
                                            $this->db->where('archived_income.Permanent_VoucherDateG IS NOT NULL');
                                            $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        } else {
                                            $this->db->where("archived_income.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
                                        }
                                        // $this->db->where('archived_income.Year', $this->activeyear);
                                        $query2_1 = $this->db->get_compiled_select();

                                        $query_1 = $this->db->query('Select AccountName, AccountCode, SUM(Debit) as Debit , SUM(Credit) AS Credit From(' . $query1_1 . ' UNION ' . $query2_1 . ')' . 'as t');
                                        $childstransactions_d_c[$akey_1][$akey_2] = $query_1->result();
                                    }
                                }
                            }
                        }
                        $accs = array();
                        $balance_sum = 0;
                        $debit_sum = 0;
                        $credit_sum = 0;
                        if ($childstransactions_d_c == array()) {
                            $debit_sum = 0.00;
                            $credit_sum = 0.00;
                        } else {
                            foreach ($childstransactions_d_c as $childs_d_c) {
                                if (isset($childs_d_c)) {
                                    foreach ($childs_d_c as $debit_credit) {
                                        if (isset($debit_credit[0])) {
                                            $debit_sum += $debit_credit[0]->Debit;
                                            $credit_sum += $debit_credit[0]->Credit;
                                            $debit_credit[0]->Debit = 0;
                                            $debit_credit[0]->Credit = 0;
                                        }
                                    }
                                }
                            }
                        }

                        foreach ($childstransactions_balances as $childstransaction) {
                            foreach ($childstransaction as $kkey => $aitems) {
                                $balance_sum += $aitems[0]->Balance;
                                $aitems[0]->Balance = 0;
                            }
                        }
                        $childstransactions_balances = array();
                        $childstransactions_d_c = array();
                        $transactions[$key_1][$key_2][0] = (object)array('AccountName' => '', 'AccountCode' => '', 'Debit' => '', 'Credit' => '', 'Balance' => '');
                        $transactions[$key_1][$key_2][0]->AccountName = $res->AccountName;
                        $transactions[$key_1][$key_2][0]->AccountCode = $res->AccountCode;
                        $transactions[$key_1][$key_2][0]->Debit = $debit_sum;
                        $transactions[$key_1][$key_2][0]->Credit = $credit_sum;
                        $transactions[$key_1][$key_2][0]->Balance = $balance_sum;
                    }
                }
            }
        }
        return $transactions;
    }

    public function getTransactionsForcheckbalance($levelid)
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('SUM(transactions.Debit) as Debit,SUM(transactions.Credit) as Credit');
            $this->db->from('transactions');
            $this->db->where('transactions.LevelID ', $levelid);
            $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
            $transactions = $this->db->get_compiled_select();

            $this->db->select('SUM(income.Debit) as Debit,SUM(income.Credit) as Credit');
            $this->db->from('income');
            $this->db->where('income.LevelID ', $levelid);
            $this->db->where('income.Permanent_VoucherNumber !=', NULL);
            $income = $this->db->get_compiled_select();

            return $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t')->result();
        } else {
            $this->db->select('SUM(archived_transactions.Debit) as Debit,SUM(archived_transactions.Credit) as Credit');
            $this->db->from('archived_transactions');
            $this->db->where('archived_transactions.LevelID ', $levelid);
            $this->db->where('archived_transactions.Permanent_VoucherNumber !=', NULL);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $transactions = $this->db->get_compiled_select();

            $this->db->select('SUM(archived_income.Debit) as Debit,SUM(archived_income.Credit) as Credit');
            $this->db->from('archived_income');
            $this->db->where('archived_income.LevelID ', $levelid);
            $this->db->where('income.Permanent_VoucherNumber !=', NULL);
            // $this->db->where('archived_income.Year', $this->activeyear);
            $income = $this->db->get_compiled_select();

            return $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t')->result();
        }
    }

    public function GetDebitcredit($levelid)
    {
        $this->db->select('id');
        $this->db->from('chart_of_account');
        $this->db->where('LevelId', $levelid);
        $this->db->order_by('id', 'ASC');
        $result = $this->db->get()->result();
         
        if($this->year_status->Active == 1) {
            foreach ($result as $key => $value) {
                $this->db->select('LinkID,IFNULL(SUM(transactions.Debit),0) as Debit,IFNULL(SUM(transactions.Credit),0) as Credit');
                $this->db->from('transactions');
                // $this->db->where('transactions.LevelID', $levelid);
                // $this->db->where('transactions.AccountID ', $value->AccountId);
                $this->db->where('transactions.LinkID ', $value->id);
                $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
                $transactions = $this->db->get_compiled_select();
                $this->db->select('AccountID,IFNULL(SUM(income.Debit),0) as Debit,IFNULL(SUM(income.Credit),0) as Credit');
                $this->db->from('income');
                // $this->db->where('income.LevelID', $levelid);
                // $this->db->where('income.AccountID ', $value->AccountId);
                $this->db->where('income.LinkID ', $value->id);
                $this->db->where('income.Permanent_VoucherNumber !=', NULL);
                $income = $this->db->get_compiled_select();

                $check[] = $this->db->query('Select LinkID, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t')->result();
            }
        } else {
            foreach ($result as $key => $value) {
                $this->db->select('LinkID,IFNULL(SUM(archived_transactions.Debit),0) as Debit,IFNULL(SUM(archived_transactions.Credit),0) as Credit');
                $this->db->from('archived_transactions');
                // $this->db->where('archived_transactions.LevelID', $levelid);
                // $this->db->where('archived_transactions.AccountID ', $value->AccountId);
                $this->db->where('archived_transactions.LinkID ', $value->id);
                $this->db->where('archived_transactions.Permanent_VoucherNumber !=', NULL);
                // $this->db->where('archived_transactions.Year', $this->activeyear);
                $transactions = $this->db->get_compiled_select();
                $this->db->select('AccountID,IFNULL(SUM(archived_income.Debit),0) as Debit,IFNULL(SUM(archived_income.Credit),0) as Credit');
                $this->db->from('archived_income');
                // $this->db->where('archived_income.LevelID', $levelid);
                // $this->db->where('archived_income.AccountID ', $value->AccountId);
                $this->db->where('archived_income.LinkID ', $value->id);
                $this->db->where('archived_income.Permanent_VoucherNumber !=', NULL);
                // $this->db->where('archived_income.Year', $this->activeyear);
                $income = $this->db->get_compiled_select();

                $check[] = $this->db->query('Select LinkID, SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t')->result();
            }
        }
        return $check;
    }

    public function GetTransactionData($sum = '', $levelId, $accs, $from = '', $to = '', $trans_of)
    {
         
        if($this->year_status->Active == 1) {
            if ($sum == '') {
                $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
            } else {
                $this->db->select('Debit,Credit');
            }
            $this->db->from('transactions');
            $this->db->where('LevelID', $levelId);
            $this->db->where_in('AccountID', $accs);
            if ($trans_of == 'p') {
                $this->db->where('transactions.Permanent_VoucherDateG IS NOT NULL');
                $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $from . "' AND '" . $to . "'");
            } else {
                $this->db->where("transactions.VoucherDateG BETWEEN  '" . $from . "' AND '" . $to . "'");
            }

            $transactions = $this->db->get_compiled_select();

            if ($sum == '') {
                $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
            } else {
                $this->db->select('Debit,Credit');
            }
            $this->db->from('income');
            $this->db->where('LevelID', $levelId);
            $this->db->where_in('AccountID', $accs);
            if ($trans_of == 'p') {
                $this->db->where('income.Permanent_VoucherDateG IS NOT NULL');
                $this->db->where("income.Permanent_VoucherDateG BETWEEN '" . $from . "' AND '" . $to . "'");
            } else {
                $this->db->where("income.VoucherDateG BETWEEN  '" . $from . "' AND '" . $to . "'");
            }
            $income = $this->db->get_compiled_select();
        } else {
            if ($sum == '') {
                $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
            } else {
                $this->db->select('Debit,Credit');
            }
            $this->db->from('archived_transactions');
            $this->db->where('LevelID', $levelId);
            $this->db->where_in('AccountID', $accs);
            if ($trans_of == 'p') {
                $this->db->where('archived_transactions.Permanent_VoucherDateG IS NOT NULL');
                $this->db->where("archived_transactions.Permanent_VoucherDateG BETWEEN '" . $from . "' AND '" . $to . "'");
            } else {
                $this->db->where("archived_transactions.VoucherDateG BETWEEN  '" . $from . "' AND '" . $to . "'");
            }
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            $transactions = $this->db->get_compiled_select();

            if ($sum == '') {
                $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
            } else {
                $this->db->select('Debit,Credit');
            }
            $this->db->from('archived_income');
            $this->db->where('LevelID', $levelId);
            $this->db->where_in('AccountID', $accs);
            if ($trans_of == 'p') {
                $this->db->where('archived_income.Permanent_VoucherDateG IS NOT NULL');
                $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '" . $from . "' AND '" . $to . "'");
            } else {
                $this->db->where("archived_income.VoucherDateG BETWEEN  '" . $from . "' AND '" . $to . "'");
            }
            // $this->db->where('archived_income.Year', $this->activeyear);
            $income = $this->db->get_compiled_select();
        }

        return $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t')->result();
    }

    public function GetPreTranData($levelId, $accs, $to = '', $from = '')
    {
         
        if($this->year_status->Active == 1) {
            $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
            $this->db->from('transactions');
            $this->db->where('LevelID', $levelId);
            $this->db->where('AccountID', $accs);
            $this->db->where('transactions.VoucherDateG < ', $from);
        } else {
            $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
            $this->db->from('archived_transactions');
            $this->db->where('LevelID', $levelId);
            $this->db->where('AccountID', $accs);
            $this->db->where('archived_transactions.VoucherDateG < ', $from);
            // $this->db->where('archived_transactions.Year', $this->activeyear);
        }
        return $this->db->get()->row();
    }

    public function getOpeningBal($level_id, $Acc_ids)
    {

        if($this->year_status->Active == 1) {
            // echo 'true';
            // exit();
        $this->db->select('IFNULL(SUM(OpeningBalance),0) as OpeningBalance');
            $this->db->where_in('ChartOfAccountId', $Acc_ids);
           // $this->db->where('LevelID',$level_id);
            $result =  $this->db->get('chart_of_account_years');
            return $result->row();
        } else {
            $this->db->select('IFNULL(SUM(OpeningBalance),0) as OpeningBalance');
            //$this->db->where('LevelID',$level_id);
            $this->db->where_in('ChartOfAccountId', $Acc_ids);
            return $this->db->get('archived_chart_of_account_years')->row();
        }
    }
    public function GetTaxDeductionVouchers($coaId, $to = '', $from = '')
    {
        $this->db->select('`transactions`.`VoucherNo`,`transactions`.`VoucherType`');
//        $this->db->select('transactions.LinkID, transactions.Debit, transactions.Credit, transactions.VoucherDateG, transactions.VoucherDateH, account_title.Category');
//        $this->db->join('chart_of_account','transactions.LinkID = chart_of_account.id');
//        $this->db->join('account_title','chart_of_account.AccountId = account_title.id');
        $this->db->where('transactions.LinkID', $coaId);
        if ($to != "" && $from != "") {
            $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
        }
         
        if($this->year_status->Active == 1) {
            return $this->db->get('transactions')->result();
        } else {
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }
    public function GetTaxDeductionData($voucherNumber, $voucherType, $to = '', $from = '')
    {
//        $this->db->select('`transactions`.`VoucherNo`,`transactions`.`VoucherType`');
        $this->db->select('transactions.TaxDebit,transactions.Description, transactions.LinkID, transactions.VoucherNo,transactions.Permanent_VoucherNumber, transactions.Debit, transactions.Credit, transactions.VoucherDateG, transactions.VoucherDateH, account_title.Type');
        $this->db->join('chart_of_account', 'transactions.LinkID = chart_of_account.id');
        $this->db->join('account_title', 'chart_of_account.AccountId = account_title.id');
        $this->db->where('`transactions`.`VoucherNo`', $voucherNumber);
        $this->db->where('`transactions`.`VoucherType`', $voucherType);
        if ($to != "" && $from != "") {
            $this->db->where("transactions.VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'", NULL, FALSE);
        }
        $result1 = $this->db->query('SELECT year from closing_year')->result();
        $previous_year_date = $result1[0]->year;
        $hijri_date = $this->get_hijri_date($from);
        if($this->year_status->Active == 1) {
            return $this->db->get('transactions')->result();
        } else {
            // $this->db->where('archived_transactions.Year', $this->activeyear);
            return $this->db->get('archived_transactions')->result();
        }
    }
    public function UpdateCurrentBalance()
    {
        foreach ($_POST['LinkID'] as $key => $value) {
            $this->db->set('CurrentBalance', $_POST['Cal_Closing'][$key]);
            $this->db->where('ChartOfAccountId', $value);
             
        if($this->year_status->Active == 1) {
                $this->db->update('chart_of_account_years');
            } else {
                // $this->db->where('archived_chart_of_account_years.Year', $this->activeyear);
                $this->db->update('archived_chart_of_account_years');
            }
        }
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function GetTransactionsForClosing($accs, $level)
    {
        $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
        $this->db->from('transactions');
        $this->db->where_in('AccountID', $accs);
        $this->db->where('LevelID', $level);
        $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
        $transactions = $this->db->get_compiled_select();
        $this->db->select('IFNULL(SUM(Debit),0) as Debit , IFNULL(SUM(Credit),0) as Credit');
        $this->db->from('income');
        $this->db->where_in('AccountID', $accs);
        //$this->db->where('LevelID', $level);
        $this->db->where('income.Permanent_VoucherNumber !=', NULL);
        $income = $this->db->get_compiled_select();

        return $this->db->query('Select SUM(Debit) as Debit, SUM(Credit) as Credit From(' . $transactions . ' UNION ' . $income . ') as t')->result();
    }
    public function GetVoucherForCashHolding($level, $to, $from)
    {
        $this->db->select('SUM(transactions.Credit)as credit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber,transactions.Seprate_series_num, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks,company_structure.LevelName,transactions.LevelID');
        $this->db->distinct();
        $this->db->from('transactions');
        $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
        $this->db->join('account_title', 'account_title.id = transactions.AccountID');
        $this->db->join('departments', 'departments.id = transactions.DepartmentId');
        $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
        $this->db->where('transactions.LevelID', $level);
        $this->db->where('transactions.VoucherType', "CP");
        $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
        $this->db->group_by('transactions.Permanent_VoucherNumber');
        $this->db->order_by('transactions.Permanent_VoucherNumber');
        return $this->db->get()->result();
    }

    public function s_GetVoucherForCashHolding($to, $from)
    {
        $this->db->select('SUM(transactions.Credit)as credit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber,transactions.Seprate_series_num, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks,company_structure.LevelName,transactions.LevelID');
        $this->db->distinct();
        $this->db->from('transactions');
        $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
        $this->db->join('account_title', 'account_title.id = transactions.AccountID');
        $this->db->join('departments', 'departments.id = transactions.DepartmentId');
        $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
        //$this->db->where('transactions.LevelID', $level);
        $this->db->where('transactions.VoucherType', "CP");
        $this->db->where("transactions.Permanent_VoucherDateG BETWEEN '" . $to . "' AND '" . $from . "'");
        $this->db->group_by('transactions.Permanent_VoucherNumber');
        $this->db->order_by('transactions.Permanent_VoucherNumber');
        return $this->db->get()->result();
    }

    //end sufyan work
    public function GetVoucherFromCashHolding($date)
    {
        $this->db->select('*');
        $this->db->where('Reportdate',$date);
        return $this->db->get('cash_holding')->result();
    }
    public function GetVoucherForCashHoldingSave($level, $vouerno)
    {
        $this->db->select('SUM(transactions.Credit)as credit,transactions.Id as t_id,transactions.VoucherType, transactions.Permanent_VoucherNumber,transactions.Seprate_series_num, transactions.VoucherNo, transactions.Permanent_VoucherDateG, transactions.Permanent_VoucherDateH, transactions.PaidTo, departments.DepartmentName, transactions.Remarks,company_structure.LevelName,transactions.LevelID');
        $this->db->from('transactions');
        $this->db->join('company_structure', 'company_structure.id = transactions.LevelID');
        $this->db->join('account_title', 'account_title.id = transactions.AccountID');
        $this->db->join('departments', 'departments.id = transactions.DepartmentId');
        $this->db->where('transactions.Permanent_VoucherNumber !=', NULL);
        $this->db->where('transactions.Permanent_VoucherNumber', $vouerno);
        // $this->db->where('transactions.LevelID', $level);
        $this->db->where('transactions.VoucherType', "CP");
        $this->db->group_by('transactions.Permanent_VoucherNumber');
        $this->db->order_by('transactions.Permanent_VoucherNumber');
        return $this->db->get()->result();
    }
    public function DeleteVoucher_old($date)
    {
        $this->db->where('reportdate',$date);
        $this->db->delete('cash_holding');

        $this->db->where('reportdate',$date);
        $this->db->delete('remaining_cash');

        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    public function GetVoucherFromRemainingCash($date)
    {
        $this->db->select('*');
        $this->db->where('Reportdate',$date);
        return $this->db->get('remaining_cash')->result();

    }
}