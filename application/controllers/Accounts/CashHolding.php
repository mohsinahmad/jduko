<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 11/21/2017
 * Time: 12:58 PM
 */

class CashHolding extends MY_Controller{
    public function __construct()
    {
        parent:: __construct();
        $this->load->model('BookModel');
        $this->load->model('CompanyModel');
    }

    public function index()
    {
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/CashHolding/GetVoucher');
        $this->load->view('Accounts/footer');

    }
    public function CheckVocher(){

        $to = $this->input->post('to');
        $from = $this->input->post('from');

//        echo '<pre>';
//        print_r($_POST);
//        exit();

        $reportdate = $this->input->post('reportdate');
        $data['SaveVoucher'] = $this->BookModel->GetVoucherFromCashHolding($reportdate);
        $RemainingCash = $this->BookModel->GetVoucherFromRemainingCash($reportdate);
        $data['levels'] = $this->CompanyModel->getCompanies();

        foreach ($data['levels']  as $key => $value) {
            $data['Vouchers'][] = $this->BookModel->GetVoucherForCashHolding($value->id,$to,$from);
            //echo $this->db->last_query();
        }

        foreach ($data['Vouchers'] as $V_key => $item) {
            foreach ($item as $A_key => $amount) {
                $data['bookAmount'][$V_key][] = $this->BookModel->get_book_Amount($amount->VoucherType,$amount->VoucherNo,$amount->LevelID,$amount->Seprate_series_num);
            }
        }
        foreach ($data['SaveVoucher'] as $S_key => $SVoucher){
            $GetSaveVoucher[] = $this->BookModel->GetVoucherForCashHoldingSave($SVoucher->LevelId,$SVoucher->VoucherNo);
        }
        if (isset($GetSaveVoucher)){
            foreach ($GetSaveVoucher as $D_key => $datum) {
                $bookAmountSave[] = $this->BookModel->get_book_Amount($datum[0]->VoucherType,$datum[0]->VoucherNo,$datum[0]->LevelID,$datum[0]->Seprate_series_num);
            }
        }
        if(isset($GetSaveVoucher)){
            $data['GetSaveVoucher'] = $GetSaveVoucher;
        }
        if (isset($bookAmountSave)){
            $data['bookAmountSave'] = $bookAmountSave;
        }

       // echo $this->db->last_query();

        $data['RemainingCash'] = $RemainingCash;

        $data['Reportdate'] = $reportdate;

        // echo "<pre>";
        // print_r($data);
        // exit();
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/CashHolding/CheckVoucher',$data);
        $this->load->view('Accounts/footer');
    }

// sufyan work start

    public function s_CheckVocher(){
        $to = $this->input->post('to');
        $from = $this->input->post('from');
        $reportdate = $this->input->post('reportdate');

      //  echo $reportdate;

        // this voucher saved in cashholding
        //$report_data = 'reportdate = (SELECT SUBDATE(CURDATE(),1))';
        $result = $this->db->query('SELECT Reportdate FROM `cash_holding` WHERE reportdate = (SELECT MAX(Reportdate)) limit 1')->result();
        if($result != '' && $result != null) {
           $date = $result[0]->Reportdate;
        }
        else{
            $date = $reportdate;
        }
        $data['SaveVoucher'] = $this->BookModel->GetVoucherFromCashHolding($date);
        //echo $this->db->last_query();
        $RemainingCash = $this->BookModel->GetVoucherFromRemainingCash($date);
            $data['levels'] = $this->CompanyModel->getCompanies();
            $data['Vouchers'][] = $this->BookModel->s_GetVoucherForCashHolding($to,$from);
           foreach ($data['Vouchers'] as $V_key => $item) {
            foreach ($item as $A_key => $amount) {
                $data['bookAmount'][$V_key][] = $this->BookModel->get_book_Amount($amount->VoucherType,$amount->VoucherNo,$amount->LevelID,$amount->Seprate_series_num);
            }
        }
        foreach ($data['SaveVoucher'] as $S_key => $SVoucher){
            $GetSaveVoucher[] = $this->BookModel->GetVoucherForCashHoldingSave($SVoucher->LevelId,$SVoucher->VoucherNo);
        }
        if (isset($GetSaveVoucher)){
            foreach ($GetSaveVoucher as $D_key => $datum) {
                $bookAmountSave[] = $this->BookModel->get_book_Amount($datum[0]->VoucherType,$datum[0]->VoucherNo,$datum[0]->LevelID,$datum[0]->Seprate_series_num);
            }
        }
        if(isset($GetSaveVoucher)){
         $data['GetSaveVoucher'] = $GetSaveVoucher;
        }
        if (isset($bookAmountSave)){
            $data['bookAmountSave'] = $bookAmountSave;
        }
        $data['RemainingCash'] = $RemainingCash;
        $data['Reportdate'] = $reportdate;
     //print_r($data['Reportdate']);
        $data['from'] = $to;
        $data['to'] = $from;


       // echo '<pre>';
       // print_r($data);
       // exit();
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/CashHolding/CheckVoucher',$data);
        $this->load->view('Accounts/footer');
    }

// end sufyan work

    public function ViewCashHoldingReport()
    {
        if(isset($_POST['voucher'])){
            $data['from'] =  $_POST['voucher'];
            $data['recieve'] =  $_POST['cashrecive'];
            $todate = $_POST['date-to'];
        }
        $final = array();
        $final1=array();
        $date_today = date('y-m-d');
        $previos_date = date('Y-m-d', strtotime('-1 day', strtotime($date_today)));
        $this->db->empty_table('cash_holding');
        $this->db->empty_table('remaining_cash');
       // print_r($_POST);
        foreach ($_POST['getvouchers'] as $key => $value) {

            $this->db->set('VoucherNo',$_POST['voucherNo'][$key]);
            $this->db->set('VoucherType',$_POST['vouchertype'][$key]);  /*insert data in table for hold record*/
            $this->db->set('LevelId',$_POST['levelID'][$key]);
            $this->db->set('Reportdate',$_POST['reportdate']);
            $this->db->insert('cash_holding');
            $voucherno = $_POST['voucherNo'][$key];
            $vouchertype = $_POST['vouchertype'][$key];
            $VoucherDateH = $_POST['VoucherDateH'][$key];
            $VoucherDateG = $_POST['VoucherDateG'][$key];
            $Remarks = $_POST['Remarks'][$key];
            $credit = $_POST['credit'][$key];

            $final[] = array($voucherno,$vouchertype,$VoucherDateH,$VoucherDateG,$Remarks,$credit);
        }
        $this->db->where('reportdate' ,$_POST['reportdate']);
        $this->db->delete('remaining_cash');
        foreach ($_POST['otherexpdetail'] as $E_key => $item){
            $this->db->set('remainingCash',$_POST['remainingCash']);
            $this->db->set('holdcash',$_POST['holdcash']);  /*insert data in table for hold record*/
            $this->db->set('holdcashdate',$_POST['holdcashdate']);
            $this->db->set('cashrecive',$_POST['cashrecive']);
            $this->db->set('pageno',$_POST['pageno']);
            $this->db->set('reportdate',$_POST['reportdate']);
            $this->db->set('otherexp',$_POST['otherexp'][$E_key]);
            $this->db->set('otherexpdetail',$_POST['otherexpdetail'][$E_key]);
            $this->db->insert('remaining_cash');

            $otherexpdetail = $_POST['otherexpdetail'][$E_key];
            $otherexp = $_POST['otherexp'][$E_key];

            $final1[] = array($otherexpdetail,$otherexp);
        }


 //echo $_POST['reportdate'];
        $data['exvoucher'] = $final;
        $data['remainingCash'] = $this->input->post('remainingCash');
        $data['holdcash'] = $this->input->post('holdcash');
        $data['holdcashdate'] = $this->input->post('holdcashdate');
        $data['otherexp'] = $final1;
        $data['cashrecive'] = $this->input->post('cashrecive');
        $data['pageno'] = $this->input->post('pageno');
        $data['todate'] = $todate;
//         echo "<pre>";
//         print_r($data);
//         exit();
        $this->load->view('Accounts/Reports/CashHolding/CashHoldingReport',$data);
    }

    public function DeleteOldvoucher($date){

        $check = $this->BookModel->DeleteVoucher_old($date);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }
}