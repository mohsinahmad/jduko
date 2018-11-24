<?php

class IncomeReport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('CompanyModel');
        $this->load->model('IncomeModel');
        $this->load->model('DepartmentModel');
    }

    public function index()
    {
        $data['departs'] = $this->DepartmentModel->department_name();
        $data['parents'] = $this->CompanyModel->getSubHead(1);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/incomereport/GetIncomeReport', $data);
        $this->load->view('Accounts/footer');
    }

    public function IncomeReport()
    {
        $data['print'] = $_POST['print'];
        $vouchers = array();
        $to = $_POST['to'];
        $from = $_POST['from'];
        $data['toE'] = $to;
        $data['fromE'] = $from;
        if($_POST['depart'] != ""){
            $depart_id = $_POST['depart'];
            $data['depart'] = $_POST['depart'];
        }else{
            $depart_id = $this->DepartmentModel->department_name();
            $data['depart'] = "all";
        }
        $levels = $this->CompanyModel->GetLevels();
        $voucher_type_numbers = $this->IncomeModel->GetIncomeVoucherNumber($levels,$depart_id,$to,$from);
        foreach ($voucher_type_numbers as $key1 => $voucher_type_number) {
            foreach ($voucher_type_number as $key2 => $v_type_number) {
                foreach ($v_type_number as $key3 => $item) {
                    foreach ($item as $key4 => $value) {
                        if($value->DepartmentName == ""){
                            $vouchers['department_name'][$key1][$key2][$key3][$key4] = $value->LevelName;
                            $vouchers['BookNo'][$key1][$key2][$key3][$key4] = $value->BookNo;
                            $vouchers['ReciptNo'][$key1][$key2][$key3][$key4] = $value->ReciptNo;
                        }else{
                            $vouchers['department_name'][$key1][$key2][$key3][$key4] = $value->DepartmentName;
                            $vouchers['BookNo'][$key1][$key2][$key3][$key4] = $value->BookNo;
                            $vouchers['ReciptNo'][$key1][$key2][$key3][$key4] = $value->ReciptNo;
                        }
                        $vouchers['Cheque'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getChequeData($value->Id);
                        $vouchers['ChequeDetails'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getChequeData($value->Id,1);
                        $vouchers['Cash'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getCurrencyData($value->Id);
                        $vouchers['CashDetails'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getCurrencyData($value->Id,1);
                        $vouchers['Total'][$key1][$key2][$key3][$key4] = $vouchers['Cheque'][$key1][$key2][$key3][$key4]->ChequeAmount + $vouchers['Cash'][$key1][$key2][$key3][$key4];
                        $vouchers['ChequeType'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getChequeTypeData($value->Id);
                    }
                }
            }
        }
        $data['vouchers'] = $vouchers;
        $data['to'] = $this->CalenderModel->getHijriDate($to);
        $data['from'] = $this->CalenderModel->getHijriDate($from);
        // echo "<pre>";
        // print_r($data);
        // exit();
        $this->load->view('Accounts/Reports/incomereport/IncomeReport',$data);
    }

    public function TotalAmountDesc()
    {
        $to = $this->uri->segment(4);
        $from = $this->uri->segment(5);
        $depart = $this->uri->segment(6);
        $income_ids = array();
        if($depart != 'all'){
            $depart_id = $depart;
            $data['depart'] = $depart;
        }else{
            $depart_id = $this->DepartmentModel->department_name();
        }
        $levels = $this->CompanyModel->GetLevels();
        $voucher_type_numbers = $this->IncomeModel->GetIncomeVoucherNumber($levels,$depart_id,$to,$from,1);
        foreach ($voucher_type_numbers as $key1 => $voucher_type_number) {
            foreach ($voucher_type_number as $key2 => $v_type_number) {
                foreach ($v_type_number as $key3 => $item) {
                    foreach ($item as $key4 => $value) {
                        $income_ids[] = $value->Id;
                    }
                }
            }
        }
        // $vouchers['Cheque'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getChequeData($value->Id);
        $vouchers['ChequeDetails'] = $this->IncomeModel->getChequeData($income_ids,1);
        //$vouchers['Cash'][$key1][$key2][$key3][$key4] = $this->IncomeModel->getCurrencyData($value->Id);
        $vouchers['CashDetails'] = $this->IncomeModel->getCurrencyData($income_ids,1);
        $vouchers['TotalCheque'] = $this->IncomeModel->getTotalCheque($income_ids);
        $vouchers['TotalCash'] = $this->IncomeModel->getTotalCash($income_ids);
        $vouchers['TotalChequeCash'] = $vouchers['TotalCheque']->ChequeAmount + $vouchers['TotalCash'];
        $vouchers['ChequeType'] = $this->IncomeModel->getChequeTypeData2($income_ids);
        $data['vouchers'] = $vouchers;
        // echo "<pre>";
        // print_r($data);
        // exit();
        // $data['to'] = $this->CalenderModel->getHijriDate($to);
        // $data['from'] = $this->CalenderModel->getHijriDate($from);
        $this->load->view('Accounts/Reports/incomereport/AmountDescriptionReportTotal',$data);
    }
}