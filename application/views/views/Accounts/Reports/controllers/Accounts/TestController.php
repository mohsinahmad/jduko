<?php
/**
 * Created by PhpStorm.
 * User: Mohsin-PC
 * Date: 10/10/2017
 * Time: 11:08 PM
 */

class TestController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->db->where('Separate_Series !=',0);
        $acc_lists = $this->db->get('chart_of_account_years')->result();

        foreach ($acc_lists as $acc_list) {
            $this->db->where('LinkID', $acc_list->ChartOfAccountId);
            $Vouchers[] = $this->db->get('transactions')->result();
        }
        // echo "<pre>";
        // print_r($acc_lists);
        // print_r($Vouchers);
        // exit();
        foreach ($Vouchers as $key => $voucheer) {
            foreach ($voucheer as $voucher) {
                $this->db->set('Seprate_series_num',$acc_lists[$key]->Separate_Series);
                $this->db->where('VoucherType',$voucher->VoucherType);
                $this->db->where('VoucherNo',$voucher->VoucherNo);
                $this->db->where('LevelID',$voucher->LevelID);
                $this->db->where('LinkID',$voucher->LinkID);
                $this->db->where('AccountID',$voucher->AccountID);
                $this->db->update('transactions');
            }
        }

    }

}