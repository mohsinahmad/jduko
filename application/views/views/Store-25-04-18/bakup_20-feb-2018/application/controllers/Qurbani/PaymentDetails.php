<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/26/2017
 * Time: 11:47 AM
 */

class PaymentDetails extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurHulqyModel');
        $this->load->model('QurHissaModel');
        $this->load->model('QurChrumQuantityModel');
        $this->load->model('QurExpenceDetailModel');
    }

    public function index()
    {
        $data['halqy'] = $this->QurHulqyModel->Get_Hulqy();
        foreach ($data['halqy'] as $halqa) {
            $data['report_Data'][] = $this->QurChrumQuantityModel->Comparitive_Report_Data($halqa->id);
        }
        $Inaam = $this->QurHissaModel->get_All();
        if ($Inaam[0]->Common_Package == 0){            //common
            if ($Inaam[0]->Common_Package_Type == 1){   // percent
                $data['Inami_Raqam'] = $Inaam[0]->Common_Package_Amount/100;
                $data['percent'] = 'Inam % meh hai';
            }else{          //cash
                $data['Inami_Raqam'] = $Inaam[0]->Common_Package_Amount;
            }
        }else{              //individual
            foreach ($data['halqy'] as $key => $halqa) {
                if ($halqa->Package_Type == 1){  // percent
                    $data['Inami_Raqam'][$key] = $halqa->Package_Amount/100;
                    $data['percent'][$key] = 'Inam % meh hai';
                }else{ //cash
                    @$data['Inami_Raqam'][$key] = $halqa->Package_Amount;
                }
                if ($halqa->Indivisual_Package_Type != '' && $halqa->Indivisual_Package_Type == 1){
                    $data['Izafi_Inami_Raqam'][$key] = $halqa->Indivisual_Package_Amount/100;
                    $data['Izafi_Inami_percent'][$key] = 'Inam % meh hai';
                }elseif ($halqa->Indivisual_Package_Type != '' && $halqa->Indivisual_Package_Type == 0){
                    @$data['Izafi_Inami_Raqam'][$key] = $halqa->Indivisual_Package_Amount;
                }
            }
        }
        $this->load->view('Qurbani/Reports/PaymentDetails/Payment_Details',$data);
    }

    public function PaymentDetails2()
    {
        $data['halqy'] = $this->QurHulqyModel->Get_Hulqy();
        foreach ($data['halqy'] as $halqa) {
            $data['report_Data'][] = $this->QurChrumQuantityModel->Comparitive_Report_Data($halqa->id);
        }
        $Inaam = $this->QurHissaModel->get_All();
        if ($Inaam[0]->Common_Package == 0){            //common
            if ($Inaam[0]->Common_Package_Type == 1){   // percent
                $data['Inami_Raqam'] = $Inaam[0]->Common_Package_Amount/100;
                $data['percent'] = 'Inam % meh hai';
            }else{          //cash
                $data['Inami_Raqam'] = $Inaam[0]->Common_Package_Amount;
            }
            foreach ($data['halqy'] as $key => $halqa) {
                $data['exp_voucher_Amount'][$key] = $this->QurExpenceDetailModel->Get_Expence_Voucher($halqa->id);
            }
        }else{              //individual
            foreach ($data['halqy'] as $key => $halqa) {
                if ($halqa->Package_Type == 1){  // percent
                    $data['Inami_Raqam'][$key] = $halqa->Package_Amount/100;
                    $data['percent'][$key] = 'Inam % meh hai';
                }else{ //cash
                    @$data['Inami_Raqam'][$key] = $halqa->Package_Amount;
                }
                if ($halqa->Indivisual_Package_Type != '' && $halqa->Indivisual_Package_Type == 1){
                    $data['Izafi_Inami_Raqam'][$key] = $halqa->Indivisual_Package_Amount/100;
                    $data['Izafi_Inami_percent'][$key] = 'Inam % meh hai';
                }elseif ($halqa->Indivisual_Package_Type != '' && $halqa->Indivisual_Package_Type == 0){
                    @$data['Izafi_Inami_Raqam'][$key] = $halqa->Indivisual_Package_Amount;
                }
                $data['exp_voucher_Amount'][$key] = $this->QurExpenceDetailModel->Get_Expence_Voucher($halqa->id);
            }
        }
        $this->load->view('Qurbani/Reports/PaymentDetails/Payment_Details2',$data);
    }
}