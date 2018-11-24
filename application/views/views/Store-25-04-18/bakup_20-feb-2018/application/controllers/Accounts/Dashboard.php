<?php

/**
 * Created by PhpStorm.
 * User: HP
 * Date: 12/21/2016
 * Time: 2:25 PM
 */
class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!($this->session->userdata('in_use'))){
            redirect('login','refresh');
        }
        $this->load->model('CalenderModel');
        $this->load->model('BookModel');
        $this->load->model('DashboardModel');
    }
    
    public function login(){
        $this->load->view('login');
    }

    public function menu()
    {
        $this->load->view('MainMenu');
    }

    public function index($book_type='')
    {



        $data = $this->BookModel->get_company_by_user();
        //print_r($_SESSION);
        $Access_level = $_SESSION['comp'];

        if ($book_type) {
            $data['transactions'] = $this->BookModel->get_all_transactions($book_type,$Access_level);
            $table = $this->load->view('Accounts/indexTable', $data ,true);
//            echo $table;
        }elseif ($book_type == '0') {
            $data['transactions'] = $this->BookModel->get_all_transactions('',$Access_level);
            $table = $this->load->view('Accounts/indexTable', $data ,true);
//            echo $table;
        }else{
            $data['transactions'] = $this->BookModel->get_all_transactions('',$Access_level);
//            echo $this->db->last_query();
            $data['Hdate'] = $this->CalenderModel->getHijriDate(date('Y-m-d'));
//            $data['Hdate'] = $this->CalenderModel->getHijriDate('2017-07-25');
            $this->load->view('Accounts/header',$data);
            $this->load->view('Accounts/index',$data);
            $this->load->view('Accounts/footer');
        }
    }

    public function all()
    {
        $Level_id = $this->session->userdata('comp');
        $data['transactions'] = $this->BookModel->get_all_transactions('',$Level_id);
        $table = $this->load->view('Accounts/indexTable', $data ,true);
        echo $table;
    }

    public function GetByVoucherNo($voucherno = '')
    {
        if ($voucherno) {
            $Level_id = $this->session->userdata('comp');
            $data['transactions'] = $this->BookModel->getVoucher($voucherno,$Level_id);
            $table = $this->load->view('Accounts/indexTable', $data ,true);
            echo $table;
        }
    }

    public function GetByVoucherNoAndType($voucherno = '' ,$book_type='' )
    {
        if ($voucherno !='' || $book_type !='') {
            $Level_id = $this->session->userdata('comp');
            $data['transactions'] = $this->BookModel->get_transaction_by_VouchernoAndType($voucherno,$book_type,$Level_id);
            // print_r($data);exit();
            $table = $this->load->view('Accounts/indexTable', $data ,true);
            echo $table;
        }

    }

    public function GetTransactionByDate($book_type= '',$Level_id='')
    {
        $to = $_POST['to'];
        $from = $_POST['from'];

        $data['transactions'] = $this->BookModel->get_transaction_date($to,$from,$book_type,$Level_id);
        $table = $this->load->view('Accounts/indexTable', $data, true);
        echo $table;
    }

    public function setYear($year)
    {
        $session = array('current_year' => $year);
        $this->session->set_userdata($session);
        echo true;
    }
}