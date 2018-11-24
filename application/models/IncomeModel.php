<?php

class IncomeModel extends CI_Model
{
    private $activeyear;
    private $year_status;

    function __construct()
    {
        parent::__construct();
        $this->load->model('BookModel');
        $this->load->model('YearModel');
        $this->activeyear = $this->session->userdata('current_year');
        $this->year_status = $this->YearModel->getYearStatus($this->activeyear);
    }

    public function save_transaction($company_id)
    {
        foreach ($_POST['AccountID'] as $key => $value) {

            $this->db->select('id');
            $this->db->where('LevelId', $company_id);
            $this->db->where('AccountId', $value);
            $result[$key] = $this->db->get('chart_of_account')->result();
        }
        $voucher_no = $this->get_voucher_no($company_id);
        $book_type = "ic";
        $voucherType = strtoupper($book_type);
        $seqno = 1;
        foreach ($_POST['AccountID'] as $key => $value)
        {
            $this->db->set('SequenceNo',$seqno);
            $this->db->set('LevelID',$_POST['companyId']);
            $this->db->set('AccountID',$value);
//            $link_id = $this->get_link_id($_POST['companyId'],$value);
            $this->db->set('LinkID',$result[$key][0]->id);
            $this->db->set('BookNo',$_POST['bookNo']);
            $recipt1 = $_POST['reciptNo1'];
            $recipt2 = $_POST['reciptNo2'];
            $newRecipt = $recipt1.'-'.$recipt2;
            $this->db->set('ReciptNo',$newRecipt);
//            $this->db->set('DepositSlipNo',$_POST['bdepositSlipNo'][$key]);
//            $this->db->set('DepositDateG',$_POST['bdepositDate'][$key]);
            if(isset($_POST['departId'])){
                $this->db->set('DepartmentId',$_POST['departId']);
            }
            $this->db->set('VoucherType',$voucherType);
            $this->db->set('VoucherNo',$voucher_no);
            $this->db->set('Remarks',$_POST['transac_details']);
            $this->db->set('VoucherDateG',$_POST['englishDate']);
            $this->db->set('VoucherDateH',$_POST['islamicDate']);
//            $this->db->set('ChequeType',$_POST['ChequeType'][$key]);
//            $this->db->set('ChequeNumber',$_POST['bchequeno'][$key]);
//            $this->db->set('ChequeDate', $_POST['bchequedate'][$key]);
//            $this->db->set('DepositType', $_POST['DepositType'][$key]);
//            $this->db->set('BankName', $_POST['BankName'][$key]);
            $this->db->set('Description',$_POST['details'][$key]);

            $this->db->set('Debit',$_POST['recieved'][$key]);
            $this->db->set('Credit',$_POST['payment'][$key]);

            $this->db->set('Createdby',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));


            if ($this->year_status->Active == 1) {
                $this->db->insert('income');
            }else{
                $this->db->set('Year' , $this->activeyear);
                $this->db->insert('archived_income');
            }
            $seqno++;
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function get_link_id($level_id,$acc_id)
    {
        $this->db->select('id');
        $this->db->where('LevelId',$level_id);
        $this->db->where('AccountId',$acc_id);
        $this->db->from('chart_of_account');
        return $this->db->get()->row();
    }

    public function get_voucher_no($company_id)
    {
        $voucher_no = "";
        $vouch = "";
        //$voucherType = "";
        $vouchNumb = "";
        $ser = $this->BookModel->checkSer($company_id);
        if($ser[0]->IsSerealized == 0){
            $code = $this->get_code($company_id);
        }else{
            $code = $this->getDepartMax($company_id);
        }
        if($code->num_rows() > 0){
            $voucherNo = $code->result();
            if(empty($voucherNo[0]->VoucherNo)){
                $vouchNumb = 1;
                $voucher_no = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
            }else{
                if($voucherNo[0]->IsSerealized == 0){
                    $vouchNo = $voucherNo[0]->VoucherNo;
                    $vouchNumb = ++$vouchNo;
                    $vouchNumber = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
                    $voucher_no = $vouchNumber;
                }else{
                    $vouchNo = $voucherNo[0]->VoucherNo;
                    $vouchNumb = ++$vouchNo;
                    $vouchNumber = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
                    $voucher_no = $vouchNumber;
                }
                $vouchNo = $voucherNo[0]->VoucherNo;
                $vouchNumb = ++$vouchNo;
                $vouchNumber = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
                $voucher_no = $vouchNumber;
            }
        }
        return $voucher_no;
    }

    public function get_code()
    {
        $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
        $this->db->select_max('VoucherNo');
        $this->db->from('income');
        $this->db->where('company_structure.IsSerealized !=',1);
        $this->db->join('company_structure', 'company_structure.id = income.LevelID');
        return $this->db->get();
    }

    public function getDepartMax($comp_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('VoucherNo');
            $this->db->from('income');
            $this->db->where('income.LevelID', $comp_id);
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
        }else{
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('VoucherNo');
            $this->db->from('archived_income');
            $this->db->where('archived_income.LevelID', $comp_id);
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
        }
        return $this->db->get();
    }

    public function get_transactions($Level_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('SUM(income.Debit) as debit,income.Id as t_id,income.VoucherType, income.Permanent_VoucherNumber, income.VoucherNo, income.VoucherDateG, income.VoucherDateH, departments.DepartmentName, income.Remarks, income.BookNo, income.ReciptNo');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId','left');
            $this->db->where('income.Permanent_VoucherNumber =' , NUll);
            $this->db->where('income.LevelID', $Level_id);
            $this->db->where('income.is_delete', '0');
            //$this->db->like('income.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('income.VoucherNo');
        }else{
            $this->db->select('SUM(archived_income.Debit) as debit,archived_income.Id as t_id,archived_income.VoucherType, archived_income.Permanent_VoucherNumber, archived_income.VoucherNo, archived_income.VoucherDateG, archived_income.VoucherDateH, departments.DepartmentName, archived_income.Remarks, archived_income.BookNo, archived_income.ReciptNo');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId','left');
            $this->db->where('archived_income.Permanent_VoucherNumber =' , NUll);
            $this->db->where('archived_income.LevelID', $Level_id);
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->like('archived_income.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->where('income.is_delete', '0');
            $this->db->group_by('archived_income.VoucherNo');
        }
        return $this->db->get()->result();
    }

    public function get_edit_transaction($id,$company_id)
    {
        $data =  $this->getVoucherAndType($id);
        if ($this->year_status->Active == 1) {
            $this->db->select('income.CreatedOn,income.Createdby,income.Permanent_VoucherDateH, income.Permanent_VoucherDateG, income.Permanent_VoucherNumber, income.Id as t_id,company_structure.id as level_id,company_structure.LevelName,income.VoucherType,income.VoucherNo,income.VoucherDateG,income.VoucherDateH, departments.Id as DepartmentId,departments.DepartmentName,income.Remarks,income.Debit,income.Credit,income.Description,account_title.id as AccountID, account_title.AccountName, account_title.Type as AccountType, income.SequenceNo,income.ChequeNumber,account_title.AccountCode as AccountCode,income.ChequeDate,income.BookNo,income.ReciptNo,income.DepositSlipNo,income.DepositDateG,income.ChequeType,income.DepositType,income.BankName');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.Id = income.DepartmentId','left');
            $this->db->where('income.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('income.LevelID',$company_id );
            $this->db->where('income.is_delete','0');
        }else{
            $this->db->select('archived_income.CreatedOn,archived_income.Createdby,archived_income.Permanent_VoucherDateH, archived_income.Permanent_VoucherDateG, archived_income.Permanent_VoucherNumber, archived_income.Id as t_id,company_structure.id as level_id,company_structure.LevelName,archived_income.VoucherType,archived_income.VoucherNo,archived_income.VoucherDateG,archived_income.VoucherDateH, departments.Id as DepartmentId,departments.DepartmentName,archived_income.Remarks,archived_income.Debit,archived_income.Credit,archived_income.Description,account_title.id as AccountID, account_title.AccountName, account_title.Type as AccountType, archived_income.SequenceNo,archived_income.ChequeNumber,account_title.AccountCode as AccountCode,archived_income.ChequeDate,archived_income.BookNo,archived_income.ReciptNo,archived_income.DepositSlipNo,archived_income.DepositDateG,archived_income.ChequeType,archived_income.DepositType,archived_income.BankName');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.Id = archived_income.DepartmentId','left');
            $this->db->where('archived_income.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('archived_income.LevelID',$company_id );
            $this->db->where('archived_income.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }

    public function getVoucherAndType($id)
    {
        $this->db->select('VoucherNo');
        $this->db->where('income.id', $id);
        if ($this->year_status->Active = 1) {
            return $this->db->get('income')->result();
        }else{
            $this->db->where('archived_income.Year', $this->activeyear);
            return $this->db->get('archived_income')->result();
        }
    }

    public function Update_Transactions()
    {
        $arr = array(
            'VoucherNo' => $_POST['VoucherNo'],
            'LevelID' => $_POST['LevelID']
        );

        if ($this->year_status->Active == 1) {
            $this->db->where($arr);
            $this->db->delete('income');
        }else{
            $this->db->where($arr);
            $this->db->delete('archived_income');
        }

        if($this->db->affected_rows() > 0) {
            $seqno = 0;
            foreach ($_POST['AccountID'] as $key => $value) {
                $seqno++;
                $this->db->set('SequenceNo',$seqno);
                $this->db->set('LevelID',$_POST['LevelID']);
                $this->db->set('AccountID',$value);
                $link_id = $this->get_link_id($_POST['LevelID'],$value);
                $this->db->set('LinkID',$link_id->id);
                $this->db->set('BookNo',$_POST['bookNo']);
                $recipt1 = $_POST['reciptNo1'];
                $recipt2 = $_POST['reciptNo2'];
                $newRecipt = $recipt1.'-'.$recipt2;
                $this->db->set('ReciptNo',$newRecipt);
                $this->db->set('DepositSlipNo',$_POST['bdepositSlipNo'][$key]);
                $this->db->set('DepositDateG',$_POST['bdepositDate'][$key]);
                $this->db->set('DepositType',$_POST['DepositType'][$key]);
                $this->db->set('ChequeType',$_POST['ChequeType'][$key]);
                $this->db->set('BankName',$_POST['BankName'][$key]);
                if(isset($_POST['DepartmentId'])){
                    $this->db->set('DepartmentId',$_POST['DepartmentId']);
                }
                $this->db->set('VoucherType',$_POST['VoucherType']);
                $this->db->set('VoucherNo',$_POST['VoucherNo']);
                $this->db->set('Remarks',$_POST['transac_details']);
                $this->db->set('VoucherDateG',$_POST['VoucherDateG']);
                $this->db->set('VoucherDateH',$_POST['VoucherDateH']);
                $this->db->set('ChequeNumber',$_POST['ChequeNumber'][$key]);
                $this->db->set('ChequeDate', $_POST['ChequeDate'][$key]);
                $this->db->set('Description',$_POST['Description'][$key]);

                $this->db->set('Debit',$_POST['Debit'][$key]);
                $this->db->set('Credit',$_POST['Credit'][$key]);

                $this->db->set('Createdby',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));

                if ($this->year_status->Active == 1) {
                    $this->db->insert('income');
                }else{
                    $this->db->set('Year' , $this->activeyear);
                    $this->db->insert('archived_income');
                }

            }
            if($this->db->affected_rows() > 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function delete_transaction($voucherno,$levelid)
    {
        $delete = array('VoucherNo' => $voucherno , 'LevelID' => $levelid );
        if ($this->year_status->Active == 1) {
            $this->db->where($delete);
            $this->db->set('is_delete','1');
            $this->db->update('income');
        }else{
            $this->db->where($delete);
            $this->db->set('is_delete','1');
            $this->db->update('archived_income');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function get_voucher_details($tran_id,$level_id)
    {
        $this->db->select('VoucherNo,LevelID');
        $this->db->where('id', $tran_id);
        $this->db->where('LevelID', $level_id);
        if ($this->year_status->Active == 1) {
            return $this->db->get('income')->result();
        }else{
            $this->db->where('archived_income.Year', $this->activeyear);
            return $this->db->get('archived_income')->result();
        }
    }

    public function get_voucher($vouch_no,$level_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('company_structure.LevelName,income.Remarks,income.Description,income.Debit,income.Credit,
        income.Permanent_VoucherNumber,income.Permanent_VoucherDateH,income.Permanent_VoucherDateG,a.AccountName,income.Createdby,
        income.CreatedOn,income.UpdatedBy,income.UpdatedOn,a.Type,departments.DepartmentName,a.AccountCode,
        income.VoucherType,income.VoucherNo,income.VoucherDateH,income.VoucherDateG,`b`.`AccountName` as `ParentName` ,income.BookNo,income.ReciptNo,income.DepositDateG,income.DepositSlipNo,income.DepositType');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title a ', 'a.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId','left');
            $this->db->join('account_title b','b.AccountCode = a.ParentCode');
            $this->db->where('income.VoucherNo', $vouch_no);
            $this->db->where('income.LevelID', $level_id);
            $this->db->order_by('income.Credit', 'ASC');
        }else{
            $this->db->select('company_structure.LevelName,archived_income.Remarks,archived_income.Description,archived_income.Debit,archived_income.Credit,
        archived_income.Permanent_VoucherNumber,archived_income.Permanent_VoucherDateH,archived_income.Permanent_VoucherDateG,a.AccountName,archived_income.Createdby,
        archived_income.CreatedOn,archived_income.UpdatedBy,archived_income.UpdatedOn,a.Type,departments.DepartmentName,a.AccountCode,
        archived_income.VoucherType,archived_income.VoucherNo,archived_income.VoucherDateH,archived_income.VoucherDateG,`b`.`AccountName` as `ParentName` ,archived_income.BookNo,archived_income.ReciptNo,archived_income.DepositDateG,archived_income.DepositSlipNo,archived_income.DepositType');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title a ', 'a.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId','left');
            $this->db->join('account_title b','b.AccountCode = a.ParentCode');
            $this->db->where('archived_income.VoucherNo', $vouch_no);
            $this->db->where('archived_income.LevelID', $level_id);
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->order_by('archived_income.Credit', 'ASC');
        }
        return $this->db->get()->result();
    }

    public function get_move_transaction($id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('VoucherNo,LevelID,Id ');
            $this->db->where('Id',$id);
            $this->db->from('income');
            $data = $this->db->get()->result();
            $this->db->select('income.LevelID, `income`.`Id` as `t_id`, income.VoucherType, income.VoucherNo, income.AccountID, account_title.Type, account_title.AccountName, income.Debit, income.Credit, income.ChequeNumber, income.ChequeDate, income.BookNo,income.ReciptNo ');
            $this->db->from('income');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->where('income.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('income.LevelID ', $data[0]->LevelID );
        }else{
            $this->db->select('VoucherNo,LevelID,Id ');
            $this->db->where('Id',$id);
            $this->db->from('archived_income');
            $data = $this->db->get()->result();
            $this->db->select('archived_income.LevelID, `archived_income`.`Id` as `t_id`, archived_income.VoucherType, archived_income.VoucherNo, archived_income.AccountID, account_title.Type, account_title.AccountName, archived_income.Debit, archived_income.Credit, archived_income.ChequeNumber, archived_income.ChequeDate, archived_income.BookNo,archived_income.ReciptNo ');
            $this->db->from('archived_income');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->where('archived_income.VoucherNo', $data[0]->VoucherNo);
            $this->db->where('archived_income.LevelID ', $data[0]->LevelID );
            $this->db->where('archived_income.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }

    public function get_max_permanent()
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('Permanent_VoucherNumber');
            $this->db->from('income');
            $this->db->where('company_structure.IsSerealized !=',1);
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
        }else{
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('Permanent_VoucherNumber');
            $this->db->from('archived_income');
            $this->db->where('company_structure.IsSerealized !=',1);
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
        }
        return $this->db->get();
    }

    public function get_dept_max_permanet($comp_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('Permanent_VoucherNumber');
            $this->db->from('income');
            $this->db->where('income.LevelID', $comp_id);
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
        }else{
            $this->db->select('VoucherType,LevelID,company_structure.IsSerealized');
            $this->db->select_max('Permanent_VoucherNumber');
            $this->db->from('archived_income');
            $this->db->where('archived_income.LevelID', $comp_id);
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
        }
        return $this->db->get();
    }

    public function move_transaction($id)
    {
        $company_id = $_POST['level'];
        $Edate = $_POST['Edate'];
        $Idate = $_POST['Idate'];
        $v_no = $_POST['v_no'];
        $voucher_no = "";
        $vouch = "";
        $vouchNumb = "";
        $accnts = $this->get_transaction_account($v_no,$company_id);
        foreach ($accnts as $accnt) {
            $balance = $this->BookModel->get_account_balance($accnt->AccountID,$company_id);
            $ch_id = $this->BookModel->get_chart_of_account_id($accnt->AccountID,$company_id);
            if($accnt->Debit != 0.00){
                $debit = $accnt->Debit;
                $credit = '';
                $balanceUpdated = $this->BookModel->update_current_balance($ch_id[0]->id,$balance[0]->CurrentBalance,$debit,$credit);
            }else{
                $debit = '';
                $credit = $accnt->Credit;
                $balanceUpdated = $this->BookModel->update_current_balance($ch_id[0]->id,$balance[0]->CurrentBalance,$debit,$credit);
            }
        }
        $ser = $this->BookModel->checkSer($company_id);
        if($ser[0]->IsSerealized == 0){
            $code = $this->get_max_permanent();
        }else{
            $code = $this->get_dept_max_permanet($company_id);
        }
        if($code->num_rows() > 0){
            $voucherNo = $code->result();
            if(empty($voucherNo[0]->Permanent_VoucherNumber)){
                $vouchNumb = 1;
                $voucher_no = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
            }else{
                if($voucherNo[0]->IsSerealized == 0){
                    $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                    $vouchNumb = ++$vouchNo;
                    $vouchNumber = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
                    // $voucherType = 'CR';
                    $voucher_no = $vouchNumber;
                }else{
                    $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                    $vouchNumb = ++$vouchNo;
                    $vouchNumber = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
                    $voucher_no = $vouchNumber;
                }
                $vouchNo = $voucherNo[0]->Permanent_VoucherNumber;
                $vouchNumb = ++$vouchNo;
                $vouchNumber = str_pad($vouchNumb,5,0,STR_PAD_LEFT);
                $voucher_no = $vouchNumber;
            }
            if ($this->year_status->Active == 1) {
                $this->db->where('income.VoucherNo', $v_no);
                $this->db->where('LevelID', $company_id);
                $this->db->set('Permanent_VoucherNumber',$voucher_no);
                $this->db->set('Permanent_VoucherDateG',$Edate);
                $this->db->set('Permanent_VoucherDateH',$Idate);
                $this->db->update('income');
            }else{
                $this->db->where('archived_income.VoucherNo', $v_no);
                $this->db->where('LevelID', $company_id);
                $this->db->set('Permanent_VoucherNumber',$voucher_no);
                $this->db->set('Permanent_VoucherDateG',$Edate);
                $this->db->set('Permanent_VoucherDateH',$Idate);
                $this->db->set('Year' , $this->activeyear);
                $this->db->update('archived_income');
            }

            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function get_transaction_account($v_no,$company_id)
    {
        $this->db->select('AccountID,Debit,Credit');
        $this->db->where('VoucherNo', $v_no);
        $this->db->where('LevelID', $company_id);
        if ($this->year_status->Active == 1) {
            return $this->db->get('income')->result();
        }else{
            $this->db->where('archived_income.Year', $this->activeyear);
            return $this->db->get('archived_income')->result();
        }
    }

    public function get_permanent_voucher($Level_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('SUM(income.Debit)as debit,income.Id as t_id,income.VoucherType, income.Permanent_VoucherNumber, income.VoucherNo, income.Permanent_VoucherDateG, income.Permanent_VoucherDateH, departments.DepartmentName, income.Remarks, income.BookNo, income.ReciptNo');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId','left');
            $this->db->where('income.Permanent_VoucherNumber !=',NUll);
            $this->db->where('income.LevelID', $Level_id);
            $this->db->like('income.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('income.VoucherNo');
        }else{
            $this->db->select('SUM(archived_income.Debit)as debit,archived_income.Id as t_id,archived_income.VoucherType, archived_income.Permanent_VoucherNumber, archived_income.VoucherNo, archived_income.Permanent_VoucherDateG, archived_income.Permanent_VoucherDateH, departments.DepartmentName, archived_income.Remarks, archived_income.BookNo, archived_income.ReciptNo');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId','left');
            $this->db->where('archived_income.Permanent_VoucherNumber !=',NUll);
            $this->db->where('archived_income.LevelID', $Level_id);
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->like('archived_income.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('archived_income.VoucherNo');
        }
        return $this->db->get()->result();
    }

    public function Per_Update_Transactions()
    {
        $arr = array(
            'VoucherNo' => $_POST['VoucherNo'],
            'LevelID' => $_POST['LevelID']
        );
        $this->db->where($arr);
        $this->db->delete('income');

        if($this->db->affected_rows() > 0) {
            $seqno = 0;
            foreach ($_POST['AccountID'] as $key => $value) {
                $seqno++;
                $this->db->set('LevelID', $_POST['LevelID']);
                $this->db->set('VoucherType', $_POST['VoucherType']);
                $link_id = $this->get_link_id($_POST['LevelID'],$value);

                $this->db->set('LinkID',$link_id->id);
                $this->db->set('BookNo',$_POST['bookNo']);
                $recipt1 = $_POST['reciptNo1'];
                $recipt2 = $_POST['reciptNo2'];
                $newRecipt = $recipt1.'-'.$recipt2;
                $this->db->set('ReciptNo',$newRecipt);
                $this->db->set('DepositSlipNo',$_POST['bdepositSlipNo'][$key]);
                $this->db->set('DepositDateG',$_POST['bdepositDate'][$key]);
                $this->db->set('VoucherNo', $_POST['VoucherNo']);
                $this->db->set('Permanent_VoucherDateH', $_POST['Permanent_VoucherDateH']);
                $this->db->set('Permanent_VoucherDateG', $_POST['Permanent_VoucherDateG']);
                $this->db->set('Permanent_VoucherNumber', $_POST['Permanent_VoucherNumber']);
                $this->db->set('DepartmentId', $_POST['DepartmentId']);
                $this->db->set('Remarks', $_POST['Remarks']);
                $this->db->set('VoucherDateG', $_POST['VoucherDateG']);
                $this->db->set('VoucherDateH', $_POST['VoucherDateH']);
                $this->db->set('Createdby', $_POST['Createdby']);
                $this->db->set('CreatedOn', $_POST['CreatedOn']);

                $this->db->set('SequenceNo', $seqno);
                $this->db->set('AccountID', $value);
                $this->db->set('Debit', $_POST['Debit'][$key]);
                $this->db->set('Credit', $_POST['Credit'][$key]);
                $this->db->set('Description', $_POST['Description'][$key]);

                $this->db->set('ChequeNumber', $_POST['ChequeNumber'][$key]);
                $this->db->set('ChequeDate', $_POST['ChequeDate'][$key]);

                $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));

                $this->db->insert('income');
            }if($this->db->affected_rows() > 0) {
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function copy_voucher($level_id)
    {
        $trans[] = '';
        $seqno = 1;
        if ($this->year_status->Active == 1) {
            foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                $this->db->where('LevelID', $level_id);
                $trans[$key] = $this->db->get('income')->result();
                $voucher_no =  $this->get_voucher_no($level_id);

                foreach ($trans[$key] as $key => $data) {
                    $this->db->set('SequenceNo',$seqno);
                    $this->db->set('LevelID',$data->LevelID);
                    $this->db->set('AccountID',$data->AccountID);
                    $link_id = $this->get_link_id($data->LevelID,$data->AccountID);

                    $this->db->set('LinkID',$link_id->id);
                    $this->db->set('BookNo',$data->BookNo);
                    $this->db->set('ReciptNo',$data->ReciptNo);
                    $this->db->set('DepositSlipNo',$data->DepositSlipNo);
                    $this->db->set('DepositDateG',$data->DepositDateG);

                    if(isset($data->DepartmentId)){
                        $this->db->set('DepartmentId',$data->DepartmentId);
                    }

                    $this->db->set('VoucherType',$data->VoucherType);
                    $this->db->set('VoucherNo',$voucher_no);
                    $this->db->set('Remarks',$data->Remarks);
                    $this->db->set('VoucherDateG',$data->VoucherDateG);

                    $this->db->set('VoucherDateH',$data->VoucherDateH);
                    $this->db->set('ChequeType',$data->ChequeType);
                    $this->db->set('ChequeNumber',$data->ChequeNumber);
                    $this->db->set('ChequeDate', $data->ChequeDate);
                    $this->db->set('DepositType', $data->DepositType);

                    $this->db->set('BankName', $data->BankName);
                    if(isset($data->Description)){
                        $this->db->set('Description',$data->Description);
                    }
                    if(isset($data->Debit)){
                        $this->db->set('Debit',$data->Debit);
                    }
                    if(isset($data->Credit)){
                        $this->db->set('Credit',$data->Credit);
                    }
                    $this->db->set('Createdby',$_SESSION['user'][0]->id);
                    $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                    $this->db->insert('income');
                    $seqno++;
                }
            }
        }else{
            foreach ($_POST['data']['copyToTemp'] as $key => $value) {
                $this->db->where('VoucherNo', $_POST['vouch_no']['VoucherNo'][$key]);
                $this->db->where('LevelID', $level_id);
                $trans[$key] = $this->db->get('archived_income')->result();
                $voucher_no =  $this->get_voucher_no($level_id);

                foreach ($trans[$key] as $key => $data) {
                    $this->db->set('SequenceNo',$seqno);
                    $this->db->set('LevelID',$data->LevelID);
                    $this->db->set('AccountID',$data->AccountID);
                    $link_id = $this->get_link_id($data->LevelID,$data->AccountID);

                    $this->db->set('LinkID',$link_id->id);
                    $this->db->set('BookNo',$data->BookNo);
                    $this->db->set('ReciptNo',$data->ReciptNo);
                    $this->db->set('DepositSlipNo',$data->DepositSlipNo);
                    $this->db->set('DepositDateG',$data->DepositDateG);

                    if(isset($data->DepartmentId)){
                        $this->db->set('DepartmentId',$data->DepartmentId);
                    }

                    $this->db->set('VoucherType',$data->VoucherType);
                    $this->db->set('VoucherNo',$voucher_no);
                    $this->db->set('Remarks',$data->Remarks);
                    $this->db->set('VoucherDateG',$data->VoucherDateG);

                    $this->db->set('VoucherDateH',$data->VoucherDateH);
                    $this->db->set('ChequeType',$data->ChequeType);
                    $this->db->set('ChequeNumber',$data->ChequeNumber);
                    $this->db->set('ChequeDate', $data->ChequeDate);
                    $this->db->set('DepositType', $data->DepositType);

                    $this->db->set('BankName', $data->BankName);
                    if(isset($data->Description)){
                        $this->db->set('Description',$data->Description);
                    }
                    if(isset($data->Debit)){
                        $this->db->set('Debit',$data->Debit);
                    }
                    if(isset($data->Credit)){
                        $this->db->set('Credit',$data->Credit);
                    }
                    $this->db->set('Createdby',$_SESSION['user'][0]->id);
                    $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                    $this->db->set('Year', $this->activeyear);
                    $this->db->insert('archived_income');
                    $seqno++;
                }
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Depart($levelid)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('departments.Id,departments.DepartmentName');
            $this->db->from('income');
            $this->db->join('departments' , 'departments.Id = income.DepartmentId');
            $this->db->where('LevelID',$levelid);
            $this->db->group_by('departments.Id');
        }else{
            $this->db->select('departments.Id,departments.DepartmentName');
            $this->db->from('archived_income');
            $this->db->join('departments' , 'departments.Id = archived_income.DepartmentId');
            $this->db->where('LevelID',$levelid);
            $this->db->group_by('departments.Id');
        }
        return $this->db->get()->result();
    }

    public function GetIncomeVoucherNumber($levels='',$depart_id ='',$to,$from,$total='')
    {
        if ($this->year_status->Active == 1) {
            if(!$total){
                foreach ($levels as $key1 => $level) {
                    foreach ($level as $key2 => $item) {
                        if(!($item->id < '47')){
                            $this->db->select('income.Id,DepartmentName,LevelName,BookNo,ReciptNo');
                            $this->db->from('income');
                            $this->db->join('departments' , 'departments.Id = income.DepartmentId','left');
                            $this->db->join('company_structure' , 'company_structure.id = income.LevelID');
                            $this->db->where('LevelID',$item->id);
                            $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                            $this->db->order_by('income.Id', 'asc');
                            $this->db->group_by('income.VoucherNo');
                            //$this->db->group_by('income.VoucherType');
                            $result[$key1][$key2][0] = $this->db->get()->result();
                        }else{
                            if(is_array($depart_id)){
                                foreach ($depart_id as $key3 => $depart){
                                    //print_r($item);
                                    $this->db->select('income.Id,DepartmentName,LevelName,BookNo,ReciptNo');
                                    $this->db->from('income');
                                    $this->db->join('departments' , 'departments.Id = income.DepartmentId');
                                    $this->db->join('company_structure' , 'company_structure.id = income.LevelID');
                                    $this->db->where('LevelID',$item->id);
                                    $this->db->where('income.DepartmentId',$depart->Id);
                                    $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                                    $this->db->order_by('income.Id', 'asc');
                                    $this->db->group_by('income.VoucherNo');
                                    //$this->db->group_by('income.VoucherType');
                                    $result[$key1][$key2][$key3] = $this->db->get()->result();
                                }
                            }else{
                                $this->db->select('income.Id,DepartmentName,LevelName,BookNo,ReciptNo');
                                $this->db->from('income');
                                $this->db->join('departments' , 'departments.Id = income.DepartmentId');
                                $this->db->join('company_structure' , 'company_structure.id = income.LevelID');
                                $this->db->where('LevelID',$item->id);
                                $this->db->where('income.DepartmentId',$depart_id);
                                $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                                $this->db->order_by('income.Id', 'asc');
                                $this->db->group_by('income.VoucherNo');
                                //$this->db->group_by('income.VoucherType');
                                $result[$key1][$key2][0] = $this->db->get()->result();
                            }
                        }
                    }
                }
            }else{
                foreach ($levels as $key1 => $level) {
                    foreach ($level as $key2 => $item) {
                        if(!($item->id < '47')){
                            $this->db->select('income.Id');
                            $this->db->from('income');
                            $this->db->join('departments' , 'departments.Id = income.DepartmentId','left');
                            $this->db->join('company_structure' , 'company_structure.id = income.LevelID');
                            $this->db->where('LevelID',$item->id);
                            $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                            $this->db->order_by('income.Id', 'asc');
                            $this->db->group_by('income.VoucherNo');
                            $result[$key1][$key2][0] = $this->db->get()->result();
                        }else{
                            if(is_array($depart_id)){
                                foreach ($depart_id as $key3 => $depart){
                                    //print_r($item);
                                    $this->db->select('income.Id');
                                    $this->db->from('income');
                                    $this->db->join('departments' , 'departments.Id = income.DepartmentId');
                                    $this->db->join('company_structure' , 'company_structure.id = income.LevelID');
                                    $this->db->where('LevelID',$item->id);
                                    $this->db->where('income.DepartmentId',$depart->Id);
                                    $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                                    $this->db->order_by('income.Id', 'asc');
                                    $this->db->group_by('income.VoucherNo');
                                    $result[$key1][$key2][$key3] = $this->db->get()->result();
                                }
                            }else{
                                $this->db->select('income.Id');
                                $this->db->from('income');
                                $this->db->join('departments' , 'departments.Id = income.DepartmentId');
                                $this->db->join('company_structure' , 'company_structure.id = income.LevelID');
                                $this->db->where('LevelID',$item->id);
                                $this->db->where('income.DepartmentId',$depart_id);
                                $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                                $this->db->order_by('income.Id', 'asc');
                                $this->db->group_by('income.VoucherNo');
                                //$this->db->group_by('income.VoucherType');
                                $result[$key1][$key2][0] = $this->db->get()->result();
                            }
                        }
                    }
                }
            }
        }else{
            foreach ($levels as $key1 => $level) {
                foreach ($level as $key2 => $item) {
                    foreach ($depart_id as $key3 => $depart){
                        //print_r($item);
                        $this->db->select('archived_income.Id,archived_income.VoucherType,archived_income.VoucherNo,.archived_income.LevelID');
                        $this->db->from('archived_income');
                        $this->db->join('departments' , 'departments.Id = archived_income.DepartmentId');
                        $this->db->where('LevelID',$item->id);
                        $this->db->where('archived_income.DepartmentId',$depart->Id);
                        $this->db->where("VoucherDateG >= '".$to."' AND VoucherDateG <=  '".$from."'");
                        $this->db->where('archived_income.Year', $this->activeyear);
                        $this->db->group_by('archived_income.VoucherNo');
                        $this->db->group_by('archived_income.VoucherType');
                        $result[$key1][$key2][$key3] = $this->db->get()->result();
                    }
                }
            }
        }
        return $result;
    }

    // public function getData($ID)
    // {
    //     if ($this->year_status->Active == 1) {
    //         $this->db->select('IFNULL(SUM(Cheque_amount),0) as ChequeAmount,IFNULL(SUM(Currency * Currency_Quantity),0) as CurrencyQuantity,departments.DepartmentName,income_cheque_data.Cheque_Type,COUNT(Cheque_Type) as TotalChequeType');
    //         $this->db->select('IFNULL(SUM(Cheque_amount),0) as ChequeAmount,IFNULL(SUM(Currency * Currency_Quantity),0) as CurrencyQuantity,departments.DepartmentName,income_cheque_data.Cheque_Type,COUNT(Cheque_Type) as TotalChequeType');
    //         $this->db->from('income');
    //         $this->db->join('departments' , 'departments.Id = income.DepartmentId');
    //         $this->db->join('income_cheque_data', 'income_cheque_data.Income_Id = income.Id');
    //         $this->db->join('income_currency_data', 'income_currency_data.Income_Id = income.Id');
    //         $this->db->join('currency', 'currency.Id = income_currency_data.Currency_Id');
    //         $this->db->where('VoucherType',$VoucherType);
    //         $this->db->where('VoucherNo',$VoucherNo);
    //         $this->db->where('LevelID', $LevelID);
    //         //$this->db->group_by('VoucherNo');
    //         $this->db->group_by('Cheque_Type');
    //     }else{
    //         $this->db->select('archived_income.DepositType,archived_income.Description,archived_income.VoucherType,archived_income.VoucherNo,departments.DepartmentName,archived_income.BookNo,archived_income.ReciptNo,Debit,Credit,ChequeType, archived_income.LevelID');
    //         $this->db->from('archived_income');
    //         $this->db->join('departments' , 'departments.Id = archived_income.DepartmentId');
    //         $this->db->join('account_title' , 'account_title.id = archived_income.AccountID');
    //         $this->db->where('VoucherType',$VoucherType);
    //         $this->db->where('VoucherNo',$VoucherNo);
    //         $this->db->where('LevelID', $LevelID);
    //         $this->db->where('archived_income.Year', $this->activeyear);
    //     }
    //     return $this->db->get()->result();
    // }

    public function getChequeData($t_id,$all='')
    {
        if(!$all){
            $this->db->select('IFNULL(SUM(Cheque_amount),0) as ChequeAmount');
            $this->db->where('Income_Id', $t_id);
            return $this->db->get('income_cheque_data')->row();
        }else{
            $this->db->select('*');
            $this->db->where_in('Income_Id', $t_id);
            return $this->db->get('income_cheque_data')->result();
        }
    }

    public function getCurrencyData($t_id,$all='')
    {
        $sum = 0;
        if(!$all){
            $query = $this->db->query('SELECT Currency,Currency_Quantity FROM `income_currency_data` JOIN `currency` ON `currency`.`Id` = `income_currency_data`.`Currency_Id` WHERE Income_Id = '.$t_id.' ');
            $currencies = $query->result();
            foreach ($currencies as $key => $currency) {
                if(is_numeric($currency->Currency)){
                    $sum += ($currency->Currency * $currency->Currency_Quantity);
                }else{
                    $sum += $currency->Currency_Quantity;
                }
            }
            return $sum;
        }else{
            $this->db->select('Currency,SUM(Currency_Quantity) as Currencies,Currency * SUM(Currency_Quantity) as TotalAmount');
            $this->db->from('income_currency_data');
            $this->db->join('currency', 'currency.Id = income_currency_data.Currency_Id');
            $this->db->where_in('Income_Id', $t_id);
            $this->db->group_by('currency.Currency');
            return $this->db->get()->result();
        }
    }

    public function getChequeTypeData($t_id)
    {
        $this->db->select('Cheque_Type,IFNULL(SUM(Cheque_amount),0) as ChequeAmountTypeWise');
        $this->db->where('Income_Id', $t_id);
        $this->db->group_by('Cheque_Type');
        return $this->db->get('income_cheque_data')->result();
    }

    public function getChequeTypeData2($t_id)
    {
        $this->db->select('Cheque_Type,Count(Cheque_Type) as ChequeAmount,IFNULL(SUM(Cheque_amount),0) as ChequeAmountTypeWise');
        $this->db->where_in('Income_Id', $t_id);
        $this->db->group_by('Cheque_Type');
        return $this->db->get('income_cheque_data')->result();
    }

    public function getTotalCheque($t_id)
    {
        $this->db->select('SUM(Cheque_amount) as ChequeAmount');
        $this->db->from('income_cheque_data');
        $this->db->where_in('Income_Id', $t_id);
        return $this->db->get()->row();
    }

    public function getTotalCash($t_id)
    {
        $ids = implode(",",$t_id);
        $sum = 0;
        $query = $this->db->query('SELECT Currency,Currency_Quantity FROM `income_currency_data` JOIN `currency` ON `currency`.`Id` = `income_currency_data`.`Currency_Id` WHERE Income_Id IN ('.$ids.')');
        $currencies = $query->result();
        foreach ($currencies as $key => $currency) {
            if(is_numeric($currency->Currency)){
                $sum += ($currency->Currency * $currency->Currency_Quantity);
            }else{
                $sum += $currency->Currency_Quantity;
            }
        }
        return $sum;
    }

    public function get_transactions_Income($book_type,$Level_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('SUM(income.Debit)as debit,income.Id as t_id,income.VoucherType, income.Permanent_VoucherNumber, income.VoucherNo, income.VoucherDateG, income.VoucherDateH, departments.DepartmentName, income.Remarks ,income.BookNo,income.ReciptNo');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountId');
            $this->db->join('departments', 'departments.id = income.DepartmentId');
            $this->db->where('income.Permanent_VoucherNumber =', NULL);
            $this->db->where('income.LevelID', $Level_id);
            if ($book_type != '') {
                $this->db->where('income.VoucherType', "ic");
            }
            $this->db->like('income.VoucherDateH', $this->session->userdata('current_year'));
        }else{
            $this->db->select('SUM(archived_income.Debit)as debit,archived_income.Id as t_id,archived_income.VoucherType, archived_income.Permanent_VoucherNumber, archived_income.VoucherNo, archived_income.VoucherDateG, archived_income.VoucherDateH, departments.DepartmentName, archived_income.Remarks ,archived_income.BookNo,archived_income.ReciptNo');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountId');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
            $this->db->where('archived_income.Permanent_VoucherNumber =', NULL);
            $this->db->where('archived_income.LevelID', $Level_id);
            if ($book_type != '') {
                $this->db->where('archived_income.VoucherType', "ic");
            }
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->like('archived_income.VoucherDateH', $this->session->userdata('current_year'));
        }
        return $this->db->get()->result();
    }

    public function get_by_account_code_income($code,$type,$id)
    {
        $this->db->select('id,AccountCode');
        $this->db->Like('AccountCode', $code);
        $acc_code = $this->db->get('account_title')->result();
        if (!empty($acc_code)) {
            foreach ($acc_code as $acc) {
                if ($this->year_status->Active == 1) {
                    $this->db->select('*,income.Id as t_id,SUM(income.Debit)as debit');
                    $this->db->from('income');
                    $this->db->join('company_structure', 'company_structure.id = income.LevelID');
                    $this->db->join('account_title', 'account_title.id = income.AccountID');
                    $this->db->join('departments', 'departments.id = income.DepartmentId');
                    $this->db->where('income.AccountID', $acc->id);
                    $this->db->where('income.LevelID', $id);
                    $this->db->where('income.VoucherType', "ic");
                }else{
                    $this->db->select('*,archived_income.Id as t_id,SUM(archived_income.Debit)as debit');
                    $this->db->from('archived_income');
                    $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                    $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
                    $this->db->where('archived_income.AccountID', $acc->id);
                    $this->db->where('archived_income.LevelID', $id);
                    $this->db->where('archived_income.VoucherType', "ic");
                    $this->db->where('archived_income.Year', $this->activeyear);
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        }else{
            return null;
        }
    }

    public function get_transaction_by_date_income($book_type,$id,$to,$from)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('SUM(income.Debit) as debit,income.Id as t_id,income.VoucherType, income.Permanent_VoucherNumber, income.VoucherNo, income.VoucherDateG, income.VoucherDateH, departments.DepartmentName, income.Remarks,income.BookNo,income.ReciptNo');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId','left');
            $array = array('income.Permanent_VoucherNumber'=>NULL , 'income.VoucherType'=> 'ic' ,'income.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("income.VoucherDateG BETWEEN '".$to."' AND '".$from."'", NULL, FALSE );
            $this->db->group_by('income.VoucherNo');
        }else{
            $this->db->select('SUM(archived_income.Debit) as debit,archived_income.Id as t_id,archived_income.VoucherType, archived_income.Permanent_VoucherNumber, archived_income.VoucherNo, archived_income.VoucherDateG, archived_income.VoucherDateH, departments.DepartmentName, archived_income.Remarks,archived_income.BookNo,archived_income.ReciptNo');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId','left');
            $array = array('archived_income.Permanent_VoucherNumber'=>NULL , 'archived_income.VoucherType'=> 'ic' ,'archived_income.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("archived_income.VoucherDateG BETWEEN '".$to."' AND '".$from."'", NULL, FALSE );
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->group_by('archived_income.VoucherNo');
        }
        return $this->db->get()->result();

    }

    public function get_transaction_by_dateAndAccountCode_income($type='',$id='')
    {
        $to = $_POST['to'];
        $from = $_POST['from'];
        $account_code = $_POST['AccountCode'];
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $account_code);
        $acc_code = $this->db->get('account_title')->result();
        if (!empty($acc_code)) {
            foreach ($acc_code as $acc) {
                if ($this->year_status->Active == 1) {
                    $this->db->select('*,income.Id as t_id');
                    $this->db->from('income');
                    $this->db->join('company_structure', 'company_structure.id = income.LevelID');
                    $this->db->join('account_title', 'account_title.id = income.AccountID');
                    $this->db->join('departments', 'departments.id = income.DepartmentId');
                    $this->db->where('income.AccountID', $acc->id);
                    $this->db->where('income.LevelID', $id);
                    $this->db->where('income.voucherType', "ic");
                    $this->db->where('income.VoucherDateG >=', $to);
                    $this->db->where('income.VoucherDateG <=', $from);
                    $this->db->like('income.VoucherDateH', $this->session->userdata('current_year'));
                }else{
                    $this->db->select('*,archived_income.Id as t_id');
                    $this->db->from('archived_income');
                    $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                    $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
                    $this->db->where('archived_income.AccountID', $acc->id);
                    $this->db->where('archived_income.LevelID', $id);
                    $this->db->where('archived_income.voucherType', "ic");
                    $this->db->where('archived_income.VoucherDateG >=', $to);
                    $this->db->where('archived_income.VoucherDateG <=', $from);
                    $this->db->where('archived_income.Year', $this->activeyear);
                    $this->db->like('archived_income.VoucherDateH', $this->session->userdata('current_year'));
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        }else{
            return null;
        }
    }

    public function get_per_transactions_income($book_type,$Level_id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('*,SUM(income.Debit)as debit');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId');
            $this->db->where('income.Permanent_VoucherNumber !=',NULL);
            $this->db->where('income.LevelID', $Level_id);
            $this->db->where('income.VoucherType', "ic");
            $this->db->like('income.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('income.VoucherNo');
        }else{
            $this->db->select('*,SUM(archived_income.Debit)as debit');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
            $this->db->where('archived_income.Permanent_VoucherNumber !=',NULL);
            $this->db->where('archived_income.LevelID', $Level_id);
            $this->db->where('archived_income.VoucherType', "ic");
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->like('archived_income.VoucherDateH', $this->session->userdata('current_year'));
            $this->db->group_by('archived_income.VoucherNo');
        }
        return $this->db->get()->result();
    }

    public function get_by_per_voucher_no_income($code='',$type,$id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('*,SUM(income.Debit)as debit');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId');
            $this->db->like('VoucherNo', $code);
            $this->db->where('income.LevelID', $id);
            $this->db->where('income.voucherType', "ic");
            $this->db->where('income.Permanent_VoucherNumber !=', NULL);
        }else{
            $this->db->select('*,SUM(archived_income.Debit)as debit');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
            $this->db->like('VoucherNo', $code);
            $this->db->where('archived_income.LevelID', $id);
            $this->db->where('archived_income.voucherType', "ic");
            $this->db->where('archived_income.Permanent_VoucherNumber !=', NULL);
            $this->db->where('archived_income.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }
    public function get_per_trans_by_account_code_income($code,$type,$id)
    {
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $code);
        $acc_code = $this->db->get('account_title')->result();
        if(!empty($acc_code)){
            foreach ($acc_code as $ac_c) {
                if ($this->year_status->Active == 1) {
                    $this->db->select('*,SUM(income.Debit) as debit');
                    $this->db->from('income');
                    $this->db->join('company_structure', 'company_structure.id = income.LevelID');
                    $this->db->join('account_title', 'account_title.id = income.AccountID');
                    $this->db->join('departments', 'departments.id = income.DepartmentId');
                    $this->db->where('income.AccountID', $ac_c->id);
                    $this->db->where('income.Permanent_VoucherNumber !=', NULL);
                    $this->db->where('income.LevelID', $id);
                    $this->db->where('income.VoucherType', "ic");
                }else{
                    $this->db->select('*,SUM(archived_income.Debit) as debit');
                    $this->db->from('archived_income');
                    $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                    $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
                    $this->db->where('archived_income.AccountID', $ac_c->id);
                    $this->db->where('archived_income.Permanent_VoucherNumber !=', NULL);
                    $this->db->where('archived_income.LevelID', $id);
                    $this->db->where('archived_income.VoucherType', "ic");
                    $this->db->where('archived_income.Year', $this->activeyear);
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        }else{
            return null;
        }
    }
    public function get_permanent_trans_bydate_income($book_type,$id,$to,$from)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('*,SUM(income.Debit) as debit');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->join('departments', 'departments.id = income.DepartmentId');
            $array = array('income.Permanent_VoucherNumber != ' => NULL , 'income.VoucherType'=> "ic" ,'income.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("income.Permanent_VoucherDateG BETWEEN '".$to."' AND '".$from."'", NULL, FALSE );
            $this->db->group_by('income.VoucherNo');
        }else{
            $this->db->select('*,SUM(archived_income.Debit) as debit');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
            $array = array('archived_income.Permanent_VoucherNumber != ' => NULL , 'archived_income.VoucherType'=> "ic" ,'archived_income.LevelID' => $id);
            $this->db->where($array);
            $this->db->where("archived_income.Permanent_VoucherDateG BETWEEN '".$to."' AND '".$from."'", NULL, FALSE );
            $this->db->where('archived_income.Year', $this->activeyear);
            $this->db->group_by('archived_income.VoucherNo');
        }
        return $this->db->get()->result();
    }
    public function get_permanent_trans_by_dateAndAccountCodeIcome($type='',$id='')
    {
        $to = $_POST['to'];
        $from = $_POST['from'];
        $account_code = $_POST['AccountCode'];
        $this->db->select('id,AccountCode');
        $this->db->like('AccountCode', $account_code);
        $acc_code = $this->db->get('account_title')->result();
        if(!empty($acc_code)){
            foreach ($acc_code as $ac_c) {
                if ($this->year_status->Active == 1) {
                    $this->db->select('*,SUM(income.Debit) as debit');
                    $this->db->from('income');
                    $this->db->join('company_structure', 'company_structure.id = income.LevelID');
                    $this->db->join('account_title', 'account_title.id = income.AccountID');
                    $this->db->join('departments', 'departments.id = income.DepartmentId');
                    $this->db->where('income.AccountID', $ac_c->id);
                    $this->db->where('income.LevelID', $id);
                    $this->db->where('income.voucherType', "ic");
                    $this->db->where('income.Permanent_VoucherNumber !=', NULL);
                    $this->db->where('income.Permanent_VoucherDateG >=', $to);
                    $this->db->where('income.Permanent_VoucherDateG <=', $from);
                    $this->db->like('income.Permanent_VoucherDateG', $this->session->userdata('current_year'));
                }else{
                    $this->db->select('*,SUM(archived_income.Debit) as debit');
                    $this->db->from('archived_income');
                    $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
                    $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
                    $this->db->join('departments', 'departments.id = archived_income.DepartmentId');
                    $this->db->where('archived_income.AccountID', $ac_c->id);
                    $this->db->where('archived_income.LevelID', $id);
                    $this->db->where('archived_income.voucherType', "ic");
                    $this->db->where('archived_income.Permanent_VoucherNumber !=', NULL);
                    $this->db->where('archived_income.Permanent_VoucherDateG >=', $to);
                    $this->db->where('archived_income.Permanent_VoucherDateG <=', $from);
                    $this->db->where('archived_income.Year', $this->activeyear);
                    $this->db->like('archived_income.Permanent_VoucherDateG', $this->session->userdata('current_year'));
                }
                $data[] = $this->db->get()->result();
            }
            return $data;
        }else{
            return null;
        }
    }

    public function get_by_voucher_no_income($code='',$type,$id)
    {
        if ($this->year_status->Active == 1) {
            $this->db->select('*,income.Id as t_id');
            $this->db->from('income');
            $this->db->join('company_structure', 'company_structure.id = income.LevelID');
            $this->db->join('account_title', 'account_title.id = income.AccountID');
            $this->db->like('VoucherNo', $code);
            $this->db->where('income.LevelID', $id);
            $this->db->where('income.voucherType', "ic");
        }else{
            $this->db->select('*,archived_income.Id as t_id');
            $this->db->from('archived_income');
            $this->db->join('company_structure', 'company_structure.id = archived_income.LevelID');
            $this->db->join('account_title', 'account_title.id = archived_income.AccountID');
            $this->db->like('VoucherNo', $code);
            $this->db->where('archived_income.LevelID', $id);
            $this->db->where('archived_income.voucherType', "ic");
            $this->db->where('archived_income.Year', $this->activeyear);
        }
        return $this->db->get()->result();
    }


    public function GetSumDebitForAmountDescription($id)
    {
        if (isset($_SESSION['comp_id'])){
            $Level_id = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Level_id = $_SESSION['comp'];
        }else{
            $Level_id = '';
        }

        $this->db->select('VoucherNo');
        $this->db->where('Id', $id);
        $voucher_no = $this->db->get('income')->result();

        $this->db->select('SUM(Debit) As Debit');
        $this->db->where('VoucherNo', $voucher_no[0]->VoucherNo);
        $this->db->where('VoucherType', 'IC');
        $this->db->where('LevelID ', $Level_id);
        return $this->db->get('income')->result();
    }

    public function get_amount_descp_cheque($t_id)
    {
        $this->db->select('IFNULL(SUM(Cheque_amount),0) as ChequeAmount');
        $this->db->where('Income_Id', $t_id);
        return $this->db->get('income_cheque_data')->row();
    }

    public function get_amount_descp_cash($t_id)
    {
        $query = $this->db->query('SELECT Currency,SUM(Currency * Currency_Quantity) as CurrencyAmount FROM `income_currency_data` JOIN `currency` ON `currency`.`Id` = `income_currency_data`.`Currency_Id` WHERE `Income_Id` = '.$t_id.' AND currency.Currency != "ریزگاری" ');
        return $query->row();
    }
    public function get_amount_descp_cash_khuly($t_id)
    {
        $query = $this->db->query('SELECT Currency,Currency_Quantity as CurrencyAmount FROM `income_currency_data` JOIN `currency` ON `currency`.`Id` = `income_currency_data`.`Currency_Id` WHERE `Income_Id` = '.$t_id.' AND currency.Currency = "ریزگاری" ');
        return $query->row();
    }
    public function Get_Voucher_No_For_AmountDescription($income_id)
    {
        $this->db->select('VoucherNo');
        $this->db->where('Id', $income_id);
        return $this->db->get('income')->result();
    }
}