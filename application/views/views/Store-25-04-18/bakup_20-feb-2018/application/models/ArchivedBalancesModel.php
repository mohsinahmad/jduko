<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivedBalancesModel extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('YearModel');
		$this->load->model('LinkModel');
	}

	public function ArchivedBalances()
	{
		// $query = $this->db->query('SELECT ChartOfAccountId,Year,OpeningBalance,CurrentBalance,Active,Createdby,CreatedOn,UpdatedBy,UpdatedOn FROM archived_chart_of_account_years');
		// return $query->result();

		// $query = $this->db->query('SELECT ChartOfAccountId,Year,OpeningBalance,CurrentBalance,Active,Createdby,CreatedOn,UpdatedBy,UpdatedOn FROM chart_of_account_years');
		// return $query->result();

		$activeYear = $this->YearModel->getActiveYear();
		$query = $this->db->simple_query('INSERT INTO archived_chart_of_account_years (ChartOfAccountId,Year,OpeningBalance,CurrentBalance,Active,Createdby,CreatedOn,UpdatedBy,UpdatedOn)
		 SELECT ChartOfAccountId,'.$activeYear[0]->year.',OpeningBalance,CurrentBalance,Active,Createdby,CreatedOn,UpdatedBy,UpdatedOn FROM chart_of_account_years');
		if($query){
			$this->db->truncate('chart_of_account_years');
			$BalanceInserted = $this->OpenNewBalances();
			if($BalanceInserted){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function OpenNewBalances()
	{
		$activeYear = $this->YearModel->getActiveYear();
		$query = $this->db->simple_query('INSERT INTO chart_of_account_years (ChartOfAccountId,Year,OpeningBalance,CurrentBalance,Active,Createdby,CreatedOn,UpdatedBy,UpdatedOn)
		SELECT ChartOfAccountId,'.$activeYear[0]->year.',CurrentBalance,CurrentBalance,Active,Createdby,CreatedOn,UpdatedBy,UpdatedOn FROM archived_chart_of_account_years');
		if($query){
			return true;
		}else{
			return false;
		}
	}
	

}

/* End of file ArchivedBalancesModel.php */
/* Location: ./application/models/ArchivedBalancesModel.php */