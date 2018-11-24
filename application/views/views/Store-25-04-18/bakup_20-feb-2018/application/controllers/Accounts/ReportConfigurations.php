<?php /**
* 
*/
class ReportConfigurations extends MY_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('ReportConfigurationModel');
	}

	public function index()
	{
		$data['name'] = $this->ReportConfigurationModel->Reportname();
    	$this->load->view('Accounts/header');
        $this->load->view('Accounts/Reports/ReportConfiguration/add',$data);
        $this->load->view('Accounts/footer');
        $this->load->view('Accounts/js/reportconfiguratioJs');
	}

	public function Save()
	{
		$check = $this->ReportConfigurationModel->Save_Report();
		if($check){
            $this->session->set_flashdata('success',"رپورٹ کامیابی سے شامل ہوگیا");
            redirect('Accounts/ReportConfigurations','refresh');
        }else{
            $this->session->set_flashdata('error',"رپورٹ کا نام درج کریں");
            redirect('Accounts/ReportConfigurations','refresh');
        }
	}
	public function ReportNameById($id)
    {
    	$check = $this->ReportConfigurationModel->ReportNameBy_Id($id);
    	$name = array('ReportName' =>$check[0]->ReportName);
    	 echo json_encode($name);
    }

    public function UpdateReport($id)
    {
    	$check = $this->ReportConfigurationModel->Update_Report($id);
        if($check){
            $response= array('success' => "ok");
        } else{
            $response=array('error' => "ok");
        }
        echo json_encode($response);
    }

    public function DeleteReport($id)
    {
    	$check = $this->ReportConfigurationModel->Delete_Report($id);
        if($check){
            $response= array('success' => "ok");}
        else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }
}