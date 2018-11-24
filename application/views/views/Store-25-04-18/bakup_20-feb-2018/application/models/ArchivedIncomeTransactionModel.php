<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivedIncomeTransactionModel extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('YearModel');
	}

	public function ArchivedIncomeTransaction($value='')
	{
		$activeYear = $this->YearModel->getActiveYear();
		$query = $this->db->simple_query('INSERT INTO archived_income (VoucherType,VoucherNo,VoucherDateG,VoucherDateH,Permanent_VoucherNumber,Permanent_VoucherDateG,Permanent_VoucherDateH,DepartmentId,DepositType,DepositDateG,DepositDateH,Debit,Credit,Createdby,CreatedOn,UpdatedBy,UpdatedOn,BookNo,LinkID,LevelID,DepositSlipNo,SequenceNo,ReciptNo,Description,Remarks,ChequeNumber,ChequeDate,ChequeType,AccountID,BankName,Year) SELECT VoucherType,VoucherNo,VoucherDateG,VoucherDateH,Permanent_VoucherNumber,Permanent_VoucherDateG,Permanent_VoucherDateH,DepartmentId,DepositType,DepositDateG,DepositDateH,Debit,Credit,Createdby,CreatedOn,UpdatedBy,UpdatedOn,BookNo,LinkID,LevelID,DepositSlipNo,SequenceNo,ReciptNo,Description,Remarks,ChequeNumber,ChequeDate,ChequeType,AccountID,BankName,'.$activeYear[0]->year.' FROM income');
		if($query){
			$this->db->truncate('income');
			return true;
		}else{
			return false;
		}
	}


}

/* End of file ArchivedIncomeTransactionModel.php */
/* Location: ./application/models/ArchivedIncomeTransactionModel.php */