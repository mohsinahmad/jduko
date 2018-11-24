<?php

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 6/15/2017
 * Time: 10:02 AM
 */
class TaxDeductionReport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('BookModel');
        $this->load->model('SupplierModel');
    }

    public function index($report_form)
    {
        if ($report_form == 1){
            $this->load->view('Accounts/header');
            $this->load->view('Accounts/Reports/IncomeTax/GetTaxReport');
            $this->load->view('Accounts/footer');
        }else{
            $to = $this->input->post('to');
            $from = $this->input->post('from');
            $data['to'] = $this->CalenderModel->getHijriDate($to);
            $data['from'] = $this->CalenderModel->getHijriDate($from);
            $data['Suppliers'] = $this->SupplierModel->GetSupplier();
            foreach ($data['Suppliers'] as $key => $supplier) {
                $TaxData[] = $this->BookModel->GetTaxDeductionVouchers($supplier->ChartOfAcc_id,$to,$from);
                $data['chartId'][] = $supplier->ChartOfAcc_id;
            }
            foreach ($TaxData as $tax_Key => $taxDatum) {
                foreach ($taxDatum as $item_key => $item) {
                    $data['TaxData'][$tax_Key][] = $this->BookModel->GetTaxDeductionData($item->VoucherNo,$item->VoucherType,$to,$from);
                }
            }
            $this->load->view('Accounts/Reports/IncomeTax/TaxReport',$data);
        }
    }

    public function GetTaxDeductionReport2()
    {
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/IncomeTax/GetTaxReport2');
        $this->load->view('Accounts/footer');

    }
    public function TexDeductionReport2()
    {   
        $TaxData = array();
        $to =$this->input->post('to');
        $from =$this->input->post('from');
        $data['Suppliers'] = $this->SupplierModel->GetSupplier();
        foreach ($data['Suppliers'] as $key => $supplier) {
            $TaxData[] = $this->BookModel->GetTaxDeductionVouchers($supplier->ChartOfAcc_id,$to,$from);
            $data['chartId'][] = $supplier->ChartOfAcc_id;
        }
        foreach ($TaxData as $tax_Key => $taxDatum) {
            foreach ($taxDatum as $item_key => $item) {
                $data['TaxData'][$tax_Key][] = $this->BookModel->GetTaxDeductionData($item->VoucherNo,$item->VoucherType,$to,$from);
            }
        }
        $data['to'] = $to;
        $data['from'] = $from;

//        echo '<pre>';
//        print_r($data);
//        exit();
        $this->load->view('Accounts/Reports/IncomeTax/TaxReport2',$data);
    }

}