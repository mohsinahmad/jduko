<?php
/**
 *
 */
class AreaDetail extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurChrumQuantityModel');
        $this->load->model('QurHulqyModel');
    }

    public function index()
    {
        $data['hulqy'] = $this->QurHulqyModel->Get_Hulqy();
        $day = array(1,2,3);
        foreach ($data['hulqy'] as $key => $value) {
            $data['ReciptNo'][] = $this->QurChrumQuantityModel->GetReciptNo($value->id,$day);
        }
        foreach ($data['ReciptNo'] as $R_key => $item) {
            foreach ($item as $D_key => $r_value) {
                if ($r_value != array()){
                    foreach ($r_value as $item) {
                        $data['AreaDetails'][$R_key][$D_key][] = $this->QurChrumQuantityModel->GetAreaDetailReport($item->receipt_no);
                    }
                }else{
                    $data['AreaDetails'][$R_key][$D_key][] = array('');
                }
            }
        }
//        foreach($data['AreaDetails'] as $Hkey => $value){
//            foreach ($value as $day => $item) {
//                foreach ($item as $myitem) {
//                    if ($myitem != array('')){
//                    }
//                }
//            }
//        }
        // echo "<pre>";
        // print_r($data);
        // exit();
        $this->load->view('Qurbani/Reports/AreaDetails/ViewAreaDetails',$data);
    }
}