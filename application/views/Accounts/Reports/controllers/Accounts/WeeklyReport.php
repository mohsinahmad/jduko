<?php

class WeeklyReport extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('MaddatModel');
        $this->load->model('ChartModel');
        $this->load->model('CompanyModel');
        $this->load->model('CalenderModel');
        $this->load->model('MaddatModel');
        $this->load->model('ReportConfigurationModel');
        $this->load->model('CustomReportModel');
        $this->load->model('BookModel');
        date_default_timezone_set('Asia/Karachi');
    }

    public function GetReports()
    {
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/weekly/GetWeeklyReport');
        $this->load->view('Accounts/footer');
    }

    public function index($to,$from,$t_type,$serial='',$is_zero = '')
    {
        $data['serial'] = isset($serial) ? $serial : 0;
        $data['with_zero'] = $is_zero == 'undefined' ? 0 : 1;
        $data['markaz'] = $this->GetWeeklymarkaz($to,$from,$t_type);
        $data['CashBalance'] = $this->GetCashBalance($to,$from,$t_type);
        // echo "<pre>";
        // print_r($data['markaz']);
        // exit();
        $this->load->view('Accounts/Reports/weekly/report1',$data);
    }

    public function GetWeeklymarkaz($to,$from,$t_type)
    {
        $report_Id = 2;//$this->input->post('report_id');
        $levelid = array(41,43,44);
        $Pre_date = $this->CalenderModel->getHijriDate($to);
        $Cur_date = $this->CalenderModel->getHijriDate($from);
        $data['To'] = $Pre_date[0]->Qm_date;
        $data['From'] = $Cur_date[0]->Qm_date;
        $data['report_date'] = $Cur_date[0]->Qm_date;
        $printing_date = $this->CalenderModel->getHijriDate(date('Y-m-d'));
        $data['printing_date'] = $printing_date[0]->Qm_date;
        $data['Maddat_Name'] = $this->MaddatModel->Get_Maddat_Name($report_Id);
        $data['Previous'] = $this->MaddatModel->Get_weekly_1($to,$from,$levelid,$report_Id,1,$t_type);
        $data['TillToday'] = $this->MaddatModel->Get_weekly_1($to,$from,$levelid,$report_Id,2,$t_type);
        $data['IsReverse'] = $this->MaddatModel->Get_weekly_1($to,$from,$levelid,$report_Id,2,$t_type,1);
        $data['IsReversePrevious'] = $this->MaddatModel->Get_weekly_1($to,$from,$levelid,$report_Id,1,$t_type,1);

        return $data;

    }

    public function GetCashBalance($to,$from,$t_type)
    {
        $report_Id_bank = 3;//$this->input->post('report_id');
        $levelid = array(41,43,44);
        $previous = 1;
        $Pre_date = $this->CalenderModel->getHijriDate($to);
        $Cur_date = $this->CalenderModel->getHijriDate($from);
        $data['To'] = $Pre_date[0]->Qm_date;
        $data['From'] = $Cur_date[0]->Qm_date;
        $data['report_date'] = $Cur_date[0]->Qm_date;
        $printing_date = $this->CalenderModel->getHijriDate(date('Y-m-d'));
        $data['printing_date'] = $printing_date[0]->Qm_date;

        $data['Maddat_Name_Bank'] = $this->MaddatModel->Get_Maddat_Name($report_Id_bank);
        $data['TillTodayBank'] = $this->MaddatModel->Get_weekly_1_Bank($to,$from,$levelid,$report_Id_bank,2,$t_type);
        $data['PreviousBank'] = $this->MaddatModel->Get_weekly_1_Bank($to,$from,$levelid,$report_Id_bank,1,$t_type);
        $data['IsReverse'] = $this->MaddatModel->Get_weekly_1_Bank($to,$from,$levelid,$report_Id_bank,2,$t_type,1);
        $data['IsReversePreviousBank'] = $this->MaddatModel->Get_weekly_1_Bank($to,$from,$levelid,$report_Id_bank,1,$t_type,1);
        return $data;

    }

    public function CustomReport($to,$from,$tran_of,$serial)
    {
        $data['report'] = $this->ReportConfigurationModel->Reportname();
        $data['to'] = $to;
        $data['from'] = $from;
        $data['tran_of'] = $tran_of;
        $data['serial'] = $serial;
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/weekly/GetCustomReport',$data);
        $this->load->view('Accounts/footer');
    }

    public function DepartmentReport($to,$from,$t_type,$serial,$is_zero)
    {
        $data['to'] = $to;
        $data['from'] = $from;
        $Pre_date = $this->CalenderModel->getHijriDate($to);
        $Cur_date = $this->CalenderModel->getHijriDate($from);
        $data['To'] = $Pre_date[0]->Qm_date;
        $data['From'] = $Cur_date[0]->Qm_date;
        $data['serial'] = $serial;
        $report_Id = 4;
        $R_date = $this->CalenderModel->getHijriDate($from);
        $data['report_date'] = $R_date[0]->Qm_date;
        $pr_date = $this->CalenderModel->getHijriDate(date('Y-m-d'));
        $data['with_zero'] = $is_zero == 'undefined' ? 0 : 1;
        $data['printing_date'] = $pr_date[0]->Qm_date;
        $levels = $this->CompanyModel->getDepartments();
        // $income_acc = $this->ChartModel->getIncomeAcc();
        // $expense_acc = $this->ChartModel->getExpenseAcc();
        // foreach ($income_acc as $key => $item) {
        //     $accs_inc[] = $item->id;
        //     $accs_exp[] = $expense_acc[$key]->id;
        // }
        foreach ($levels as $key => $level) {
            $data['IncTran'][$key] = $this->MaddatModel->GetDepartmentReportTrans($to,$from,$level->Id,$report_Id,0,$t_type,0);
            //$data['ExpTran'][$key] = $this->MaddatModel->GetDepartmentReportTrans($to,$from,$level->Id,$report_Id,0,$t_type,0);
            $data['IncTranR'][$key] = $this->MaddatModel->GetDepartmentReportTrans($to,$from,$level->Id,$report_Id,0,$t_type,1);
            $data['IncTran'][$key][0][0]->Debit = $data['IncTran'][$key][0][0]->Debit - $data['IncTranR'][$key][0][0]->Credit;
            $data['IncTran'][$key][0][0]->Credit = $data['IncTran'][$key][0][0]->Credit - $data['IncTranR'][$key][0][0]->Debit;
            //$data['ExpTranR'][$key] = $this->MaddatModel->GetDepartmentReportTrans($to,$from,$level->Id,$report_Id,0,$t_type,1);
            $data['Maddat_Name'] = $this->MaddatModel->Get_Maddat_Name($report_Id);
            $data['IncTran'][$key]['ClosingBalance'] = $this->MaddatModel->Get_Maddad_Acc_transactions($level->Id,$to,$from,$report_Id,$t_type);

            $data['IncTran'][$key]['LevelName'] = $level->LevelName;
            $data['IncTran'][$key]['Difference'] = $data['IncTran'][$key][0][0]->Debit - $data['IncTran'][$key][0][0]->Credit;

            $data['IncTran'][$key]['BankAddition'] = $data['IncTran'][$key]['ClosingBalance'][0] + $data['IncTran'][$key]['Difference'];
        }
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // exit();
        $this->load->view('Accounts/Reports/weekly/DepartmentReport',$data);
    }

    public function Report2()
    {
        $to = $this->input->post('to');
        $from = $this->input->post('from');
        $Pre_date = $this->CalenderModel->getHijriDate($to);
        $data['report_date'] = $Pre_date[0]->Qm_date;
        $Cur_date = $this->CalenderModel->getHijriDate($from);
        $pr_date = $this->CalenderModel->getHijriDate(date('Y-m-d'));
        $data['printing_date'] = $pr_date[0]->Qm_date;
        $data['To'] = $Pre_date[0]->Qm_date;
        $data['From'] = $Cur_date[0]->Qm_date;
        $data['serial'] = $this->input->post('serial');;
        $previous = 1;
        $report_Id = $this->input->post('Report_id');

        if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        }

        $data['TillToday'] = $this->MaddatModel->GetTrans($to,$from,$Access_level,$report_Id,"");
        $data['Previous'] = $this->MaddatModel->GetTrans($to,$from,$Access_level,$report_Id,$previous);
        $data['Titles'] = $this->CustomReportModel->GetCustomReportData($report_Id,$Access_level);
        $this->load->view('Accounts/Reports/weekly/NewReport',$data);
    }

    public function SetupTransaction()
    {
        if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        }
        $data['tran_of'] = $_POST['tran_of'];
        $data['serial'] = $_POST['serial'];
        $report_Id = $this->input->post('report_id');
        $data['acc'] = $this->MaddatModel->getExpenseMaddadAcc($report_Id);
        $data['customReport'] = $this->CustomReportModel->get_custom_report_titles($report_Id);
        if($data['customReport'] != array()){
            foreach ($data['customReport'] as $key => $value) {
                $acc[] = $this->CustomReportModel->get_title_accounts($value->id);
            }
            $data['selectedAccs'] = $acc;
        }
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/weekly/SetupTransaction', $data);
        $this->load->view('Accounts/footer');
    }

    public function getExpenseAccounts($report_id)
    {
        if (isset($_SESSION['comp_id'])){
            $Access_level = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Access_level = $_SESSION['comp'];
        }else{
            $Access_level = '';
        }
        $acc = $this->MaddatModel->getExpenseMaddadAcc($report_id,$Access_level);
        echo json_encode($acc);
    }

    public function GetAccountTransactions()
    {
        $data['trans'] = $this->MaddatModel->getAccountTrans();
        $trans = $this->load->view('Accounts/Reports/weekly/TransactionTable', $data, TRUE);
        echo $trans;
    }

    public function saveCustomReport()
    {
        $check = $this->CustomReportModel->save_custom_report();
        if($check){
            $this->Report2();
        }else{
            $this->session->set_flashdata('error',"مدّات کنفیگریشن ناکام");
            redirect('Accounts/testController/SetupTransaction','refresh');
        }
    }

    public function updateCustomReport()
    {
        $check = $this->CustomReportModel->update_custom_report();
        if($check){
            $this->Report2();
        }else{
            $this->session->set_flashdata('error',"مدّات تدوین ناکام");
            redirect('Accounts/testController/SetupTransaction','refresh');
        }
    }
}