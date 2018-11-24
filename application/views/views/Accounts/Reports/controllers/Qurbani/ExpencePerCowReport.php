<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 9/15/2017
 * Time: 4:35 PM
 */

class ExpencePerCowReport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurCowModel');
        $this->load->model('QurExpenceDetailModel');
    }

    public function index()
    {
        $data['cow_count'] = $this->QurCowModel->Get_No_Cows();
        $Exp_vouchers = $this->QurExpenceDetailModel->Get_Expence_Detail();
        if ($Exp_vouchers != array()){
            foreach ($Exp_vouchers as $item) {
                $data['vouchers'][] = $this->QurExpenceDetailModel->Get_Expence_Voucehrs($item->Voucher_Number);
            }
        }else{
            $data['vouchers'] = array();
        }
        $this->load->view('Qurbani/Reports/ExpencePerCowReport/ExpencePerCowReport',$data);
    }
}