<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Closing extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('ArchivedTransactionModel');
		$this->load->model('ArchivedIncomeTransactionModel');
		$this->load->model('ArchivedBalancesModel');
		$this->load->model('ChartModel');
		$this->load->model('LinkModel');
		$this->load->model('BookModel');
	} 

	public function index()
	{
		$ArchivedB = $this->ArchivedBalancesModel->ArchivedBalances();
		$isSet = $this->LinkModel->SetBalancesForClosing();
		$ArchivedT = $this->ArchivedTransactionModel->ArchivedTransaction();
		$ArchivedI = $this->ArchivedIncomeTransactionModel->ArchivedIncomeTransaction();
		$NewYearOpening = $this->YearModel->AddNewYear();
		
		// $IncomeAccs = $this->ChartModel->getIncomeAcc();
		// foreach ($IncomeAccs as $key => $IncomeAcc) {
		// 	$IncomeAccounts[] = $IncomeAcc->id;
		// }
		// $ExpenseAccs = $this->ChartModel->getExpenseAcc();
		// foreach ($ExpenseAccs as $key => $ExpenseAcc) {
		// 	$ExpenseAccounts[] = $ExpenseAcc->id;
		// }
		// $ExpenseOpeningBal = $this->LinkModel->Get_OpeningBalanceForClosing($ExpenseAccounts);
		// $IncomeDCSum = $this->BookModel->GetTransactionsForClosing($IncomeAccounts);
		// $ExpenseDCSum = $this->BookModel->GetTransactionsForClosing($ExpenseAccounts);

		// echo "<pre>";
		// print_r($IncomeAccounts);
		// print_r($ExpenseAccounts);
		// echo "<pre>";
		// 	print_r($isSet);
		// echo "</pre>";
		// 	//print_r($ExpenseOpeningBal);
		// echo "</pre>";
		//exit();
		if($ArchivedT == True && $ArchivedI == True && $ArchivedB == True && $isSet && $NewYearOpening){
			echo "Closing Done";
		}else{
			echo "Closing Not Done";
		}
	}

}

/* End of file Closing.php */
/* Location: ./application_cuurent_working_position/controllers/Accounts/Closing.php */