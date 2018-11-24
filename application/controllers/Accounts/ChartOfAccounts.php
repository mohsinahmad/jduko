<?php

class ChartOfAccounts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!($this->session->userdata('in_use'))){
            redirect('login','refresh');
        }
        $this->load->model('ChartModel');
        $this->load->model('LinkModel');
        $this->load->model('CompanyModel');
    }

    public function index()
    {
        $data['heads'] = $this->ChartModel->get_MainHead();
        $data['A_Heads'] = $this->ChartModel->getSubHead("assets");
        $data['L_Heads'] = $this->ChartModel->getSubHead("libilities");
        $data['C_Heads'] = $this->ChartModel->getSubHead("capital");
        $data['R_Heads'] = $this->ChartModel->getSubHead("revenue");
        $data['E_Heads'] = $this->ChartModel->getSubHead("expense");
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/coa/index',$data);
        $this->load->view('Accounts/footer');
        $this->load->view('Accounts/js/chartofaccountJs');
    }



    //sufyan work start for rauf sahab report from here

    public function get_head($code)
    {

        $accounts = array($code);
        $data = $this->ChartModel->GetAccountTreeby_parent_ledger($accounts);
        $parent = '';
        //print_r($data).'<br>';
        foreach($data as $key => $value) {
            foreach ($data[$key] as $key1 => $value1) {
                if (isset($value1->AccountName)) {
                    echo '<ul><li>'.$value1->AccountName.'<br>';
                } else {
                    foreach ($value1 as $key2 => $value2) {
                        if (isset($value2->AccountName)) {
                        } else {
                            if($key2 == 'Child0'){
                              echo '<li>'.$value1[0]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child1'){
                           echo '<li>'.$value1[1]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child2'){
                             echo '<li>'.$value1[2]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child3'){
                             echo '<li>'.$value1[3]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child4'){
                             echo '<li>'.$value1[4]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child5'){
                             echo '<li>'.$value1[5]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child6'){
                             echo '<li>'.$value1[6]->AccountName.'<br>';
                            }
                            else if($key2 == 'Child7'){
                               echo '<li>'.$value1[7]->AccountName.'<br>';
                            }
                            foreach ($value2 as $key3 => $value3) {
                                //echo $key3.'<br>';
                                if (isset($value3->AccountName)) {
                                }
                                else {
                                    if($key3 == 'Child0'){
                                        echo '<li>'.$value2[0]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child1'){
                                        echo '<li>'.$value2[1]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child2'){
                                        echo '<li>'.$value2[2]->AccountName.'<br>';
                                    }
                                    else if($key == 'Child3'){
                                        echo '<li>'.$value2[3]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child4'){
                                        echo '<li>'.$value2[4]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child5'){
                                        echo '<li>'.$value2[5]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child6'){
                                        echo '<li>'.$value2[6]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child7'){
                                        echo '<li>'.$value2[7]->AccountName.'<br>';
                                    }
                                    else if($key3 == 'Child8'){
                                        echo '<li>'.$value2[8]->AccountName.'<br>';
                                    }    else if($key3 == 'Child9'){
                                        echo '<li>'.$value2[9]->AccountName.'<br>';
                                    }    else if($key3 == 'Child10'){
                                        echo '<li>'.$value2[10]->AccountName.'<br>';
                                    }    else if($key3 == 'Child11'){
                                        echo '<li>'.$value2[11]->AccountName.'<br>';
                                    }    else if($key3 == 'Child12'){
                                        echo '<li>'.$value2[12]->AccountName.'<br>';
                                    }
                                foreach ($value3 as $key4 => $value4) {
                                    if (isset($value4->AccountName)) {
                                        echo 'subchild' . $value4->AccountName . $key3 . '<br>';
                                    }
                                }
                            }
                            }


                            // echo "<pre>";
                           // print_r($value3);
                            // print_r($value3);
                        }


                    }
                }
            }
        }
        //print_r($value1);
       // print_r($treedata);
    }
        //echo $this->db->last_query();
        /*  $data['heads'] = $this->ChartModel->get_MainHead();
       //  echo $this->db->last_query();
         $data['A_Heads'] = $this->ChartModel->getSubHead("assets");
         echo $this->db->last_query();
              $data['L_Heads'] = $this->ChartModel->getSubHead("libilities");
             echo $this->db->last_query();
             echo $this->db->last_query();
             $data['C_Heads'] = $this->ChartModel->getSubHead("capital");
             echo $this->db->last_query();
             $data['R_Heads'] = $this->ChartModel->getSubHead("revenue");
             echo $this->db->last_query();
             $data['E_Heads'] = $this->ChartModel->getSubHead("expense");
             echo $this->db->last_query();*/
        /*$this->load->view('Accounts/header');
        $this->load->view('Accounts/coa/ledgerhead',$data);
        $this->load->view('Accounts/footer');
        $this->load->view('Accounts/js/chartofaccountJs');*/

    //suyan till here


    public function GetAllAccounts()
    {
         $accounts = array(1,2,3,4,5);
         $data['Accounts'] = $this->ChartModel->GetAccountTree($accounts);
        // echo "<pre>";
        // print_r($data);
        // exit();
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/coa/tree',$data);
        $this->load->view('Accounts/footer');
        $this->load->view('Accounts/js/chartofaccountJs');
    }

    public function save()
    {
        $check = $this->ChartModel->save_data();
        if($check){
            $this->load->view('Accounts/header');
            $this->load->view('Accounts/coa/add');
            $this->load->view('Accounts/footer');
            $this->load->view('Accounts/js/chartofaccountJs');
        }
    }

    public function getEditAccount($id)
    {
        $data = $this->ChartModel->get_Edit_Account($id);
        $account = array('parent_code' => $data['LevelData'][0]->ParentCode,
            'account_code' => $data['LevelData'][0]->AccountCode,
            'account_name' => $data['LevelData'][0]->AccountName,
            'parent_name' => $data['parentName'],
            'head' => $data['LevelData'][0]->Head,
            'type' => $data['LevelData'][0]->Type,
            'category' => $data['LevelData'][0]->Category,
            'child' => $data['child']
        );
        echo json_encode($account);
    }

    public function newAccount($id)
    {
        $data = $this->ChartModel->new_Account($id);
        $newAccount = array('parent_ID' => $data['parentID'],
            'parent_Name' => $data['parentNAME'],
            'new_ID' => $data['new_ID'],
            'head' => $data['head'] );
        echo json_encode($newAccount);
    }

    public function SaveNewAccount()
    {
        $check = $this->ChartModel->save_new_account();
        if($check){
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }

    public function EditAccount($id)
    {
        $check = $this->ChartModel->edit_account($id);
        if($check){
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
    }

    public function DeleteAccount($id)
    {
        $check = $this->ChartModel->delete_account($id);
        if($check == "true"){
            $response = array('success' => "ok");
        }else if($check === 203){
            $response = array('link' => "ok");
        }else{
            $response = array('error' => "ok" ,'message' => "اکاؤنٹ حذف نہیں کیا جاسکتا اس کے تابع اکاؤنٹ موجود ہیں");
        }
        echo json_encode($response);
    }

    public function PrintChartOfAccount()
    {
        if (isset($_SESSION['comp_id'])){
            $Level_id = $_SESSION['comp_id'];
        }elseif (isset($_SESSION['comp'])){
            $Level_id = $_SESSION['comp'];
        }else{
            $Level_id = '';
        }
        $accounts = array(1,2,3,4,5);
        $Tree_Accounts = $this->ChartModel->GetAccountTree($accounts);

        $data['Links'] = $this->LinkModel->links($Tree_Accounts,$Level_id);
        $data['Accounts'] = $Tree_Accounts;
        $data['LevelName'] = $this->CompanyModel->get_company_name($Level_id);

        $this->load->view('Accounts/coa/ChartOfAccount(Print)',$data);
        $this->load->view('Accounts/footer');
        $this->load->view('Accounts/js/chartofaccountJs');
    }
}