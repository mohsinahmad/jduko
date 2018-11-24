<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 9/16/2017
 * Time: 4:39 PM
 */

class ProfitLoss extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurSlipModel');
        $this->load->model('QurExpenceDetailModel');
    }

    public function index()
    {
        $days = array(1,2,3);
        foreach ($days as $day) {
            $data['income_share'][] = $this->QurSlipModel->Get_Income_Day_Wise($day,0);
            $data['income_self'][] = $this->QurSlipModel->Get_Income_Day_Wise($day,1);
        }
        $data['Expence'] = $this->QurExpenceDetailModel->Get_All_Expence_Details();

        $this->load->view('Qurbani/Reports/ProfitLoss/ProfitLoss',$data);
    }
}