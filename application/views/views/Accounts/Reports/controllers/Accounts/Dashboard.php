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
//        echo $book_type;
        $data = $this->BookModel->get_company_by_user();
        //print_r($_SESSION);
        $Access_level = $_SESSION['comp'];
        if ($book_type) {
            echo 'andar agaya';
            $data['transactions'] = $this->BookModel->get_all_transactions($book_type,$Access_level);
            $table = $this->load->view('Accounts/indexTable', $data ,true);
            echo $table;
        }elseif ($book_type == '0') {
            $data['transactions'] = $this->BookModel->get_all_transactions('',$Access_level);
            $table = $this->load->view('Accounts/indexTable', $data ,true);
            echo $table;
        }else{
            $data['transactions'] = $this->BookModel->get_all_transactions('',$Access_level);
//            echo $this->db->last_query();
            $data['Hdate'] = $this->CalenderModel->getHijriDate(date('Y-m-d'));
//            $data['Hdate'] = $this->CalenderModel->getHijriDate('2017-07-25');
            $this->load->view('Accounts/header',$data);
            $this->load->view('Accounts/index',$data);
            $this->load->view('Accounts/footer');
        }
//        $this->output->enable_profiler(TRUE);
    }

    public function all($book_type_search = '')
    {
        $Level_id = $this->session->userdata('comp');

        $to = $_POST['to'];
        $from = $_POST['from'];

//        echo $to.'<br>'.$from;
//echo $book_type_search;
        $data['transactions'] = $this->BookModel->get_all_transactions_on_search($book_type_search,$Level_id,$from,$to);
//         echo $this->db->last_query();
        //        echo $book_type_search;
        if($book_type_search != ''){
            foreach ($data['transactions'] as $key => $transaction) {
//                echo 'book type is no null';
//            echo '<pre>';
//            print_r($transaction);
//            echo '</pre>';
                $sep_seq = '';
                if(isset($transaction->Seprate_series_num)){
                    $sep_seq = $transaction->Seprate_series_num;
                }
                //echo $sep_seq;
                $data['bookAmount'][] = $this->BookModel->get_book_Amount($data['transactions'][0]->VoucherType,$transaction->VoucherNo,$Level_id,$sep_seq?$sep_seq:'');
//                 echo "<pre>";
//                 print_r($data);
//                 exit();
            }
            //        echo $book_type_search.'book ki type print';
            if($book_type_search == 'cr' || $book_type_search == 'cp') {
//           echo $book_type_search;
                $table = $this->load->view('Accounts/books/cashbook/cashbookTable',$data,true);
                echo $table;
//           echo '<pre>';
//           print_r($data['transactions']);
//           echo '</pre>';
            }else if($book_type_search == 'br' || $book_type_search == 'bp') {
//           echo $book_type_search.'book ki type print';
//           echo $book_type_search;
                $table = $this->load->view('Accounts/books/bankbook/bankbookTable',$data,true);
                echo $table;
            }else if($book_type_search == 'jv') {
               // echo 'jv';
                $table = $this->load->view('Accounts/books/generaljournal/GeneralJournalTable',$data,true);
                echo $table;
            }else if($book_type_search == 'inc') {
                //echo 'inc';
                $table = $this->load->view('Accounts/books/income/incomeTable',$data,true);
                echo $table;
            }
            //print_r($data);
        }
       else{
           $table = $this->load->view('Accounts/indexTable',$data,true);
           echo $table;
       }
//        echo $this->db->last_query();
//        echo '<pre>';
//        print_r($data['transactions']);
//        echo exit();
//        foreach ($data['transactions'] as $transaction_2){
//            echo "<tr class='odd gradeX'>
//                <td>$transaction_2->VoucherNo-$transaction_2->VoucherType</td>
//                <td>$transaction_2->DepartmentName</td>
//                <td>$transaction_2->PaidTo</td>
//                <td>$transaction_2->VoucherDateG</td>
//                <td>$transaction_2->VoucherDateH</td>
//                <td><textarea class='form-control' style='height: 35px; width: 120px;' rows='1' readonly>$transaction_2->Remarks</textarea></td>
//                <td>".number_format($transaction_2->debit)."</td>
//            </tr>";
//        }
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