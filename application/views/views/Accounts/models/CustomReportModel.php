<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomReportModel extends CI_Model {

	private $Title;
	private $Report_id;
	private $Seq_num = 1;
	private $Amount;
	private $FromDate;
	private $ToDate;
	private $CreatedBy;
	private $UpdatedBy;
	private $UpdatedOn;
	private $CreatedOn;
	private $Custom_Report_id;
	private $ChartofAcc_id;


	public function save_custom_report()
	{
		$Title = $this->input->post('Title');
		$Report_id = $this->input->post('Report_id');
		$FromDate = $this->input->post('from');
		$ToDate = $this->input->post('to');
		$CreatedBy = $_SESSION['user'][0]->id;
        $CreatedOn = date('Y-m-d H:i:s'); 
        $Amount = $this->input->post('Amount');
        $ChartofAcc_id = $this->input->post('accounts');
        $seq_num = 1;
		foreach ($Title as $key => $value) {
			$this->db->set('Title',$value);
			$this->db->set('Report_id',$Report_id);
			$this->db->set('Seq_num',$seq_num);
			$this->db->set('Amount',$Amount[$key]);
			$this->db->set('FromDate',$FromDate);
			$this->db->set('ToDate',$ToDate);
			$this->db->set('CreatedBy',$CreatedBy);
			$this->db->set('CreatedOn',$CreatedOn);
			$seq_num++;
			$this->db->insert('custom_report');
			$Custom_Report_id = $this->db->insert_id();
			foreach ($ChartofAcc_id[$key] as $Char_of_acc_id) {
				$this->db->set('Custom_Report_id',$Custom_Report_id);
				$this->db->set('ChartOfAcc_id',$Char_of_acc_id);
				$this->db->set('CreatedBy',$CreatedBy);
				$this->db->set('CreatedOn',$CreatedOn);
				$this->db->insert('custom_report_details');
			}
		}
    	if ($this->db->affected_rows() > 0) {
            $result = true;
        }else{
            $result = false;
        }

        return $result;
	}

	public function get_custom_report_titles($report_id)
	{
		$this->db->select('id,Title,Amount');
		$this->db->from('custom_report');
		$this->db->where('Report_id',$report_id);
		return $this->db->get()->result();
	}

	public function get_title_accounts($title_id)
	{
        $this->db->select('ChartOfAcc_id,account_title.AccountName');
        $this->db->from('custom_report_details');
        $this->db->join('chart_of_account', 'chart_of_account.id = custom_report_details.ChartOfAcc_id'); 
        $this->db->join('account_title','chart_of_account.AccountId = account_title.id');
        $this->db->where('Custom_Report_id', $title_id);
        return $this->db->get()->result();
	}

	public function GetCustomReportData($report,$level)
	{
		$this->db->select('custom_report.id,Title,Report_id,Amount,reports.ReportName');
		$this->db->from('custom_report');
		$this->db->join('reports', 'custom_report.Report_id = reports.Id');
		// $this->db->join('custom_report_details', 'custom_report.id = custom_report_details.Custom_Report_id');
		// $this->db->join('chart_of_account', 'chart_of_account.id = custom_report_details.ChartOfAcc_id','LEFT');
		// $this->db->where('chart_of_account.id',NULL);
		//$this->db->where('chart_of_account.LevelId', $level);
		$this->db->where('custom_report.Report_id', $report);
		return $this->db->get()->result();
	}

	public function update_custom_report()
	{
		$Report_id = $this->input->post('Report_id');
		$this->db->select('Id');
		$this->db->where('Report_id',$Report_id);
		$Custom_Report_ids = $this->db->get('custom_report')->result();
		foreach ($Custom_Report_ids as $key => $Custom_report_id) {
			$Cids[] = $Custom_report_id->Id;
		}
		$this->db->where_in('Custom_report_id', $Cids);
		$this->db->delete('custom_report_details');

		if($this->db->affected_rows() > 0){
			$this->db->where('Report_id', $Report_id);
			$this->db->delete('custom_report');

			$this->save_custom_report();
			if($this->db->affected_rows() > 0){
				return true;
			}else{
				return false;
			}
		}

	}

}

/* End of file CustomReportModel.php */
/* Location: ./application/models/CustomReportModel.php */