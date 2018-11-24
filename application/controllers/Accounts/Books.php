<?phpdefined('BASEPATH') OR exit('No direct script access allowed');class Books extends MY_Controller{    function __construct()    {        parent::__construct();        $this->load->model('BookModel');        $this->load->model('UserModel');        $this->load->model('DepartmentModel');        $this->load->model('ChartModel');        $this->load->model('YearModel');        $this->load->model('CompanyModel');        $this->load->model('IncomeModel');        $this->load->model('SupplierModel');    }    public function TestVoucher()    {        $this->load->view('Accounts/TestVoucher');    }    public function transaction_check()    {        $this->BookModel->transaction_check();    }    public function GetDepart($id)    {        $depart = $this->IncomeModel->Get_Depart($id);        echo json_encode($depart);    }    public function AllBooks($book_type = null,$Level_id = null)    {        // echo $Level_id;        if($book_type == 'inc'){            $data['transactions'] = $this->IncomeModel->get_transactions($Level_id);        }else{        $data['transactions'] = $this->BookModel->get_transactions($book_type,$Level_id);                      // echo $this->db->last_query();            }       // echo $this->db->last_query();        foreach ($data['transactions'] as $key => $transaction) {             $sep_seq = '';            if(isset($transaction->Seprate_series_num)){                    $sep_seq = $transaction->Seprate_series_num;            }           $data['bookAmount'][] = $this->BookModel->get_book_Amount($data['transactions'][0]->VoucherType,$transaction->VoucherNo,$Level_id,$sep_seq?$sep_seq:'','not');     // echo $this->db->last_query();     // exit();       }           // echo '<pre>';           // print_r($data);           // exit();        $this->load->view('Accounts/header');        if ($book_type == 'cr' || $book_type == 'cp'){            $this->load->view('Accounts/books/cashbook/cashbook',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');        }elseif ($book_type == 'br' || $book_type == 'bp'){            $this->load->view('Accounts/books/bankbook/bankbook',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');        }elseif($book_type == 'inc'){            $this->load->view('Accounts/books/income/income',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/incomeJs');        }else{            $this->load->view('Accounts/books/generaljournal/GeneralJournal',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');        }   }    public function AddTransaction($book_type = '',$comp_id ='')    {        $data['departments'] = $this->DepartmentModel->department_name();        if($book_type == "inc"){            $data['accounts'] = $this->DepartmentModel->get_accounts($comp_id);        }else{            $data['accounts'] = $this->DepartmentModel->account_name($comp_id);        }          //echo $this->db->last_query();         // echo '<pre>';        //print_r($data['accounts']);       // echo '</pre>';        $this->load->view('Accounts/header');        if ($book_type == 'cr' || $book_type == 'cp'){            $this->load->view('Accounts/books/cashbook/CashBookPage',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');        }elseif ($book_type == 'br' || $book_type == 'bp'){            $data['suppliers'] = $this->SupplierModel->GetSupplierAccountId($comp_id);            $this->load->view('Accounts/books/bankbook/BankBookPage',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/banktransactionJs');        }elseif($book_type == 'inc'){            $this->load->view('Accounts/books/income/incomePage',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/incomeJs');        }else{            $this->load->view('Accounts/books/generaljournal/GeneralJournalPage',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/jvtransactionJs');        }    }// work will start from here tommorow    public function SaveTransaction($book_type,$company_id)    {//        echo '<pre>';//        print_r($_POST);//        exit();        //echo $book_type.'/'.$company_id;        $check = $this->BookModel->save_transaction($company_id,$book_type);        // echo $this->db->last_query();        // exit();        if($check){            $this->session->set_flashdata('success', 'ٹراسیکشن  کامیابی سے ہو گئ');             redirect('Accounts/Books/AllBooks/'.$book_type.'/'.$company_id,'refresh');        }    }    public function SaveIncTransaction($book_type,$company_id)    {        $check = $this->IncomeModel->save_transaction($company_id);        if($check){            $this->session->set_flashdata('success', 'ٹراسیکشن  کامیابی سے ہو گئ');           redirect('Accounts/Books/AllBooks/'.$book_type.'/'.$company_id,'refresh');        }    }    public function EditTransaction($id,$comp_id,$type,$is_permint="")    {        $booktype = $this->BookModel->get_type($id);        $booktype = $booktype[0]->VoucherType;        $data['departments'] = $this->DepartmentModel->department_name();        $data['accounts'] = $this ->DepartmentModel->account_name($comp_id);        $data['transaction'] = $this->BookModel->get_edit_transaction($id,$comp_id);                // echo '<pre>';        // print_r($data['transaction']);        // exit();        // echo $this->db->last_query();        // exit();          $this->load->view('Accounts/header');        if ($type == 1){            if ($booktype == 'CP' || $booktype == 'CR'){                $this->load->view('Accounts/books/cashbook/cashbookPerEdit',$data);                $this->load->view('Accounts/footer');                $this->load->view('Accounts/js/transactionJs');            }elseif ($booktype == 'BP' || $booktype == 'BR'){                $this->load->view('Accounts/books/bankbook/bankbookperEdit',$data);                $this->load->view('Accounts/footer');                $this->load->view('Accounts/js/banktransactionJs');            }else{                $this->load->view('Accounts/books/generaljournal/GeneralJournalPerEdit',$data);                $this->load->view('Accounts/footer');                $this->load->view('Accounts/js/jvtransactionJs');            }        }else{            if ($booktype == 'CP' || $booktype == 'CR'){                $this->load->view('Accounts/books/cashbook/CashBookPageEdit',$data);                $this->load->view('Accounts/footer');                $this->load->view('Accounts/js/transactionJs');            }elseif ($booktype == 'BP' || $booktype == 'BR'){                $this->load->view('Accounts/books/bankbook/BankBookPageEdit',$data);                $this->load->view('Accounts/footer');                $this->load->view('Accounts/js/banktransactionJs');            }else{                $this->load->view('Accounts/books/generaljournal/GeneralJournalPageEdit',$data);                $this->load->view('Accounts/footer');                $this->load->view('Accounts/js/jvtransactionJs');            }        }    }    public function EditIncTransaction($id,$comp_id,$type)    {        $data['departments'] = $this->DepartmentModel->department_name();        $data['accounts'] = $this->DepartmentModel->get_accounts($comp_id);        $data['transaction'] = $this->IncomeModel->get_edit_transaction($id,$comp_id);        $this->load->view('Accounts/header');        if ($type == 1){            $this->load->view('Accounts/books/income/incomePerEdit',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/incomeJs');        }else{            $this->load->view('Accounts/books/income/incomePageEdit',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/incomeJs');        }    }    public function permanentVoucher($type,$comp_id='')    {        // echo '<pre>';        // print_r($_SESSION);        // exit();        if($type == 'inc'){            $data['transactions'] = $this->IncomeModel->get_permanent_voucher($comp_id);        }else{            // echo 'sdfsdf';            $data['transactions'] = $this->BookModel->get_permanent_voucher($type,$comp_id);            // echo $this->db->last_query();           // echo '<pre>';           // print_r($data['transactions']);           // exit();        }        // echo $this->db->last_query();        $this->load->view('Accounts/header');        foreach ($data['transactions'] as $transaction) {            $data['bookAmount'][] = $this->BookModel->get_book_Amount($data['transactions'][0]->VoucherType,$transaction->VoucherNo,$comp_id,$transaction->Seprate_series_num);// echo '<pre>'.$this->db->last_query();        }// echo '<pre>';// print_r($data['bookAmount']);// exit();        if($type == 'cr')        {            $this->load->view('Accounts/books/cashbook/permanentCashbook',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');            $this->load->view('Accounts/js/permanentCashJs');        }elseif ($type == 'cp') {//            echo "<pre>";//            print_r($data);//            exit();            $this->load->view('Accounts/books/cashbook/permanentCashbook',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');            $this->load->view('Accounts/js/permanentCashJs');        }elseif ($type == 'br') {            $this->load->view('Accounts/books/bankbook/permanentBankbook',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');            $this->load->view('Accounts/js/permanentBankJs');        }elseif ($type == 'bp') {            $this->load->view('Accounts/books/bankbook/permanentBankbook',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');            $this->load->view('Accounts/js/permanentBankJs');        }elseif ($type == 'jv') {            $this->load->view('Accounts/books/generaljournal/permanentGeneraljournal',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/transactionJs');            $this->load->view('Accounts/js/permanentGeneralJournalJs');        }elseif($type == 'inc'){            $this->load->view('Accounts/books/income/permanentIncome',$data);            $this->load->view('Accounts/footer');            $this->load->view('Accounts/js/incomeJs');        }    }    public function getDate($dates)    {        $data = $this->BookModel->get_date($dates);        $date = array('date' => $data[0]->Qm_date );        echo json_encode($date);    }    public function getAccount($id)    {        $data = $this->BookModel->get_account($id);        echo json_encode($data);    }    public function getBooksAccToUser($id)    {        $comp = array('comp' => $id);        $this->session->set_userdata( $comp );        $data = $this->BookModel->get_books_acc_to_user($id);        $data = array(            'level_id' => $data[0]->levelId );        echo json_encode($data);    }    public function getAccountBalance($id,$compId)    {        $data = $this->BookModel->get_account_balance($id,$compId);        $currBal = array('currBal' => $data[0]->CurrentBalance );        echo json_encode($currBal);    }    public function getCompanyName($id)    {        $this->session->unset_userdata('comp_id');        $data = $this->BookModel->get_company_name($id);        $comp = array('comp' => $id,            'parent_id' => $data[0]->ParentCode);        $this->session->set_userdata( $comp );        if (isset($data[0])) {            $level_name = $this->CompanyModel->get_parent_Name($data[0]->ParentCode);            $name = array(                'Name' => $data[0]->LevelName,                'id' => $data[0]->id,                'par_name' => $level_name);            echo json_encode($name);        }else{            $name = array(                'Name' => '',                'id' => '',                'par_name' => '');            echo json_encode($name);        }    }    public function UpdateTransactions($type = '',$lev = '',$count)    {        if ($count == 1) {            $company_id = $_POST['LevelID'];            $booktype = strtolower($_POST['VoucherType']);            $page = 'permanentVoucher';            $check = $this->BookModel->Per_Update_Transactions();            if($check){                $this->session->set_flashdata('success', 'ٹراسیکشن تدوین کامیاب');                redirect('Accounts/Books/'.$page.'/'.$booktype.'/'.$company_id,'refresh');            }else{                $this->session->set_flashdata('error', 'ٹراسیکشن تدوین ناکام');                redirect('Accounts/Books/'.$page.'/'.$booktype.'/'.$company_id,'refresh');            }        }else{            $company_id = $_POST['LevelID'];            $booktype = strtolower($_POST['VoucherType']);            $check = $this->BookModel->Update_Transactions();            if($check){                $this->session->set_flashdata('success', 'ٹراسیکشن تدوین کامیاب');                redirect('Accounts/Books/AllBooks/'.$booktype.'/'.$company_id,'refresh');            }else{                $this->session->set_flashdata('error', 'ٹراسیکشن تدوین ناکام');                redirect('Accounts/Books/AllBooks/'.$booktype.'/'.$company_id,'refresh');            }        }    }    public function UpdateIncTransactions($type = '',$lev = '',$count)    {        if ($count == 1) {            $company_id = $_POST['LevelID'];            $page = 'permanentVoucher';            $check = $this->IncomeModel->Per_Update_Transactions();            if($check){                $this->session->set_flashdata('success', 'ٹراسیکشن تدوین کامیاب');                redirect('Accounts/Books/'.$page.'/inc/'.$company_id,'refresh');            }else{                $this->session->set_flashdata('error', 'ٹراسیکشن تدوین ناکام');                redirect('Accounts/Books/'.$page.'/inc/'.$company_id,'refresh');            }        }else{            $company_id = $_POST['LevelID'];            $booktype = strtolower($_POST['VoucherType']);            if($booktype == "ic"){                $booktype = "inc";            }            $check = $this->IncomeModel->Update_Transactions();            if($check){                $this->session->set_flashdata('success', 'ٹراسیکشن تدوین کامیاب');                redirect('Accounts/Books/AllBooks/'.$booktype.'/'.$company_id,'refresh');            }else{                $this->session->set_flashdata('error', 'ٹراسیکشن تدوین ناکام');                redirect('Accounts/Books/AllBooks/'.$booktype.'/'.$company_id,'refresh');            }        }    }    public function deleteTransaction($voucherno, $voucherType,$levelid)    {        $check = $this->BookModel->delete_transaction($voucherno,$voucherType,$levelid);        if($check){            $response = array('success' => "ok");        }else{            $response = array('error' => "ok");        }        echo json_encode($response);    }    public function deleteIncTransaction($voucherno,$levelid)    {        $check = $this->IncomeModel->delete_transaction($voucherno,$levelid);        if($check){            $response = array('success' => "ok" );        }else{            $response = array('error' => "ok" );        }        echo json_encode($response);    }        public function getTransactionByDate($book_type='',$id='')    {        $to = $_POST['to'];        $from = $_POST['from'];        $data['transactions'] = $this->BookModel->get_transaction_by_date($book_type,$id,$to,$from);        foreach ($data['transactions'] as $transaction) {            $data['bookAmount'][] = $this->BookModel->get_book_Amount($from,$transaction->VoucherType,$transaction->VoucherNo,$transaction->LevelID,$transaction->Seprate_series_num,'no');        }            $table = $this->load->view('Accounts/books/cashbook/cashbookTable', $data, true);            echo $table;    }    public function getTransactionByDateIncome($book_type='',$id='')    {        $to = $_POST['to'];        $from = $_POST['from'];        $data['transactions'] = $this->IncomeModel->get_transaction_by_date_income($book_type,$id,$to,$from);//echo '<pre>';//print_r($data['transactions']);//exit();        $table = $this->load->view('Accounts/books/income/incomeTable', $data, true);        echo $table;    }        public function getPermanentTranByDate($book_type='',$id='')    {        $to = $_POST['to'];        $from = $_POST['from'];        $data['transactions'] = $this->BookModel->get_permanent_trans_bydate($from,$book_type,$id,$to,$from);            // echo $this->db->last_query();// print_r($data['transactions']);// exit();        foreach ($data['transactions'] as $transaction) {            $data['bookAmount'][] = $this->BookModel->get_book_Amount($from,$transaction->VoucherType,$transaction->VoucherNo,$transaction->LevelId,$transaction->Seprate_series_num);        }// echo $this->db->last_query();// print_r($data);// exit();                $table = $this->load->view('Accounts/books/cashbook/permanentCashbookTable', $data, true);        echo $table;    }    public function getPermanentTranByDateIncome($book_type='',$id='')    {        $to = $_POST['to'];        $from = $_POST['from'];        $data['transactions'] = $this->IncomeModel->get_permanent_trans_bydate_income($book_type,$id,$to,$from);        $table = $this->load->view('Accounts/books/income/permanentIncomeTable', $data, true);        echo $table;    }    public function getPerTransByDateAndAccountCode($type,$id)    {        $tran = $this->BookModel->get_permanent_trans_by_dateAndAccountCode($type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/cashbook/cashbookTable', $data, true);            echo $table;        }    }    public function getPerTransByDateAndAccountCodeIcome($type,$id)    {          $tran = $this->IncomeModel->get_permanent_trans_by_dateAndAccountCodeIcome($type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/income/permanentIncomeTable', $data, true);            echo $table;        }    }    public function lastCompanyByUser()    {        $one = array(            'one' => true        );        $this->session->set_userdata($one);        $data = $this->BookModel->get_company_by_user();        $lastComp = array(            'name' => $data[0]->LevelName,            'comp_id' => $data[0]->LevelID,            'parent_id' => $data[0]->ParentCode        );        $this->session->set_userdata( $lastComp );        $level_name = $this->CompanyModel->get_parent_Name($data[0]->ParentCode);        $name = array('Name' => $data[0]->LevelName,            'id' => $data[0]->LevelID,            'par_name' => $level_name);        echo json_encode($name);    }    public function getByVoucherNo($code='',$type='',$id='')    {        if($code)        {            $data['transactions'] = $this->BookModel->get_by_voucher_no($code,$type,$id);            $table = $this->load->view('Accounts/books/cashbook/cashbookTable', $data, true);            echo $table;        }    }    public function getByVoucherNoIcome($code='',$type='',$id='')    {        if($code)        {            $data['transactions'] = $this->IncomeModel->get_by_voucher_no_income($code,$type,$id);            $table = $this->load->view('Accounts/books/income/incomeTable', $data, true);            echo $table;        }    }    public function getAll($type='',$id='')    {        $data['transactions'] = $this->BookModel->get_transactions($type,$id);        $table = $this->load->view('Accounts/books/cashbook/cashbookTable', $data, true);        echo $table;    }    public function GetAllIncome($type='',$id='')    {        $data['transactions']=$this->IncomeModel->get_transactions_Income($type,$id);        $table =$this->load->view('Accounts/books/income/incomeTable', $data, true);        echo $table;    }    public function getAllPer($type='',$id='')    {        $data['transactions'] = $this->BookModel->get_per_transactions($type,$id);        $table = $this->load->view('Accounts/books/cashbook/permanentCashbookTable', $data, true);        echo $table;    }    public function getAllPerIncome($type='',$id='')    {        $data['transactions'] = $this->IncomeModel->get_per_transactions_income($type,$id);        $table = $this->load->view('Accounts/books/income/permanentIncomeTable', $data, true);        echo $table;    }    public function getByAccountCode($code='',$type='',$id='')    {        $tran = $this->BookModel->get_by_account_code($code,$type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/cashbook/cashbookTable', $data, true);            echo $table;        }    }    public function getByAccountCodeIncome($code='',$type='',$id='')    {        $tran = $this->IncomeModel->get_by_account_code_income($code,$type,$id);        if (!empty($tran)) {            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/income/incomeTable', $data, true);            echo $table;        }    }    public function getPerTransByAccountCode($code='',$type='',$id='')    {        $tran = $this->BookModel->get_per_trans_by_account_code($code,$type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/cashbook/permanentCashbookTable', $data, true);            echo $table;        }    }    public function getPerTransByAccountCodeIncome($code='',$type='',$id='')    {        $tran = $this->IncomeModel->get_per_trans_by_account_code_income($code,$type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/income/permanentIncomeTable', $data, true);            echo $table;        }    }    public function getTransactionByDateAndAccountCode($type='',$id='')    {        $tran = $this->BookModel->get_transaction_by_dateAndAccountCode($type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/cashbook/cashbookTable', $data, true);            echo $table;        }    }    public function getTransactionByDateAndAccountCodeIncome($type='',$id='')    {        $tran = $this->IncomeModel->get_transaction_by_dateAndAccountCode_income($type,$id);        if(!empty($tran)){            $data['transactions'] = $tran;            $data['type'] = 'AccountCode';            $table = $this->load->view('Accounts/books/income/incomeTable', $data, true);            echo $table;        }    }    public function getAccountCode($id)    {        header('Content-Type: application/json;charset=UTF-8');        $data = $this->ChartModel->get_account_code($id);        $AccCode = array('_id' => $data[0]->id,            '_code' => $data[0]->AccountCode,            '_name' => $data[0]->AccountName,            '_type' => $data[0]->Type );        echo json_encode($AccCode);    }    public function getBankAccountCode($id)    {        $data = $this->ChartModel->get_account_code($id);        $AccCode = array('_id' => $data[0]->id,            '_code' => $data[0]->AccountCode,            '_name' => $data[0]->AccountName );        echo json_encode($AccCode);    }    public function checkType($id)    {        $data = $this->ChartModel->check_type($id);        $type = array('type' => $data[0]->Type );        echo json_encode($type);    }    public function getMoveTransaction($id)    {        $data = $this->BookModel->get_move_transaction($id);        // print_r($data);       // echo $this->db->last_query();        foreach ($data as $key => $trans){            $tran[] = array(                '_voucherNo' => $trans->VoucherNo,                '_ChequeNumber' => $trans->ChequeNumber,                '_ChequeDate' => $trans->ChequeDate,                '_voucherType' => $trans->VoucherType,                '_trans_id' => $trans->t_id,                '_accountName' => $trans->AccountName,                '_accountType' => $trans->Type,                '_accountDebit' => $trans->Debit,                '_accountCredit' => $trans->Credit,                '_Seprate_series_num' => $trans->Seprate_series_num,                '_levelID' => $trans->LevelID );        }        echo json_encode($tran);    }    public function getIncMoveTransaction($id)    {        $data = $this->IncomeModel->get_move_transaction($id);        foreach ($data as $key=>$trans){            $tran[] = array(                '_voucherNo' => $trans->VoucherNo,                '_ChequeNumber' => $trans->ChequeNumber,                '_ChequeDate' => $trans->ChequeDate,                '_voucherType' => $trans->VoucherType,                '_trans_id' => $trans->t_id,                '_accountName' => $trans->AccountName,                '_accountType' => $trans->Type,                '_accountDebit' => $trans->Debit,                '_accountCredit' => $trans->Credit,                '_levelID' => $trans->LevelID );        }        echo json_encode($tran);    }    public function moveTransaction($id)    {                $level = $_POST['level'];        $type = $_POST['v_type'];        $check = $this->BookModel->move_transaction($id,$type);        if($check){            $response = array('success' => "ok" );        }else{            $response = array('error' => "ok" );        }       echo json_encode($response);    }    public function moveIncTransaction($id)    {        $check = $this->IncomeModel->move_transaction($id);        if($check){            $response = array('success' => "ok" );        }else{            $response = array('error' => "ok" );        }        echo json_encode($response);    }    public function updatePermanentVoucher($type,$TokeepVoucher,$level_id)    {        $check = $this->BookModel->update_Permanent_Voucher($type,$TokeepVoucher,$level_id);        if($check){            $response = array('success' => "ok" );        }else{            $response = array('error' => "ok" );        }        echo json_encode($response);    }    public function copyVoucher($type,$level_id)    {        $check = $this->BookModel->copy_voucher($type,$level_id);        if($check){            $response = array('success' => "ok" );        }else{            $response = array('error' => "ok" );        }        echo json_encode($response);    }    public function copyIncVoucher($level_id)    {        $check = $this->IncomeModel->copy_voucher($level_id);        if($check){            $response = array('success' => "ok" );        }else{            $response = array('error' => "ok" );        }        echo json_encode($response);    }    public function getByPerVoucherNo($code='',$type='',$id='')    {        if($code)        {            $data['transactions'] = $this->BookModel->get_by_per_voucher_no($code,$type,$id);            $table = $this->load->view('Accounts/books/cashbook/permanentCashbookTable', $data, true);            echo $table;        }    }    public function getByPerVoucherNoIncome($code='',$type='',$id='')    {        if($code)        {            $data['transactions'] = $this->IncomeModel->get_by_per_voucher_no_income($code,$type,$id);            $table = $this->load->view('Accounts/books/income/permanentIncomeTable', $data, true);            echo $table;        }    }    public function viewVoucher($tran_id='',$level_id='',$is_permit = '')    {// echo $is_permit;// exit();        $data =  $this->BookModel->get_voucher_details($tran_id,$level_id);       // echo $this->db->last_query();               $voucher_no = $data[0]->VoucherNo;        $voucher_type = $data[0]->VoucherType;        $comp_id = $data[0]->LevelID;        $Seprate_series_num  = $data[0]->Seprate_series_num ;                $voucher['vouch'] = $this->BookModel->get_voucher($voucher_no,$voucher_type,$comp_id,$Seprate_series_num,$is_permit);        // echo $this->db->last_query();        $debit = '';        foreach($voucher['vouch'] as $acc):            if($acc->Type == 1 || $acc->Type == 2):                if($voucher['vouch'][0]->VoucherType == 'BR' || $voucher['vouch'][0]->VoucherType == 'CR'):                    $debit += $acc->Debit;                endif;                if($voucher['vouch'][0]->VoucherType == 'BP' || $voucher['vouch'][0]->VoucherType == 'CP'):                    $debit += $acc->Credit;                endif;            endif;            if($voucher['vouch'][0]->VoucherType == 'JV'):                if($acc->Credit == 0.00):                    $debit += $acc->Debit;                endif;            endif;        endforeach;        $debit = (string)$debit;        $voucher['AmountInWords'] = $this->Amount($debit);        $users = $this->UserModel->getCreatedBy($voucher['vouch'][0]->Createdby);        // echo '<pre>'.$this->db->last_query();        // print_r($users);        // exit();        $voucher['CreatedOn'] = $voucher['vouch'][0]->CreatedOn;        $voucher['Permanent_VoucherNumber'] = $voucher['vouch'][0]->Permanent_VoucherNumber;        if(!empty($users)){                        $voucher['Createdby'] = $users[0]->UserName;                }        if(isset($voucher['vouch'][0]->UpdatedBy)){            $user = $this->UserModel->getCreatedBy($voucher['vouch'][0]->UpdatedBy);            $voucher['UpdatedOn'] = $voucher['vouch'][0]->UpdatedOn;            if(!empty($users)){            $voucher['UpdatedBy'] = $user[0]->UserName;        }        }                $this->load->view('Accounts/voucherView',$voucher);    }    public function viewIncVoucher($tran_id='',$level_id='')    {        $data =  $this->IncomeModel->get_voucher_details($tran_id,$level_id);        $voucher_no = $data[0]->VoucherNo;        $comp_id = $data[0]->LevelID;        $voucher['vouch'] = $this->IncomeModel->get_voucher($voucher_no,$comp_id);        $voucher['AmountDescriptionCheque'] = $this->IncomeModel->get_amount_descp_cheque($tran_id);        $cash = $this->IncomeModel->get_amount_descp_cash($tran_id);        $khuly = $this->IncomeModel->get_amount_descp_cash_khuly($tran_id);        //added by sufyan        $CurrencyAmount = '';        if(isset($khuly->CurrencyAmount)){            $CurrencyAmount = $khuly->CurrencyAmount;        }        $voucher['AmountDescriptionCash'] = $cash->CurrencyAmount + $CurrencyAmount;        $debit = '';        foreach($voucher['vouch'] as $acc):            $debit += $acc->Debit;        endforeach;        $debit = (string)$debit;        $voucher['AmountInWords'] = $this->Amount($debit);        $users = $this->UserModel->getCreatedBy($voucher['vouch'][0]->Createdby);        $voucher['CreatedOn'] = $voucher['vouch'][0]->CreatedOn;        $voucher['Permanent_VoucherNumber'] = $voucher['vouch'][0]->Permanent_VoucherNumber;        $voucher['Createdby'] = $users[0]->UserName;        if(isset($voucher['vouch'][0]->UpdatedBy)){            $user = $this->UserModel->getCreatedBy($voucher['vouch'][0]->UpdatedBy);            $voucher['UpdatedOn'] = $voucher['vouch'][0]->UpdatedOn;            $voucher['UpdatedBy'] = $user[0]->UserName;        }        $this->load->view('Accounts/IncomeVoucherView',$voucher);    }    public function update_year(){        $this->db->set('year',$_POST['year']);        $this->db->update('closing_year');        if($this->db->affected_rows() > 0){            echo 'ok';        }        else{            echo 'error';        }    }}