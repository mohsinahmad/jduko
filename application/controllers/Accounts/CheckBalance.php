<?php
/**
*
*/
class CheckBalance extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('LinkModel');
        $this->load->model('BookModel');
        $this->load->model('CompanyModel');
    }

    public function CheckOpeningBalance()
    {
//        $this->db->query("UPDATE `chart_of_account_years` SET `CurrentBalance` = '-310' WHERE `chart_of_account_years`.`ID` = 1404;");
        if (isset($_SESSION['comp_id'])){
            $Level_id = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Level_id = $_SESSION['comp'];
        }else{ $Level_id = ''; }

        $data['debitandcredit'] = $this->BookModel->GetDebitcredit($Level_id);
        $data['OpenAndCurrent'] = $this->LinkModel->Getopenandcurrent($Level_id);
        $data['level'] = $this->CompanyModel->get_company_name($Level_id);
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/CheckBalance/balance',$data);
        $this->load->view('Accounts/footer');
    }

    public function Update_Balance()
    {
      $check = $this->BookModel->UpdateCurrentBalance();
    }
}