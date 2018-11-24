<?php


$basepath = str_replace('system','application', BASEPATH) . 'libraries/Tcpdf';
// echo str_replace('system','application', BASEPATH);
// exit();
include_once $basepath . '/Tcpdf.php';

class MYPDF extends TCPDF {
    //Page header
    // $form = '';
    public function Header() {
            // Logo
        // $i = 1;
       $this->setJPEGQuality(90);
       $basepath = str_replace('system','application', BASEPATH);
       $img_path = $basepath.'libraries/Tcpdf/examples/images/logo.jpg';
       $this->Image($img_path, 120, 10, 75, 0, 'PNG', '');
       $headerData = $this->getHeaderData();
       $this->SetFont('freeserif', 'B', 16);
       $this->SetXY(0, 30);
       // $this->Cell(140,10, 'وصولی فارم', 0, 1, 'L', 0, '', 5,'B');
       // $this->SetFont('freeserif', 'B', 10);
       // $this->writeHTML($headerData['string']);    
    }
    // Page footer
    // public function Footer() {
    //     // Position at 15 mm from bottom
    //     $this->SetY(-15);
    //     // Set font    $pdf->setHeaderFont(Array('arialuni', '', PDF_FONT_SIZE_MAIN));
    //     $this->SetFont('arialuni', 8);
    //     // Page number
    //     $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    // }
}
class StockSlip extends MY_Controller
{	
	function __construct()
	{
		parent::__construct();
		$this->load->model('ItemSetupModel');
		$this->load->model('StockReciveModel');
		$this->load->model('SupplierModel');
		$this->load->model('DepartmentModel');
        $this->load->model('BookModel');        
	}
	public function generate_sip(){
	    for($i = 1; $i <= 884;$i++) {
           $slip = $this->DemandFormModel->getSlipNumber('item_stockrecieve_slip');
           $this->db->set('Slip_number', $slip);
           $this->db->where('id', $i);
           $this->db->update('item_stockrecieve_slip');
       }
       }
        public  function  direct_issue(){
        $data['items'] = $this->ItemSetupModel->getAllItems();
        $data['supplier'] = $this->SupplierModel->GetSupplier(0);
        $data['departments'] = $this->DepartmentModel->department_name();
        $this->load->view('Store/header');
        $this->load->view('Store/direct_issue/StockReciveSlipPage',$data);
        $this->load->view('Store/footer');
        }
    public function get_issue_voucher(){
                $data['stock'] = $this->StockReciveModel->get_direct_issue_data();                
                $this->load->view('Store/header');
                $this->load->view('Store/direct_issue/StockReciveSlip',$data);
                $this->load->view('Store/footer');
                $this->load->view('Store/js/stockJs');
        }
    public function Save_direct_issue()
    {
        isset($_POST['IssueNow'])?$IssueNow = 1:$IssueNow=0;
        $result = $this->StockReciveModel->Save_direct_issue();
        if($result){
            $this->session->set_flashdata('success',"Stock Added Successfully");
            if ($IssueNow == 1){
                $this->db->select_max('id');
                $this_id = $this->db->get('item_stockrecieve_slip')->result();
                redirect('Store/DirectIssue/index/'.$this_id[0]->id,'refresh');
            }else{
                redirect('Store/StockSlip/get_issue_voucher','refresh');
            }
        }else{
            $this->session->set_flashdata('error',"Stock Not Inserted");
            if ($IssueNow == 1){
                redirect('Store/StockSlip/direct_issue','refresh');
            }else{
                redirect('Store/StockSlip/get_issue_voucher','refresh');
            }
        }
    }
    
    public function get_direct_issue_report($id){

        $result = $this->StockReciveModel->get_direct_issue_report_data($id);

        $total_price = 0;
        $data = '<center>       
        <table cellspacing="0" cellpadding="5" border="1">';
        $data .= '
        <thead>        
        <tr>
        <th><b>سلپ نمبر  </b> </th>
        <th><b>خرید کنندہ  </b> </th>
        <th style="width:30%"><b>آئٹم  </b></th>
        <th style="width:15%"><b>تعاون  </b></th>
        <th style="width:10%"><b>پیمائش   </b> </th>     
        <th style="width:10%"><b>قیمت   </b></th>
        <th style="width:10%"><b>مقدار  </b></th>
        </tr>
        </thead>
        <tbody>';
        foreach ($result as $key => $value) {
            $data .= '<tr>'.'
                 <td>'.$value->slip_number.'</td>'.
                '<td>'.$value->purchaser.'</td>'.
                '<td style="width:30%">'.$value->name.'</td>'.
                '<td style="width:15%">'.$value->donation_type.'</td>'.
                '<td style="width:10%">'.$value->unit_of_measure.'</td>'.
                '<td style="width:10%">'.number_format($value->amount).'</td>'.
                '<td style="width:10%">'.$value->quantity.'</td>'.
                '</tr>';
                $total_price += $value->price;
                $slip_number = $value->slip_number;
        }
        $amount_words = (string)$total_price;
       $price_in_word = $this->Amount($amount_words);
       // echo $price_in_word;
       // exit();
        $data .= '</tbody>
        
        </table>
        <table>
<tfoot style="border:none">
<tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
            <td style="width:120px;">دستخط چیف اکاوٴنٹنٹ</td>
                <td style="border-bottom:1px solid black;"></td>
                <td style="width:6 0px;"></td>
                <td style="width:100px;">دستخط اسٹور کیپر</td>
                <td style="border-bottom:1px solid black;"></td>
            </tr>
             <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>            
            <td style="width:120px;"></td>
                <td></td>
                <td style="width:6 0px;"></td>
                <td style="width:130px;">ددستخط اجراء/وصول کنندہ  </td>
                <td style="border-bottom:1px solid black;"></td>
            </tr>
        </tfoot>
        </table>
        </center>';
      $purchase_date = $result[0]->Purchase_dateG;
      $recieve_date =  $result[0]->Item_recieve_dateG;
      $purchaser =  $result[0]->purchaser;
      $supplier =  $result[0]->NameU;
      $purchase_date_i = $this->BookModel->get_hijri_date($purchase_date);
      $recieve_date_i = $this->BookModel->get_hijri_date($recieve_date);      
        // $custom_layout = array('500', '500');
        // $basepath = str_replace('system','', BASEPATH) . 'application/libraries/Tcpdf';
        // $img_path = $basepath.'application/libraries/Tcpdf/examples/images/logo.jpg';
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(400, 300), true, 'UTF-8', false); 
        // $pdf->SetHeaderData(PDF_HEADER_LOGO,80, '','',array(0,0,0), array(255,255,255) );
        // $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->Image(PDF_HEADER_LOGO, 90, 5, 40, '', 'PNG', '', 'T', false, 300, 'C', false, false, 0, false, false, false);
        // $pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
        // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // $pdf->setFooterData('',0,'','',array(0,0,0), array(255,255,255) );  
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, 20);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetTitle('فارم');
        // $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // $pdf->setJPEGQuality(90);
        // $pdf->Image($img_path, 120, 10, 75, 0, 'JPG', '');
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';
        $pdf->SetFont('freeserif', '', 10);
        // set some language-dependent strings (optional)
        $pdf->setLanguageArray($lg);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->setRTL(true);

        $pdf->AddPage('P', 'A4');
        $pdf->SetFontSize(20);
        $pdf->Cell(120,10, 'وصولی فارم', 0, 1, 'L', 0, '', 5,'B');
        $pdf->SetFontSize(11);
        // $pdf->form = 'ّوصولی فارم';
        $pdf->MultiCell(100, 5, 'اسٹاک سلپ#:', 0, 'R', false, 1, 20, 50, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $slip_number, 0, 'R', false, 1, 40, 50, true, 0, false, true, 10, 'L', false);
        // $pdf->MultiCell(100, 5, 'وصولی فارم',   0, 'R', false, 1, 180, 50, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'عیسوی تاریخ خریداری مطابق رسید:', 0, 'R', false, 1, 20, 60, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date, 0, 'R', false, 1, 70, 60, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'ہجری تاریخ خریداری مطابق رسید:', 0, 'R', false, 1, 20, 65, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date_i[0]->Qm_date , 0, 'R', false, 1, 70, 65, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'عیسوی تاریخ وصولی دراسٹور:', 0, 'R', false, 1, 20, 75, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date, 0, 'R', false, 1, 65, 75, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'ہجری تاریخ وصولی دراسٹور:', 0, 'R', false, 1, 20, 80, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date_i[0]->Qm_date, 0, 'R', false, 1, 65, 80, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'نام سپلائر/دکاندار:', 0, 'R', false, 1, 20, 90, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $supplier, 0, 'R', false, 1, 45, 90, true, 0, false, true, 10, 'L', false);

        $pdf->MultiCell(100, 5, 'رقم ہندسوں میں  :', 0, 'R', false, 1, 120, 85, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, number_format($total_price), 0, 'R', false, 1, 145, 85, true, 0, false, true, 10, 'L', false);

        $pdf->MultiCell(100, 5, 'نام خرید کنندہ: ' , 0, 'R', false, 1, 20, 95, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchaser , 0, 'R', false, 1, 40, 95, true, 0, false, true, 10, 'L', false);

        $pdf->MultiCell(100, 5, 'رقم عبارت میں  :', 0, 'R', false, 1, 120, 95, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $price_in_word, 0, 'R', false, 1, 145, 95, true, 0, false, true, 10, 'L', false);


        $pdf->Ln();
        $pdf->WriteHTML($data, true, 0, true, 0);        
        $pdf->AddPage('P', 'A4');
        $pdf->SetFontSize(20);
        $pdf->Cell(120,10, 'اجراء فارم', 0, 1, 'L', 0, '', 5,'B');
        $pdf->SetFontSize(11);
        $pdf->MultiCell(100, 5, 'اسٹاک سلپ#:', 0, 'R', false, 1, 20, 50, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $slip_number, 0, 'R', false, 1, 40, 50, true, 0, false, true, 10, 'L', false);
        // $pdf->MultiCell(100, 5, 'وصولی فارم',   0, 'R', false, 1, 180, 50, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'عیسوی تاریخ خریداری مطابق رسید:', 0, 'R', false, 1, 20, 60, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date, 0, 'R', false, 1, 70, 60, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'ہجری تاریخ خریداری مطابق رسید:', 0, 'R', false, 1, 20, 65, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date_i[0]->Qm_date , 0, 'R', false, 1, 70, 65, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'عیسوی تاریخ وصولی دراسٹور:', 0, 'R', false, 1, 20, 75, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date, 0, 'R', false, 1, 65, 75, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'ہجری تاریخ وصولی دراسٹور:', 0, 'R', false, 1, 20, 80, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchase_date_i[0]->Qm_date, 0, 'R', false, 1, 65, 80, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, 'نام سپلائر/دکاندار:', 0, 'R', false, 1, 20, 90, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $supplier, 0, 'R', false, 1, 45, 90, true, 0, false, true, 10, 'L', false);

        $pdf->MultiCell(100, 5, 'رقم ہندسوں میں  :', 0, 'R', false, 1, 120, 85, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $total_price, 0, 'R', false, 1, 145, 85, true, 0, false, true, 10, 'L', false);

        $pdf->MultiCell(100, 5, 'نام خرید کنندہ: ' , 0, 'R', false, 1, 20, 95, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $purchaser , 0, 'R', false, 1, 40, 95, true, 0, false, true, 10, 'L', false);

        $pdf->MultiCell(100, 5, 'رقم عبارت میں  :', 0, 'R', false, 1, 120, 95, true, 0, false, true, 10, 'L', false);
        $pdf->MultiCell(100, 5, $price_in_word, 0, 'R', false, 1, 145, 95, true, 0, false, true, 10, 'L', false);

        $pdf->Ln();
        $pdf->WriteHTML($data, true, 0, true, 0);
        $pdf->Output('example_004.pdf', 'I'); 

}
	
    public function index()
	{
		$data['stock'] = $this->StockReciveModel->Get_Stock();
//        echo '<pre>';
//        print_r($data['stock']);
//        echo $this->db->last_query();
//        echo '</pre>';
        $this->load->view('Store/header');
		$this->load->view('Store/stockslip/StockReciveSlip',$data);
		$this->load->view('Store/footer');
		$this->load->view('Store/js/stockJs');
	}
	public function AddStock()
	{
		$data['items'] = $this->ItemSetupModel->getAllItems();
        $data['supplier'] = $this->SupplierModel->GetSupplier(0);
		$this->load->view('Store/header');
		$this->load->view('Store/stockslip/StockReciveSlipPage',$data);
		$this->load->view('Store/footer');
	}
	public function GetItemCode($id)
	{
		$data = $this->ItemSetupModel->GetCode($id);
		$ids =  array('_id' => $data[0]->Id);
		echo json_encode($ids);
	}    
	public function Save()
	{
	    
    	$result = $this->StockReciveModel->Save_Stock();
        if($result){
            $this->session->set_flashdata('success',"Stock Added Successfully");
            // if ($IssueNow == 1){
            //     $this->db->select_max('id');
            //     $this_id = $this->db->get('item_stockrecieve_slip')->result();
            //     redirect('Store/DirectIssue/index/'.$this_id[0]->id,'refresh');
            // }else{
                redirect('Store/StockSlip','refresh');
            // }
        }else{
           // if ($IssueNow == 1){
           //    redirect('Store/StockSlip');
           //  }else{
                 redirect('Store/StockSlip');
            // }
        }
	}
	public function Update_Stock($id)
	{

		$data['slips'] = $this->SupplierModel->GetSupplierById($id);
        // echo '<pre>'.$this->db->last_query();
        // print_r($data);
        // exit();
		$data['supplier'] = $this->SupplierModel->GetSupplier(0);
		$this->load->view('Store/header');
		$this->load->view('Store/stockslip/StockReciveSlipPageEdit',$data);
		$this->load->view('Store/footer');
	}
	public function ViewVoucher($id)
	{
		$data['viewstock'] = $this->StockReciveModel->Get_Stock_Voucher($id);
       // echo  $this->db->last_query();
       // exit();
        $price_sum = 0;
		foreach ($data['viewstock'] as $datum) {
		    $price_sum = $datum->Item_price + $price_sum;
        }
        $price_sum = (string)$price_sum;
        $data['AmountInWords'] = $this->Amount($price_sum);
        $data['AmountInNumber'] = $price_sum;

        $this->load->view('Store/stockslip/StockVoucher',$data);
		$this->load->view('Store/footer');
	}
	public function UpdateStockSlip()
	{
		$result = $this->StockReciveModel->UpdateStock();
        if($result){
            $this->session->set_flashdata('success',"Stock Slip Edited Successfully");
            redirect('Store/StockSlip','refresh');
        }else{
            $this->session->set_flashdata('error',"Stock Slip Not Edited");
            redirect('Store/StockSlip','refresh');
        }
	}
	public function DeleteStockSlip($id)
	{
		$check = $this->StockReciveModel->DeleteStock($id);
        if ($check) {
            $response = array('success' => "ok");
        }else{
            $response = array('error' => "ok");
        }
        echo json_encode($response);
	}
    public function delete_direct_issue($id){
        $this->db->where('id',$id);
        $this->db->delete('direct_issue');
        if($this->db->affected_rows() > 0){
        $this->db->where('direct_issue_id',$id);
        $this->db->delete('direct_issue_details');  
        $response = array('success' => "ok");
        }
        else{
           $response = array('error' => "ok"); 
        }      
    }
    public function check_opening($item_id,$donation){

            $arrayName = array('donation_type' =>$donation,'item_setup_id'=> $item_id);
            $data = $this->db->get_where('item_setup_details',$arrayName);
            if($data->num_rows() > 0){
                echo 'true';
            }
            else{
                echo 'false';
            }
            // echo json_encode($data);
    }
}