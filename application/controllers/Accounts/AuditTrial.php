<?php

class AuditTrial extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BookModel');
        $this->load->model('CalenderModel');
        $this->load->model('CompanyModel');
    }

    public function index()
    {
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/audittrial/GetAuditTrial');
        $this->load->view('Accounts/footer');
    }

    public function getAuditTrial()
    {
        $data['print'] = $_POST['print'];
        $to = $_POST['to'];
        $from = $_POST['from'];
        $v_rype = $_POST['voucher_type'];

        if (isset($_SESSION['comp_id'])) {
            $company = $_SESSION['comp_id'];
        } elseif (isset($_SESSION['comp'])) {
            $company = $_SESSION['comp'];
        }
        if (!isset($company)) {
            echo '<script>alert("لیول کو منتخب کریں!!!"); window.close();</script>';
        }else{
            $auditOf = $_POST['auditof'];
            $data['all'] = $auditOf;
            $voucher_data = $this->BookModel->getTransactionsForAudit($v_rype, $auditOf, $to, $from, $company);
            $transactions = array();
            $v_num = '';
            $v_ty = '';
            if ($voucher_data != array()) {
                foreach ($voucher_data as $item) {
                    if ($auditOf == 'p'){
                        if ($item->VoucherType . $item->Permanent_VoucherNumber != $v_ty . $v_num) {
                            $transactions[] = $this->BookModel->getTransactionsDataForAudit($item->VoucherType, $item->Permanent_VoucherNumber, $auditOf, $to, $from, $company);
                            $v_num = $item->Permanent_VoucherNumber;
                            $v_ty = $item->VoucherType;
                        }
                    }else{
                        if ($item->VoucherType . $item->VoucherNo != $v_ty . $v_num) {
                            $transactions[] = $this->BookModel->getTransactionsDataForAudit($item->VoucherType, $item->VoucherNo, $auditOf, $to, $from, $company);
                            $v_num = $item->VoucherNo;
                            $v_ty = $item->VoucherType;
                        }
                    }
                }
            } else {
                $transactions = '';
            }
            $data['transactions'] = $transactions;
            $data['to'] = $this->CalenderModel->getHijriDate($to);
            $data['from'] = $this->CalenderModel->getHijriDate($from);
            $data['LevelName'] = $this->CompanyModel->get_company_name($company);

            $this->load->view('Accounts/Reports/audittrial/AuditTrial', $data);
        }
    }
}