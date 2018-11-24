<?php
/**
 *
 */
class RamdanReport extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('MaddatModel');
        date_default_timezone_set('Asia/Karachi');
    }

    public function index()
    {
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/ramzanreport/GetRamdanReport');
        $this->load->view('Accounts/footer');
    }

    public function GetValue()
    {
        $report_Id = 1;
        $levelid = array(41,43,44);
        $date_from = $this->input->post('from');
        $from = date('Y-m-d',strtotime($date_from));

        $data['notes'] = $this->input->post('note');
        $to = '2018-05-17';
        $R_date = $this->CalenderModel->getHijriDate($from);
        $data['report_date'] = $R_date[0]->Qm_date;
        $printing_date = $this->CalenderModel->getHijriDate(date('Y-m-d'));
        $data['printing_date'] = $printing_date[0]->Qm_date;
        $data['Maddat_Name'] = $this->MaddatModel->Get_Maddat_Name($report_Id);
        $data['TillToday'] = $this->MaddatModel->Get_Maddat($to,$from,$levelid,$report_Id);
        $data['TillTodayR'] = $this->MaddatModel->Get_Maddat($to,$from,$levelid,$report_Id,1);
        $data['Today'] = $this->MaddatModel->Get_Maddat('',$from,$levelid,$report_Id);
        $data['TodayR'] = $this->MaddatModel->Get_Maddat('',$from,$levelid,$report_Id,1);

        $this->load->view('Accounts/Reports/ramzanreport/RamdanReport',$data);
    }
}