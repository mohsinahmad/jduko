<?php

class Link extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if(!($this->session->userdata('in_use'))){
            redirect('login','refresh');
        }
        $this->load->model('CompanyModel');
        $this->load->model('ChartModel');
        $this->load->model('LinkModel');
    }

    public function index()
    {
        /*company Model*/
        $data['head'] = $this->CompanyModel->getHead();
        $data['companydata'] = $this->CompanyModel->get_data();

        /*Chart model*/
        $data['A_Heads'] = $this->ChartModel->getSubHead("assets");
        $data['L_Heads'] = $this->ChartModel->getSubHead("libilities");
        $data['C_Heads'] = $this->ChartModel->getSubHead("capital");
        $data['R_Heads'] = $this->ChartModel->getSubHead("revenue");
        $data['E_Heads'] = $this->ChartModel->getSubHead("expense");
        $data['accountname'] = $this->ChartModel->get_account_name_2();
        /*link Model*/
        $data['result']=$this->LinkModel->getdata();
        //print_r($data);
//echo $this->db->last_query();
        $this->load->view('Accounts/header');
        $this->load->view('Accounts/link/add',$data);
        $this->load->view('Accounts/link/index');
        $this->load->view('Accounts/footer');
        $this->load->view('Accounts/js/linkJs');
    }

    public function getByAccountCode($code)
    {
        if ($code) {
            $data['result'] = $this->LinkModel->get_by_account_code($code);
            $table = $this->load->view('Accounts/link/linkTable', $data ,true);
            echo $table;
        }
    }

    public function getAll()
    {
        $data['result'] = $this->LinkModel->getdata();
        $table = $this->load->view('Accounts/link/linkTable', $data, true);
        echo $table;
    }

    public function getdata($id)
    {
        $data = $this->ChartModel->get_a_data($id);
        $account = array('account_name' => $data[0]->AccountName,
            'account_code' => $data[0]->AccountCode);
        echo json_encode($account);
    }

    public function get_c_data($id)
    {
        $data = $this->CompanyModel->get_s_data($id);
        $level= array(
            'level_name' =>$data[0]->LevelName,
            'level_id' =>$data[0]->LevelCode,
            'id' => $data[0]->id);
        echo json_encode($level);
    }

    public function save()
    {
        $str  = $_POST['OpeningBalance'];
        $strlen = strlen($str);
        $as = strpos($str,".");
        $as++;
        $ap = substr($str,$as);
        $aplen = strlen($ap);
        $as--;
        $bp = substr($str,0,$as);
        $bplen = strlen($bp);

        if($aplen >= 13 || $strlen > 15 || $bplen >= 13){
            $this->session->set_flashdata('error', 'ابتدائ بلنس غلط ہے');
            redirect('Accounts/Link','refresh');
        }elseif ($_POST['OpeningBalance'] == ""){
            $this->session->set_flashdata('error', 'ابتدائ بیلنس ڈالنا ضروری ہے');
            redirect('Accounts/Link','refresh');
        }elseif ($_POST['chart1'] == ''){
            $this->session->set_flashdata('error', 'براے مہربانی اکاونٹ منتخب کیجیے');
            redirect('Accounts/Link','refresh');
        }elseif ($_POST['comm1'] == '') {
            $this->session->set_flashdata('error', 'براے مہربانی کمپنی منتخب کیجیے');
            redirect('Accounts/Link', 'refresh');
        }else{
            $check = $this->LinkModel->save_data();
            if (!$check){
                $this->session->set_flashdata('error', 'ربط ممکن نہیں');
                redirect('Accounts/Link','refresh');
            }elseif ($check['errorCode1'] === 203){
                $this->session->set_flashdata('error', $check['Aname'] . ' کا ربط موجود ہے');
                redirect('Accounts/Link','refresh');
            }elseif ($check === 102){
                $this->session->set_flashdata('error', 'ربط ممکن نہیں');
                redirect('Accounts/Link','refresh');
            }else{
                $this->session->set_flashdata('success', 'ربط قائم ہو گیا');
                redirect('Accounts/Link','refresh');
            }
        }
    }

    public function DeleteLink($id)
    {
        $check = $this->LinkModel->delete_data($id);
        if($check){
            $response = array('success' => "ok", 'message' => " ربط حذف کردیا گیا ہے");
        }else{
            $response = array('error' => "ok" ,'message' => "ربط حذف نیہں ہوسکتا ہے اس کی ٹرانسزیکشنزموجود ہیں!!!");
        }
        echo json_encode($response);
    }

    public function getLastInserted()
    {
        $data = $this->LinkModel->get_LastInserted();
        if($data != Null){
            $acc = array('AccountName' => $data[0]->LevelName,
                'AccountID' => $data[0]->LevelCode );
        }else{
            $acc = array();
        }
        echo json_encode($acc);
    }

    public function EditLink($id)
    {

        $check = $this->LinkModel->editdata($id);
        $max_seprate_series = $this->LinkModel->max_seprate_series();
        $data = array('maxSeparate_Series' => $max_seprate_series[0]->Separate_Series, 'levelname' => $check[0]->LevelName,"accountname" => $check[0]->AccountName, "opening" => $check[0]->OpeningBalance, "Separate_Series" => $check[0]->Separate_Series, "active" => $check[0]->Active);
        echo json_encode($data);
    }

    public function updatelink($id,$Obalance,$Active,$Seprate)
    {
        $str  = $Obalance;
        $strlen = strlen($str);
        $as = strpos($str,".");
        $as++;
        $ap = substr($str,$as);
        $aplen = strlen($ap);
        $as--;
        $bp = substr($str,0,$as);
        $bplen = strlen($bp);

        if($aplen >= 13 || $strlen > 15 || $bplen >= 13){
            $this->session->set_flashdata('error', 'ابتدائ بلنس غلط ہے');
            redirect('Accounts/Link','refresh');
        }
        elseif ($Obalance == ""){
            $this->session->set_flashdata('error', 'ابتدائ بلنس ضروری ہے');
            redirect('Accounts/Link','refresh');
        }
        else{
            $check = $this->LinkModel->update_link($id,$Obalance,$Active,$Seprate);
            if($check){
                $this->session->set_flashdata('success', 'کامیابی سے تدوین ہوگئ');
                redirect('Accounts/Link','refresh');
            }
            else{
                $this->session->set_flashdata('error', 'ابتدائ بلنس غلط ہے');
                redirect('Accounts/Link','refresh');
            }
        }
    }
}