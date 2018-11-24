<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ArchivedTransactionModel extends CI_Model {
	private $ActiveYear;
    private $yearState;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('YearModel');
        $this->activeyear = $this->session->userdata('current_year');
        $this->year_status = $this->YearModel->getYearStatus($this->activeyear);
	}
	public function ArchivedTransaction()
	{
        $result1 = $this->db->query('SELECT year from closing_year')->result();
        $previous_year_date = $result1[0]->year;
//        echo $previous_year_date;
        $activeYear = $this->YearModel->getActiveYear();
		$query = $this->db->simple_query('INSERT INTO archived_transactions (VoucherType,VoucherNo,VoucherDateG,VoucherDateH,Permanent_VoucherNumber,Permanent_VoucherDateG,LinkID,LevelID,AccountID,Debit,Credit,DepartmentId,PaidTo,Remarks,SequenceNo,ChequeNumber,ChequeDate,Description,Year,Createdby,CreatedOn,UpdatedBy,UpdatedOn) 
            SELECT  VoucherType,VoucherNo,VoucherDateG,VoucherDateH,Permanent_VoucherNumber,Permanent_VoucherDateG,LinkID,LevelID,AccountID,Debit,Credit,DepartmentId,PaidTo,Remarks,SequenceNo,ChequeNumber,ChequeDate,Description,'.$activeYear[0]->year.',Createdby,CreatedOn,UpdatedBy,UpdatedOn FROM transactions
            where VoucherDateH <= "'.$previous_year_date.'"');
        if($query){
            $this->db->where('VoucherDateH <=',$previous_year_date);
			$this->db->delete('transactions');
			return true;
		}else{
			return false;
		}
	}
}
/* End of file ArchivedTransactionModel.php */
/* Location: ./application/models/ArchivedTransactionModel.php */