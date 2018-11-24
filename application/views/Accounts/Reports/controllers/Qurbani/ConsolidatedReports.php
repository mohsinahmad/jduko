<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 9/25/2017
 * Time: 10:44 AM
 */

class ConsolidatedReports extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurSlipModel');
        $this->load->model('QurCashRecieptModel');
        $this->load->model('QurExpenceDetailModel');
    }

    public function index()
    {
        $locs = array(41,43,44);
        foreach ($locs as $loc) {
            $data['Expence'][] = $this->QurExpenceDetailModel->Get_All_Expence_Details($loc);
            $data['Reward'][] = $this->QurExpenceDetailModel->Get_All_Expence_Details($loc,1);
            $data['income'][] = $this->QurSlipModel->Get_Income_Loc_Wise($loc);
            $data['Misc_Income'][] = $this->QurCashRecieptModel->Get_Misc_Icome($loc);
        }
        $this->load->view('Qurbani/Reports/Consolidated/Report1',$data);
    }
}