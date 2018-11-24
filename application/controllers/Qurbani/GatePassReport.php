<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 9/15/2017
 * Time: 11:31 AM
 */

class GatePassReport extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurSaleSlipModel');
    }

    public function index()
    {
        $data['Receipt_numbers'] = $this->QurSaleSlipModel->Get_Slips();
        $this->load->view('Qurbani/Reports/GatePassReport/GatePassReport',$data);
    }
}