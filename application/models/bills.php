<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bills extends MY_Controller {

    public function __construct() {
        parent::__construct();
		
		$this->load->library('form_validation');
		
        if($this->uri->segment(2) == 'simple_pdf3'){
			// Allow PDF download with Login Authentication.
		} else {
			if (isset($this->user) && !is_array($this->user)) {
				$this->load->helper('url');
				$this->session->set_userdata('last_page', current_url());
				redirect();
			}
		}
		
        $this->load->model('Bills_model');
        $this->load->model('Clients_model');
        $this->load->model('Currencies_model');
        $this->load->model('Segments_model');
        $this->load->model('Users_model');
        $this->load->model('Projects_model');
        $this->load->model('Countries_model');
        $this->load->model('Branches_model');
		$this->load->model('Email_model');
		$this->load->model('Settings_model');
		$this->load->model('Others_model');
		
    }
public function get_qrm(){
$data = $this->db->get('qrm')->result();
	$response = '';	
foreach ($data as $key => $value) {
	$response .= '<option value="'.$value->id.'">"'.$value->name.'"</option>';
}
echo $response;
}
    public function voucher() {
        $data = array(
            'page' => 'bills/voucher',
        );
        $this->load->view('layout/v2/default', $data);
    }

    public function view($bill_id) {
        $data = array(
            'page' => 'bills/view',
            'bill' => $this->Bills_model->getBillByID($bill_id),
        );
        $this->load->view('layout/v2/default', $data);
    }

    public function index($error_no = '') {
		
		$count = $this->Bills_model->getAllBills('bill', false, true);
        $pagination_config = $this->getPaginationConfig($count, 'bills/index/page');
        $this->pagination->initialize($pagination_config);

        $data = array(
            'page' => 'bills/index',
            'bills' => $this->Bills_model->getAllBills('bill', $pagination_config),
            'links' => $this->pagination->create_links(),
            'error_no' => $error_no,
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	public function test_email() {
		$to[] = 'Niaz.Ahmed@pk.ey.com';
		$cc[] = 'connect.niaz@gmail.com'; 
		$bcc = array();
		$email_result = $this->Email_model->send_email('This is Main Body..', 'This is Subject..', 'Niaz.Ahmed@pk.ey.com', $to, $cc, $bcc);
		echo $email_result;
	}
	
	public function temp_update_records() {
		
		// -- TEMPORARY SCRIPT TO UPDATE Currency Rates for Credit Note  -//
		/*$query = mysql_query("SELECT id, credit_bill_id FROM bills WHERE TYPE = 'credit' AND STATUS = 'approved' AND billed_currency_rate = 0");
		while($row = mysql_fetch_array($query)){
			$query2 = mysql_query("SELECT currency_rate FROM bills WHERE id = '".$row['credit_bill_id']."'");
			$row2 = mysql_fetch_array($query2);
			mysql_query("UPDATE bills SET billed_currency_rate = ".$row2['currency_rate']." WHERE id = '".$row['id']."'");
		}*/
		/*$query = mysql_query("SELECT id FROM bills WHERE credit_note=1 AND STATUS = 'approved' AND credit_currency_rate = 0");
		while($row = mysql_fetch_array($query)){
			$query2 = mysql_query("SELECT currency_rate FROM bills WHERE credit_bill_id = '".$row['id']."'");
			$row2 = mysql_fetch_array($query2);
			mysql_query("UPDATE bills SET credit_currency_rate = ".$row2['currency_rate']." WHERE id = '".$row['id']."'");
		}*/
		
		
		// -- TEMPORARY SCRIPT TO UPDATE BILLS_DATA TABLE FOR SEGMENT, PARTNER NAMES ETC -//
		
		$query = mysql_query("SELECT * FROM bills_data");
		while($row = mysql_fetch_array($query)){

			$segment_name = $this->Segments_model->getSegmentName($row['segment_id']);
			$service1_name = $this->Segments_model->getSegmentName($row['service1_id']);
			$partner_name = $this->Users_model->getUserShortCode($row['partner_id']);
			$manager_name = $this->Users_model->getUserShortCode($row['manager_id']);
			$sr_manager_name = $this->Users_model->getUserShortCode($row['sr_manager_id']);
			
			mysql_query("UPDATE bills_data SET segment_name = '".$segment_name."', service1_name = '".$service1_name."', partner_name = '".$partner_name."', manager_name = '".$manager_name."', sr_manager_name = '".$sr_manager_name."' WHERE bid = '".$row['bid']."'");
			
		}
		
		// -- TEMPORARY SCRIPT TO REMOVE EXTRA RECORARDS FROM RECEIPTS_DATA WHOSE PARENT DOESN'T EXIT -- //
		/*$query = mysql_query("SELECT receipt_id FROM receipts_data");
		while($row = mysql_fetch_array($query)){
			$receipt_query = mysql_query("SELECT * FROM receipts WHERE id = '".$row['receipt_id']."'");
			if(mysql_num_rows($receipt_query) == 0){
				echo $row['receipt_id'].'<br>';
			}
		}*/
		
		/*echo '<table border="1">
			   <tr>
			    <th>Receipt_IDs</td>
			   	<th>Bill#</td>
			    <th>Bill Amount</td>
				<th>Receipt Amount</td>
			   </tr>';
		$query = mysql_query("SELECT id, bill_no_str, ROUND(total_amount*currency_rate) AS billed_amount, ROUND(amount_received) AS receipt_amount FROM bills WHERE status not in ('draft','deleted') and type not in ('debit','credit')");
		while($row = mysql_fetch_array($query)){
			if($row['receipt_amount'] > $row['billed_amount']){
				
				$query2 = mysql_query("SELECT receipt_id FROM receipts_data WHERE bill_id = '".$row['id']."'");
				$rc = array();
				while($row2 = mysql_fetch_array($query2)){
					$rc[] = $row2['receipt_id'];
				}
				
				echo '<tr>'
				 		.'<td>'.implode(' _ ',$rc).'</td>'
						.'<td>'.$row['bill_no_str'].'</td>'
						.'<td>'.$row['billed_amount'].'</td>'
						.'<td>'.$row['receipt_amount'].'</td>'
					 .'</tr>';
			}
		}
		echo '</table>';
		*/
		
		// -- TEMPORARY SCRIPT TO UPDATE BILLS TABLE FOR RECEIVED AMOUNT -- //
		/*$query = mysql_query("SELECT id FROM bills WHERE type NOT IN ('debit','credit')");
		while($row = mysql_fetch_array($query)){
			$bill_id = $row['id'];
			$total_receipt_query = mysql_query("SELECT SUM(receipts_data.amount) AS total_receipt_amount, receipts.date, receipts.payment_mode 
												FROM receipts_data INNER JOIN receipts ON receipts.id = receipts_data.receipt_id
												WHERE receipts_data.bill_id = '".$bill_id."'");
			$total_receipt = mysql_fetch_array($total_receipt_query);
			if($total_receipt['total_receipt_amount'] != NULL){
				mysql_query("UPDATE bills SET amount_received = ".$total_receipt['total_receipt_amount'].", 
							 receipt_date = ".$total_receipt['date'].", receipt_payment_mode = '".$total_receipt['payment_mode']."' 
							 WHERE id = '".$bill_id."'");
			}
		}*/
	
		//$count = $this->Bills_model->getAllBills('bill', false, true);
        //$pagination_config = $this->getPaginationConfig($count, 'bills/index/page');
        //$this->pagination->initialize($pagination_config);
		
		//------------- Temporary Script to Update Bill_No_Str Column in Bills Table ---------//
		/*$zeros = array('0000', '000', '00', '0', '');
        $query = mysql_query("SELECT id, bill_no, bill_date FROM bills WHERE STATUS != 'draft'");
		while($row = mysql_fetch_array($query)){
			$bill_id = $row['id'];
			$bill_no = $row['bill_no'];
			$bill_date = $row['bill_date'];
			$bill_no_str = $zeros[strlen($bill_no)] . $bill_no . '/' . date('y', $bill_date);
			mysql_query("UPDATE bills SET bill_no_str = '".$bill_no_str."' WHERE id = '".$bill_id."'");
		}*/
		//------------------------------------------------------------------------------------//		
		die('..DONE..');
	}
	
	public function common($error_no = '') {
		 
		/*$query = mysql_query("SELECT * FROM bills WHERE TYPE = 'debit' AND bill_date BETWEEN UNIX_TIMESTAMP('2010-07-01 00:00:00') AND UNIX_TIMESTAMP('2016-06-30 23:59:59')"); 
		while($row = mysql_fetch_array($query)){
			$bill_id = $row['id'];
			mysql_query("DELETE FROM bills_data WHERE bill_id = '".$bill_id."'");
			mysql_query("DELETE FROM bills WHERE id = '".$bill_id."'");
		}*/
		
		$list_types = array('bill','memo','debit','credit');
		if(isset($_POST['bills_only']))		$list_types = array('bill');
		if(isset($_POST['memos_only']))		$list_types = array('memo');
		if(isset($_POST['debits_only']))	$list_types = array('debit');
		if(isset($_POST['credits_only']))	$list_types = array('credit');
		if(isset($_POST['deleted_only']))	$list_types = 'deleted';
		if(isset($_POST['drafts_only']))	$list_types = 'draft';
		
		//'bills' => isset($_REQUEST['search-bills-submit']) ? $this->Bills_model->getAllBills($list_types) : array(),

		// $data = array(
            // 'page' => 'bills/index_common',
            // 'bills' => $this->Bills_model->getAllBills($list_types),
            // 'links' => $this->pagination->create_links(),
            // 'error_no' => $error_no,
        // );
		
		if(isset($_POST['show_records']) or isset($_POST['drafts_only']) or isset($_POST['bills_only'])
            or isset($_POST['memos_only']) or isset($_POST['debits_only']) or isset($_POST['credits_only'])
            or isset($_POST['deleted_only']) or isset($_POST['get_bill'])
        ){
            $data = array(
                'page' => 'bills/index_common',
                'bills' => $this->Bills_model->getAllBills($list_types),
                //  'links' => $this->pagination->create_links(),
                'error_no' => $error_no,
            );
        }
		else{


            $data = array(
                'page' => 'bills/index_common',
//                'bills' => $this->Bills_model->getAllBills($list_types),
                //  'links' => $this->pagination->create_links(),
                'error_no' => $error_no,
            );

        }
        $this->load->view('layout/v2/default', $data);
		
    }

    public function approval() {
        $data = array(
            'page' => 'bills/approval',
            'bills' => $this->Approvals_model->getBillsForApproval($this->user['id']),
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	public function posted($bill_id) {
        $record = array(
					'is_posted' => '1',
					'posted_by' => $this->user['id'],
				);
		$this->Bills_model->updateBillsLog($bill_id,'posted','edit');
		$this->Bills_model->updateBill($bill_id, $record);
    }
	
	public function is_approved($bill_id) {
        $bill_status = $this->Bills_model->getStatus($bill_id);
		echo $bill_status;
    }
	
	/* This Controller Unlocks the Bill to Editable again */
	public function unlock($bill_id) {
        $this->Others_model->updateEditModeStatus($bill_id, 'unlocked');
		redirect('bills/common');
    }
	
	public function copy($bill_id) {
		
		$this->Others_model->updateEditModeStatus($bill_id, 'unlocked');
		
		$new_bill_id = $this->uuid->quickGenerate();
		list($day, $month, $year) = explode('/', date('d/m/Y'));
		$bill_date = strtotime("{$month}/{$day}/{$year}");
		
		$bills_query = mysql_query("SELECT * FROM bills WHERE id = '".$bill_id."'");
		$bills_row = mysql_fetch_array($bills_query);
		
		// Get Branch SST Rate
		$setting = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		
		//SST Re-Calculation
		if($bills_row['total_others'] > 0){
			$sst_total = $bills_row['total_fees']*$setting->sst_rate;
		} else {
			$sst_total = 0;
		}
		
		$total_mount = $bills_row['total_fees']+$bills_row['total_oop']+$sst_total;
		
		// Get Currency Rate from Currencies Table
		$currency = $this->Currencies_model->getCurrencyByID($bills_row['currency_id']);
		
		$record = array(
					'id' => $new_bill_id,
					'type' => $bills_row['type'],
					'user_id' => $this->user['id'],
					'branch_id' => $this->user['branch_id'],
					'client_id' => $bills_row['client_id'],
					'billto_id' => $bills_row['billto_id'],
					'project_id' => $bills_row['project_id'],
					'billto_address_id' => $bills_row['billto_address_id'],
					'billto_address' => $bills_row['billto_address'],
					'project_title' => $bills_row['project_title'],
					'bill_date' => $bill_date,
					'currency_id' => $bills_row['currency_id'],
					'currency_rate' => $currency->rate,
					'credit_days' => $bills_row['credit_days'],
					'total_fees' => $bills_row['total_fees'],
					'total_oop' => $bills_row['total_oop'],
					'total_others' => round($sst_total),
					'discount' => $bills_row['discount'],
					'total_amount' => round($total_mount),
					'qrm' => $bills_row['qrm'],
					'conflict_check' => $bills_row['conflict_check'],
					'engagement_code' => $bills_row['engagement_code'],
					'remarks' => $bills_row['remarks'],
					'remarks2' => $bills_row['remarks2'],
					'status' => 'draft',
					'created' => time(),
					'tgl_code' => $bills_row['tgl_code'],
					'collector_id' => $bills_row['collector_id'],
				);
		
		if ($this->Bills_model->insertBill($record)) {
			
			// Bills Allocation to save in database
			$bills_allocations_query = mysql_query("SELECT * FROM bills_allocations WHERE bill_id = '".$bill_id."'");
			while($bills_allocations_row = mysql_fetch_array($bills_allocations_query)){
				
				//Re_Calculdate SST
				if($bill_allocation['others'] > 0){
					$sst_bill_data = $sst_total;
					$tot_bill_data = $total_mount;
				} else {
					$sst_bill_data = 0;
					$tot_bill_data = 0;
				}
				
				$bill_allocation[] = array(
					'sno' => $bill_allocation['sno'],
					'bill_id' => $new_bill_id,
					'segment_id' => $bill_allocation['segment_id'],
					'service1_id' => $bill_allocation['service1_id'],
					'service2_id' => $bill_allocation['service2_id'],
					'section_id' => $bill_allocation['section_id'],
					'partner_id' => $bill_allocation['partner_id'],
					'manager_id' => $bill_allocation['manager_id'],
					'task_id' => $bill_allocation['task_id'],
					'description' => $bill_allocation['description'],
					'description_oop' => $bill_allocation['description_oop'],
					'description_tax' => $bill_allocation['description_tax'],
					'fee' => $bill_allocation['fee'],
					'oop' => $bill_allocation['oop'],
					'others' => round($sst_bill_data),
					'tax' => $sst_bill_data,
					'total' => round($tot_bill_data)
				);
				$this->Bills_model->insertBillAllocation($bill_allocation);
				unset($bill_allocation);
			}
			
			// Bills Data to save in database
			$bills_data_query = mysql_query("SELECT * FROM bills_data WHERE bill_id = '".$bill_id."'");
			while($bills_data_row = mysql_fetch_array($bills_data_query)){
				
				//Re_Calculdate SST
				if($bills_data_row['others'] > 0){
					$sst_bill_data = $sst_total;
					$tot_bill_data = $total_mount;
				} else {
					$sst_bill_data = 0;
					$tot_bill_data = 0;
				}
				
				$bills_data[] = array(
					'sno' => $bills_data_row['sno'],
					'bill_id' => $new_bill_id,
					'bill_data_type' => $bills_data_row['bill_data_type'],
					'segment_id' => $bills_data_row['segment_id'],
					'service1_id' => $bills_data_row['service1_id'],
					'service2_id' => $bills_data_row['service2_id'],
					'section_id' => $bills_data_row['section_id'],
					'partner_id' => $bills_data_row['partner_id'],
					'manager_id' => $bills_data_row['manager_id'],
					'sr_manager_id' => $bills_data_row['sr_manager_id'],
					'task_id' => $bills_data_row['task_id'],
					'description' => $bills_data_row['description'],
					'description_oop' => $bills_data_row['description_oop'],
					'description_tax' => $bills_data_row['description_tax'],
					'fee' => $bills_data_row['fee'],
					'oop' => $bills_data_row['oop'],
					'others' => $sst_bill_data,
					'tax' => $sst_bill_data,
					'total' => $tot_bill_data
				);
				$this->Bills_model->insertBillData($bills_data);
				unset($bills_data);
			}
			
			// Copy Bills Other Details 
			//'special_instructions' => $other_details_row['special_instructions'],
			$other_details_query = mysql_query("SELECT * FROM bills_other_details WHERE bill_id = '".$bill_id."'");
			$other_details_row = mysql_fetch_array($other_details_query);
			$other_data = array(
						'bill_id' => $new_bill_id,
						'attention_line_1' => $other_details_row['attention_line_1'],
						'attention_line_2' => $other_details_row['attention_line_2'],
						'special_instructions' => $other_details_row['special_instructions'],
						'terms_conditions' => $other_details_row['terms_conditions'],
						'signature' => $other_details_row['signature'],
						'note' => $other_details_row['note']
					);
			$this->Bills_model->updateBillsOtherDetail($other_data);
			
			// Update Bill History Log
			$this->Bills_model->updateBillsLog($new_bill_id,'copy',$bills_row['bill_no_str']);
			
			if($bills_row['type'] == 'bill')
				redirect("bills/edit/{$new_bill_id}");
			if($bills_row['type'] == 'memo')
				redirect("memos/edit2/{$new_bill_id}");
			if($bills_row['type'] == 'debit')
				redirect("debits/edit/{$new_bill_id}");
			
		} else {
			redirect('bills/common');
		}
    }

    public function addv2($type = '') {
        
		$setting = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		
		if (isset($_POST['draft']) || isset($_POST['save']) || (isset($_POST['approval-dialog-values']) && !empty($_POST['approval-dialog-values'])) || isset($_POST['client'])) {
						
			if($type==''|| $_POST['project']=='0'){
				$this->form_validation->set_rules('client', 'Client', 'required');
				$this->form_validation->set_rules('client_address2', 'Address', 'required');
				$this->form_validation->set_rules('segments[]', 'Segment', 'required');
				$this->form_validation->set_rules('service1[]', 'Service-1', 'required');
				$this->form_validation->set_rules('partner[]', 'Partner', 'required');
				$this->form_validation->set_rules('qrm', 'qrm', 'required');
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
			} else {
				$this->form_validation->set_rules('client', 'Client', 'required');
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			}
			
			if ($this->form_validation->run() == FALSE){
				
				// Don't Save anything - Show Errors.
				
			}else{
				
				// Task start date
				list($day, $month, $year) = explode('/', $this->input->post('bill_date'));
				$bill_date = strtotime("{$month}/{$day}/{$year}");

				$record = array(
					'id' => $this->uuid->quickGenerate(),
					'type' => 'bill',
					'branch_id' => $this->user['branch_id'],
					'client_id' => $this->input->post('client'),
					'billto_id' => $this->input->post('bill_to'),
					'project_id' => $this->input->post('project'),
					'billto_address' => $this->input->post('client_address2'),
					'project_title' => $this->input->post('project_title'),
					'bill_date' => $bill_date,
					'currency_rate' => $this->input->post('currency_rate'),
					'credit_days' => $this->input->post('credit_days'),
					'discount' => $this->input->post('discount'),
					'total_amount' => round($this->input->post('total_amount')),
					'remarks' => $this->input->post('bill_remarks'),
					'page2_enable' => $this->input->post('page2_enable'),
					'qrm_id' => $this->input->post('qrm'),
					'draft_saved' => 1,
					'created' => time(),
				);
				
				$project_id = $this->input->post('project');
				if($project_id=='0'){				
					list($currency_id) = explode('^', $this->input->post('currency'));
					$record['currency_id'] = $currency_id;
					
					if(($record['currency_rate'] == 0) || ($record['currency_rate'] == '')){
						$currency = $this->Currencies_model->getCurrencyByID($currency_id);
						$record['currency_rate'] = $currency->rate;
					}
					
					$client_address = explode(':', $this->input->post('client_address'));
					if (isset($client_address[0]))
						$record['billto_address_id'] = $client_address[0];
				} else {
					$projectData = $this->Projects_model->getProjectByID($project_id);
					$record['currency_id'] = $projectData->currency_id;
					$record['billto_address_id'] = $projectData->billto_address_id;
				}				
				
				/*$client_address = explode(':', $this->input->post('client_address'));
				if (isset($client_address[0]))
					$record['billto_address_id'] = $client_address[0];*/
				
				// check if user pressed draft button
				if (isset($_POST['draft'])) {
					$record['status'] = 'draft';
				}
				// check if user pressed save button and no approval is required
				else if (isset($_POST['save']) && empty($_POST['approval-dialog-values'])) {
					$record['status'] = 'saved';
					$record['bill_no'] = $this->Bills_model->getBillNo();
				}
				// If approval is required
				else if (!empty($_POST['approval-dialog-values'])) {
					$record['status'] = 'pending';
					$record['bill_no'] = $this->Bills_model->getBillNo();
				}

				// Check if user is logged in
				if (isset($this->user['id'])) {
					$record['user_id'] = $this->user['id'];
				}

				/*// Calculating total fees
				if ($this->input->post('fee'))
					$record['total_fees'] = array_sum($this->input->post('fee'));

				// Calculating total O.O.P
				if ($this->input->post('oop'))
					$record['total_oop'] = array_sum($this->input->post('oop'));

				// Calculating total other
				if(in_array('sst_calc',$this->input->post('rec_type'))){
					$record['total_others'] = $record['total_fees'] * $setting->sst_rate;
				} else {
					$record['total_others'] = 0;
				}*/
				/*if ($this->input->post('other'))
					$record['total_others'] = array_sum($this->input->post('other'));*/
					
				$client_id = $record['client_id'];

				// Insert data into database
				if ($this->Bills_model->insertBill($record)) {
					//Update Client's NTN number					
					$client_record = array(
						'ntn' => $this->input->post('ntn'),
					);
					$this->Clients_model->updateClient($client_id, $client_record);
					
					
					$note = preg_replace('/(<br>)+$/', '', $this->input->post('note'));
					$other_data = array(
						'bill_id' => $record['id'],
						'attention_line_1' => $this->input->post('attention_line_1'),
						'attention_line_2' => $this->input->post('attention_line_2'),
						'special_instructions' => $this->input->post('special_instructions'),
						'note' => $note,
					);
					$this->Bills_model->updateBillsOtherDetail($other_data);

					$bill_data = array();
					$bill_post = array();

					$vars = array('segments', 'service1', 'service2', 'section', 'partner', 'manager', 'sr_manager', 'task', 'description', 'description_oop', 'description_tax', 'fee', 'oop', 'other', 'tax', 'total', 'sno');
					foreach ($vars as $var) {
						$bill_post[$var] = $this->input->post($var);
					}
					
					/*$fee_arr = array_filter($bill_post['description']);
					$oop_arr = array_filter($bill_post['description_oop']);*/
					$fee_arr = array_filter($bill_post['fee']);
					$oop_arr = array_filter($bill_post['oop']);
					if(count($oop_arr) > count($fee_arr)){ $bill_data_arr = $oop_arr; }
					else { $bill_data_arr = $fee_arr; }
					
					$total_fee = 0;
					$total_oop = 0;
					//$bill_total_amount = 0;
					
					//foreach ($bill_post['segments'] as $index => $segment)//
					foreach ($bill_data_arr as $index => $val) {
						// Check to eliminate empty record in database
						if ((empty($bill_post['fee'][$index]) || $bill_post['fee'][$index] == 0) && (empty($bill_post['oop'][$index]) || $bill_post['oop'][$index] == 0) && (empty($bill_post['other'][$index]) || $bill_post['other'][$index] == 0)) {
							continue;
						}
						
						/*if (empty($bill_post['description'][$index]) || $bill_post['description'][$index] == '')
							$fee = 0;
						else $fee = $bill_post['fee'][$index];*/
						
						/*if (empty($bill_post['description_oop'][$index]) || $bill_post['description_oop'][$index] == '')
							$oop = 0;
						else $oop = $bill_post['oop'][$index];*/
						if($project_id=='0'){
							$segment_id = $bill_post['segments'][0];
							$service1_id = $bill_post['service1'][0];
							$service2_id = $bill_post['service2'][0];
							$section_id = $bill_post['section'][0];
							$partner_id = $bill_post['partner'][0];
							$manager_id = $bill_post['manager'][0];
							$sr_manager_id = $bill_post['sr_manager'][0];
						} else {
							$segment_id = $projectData->segment_id;
							$service1_id = $projectData->service1_id;
							$service2_id = $projectData->service2_id;
							$section_id = $projectData->section_id;
							$partner_id = $projectData->partner_id;
							$manager_id = $projectData->manager_id;
							$sr_manager_id = $projectData->sr_manager_id;
						}
						$task_id = $bill_post['task'][0];

						//$total_fee += $fee;
						//$total_oop += $oop;
						$total_fee += $bill_post['fee'][$index];
						$total_oop += $bill_post['oop'][$index];
						//$bill_total_amount += $bill_post['fee'][$index] + $bill_post['oop'][$index] + $bill_post['other'][$index];
						
						/*if(empty($bill_post['fee'][$index]) || $bill_post['fee'][$index] == 0){
							$fee_description = '';
						} else {
							$fee_description = preg_replace('/(<br>)+$/', '', $bill_post['description'][$index]);
						}
						if(empty($bill_post['oop'][$index]) || $bill_post['oop'][$index] == 0){
							$oop_description = '';
						} else {
							$oop_description = $bill_post['description_oop'][$index];
						}
						if(empty($bill_post['other'][$index]) || $bill_post['other'][$index] == 0){
							$tax_description = '';
						} else {
							$tax_description = $bill_post['description_tax'][$index];
						}*/
						
						if (empty($bill_post['description'][$index]) || $bill_post['description'][$index] == ''){
							$fee_description = 'FEE';	
						} else {
							$fee_description = preg_replace('/(<br>)+$/', '', $bill_post['description'][$index]);
						}
						$oop_description = nl2br($bill_post['description_oop'][$index]);
						$tax_description = nl2br($bill_post['description_tax'][$index]);
						
						$segment_name = $this->Segments_model->getSegmentName($segment_id);
						$service1_name = $this->Segments_model->getSegmentName($service1_id);
						$partner_name = $this->Users_model->getUserShortCode($partner_id);
						$manager_name = $this->Users_model->getUserShortCode($manager_id);
						$sr_manager_name = $this->Users_model->getUserShortCode($sr_manager_id);
						// Billin data to save in database
						$bill_data[] = array(
							'sno' => $bill_post['sno'][$index],
							'bill_id' => $record['id'],
							'segment_id' => $segment_id,
							'segment_name' => $segment_name,
							'service1_id' => $service1_id,
							'service1_name' => $service1_name,
							'service2_id' => $service2_id,
							'section_id' => $section_id,
							'partner_id' => $partner_id,
							'partner_name' => $partner_name,
							'manager_id' => $manager_id,
							'manager_name' => $manager_name,
							'sr_manager_id' => $sr_manager_id,
							'sr_manager_name' => $sr_manager_name,
							'task_id' => $task_id,
							'description' => $fee_description,
							'description_oop' => $oop_description,
							'description_tax' => $tax_description,
							//'description_others' => $bill_post['description_others'][$index],
							'fee' => round($bill_post['fee'][$index]),
							'oop' => round($bill_post['oop'][$index]),
							'others' => round($bill_post['other'][$index]),
							'tax' => $bill_post['tax'][$index],
							'total' => round($bill_post['total'][$index])
						);
					}
					if (sizeof($bill_data))
						$this->Bills_model->insertBillData($bill_data);
					
					// Calculating total other
					if(in_array('sst_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $setting->sst_rate;
						$sst_rate = $setting->sst_rate;
						$sst_log = round($total_others);
					} else if(in_array('sst2_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $setting->sst2_rate;
						$sst_rate = $setting->sst2_rate;
						$sst_log = round($total_others);
					} else if(in_array('sst3_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $setting->sst3_rate;
						$sst_rate = $setting->sst3_rate;
						$sst_log = round($total_others);
					} else {
						$total_others = 0;
						$sst_rate = 0;
						$sst_log = 'Exempt';
					}
					$bill_total_amount = $total_fee + $total_oop + $total_others;
					
					// For Safe side, Update Server Side Total in Bills and Bills_Data Table. 
					mysql_query("UPDATE bills SET total_fees = ".$total_fee.", total_oop = ".$total_oop.", total_others = ".round($total_others).", sst_rate = ".$sst_rate.", total_amount = ".round($bill_total_amount)." WHERE id = '".$record['id']."'");
					//Save Amounts in Bill History Log
					$log_amounts = "Total Fee: ".$total_fee.", Total OOP: ".$total_oop.", SST: ".$sst_log.", Total Amount: ".round($bill_total_amount);
					
					//mysql_query("UPDATE bills_data SET others = ".round($total_others).", total = ".round($bill_total_amount)." WHERE bill_id = '".$record['id']."'");
					
					
					/*$vars = array('allocation-segments', 'allocation-service1', 'allocation-service2', 'allocation-section', 'allocation-partner', 'allocation-manager', 'allocation-task', 'allocation-description', 'allocation-description_oop', 'allocation-description_tax', 'allocation-fee', 'allocation-oop', 'allocation-other', 'allocation-tax', 'allocation-total');
					foreach ($vars as $var) {
						$bill_post[$var] = $this->input->post($var);
					}

					$bill_data = array();
					foreach ($bill_post['allocation-segments'] as $index => $segment) {
						// Check to eliminate empty record in database
						if ((empty($bill_post['allocation-fee'][$index]) || $bill_post['allocation-fee'][$index] == 0) && (empty($bill_post['allocation-oop'][$index]) || $bill_post['allocation-oop'][$index] == 0) && (empty($bill_post['allocation-other'][$index]) || $bill_post['allocation-other'][$index] == 0)) {
							continue;
						}

						// Allocation data to save in database
						$bill_data[] = array(
							'bill_id' => $record['id'],
							'segment_id' => $segment,
							'service1_id' => $bill_post['allocation-service1'][$index],
							'service2_id' => $bill_post['allocation-service2'][$index],
							'section_id' => $bill_post['allocation-section'][$index],
							'partner_id' => $bill_post['allocation-partner'][$index],
							'manager_id' => $bill_post['allocation-manager'][$index],
							'task_id' => $bill_post['allocation-task'][$index],
							'description' => $bill_post['allocation-description'][$index],
							'description_oop' => $bill_post['allocation-description_oop'][$index],
							'description_tax' => $bill_post['allocation-description_tax'][$index],
							'fee' => $bill_post['allocation-fee'][$index],
							'oop' => $bill_post['allocation-oop'][$index],
							'others' => $bill_post['allocation-other'][$index],
							'tax' => $bill_post['allocation-tax'][$index],
							'total' => $bill_post['allocation-total'][$index]
						);
					}

					if (sizeof($bill_data))
						$this->Bills_model->insertBillAllocation($bill_data);*/

					$approvals = $this->input->post('approval-dialog-values');
					if (!empty($approvals)) {
						$approvals = explode(',', $approvals);
						$approval_data = array();
						foreach ($approvals as $approval) {
							$approval_data[] = array(
								'type' => 'bill',
								'type_id' => $record['id'],
								'user_id' => $approval,
								'status' => 'pending',
							);
						}

						if (sizeof($approval_data))
							$this->Approvals_model->insertApprovals($approval_data);
					}
					
					if (isset($_POST['draft'])) {
						$this->Bills_model->updateBillsLog($record['id'],'draft','new','Bill', $log_amounts);
						//redirect("bills/edit/{$record['id']}/new");
						if($type == 'new') 
							redirect("bills/edit/{$record['id']}/new");
						else
							redirect("bills/edit/{$record['id']}");
					} else {
						redirect('bills/common');
					}
				}
			
			}
			
        }

        // Generating data for segments
        $all_segments = array();
        $segments = $this->Segments_model->getAllSegments();
        foreach ($segments as $segment) {
            if (empty($segment->parent_id) || $segment->parent_id == "0") {
                $all_segments[$segment->id]['parent'] = array(
                    'id' => $segment->id,
                    'parent_id' => $segment->parent_id,
                    'name' => $segment->name,
                );
            } else {
                $all_segments[$segment->parent_id]['childs'][] = array(
                    'id' => $segment->id,
                    'parent_id' => $segment->parent_id,
                    'name' => str_replace("'", '', $segment->name),
                );
            }
        }

        // Generating data for sections in segment
        $sections = $this->Segments_model->getAllSections();
        foreach ($sections as $section) {
            if (empty($section->segment_id) || $section->segment_id == "0") {
                // Do nothing
            } else {
                $all_segments[$section->segment_id]['segments'][] = array(
                    'id' => $section->id,
                    'parent_id' => $section->segment_id,
                    'name' => str_replace("'", '', $section->name),
                );
            }
        }

        $partners = $this->Users_model->getUserByRoleName('Partner', array("users.segment_id != ''"));
        foreach ($partners as $partner) {
            if (empty($partner->segment_id) || $partner->segment_id == "0") {
                // Do nothing
            } else {
                $all_segments[$partner->segment_id]['partners'][] = array(
                    'id' => $partner->id,
                    'parent_id' => $partner->segment_id,
                    'name' => str_replace("'", '', $partner->full_name),
                );
            }
        }

        $managers = $this->Users_model->getUserByRoleName('Manager', array("users.segment_id != ''"));
        foreach ($managers as $manager) {
            if (empty($manager->segment_id) || $manager->segment_id == "0") {
                // Do nothing
            } else {
                $all_segments[$manager->segment_id]['managers'][] = array(
                    'id' => $manager->id,
                    'parent_id' => $manager->segment_id,
                    'name' => str_replace("'", '', $manager->full_name),
                );
            }
        }
		
		$sr_managers = $this->Users_model->getUserByRoleName('SM_ED', array("users.segment_id != ''"));
        foreach ($sr_managers as $sr_manager) {
            if (empty($sr_manager->segment_id) || $sr_manager->segment_id == "0") {
                // Do nothing
            } else {
                $all_segments[$sr_manager->segment_id]['sr_managers'][] = array(
                    'id' => $sr_manager->id,
                    'parent_id' => $sr_manager->segment_id,
                    'name' => str_replace("'", '', $sr_manager->full_name),
                );
            }
        }

        $accounting_period = array(
            'start' => explode('-', date('Y-n-j', $this->variables['accounting_period_start'])),
            'end' => explode('-', date('Y-n-j', $this->variables['accounting_period_end'])),
        );
		
		if($type == 'new') $page = 'bills/v2/addp'; 
		else $page = 'bills/v2/add2';
				
        $data = array(
            'page' => $page,
            'clients' => $this->Clients_model->getClientsByBranchID($this->variables['branch']),
            'currencies' => $this->Currencies_model->getAllCurrency(),
			'remitting_banks' => $this->Bills_model->getRemittingBanks(),
            'segments' => $this->Segments_model->getAllSegments(0),
            'all_segments' => json_encode($all_segments), //$this->Segments_model->getAllSegments(0),
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
            'managers' => $this->Users_model->getUserByRoleName('Manager'),
			'sr_managers' => $this->Users_model->getUserByRoleName('Sr. Manager'),
            'users' => $this->Users_model->getUsers(),
            'countries' => $this->Countries_model->getAllCountry(),
            'branches' => $this->Branches_model->getAllBranch(),
            'accounting_period' => json_encode($accounting_period),
			'setting' => $setting,
        );

        $this->load->view('layout/v2/default', $data);
    }

    public function edit($bill_id, $type = '') {
				
		// By Default BILL is Un-Locked. Status will be checked down the script. again.
		$editable = 'unlocked';
		
		// Check Whether Bill Edit Mode is Locked or Unclocked.
		// $edit_mode_status = $this->Others_model->chkEditModeStatus($bill_id);
		// $edit_mode = explode("|",$edit_mode_status);
		// if($edit_mode[0] == 'locked'){
			// $editable = 'locked';		
		// }
		
		$email_settings = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		$from = $email_settings->from_email;
		
		//// ------ START - Important Check to avoid users opening Approved bills again. ------- //
		//if(($this->Bills_model->getStatus($bill_id) == 'approved') && ($this->user['id'] != 'ccd4290c-ad21-50e5-9457-f0e77bac1659')){
			
			/*$user_branch = $this->Branches_model->getBranchByID($this->user['branch_id']);
			$email_body = 
				'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<title>EYFRSH Billing Management System</title>
				</head>
				<body>
					<p>Dear Admin,</p>
					<p>A user has tried to edit an approved bill, details are given below:</p>
					<table border="0">
						<tr>
							<td width="160" valign="top">Bill URL<span style="float:right">:</span></td>
							<td> <a href="'.site_url("bills/edit/{$bill_id}").'">View Bill</a></td>
						</tr>
						<tr>
							<td>User<span style="float:right">:</span></td>
							<td> '.$this->user['full_name'].'</td>
						</tr>
						<tr>
							<td>IP<span style="float:right">:</span></td>
							<td> '.$_SERVER['REMOTE_ADDR'].'</td>
						</tr>
						<tr>
							<td>Branch<span style="float:right">:</span></td>
							<td> '.$user_branch->name.'</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
					 </table>
					 <p>Please take necessary action.</p>
					 <p>EYFRSH Billing Management System</p>
					 <br>
				</body>
				</html>';
	
				$email_subject = 'Un-authorized edit access by user '.$this->user['full_name'].'('.$user_branch->name.')';
				$to[] = 'Niaz.Ahmed@pk.ey.com';
				$this->Email_model->send_email($email_body, $email_subject, $from, $to, $cc, $bcc);*/
			
			//redirect('bills/common');
		//}
		//// ------ END - Important Check to avoid users opening Approved bills again. ------- //
		
		if (isset($_POST['draft']) || isset($_POST['save']) || (isset($_POST['approval-dialog-values']) && !empty($_POST['approval-dialog-values'])) || isset($_POST['client'])) {
									
									
			if($type==''|| $_POST['project']=='0'){
				$this->form_validation->set_rules('qrm', 'qrm', 'required');
				$this->form_validation->set_rules('client', 'Client', 'required');
				$this->form_validation->set_rules('client_address2', 'Address', 'required');
				$this->form_validation->set_rules('segments[]', 'Segment', 'required');
				$this->form_validation->set_rules('service1[]', 'Service-1', 'required');
				$this->form_validation->set_rules('partner[]', 'Partner', 'required');
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>'); 
			} else {
				$this->form_validation->set_rules('client', 'Client', 'required');
				$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
			}
			
			if ($this->form_validation->run() == FALSE){				
				// Don't Save anything - Show Errors.				
			}else{
				// Task start date
				list($day, $month, $year) = explode('/', $this->input->post('bill_date'));
				$bill_date = strtotime("{$month}/{$day}/{$year}");
				$record = array(
					'id' => $bill_id,
	//                'branch_id' => $this->variables['branch'],
					'client_id' => $this->input->post('client'),
					'billto_id' => $this->input->post('bill_to'),
					'project_id' => $this->input->post('project'),
					'billto_address' => $this->input->post('client_address2'),
					'project_title' => $this->input->post('project_title'),
					'bill_date' => $bill_date,
					'currency_rate' => $this->input->post('currency_rate'),
					'credit_days' => $this->input->post('credit_days'),
					'discount' => $this->input->post('discount'),
					'total_amount' => round($this->input->post('total_amount')),
					'remarks' => $this->input->post('bill_remarks'),
					'collector_id' => $this->input->post('clients_collector'),
					'page2_enable' => $this->input->post('page2_enable'),
					'qrm_id' => $this->input->post('qrm'),
					'draft_saved' => 1,
					'created' => time(),
				);				
				// For Bill No. String. Full bill number with Year.
				$record['bill_no_str'] = $this->input->post('bill_no_str');
				$zeros = array('0000', '000', '00', '0', '');				
				$project_id = $this->input->post('project');
				if($project_id=='0'){						
					list($currency_id) = explode('^', $this->input->post('currency'));
					$record['currency_id'] = $currency_id;					
					if(($record['currency_rate'] == 0) || ($record['currency_rate'] == '')){
						$currency = $this->Currencies_model->getCurrencyByID($currency_id);
						$record['currency_rate'] = $currency->rate;
					}					
					$client_address = explode(':', $this->input->post('client_address'));
					if (isset($client_address[0]))
						$record['billto_address_id'] = $client_address[0];
				} else {
					
					$projectData = $this->Projects_model->getProjectByID($project_id);
					$record['currency_id'] = $projectData->currency_id;
					$record['billto_address_id'] = $projectData->billto_address_id;
				}				
				/*$client_address = explode(':', $this->input->post('client_address'));
				if (isset($client_address[0]))
					$record['billto_address_id'] = $client_address[0];*/
				// check if user pressed draft button
				if (isset($_POST['draft'])) {
					$record['status'] = 'draft';
				}
				// check if user pressed save button and no approval is required
				else if (isset($_POST['save']) && empty($_POST['approval-dialog-values'])) {
					//$record['status'] = 'save';
					$bill_no = $this->input->post('bill_no');
					if (!$bill_no){
						$bill_no = $this->Bills_model->getBillNo();
						$record['bill_no'] = $bill_no;
						$record['bill_no_str'] = $zeros[strlen($bill_no)].$bill_no.'/'.date('y', $bill_date);
					}
				}
				// If approval is required
				else if (!empty($_POST['approval-dialog-values'])) {
					$record['status'] = 'pending';
					//$record['bill_no'] = $this->Bills_model->getBillNo();
					
					$bill_no = $this->Bills_model->getBillNo();
					$record['bill_no'] = $bill_no;
					$record['bill_no_str'] = $zeros[strlen($bill_no)].$bill_no.'/'.date('y', $bill_date);
				}					
				if($_POST['draft_approved'] == '1'){
					if(isset($_POST['draft']) || isset($_POST['save'])){
					} else {
						//$record['status'] = 'save';
						$record['status'] = 'approved';
						//$this->Bills_model->updateBillsLog($record['id'],'save','edit');
						//$record['bill_no'] = $this->Bills_model->getBillNo();
						
						$bill_no = $this->Bills_model->getBillNo();
						$record['bill_no'] = $bill_no;
						$record['bill_no_str'] = $zeros[strlen($bill_no)].$bill_no.'/'.date('y', $bill_date);
						
						// SAVE LAST BILL NO GENERATED
						/*mysql_query("UPDATE bill_no SET counter=".$record['bill_no'].", bill_no_str='".$record['bill_no_str']."', 
									  branch_id = '".$this->user['branch_id']."' WHERE type = 'bill'");*/
					}
				}
				// Check if user is logged in
				if (isset($this->user['id'])) {
					$record['user_id'] = $this->user['id'];
				}
				/*// Calculating total fees
				if ($this->input->post('fee'))
					$record['total_fees'] = array_sum($this->input->post('fee'));

				// Calculating total O.O.P
				if ($this->input->post('oop'))
					$record['total_oop'] = array_sum($this->input->post('oop'));

				// Calculating total other
				if(in_array('sst_calc',$this->input->post('rec_type'))){
					$record['total_others'] = $record['total_fees'] * $email_settings->sst_rate;
					$sst_total = $record['total_fees'] * $email_settings->sst_rate;
				} else {
					$record['total_others'] = 0;
					$sst_total = 0;
				}*/
				/*if ($this->input->post('other'))
					$record['total_others'] = array_sum($this->input->post('other'));*/
				
				$bill_no_str = $record['bill_no_str'];
				$bill_currency_rate = str_replace(' ','',$record['currency_rate']);
				$bill_currency_rate = str_replace(',','',$bill_currency_rate);
				$bill_currency_rate = round($bill_currency_rate, 2);
				
				// Updating data into database
				if ($this->Bills_model->updateBill($bill_id, $record)) {
					
					$client_record = array(
						'ntn' => $this->input->post('ntn'),
					);
					$this->Clients_model->updateClient($record['client_id'], $client_record);
					
					$emailResult = '';						
					$note = preg_replace('/(<br>)+$/', '', $this->input->post('note'));
					$other_data = array(
						'bill_id' => $record['id'],
						'attention_line_1' => $this->input->post('attention_line_1'),
						'attention_line_2' => $this->input->post('attention_line_2'),
						'special_instructions' => $this->input->post('special_instructions'),
						'note' => $note,
					);

					$bill_data = array();
					$bill_post = array();

					$vars = array('segments', 'service1', 'service2', 'section', 'partner', 'manager', 'sr_manager', 'task', 'description', 'description_oop', 'description_tax', 'fee', 'oop', 'other', 'tax', 'total', 'sno');
					foreach ($vars as $var) {
						$bill_post[$var] = $this->input->post($var);
					}
					
					/*$fee_arr = array_filter($bill_post['description']);
					$oop_arr = array_filter($bill_post['description_oop']);*/
					$fee_arr = array_filter($bill_post['fee']);
					$oop_arr = array_filter($bill_post['oop']);
					
					if(count($oop_arr) > count($fee_arr)){ $bill_data_arr = $oop_arr; }
					else { $bill_data_arr = $fee_arr; }
					
					$total_fee = 0;
					$total_oop = 0;
					$total_sst = 0;
					//$bill_total_amount = 0;
					
					//$sno = 1;
					// foreach ($bill_post['segments'] as $index => $segment) //
					foreach ($bill_data_arr as $index => $val) {
						
						// Check to eliminate empty record in database
						if ((empty($bill_post['fee'][$index]) || $bill_post['fee'][$index] == 0) && (empty($bill_post['oop'][$index]) || $bill_post['oop'][$index] == 0) && (empty($bill_post['other'][$index]) || $bill_post['other'][$index] == 0)) {
							continue;
						}
						
						/*if (empty($bill_post['description'][$index]) || $bill_post['description'][$index] == '')
							$fee = 0;
						else $fee = $bill_post['fee'][$index];
						
						if (empty($bill_post['description_oop'][$index]) || $bill_post['description_oop'][$index] == '')
							$oop = 0;
						else $oop = $bill_post['oop'][$index];*/
						
						if($project_id=='0'){
							$segment_id = $bill_post['segments'][0];
							$service1_id = $bill_post['service1'][0];
							$service2_id = $bill_post['service2'][0];
							$section_id = $bill_post['section'][0];
							$partner_id = $bill_post['partner'][0];
							$manager_id = $bill_post['manager'][0];
							$sr_manager_id = $bill_post['sr_manager'][0];
						} else {
							$segment_id = $projectData->segment_id;
							$service1_id = $projectData->service1_id;
							$service2_id = $projectData->service2_id;
							$section_id = $projectData->section_id;
							$partner_id = $projectData->partner_id;
							$manager_id = $projectData->manager_id;
							$sr_manager_id = $projectData->sr_manager_id;
						}
						$task_id = $bill_post['task'][0];
						
						//$total_fee += $fee;
						//$total_oop += $oop;
						$total_fee += $bill_post['fee'][$index];
						$total_oop += $bill_post['oop'][$index];
						$total_sst += $bill_post['other'][$index];
						//$bill_total_amount += $bill_post['fee'][$index] + $bill_post['oop'][$index] + $bill_post['other'][$index];
						
						/*if(empty($bill_post['fee'][$index]) || $bill_post['fee'][$index] == 0){
							$fee_description = '';
						} else {
							$fee_description = preg_replace('/(<br>)+$/', '', $bill_post['description'][$index]);
						}
						if(empty($bill_post['oop'][$index]) || $bill_post['oop'][$index] == 0){
							$oop_description = '';
						} else {
							$oop_description = $bill_post['description_oop'][$index];
						}
						if(empty($bill_post['other'][$index]) || $bill_post['other'][$index] == 0){
							$tax_description = '';
						} else {
							$tax_description = $bill_post['description_tax'][$index];
						}*/
						if (empty($bill_post['description'][$index]) || $bill_post['description'][$index] == ''){
							$fee_description = 'FEE';	
						} else {
							$fee_description = preg_replace('/(<br>)+$/', '', $bill_post['description'][$index]);
						}
						$oop_description = nl2br($bill_post['description_oop'][$index]);
						$tax_description = nl2br($bill_post['description_tax'][$index]);
						
						$segment_name = $this->Segments_model->getSegmentName($segment_id);
						$service1_name = $this->Segments_model->getSegmentName($service1_id);
						$partner_name = $this->Users_model->getUserShortCode($partner_id);
						$manager_name = $this->Users_model->getUserShortCode($manager_id);
						$sr_manager_name = $this->Users_model->getUserShortCode($sr_manager_id);
						
						// Billin data to save in database
						$bill_data[] = array(
							'sno' => $bill_post['sno'][$index],
							'bill_id' => $record['id'],
							'segment_id' => $segment_id,
							'segment_name' => $segment_name,
							'service1_id' => $service1_id,
							'service1_name' => $service1_name,
							'service2_id' => $service2_id,
							'section_id' => $section_id,
							'partner_id' => $partner_id,
							'partner_name' => $partner_name,
							'manager_id' => $manager_id,
							'manager_name' => $manager_name,
							'sr_manager_id' => $sr_manager_id,
							'sr_manager_name' => $sr_manager_name,
							'task_id' => $task_id,
							'description' => $fee_description,
							'description_oop' => $oop_description,
							'description_tax' => $tax_description,
							'fee' => round($bill_post['fee'][$index]),
							'oop' => round($bill_post['oop'][$index]),
							'others' => round($bill_post['other'][$index]),
							'tax' => $bill_post['tax'][$index],
							'total' => round($bill_post['total'][$index])
						);
						
					}

					if (sizeof($bill_data)) {
						$this->Bills_model->deleteBillData($record['id']);
						$this->Bills_model->insertBillData($bill_data);
					}
					
					// Calculating total other
					/*if(in_array('sst_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $email_settings->sst_rate;
						$sst_log = round($total_others);
					} else {
						$total_others = 0;
						$sst_log = 'Exempt';
					}*/
					
					// Calculating total other
					if(in_array('sst_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $email_settings->sst_rate;
						$sst_rate = $email_settings->sst_rate;
						$sst_log = round($total_others);
					} else if(in_array('sst2_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $email_settings->sst2_rate;
						$sst_rate = $email_settings->sst2_rate;
						$sst_log = round($total_others);
					} else if(in_array('sst3_calc',$this->input->post('rec_type'))){
						$total_others = $total_fee * $email_settings->sst3_rate;
						$sst_rate = $email_settings->sst3_rate;
						$sst_log = round($total_others);
					} else if(in_array('sst_custom',$this->input->post('rec_type'))){
						$total_others = $total_sst;
						$sst_rate = $total_others/$total_fee;
						$sst_log = round($total_others);
					} else {
						$total_others = 0;
						$sst_rate = 0;
						$sst_log = 'Exempt';
					}
					
					$bill_total_amount = $total_fee + $total_oop + $total_others;
					
					
					
					
					
					if($_POST['draft_approved'] == '1'){
						
						if(isset($_POST['draft']) || isset($_POST['save'])){
							
						} else {
							//------------- Email Script -------------//
							
							//Get emails of Partner and Manager.
							$partner_temp = $this->input->post('partner');
							$manager_temp = $this->input->post('manager');
							$sr_manager_temp = $this->input->post('sr_manager');
							
							$to_name = array();
							$to_email = array();
							$et_arr = array();
							
							$queryp = mysql_query("SELECT full_name, email_address, cc_email FROM users WHERE id IN ('".$partner_temp[0]."')");
							$rowp = mysql_fetch_array($queryp);
							
							$querysm = mysql_query("SELECT short_code, full_name, email_address FROM users WHERE id IN ('".$sr_manager_temp[0]."')");
							if(mysql_num_rows($querysm) > 0){
								$rowsm = mysql_fetch_array($querysm);
								$et_arr[] = $rowsm['short_code'];
							}
							
							$querym = mysql_query("SELECT short_code, full_name, email_address FROM users WHERE id IN ('".$manager_temp[0]."')");
							if(mysql_num_rows($querym) > 0){
								$rowm = mysql_fetch_array($querym);
								$et_arr[] = $rowm['short_code'];
							}
							
							// If Partner is Salman Haq, Add CC to Saira Aslam also.
							/*if($partner_temp[0] == 'ffffffb3-ecce-50b4-b094-ba14d047240b'){
								$query_saira = mysql_query("SELECT full_name, email_address FROM users WHERE id = '2d6ec43e-e687-5566-862c-e13d6561c32b'");
								$row_saira = mysql_fetch_array($query_saira);
							}*/
							
							$currency_query = mysql_query("SELECT `symbol` FROM `currencies` WHERE id = '".$currency_id."'");
							$currency_row = mysql_fetch_array($currency_query);
							
							$client_name = $this->Clients_model->getClientsNameByID($this->input->post('client'));
							$bills_date = $this->input->post('bill_date');
							$count = count($to_name);
							
							
							//----------- Code to Get Current O/s position of Client/Segment ---------------//
							
							$client_id = $this->input->post('client');
							$segment_temp = $this->input->post('segments');
							$segment_name = $this->Segments_model->getSegmentName($segment_temp[0]);
							
							$bills = $this->Bills_model->client_os_position($client_id, $segment_temp[0]);
							$result = array();
							foreach ($bills as $bill) {
								$result[$bill->client_id][] = $bill;
							}
							
							$client_os_position_html = '';
							if(sizeof($result)){
								$client_os_position_html .= "Outstanding receivable position of above client for ".$segment_name." as on ".date('d-M-Y').".";
								$client_os_position_html .= '<table cellpadding="2" cellspacing="0" width="100%" style="font-size:12px;" border="1">
																<tr>
																	<th>Location</th>
																	<th>Segment</th>
																	<th>Partner</th>
																	<th>Engagement<br>Team</th>
																	<th>Bill No</th>
																	<th>Bill Date</th>        
																	<th>Service Description</th>
																	<th align="right">Billed</th>
																	<th align="right">Partially<br>Received</th>
																	<th align="right">Outstanding</th>
																	<th>Outstanding<br>Days</th>
																	<th>Remarks</th>
																</tr>';
																//<th>Status</th>
								$total_billed_amount = 0;
								$outstanding_balance = 0;
								foreach($result as $bills){
									foreach ($bills as $row){
										
										$currency_rate = $row->currency_rate;
										
										
										
										$received = empty($row->amount_received) ? 0 : $row->amount_received;
										$remaining = empty($row->outstanding) ? $row->total_amount : $row->outstanding;
									
										if($remaining <= 0){
											continue;	
										}
										
										$currency_symbol = '';
										$query = $this->db->query("SELECT symbol FROM currencies WHERE id = '{$row->currency_id}' LIMIT 1");
										if ($query->num_rows()) {
											$rowC = $query->row();
											$currency_symbol = $rowC->symbol;
										}
										
										
										
										// Get Collector Name
										$collector_name = '';
										$query = $this->db->query("SELECT short_code FROM users WHERE id = '{$row->collector_id}' LIMIT 1");
										if ($query->num_rows()) {
											$row2 = $query->row();
											$collector_name = $row2->short_code;
										}
										
										// Get Recipet Remakrs
										$query = $this->db->query("SELECT a.remarks_other_branch, a.remarks AS receipts_remarks, b.remarks, b.bill_id FROM receipts a, receipts_data b INNER JOIN bills c ON c.id = b.bill_id WHERE a.id = b.receipt_id AND b.bill_id = '".$row->id."'");
										if ($query->num_rows()) {
											$row3 = $query->row();
											$receipts_remarks = $row3->receipts_remarks.' / '.$row3->remarks;
										} else {
											$receipts_remarks = '';
										}										
										// $total_billed_amount += $row->total_amount/$row->currency_rate;										
										$total_billed_amount += round($row->total_amount);										
																				
										$total_billed_text = number_format($row->total_amount);
										if($currency_symbol != 'Rs') 
											$total_billed_text .= '<br>[='.$currency_symbol.($row->total_amount/$row->currency_rate).']';
										
										
										// echo $total_billed_text;
										// exit();
										
										$received_text = '';
										if($received > 0){
											$received_text = number_format($received); 
											if($currency_symbol != 'Rs') 
												$received_text .= '<br>[='.$currency_symbol.($received/$row->currency_rate).']';
										}										
										// $outstanding_balance += $remaining/$row->currency_rate;
										
										
										$outstanding_balance += round($remaining);	
										// echo $outstanding_balance;
										// exit();										
										$remaining_text = number_format($remaining); 
										if($currency_symbol != 'Rs') 
											$remaining_text .= '<br>[='.$currency_symbol.($remaining/$row->currency_rate).']';										
										$date1 = $row->bill_date;
										$date2 = time();
										$datediff = $date2 - $date1;
										$days = floor($datediff / (60 * 60 * 24));
										
										$client_os = ($row->total_amount + $row->total_credit_amount) * $row->currency_rate - $row->amount_received;
										if($client_os == $row->total_amount * $row->currency_rate)	$status = 'O/S';
										if($client_os < $row->total_amount * $row->currency_rate)	$status = 'Partially Settled';
										if(number_format($client_os) == 0) 			$status = 'Settled';
										
										// For Outstanding days more than 90 or 365, Highlight.
										$row_style = '';
										$cell_style = '';
										$status_remarks = $receipts_remarks;
										if($days > 90 && $days <= 365){ 
											$cell_style = 'bgcolor="#FFFF00"';
										} 
										if($days > 365){
											$row_style = 'style="color:#F00; font-weight:bold;"';
											$cell_style = 'style="background-color:#F00; color:#FFF; font-weight:bold;"';
											$status_remarks = 'Immediate Action is required';
										}										
										$managers_arr = array();
										if($row->sr_manager_name != '') $managers_arr[] = $row->sr_manager_name;
										if($row->manager_name != '') $managers_arr[] = $row->manager_name;
										$managers_str = implode(" | ",$managers_arr);
										
										$client_os_position_html .= '<tr '.$row_style.'>
																		<td align="center">'.$row->branch_name.'</td>
																		<td align="center">'.$row->segment_name.'</td>
																		<td align="center">'.$row->partner_name.'</td>
																		<td align="center">'.$managers_str.'</td>
																		<td align="center"><a href="'.site_url("bills/simple_pdf3/{$row->id}/false").'" title="Click to Download this Bill.">'.$row->bill_no_str.'</a></td>
																		<td align="center">'.date('d-M-y', $row->bill_date).'</td>
																		<td>'.$row->remarks.'</td>
																		<td align="right">'.$total_billed_text.'</td>
																		<td align="right">'.$received_text.'</td>
																		<td align="right">'.$remaining_text.'</td>
																		<td align="center" '.$cell_style.'>'.($status != 'Settled' ? $days : '').'</td>
																		<td align="center">'.$status_remarks.'</td>
																	</tr>';	
																	//<td align="center">'.$status.'</td>
									}
								}
								$client_os_position_html .= '<tr>
																<td align="center"></td>
																<td align="center"></td>
																<td align="center"></td>
																<td align="center"></td>
																<td align="center"></td>
																<td align="center"></td>
																<td align="right"></td>
																<td align="right" style="border-top:1pt solid; border-bottom:1pt solid;"><strong>'.number_format($total_billed_amount).'</strong></td>
																<td align="center"></td>
																<td align="right" style="border-top:1pt solid; border-bottom:1pt solid;"><strong>'.number_format($outstanding_balance).'</strong></td>
																<td align="center"></td>
																<td align="center"></td>
															</tr>';
								$client_os_position_html .= '</table>';
								
								//$client_os_position_html .= '<p>Outstanding Balance is <strong>'.($currency_symbol=='Rs' ? 'PKR':$currency_symbol).' '.number_format($outstanding_balance).'.</strong></p>';
							}
							
							///----------------------------------------------------------------------------//
							// echo $outstanding_balance;
							
							
							//"<p><a href="'.site_url("bills/simple_pdf2/{$bill_id}/false").'">View Bill</a></p>"
							$download_link = site_url("bills/simple_pdf3/{$bill_id}/false");
							$email_body = 
								'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
								<html xmlns="http://www.w3.org/1999/xhtml">
								<head>
								<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
								<title>EYFRSH Billing Management System</title>
								</head>
								<body>
									<p>Dear Sir,</p>
									<p>This is to inform you that an Invoice No.<strong>'.$bill_no_str.'</strong> dated '.$bills_date.' has been generated as per details given below :</p>
									<table border="0">
										<tr>
											<td width="160" valign="top">Segment<span style="float:right">:</span></td>
											<td> '.$segment_name.'</td>
										</tr>
										
										<tr>
											<td width="160" valign="top">Service<span style="float:right">:</span></td>
											<td> '.$service1_name.'</td>
										</tr>
										<tr>
											<td width="160" valign="top">Client Name<span style="float:right">:</span></td>
											<td> '.$client_name.'</td>
										</tr>
										
										<tr>
											<td>Engagement Partner<span style="float:right">:</span></td>
											<td> '.$rowp['full_name'].'</td>
										</tr>';
				/*if($rowsm['full_name'] != ''){					
						$email_body .=	'<tr>
											<td>ED/Sr. Manager<span style="float:right">:</span></td>
											<td valign="bottom"> '.$rowsm['full_name'].'</td>
										</tr>';
				}*/
					if(count($et_arr) > 0){
						$email_body .=	'<tr>
											<td>Engagement Team<span style="float:right">:</span></td>
											<td> '.implode(', ',$et_arr).'</td>
										</tr>';
					}
										
						$email_body .=	'<tr><td colspan="2">&nbsp;</td></tr>
									 </table>
									 <table border="0">';
				if($currency_row['symbol'] != 'Rs'){
						$email_body .=' <tr>
											<td width="160">Currency<span style="float:right">:</span></td>
											<td align="right" width="80">'.$currency_row['symbol'].' @ '.$bill_currency_rate.'</td>
										</tr>';
				}
						$email_body .=' <tr>
											<td width="160">Fee<span style="float:right">:</span></td>
											<td align="right" width="80">'.number_format($total_fee).'</td>
										</tr>';
				if($total_others){
						$sst_rate_val = number_format(($total_others * 100)/$total_fee);
						$email_body .=' <tr>
											<td>SST('.$sst_rate_val.'%)<span style="float:right">:</span></td>
											<td align="right">'.number_format($total_others).'</td>
										</tr>';
				}
				if($total_oop){
						$email_body .=' <tr>
											<td>OOP<span style="float:right">:</span></td>
											<td align="right">'.number_format($total_oop).'</td>
										</tr>';
				}
				
				// echo $outstanding_balance;
								
							//echo $bill_total_amount;	
				
						$email_body .=' <tr>
											<td align="right">Total&nbsp;&nbsp;&nbsp;'.$currency_row['symbol'].' :</td>
											<td align="right" style="border-top: 1pt solid; border-bottom: 1pt solid;"><font style="font-weight:bold;">'.number_format($bill_total_amount).'</font></td>
										</tr>
									</table>
									<p>A soft copy of the above invoice (PDF version) is available on the following link for your perusal.</p>
									<p><a href="'.$download_link.'">Download PDF</a></p>  
									'.$client_os_position_html.'
									<br>
									<p>Regards,</p>
									<br>
									<p>Ernst & Young Pakistan</p>
									<p>Billing Management System</p>
									<br>
								</body>
								</html>';
								
								//echo $email_body;
								
								$to_emails = explode(',',$rowp['email_address']);
								foreach($to_emails as $tm){
									$to[] = $tm;
								}
								$cc_emails = explode(',',$rowp['cc_email']);
								foreach($cc_emails as $key => $value){
									$cc[] = $value;
								}
								
								$cc[] = $this->user['email_address']; // User who created this bill
								if($rowm['email_address'] != '') $cc[] = $rowm['email_address']; // For Manager
								if($rowsm['email_address'] != '') $cc[] = $rowsm['email_address']; // For Sr. Manager
								/*if($partner_temp[0] == 'ffffffb3-ecce-50b4-b094-ba14d047240b'){
									$cc[] = $row_saira['email_address']; // If Partner is Salman Haq, add CC to Saira Aslam
								}*/
								
								$from = $email_settings->from_email;
								if(!empty($email_settings->new_bill_bcc1)) $bcc[] = $email_settings->new_bill_bcc1;
								if(!empty($email_settings->new_bill_bcc2)) $bcc[] = $email_settings->new_bill_bcc2;
								if(!empty($email_settings->new_bill_cc1)) 	$cc[] = $email_settings->new_bill_cc1;
								if(!empty($email_settings->new_bill_cc2)) 	$cc[] = $email_settings->new_bill_cc2;
								
								$email_subject = 'Acknowledgement of Invoice No.'.$bill_no_str.' - '.$client_name.'';
								
								
								
								$emailResult = $this->Email_model->send_email($email_body, $email_subject, $from, $to, $cc, $bcc);
								//echo '<b>SUBJECT:</b><br>'.$email_subject.'<hr><b>BODY:</b><br><br>'.$email_body;
								
							//----------------------------------------//
						}
					}
										
					// For Safe side, Update Server Side Total in Bills and Bills_Data Table.
					mysql_query("UPDATE bills SET total_fees = ".$total_fee.", total_oop = ".$total_oop.", total_others = ".round($total_others).", sst_rate = ".$sst_rate.", total_amount = ".round($bill_total_amount).", email_result = '".addslashes($emailResult)."' WHERE id = '".$record['id']."'");
					
					//Save Amounts in Bill History Log
					$log_amounts = "Total Fee: ".$total_fee.", Total OOP: ".$total_oop.", SST: ".$sst_log.", Total Amount: ".round($bill_total_amount);
					
					//mysql_query("UPDATE bills_data SET others = ".round($total_others).", total = ".round($bill_total_amount)." WHERE bill_id = '".$record['id']."'");
				
				   /* $bill_post = array();
					$vars = array('allocation-segments', 'allocation-service1', 'allocation-service2', 'allocation-section', 'allocation-partner', 'allocation-manager', 'allocation-task', 'allocation-description', 'allocation-description_oop', 'allocation-description_tax', 'allocation-fee', 'allocation-oop', 'allocation-other', 'allocation-tax', 'allocation-total');
					foreach ($vars as $var) {
						$bill_post[$var] = $this->input->post($var);
					}

					$bill_data = array();
					$sno = 1;
					foreach ($bill_post['allocation-segments'] as $index => $segment) {
						// Check to eliminate empty record in database
						if ((empty($bill_post['allocation-fee'][$index]) || $bill_post['allocation-fee'][$index] == 0) && (empty($bill_post['allocation-oop'][$index]) || $bill_post['allocation-oop'][$index] == 0) && (empty($bill_post['allocation-other'][$index]) || $bill_post['allocation-other'][$index] == 0)) {
							continue;
						}

						// Allocation data to save in database
						$bill_data[] = array(
							'sno' => $sno++,
							'bill_id' => $record['id'],
							'segment_id' => $segment,
							'service1_id' => $bill_post['allocation-service1'][$index],
							'service2_id' => $bill_post['allocation-service2'][$index],
							'section_id' => $bill_post['allocation-section'][$index],
							'partner_id' => $bill_post['allocation-partner'][$index],
							'manager_id' => $bill_post['allocation-manager'][$index],
							'task_id' => $bill_post['allocation-task'][$index],
							'description' => $bill_post['allocation-description'][$index],
							'description_oop' => $bill_post['allocation-description_oop'][$index],
							'description_tax' => $bill_post['allocation-description_tax'][$index],
							'fee' => $bill_post['allocation-fee'][$index],
							'oop' => $bill_post['allocation-oop'][$index],
							'others' => $bill_post['allocation-other'][$index],
							'tax' => $bill_post['allocation-tax'][$index],
							'total' => $bill_post['allocation-total'][$index]
						);
					}

					if (sizeof($bill_data)) {
						$this->Bills_model->deleteBillAllocation($record['id']);
						$this->Bills_model->insertBillAllocation($bill_data);
					}*/

					$approvals = $this->input->post('approval-dialog-values');
					if (!empty($approvals)) {
						$approvals = explode(',', $approvals);
						$approval_data = array();
						foreach ($approvals as $approval) {
							$approval_data[] = array(
								'type' => 'bill',
								'type_id' => $record['id'],
								'user_id' => $approval,
								'status' => 'pending',
							);
						}

						if (sizeof($approval_data))
							$this->Approvals_model->insertApprovals($approval_data);
					}

					$this->Bills_model->updateBillsOtherDetail($other_data);
					
					// Unlocked the Bill - Editable Mode.
					$this->Others_model->updateEditModeStatus($record['id'], 'unlocked');
					
					if($_POST['draft_approved'] == '1'){
			
						$this->Bills_model->updateBillsLog($record['id'],'save','edit', 'Bill', $log_amounts);
						//redirect('bills/common');
						redirect("bills/edit/{$record['id']}");
					} else {
					
						if (isset($_POST['draft'])) {
							
							$this->Bills_model->updateBillsLog($record['id'],'draft', 'edit', 'Bill', $log_amounts);
							if($type == 'new'){ 
								redirect("bills/edit/{$record['id']}/new");
							} else {
								redirect("bills/edit/{$record['id']}");
							}
						} else {
							
							if($email_settings->send_bill_edit_email == 'YES'){
							//if($this->user['id'] == 'ccd4290c-ad21-50e5-9457-f0e77bac1659')
								
								$this->Bills_model->updateBillsLog($record['id'],'revised', 'edit', 'Bill', $log_amounts);
								$client_data = $this->Bills_model->getBillToByBillID($bill_id);
								$user_branch = $this->Branches_model->getBranchByID($this->user['branch_id']);			
								$email_body = 
									'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
									<html xmlns="http://www.w3.org/1999/xhtml">
									<head>
									<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
									<title>EYFRSH Billing Management System</title>
									</head>
									<body>
										<p>Dear Admin,</p>
										<p>A Bill has been updated, details are given below:</p>
										<table border="0">
											<tr>
												<td>Bill#<span style="float:right">:</span></td>
												<td> '.$record['bill_no_str'].'</td>
											</tr>
											<tr>
												<td width="160" valign="top">Bill URL<span style="float:right">:</span></td>
												<td> <a href="'.site_url("bills/edit/{$bill_id}").'">View Bill</a></td>
											</tr>
											<tr>
												<td width="160" valign="top">Client<span style="float:right">:</span></td>
												<td> '.$client_data->client_name.'</td>
											</tr>
											<tr>
												<td>User<span style="float:right">:</span></td>
												<td> '.$this->user['full_name'].'</td>
											</tr>
											<tr>
												<td>IP<span style="float:right">:</span></td>
												<td> '.$_SERVER['REMOTE_ADDR'].'</td>
											</tr>
											<tr>
												<td>Branch<span style="float:right">:</span></td>
												<td> '.$user_branch->name.'</td>
											</tr>
											<tr><td colspan="2">&nbsp;</td></tr>
										 </table>
										 <p>Please take necessary action.</p>
										 <p>EYFRSH Billing Management System</p>
										 <br>
									</body>
									</html>';
									$email_subject = 'Bill Updated - '.$record['bill_no_str'].'';
									
									$from = $email_settings->from_email;
									if(!empty($email_settings->edit_bill_to1)) $to[] = $email_settings->edit_bill_to1;
									if(!empty($email_settings->edit_bill_to2)) $to[] = $email_settings->edit_bill_to2;
									//$to[] = 'Niaz.Ahmed@pk.ey.com';
									
									$this->Email_model->send_email($email_body, $email_subject, $from, $to, $cc, $bcc);
									
							}
							redirect('bills/common');
						}
					}
				}
			
			}
		}
		
		// If BILL is in LOCKED mode. User can't open it.
		if($editable == 'locked'){
		
			$userInfo = $this->Users_model->getUserByID($edit_mode[1]);
			$data = array(
				'page' => 'bills/locked',
				'user_name' => $userInfo->full_name,
				'bill_id' => $bill_id
			);	
			$this->load->view('layout/v2/default', $data);
			
		}
		else {
			
			// Generating data for segments
			$all_segments = array();
			$segments = $this->Segments_model->getAllSegments();
			foreach ($segments as $segment) {
				if (empty($segment->parent_id) || $segment->parent_id == "0") {
					$all_segments[$segment->id]['parent'] = array(
						'id' => $segment->id,
						'parent_id' => $segment->parent_id,
						'name' => $segment->name,
					);
				} else {
					$all_segments[$segment->parent_id]['childs'][] = array(
						'id' => $segment->id,
						'parent_id' => $segment->parent_id,
						'name' => str_replace("'", '', $segment->name),
					);
				}
			}
	
			// Generating data for sections in segment
			$sections = $this->Segments_model->getAllSections();
			foreach ($sections as $section) {
				if (empty($section->segment_id) || $section->segment_id == "0") {
					// Do nothing
				} else {
					$all_segments[$section->segment_id]['segments'][] = array(
						'id' => $section->id,
						'parent_id' => $section->segment_id,
						'name' => str_replace("'", '', $section->name),
					);
				}
			}
	
			$partners = $this->Users_model->getUserByRoleName('Partner', array("users.segment_id != ''"));
			foreach ($partners as $partner) {
				if (empty($partner->segment_id) || $partner->segment_id == "0") {
					// Do nothing
				} else {
					$all_segments[$partner->segment_id]['partners'][] = array(
						'id' => $partner->id,
						'parent_id' => $partner->segment_id,
						'name' => str_replace("'", '', $partner->full_name),
					);
				}
			}
	
			$managers = $this->Users_model->getUserByRoleName('Manager', array("users.segment_id != ''"));
			foreach ($managers as $manager) {
				if (empty($manager->segment_id) || $manager->segment_id == "0") {
					// Do nothing
				} else {
					$all_segments[$manager->segment_id]['managers'][] = array(
						'id' => $manager->id,
						'parent_id' => $manager->segment_id,
						'name' => str_replace("'", '', $manager->full_name),
					);
				}
			}
			
			$sr_managers = $this->Users_model->getUserByRoleName('SM_ED', array("users.segment_id != ''"));
			foreach ($sr_managers as $sr_manager) {
				if (empty($sr_manager->segment_id) || $sr_manager->segment_id == "0") {
					// Do nothing
				} else {
					$all_segments[$sr_manager->segment_id]['sr_managers'][] = array(
						'id' => $sr_manager->id,
						'parent_id' => $sr_manager->segment_id,
						'name' => str_replace("'", '', $sr_manager->full_name),
					);
				}
			}
	
			$accounting_period = array(
				'start' => explode('-', date('Y-n-j', $this->variables['accounting_period_start'])),
				'end' => explode('-', date('Y-n-j', $this->variables['accounting_period_end'])),
			);
			
			
			if($type == 'new') $page = 'bills/v2/editp'; 
			else $page = 'bills/v2/edit2';
	
			$bill = $this->Bills_model->getBillByID($bill_id, true);
			$data = array(
				'page' => $page,
				'bill' => $bill,
				'clients' => $this->Clients_model->getClientsByBranchID($this->variables['branch'],false,false,1),
				'currencies' => $this->Currencies_model->getAllCurrency(),
				'remitting_banks' => $this->Bills_model->getRemittingBanks(),
				'bill_history' => $this->Bills_model->getBillHistory($bill_id),
				'segments' => $this->Segments_model->getAllSegments(0),
				'all_segments' => json_encode($all_segments),
				'partners' => $this->Users_model->getUserByRoleName('Partner'),
				'managers' => $this->Users_model->getUserByRoleName('Manager'),
				'sr_managers' => $this->Users_model->getUserByRoleName('SM_ED'),
				'collectors' => $this->Users_model->getUserByRoleName('Collector'),
				'users' => $this->Users_model->getUsers(),
				'projects' => $this->Projects_model->getProjectsByClientID($bill->client_id),
				'projects_files' => $this->Projects_model->getProjectFilesData($bill->project_id),
				'countries' => $this->Countries_model->getAllCountry(),
				'branches' => $this->Branches_model->getAllBranch(),
				'accounting_period' => json_encode($accounting_period),
				'setting' => $email_settings,
			);
	
			$this->load->view('layout/v2/default', $data);
		}
		
    }

    public function pdf($bill_id = 0, $header = true) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set auto page breaks
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetAutoPageBreak(TRUE, 0);
		
		$style = '<style>
					.main_text{
						color:#424242;
					}
					</style>';
		
        // set font
        //$pdf->SetFont('dejavusans', '', 8);
		$pdf->SetFontSize(11);
		
        // add a page
        $pdf->AddPage();

        $zeros = array('0000', '000', '00', '0', '');
        $bill = $this->Bills_model->getBillByID($bill_id);
		
		$header_data = $style;
		
        $header_data .= $header === "false" ?
                '<br />
                  <strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br /><br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;
                  </span>' :
                '<br />
                  <strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br />
                  </span>';

        if (empty($bill->attention_line_1) && empty($bill->attention_line_1)) {
            $attention_text = '<br /><br /><br />';
        } else {		
            $attention_text = '<br /><br />
                <strong class="main_text">Attention: &nbsp;&nbsp;
                          ' . $bill->attention_line_1 . '<br />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <u>' . $bill->attention_line_2 . '</u>
                 </strong>';
        }
        $html = '
            <table width="750">
              <tr>
                <td>&nbsp;</td>
                <td width="165" align="left">
                  ' . $header_data . '
                      <br />
                </td>
              </tr>
              <tr>
				<td>&nbsp;</td>
                <td>
                  <table>
                    <tr>
                      <td width="35" class="main_text">' . ($header === "false" ? '&nbsp;' : '') . '</td>
                      <td>' . $zeros[strlen($bill->bill_no)] . $bill->bill_no . '/' . date('y') . '</td>
                    </tr>
                    <tr>
                      <td class="main_text">' . ($header === "false" ? '&nbsp;' : '') . '</td>
                      <td>' . date('d-M-Y', $bill->bill_date) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr> 
			  <td width="13">&nbsp;</td>
                <td width="530">
                  <table>
                    <tr>
                      <td>' . (!empty($bill->bill_to->client_name) ? $bill->bill_to->client_name . '<br />' : '') . '
                        ' . (!empty($bill->billto_address) ? nl2br($bill->billto_address) : '') . '
                        ' . $attention_text . '
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
			  <tr>
                <td colspan="2" height="100">&nbsp;
                  
                </td>
              </tr>
              <tr>
				<td width="13">&nbsp;</td>
                <td>
                  <table cellspacing="2" cellpadding="4" width="510">
                    <tr>
                      <td colspan="3">&nbsp;</td>
                    </tr>';
		$border_top = '';
			  $html .= '
					<tr>
					  <td colspan="2" width="415" style="margin-left: 25px;"></td>
					  <td valign="bottom" width="75">' . $bill->currency_title . '</td>
					</tr>';
        $total_amount = 0;
        $i1 = 0; 
        foreach ($bill->bill_data as $index => $bill_data_row) {
            if (!empty($bill_data_row->description) && $bill_data_row->fee > 0) {
               // $border_top = $i1 <= 0 && false ? 'border-top: 1px solid #000;' : '';
                $html .= '
                    <tr>
                      <td colspan="2" style="margin-left: 25px;">' . $bill_data_row->description . '</td>
                      <td align="right" style="vertical-align:bottom">' . number_format($bill_data_row->fee) . '</td>
                    </tr>';
                $total_amount = $total_amount + $bill_data_row->fee;
            }
			
			if (!empty($bill_data_row->description_tax) && $bill_data_row->others > 0) {
               // $border_top = $i1 <= 0 && false ? 'border-top: 1px solid #000;' : '';
                $html .= '
                    <tr>
                      <td colspan="2" style="margin-left: 25px;">' . $bill_data_row->description_tax . '</td>
                      <td align="right" style="vertical-align:bottom">' . number_format($bill_data_row->others) . '</td>
                    </tr>';
                $total_amount = $total_amount + $bill_data_row->others;
            }

            if (!empty($bill_data_row->description_oop) && $bill_data_row->oop > 0) {
                //$border_top = $i1 <= 0 && false ? 'border-top: 1px solid #000;' : '';
                $html .= '
                    <tr>
                      <td colspan="2" style="margin-left: 25px;">' . $bill_data_row->description_oop . '</td>
                      <td valign="bottom" align="right">' . number_format($bill_data_row->oop) . '</td>
                    </tr>';
                $total_amount = $total_amount + $bill_data_row->oop;
            }
        }
				
				$words = $bill->currency_title . ' ' . $this->_convert_number_to_words(str_replace(',', '', $total_amount));
				$html .= '
					<tr>
					  <td colspan="2" style="margin-left: 25px;">(' . strtoupper($words) . ')</td>
					  <td valign="bottom" align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . number_format($total_amount) . '</td>
					</tr>';

				$html .= '
					<tr>
					  <td colspan="2" style="margin-left: 25px;">' . nl2br ($bill->special_instructions) . '</td>
					  <td valign="bottom" align="right"></td>
					</tr>';

        $html .= '
                  </table>
                </td>
              </tr>
            ';

        if ($header === "false") {
            // do something
        } else {
            $base_path = str_replace('system/', '', BASEPATH);
            //$pdf->Image($base_path . 'assets/v2/img/pdf/ey_new_logo2.jpg', 15, 0, 120, 30, 'JPG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 0, false, false, false);
			$pdf->Image($base_path . 'assets/v2/img/pdf/ey_background2.png', 0, 0, 217, 297, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
        }

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        //Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');
    }
	
	public function pdf_download($bill_id = 0, $header = true, $pdf_action = 'D') {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set font
        //$pdf->SetFont('dejavusans', '', 8);
		$pdf->SetFontSize(11);
		
        // add a page
        $pdf->AddPage();

        $zeros = array('0000', '000', '00', '0', '');
        $bill = $this->Bills_model->getBillByID($bill_id);

        $header_data =
                '<strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br /><br />
					&nbsp;<br />
                  </span>';

        if (empty($bill->attention_line_1) && empty($bill->attention_line_1)) {
            $attention_text = '<br /><br />';
        } else {
            $attention_text = '
                <br /><br />
                <strong>Attention: &nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_1 . '<br />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_2 . '
                 </strong>';
        }
		
        $html = '
            <table width="750">
              <tr>
                <td>&nbsp;</td>
                <td width="165">
                  ' . $header_data . '
                </td>
              </tr>
              <tr>
                <td style="padding: 0px;">
                <table>
                    <tr>
                      <td width="415">&nbsp;</td>
                      <td>' . $zeros[strlen($bill->bill_no)] . $bill->bill_no . '/' . date('y') . '</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>' . date('d-M-Y', $bill->bill_date) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="750">
                    <tr>
                      <td width="1"></td>
                      <td width="400" height="150">
                        <br />
                        ' . (!empty($bill->client_name) ? $bill->client_name . '<br />' : '') . '
                        ' . (!empty($bill->billto_address) ? nl2br($bill->billto_address) : '') . '
                        ' . $attention_text . '
                      </td>
                      <td>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table>
                    <tr>
                      <td>
                        &nbsp;<br />
                        &nbsp;
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table>
                    <tr>
                      <td width="390"></td>
                      <td>
                        <br />
                        <span style="font-size: 16px">&nbsp;</span><br />
                        <!--xxxxx-->
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2" width="530">
                  <table cellspacing="2" cellpadding="4">
                    <tr>
                      <td colspan="3" style=""><br /><br /><br /><br />'. (!empty($attention_text) ? '' : '<br /><br /><br />') .'</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            ';


         if ($header === "false") {
            // do something
			$base_path = str_replace('system/', '', BASEPATH);
            $pdf->Image($base_path . 'assets/v2/img/pdf/ey_background2.png', 0, 0, 217, 297, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
        } else {
            $base_path = str_replace('system/', '', BASEPATH);
            $pdf->Image($base_path . 'assets/v2/img/pdf/ey_background2.png', 0, 0, 217, 297, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
        }
		
		 // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->setY($pdf->getY() - 15);
        // Setting up currency title
        $this->__addCells($pdf, '', $bill->currency_title, 'L', 'R', -1);

        // Setting up bills data
        $total_amount = 0;
        $i = 1;
        foreach ($bill->bill_data as $bill_data_row) {
            if (!empty($bill_data_row->description) && !($bill_data_row->fee == 0)) {
                $this->__addCells($pdf, $bill_data_row->description, number_format($bill_data_row->fee), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->fee;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }

            /*if (!empty($bill_data_row->description_tax) && !($bill_data_row->others == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_tax, number_format($bill_data_row->others), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->others;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }
			
			if (!empty($bill_data_row->description_oop) && !($bill_data_row->oop == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->oop;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }*/
        }
		
		foreach ($bill->bill_data as $bill_data_row) {
           /* if (!empty($bill_data_row->description) && !($bill_data_row->fee == 0)) {
                $this->__addCells($pdf, $bill_data_row->description, number_format($bill_data_row->fee), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->fee;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }*/

            if (!empty($bill_data_row->description_tax) && !($bill_data_row->others == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_tax, number_format($bill_data_row->others), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->others;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }
			
			if (!empty($bill_data_row->description_oop) && !($bill_data_row->oop == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->oop;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }
        }

        // Line before total amount
        $lineY = $pdf->getY() - 1;
        $pdf->Line(183, $lineY, 207, $lineY);

        // Total amount
        $words = $bill->currency_title . ' ' . $this->_convert_number_to_words(str_replace(',', '', $total_amount)) . ' ONLY';
        $words = str_replace(',', '', $words);
        $pdf->getY($pdf->getY() + 15);
        $this->__addCells($pdf, '(' . strtoupper($words) . ')', number_format($total_amount), 'L', 'R');

        // Line after total amount
        $lineY = $pdf->getY() - 1;
        $pdf->Line(183, $lineY, 207, $lineY);
        $pdf->Line(183, $lineY + 0.6, 207, $lineY + 0.6);

        // Setting up currency title
        if (!empty($bill->special_instructions) || $bill->special_instructions > 0){
            $bill->special_instructions = nl2br ($bill->special_instructions);
            $this->__addCells($pdf, $bill->special_instructions, '', 'L', 'R');
		}

		$file_name = $bill->bill_no.'.pdf';

        //Close and output PDF document
		if($pdf_action == 'S'){
			return $pdf->Output($file_name, $pdf_action);
		} else {
			$pdf->Output($file_name, $pdf_action);
		}
    }

    public function simple_pdf($bill_id = 0, $header = true) {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        //$pdf->SetMargins(0, 0, 0, false);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set font
        //$pdf->SetFont('dejavusans', '', 8);
        // add a page
        $pdf->AddPage();

        $zeros = array('0000', '000', '00', '0', '');
        $bill = $this->Bills_model->getBillByID($bill_id);

        $header_data =
                '<br />
                  <strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br /><br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;
                  </span>';

        if (empty($bill->attention_line_1) && empty($bill->attention_line_1)) {
            $attention_text = '';
        } else {
            $attention_text = '
                <strong>Attention: &nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_1 . '<br />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          <u>' . $bill->attention_line_2 . '</u>
                 </strong>';
        }
        $html = '
            <table width="750">
              <tr>
                <td>&nbsp;</td>
                <td width="165">
                  ' . $header_data . '
                </td>
              </tr>
              <tr>
                <td><table>
                    <tr>
                      <td width="30">' . ($header === "false" ? '&nbsp;' : 'Bill No:') . '</td>
                      <td>' . $zeros[strlen($bill->bill_no)] . $bill->bill_no . '/' . date('y') . '</td>
                    </tr>
                    <tr>
                      <td>' . ($header === "false" ? '&nbsp;' : 'Date:') . '</td>
                      <td>' . date('d-M-Y', $bill->bill_date) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="750">
                    <tr>
                      <td width="1"></td>
                      <td>
                        <br /><br />
                        ' . (!empty($bill->bill_to->client_name) ? $bill->bill_to->client_name . '<br />' : '') . '
                        ' . (!empty($bill->client_address->address) ? $bill->client_address->address . '<br />' : '') . '
                        ' . (!empty($bill->client_address->address2) ? $bill->client_address->address2 . '<br />' : '') . '
                        ' . (!empty($bill->client_address->city) ? $bill->client_address->city . '<br />' : '') . '
                        ' . $attention_text . '
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table>
                    <tr>
                      <td>
                        <br /><br /><br />
                        ' . ($header === "false" ? '&nbsp;' : 'Dear Sir (s)') . '<br />
                        ' . ($header === "false" ? '&nbsp;' : 'Our charges for professional services rendered are details below, for which we sall be glad to receive remittance.') . '
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table>
                    <tr>
                      <td width="390"></td>
                      <td>
                        <br />
                        <span style="font-size: 16px">' . ($header === "false" ? '&nbsp;' : 'Yours faithfully') . '</span><br />
                        <!--xxxxx-->
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2" width="530">
                  <table cellspacing="2" cellpadding="4">
                    <tr>
                      <td colspan="3" style=""><br /><br /><br /><br /><br /><br /><br /><br /><br /></td>
                    </tr>';

        $html .= '
            <tr>
              <td width="10"></td>
              <td width="465"></td>
              <td width="100" valign="bottom">' . $bill->currency_title . '</td>
            </tr>';
        $total_amount = 0;
        $i1 = 0;
        foreach ($bill->bill_data as $index => $bill_data_row) {
            if (!empty($bill_data_row->description)) {
                $border_top = '';
                $html .= '
                    <tr>
                      <td width="10"></td>
                      <td width="465">' . $bill_data_row->description . '</td>
                      <td width="50" valign="bottom">' . number_format($bill_data_row->fee) . '</td>
                    </tr>';
                $total_amount = $total_amount + $bill_data_row->fee;
            }

            if (!empty($bill_data_row->description_oop)) {
                $border_top = '';
                $html .= '
                    <tr>
                      <td width="10"></td>
                      <td width="465">' . $bill_data_row->description_oop . '</td>
                      <td width="50" valign="bottom">' . number_format($bill_data_row->oop) . '</td>
                    </tr>';
                $total_amount = $total_amount + $bill_data_row->oop;
            }
        }

        $html .= '
            <tr>
              <td width="10"></td>
              <td width="465">&nbsp;</td>
              <td width="50" valign="bottom">' . number_format($total_amount) . '</td>
            </tr>';

        $words = $bill->currency_title . ' ' . $this->_convert_number_to_words(str_replace(',', '', $total_amount));
        $html .= '
            <tr>
              <td width="10"></td>
              <td width="465">(' . strtoupper($words) . ')</td>
              <td width="50" valign="bottom">&nbsp;</td>
            </tr>';

        $html .= '
            <tr>
              <td width="10"></td>
              <td width="460">' . $bill->special_instructions . '</td>
              <td width="50" valign="bottom"></td>
            </tr>';

        $html .= '
                  </table>
                </td>
              </tr>
            </table>
            ';

        if ($header === "false") {
            // do something
        } else {
            $base_path = str_replace('system/', '', BASEPATH);
            $pdf->Image($base_path . 'assets/v2/img/pdf/ltbeam-header-wide.png', 0, 0, 350, 45, 'PNG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 0, false, false, false);
            $pdf->Image($base_path . 'assets/v2/img/pdf/logo.gif', 70, 11, 45, 7, 'GIF', 'http://www.tcpdf.org', '', true, 150, '', false, false, 0, false, false, false);
        }

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        //Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');
    }

    public function simple_pdf2($bill_id = 0, $header = true) {
       //echo PDF_MARGIN_BOTTOM; exit;

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        //$pdf->SetMargins(0, 0, 0, false);
        // set auto page breaks

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        // set font
        //$pdf->SetFont('dejavusans', '', 10);
		$pdf->SetFontSize(10);
		
        // add a page
        $pdf->AddPage();

        $zeros = array('0000', '000', '00', '0', '');
        $bill = $this->Bills_model->getBillByID($bill_id);

        $header_data =
                '
                  <strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br /><br />
					<br /><br />
                  </span>';

        if (empty($bill->attention_line_1) && empty($bill->attention_line_1)) {
            $attention_text = '<br /><br />';
        } else {
            $attention_text = '
                <br /><br /><br />
                <strong>Attention: &nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_1 . '<br />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_2 . '
                 </strong>';
        }
		
		/*<td width="435">' . ($header === "false" ? '&nbsp;' : 'Bill No:') . '</td>
		<td>' . ($header === "false" ? '&nbsp;' : 'Date:') . '</td>*/
		
		$segment_html = '';
		
		if(($bill->page2_enable == 1) && ($_POST['pdf_type']=='Copy' || $_POST['pdf_type']=='Print Copy')) {
			
			foreach ($bill->bill_data as $bill_data_row) {
				$bill_segment = $bill_data_row->segment;		$bill_detail_row['segment'] = $bill_data_row->segment;
				$bill_service1 = $bill_data_row->service1;		$bill_detail_row['service1'] = $bill_data_row->service1;
				$bill_service2 = $bill_data_row->service2;		$bill_detail_row['service2'] = $bill_data_row->service2;
				$bill_partner = $bill_data_row->partner;		$bill_detail_row['partner'] = $bill_data_row->partner;
				$bill_sr_manager = $bill_data_row->sr_manager;	
				$bill_manager = $bill_data_row->manager;		
				
				if($bill_data_row->sr_manager!='' && $bill_data_row->sr_manager!=NULL){
					$et_arr[] = $bill_data_row->sr_manager;
				}
				if($bill_data_row->manager!='' && $bill_data_row->manager!=NULL){
					$et_arr[] = $bill_data_row->manager;
				}
				
				$bill_detail_row['sm_ed'] = implode(', ',$et_arr);
			}
			
			$segment_html = '<p></p>
								<table border="0" width="100%">
									<tr><td width="15%">Segment:</td><td>'.$bill_segment.'</td></tr>
									<tr><td>Service 1:</td><td>'.$bill_service1.'</td></tr>';

			if($bill_service2 != ''){
				$segment_html .= '<tr><td>Service 2:</td><td>'.$bill_service2.'</td></tr>';
			}
			$segment_html .= '<tr><td>Partner:</td><td>'.$bill_partner.'</td></tr>';
			if($bill_sr_manager != ''){
				$segment_html .= '<tr><td>ED / SM:</td><td>'.$bill_sr_manager.'</td></tr>';
			}
			if($bill_manager != ''){
				$segment_html .= '<tr><td>Manager:</td><td>'.$bill_manager.'</td></tr>';
			}
			
			/*$segment_html = '<p></p>
								<table border="0" width="100%">
									<tr><td width="15%">Segment:</td><td>'.$bill_segment.'</td></tr>
									<tr><td>Service 1:</td><td>'.$bill_service1.'</td></tr>
									<tr><td>Service 2:</td><td>'.$bill_service2.'</td></tr>
									<tr><td>Partner:</td><td>'.$bill_partner.'</td></tr>
									<tr><td>Manager:</td><td>'.$bill_manager.'</td></tr>
								</table>';*/
								
			$segment_html .= '</table>';
		} 
		
		if ($_POST['pdf_type']=='Copy' || $_POST['pdf_type']=='Print Copy'){
			$client_name = $bill->client_name;
		} else {
			$client_array = explode('|',$bill->client_name);
			$client_name = $client_array[0];
		}
		
        $html = 
			'<table border="0">
				<tr>
					<td colspan="2">' . $header_data . '</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td>
						<table border="0">
							<tr>
								<td width="160" height="10">&nbsp;</td>
								<td>' . $bill->bill_no_str . '</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>' . date('d-M-Y', $bill->bill_date) . '</td>
							</tr>';
			
			$blank_heighht = 130; // Main Height Margin - Karachi
			$client_ntn = '';
			if($this->user['branch_id'] == 'fffffff7-efd8-59ef-bb91-926565e01b1a'){
				$client_ntn = (!empty($bill->ntn) ? '<br />NTN: '.$bill->ntn : '');
				 $html .= ' <tr>
							  <td height="17">&nbsp;</td>
							  <td>&nbsp;</td>
							</tr>
							<tr>
							  <td align="right">
							  	PNTN:
							  </td>
							  <td>1452191-1</td>
							</tr>';
				$blank_heighht = 105; // Main Height Margin - Lahore
			}
			
			if($_POST['pdf_type'] == 'Org2'){
				$client_name = '<br><br>';
				$billto_address = '<br><br>';
			} else {
				$billto_address = nl2br($bill->billto_address);
			}
				$html .= '</table>
					</td>
				</tr>
				<tr>
					<td>
						<table border="0">
							<tr>
								<td width="25">&nbsp;</td>
								<td width="410" height="140">
									' . (!empty($bill->client_name) ? $client_name . '<br />' : '') . '
									' . (!empty($bill->billto_address) ? $billto_address : '') . '
									' . $client_ntn . '
									' . $attention_text . '
								</td>
							</tr>
						</table>
					</td>
					<td>'.$segment_html.'</td>
				</tr>
				<tr>
					<td colspan="2" height="'.$blank_heighht.'">&nbsp;</td>
				</tr>
			</table>';
		
		// ----------------- For Single PAGE print TCPDF Standard Version -----------------//
		if($bill->page2_enable == 0){

			if ($header === "false") {
				// do something
			} else {
				$base_path = str_replace('system/', '', BASEPATH);
				$pdf->Image($base_path . 'assets/v2/img/pdf/ey_new_logo.jpg', 20, 0, 30, 30, 'JPG', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
				//$pdf->Image($base_path . 'assets/v2/img/pdf/logo.gif', 70, 11, 45, 7, 'GIF', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
			}

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->setY($pdf->getY() - 5);
			// Setting up currency title
			$this->__addCells2($pdf, '', $bill->currency_title, 'L', 'R', -1);

			// Setting up bills data
			$total_amount = 0;
			$i = 1;
			
			$bill_detail_row = array();
			$et_arr = array();
			foreach ($bill->bill_data as $bill_data_row) {
				$bill_detail_row['segment'] = $bill_data_row->segment;
				$bill_detail_row['service1'] = $bill_data_row->service1;
				$bill_detail_row['service2'] = $bill_data_row->service2;
				$bill_detail_row['partner'] = $bill_data_row->partner;
				if($bill_data_row->sr_manager!='' && $bill_data_row->sr_manager!=NULL){
					$et_arr[] = $bill_data_row->sr_manager;
				}
				if($bill_data_row->manager!='' && $bill_data_row->manager!=NULL){
					$et_arr[] = $bill_data_row->manager;
				}
				
				$bill_detail_row['sm_ed'] = implode(', ',$et_arr);
			}
			
			foreach ($bill->bill_data as $bill_data_row) {
				
				if ($bill_data_row->fee != 0) {
					if(empty($bill_data_row->description)){
						echo '<h3>We are sorry, PDF cannot be generated due to some corrections in the Bill.<br>Please contact Administrator.</h3>';
						die;
					}
					$this->__addCells2($pdf, $bill_data_row->description, number_format($bill_data_row->fee,0), 'L', 'R', $i); // Open Decimal Places
					$total_amount = $total_amount + $bill_data_row->fee;

					if ($i == 1)
						$this->__addSegments($pdf, $bill_detail_row);

					$i++;
				}
			}
			
			foreach ($bill->bill_data as $bill_data_row) {
				if (!empty($bill_data_row->description_tax) && !($bill_data_row->others == 0)) {
					$this->__addCells2($pdf, $bill_data_row->description_tax, number_format($bill_data_row->others, 0), 'L', 'R', $i); // Decimal Places
					$total_amount = $total_amount + $bill_data_row->others;

					if ($i == 1)
						$this->__addSegments($pdf, $bill_detail_row);

					$i++;
				}
				
				if (!empty($bill_data_row->description_oop) && !($bill_data_row->oop == 0)) {
					$this->__addCells2($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop,0), 'L', 'R', $i); // Decimal Places
					$total_amount = $total_amount + $bill_data_row->oop;

					if ($i == 1)
						$this->__addSegments($pdf, $bill_detail_row);

					$i++;
				}
			}
			
			if($total_amount != $bill->total_amount){
				echo '<h3>We are sorry, PDF cannot be generated due to some corrections in the Bill.<br>Please contact Administrator.</h3>';
				die;
			}

			// Line before total amount
			$lineY = $pdf->getY() - 1;
			$pdf->Line(176, $lineY, 202, $lineY);

			// Total amount
			$words = $this->_convert_number_to_words(str_replace(',', '', number_format($bill->total_amount,0))) . ' ONLY';
			$words = str_replace(',', '', strtolower($words));
						
			$pdf->getY($pdf->getY() + 15);
			$this->__addCells2($pdf, '(' .$bill->currency_title . ' ' .  ucwords($words) . ')', number_format($bill->total_amount,0), 'L', 'R'); // Decimal Places
			
			// Line after total amount
			$lineY = $pdf->getY() - 1;
			$pdf->Line(176, $lineY, 202, $lineY);
			$pdf->Line(176, $lineY + 0.6, 202, $lineY + 0.6);
						
			// Setting up currency title
			if (!empty($bill->special_instructions) || $bill->special_instructions > 0){
				$bill->special_instructions = nl2br ($bill->special_instructions);
				$pdf->SetFontSize(8);
				$this->__addCells2($pdf, $bill->special_instructions, '', 'L', 'R');
			}
			
			// Show Bottom Note
			if (!empty($bill->note)){
				$pdf->SetFontSize(8);
				$this->__addCells_memo($pdf, $bill->note, '', 'L', 'R');
			}
		
		// ---------------------------- For Multiple PAGES, Print Custom HTML Version ------------------------//
		} else {
		
			$html .= '<table width="750">
					<tr style="line-height:2px">
		              <td width="45">&nbsp;</td>
					  <td colspan="1" width="410"></td>
		              <td width="30">&nbsp;</td>
					  <td valign="bottom" align="right" width="65">' . $bill->currency_title . '</td>
					</tr>';
			$total_amount = 0;
			
			$i = 0; 
			foreach ($bill->bill_data as $index => $bill_data_row) {
				if (!empty($bill_data_row->description) && $bill_data_row->fee > 0) {
					// Due to possible Big description in First Fee Row, Amount is given in next row because of V-Align bottom not working.
					/*if($i1 == 0){
						$html .= '
							<tr>
							  <td>&nbsp;</td>
							  <td colspan="1">' . $bill_data_row->description . '</td>
							  <td>&nbsp;</td>
							  <td align="right">&nbsp;</td>
							</tr>
							<tr style="line-height:2px">
							  <td>&nbsp;</td>
							  <td colspan="1">&nbsp;</td>
							  <td>&nbsp;</td>
							  <td align="right">' . number_format($bill_data_row->fee) . '</td>
							</tr>';
					} else {*/
						$html .= '
							<tr>
							  <td>&nbsp;</td>
							  <td colspan="1">' . $bill_data_row->description . '</td>
							  <td>&nbsp;</td>
							  <td valign="bottom">' . number_format($bill_data_row->fee) . '</td>
							</tr>';
					//}
					$total_amount = $total_amount + $bill_data_row->fee;
				}
				$i++;
			}
			
			$i = 0; 
			foreach ($bill->bill_data as $index => $bill_data_row) {			
				if (!empty($bill_data_row->description_tax) && $bill_data_row->others > 0) {
					$html .= '
						<tr style="line-height:2px">
											  <td>&nbsp;</td>

						  <td colspan="1" >' . $bill_data_row->description_tax . '</td>
						  <td>&nbsp;</td>
						  <td align="right" style="vertical-align:bottom">' . number_format($bill_data_row->others) . '</td>
						</tr>';
					$total_amount = $total_amount + $bill_data_row->others;
				}

				if (!empty($bill_data_row->description_oop) && $bill_data_row->oop > 0) {
					$html .= '
						<tr style="line-height:2px">
											  <td>&nbsp;</td>

						  <td colspan="1" >' . $bill_data_row->description_oop . '</td>
						  <td>&nbsp;</td>
						  <td valign="bottom" align="right">' . number_format($bill_data_row->oop) . '</td>
						</tr>';
					$total_amount = $total_amount + $bill_data_row->oop;
				}
				$i++;
			}
				
			$words = $bill->currency_title . ' ' . $this->_convert_number_to_words(str_replace(',', '', $bill->total_amount));
			$html .= '
				<tr style="line-height:2px">
									  <td>&nbsp;</td>

				  <td colspan="1" >(' . strtoupper($words) . ')</td>
				  <td>&nbsp;</td>
				  <td valign="bottom" align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . number_format($bill->total_amount) . '</td>
				</tr>';
			
			if(!empty($bill->special_instructions)){
				$html .= '
					<tr>
					  <td colspan="4">&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>' . nl2br ($bill->special_instructions) . '</td>
					  <td>&nbsp;</td>
					  <td valign="bottom" align="right"></td>
					</tr>';
			}
			
			if(!empty($bill->note)){
				$html .= '
					<tr>
					  <td colspan="4">&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>' . $bill->note . '</td>
					  <td>&nbsp;</td>
					  <td valign="bottom" align="right"></td>
					</tr>';

			}
			
			$html .= '</table>';

			if ($header === "false") {
				// do something
			} else {
				$base_path = str_replace('system/', '', BASEPATH);
				$pdf->Image($base_path . 'assets/v2/img/pdf/ey_new_logo.jpg', 20, 0, 30, 30, 'JPG', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
				//$pdf->Image($base_path . 'assets/v2/img/pdf/logo.gif', 70, 11, 45, 7, 'GIF', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
			}
			

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');
		
		}
//        echo htmlentities($bill->special_instructions);
//        exit;
        //Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');
    }

	
	///// ---------------- Memo PDF ------------------ ////
	public function memo_pdf($bill_id = 0, $header = true) {
       //echo PDF_MARGIN_BOTTOM; exit;

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        //$pdf->SetMargins(0, 0, 0, false);
        // set auto page breaks

		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


        // set font
        //$pdf->SetFont('dejavusans', '', 10);
		$pdf->SetFontSize(10);
		
        // add a page
        $pdf->AddPage();

        $zeros = array('0000', '000', '00', '0', '');
        $bill = $this->Bills_model->getBillByID($bill_id);

        $header_data =
                '
                  <strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br /><br />
                    &nbsp;<br /><br />
                  </span>';

        if (empty($bill->attention_line_1) && empty($bill->attention_line_1)) {
            $attention_text = '<br /><br />';
        } else {
            $attention_text = '
                <br /><br /><br />
                <strong>Attention: &nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_1 . '<br />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_2 . '
                 </strong>';
        }
		
		/*<td width="435">' . ($header === "false" ? '&nbsp;' : 'Bill No:') . '</td>
		<td>' . ($header === "false" ? '&nbsp;' : 'Date:') . '</td>*/
		
		$segment_html = '';
		
		if(($bill->page2_enable == 1) && ($_POST['pdf_type'] == 'Copy')) {
		
			foreach ($bill->bill_data as $bill_data_row) {
				$bill_segment = $bill_data_row->segment;
				$bill_service1 = $bill_data_row->service1;
				$bill_service2 = $bill_data_row->service2;
				$bill_partner = $bill_data_row->partner;
				$bill_manager = $bill_data_row->manager;
			}
			$segment_html = '<p></p>
								<table border="0" width="100%">
									<tr><td width="15%">Segment:</td><td>'.$bill_segment.'</td></tr>
									<tr><td>Service 1:</td><td>'.$bill_service1.'</td></tr>
									<tr><td>Service 2:</td><td>'.$bill_service2.'</td></tr>
									<tr><td>Partner:</td><td>'.$bill_partner.'</td></tr>
									<tr><td>Manager:</td><td>'.$bill_manager.'</td></tr>
								</table>';
		} 
		
		
		if($_POST['pdf_type'] == 'Copy'){
			$attn_width = 'width="250"';
			$client_name = $bill->client_name;
		} else {
			$attn_width = 'width="400"';
			$client_array = explode('|',$bill->client_name);
			$client_name = $client_array[0];
		}
		
        $html = '
            <table width="750">
              <tr>
                <td>&nbsp;</td>
                <td width="165">
                  ' . $header_data . '
                </td>
              </tr>
              <tr>
                <td style="padding: 0px;">
                <table>
                    <tr>
                      <td width="435">' . ($header === "false" ? '&nbsp;' : 'Bill No:') . '</td>
                      <td>' . $bill->bill_no_str . '</td>
                    </tr>
                    <tr>
                      <td>' . ($header === "false" ? '&nbsp;' : 'Date:') . '</td>
                      <td>' . date('d-M-Y', $bill->bill_date) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="750">
                    <tr>
                      <td width="1"></td>
                      <td '.$attn_width.' height="150">
                        <br />
                        ' . (!empty($bill->client_name) ? $client_name . '<br />' : '') . '
                        ' . (!empty($bill->billto_address) ? nl2br($bill->billto_address) : '') . '
                        ' . $attention_text . '
                      </td>
                      <td width="450">
						'.$segment_html.'
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              
              <tr>
                <td colspan="2" width="530">
                  <table cellspacing="2" cellpadding="4">
                    <tr>
                      <td colspan="3" style=""><br /><br />'. (!empty($attention_text) ? '' : '<br /><br /><br />') .'</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
			<!-- Font Size 10 -->
			<br><br>
            ';
		
		// ----------------- For Single PAGE print TCPDF Standard Version -----------------//
		if($bill->page2_enable == 0){

			if ($header === "false") {
				// do something
			} else {
				$base_path = str_replace('system/', '', BASEPATH);
				$pdf->Image($base_path . 'assets/v2/img/pdf/ey_new_logo.jpg', 20, 0, 30, 30, 'JPG', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
				//$pdf->Image($base_path . 'assets/v2/img/pdf/logo.gif', 70, 11, 45, 7, 'GIF', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
			}

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->setY($pdf->getY() - 5);
			// Setting up currency title
			$this->__addCells_memo($pdf, '', $bill->currency_title, 'L', 'R', -1);

			// Setting up bills data
			$total_amount = 0;
			$i = 1;
			/*foreach ($bill->bill_data as $bill_data_row) {
				if (!empty($bill_data_row->description) && !($bill_data_row->fee == 0)) {
					$this->__addCells_memo($pdf, $bill_data_row->description, number_format($bill_data_row->fee,0), 'L', 'R', $i); // Open Decimal Places
					$total_amount = $total_amount + $bill_data_row->fee;

					if ($i == 1)
						$this->__addSegments($pdf, $bill_data_row);

					$i++;
				}
			}*/
			
			foreach ($bill->bill_data as $bill_data_row) {
				
				$this->__addCells_memo($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop,0), 'L', 'R', $i); // Decimal Places
				$total_amount = $total_amount + $bill_data_row->oop;

				if ($i == 1)
					$this->__addSegments($pdf, $bill_data_row);

				$i++;
				
				/*if (!empty($bill_data_row->description_tax) && !($bill_data_row->others == 0)) {
					$this->__addCells_memo($pdf, $bill_data_row->description_tax, number_format($bill_data_row->others, 0), 'L', 'R', $i); // Decimal Places
					$total_amount = $total_amount + $bill_data_row->others;

					if ($i == 1)
						$this->__addSegments($pdf, $bill_data_row);

					$i++;
				}
				
				if (!empty($bill_data_row->description_oop) && !($bill_data_row->oop == 0)) {
					$this->__addCells_memo($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop,0), 'L', 'R', $i); // Decimal Places
					$total_amount = $total_amount + $bill_data_row->oop;

					if ($i == 1)
						$this->__addSegments($pdf, $bill_data_row);

					$i++;
				}*/
			}

			// Line before total amount
			$lineY = $pdf->getY() - 1;
			$pdf->Line(170, $lineY, 195, $lineY);

			// Total amount
			$words = $this->_convert_number_to_words(str_replace(',', '', number_format($bill->total_amount,0))) . ' ONLY';
			$words = str_replace(',', '', strtolower($words));
						
			$pdf->getY($pdf->getY() + 15);
			$this->__addCells_memo($pdf, '(' .$bill->currency_title . ' ' .  ucwords($words) . ')', number_format($bill->total_amount,0), 'L', 'R'); // Decimal Places
			
			// Line after total amount
			$lineY = $pdf->getY() - 1;
			$pdf->Line(170, $lineY, 195, $lineY);
			$pdf->Line(170, $lineY + 0.6, 195, $lineY + 0.6);
						
			// Show Signature
			if (!empty($bill->signature)){
				$pdf->SetFontSize(10);
				$this->__addCells_memo($pdf, $bill->signature, '', 'L', 'R');
			}

			// Show Special Instructions
			if (!empty($bill->special_instructions) || $bill->special_instructions > 0){
				$bill->special_instructions = nl2br ($bill->special_instructions);
				$pdf->SetFontSize(8);
				$this->__addCells_memo($pdf, $bill->special_instructions, '', 'L', 'R');
			}
			
			// Show Bottom Note
			if (!empty($bill->note)){
				$pdf->SetFontSize(8);
				$this->__addCells_memo($pdf, $bill->note, '', 'L', 'R');
			}
		
		// ---------------------------- For Multiple PAGES, Print Custom HTML Version ------------------------//
		} else {
		
			$html .= '<table width="750">
					<tr style="line-height:2px">
		              <td width="45">&nbsp;</td>
					  <td colspan="1" width="410"></td>
		              <td width="30">&nbsp;</td>
					  <td valign="bottom" align="right" width="65">' . $bill->currency_title . '</td>
					</tr>';
			$total_amount = 0;
			
			$i = 0; 
			foreach ($bill->bill_data as $index => $bill_data_row) {
				if (!empty($bill_data_row->description) && $bill_data_row->fee > 0) {
					// Due to possible Big description in First Fee Row, Amount is given in next row because of V-Align bottom not working.
					/*if($i1 == 0){
						$html .= '
							<tr>
							  <td>&nbsp;</td>
							  <td colspan="1">' . $bill_data_row->description . '</td>
							  <td>&nbsp;</td>
							  <td align="right">&nbsp;</td>
							</tr>
							<tr style="line-height:2px">
							  <td>&nbsp;</td>
							  <td colspan="1">&nbsp;</td>
							  <td>&nbsp;</td>
							  <td align="right">' . number_format($bill_data_row->fee) . '</td>
							</tr>';
					} else {*/
						$html .= '
							<tr>
							  <td>&nbsp;</td>
							  <td colspan="1">' . $bill_data_row->description . '</td>
							  <td>&nbsp;</td>
							  <td valign="bottom">' . number_format($bill_data_row->fee) . '</td>
							</tr>';
					//}
					$total_amount = $total_amount + $bill_data_row->fee;
				}
				$i++;
			}
			
			$i = 0; 
			foreach ($bill->bill_data as $index => $bill_data_row) {			
				if (!empty($bill_data_row->description_tax) && $bill_data_row->others > 0) {
					$html .= '
						<tr style="line-height:2px">
											  <td>&nbsp;</td>

						  <td colspan="1" >' . $bill_data_row->description_tax . '</td>
						  <td>&nbsp;</td>
						  <td align="right" style="vertical-align:bottom">' . number_format($bill_data_row->others) . '</td>
						</tr>';
					$total_amount = $total_amount + $bill_data_row->others;
				}

				if (!empty($bill_data_row->description_oop) && $bill_data_row->oop > 0) {
					$html .= '
						<tr style="line-height:2px">
											  <td>&nbsp;</td>

						  <td colspan="1" >' . $bill_data_row->description_oop . '</td>
						  <td>&nbsp;</td>
						  <td valign="bottom" align="right">' . number_format($bill_data_row->oop) . '</td>
						</tr>';
					$total_amount = $total_amount + $bill_data_row->oop;
				}
				$i++;
			}
				
			$words = $bill->currency_title . ' ' . $this->_convert_number_to_words(str_replace(',', '', $total_amount));
			$html .= '
				<tr style="line-height:2px">
									  <td>&nbsp;</td>

				  <td>(' . strtoupper($words) . ')</td>
				  <td>&nbsp;</td>
				  <td valign="bottom" align="right" style="border-top: 1px solid #000; border-bottom: 1px solid #000;">' . number_format($total_amount) . '</td>
				</tr>';
			
			if (!empty($bill->signature)) {
				$html .= '
					<tr>
					  <td colspan="4">&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>' . $bill->signature . '</td>
					  <td>&nbsp;</td>
					  <td valign="bottom" align="right"></td>
					</tr>';
			}
			
			if (!empty($bill->special_instructions)) {
				$html .= '
					<tr>
					  <td colspan="4">&nbsp;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td>' . nl2br ($bill->special_instructions) . '</td>
					  <td>&nbsp;</td>
					  <td valign="bottom" align="right"></td>
					</tr>';
			}
			
			$html .= '</table>';

			if ($header === "false") {
				// do something
			} else {
				$base_path = str_replace('system/', '', BASEPATH);
				$pdf->Image($base_path . 'assets/v2/img/pdf/ey_new_logo.jpg', 20, 0, 30, 30, 'JPG', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
				//$pdf->Image($base_path . 'assets/v2/img/pdf/logo.gif', 70, 11, 45, 7, 'GIF', 'http://www.ey.com/', '', true, 150, '', false, false, 0, false, false, false);
			}
			

			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');
		
		}
//        echo htmlentities($bill->special_instructions);
//        exit;
        //Close and output PDF document
        $pdf->Output('example_006.pdf', 'I');
    }
	
	
	
	public function simple_pdf3($bill_id = 0, $header = true, $pdf_action = 'D') {
		
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		//Get Path Settings
		$settings = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		
        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
       
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 26);

        // set font
        //$pdf->SetFont('dejavusans', '', 10);
		$pdf->SetFontSize(10);
		
        // add a page
        $pdf->AddPage();

        $zeros = array('0000', '000', '00', '0', '');
        $bill = $this->Bills_model->getBillByID($bill_id);

        $header_data =
                '
                  <strong style="font-weight: normal; font-size: 8px; line-height: 0; padding: 0; margin: 0;">&nbsp;</strong>
                  <span style="font-weight: normal; font-size: 8px;">
                    <br />
                    &nbsp;<br />
                    &nbsp;<br />
                    &nbsp;<br /><br />
					&nbsp;
                  </span>';

        if (empty($bill->attention_line_1) && empty($bill->attention_line_1)) {
            $attention_text = '<br /><br />';
        } else {
            $attention_text = '
                <br /><br />
                <strong>Attention: &nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_1 . '<br />
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                          ' . $bill->attention_line_2 . '
                 </strong>';
        }
		
		$client_array = explode('|',$bill->client_name);
		$client_name = $client_array[0];
		
        $html = '
            <table width="750">
              <tr>
                <td>&nbsp;</td>
                <td width="165">
                  ' . $header_data . '
                </td>
              </tr>
              <tr>
                <td style="padding: 0px;">
                <table>
                    <tr>
						<td colspan="2"></td>
					</tr>
					<tr>
                      <td width="415">' . ($header === "false" ? '&nbsp;' : '&nbsp;') . '</td>
                      <td style="font-size: 10px;">' . $bill->bill_no_str . '</td>
                    </tr>
                    <tr>
                      <td>' . ($header === "false" ? '&nbsp;' : '&nbsp;') . '</td>
                      <td style="font-size: 10px;">' . date('d-M-Y', $bill->bill_date) . '</td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td>
                  <table width="750">
                    <tr>
                      <td width="1"></td>
                      <td width="520" height="150">
                        <br />
                        ' . (!empty($bill->client_name) ? $client_name . '<br />' : '') . '
                        ' . (!empty($bill->billto_address) ? nl2br($bill->billto_address) : '') . '
						' . (!empty($bill->ntn) ? '<br /> NTN: '.$bill->ntn : '') . '
                        ' . $attention_text . '
                      </td>
                      <td>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table>
                    <tr>
                      <td>
                        ' . ($header === "false" ? '&nbsp;' : '&nbsp;') . '<br />
                        ' . ($header === "false" ? '&nbsp;' : '&nbsp;') . '<br />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <table>
                    <tr>
                      <td width="390"></td>
                      <td>
                        <br />
                        <span style="font-size: 16px">' . ($header === "false" ? '&nbsp;' : '&nbsp;') . '</span><br />
                        <!--xxxxx-->
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="2" width="530">
                  <table cellspacing="2" cellpadding="4">
                    <tr>
                      <td colspan="3" style=""><br /><br /><br /><br />'. (!empty($attention_text) ? '' : '<br /><br /><br />') .'</td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            ';

        if ($header === "false") {
            // do something
			$base_path = str_replace('system/', '', BASEPATH);
            $pdf->Image($base_path . 'assets/v2/img/pdf/'.$settings->path_name.'.png', 0, 0, 217, 297, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
        } else {
            $base_path = str_replace('system/', '', BASEPATH);
            $pdf->Image($base_path . 'assets/v2/img/pdf/'.$settings->path_name.'.png', 0, 0, 217, 297, 'PNG', '', '', true, 300, '', false, false, 0, false, false, false);
        }

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->setY($pdf->getY() - 15);
        // Setting up currency title
        $this->__addCells($pdf, '', $bill->currency_title, 'L', 'R', -1);

        // Setting up bills data
        $total_amount = 0;
        $i = 1;
        foreach ($bill->bill_data as $bill_data_row) {
            if ($bill_data_row->fee != 0) {
				if(empty($bill_data_row->description)){
					echo '<h3>We are sorry, PDF cannot be generated due to some corrections in this Bill.<br>Please contact Administrator.</h3>';
					die;
				}
                $this->__addCells($pdf, $bill_data_row->description, number_format($bill_data_row->fee, 0), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->fee;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }

            /*if (!empty($bill_data_row->description_tax) && !($bill_data_row->others == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_tax, number_format($bill_data_row->others, 2), 'L', 'R', $i);

                $total_amount = $total_amount + $bill_data_row->others;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }
			
			if (!empty($bill_data_row->description_oop) && !($bill_data_row->oop == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop, 2), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->oop;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }*/
        }
		
		foreach ($bill->bill_data as $bill_data_row) {
            /*if (!empty($bill_data_row->description) && !($bill_data_row->fee == 0)) {
                $this->__addCells($pdf, $bill_data_row->description, number_format($bill_data_row->fee, 2), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->fee;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }*/

            if (!empty($bill_data_row->description_tax) && !($bill_data_row->others == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_tax, number_format($bill_data_row->others, 0), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->others;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }
			
			if (!empty($bill_data_row->description_oop) && !($bill_data_row->oop == 0)) {
                $this->__addCells($pdf, $bill_data_row->description_oop, number_format($bill_data_row->oop, 0), 'L', 'R', $i);
                $total_amount = $total_amount + $bill_data_row->oop;

                if ($i == 1)
                    $this->__addSegments_d($pdf, $bill_data_row);

                $i++;
            }
        }
		
		if($total_amount != $bill->total_amount){
			echo '<h3>We are sorry, PDF cannot be generated due to some corrections in this Bill.<br>Please contact Administrator.</h3>';
			die;
		}

        // Line before total amount
        $lineY = $pdf->getY() - 1;
        $pdf->Line(183, $lineY, 207, $lineY);

        // Total amount
		$words = $this->_convert_number_to_words(str_replace(',', '', number_format($bill->total_amount,0))) . ' ONLY';
		$words = str_replace(',', '', strtolower($words));
					
		$pdf->getY($pdf->getY() + 15);
		$this->__addCells($pdf, '(' .$bill->currency_title . ' ' .  ucwords($words) . ')', number_format($bill->total_amount,0), 'L', 'R'); // Decimal Places

        // Line after total amount
        $lineY = $pdf->getY() - 1;
        $pdf->Line(183, $lineY, 207, $lineY);
        $pdf->Line(183, $lineY + 0.6, 207, $lineY + 0.6);

        // Setting up currency title
        if (!empty($bill->special_instructions) || $bill->special_instructions > 0){
            $bill->special_instructions = nl2br ($bill->special_instructions);
			$pdf->SetFontSize(8);
            $this->__addCells($pdf, $bill->special_instructions, '', 'L', 'R');
		}
		
		// Show Bottom Note
		if (!empty($bill->note)){
			$pdf->SetFontSize(8);
			$this->__addCells_memo($pdf, $bill->note, '', 'L', 'R');
		}

		$file_name = str_replace('/','_',$bill->bill_no_str).'.pdf';

        //Close and output PDF document
		if($pdf_action == 'S'){
			$pdf->Output('example_006.pdf', 'I');
			//return $pdf->Output($file_name, $pdf_action);
		} else {
			$pdf->Output($file_name, $pdf_action);
		}
    }
	
	
	
    function __addSegments(&$pdf, $row) {
        $text = 'Segment: test
                  Service 1: test<br />
                  Service 2: test<br />
                  Partner: test<br />
                  Manager: test<br />';

        $cw = 22;
        $ch = 0;
        $nx = 110;
        $ny = 56;
        $ox = $pdf->getX();
        $oy = $pdf->getY();
        $pdf->setY($ny);

        /*$columns = array(
            'segment' => 'Segment :',
            'service1' => 'Service 1 :',
            'service2' => 'Service 2:',
            'partner' => 'Partner :',
            'manager' => 'Manager :',
			'sr_manager' => 'SM / ED :',
        );*/
		$columns = array(
            'segment' => 'Segment :',
            'service1' => 'Service 1 :',
            'service2' => 'Service 2:',
            'partner' => 'Partner :',
            'sm_ed' => 'Engm. Team :'
        );
		
		if(isset($_POST['pdf_type'])){
			if ($_POST['pdf_type']=='Copy' || $_POST['pdf_type']=='Print Copy'){
				$row = (array) $row;
				foreach ($columns as $column => $heading) {
					if($row[$column] != ''){
						$pdf->setX($nx);
						$pdf->Cell($cw, $ch, $heading, 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
						$pdf->Cell($cw, $ch, $row[$column], 0, 1, 'L', 0, 0, 0, false, 'T', 'B');
					}
				}
			}
		} else {
			$row = (array) $row;
			foreach ($columns as $column => $heading) {
				if($row[$column] != ''){
					$pdf->setX($nx);
					$pdf->Cell($cw, $ch, $heading, 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
					$pdf->Cell($cw, $ch, $row[$column], 0, 1, 'L', 0, 0, 0, false, 'T', 'B');
				}
			}
		}

        $pdf->setX($ox);
        $pdf->setY($oy);
    }
	
	
	function __addSegments_d(&$pdf, $row) {
        $text = 'Segment: test
                  Service 1: test<br />
                  Service 2: test<br />
                  Partner: test<br />
                  Manager: test<br />';

        $cw = 22;
        $ch = 0;
        $nx = 110;
        $ny = 56;
        $ox = $pdf->getX();
        $oy = $pdf->getY();
        $pdf->setY($ny);

        $columns = array(
            'segment' => 'Segment :',
            'service1' => 'Service 1 :',
            'service2' => 'Service 2:',
            'partner' => 'Partner :',
            'manager' => 'Manager :',
        );
		
		/*if(isset($_POST['pdf_type'])){
			if($_POST['pdf_type'] == 'Copy'){
				$row = (array) $row;
				foreach ($columns as $column => $heading) {
					$pdf->setX($nx);
					$pdf->Cell($cw, $ch, $heading, 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
					$pdf->Cell($cw, $ch, $row[$column], 0, 1, 'L', 0, 0, 0, false, 'T', 'B');
				}
			}
		} else {
			$row = (array) $row;
			foreach ($columns as $column => $heading) {
				$pdf->setX($nx);
				$pdf->Cell($cw, $ch, $heading, 0, 0, 'L', 0, 0, 0, false, 'T', 'B');
				$pdf->Cell($cw, $ch, $row[$column], 0, 1, 'L', 0, 0, 0, false, 'T', 'B');
			}
		}*/

        $pdf->setX($ox);
        $pdf->setY($oy);
    }
	
	
    function __addCells(&$pdf, $description, $amount, $align1 = 'L', $align2 = 'R', $first = -1) {
        $description = preg_replace('/size="(\d)"/i', '', $description);
        $description = preg_replace('/[\s]*font-[a-z]*:[\s*](\d*)[a-z]*[;]*[\s]*/i', '', $description);

        if ($first == 1)
            $pdf->setY($pdf->getY() - 5);

        //$this->__pageBreak($pdf, $description);
        $startY = $pdf->getY();
        $pdf->MultiCell(150, 0, $description, 0, $align1, 0, 25, 25, '', true, 0, 1);
        $height = $pdf->getY() - $startY;

        $pdf->setY($startY);
        $pdf->setX(171);
        $pdf->Cell(34, $height, $amount, 0, 1, $align2, 0, 0, 0, false, 'T', 'B');

        if (!($first == 0))
            $pdf->setY($pdf->getY() + 4);
    }
	
	// For Original and Copy bills with BG.
	function __addCells2(&$pdf, $description, $amount, $align1 = 'L', $align2 = 'R', $first = -1) {
        $description = preg_replace('/size="(\d)"/i', '', $description);
        $description = preg_replace('/[\s]*font-[a-z]*:[\s*](\d*)[a-z]*[;]*[\s]*/i', '', $description);

        if ($first == 1)
            $pdf->setY($pdf->getY() - 5);

        //$this->__pageBreak($pdf, $description);
        $startY = $pdf->getY();
        $pdf->MultiCell(150, 0, $description, 0, $align1, 0, 25, 25, '', true, 0, 1);
        $height = $pdf->getY() - $startY;

        $pdf->setY($startY);
		// For Amount Alignment Left or Right
        $pdf->setX(167);
        $pdf->Cell(34, $height, $amount, 0, 1, $align2, 0, 0, 0, false, 'T', 'B');

        if (!($first == 0))
            $pdf->setY($pdf->getY() + 4);
    }
	
	function __addCells_memo(&$pdf, $description, $amount, $align1 = 'L', $align2 = 'R', $first = -1) {
        $description = preg_replace('/size="(\d)"/i', '', $description);
        $description = preg_replace('/[\s]*font-[a-z]*:[\s*](\d*)[a-z]*[;]*[\s]*/i', '', $description);
        if ($first == 1)
        $pdf->setY($pdf->getY() - 5);
        //$this->__pageBreak($pdf, $description);
        $startY = $pdf->getY();
        $pdf->MultiCell(135, 0, $description, 0, $align1, 0, 1, 25, '', true, 0, 1);
        $height = $pdf->getY() - $startY;
        $pdf->setY($startY);
        $pdf->setX(171);
        $pdf->Cell(20, $height, $amount, 0, 1, $align2, 0, 0, 0, false, 'T', 'B');
        if (!($first == 0))
            $pdf->setY($pdf->getY() + 4);
    }
    function __pageBreak(&$pdf, $text) {
        $pdf2 = clone $pdf;
        $pdf2->addpage();
        $startY = $pdf2->getY();
        $pdf2->MultiCell(150, 0, $text, 0, 'L', 0, 1, 25, '', true, 0, 1);
        $height = $pdf2->getY() - $startY;
        $pdf2->deletePage($pdf2->getPage());
        $pdf->checkPageBreak($height);
    }
    public function delete($bill_id = 0) {
		
		$email_settings = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		if(!empty($email_settings->del_bill_to1)) $to[] = $email_settings->del_bill_to1;
		if(!empty($email_settings->del_bill_to2)) $to[] = $email_settings->del_bill_to2;
		$from = $email_settings->from_email;
		
		//// ------ START - Stop Delete option by Non-Admin user. . ------- //
		if(($this->Bills_model->getStatus($bill_id) == 'approved') && ($this->user['id'] != 'ccd4290c-ad21-50e5-9457-f0e77bac1659')){
			
			if($email_settings->send_bill_del_email == 'YES' && count($to) > 0){
				
				$bill_no = $this->Bills_model->getBillNoByID($bill_id);
				$client = $this->Bills_model->getBillToByBillID($bill_id);
				$user_branch = $this->Branches_model->getBranchByID($this->user['branch_id']);
				$email_body = 
					'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml"> 
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>EYFRSH Billing Management System</title>
					</head>
					<body>
						<p>Dear Admin,</p>
						<p>A user has tried to delete a bill, details are given below:</p>
						<table border="0">
							<tr>
								<td width="160" valign="top">Bill URL<span style="float:right">:</span></td>
								<td> <a href="'.site_url("bills/edit/{$bill_id}").'">'.$bill_no->bill_no_str.'</a></td>
							</tr>
							<tr>
								<td width="160" valign="top">Client<span style="float:right">:</span></td>
								<td> '.$client->client_name.'</td>
							</tr>
							<tr>
								<td>User<span style="float:right">:</span></td>
								<td> '.$this->user['full_name'].'</td>
							</tr>
							<tr>
								<td>IP<span style="float:right">:</span></td>
								<td> '.$_SERVER['REMOTE_ADDR'].'</td>
							</tr>
							<tr>
								<td>Branch<span style="float:right">:</span></td>
								<td> '.$user_branch->name.'</td>
							</tr>
							<tr><td colspan="2">&nbsp;</td></tr>
						 </table>
						 <p>Please take necessary action.</p>
						 <p>EYFRSH Billing Management System</p>
						 <br>
					</body>
					</html>';
		
				$email_subject = 'Un-authorized Delete access by user '.$this->user['full_name'].'('.$user_branch->name.')';
				//$to[] = 'Niaz.Ahmed@pk.ey.com';
				$this->Email_model->send_email($email_body, $email_subject, $from, $to, $cc, $bcc);
			}
			redirect('bills/common');
		}
		//// ------ END - Important Check to avoid users opening Approved bills again. ------- //
		else {
			
			// Checking if payment is received against the bill
			$query = $this->db->query("SELECT amount FROM receipts_data WHERE bill_id = '{$bill_id}'");
			if ($query->num_rows())
				redirect('bills/common/err1');
			
			if($this->Bills_model->getStatus($bill_id)=='approved' && $email_settings->send_bill_del_email=='YES' && count($to)>0){
				
				$bill_no = $this->Bills_model->getBillNoByID($bill_id);
				$client = $this->Bills_model->getBillToByBillID($bill_id);
				$user_branch = $this->Branches_model->getBranchByID($this->user['branch_id']);
				$email_body = 
					'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">
					<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
					<title>EYFRSH Billing Management System</title>
					</head>
					<body>
						<p>Dear Admin,</p>
						<p>A bill has been deleted, details are given below:</p>
						<table border="0">
							<tr>
								<td width="160" valign="top">Bill No<span style="float:right">:</span></td>
								<td> '.$bill_no->bill_no_str.'</td>
							</tr>
							<tr>
								<td width="160" valign="top">Client<span style="float:right">:</span></td>
								<td> '.$client->client_name.'</td>
							</tr>
							<tr>
								<td>User<span style="float:right">:</span></td>
								<td> '.$this->user['full_name'].'</td>
							</tr>
							<tr>
								<td>IP<span style="float:right">:</span></td>
								<td> '.$_SERVER['REMOTE_ADDR'].'</td>
							</tr>
							<tr>
								<td>Branch<span style="float:right">:</span></td>
								<td> '.$user_branch->name.'</td>
							</tr>
							<tr><td colspan="2">&nbsp;</td></tr>
						 </table>
						 <p>Please take necessary action.</p>
						 <p>EYFRSH Billing Management System</p>
						 <br>
					</body>
					</html>';
		
				$email_subject = 'Bill Deleted by user '.$this->user['full_name'].'('.$user_branch->name.')';
				//$to[] = 'Niaz.Ahmed@pk.ey.com';
				$this->Email_model->send_email($email_body, $email_subject, $from, $to, $cc, $bcc);
			}
			 
			// Removing bills and all it's data
			/*$this->db->delete('bills_other_details', array('bill_id' => $bill_id));
			$this->db->delete('bills_files', array('bill_id' => $bill_id));
			$this->db->delete('bills_allocations', array('bill_id' => $bill_id));
			$this->db->delete('bills_data', array('bill_id' => $bill_id));
			$this->db->delete('bills', array('id' => $bill_id));*/
			
			$record = array(
					'status' => 'deleted',
				);
			$this->Bills_model->updateBillsLog($bill_id,'deleted','index');
			$this->Bills_model->updateBill($bill_id, $record);
			
			//redirect('bills/index/msg1');
			redirect('bills/common/msg1');
		}
    }
    function approve($bill_id) {
        if ($this->Approvals_model->updateApproval('approved', $bill_id, $this->user['id'])) {
            redirect('bills/approval');
        }
    }
    function deny($bill_id) {
        if ($this->Approvals_model->updateApproval('deny', $bill_id, $this->user['id'])) {
            redirect('bills/approval');
        }
    }
    public function _convert_number_to_words($number) {
        $hyphen = '-';
        $conjunction = ' and ';
        $separator = ', ';
        $negative = 'negative ';
        $decimal = ' point ';
        $dictionary = array(
            0 => 'zero',
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
            1000000 => 'million',
            1000000000 => 'billion',
            1000000000000 => 'trillion',
            1000000000000000 => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                    'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . $this->_convert_number_to_words(abs($number));
        }
        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int) ($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->_convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = $this->_convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->_convert_number_to_words($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

}
