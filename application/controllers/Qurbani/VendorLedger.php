<?php 
/**
* 
*/
class VendorLedger extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
        $this->load->model('QurCashRecieptModel');
        $this->load->model('QurSaleSlipModel');
        $this->load->model('CalenderModel');
    }

	public function index()
	{
		$data['vendors'] = $this->QurConfigModel->Get_All_config('qur_khaal_vendor');
		$this->load->view('Qurbani/header');	
		$this->load->view('Qurbani/Reports/VendorLedger/GetVendorLedger',$data);
		$this->load->view('Qurbani/footer');
	}

	public function ViewVendorLedger()
	{
		$vendor = $_POST['vendor'];
		$data['vendor_Name'] = $this->QurCashRecieptModel->GetVendorsName($vendor);
		$data['Slip'] = $this->QurSaleSlipModel->Get_Data_Vendor_Ledger($vendor);
		$this->load->view('Qurbani/Reports/VendorLedger/ViewVendorLedger',$data);
	}

	public function GetVendorReport()
	{
		$data['vendors'] = $this->QurConfigModel->Get_All_config('qur_khaal_vendor');
		$this->load->view('Qurbani/header');	
		$this->load->view('Qurbani/Reports/VendorLedger/GetVendorReport',$data);
		$this->load->view('Qurbani/footer');
	}

    public function VenderReport()
    {
        $vendor = $_POST['vendor'];
        $data['vendor_Name'] = $this->QurCashRecieptModel->GetVendorsNameAndCash($vendor);
        $data['Slip'] = $this->QurSaleSlipModel->Get_Data_For_Vendor_Report($vendor);
        $cow_fresh = $data['Slip'][0]->Cow_Fresh * $data['Slip'][0]->Fresh_Rate_Cow;
        $cow_old = $data['Slip'][0]->Cow_Old * $data['Slip'][0]->Old_Rate_Cow;
        $goat_fresh = $data['Slip'][0]->Goat_Fresh * $data['Slip'][0]->Fresh_Rate_Goat;
        $goat_old = $data['Slip'][0]->Goat_Old * $data['Slip'][0]->Old_Rate_Goat;
        $sheep_fresh = $data['Slip'][0]->Sheep_Fresh * $data['Slip'][0]->Fresh_Rate_Sheep;
        $sheep_old = $data['Slip'][0]->Sheep_Old * $data['Slip'][0]->Old_Rate_Sheep;
        $Camel_fresh = $data['Slip'][0]->Camel_Fresh * $data['Slip'][0]->Fresh_Rate_Camel;
        $Camel_old = $data['Slip'][0]->Camel_Old * $data['Slip'][0]->Old_Rate_Camel;
        $buffalo_fresh = $data['Slip'][0]->Buffelo_Fresh * $data['Slip'][0]->Fresh_Rate_Buffelo;
        $buffalo_old = $data['Slip'][0]->Buffelo_Old * $data['Slip'][0]->Old_Rate_Buffelo;
        $total_amount_khaal = $cow_fresh+$cow_old+$goat_fresh+$goat_old+$sheep_fresh+$sheep_old+$Camel_fresh+$Camel_old+$buffalo_fresh+$buffalo_old;
        $data['total_amount_khaal'] = $total_amount_khaal;
        $date = date('Y-m-d');
        $data['HijriDate'] = $this->CalenderModel->getHijriDate($date);
        $this->load->view('Qurbani/Reports/VendorLedger/VendorReport',$data);
	}
}