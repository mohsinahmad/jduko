<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/21/2017
 * Time: 10:47 AM
 */

class ReceiptReport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurSlipModel');
    }

    public function index()
    {
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/Reports/GetSlipReport');
        $this->load->view('Qurbani/footer');
    }

    public function getReport()
    {
        $from = $_POST['to'];
        $to = $_POST['from'];
        $days = array(1,2,3);
        $details = array();
        $report_data = array();
        $dates = $this->QurSlipModel->Get_Dates_For_Report($from,$to);
        foreach ($dates as $key => $date) {
            $details[$key]['share'] = $this->QurSlipModel->getReportData($date->Slip_Date_G,$days,0);
            $details[$key]['self'] = $this->QurSlipModel->getReportData($date->Slip_Date_G,$days,1);
        }
        $count = 0; $total_quantity = 0; $total_Amount = 0;$Collection_Day = 0; $Slip_Date_G = 0;

        foreach ($details as $key_1 => $datum) {
            foreach ($datum as $key_2 => $item) { // $key_2 -> self/share
                foreach ($item as $key_3 => $value) {
                    foreach ($value as $key_4 => $val) {
                        $count++;
                        $total_quantity += $val->Quantity;
                        $total_Amount += $val->Total_Amount;
                        $Collection_Day = $val->Collection_Day;
                        $Slip_Date_G = $val->Slip_Date_G;
                    }
                    $report_data[$key_1][$key_2][$key_3]['Collection_Day'] = $Collection_Day;
                    $report_data[$key_1][$key_2][$key_3]['Slip_Date_G'] = $Slip_Date_G;
                    $report_data[$key_1][$key_2][$key_3]['slip_count'] = $count;
                    $report_data[$key_1][$key_2][$key_3]['Quantity'] = $total_quantity;
                    $report_data[$key_1][$key_2][$key_3]['Total_Amount'] = $total_Amount;
                    $count = 0; $total_quantity = 0; $total_Amount = 0;$Collection_Day = 0; $Slip_Date_G = 0;
                }
            }
        }
        $data['report_dates'] = $dates;
        $data['report_data'] = $report_data;
        $this->load->view('Qurbani/Reports/SlipReport',$data);
    }
}