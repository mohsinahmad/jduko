<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();

		if($this->uri->segment(2) == 'outstanding_excel_email'){
			// Allow Reports without Login Authentication.
		} else {
			if (isset($this->user) && !is_array($this->user)) {
				$this->load->helper('url');
				$this->session->set_userdata('last_page', current_url());
				redirect();
			}
		}
		/*if (isset($this->user) && !is_array($this->user)) {
			$this->session->set_userdata('last_page', current_url());
            redirect();
        }*/

        $this->load->model('Bills_model');
        $this->load->model('Banks_model');
        $this->load->model('Receipts_model');
        $this->load->model('Clients_model');
        $this->load->model('Currencies_model');
        $this->load->model('Segments_model');
        $this->load->model('Users_model');
        $this->load->model('Reports_model');
        $this->load->model('Branches_model');
		$this->load->model('Email_model');
		$this->load->model('Shapps_model');
		$this->load->model('Settings_model');
    }

    public function client_ledger($type = 'html') {
        if (isset($_POST['client_id'])) {
            $query = $this->db->query("
            SELECT
              bills.bill_no, bills.total_fees, bills.total_oop,
              client_id, bills.id, receipts_data.amount, bills.bill_date, receipts_data.date,
              FROM_UNIXTIME(bills.bill_date,  '%Y-%d-%m') as date_bill,
              (SELECT date FROM receipts WHERE id = receipts_data.receipt_id LIMIT 1) as date_receipt
            FROM bills
            LEFT JOIN receipts_data ON bills.id = receipts_data.bill_id
            WHERE client_id = '{$_POST['client_id']}'
            ORDER BY bills.client_id, bills.bill_date, receipts_data.date
            limit 100
            ");

            $result = $query->result();
            $bill_ids = array();
            $clients_data = array();
            foreach ($result as $index => $row) {
                if (!in_array($row->id, $bill_ids)) {
                    $bill_ids[] = $row->id;
                    $row2 = (array) $row;
                    $row2['amount'] = 0;
                    $clients_data[$row->client_id][$row->bill_date] = (object) $row2;
                }

                if ($row->amount > 0)
                    $clients_data[$row->client_id][$row->date_receipt + $index] = $row;
            }

            // Preparing data for views
            $headings = array('Bill No', 'Date', 'Amount', 'OOP', 'Debit', 'Credit', 'Balance');
            $rows = array();
            foreach ($clients_data as $client_id => $data) {
                $client_name = $this->Default_model->findColumnByID('clients', $client_id, 'client_name');
                $rows[] = array(array('data' => '<b>' . $client_name . '</b>', 'attr' => 'colspan="7"'));

                ksort($data);
                $debit = 0;
                $credit = 0;
                $balance = 0;
                foreach ($data as $row) {
                    $balance = $row->amount > 0 ? $balance - $row->amount : $balance + ($row->total_fees + $row->total_oop);

                    if ($row->amount > 0)
                        $credit = $credit + number_format($row->amount);
                    else
                        $debit = $debit + ($row->total_fees + $row->total_oop);

                    $rows[] = array(
                        array('data' => $row->bill_no),
                        array('data' => ($row->amount > 0 ? date('Y-m-d', $row->date_receipt) : $row->date_bill), 'cols' => 1),
                        array('data' => number_format($row->total_fees), 'attr' => 'align="right"', 'type' => 'numeric'),
                        array('data' => number_format($row->total_oop), 'attr' => 'align="right"', 'type' => 'numeric'),
                        array('data' => ($row->amount > 0 ? 0 : number_format($row->total_fees + $row->total_oop)), 'attr' => 'align="right"', 'type' => 'numeric'),
                        array('data' => ($row->amount > 0 ? number_format($row->amount) : 0), 'attr' => 'align="right"', 'type' => 'numeric'),
                        array('data' => number_format($balance), 'attr' => 'align="right"', 'type' => 'numeric'),
                    );
                }

                if ($type == 'excel') {
                    $rows[] = array(
                        array('data' => '<b>Total</b>'),
                        array('data' => ''),
                        array('data' => ''),
                        array('data' => ''),
                        array('data' => '<b>' . number_format($debit) . '</b>', 'attr' => 'align="right"'),
                        array('data' => '<b>' . number_format($credit) . '</b>', 'attr' => 'align="right"'),
                        array('data' => '<b>' . number_format($balance) . '</b>', 'attr' => 'align="right"'),
                    );
                } else {
                    $rows[] = array(
                        array('data' => '<b>Total</b>', 'attr' => 'colspan="4"'),
                        array('data' => '<b>' . number_format($debit) . '</b>', 'attr' => 'align="right"'),
                        array('data' => '<b>' . number_format($credit) . '</b>', 'attr' => 'align="right"'),
                        array('data' => '<b>' . number_format($balance) . '</b>', 'attr' => 'align="right"'),
                    );
                }

                $rows[] = array(array('data' => '', 'attr' => 'colspan="7"'));
            }
        }

        // Simple HTML view
        if ($type == 'html') {
            $data['search_fields'] = array(
                array('label' => 'Client Name', 'name' => "clients", 'class' => 'autocomplete'),
            );
            $data['headings'] = isset($headings) ? $headings : array();
            $data['rows'] = isset($rows) ? $rows : array();
            $data['page'] = 'reports/view';
            $this->load->view('layout/v2/default', $data);
        }
        // PDF view
        else if ($type == 'pdf')
            ey_generate_pdf($headings, $rows);
        // Excell view
        else if ($type == 'excel')
            ey_generate_excel($headings, $rows, 'Client Ledger');
    }

    public function monthly_billing($type = 'html') {
        $where = '';
        if (isset($_POST['search-bills-submit'])) {
            $segments = array();
            if (isset($_POST['segments'])) {
                foreach ($_POST['segments'] as $segment) {
                    $segments[] = "'{$segment}'";
                }
            }

            if (sizeof($segments))
                $where .= 'bills_data.segment_id IN (' . implode(',', $segments) . ')';

            $partners = array();
            if (isset($_POST['partners'])) {
                foreach ($_POST['partners'] as $partner) {
                    $partners[] = "'{$partner}'";
                }
            }

            if (sizeof($partners))
                $where .= 'bills_data.partner_id IN (' . implode(',', $partners) . ')';

            if (!empty($where))
                $where = 'WHERE ' . $where;
        }
        $result = $this->db->query("
            SELECT 
            (SELECT bill_date FROM bills WHERE id = bills_data.bill_id) as bill_date,
            FROM_UNIXTIME((SELECT bill_date FROM bills WHERE id = bills_data.bill_id), '%M') as bill_month,
            (SELECT name FROM segments WHERE id = bills_data.segment_id) as segment_name,
            (SELECT full_name FROM users WHERE id = bills_data.partner_id) as partner_name,
            SUM(fee) as total_fee,
            SUM(oop) as total_oop,
            SUM(fee) + SUM(oop) as total_amount
            FROM `bills_data`
            {$where}
            GROUP BY bill_month, partner_id, segment_id
            ORDER BY segment_name, partner_name, bill_month
            ");
        //$result = $this->db->get();

        $total_billings = array();
        foreach ($result->result() as $row) {
            $total_billings[$row->segment_name][$row->partner_name][$row->bill_month] = array(
                'total_fee' => $row->total_fee,
                'total_oop' => $row->total_oop,
                'total_amount' => $row->total_amount,
            );
        }

        $months = array('January', 'Febuary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $headings = array('&nbsp;');
        foreach ($months as $month) {
            $headings[] = array('data' => substr($month, 0, 3), 'attr' => 'width="90"');
        }

        $items = array('total_fee' => 'Fees', 'total_oop' => 'OOP', 'total_amount' => 'Total');
        $rows = array();
        foreach ($total_billings as $segment_name => $total_billings_partners) {
            $rows[] = array(array('data' => '<b>' . $segment_name . '</b>', 'attr' => 'width="150" colspan="13"'));
            foreach ($total_billings_partners as $partner_name => $record) {
                $rows[] = array(array('data' => '<b>' . (empty($partner_name) ? 'N/A' : $partner_name) . '</b>', 'attr' => 'style="padding-left: 24px;" colspan="13"'));

                foreach ($items as $item_key => $item_value) {
                    $column = array();
                    $column[] = array('data' => '<b>' . $item_value . '</b>', 'attr' => 'style="padding-left: 48px;"');
                    foreach ($months as $month) {
                        if (!isset($total_billings[$segment_name][$partner_name][$month])) {
                            $column[] = array('data' => 0, 'attr' => 'align="right"');
                        } else {
                            $column[] = array(
                                'data' => $type == 'excel' ? $total_billings[$segment_name][$partner_name][$month][$item_key] : number_format($total_billings[$segment_name][$partner_name][$month][$item_key]),
                                'attr' => 'align="right"',
                                'type' => 'numeric'
                            );
                        }
                    }
                    $rows[] = $column;
                }
            }
        }

        // Simple HTML view
        if ($type == 'html') {
            $data['search_view'] = 'reports/search/monthly-billing';
            $data['partners'] = $this->Users_model->getUserByRoleName('Partner');
            $data['segments'] = $this->Segments_model->getAllSegments(0);
            $data['headings'] = isset($headings) ? $headings : array();
            $data['rows'] = isset($rows) ? $rows : array();
            $data['page'] = 'reports/view';
            $this->load->view('layout/v2/default', $data);
        }
        // PDF view
        else if ($type == 'pdf')
            ey_generate_pdf($headings, $rows);
        // Excell view
        else if ($type == 'excel')
            ey_generate_excel($headings, $rows, 'Monthly Billing');
    }


    public function outstanding($action = '') {
		
        $age_filter = 'NO';
		
		$data = array(
            'page' => 'reports/builder/bills-outstanding-cw',
            'collectors' => $this->Users_model->getCollectors(),
			'sr_managers' => $this->Users_model->getUserByRoleName('SM_ED'),
			'managers' => $this->Users_model->getUserByRoleName('Manager'),
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
			'branches' => $this->Branches_model->getAllBranch(),
            'segments' => $this->Segments_model->getAllSegments(0),
			'variables' => $this->variables,
			'age_filter' => $age_filter,
            'outstanding' => array(),
        );

        if (isset($_REQUEST['search-bills-submit'])) {
			
			$age_ranges = array();
			foreach ($_REQUEST['fields'] as $field_name => $field_value) {
				if ($field_name == 'bills-age1') {
					$bill_age_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_age_to = $_REQUEST['fields'][$field_name]['to'];
	
					if (!empty($bill_age_from) && !empty($bill_age_to)) {



						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-'.$bill_age_to;
					} else if (!empty($bill_age_from)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-';
					} else if (!empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = '-'.$bill_age_to;
					}
				}
				else if ($field_name == 'bills-age2') {
					$bill_age_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_age_to = $_REQUEST['fields'][$field_name]['to'];
	
					if (!empty($bill_age_from) && !empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-'.$bill_age_to;
					} else if (!empty($bill_age_from)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-';
					} else if (!empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = '-'.$bill_age_to;
					}
				}
				else if ($field_name == 'bills-age3') {
					$bill_age_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_age_to = $_REQUEST['fields'][$field_name]['to'];
	
					if (!empty($bill_age_from) && !empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-'.$bill_age_to;
					} else if (!empty($bill_age_from)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-';
					} else if (!empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = '-'.$bill_age_to;
					}
				}
				else if ($field_name == 'bills-age4') {
					$bill_age_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_age_to = $_REQUEST['fields'][$field_name]['to'];
	
					if (!empty($bill_age_from) && !empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-'.$bill_age_to;
					} else if (!empty($bill_age_from)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-';
					} else if (!empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = '-'.$bill_age_to;
					}
				}
				else if ($field_name == 'bills-age5') {
					$bill_age_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_age_to = $_REQUEST['fields'][$field_name]['to'];
	
					if (!empty($bill_age_from) && !empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-'.$bill_age_to;
					} else if (!empty($bill_age_from)) {
						$age_filter = 'YES';
						$age_ranges[] = $bill_age_from.'-';
					} else if (!empty($bill_age_to)) {
						$age_filter = 'YES';
						$age_ranges[] = '-'.$bill_age_to;
					}
				}
			}
			
			
			$bills = $this->Reports_model->getOutstanding();
            $result = array();
            foreach ($bills as $bill) {
                $result[$bill->client_id][] = $bill;
            }
            if ($action == "excel") {
				/** Include path * */
				$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
				//ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

				/** PHPExcel */
				include_once $basepath . 'PHPExcel.php';

				/** PHPExcel_Writer_Excel2007 */
				include_once $basepath . 'PHPExcel/Writer/Excel2007.php';

				// Create new PHPExcel object
				$objPHPExcel = new PHPExcel();

				// Set properties
				$objPHPExcel->getProperties()->setCreator("Earnst & Young");
				$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
				$objPHPExcel->getProperties()->setTitle("Bill Register");
				$objPHPExcel->getProperties()->setSubject("Bill Register");
				$objPHPExcel->getProperties()->setDescription("Bill Register");

				// Add some data
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Client Name');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Segment');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Partner');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Manager');
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Bill Date');
				$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Bill No');
				$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Outstanding');
				$objPHPExcel->getActiveSheet()->SetCellValue('H1', '1-30');
				$objPHPExcel->getActiveSheet()->SetCellValue('I1', '31-60');
				$objPHPExcel->getActiveSheet()->SetCellValue('J1', '61-90');
				$objPHPExcel->getActiveSheet()->SetCellValue('K1', '91-120');
				$objPHPExcel->getActiveSheet()->SetCellValue('L1', '121-150');
				$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Over 150');

                $zeros = array('0000', '000', '00', '0', '');
                $grand_total = array('total' => 0, 'received' => 0, '1-30' => 0, '31-60' => 0, '61-90' => 0, '91-120' => 0, '121-150' => 0, '150-' => 0);

				PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
				$i = 2;
				foreach ($result as $bills) {
                    $total = array('total' => 0, 'received' => 0, '1-30' => 0, '31-60' => 0, '61-90' => 0, '91-120' => 0, '121-150' => 0, '150-' => 0);
                    foreach ($bills as $row) {
						$received = empty($row->amount_received) ? 0 : $row->amount_received;
                        $remaining = empty($row->outstanding) ? $row->total_amount : $row->outstanding;
                        $date1 = $row->bill_date;
                        $date2 = time();
                        $datediff = $date2 - $date1;
                        $days = floor($datediff / (60 * 60 * 24));

                        if ($remaining <= 0)
                            continue;

                        $grand_total['total'] = $grand_total['total'] + $remaining;
                        $grand_total['received'] = $grand_total['received'] + $received;
                        if ($days >= 1 && $days <= 30) {
                            $grand_total['1-30'] = $grand_total['1-30'] + $remaining;
                        } elseif ($days >= 31 && $days <= 60) {
                            $grand_total['31-60'] = $grand_total['31-60'] + $remaining;
                        } elseif ($days >= 61 && $days <= 90) {
                            $grand_total['61-90'] = $grand_total['61-90'] + $remaining;
                        } elseif ($days >= 91 && $days <= 120) {
                            $grand_total['91-120'] = $grand_total['91-120'] + $remaining;
                        } elseif ($days >= 121 && $days <= 150) {
                            $grand_total['121-150'] = $grand_total['121-150'] + $remaining;
                        } elseif ($days >= 150) {
                            $grand_total['150-'] = $grand_total['150-'] + $remaining;
                        }

                        $total['total'] = $total['total'] + $remaining;
                        $total['received'] = $total['received'] + $received;
                        if ($days >= 1 && $days <= 30) {
                            $total['1-30'] = $total['1-30'] + $remaining;
                        } elseif ($days >= 31 && $days <= 60) {
                            $total['31-60'] = $total['31-60'] + $remaining;
                        } elseif ($days >= 61 && $days <= 90) {
                            $total['61-90'] = $total['61-90'] + $remaining;
                        } elseif ($days >= 91 && $days <= 120) {
                            $total['91-120'] = $total['91-120'] + $remaining;
                        } elseif ($days >= 121 && $days <= 150) {
                            $total['121-150'] = $total['121-150'] + $remaining;
                        } elseif ($days >= 150) {
                            $total['150-'] = $total['150-'] + $remaining;
                        }


						// Add some data
						$objPHPExcel->setActiveSheetIndex(0);
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row->billto_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $row->segment_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $row->partner_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $row->manager_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, date('d/m/Y', $row->bill_date));
						//$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, " " . $zeros[strlen($row->bill_no)] . $row->bill_no . '/' . date('y', $row->bill_date) . " ");
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, " " . $row->bill_no_str . " ");
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $remaining);
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, ($days >= 1 && $days <= 30 ? $remaining : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, ($days >= 31 && $days <= 60 ? $remaining : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, ($days >= 61 && $days <= 90 ? $remaining : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ($days >= 91 && $days <= 120 ? $remaining : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, ($days >= 121 && $days <= 150 ? $remaining : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, ($days >= 151 ? $remaining : ''));

						$format_code = "###,###,###,###";
						$excel_colum = array('G', 'H', 'I', 'J', 'K', 'L', 'M');
						foreach ($excel_colum as $format_column) {
							$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
						}
						$i++;
					}
					
					$__total = isset($total['total']) ? number_format($total['total']) : 0;
					if ($__total > 0) {
						// Add some data
						$objPHPExcel->setActiveSheetIndex(0);
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row->billto_name . ' Total');
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, (isset($total['total']) ? $total['total'] : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, (isset($total['1-30']) && $total['1-30'] > 0 ? $total['1-30'] : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, (isset($total['31-60']) && $total['31-60'] > 0 ? $total['31-60'] : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, (isset($total['61-90']) && $total['61-90'] > 0 ? $total['61-90'] : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, (isset($total['91-120']) && $total['91-120'] > 0 ? $total['91-120'] : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, (isset($total['121-150']) && $total['121-150'] > 0 ? $total['121-150'] : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, (isset($total['151-']) && $total['151-'] > 0 ? $total['151-'] : ''));

						$format_code = "###,###,###,###";
						$excel_colum = array('G', 'H', 'I', 'J', 'K', 'L', 'M');
						foreach ($excel_colum as $format_column) {
							$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
						}
						$i++;
						$i++;
					}
				}
				// Rename sheet
				//$objPHPExcel->getActiveSheet()->setTitle('Bill Outstanding');

				// Save Excel 2007 file
				$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

				// We'll be outputting an excel file
				header('Content-type: application/vnd.ms-excel');
				header('Content-Disposition: attachment; filename="Bill Outstanding - ' . date('d/m/Y') . '.xlsx"');
				$objWriter->save('php://output');
			}
            else if ($action == "print") {
//                echo PDF_PAGE_FORMAT; exit;
                $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

                // remove default header/footer
                $pdf->setPrintHeader(false);
                $pdf->setPrintFooter(false);
                // set auto page breaks
                $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM - 2);

                // set font
                $pdf->SetFont('dejavusans', '', 7);
//                $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
                // add a page
                $pdf->AddPage('L', 'A4');

                $html = '
                    <table cellpadding="4" cellspacing="0" border="1">
                        <tr>
                            <th width="175">Client Name</th>
                            <th width="55">Segment</th>
                            <th width="42">Partner</th>
                            <th width="42">Manager</th>
                            <th width="55">Bill Date</th>
                            <th width="45">Bill No</th>
                            <th width="55">Outstanding</th>
                            <th width="55">1-30</th>
                            <th width="55">31-60</th>
                            <th width="55">61-90</th>
                            <th width="55">91-120</th>
                            <th width="55">121-150</th>
                            <th width="55">Over 150</th>
                        </tr>';

                $zeros = array('0000', '000', '00', '0', '');
                $grand_total = array('total' => 0, 'received' => 0, '1-30' => 0, '31-60' => 0, '61-90' => 0, '91-120' => 0, '121-150' => 0, '150-' => 0);

                foreach ($result as $bills) {
                    $i = 0;
                    $total = array('total' => 0, 'received' => 0, '1-30' => 0, '31-60' => 0, '61-90' => 0, '91-120' => 0, '121-150' => 0, '150-' => 0);
                    foreach ($bills as $row) {
                        $received = empty($row->amount_received) ? 0 : $row->amount_received;
                        $remaining = empty($row->outstanding) ? $row->total_amount : $row->outstanding;
                        $date1 = $row->bill_date;
                        $date2 = time();
                        $datediff = $date2 - $date1;
                        $days = floor($datediff / (60 * 60 * 24));

                        if ($remaining <= 0)
                            continue;

                        $grand_total['total'] = $grand_total['total'] + $remaining;
                        $grand_total['received'] = $grand_total['received'] + $received;
                        if ($days >= 1 && $days <= 30) {
                            $grand_total['1-30'] = $grand_total['1-30'] + $remaining;
                        } elseif ($days >= 31 && $days <= 60) {
                            $grand_total['31-60'] = $grand_total['31-60'] + $remaining;
                        } elseif ($days >= 61 && $days <= 90) {
                            $grand_total['61-90'] = $grand_total['61-90'] + $remaining;
                        } elseif ($days >= 91 && $days <= 120) {
                            $grand_total['91-120'] = $grand_total['91-120'] + $remaining;
                        } elseif ($days >= 121 && $days <= 150) {
                            $grand_total['121-150'] = $grand_total['121-150'] + $remaining;
                        } elseif ($days >= 150) {
                            $grand_total['150-'] = $grand_total['150-'] + $remaining;
                        }

                        $total['total'] = $total['total'] + $remaining;
                        $total['received'] = $total['received'] + $received;
                        if ($days >= 1 && $days <= 30) {
                            $total['1-30'] = $total['1-30'] + $remaining;
                        } elseif ($days >= 31 && $days <= 60) {
                            $total['31-60'] = $total['31-60'] + $remaining;
                        } elseif ($days >= 61 && $days <= 90) {
                            $total['61-90'] = $total['61-90'] + $remaining;
                        } elseif ($days >= 91 && $days <= 120) {
                            $total['91-120'] = $total['91-120'] + $remaining;
                        } elseif ($days >= 121 && $days <= 150) {
                            $total['121-150'] = $total['121-150'] + $remaining;
                        } elseif ($days >= 150) {
                            $total['150-'] = $total['150-'] + $remaining;
                        }

                        $html .= '
                        <tr>
                            <td>' . $row->billto_name . '</td>
                            <td>' . $row->segment_name . '</td>
                            <td>' . $row->partner_name . '</td>
                            <td>' . $row->manager_name . '</td>
                            <td>' . date('d/m/Y', $row->bill_date) . '</td>
                            <td>' . $zeros[strlen($row->bill_no)] . $row->bill_no . '/' . date('y', $row->bill_date) . '</td>
                            <td>' . number_format($remaining) . '</td>
                            <td>' . ($days >= 1 && $days <= 30 ? number_format($remaining) : '') . '</td>
                            <td>' . ($days >= 31 && $days <= 60 ? number_format($remaining) : '') . '</td>
                            <td>' . ($days >= 61 && $days <= 90 ? number_format($remaining) : '') . '</td>
                            <td>' . ($days >= 91 && $days <= 120 ? number_format($remaining) : '') . '</td>
                            <td>' . ($days >= 121 && $days <= 150 ? number_format($remaining) : '') . '</td>
                            <td>' . ($days >= 151 ? number_format($remaining) : '') . '</td>
                        </tr>';
                        $i++;
                    }

                    $__total = isset($total['total']) ? number_format($total['total']) : 0;
                    if ($__total > 0) {
                        $html .= '
                        <tr style="font-weight: bold;">
                            <td colspan="6"><strong>' . $row->billto_name . ' Total</strong></td>
                            <td>' . (isset($total['total']) ? number_format($total['total']) : '') . '</td>
                            <td>' . (isset($total['1-30']) && $total['1-30'] > 0 ? number_format($total['1-30']) : '') . '</td>
                            <td>' . (isset($total['31-60']) && $total['31-60'] > 0 ? number_format($total['31-60']) : '') . '</td>
                            <td>' . (isset($total['61-90']) && $total['61-90'] > 0 ? number_format($total['61-90']) : '') . '</td>
                            <td>' . (isset($total['91-120']) && $total['91-120'] > 0 ? number_format($total['91-120']) : '') . '</td>
                            <td>' . (isset($total['121-150']) && $total['121-150'] > 0 ? number_format($total['121-150']) : '') . '</td>
                            <td>' . (isset($total['151-']) && $total['151-'] > 0 ? number_format($total['151-']) : '') . '</td>
                        </tr>';
                    }
                }

                /* for ($i = 0; $i < 50; $i++) {
                  $html .= '
                  <tr>
                  <td>' . $i . '</td>
                  <td>Segment</td>
                  <td>Partner</td>
                  </tr>';
                  } */

                $html .= '
                    </table>
                    ';
//echo $html; exit;
                // output the HTML content
                $pdf->writeHTML($html, true, false, true, false, '');
                $pdf->Output('example_006.pdf', 'I');
                exit;
                echo '<pre>';
                print_r($result);
                exit;
            }
			$data['age_filter'] = $age_filter;
			$data['age_ranges'] = $age_ranges;
            $data['result'] = $result;
            $data['page'] = 'reports/builder/bills-outstanding-cw';
        }
        $this->load->view('layout/v2/default', $data);
    }
	
	//// ------------------------------------/////
	
	
	public function outstanding2($action = '') {
        
		// Temporary Update Query - Do not Open IT.
		/*$query = mysql_query("SELECT id, DATE FROM receipts");
		while($row = mysql_fetch_array($query)){
			//echo "UPDATE receipts_data SET receipt_date = '".$row['DATE']."' WHERE receipt_id = '".$row['id']."'"; die;
			mysql_query("UPDATE receipts_data SET receipt_date = '".$row['DATE']."' WHERE receipt_id = '".$row['id']."'");
		}*/
		
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
		
		$data = array(
            'page' => 'reports/outstanding2',
			'collectors' => $this->Users_model->getCollectors(),
            'managers' => $this->Users_model->getUserByRoleName('Manager'),
			'sr_managers' => $this->Users_model->getUserByRoleName('SM_ED'),
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
            'segments' => $this->Segments_model->getAllSegments(0),
			'branches' => $this->Branches_model->getAllBranch(),
			'all_segments' => json_encode($all_segments),
			'variables' => $this->variables,
			'settings' => $this->Settings_model->getBranchSettings($this->user['branch_id']),
            'outstanding' => array(),
        );
		
        if (isset($_REQUEST['search-bills-submit'])) {
            $bills = $this->Reports_model->getOutstanding();
			$result = array();
			foreach ($bills as $bill) {
                $result[$bill->client_id][] = $bill;
            }
			
			if (isset($_REQUEST['fields']['clients-collector'])) {
				$bills2 = $this->Reports_model->getOutstanding2();
				$result2 = array();
				foreach ($bills2 as $bill2) {
					$result2[$bill2->client_id][] = $bill2;
				}
				$data['result2'] = $result2;
			}
            
            $data['result'] = $result;
            $data['page'] = 'reports/outstanding2';
        }
        $this->load->view('layout/v2/default', $data);
    }
	
	///// ---------------------------///////
	

    public function receipt_register($action = '') {
        if ($action == "excel") {
            /** Include path * */
            $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
            //ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

            /** PHPExcel */
            include_once $basepath . 'PHPExcel.php';

            /** PHPExcel_Writer_Excel2007 */
            include_once $basepath . 'PHPExcel/Writer/Excel2007.php';

            // Create new PHPExcel object
            //$objPHPExcel = new PHPExcel();
			
			// Load Excel Template
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/receipt_register_template.xlsx");

            // Set properties
            $objPHPExcel->getProperties()->setCreator("Earnst & Young");
            $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
            $objPHPExcel->getProperties()->setTitle("Receipt Register");
            $objPHPExcel->getProperties()->setSubject("Receipt Register");
            $objPHPExcel->getProperties()->setDescription("Receipt Register");

            // Add some data
            $objPHPExcel->setActiveSheetIndex(0);
			
			$main_heading = '';
			
			// Bank Name Heading
			if (isset($_REQUEST['fields']['clients-bank_name'])) {
				$j = 0;
				foreach ($_REQUEST['fields']['clients-bank_name'] as $bank_id) {
					$bank = $this->Banks_model->getBankByID($bank_id);		
					if($j > 0) $main_heading .= ', ';
					$main_heading .= $bank->name;
					$j++;
				}
			}
			
			// Date Heading
			if (isset($_REQUEST['fields']['receipts-date'])) {
				$receipt_date_from = $_REQUEST['fields']['receipts-date']['from'];
				$receipt_date_to = $_REQUEST['fields']['receipts-date']['to'];
				if (!empty($receipt_date_from) && !empty($receipt_date_to)) {
					if($main_heading != '') $main_heading .= ' - ';
					$main_heading .= 'From '.$receipt_date_from.' To '.$receipt_date_to;
				} else if (!empty($receipt_date_from)) {
					if($main_heading != '') $main_heading .= ' - ';
					$main_heading .= 'From '.$receipt_date_from;
				} else if (!empty($receipt_date_to)) {
					if($main_heading != '') $main_heading .= ' - ';
					$main_heading .= 'Till '.$receipt_date_to;
				}
			}
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', $main_heading);
			
            $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Date');
			$objPHPExcel->getActiveSheet()->SetCellValue('B3', 'Client Name');
			$objPHPExcel->getActiveSheet()->SetCellValue('C3', 'Receipt #');
            $objPHPExcel->getActiveSheet()->SetCellValue('D3', 'Payment Mode');
            $objPHPExcel->getActiveSheet()->SetCellValue('E3', 'Doc No');
            $objPHPExcel->getActiveSheet()->SetCellValue('F3', 'Doc Date');
            $objPHPExcel->getActiveSheet()->SetCellValue('G3', 'Currency');
            $objPHPExcel->getActiveSheet()->SetCellValue('H3', 'Deposit Bank');
            $objPHPExcel->getActiveSheet()->SetCellValue('I3', 'Amount Received');
			$objPHPExcel->getActiveSheet()->SetCellValue('J3', 'Amount Receipt USD Bank');
            $objPHPExcel->getActiveSheet()->SetCellValue('K3', 'WHT Deducted');
			$objPHPExcel->getActiveSheet()->SetCellValue('L3', 'SST Deducted');
            $objPHPExcel->getActiveSheet()->SetCellValue('M3', 'Bank Charges');
            $objPHPExcel->getActiveSheet()->SetCellValue('N3', 'Foreign Exchange');
			$objPHPExcel->getActiveSheet()->SetCellValue('O3', 'Others');
			$objPHPExcel->getActiveSheet()->SetCellValue('P3', 'Write off');
            $objPHPExcel->getActiveSheet()->SetCellValue('Q3', 'Total Amount');
            $objPHPExcel->getActiveSheet()->SetCellValue('R3', 'Total Adjusted');
            $objPHPExcel->getActiveSheet()->SetCellValue('S3', 'Unadjusted');
			$objPHPExcel->getActiveSheet()->SetCellValue('T3', 'Settled Bill No. & Amount');
			$objPHPExcel->getActiveSheet()->SetCellValue('U3', 'Remarks');

            $zeros = array('0000', '000', '00', '0', '');
            $receipts = $this->Receipts_model->getAllReceiptsAndData();

            PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
            
			$format_code = "###,###,###,###";
			$excel_colum = array('I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S');
			
			$i = 4;
			foreach ($receipts as $receipt) {
                
                $receipt_total = 0;

				if($receipt->doc_date){
					$doc_date = date('d/m/Y', $receipt->doc_date);
				} else {
					$doc_date = '';
				}

                $client_name22 = isset($receipt->detail[0]) ? $receipt->detail[0]->client_name : '';
                $client_name22 = !empty($receipt->client_name) ? $receipt->client_name : $client_name22;
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, date('d/m/Y', $receipt->date));
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $client_name22);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $zeros[strlen($receipt->receipt_no)] . $receipt->receipt_no . '/' . date('y', $receipt->date));
                $objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, ucwords($receipt->payment_mode));
                $objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $receipt->doc_no);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $doc_date);
                $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $receipt->currency_title);
                $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $receipt->bank_name);
                $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $receipt->amount_received);
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $receipt->charges_others);				
                $objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $receipt->tax_deduction);
                $objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $receipt->sindh_gov_tax);
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $receipt->bank_charges);
                $objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $receipt->exchange_fc);
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, $receipt->others2);
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, $receipt->writeoff);                
                $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, $receipt->total_received);
                $objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, $receipt->total_adjusted);
                $objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, $receipt->total_received - $receipt->total_adjusted);
                $j = 0;
				$receipt_data_str = '';
				foreach ($receipt->detail as $data) {      
					if($j > 0){
						$receipt_data_str .= ', ';
					}
					$receipt_data_str .= '['.$data->bill_no_str.', '.number_format($data->amount).']';
                    $j++;
                }
				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, $receipt_data_str);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, $receipt->remarks);
				
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
				}
				
				$objPHPExcel->getActiveSheet()->getStyle('A'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
					
				$objPHPExcel->getActiveSheet()->getStyle('F'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
				
                $i++;
            }
            $i++;
            
			//Print Totals With SUM formula
			/*$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '=SUM(I4:I'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUM(J4:J'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUM(K4:K'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUM(L4:L'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUM(M4:M'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUM(N4:N'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUM(O4:O'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUM(P4:P'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUM(Q4:Q'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUM(R4:R'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUM(S4:S'.($i-2).')');*/
			// With SubTotal Formula
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '=SUBTOTAL(9,I4:I'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUBTOTAL(9,J4:J'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUBTOTAL(9,K4:K'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUBTOTAL(9,L4:L'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUBTOTAL(9,M4:M'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUBTOTAL(9,N4:N'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUBTOTAL(9,O4:O'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUBTOTAL(9,P4:P'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUBTOTAL(9,Q4:Q'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUBTOTAL(9,R4:R'.($i-2).')');
			$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUBTOTAL(9,S4:S'.($i-2).')');
			
            foreach ($excel_colum as $format_column) {
                $objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
            }
			
			// Apply Border Lines on Total Amounts
			$styleArray = array(
				'font' => array(
					'bold' => true,
				),
				'borders' => array(
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					),
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THICK
					)
				)
			);
			foreach ($excel_colum as $format_column) {
                $objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->applyFromArray($styleArray);
            }

            // Rename sheet
            //$objPHPExcel->getActiveSheet()->setTitle('Receipt Register');

            // Save Excel 2007 file
            $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

            // We'll be outputting an excel file
            header('Content-type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Receipt Register - ' . date('d/m/Y') . '.xlsx"');
            $objWriter->save('php://output');
			
        } else {

            $data = array(
                'page' => 'reports/receipt-register',
                'banks' => $this->Banks_model->getAllBanks(),
                'receipts' => array(),
            );

            if (isset($_REQUEST['search-bills-submit'])) {
                $data['receipts'] = $this->Receipts_model->getAllReceiptsAndData();
            }
            $this->load->view('layout/v2/default', $data);
        }
    }

    function builder2() {
        if (isset($_POST['generate'])) {
            $serialize = serialize($_POST);
            if ($_POST['reports'] == "bills-outstanding") {
                $result = $this->Reports_model->outstaning();
            } elseif ($_POST['reports'] == "bills-outstanding-cw") {
                $bills = $this->Reports_model->outstaning();
                $result = array();
                foreach ($bills as $bill) {
                    $result[$bill->client_id][] = $bill;
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

        $data = array(
            'page' => 'reports/builder2',
            'branches' => $this->Branches_model->getAllBranch(),
            'segments' => $this->Segments_model->getAllSegments(0),
            'all_segments' => json_encode($all_segments),
            'result' => isset($result) ? $result : array(),
            'report' => isset($_POST['reports']) ? $_POST['reports'] : '',
            'serialize' => isset($serialize) ? $serialize : ''
        );
        $this->load->view('layout/v2/default', $data);
    }

    function builder() {
        // To store all tables and their columns details
        $table_details = array();

        // Getting list of tables from db
        $tables = $this->db->query("SHOW TABLES");

        // Loop through all the tables and store their details in $tables_details varaible
        foreach ($tables->result_array() as $table) {
            // Get only 1 record at a time, end function will return table name
            $table = end($table);

            // Getting list of columns from the table
            $columns = $this->db->query("SHOW COLUMNS FROM {$table}");

            // Storing table name in $table_details > tables variable
            $table_name = ucwords(str_replace('_', ' ', $table));
            $table_details['tables'][$table] = array('table' => $table, 'name' => $table_name);

            // Loop through all the columns in table
            foreach ($columns->result_array() as $column) {
                // Storing columns detail in $tables_detail > details > table name variable
                $table_details['details'][$table][$column['Field']] = $column;
            }
        }

        $data = array(
            'page' => 'reports/builder',
            'table_details' => $table_details,
        );
        $this->load->view('layout/v2/default', $data);
    }

    public function bill_register() {
		
		// Generating data for segments
        $all_segments = array();
        $segments = $this->Segments_model->getAllSegments();
        foreach ($segments as $segment) {
			
			if(in_array($segment->id, $this->userSegments) || ($this->user['short_code'] == 'admin')){
		
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
		
		
        $data = array(
            'page' => 'reports/bill-register',
			'managers' => $this->Users_model->getUserByRoleName('Manager'),
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
			'sr_managers' => $this->Users_model->getUserByRoleName('SM_ED'),
            'segments' => $this->Segments_model->getAllSegments(0),
			'generated_reports' => $this->Reports_model->getAllGeneratedReports('bill_register_excel'),
			'all_segments' => json_encode($all_segments),
			'variables' => $this->variables,
            'bills' => isset($_REQUEST['search-bills-submit']) ? $this->Reports_model->getAllBillsM(array('bill','memo','debit','credit')) : array(),
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	public function generate_bill_register_data($user_id = '') {
		
		$khi_db = mysql_connect('localhost','root',$this->db->password) or die(mysql_error());
		mysql_select_db($this->db->database, $khi_db) or die(mysql_error());
		
		$session_id = time().'_'.rand();
		$report_date = date("d M Y").', Time '.date("h:i:sa");
		
		if (isset($_REQUEST['search-bills-submit'])) {
			
			$report_parameters = '';
			
            foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
				if (empty($field_value) || sizeof($field_value) <= 0)
                	continue;
				
				// Filter by Bill No.
				if ($field_name == 'bills-bill_no') {
					$bill_no_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_no_to = $_REQUEST['fields'][$field_name]['to'];
					
					if($bill_no_from != ''){
					
						$temp = explode('/', $bill_no_from);
						if (sizeof($temp) > 1) {
							$bill_no_from = $temp[0];
						}
		
						$temp = explode('/', $bill_no_to);
						if (sizeof($temp) > 1) {
							$bill_no_to = $temp[0];
						}
		
						$report_parameters .= 'Bill Nos: <b>'. $bill_no_from.' to '.$bill_no_to.'</b>, ';
					}
				}
				// Filter bills by bill date
				else if ($field_name == 'bills-bill_date') {
					$bill_date_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_date_to = $_REQUEST['fields'][$field_name]['to'];
					$report_parameters .= 'Bill Date: <b>'. $bill_date_from.' to '.$bill_date_to.'</b>, ';
				}
				
				// Filter by segment
				else if ($field_name == 'clients-segments') {
					
					if(in_array('All',$field_value)){
						// --- DO NOTHING, SELECT ALL SEGMENTS -- //
						$report_parameters .= 'Segments: <b>ALL</b>, ';
					} else {
						$segments = array();
						foreach ($field_value as $semgnet_id) {
							$segment_name = $this->Segments_model->getSegmentName($semgnet_id);
							$segments[] = "'{$segment_name}'";
						}
						$report_parameters .= 'Segment: <b>'. implode(',', $segments).'</b>, ';
					}
				}
				
				// Filter by Service-1
				else if ($field_name == 'service1') {
					$service1 = array();
					foreach ($field_value as $service1_id) {
						$service1_name = $this->Segments_model->getSegmentName($service1_id);
						$service1[] = "'{$service1_name}'";
					}
	
					if($service1_id != '0'){
						$report_parameters .= 'Service-1: <b>'. implode(',', $service1).'</b>, ';
					}
				}
				
				// Filter bills by Client
				else if ($field_name == 'clients-name') {
					$clients = array();
					foreach ($field_value as $clientm_id) {
						$client_names = $this->Clients_model->getClientsNameByID($clientm_id);
						$clients[] = "'{$client_names}'";
					}
					$report_parameters .= 'Client: <b>'. implode(',', $clients).'</b>, ';
					
				}
				
				// Filter by partner
				else if ($field_name == 'clients-partners') {
					$partners = array();
					foreach ($field_value as $partner_id) {
						$partner_names = $this->Users_model->getUserShortCode($partner_id);
						$partners[] = "'{$partner_names}'";
					}
					$report_parameters .= 'Partner: <b>'. implode(',', $partners).'</b>, ';
				}
				// Filter by Sr.Manager
				else if ($field_name == 'clients-sr_managers') {
					$sr_managers = array();
					foreach ($field_value as $sr_managers_id) {
						$sm_names = $this->Users_model->getUserShortCode($sr_managers_id);
						$sr_managers[] = "'{$sm_names}'";
					}
					$report_parameters .= 'ED/SM: <b>'. implode(',', $sr_managers).'</b>, ';
				}
				// Filter by Manager
				else if ($field_name == 'clients-managers') {
					$managers = array();
					foreach ($field_value as $manager_id) {
						$manager_names = $this->Users_model->getUserShortCode($manager_id);
						$managers[] = "'{$manager_names}'";
					}
					$report_parameters .= 'Manager: <b>'. implode(',', $managers).'</b>, ';
				}
				
				// Filter bills by bill date
				if ($field_name == 'branch-name2') {
					
					if(in_array('All',$field_value)){
						// --- DO NOTHING, SELECT ALL SEGMENTS -- //
						$report_parameters .= 'Branches: <b>ALL</b>, ';
					}
					else {
						foreach ($field_value as $branch) {
							$branch_names = $this->Branches_model->getBranchNameByID($branch);
							$branches[] = "'{$branch_names}'";
							$branch_id = $branch;
							$report_parameters .= 'Branch: <b>'. implode(',', $branches).'</b>';
						}
					}
					
				}
			}
        }
		
		$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
		$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
		$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
		$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
		
		foreach ($_REQUEST['fields'] as $field_name => $field_value) {
				
			if ($field_name == 'branch-name2') {
			
				if(in_array('All',$field_value)){
					// --- JUST SELECT ALL Branches ---- //
				} else {
					unset($branch_arr);
					foreach ($field_value as $branch_id) {
						if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
						if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
						if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
						if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
					}
				}
			}
			
		}
		
		foreach($branch_arr as $branch_id => $branch_name){
		
			$data = $this->Reports_model->getBillDataAndAllocation(true, $user_id, $branch_id);
			
			$i = 2;
			foreach ($data as $row) {
			
				$bill_no_str = $row['bill_no_str'];
				if($i == 2){
					$bill_no_prev = $bill_no_str;
					$bill_no_next = $bill_no_str;
				} else {
					$bill_no_prev = $bill_no_next;
					$bill_no_next = $bill_no_str;
					if($bill_no_prev==$bill_no_next && $row['type']=='bill'){ continue; }
				}
				 
				$client_info = $this->Clients_model->getClientsDetailsByID($row['client_id']);
				
				$branch = $row['branch_name'];
				if($row['credit_note'] == 1 && $row['credit_note_type'] == 'R') $bill_no_str .= '(R)';
				//$client_name = is_object($bill_info) ? $bill_info->client_name : '';
				$client_name = $client_info['client_name'];
				//$bill_date = $bill_info->bill_date;
				$bill_date = $row['bill_date'];
				$project_name = $row['project_name'];
				$segment = $row['segment'];
				$service1 = $row['service1'];
				
				$short_description = ($row['type']=="debit" ? $row['short_description'] : $row['remarks']);
				if($row['type']=="credit"){
					$short_description .= ' [Bill# '.$row['credit_bill_no'].']';
				}
				$partner = $row['partner'];
				$sr_manager = $row['sr_manager'];
				$manager = $row['manager'];
				if($row['type']=='bill'){
					$fee = $row['total_fees']*$row['currency_rate'];
					$oop = $row['total_oop']*$row['currency_rate'];
					$others = $row['total_others']*$row['currency_rate'];
					$total = $row['total_amount']*$row['currency_rate'];
				} else {
					$fee = $row['fee'];
					$oop = $row['oop'];
					$others = $row['others'];
					$total = $row['total'];
				}
				$sst_rate = $row['sst_rate'];
				$currency_symbol = $row['currency_symbol'];
				$currency_rate = $row['currency_rate'];
				$total_fc = $row['total_fc'];			
				$outstanding = $row['outstanding'];
				if($row['type'] == 'credit'){ 
					$outstanding = 0;
				}
				$payment_mode = $row['payment_mode'];
				$doc_no = $row['doc_no'];
				$receipt_date = $row['receipt_date'];
				$code = $client_info['code'];
				$gl_code = $client_info['gl_code'];
				$client_ntn = $client_info['ntn'];
				
				$industry = $client_info['by_industry'];
				$sector = $client_info['sector'];
				$sub_sector = $client_info['sub_sector'];
				$g360 = $client_info['g360'];
				
				$month = date('m', $bill_date);
				/*$month = date('m', $bill_info->bill_date);
				$client_ntn = is_object($bill_info) ? $bill_info->client_ntn : '';*/
							
				// Put Tooltip On Bill# Cell.
				if ($row['type'] == "debit") {
					$cell_comment = $row['short_description'];
				
				} else if ($row['type'] == "credit") {
					$cell_comment = "Credit note against Bill# ".$row['credit_bill_no'];
				} else {
					$cell_comment = $row['fee_description'];	
				}
				
				$sql = "INSERT INTO bill_register_excel (
								session_id, 
								branch, bill_no, 
								client_name, bill_date,
								project_name, segment, service1,
								short_description, partner, 
								sr_manager, manager,
								fee, oop, others, sst_rate, total,
								currency_symbol, currency_rate, total_fc, 
								outstanding,
								payment_mode, doc_no, receipt_date, 
								code, gl_code, 
								month, client_ntn, 
								report_date,
								cell_comment, report_parameters,
								industry, sector, sub_sector, g360)
						VALUES (
								'".$session_id."', 
								'".$branch."', 
								'".$bill_no_str."', 
								'".addslashes($client_name)."',
								'".$bill_date."',
								'".$project_name."', 
								'".$segment."',
								'".$service1."',
								'".addslashes($short_description)."', 
								'".$partner."', '".$sr_manager."', '".$manager."',
								'".$fee."', '".$oop."', '".$others."', '".$sst_rate."', '".$total."',
								'".$currency_symbol."', '".$currency_rate."', '".$total_fc."',
								'".$outstanding."',
								'".$payment_mode."', '".$doc_no."', '".$receipt_date."', 
								'".$code."', '".$gl_code."', 
								'".$month."', '".$client_ntn."', 
								'".$report_date."', '".addslashes($cell_comment)."', '".addslashes($report_parameters)."',
								'".addslashes($industry)."',
								'".addslashes($sector)."',
								'".addslashes($sub_sector)."',
								'".addslashes($g360)."'
							)";
			
				mysql_query($sql, $khi_db) or die(mysql_error());
				
				$i++;
			}
		}
		
		redirect('reports/bill_register');

    }
	
	public function generate_bill_register_data_short() {
		
		$khi_db = mysql_connect('localhost','root',$this->db->password) or die(mysql_error());
		mysql_select_db($this->db->database, $khi_db) or die(mysql_error());
		
		$session_id = time().'_'.rand();
		$report_date = date("d M Y").', Time '.date("h:i:sa");
		
		$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
		$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
		$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
		$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
		
		
		if (isset($_REQUEST['search-bills-submit'])) {
			
			$report_parameters = '';
			
            foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
				if (empty($field_value) || sizeof($field_value) <= 0)
                	continue;
				
				// Filter by Bill No.
				if ($field_name == 'bills-bill_no') {
					$bill_no_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_no_to = $_REQUEST['fields'][$field_name]['to'];
					
					if($bill_no_from != ''){
					
						$temp = explode('/', $bill_no_from);
						if (sizeof($temp) > 1) {
							$bill_no_from = $temp[0];
						}
		
						$temp = explode('/', $bill_no_to);
						if (sizeof($temp) > 1) {
							$bill_no_to = $temp[0];
						}
		
						$report_parameters .= 'Bill Nos: <b>'. $bill_no_from.' to '.$bill_no_to.'</b>, ';
					}
				}
				// Filter bills by bill date
				else if ($field_name == 'bills-bill_date') {
					$bill_date_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_date_to = $_REQUEST['fields'][$field_name]['to'];
					$report_parameters .= 'Bill Date: <b>'. $bill_date_from.' to '.$bill_date_to.'</b>, ';
				}
				
				// Filter by segment
				else if ($field_name == 'clients-segments') {
					
					if(in_array('All',$field_value)){
						// --- DO NOTHING, SELECT ALL SEGMENTS -- //
						$report_parameters .= 'Segments: <b>ALL</b>, ';
					} else {
						$segments = array();
						foreach ($field_value as $semgnet_id) {
							$segment_name = $this->Segments_model->getSegmentName($semgnet_id);
							$segments[] = "'{$segment_name}'";
						}
						$report_parameters .= 'Segment: <b>'. implode(',', $segments).'</b>, ';
					}
				}
				
				// Filter by Service-1
				else if ($field_name == 'service1') {
					$service1 = array();
					foreach ($field_value as $service1_id) {
						$service1_name = $this->Segments_model->getSegmentName($service1_id);
						$service1[] = "'{$service1_name}'";
					}
	
					if($service1_id != '0'){
						$report_parameters .= 'Service-1: <b>'. implode(',', $service1).'</b>, ';
					}
				}
				
				// Filter bills by Client
				else if ($field_name == 'clients-name') {
					$clients = array();
					foreach ($field_value as $clientm_id) {
						$client_names = $this->Clients_model->getClientsNameByID($clientm_id);
						$clients[] = "'{$client_names}'";
					}
					$report_parameters .= 'Client: <b>'. implode(',', $clients).'</b>, ';
					
				}
				
				// Filter by partner
				else if ($field_name == 'clients-partners') {
					$partners = array();
					foreach ($field_value as $partner_id) {
						$partner_names = $this->Users_model->getUserShortCode($partner_id);
						$partners[] = "'{$partner_names}'";
					}
					$report_parameters .= 'Partner: <b>'. implode(',', $partners).'</b>, ';
				}
				// Filter by Sr.Manager
				else if ($field_name == 'clients-sr_managers') {
					$sr_managers = array();
					foreach ($field_value as $sr_managers_id) {
						$sm_names = $this->Users_model->getUserShortCode($sr_managers_id);
						$sr_managers[] = "'{$sm_names}'";
					}
					$report_parameters .= 'ED/SM: <b>'. implode(',', $sr_managers).'</b>, ';
				}
				// Filter by Manager
				else if ($field_name == 'clients-managers') {
					$managers = array();
					foreach ($field_value as $manager_id) {
						$manager_names = $this->Users_model->getUserShortCode($manager_id);
						$managers[] = "'{$manager_names}'";
					}
					$report_parameters .= 'Manager: <b>'. implode(',', $managers).'</b>, ';
				}
				
				// Filter bills by bill date
				if ($field_name == 'branch-name2') {
					
					if(in_array('All',$field_value)){
						// --- DO NOTHING, SELECT ALL Branches -- //
						$report_parameters .= 'Branches: <b>ALL</b>, ';
					}
					else {
						unset($branch_arr);
						foreach ($field_value as $branch_id) {
							if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
							if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
							if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
							if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
						}
						$report_parameters .= 'Branch: <b>'. implode(',', $branch_arr).'</b>, ';
					}
					
				}
			}
        }
		
		
		/*foreach ($_REQUEST['fields'] as $field_name => $field_value) {
				
			if ($field_name == 'branch-name2') {
			
				if(in_array('All',$field_value)){
					// --- JUST SELECT ALL Branches ---- //
					$report_parameters .= 'Branches: <b>ALL</b>, ';
				} else {
					unset($branch_arr);
					foreach ($field_value as $branch_id) {
						if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
						if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
						if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
						if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
					}
				}
			}
			
		}*/
	
		foreach($branch_arr as $branch_id => $branch_name){
		
			$data = $this->Reports_model->getBillRegisterData($branch_id);
			
			$i = 2;
			foreach ($data as $row) {
				
				// For Kabul Only Dollar PKR Rate
				if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908'){
					$dollar_rate = $row->doller_pkr_rate;
				} else {
					$dollar_rate = 1;
				}
			
				$bill_no_str = $row->bill_no_str;
				if($i == 2){
					$bill_no_prev = $bill_no_str;
					$bill_no_next = $bill_no_str;
				} else {
					$bill_no_prev = $bill_no_next;
					$bill_no_next = $bill_no_str;
					if($bill_no_prev==$bill_no_next && $row->type=='bill') continue;
				}
							 
				$branch = $row->branch_name;
				if($row->credit_note == 1 && $row->credit_note_type == 'R') $bill_no_str .= '(R)';
				$client_name = $row->client_name;
				$bill_date = $row->bill_date;
				//$project_name = $row['project_name'];
				if($row->segment_name == ''){
					$segment = $this->Segments_model->getSegmentName($row->segment_id);
				} else {
					$segment = $row->segment_name;
				}
				
				if($row->service1_name == ''){
					$service1 = $this->Segments_model->getSegmentName($row->service1_id);
				} else {
					$service1 = $row->service1_name;
				}
				
				$short_description = ($row->type=="debit" ? $row->short_description : $row->remarks);
				
				if($row->partner_name == ''){
					$partner = $this->Users_model->getUserShortCode($row->partner_id);
				} else {
					$partner = $row->partner_name;
				}
				
				if($row->sr_manager_name == ''){
					$sr_manager = $this->Users_model->getUserShortCode($row->sr_manager_id);
				} else {
					$sr_manager = $row->sr_manager_name;
				}
				
				if($row->manager_name == ''){
					$manager = $this->Users_model->getUserShortCode($row->manager_id);
				} else {
					$manager = $row->manager_name;
				}
				
				if($row->type=='bill'){
					$fee = $row->total_fees*$row->currency_rate;
					$oop = $row->total_oop*$row->currency_rate;
					$others = $row->total_others*$row->currency_rate;
					$total = $row->total_amount*$row->currency_rate;
				} else {
					$fee = $row->fee*$row->currency_rate;
					$oop = $row->oop*$row->currency_rate;
					$others = $row->others*$row->currency_rate;
					$total = $row->total*$row->currency_rate;
				}
				
				if($row->currency_id == 'a6f0e94e-f190-5b8b-a32a-0ab0916e4b5e'){
					$fee = $fee * $dollar_rate;
					$oop = $oop * $dollar_rate;
					$others = $others * $dollar_rate;
					$total = $total * $dollar_rate;
				}
				
				$currency_symbol = $row->currency_symbol;
				$currency_rate = $row->currency_rate;
				$total_fc = $row->currency_symbol=='Rs' ? '' : $row->total;			
							
				// Put Tooltip On Bill# Cell.
				if ($row->type == "debit") {
					$cell_comment = $row->short_description;
				
				} else if ($row->type == "credit") {
					$cell_comment = "Credit note against Bill# ".$row->credit_bill_no;
				} else {
					$cell_comment = $row->description;	
				}
				
				$sql = "INSERT INTO bill_register_excel (
								session_id, 
								branch, bill_no, 
								client_name, bill_date,
								segment, service1,
								short_description, partner, 
								sr_manager, manager,
								fee, oop, others, total,
								currency_symbol, currency_rate, total_fc,  
								report_date,
								cell_comment, report_parameters)
						VALUES (
								'".$session_id."', 
								'".$branch."', 
								'".$bill_no_str."', 
								'".addslashes($client_name)."',
								'".$bill_date."',
								'".$segment."',
								'".$service1."',
								'".addslashes($short_description)."', 
								'".$partner."', '".$sr_manager."', '".$manager."',
								'".$fee."', '".$oop."', '".$others."', '".$total."',
								'".$currency_symbol."', '".$currency_rate."', '".$total_fc."',
								'".$report_date."', '".addslashes($cell_comment)."', '".addslashes($report_parameters)."'
							)";
				
				mysql_query($sql, $khi_db) or die(mysql_error());
				
				$i++;
			}
			
		}
		
		redirect('reports/bill_register');

    }

	public function export_to_excel_br($session_id = '', $export_type) {
		
		
		
		
		ini_set('memory_limit','1024M');	
		ini_set('max_execution_time', 100000);		
        /** Include path * */
        $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';

        /** PHPExcel */
        include_once $basepath . 'PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once $basepath . 'PHPExcel/Writer/Excel2007.php';

		$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/bill_register_template.xlsx");

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Earnst & Young");
        $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
        $objPHPExcel->getProperties()->setTitle("Bill Register");
        $objPHPExcel->getProperties()->setSubject("Bill Register");
        $objPHPExcel->getProperties()->setDescription("Bill Register");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Bill Register');        
		
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		
		$data = $this->Reports_model->getGeneratedReport($session_id, 'bill_register_excel');
        
		$i = 4;
		if ( $export_type == 'H' ) {
?>
			<table width="100%" border="0">
            	<th>Branch</th>
                <th>Bill No.</th>
                <th>Client</th>
                <th>Bill Date</th>
                <!--<th>Project</th>-->
                <th>Segment</th>
                <th>Service-1</th>
                <th>Short Description</th>
                <th>Partner</th>
                <th>Sr.Manager</th>
                <th>Manager</th>
                <th>Fee</th>
                <th>Oop</th>
                <th>SST</th>
                <th>Total</th>
                <th>SST Rate</th>
                <th>Currency Symbol</th>
                <th>Currency Rate</th>
                <th>Amount FC </th>
<?php
		}
		
		foreach ($data as $row) {
			
			/*$et_array = array();
			if($row->sr_manager!='' && $row->sr_manager!=NULL){
				$et_array[] = $row->sr_manager;
			}
			if($row->manager!='' && $row->manager!=NULL){
				$et_array[] = $row->manager;
			}*/
			
			if ( $export_type == 'E' ) {
				
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, " " . $row->branch . " ");
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, " " . $row->bill_no . " ");
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $row->client_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, date('d/m/Y', $row->bill_date));
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $row->project_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $row->segment);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $row->service1);
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $row->short_description);
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $row->partner);
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $row->sr_manager);
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $row->manager);
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $row->fee);
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $row->oop);
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $row->others);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, $row->total);
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, $row->sst_rate);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, $row->currency_symbol);
				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, $row->currency_rate);
				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, $row->total_fc);
				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, $row->outstanding);
				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, $row->payment_mode);
				$objPHPExcel->getActiveSheet()->SetCellValue('V' . $i, $row->doc_no);
				$objPHPExcel->getActiveSheet()->SetCellValue('W' . $i, $row->receipt_date);
				
				$pay_days_txt = $row->payment_mode=='' ? '' : '=DAYS360(D'.$i.',W'.$i.',FALSE)';
				$objPHPExcel->getActiveSheet()->SetCellValue('X' . $i, $pay_days_txt);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $i, $row->code);
				$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $i, $row->gl_code);
				$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $i, $row->month);
				$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $i, $row->client_ntn);
				
				$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $i, $row->industry);
				$objPHPExcel->getActiveSheet()->SetCellValue('AD' . $i, $row->sector);
				$objPHPExcel->getActiveSheet()->SetCellValue('AE' . $i, $row->sub_sector);
				$objPHPExcel->getActiveSheet()->SetCellValue('AF' . $i, $row->g360);
			}
			
			if ( $export_type == 'H' ) {
				
				$day1 = $row->bill_date;
				$day2 = strtotime($row->receipt_date);
				$pay_days_txt = floor(($day2 - $day1) / (60 * 60 * 24));
?>

                <tr>
                    <td><?php print $row->branch; ?></td>
                    <td><?php print $row->bill_no; ?></td>
                    <td><?php print $row->client_name; ?></td>
                    <td><?php print date('d/m/Y', $row->bill_date); ?></td>
                    <!--<td><?php print $row->project_name; ?></td>-->
                    <td><?php print $row->segment; ?></td>
                    <td><?php print $row->service1; ?></td>
                    <td><?php print $row->short_description; ?></td>
                    <td><?php print $row->partner; ?></td>
                    <td><?php print $row->sr_manager; ?></td>
                    <td><?php print $row->manager; ?></td>
                    <td><?php print $row->fee; ?></td>
                    <td><?php print $row->oop; ?></td>
                    <td><?php print $row->others; ?></td>
                    <td><?php print $row->total; ?></td>
                    <td><?php print $sst_rate; ?></td>
                    <td><?php print $row->currency_symbol; ?></td>
                    <td><?php print $row->currency_rate; ?></td>
                    <td><?php print $row->total_fc; ?></td>
                    <!--<td><?php print $row->outstanding; ?></td>
                    <td><?php print $row->payment_mode; ?></td>
                    <td><?php print $row->doc_no ?></td>
                    <td><?php print $row->receipt_date; ?></td>
                    <td><?php print $pay_days_txt; ?></td>
                    <td><?php print $row->code; ?></td>
                    <td><?php print $row->gl_code; ?></td>
                    <td><?php print $row->month; ?></td>
                    <td><?php print $row->client_ntn; ?></td>-->        	
                </tr>
<?php 
			} 
			
			if ( $export_type == 'E' ) {
			 
				 $objPHPExcel->getActiveSheet()->getStyle('D'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y' 
					);
					
				$objPHPExcel->getActiveSheet()->getStyle('W'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y' 
					);
				
				$format_code = "###,###,###,###";
				$excel_colum = array('L', 'M', 'N', 'O', 'R', 'S', 'T');
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
				}
				
				$objPHPExcel->getActiveSheet()->getComment('B'.$i)->getText()->createTextRun($row->cell_comment);
			}
            $i++;
        }
		
		if ( $export_type == 'H' ) {
			echo '</table>';
		}
		
		if ( $export_type == 'E' ) {
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Dated: '.$row->report_date);
			
			//Print Totals
			$i++;
			
			$objPHPExcel->getActiveSheet()
					->setCellValue(
						'L'.$i,
						'=SUBTOTAL(9,L4:L'.($i-2).')'
					);
			$objPHPExcel->getActiveSheet()
					->setCellValue(
						'M'.$i,
						'=SUBTOTAL(9,M4:M'.($i-2).')'
					);
			$objPHPExcel->getActiveSheet()
					->setCellValue(
						'N'.$i,
						'=SUBTOTAL(9,N4:N'.($i-2).')'
					);
			$objPHPExcel->getActiveSheet()
					->setCellValue(
						'O'.$i,
						'=SUBTOTAL(9,O4:O'.($i-2).')'
					);
			$objPHPExcel->getActiveSheet()
					->setCellValue(
						'S'.$i,
						'=SUBTOTAL(9,S4:S'.($i-2).')'
					);
			$objPHPExcel->getActiveSheet()
					->setCellValue(
						'T'.$i,
						'=SUBTOTAL(9,T4:T'.($i-2).')'
					);
			
			$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode($format_code);
			$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getNumberFormat()->setFormatCode($format_code);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getNumberFormat()->setFormatCode($format_code);		
			$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->getNumberFormat()->setFormatCode($format_code);
			$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->getNumberFormat()->setFormatCode($format_code);
			$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->getNumberFormat()->setFormatCode($format_code);
			$styleArray = array(
				'font' => array(
					'bold' => true,
				),
				'borders' => array(
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					),
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THICK
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);			
			
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Bill Register - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
		}
    }

    public function excel($user_id = '', $filename, $params) {
		
		$report_parameters = '[';
		if(!empty($params->client_id)){	//$cc[] = $params->cc_email;
			$clients = $this->Clients_model->getClientsNameByID($params->client_id);
			$report_parameters .= '[';			
		}
		
		$setting = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		
        /** Include path * */
        $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
        //ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

        /** PHPExcel */
        include_once $basepath . 'PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once $basepath . 'PHPExcel/Writer/Excel2007.php';

        // Create new PHPExcel object
        //$objPHPExcel = new PHPExcel();
		
		$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/bill_register_template.xlsx");

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Earnst & Young");
        $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
        $objPHPExcel->getProperties()->setTitle("Bill Register");
        $objPHPExcel->getProperties()->setSubject("Bill Register");
        $objPHPExcel->getProperties()->setDescription("Bill Register");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Bill Register');
        $objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Dated: '.date("d M Y").', Time '.date("h:i:sa"));
		
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		
		
		if (isset($_REQUEST['search-bills-submit'])) {
            foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
				// Filter bills by bill date
				if ($field_name == 'branch-name') {
					foreach ($field_value as $branch) {
						$branch_id = $branch;
					}
				}
			}
        }
		
		
        $data = $this->Reports_model->getBillDataAndAllocation(true, $user_id, $branch_id);
		
		$i = 4;
		
		// Test Printing for Debugging purpose.
		//echo '<table border="1" width="98%" align="center">';
        
		$n = 2;
		foreach ($data as $row) {
			
			$et_array = array();
			if($row['sr_manager']!='' && $row['sr_manager']!=NULL){
				$et_array[] = $row['sr_manager'];
			}
			if($row['manager']!='' && $row['manager']!=NULL){
				$et_array[] = $row['manager'];
			}
			
			$bill_no_str = $row['bill_no_str'];
			if($n == 2){
				$bill_no_prev = $bill_no_str;
				$bill_no_next = $bill_no_str;
			} else {
				$bill_no_prev = $bill_no_next;
				$bill_no_next = $bill_no_str;
				if($bill_no_prev==$bill_no_next && $row['type']=='bill'){ continue; }
			}
			 
			//$bill_info = $this->Reports_model->getClientName($row['bill_id']);
			$client_info = $this->Clients_model->getClientsDetailsByID($row['client_id']);
			
			/*$client_name = is_object($bill_info) ? $bill_info->client_name : '';
			$client_ntn = is_object($bill_info) ? $bill_info->client_ntn : '';*/
			$client_name = $client_info['client_name'];
			$client_ntn = $client_info['ntn'];
			
            //$bill_date = is_object($bill_info) ? date('d/M/y', $bill_info->bill_date) : '';
            $bill_date = date('d/M/y', $row['bill_date']);
			
			//$prefix = isset($row['type']) && $row['type'] == "memo" ? 'M/' : '';
			if(isset($row['type']) && ($row['type'] == "memo")){ $prefix = 'M/'; }
			else if(isset($row['type']) && ($row['type'] == "debit")){ $prefix = 'DN/'; }
			else { $prefix = ''; }
			$remarks = '';
			
			if($row['credit_note'] == 1 && $row['credit_note_type'] == 'R') $bill_no_str .= '(R)';
			$short_description = ($row['type']=="debit" ? $row['short_description'] : $row['remarks']);
			if($row['type']=="credit"){
				$short_description .= ' [Bill# '.$row['credit_bill_no'].']';
			}
            // Add some data
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, " " . $row['branch_name'] . " ");
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, " " . $bill_no_str . " ");
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $client_name);
            // Colum Removed (21-August-2014)
			//$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $billto_name);
            
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $bill_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $row['project_name']);
            $objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $row['segment']);
            $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $row['service1']);
           // $objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $row['service2']);
		    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $short_description);
            $objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $row['partner']);
            //$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, implode(' | ',$et_array));
			$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $row['sr_manager']);
			$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $row['manager']);
			
			if($row['type']=='bill'){
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $row['total_fees']*$row['currency_rate']);
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $row['total_oop']*$row['currency_rate']);
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $row['total_others']*$row['currency_rate']);
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, $row['total_amount']*$row['currency_rate']);
			} else {
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $row['fee']);
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $row['oop']);
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $row['others']);
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, $row['total']);
			}
            $objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, $row['currency_symbol']);
            $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, $row['currency_rate']);
            $objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, $row['total_fc']);
			
			$outstanding = $row['outstanding'];
			if($row['type'] == 'credit'){ 
				//$remarks = 'Credit note against bill# '.$row['credit_bill_no'];
				$outstanding = 0;
			}
			
			$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, $outstanding);
			$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, $row['payment_mode']);
			$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, $row['doc_no']);
			$objPHPExcel->getActiveSheet()->SetCellValue('V' . $i, $row['receipt_date']);
			
			$pay_days_txt = $row['payment_mode']=='' ? '' : '=DAYS360(D'.$i.',U'.$i.',FALSE)';
			$objPHPExcel->getActiveSheet()->SetCellValue('W' . $i, $pay_days_txt);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('X' . $i, $client_info['code']);
			$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $i, $client_info['gl_code']);
			$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $i, date('m', $row['bill_date']));
			$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $i, $client_ntn);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $i, $client_info['by_industry']);
			$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $i, $client_info['sector']);
			$objPHPExcel->getActiveSheet()->SetCellValue('AD' . $i, $client_info['sub_sector']);
			$objPHPExcel->getActiveSheet()->SetCellValue('AE' . $i, $client_info['g360']);
			
			$objPHPExcel->getActiveSheet()->getStyle('D'.$i)
				->getNumberFormat()
				->setFormatCode(
					'd/mmm/y' 
				);
				
			$objPHPExcel->getActiveSheet()->getStyle('V'.$i)
				->getNumberFormat()
				->setFormatCode(
					'd/mmm/y' 
				);
			
            $format_code = "###,###,###,###";
            $excel_colum = array('L', 'M', 'N', 'O', 'Q', 'R', 'S');
            foreach ($excel_colum as $format_column) {
                $objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
            }
			
			// Put Tooltip On Bill# Cell.
			if ($row['type'] == "debit") {
				$objPHPExcel->getActiveSheet()->getComment('B'.$i)->getText()->createTextRun($row['short_description']);
			
			} else if ($row['type'] == "credit") {
				$objPHPExcel->getActiveSheet()->getComment('B'.$i)->getText()->createTextRun("Credit note against Bill# ".$row['credit_bill_no']);
			} else {
				$objPHPExcel->getActiveSheet()->getComment('B'.$i)->getText()->createTextRun($row['fee_description']);	
			}
			
			$n++;
            $i++;
        }

		//Print Totals
		$i++;
		$objPHPExcel->getActiveSheet()
				->setCellValue(
					'L'.$i,
					'=SUBTOTAL(9,L4:L'.($i-2).')'
				);
		$objPHPExcel->getActiveSheet()
				->setCellValue(
					'M'.$i,
					'=SUBTOTAL(9,M4:M'.($i-2).')'
				);
		$objPHPExcel->getActiveSheet()
				->setCellValue(
					'N'.$i,
					'=SUBTOTAL(9,N4:N'.($i-2).')'
				);
		$objPHPExcel->getActiveSheet()
				->setCellValue(
					'O'.$i,
					'=SUBTOTAL(9,O4:O'.($i-2).')'
				);
		$objPHPExcel->getActiveSheet()
				->setCellValue(
					'R'.$i,
					'=SUBTOTAL(9,R4:R'.($i-2).')'
				);
		$objPHPExcel->getActiveSheet()
				->setCellValue(
					'S'.$i,
					'=SUBTOTAL(9,S4:S'.($i-2).')'
				);
		
		
		$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->getNumberFormat()->setFormatCode($format_code);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->getNumberFormat()->setFormatCode($format_code);
		$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->getNumberFormat()->setFormatCode($format_code);
		$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->getNumberFormat()->setFormatCode($format_code);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->getNumberFormat()->setFormatCode($format_code);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->getNumberFormat()->setFormatCode($format_code);
		$styleArray = array(
			'font' => array(
				'bold' => true,
			),
			'borders' => array(
				'top' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
				),
				'bottom' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THICK
				)
			)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
		
		
		// Test Printing for Debugging purpose.
		//echo '</table>'; die;
		
        // Rename sheet
        //$objPHPExcel->getActiveSheet()->setTitle('Bill Register');

        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

        // We'll be outputting an excel file
        /*header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Bill Register - ' . date('d/m/Y') . '.xlsx"');
        $objWriter->save('php://output');*/
		$filename=$_SERVER['DOCUMENT_ROOT'].$setting->path_name."/attachments/".$filename."";
		$objWriter->save($filename);
    }
	
	public function generate_os_data() {
		
		$bill_date_to = $_REQUEST['fields']['bills-bill_date']['to'];
		$date_to_stamp = $this->Reports_model->get_to_date($bill_date_to);
		
		if (isset($_REQUEST['search-bills-submit'])) {
			
			
			$report_parameters = '';
			
            foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
				if (empty($field_value) || sizeof($field_value) <= 0)
                	continue;
				
				// Filter by Bill No.
				if ($field_name == 'bills-bill_no') {
					$bill_no_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_no_to = $_REQUEST['fields'][$field_name]['to'];
					
					if($bill_no_from != ''){
					
						$temp = explode('/', $bill_no_from);
						if (sizeof($temp) > 1) {
							$bill_no_from = $temp[0];
						}
		
						$temp = explode('/', $bill_no_to);
						if (sizeof($temp) > 1) {
							$bill_no_to = $temp[0];
						}
		
						$report_parameters .= 'Bill Nos: <b>'. $bill_no_from.' to '.$bill_no_to.'</b>, ';
					}
				}
				// Filter bills by bill date
				else if ($field_name == 'bills-bill_date') {
					$bill_date_from = $_REQUEST['fields'][$field_name]['from'];
					$bill_date_to = $_REQUEST['fields'][$field_name]['to'];
					$report_parameters .= 'Bill Date: <b>'. $bill_date_from.' to '.$bill_date_to.'</b>, ';
				}
				
				// Filter by segment
				else if ($field_name == 'clients-segments') {
					
					if(in_array('All',$field_value)){
						// --- DO NOTHING, SELECT ALL SEGMENTS -- //
						$report_parameters .= 'Segments: <b>ALL</b>, ';
					} else {
						$segments = array();
						foreach ($field_value as $semgnet_id) {
							$segment_name = $this->Segments_model->getSegmentName($semgnet_id);
							$segments[] = "'{$segment_name}'";
						}
						$report_parameters .= 'Segment: <b>'. implode(',', $segments).'</b>, ';
					}
				}
				
				// Filter by Service-1
				else if ($field_name == 'service1') {
					$service1 = array();
					foreach ($field_value as $service1_id) {
						$service1_name = $this->Segments_model->getSegmentName($service1_id);
						$service1[] = "'{$service1_name}'";
					}
	
					if($service1_id != '0'){
						$report_parameters .= 'Service-1: <b>'. implode(',', $service1).'</b>, ';
					}
				}
				
				// Filter bills by Client
				else if ($field_name == 'clients-name') {
					$clients = array();
					foreach ($field_value as $clientm_id) {
						$client_names = $this->Clients_model->getClientsNameByID($clientm_id);
						$clients[] = "'{$client_names}'";
					}
					$report_parameters .= 'Client: <b>'. implode(',', $clients).'</b>, ';
					
				}
				
				// Filter by Sector
				else if ($field_name == 'clients-sectors') {
					$sectors = array();
					foreach ($field_value as $sector) {
						$sectors[] = "'{$sector}'";
					}
					$report_parameters .= 'Sector: <b>'. implode(',', $sectors).'</b>, ';
				}
				
				// Filter by Industry
				else if ($field_name == 'clients-industry') {
					$industries = array();
					foreach ($field_value as $industry) {
						$industries[] = "'{$industry}'";
					}
					$report_parameters .= 'Industry: <b>'. implode(',', $industries).'</b>, ';
				}
				
				// Filter by partner
				else if ($field_name == 'clients-partners') {
					$partners = array();
					foreach ($field_value as $partner_id) {
						$partner_names = $this->Users_model->getUserShortCode($partner_id);
						$partners[] = "'{$partner_names}'";
					}
					$report_parameters .= 'Partner: <b>'. implode(',', $partners).'</b>, ';
				}
				// Filter by Sr.Manager
				else if ($field_name == 'clients-sr_managers') {
					$sr_managers = array();
					foreach ($field_value as $sr_managers_id) {
						$sm_names = $this->Users_model->getUserShortCode($sr_managers_id);
						$sr_managers[] = "'{$sm_names}'";
					}
					$report_parameters .= 'ED/SM: <b>'. implode(',', $sr_managers).'</b>, ';
				}
				// Filter by Manager
				else if ($field_name == 'clients-managers') {
					$managers = array();
					foreach ($field_value as $manager_id) {
						$manager_names = $this->Users_model->getUserShortCode($manager_id);
						$managers[] = "'{$manager_names}'";
					}
					$report_parameters .= 'Manager: <b>'. implode(',', $managers).'</b>, ';
				}
				
				// Filter bills by bill date
				if ($field_name == 'branch-name2') {
					
					if(in_array('All',$field_value)){
						// --- DO NOTHING, SELECT ALL Branches -- //
						$report_parameters .= 'Branches: <b>ALL</b>, ';
					}
					else {
						unset($branch_arr);
						foreach ($field_value as $branch_id) {
							if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
							if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
							if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
							if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
						}
						$report_parameters .= 'Branch: <b>'. implode(',', $branch_arr).'</b>, ';
					}
					
				}
			}
			
			$khi_db = mysql_connect('localhost','root',$this->db->password) or die(mysql_error());
			mysql_select_db($this->db->database, $khi_db) or die(mysql_error());
			
			$session_id = time().'_'.rand();
			$report_date = date("d M Y").', Time '.date("h:i:sa");
			
			$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
			$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
			$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
			$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
			
			foreach ($_REQUEST['fields'] as $field_name => $field_value) {
					
				if ($field_name == 'branch-name2') {
				
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL Branches ---- //
					} else {
						unset($branch_arr);
						foreach ($field_value as $branch_id) {
							if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
							if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
							if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
							if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
						}
					}
				}
				
			}
			
			foreach($branch_arr as $branch_id => $branch_name){
				
				$setting = $this->Settings_model->getBranchSettings($branch_id);
				
				//------------------ Check Advances - START ---------------//
				
				$advances = $this->Receipts_model->getAdvancesFull($branch_id);
				
				foreach ($advances as $advance){
					
					$clients_arr = explode(',',$advance->client_id);
					if(count($clients_arr) == 1){
						$client_names = $advance->client_name;
					} else {
						$client_names = '';
						foreach($clients_arr as $key=>$val){
							$query = mysql_query("SELECT client_name FROM clients WHERE id = '".$val."'");
							if(mysql_num_rows($query) > 0){
								$rec = mysql_fetch_array($query);
								$client_names .= $rec['client_name'].', ';
							}
						}
					}
					
					$adv_date = $advance->date_advance;
					$cur_date = $date_to_stamp;
					$datediff = $cur_date - $adv_date;
					$a_days = floor($datediff / (60 * 60 * 24));
					
					$a30 = ($a_days >= 0 && $a_days <= 30 ? $advance->amount : '');
					$a60 = ($a_days >= 31 && $a_days <= 60 ? $advance->amount : '');
					$a90 = ($a_days >= 61 && $a_days <= 90 ? $advance->amount : '');
					$a120 = ($a_days >= 91 && $a_days <= 120 ? $advance->amount : '');
					$a150 = ($a_days >= 121 && $a_days <= 150 ? $advance->amount : '');
					$a180 = ($a_days >= 151 && $a_days <= 180 ? $advance->amount : '');
					$a270 = ($a_days >= 181 && $a_days <= 270 ? $advance->amount : '');
					$a365 = ($a_days >= 271 && $a_days <= 365 ? $advance->amount : '');
					$a_up = ($a_days > 365 ? $advance->amount : '');
					
					$sql = "INSERT INTO bill_outstanding_excel (
									session_id, 
									branch, 
									client_name, 
									segment, 
									partner, 
									manager, 
									bill_no, 
									bill_date, 
									remaining, 
									a30, a60, a90, a120, a150, a180, a270, a365, a_up, 
									remarks, 
									report_date, report_parameters)
							VALUES (
									'".$session_id."', 
									'".$branch_name."', 
									'".addslashes($client_names)."', 
									'".$advance->segment."', 
									'".$advance->partner."', 
									'".$advance->manager."', 
									'Adv/".$advance->advance_no."', 
									'".$advance->date_advance."', 
									'".-$advance->amount."', 
									'".-$a30."', '".-$a60."', '".-$a90."', '".-$a120."', '".-$a150."', '".-$a180."', '".-$a270."', '".-$a365."', '".-$a_up."', 
									'".addslashes($advance->remarks)."', 
									'".$report_date."', '".addslashes($report_parameters)."')";
					mysql_query($sql, $khi_db) or die(mysql_error());
					
				}
				
				$data = $this->Reports_model->getOutstanding_excel('', $branch_id);
				
				//$count = 0;
				foreach ($data as $row) {
					
					/*if($row->currency_id!='2e323c67-38fd-58f7-9296-0344d01173a8'){
						$total_amount_field = $row->total_amount_actual;
					} else {
						$total_amount_field = $row->total_amount;
					}*/
					
					$total_billed_amount = $row->total_amount + ($row->total_credit_amount*$row->credit_currency_rate);
					$total_amount_foreign_currency = $total_billed_amount/$row->currency_rate;
					$received = empty($row->amount_received) ? 0 : $row->amount_received;
					$remaining = empty($row->outstanding) ? ($row->total_amount + ($row->total_credit_amount*$row->credit_currency_rate)) : $row->outstanding;
					$remaining = round($remaining);
					
					/*if( $row->bill_no_str == '1221/17'){
						//print $row->total_amount.'<br>'.($row->total_credit_amount*$row->credit_currency_rate).'<br>'.$remaining;
						print $total_amount_field.'<br>'.round($row->total_amount).'<br>'.$row->total_credit_amount.'<br>'.$total_billed_amount.'<br>'.$total_amount_foreign_currency.'<br>'.$received.'<br>'.$row->outstanding.'<br>'.round($remaining);
						die;
					}*/
					
					if(!($remaining > 0 || $remaining < 0)) {						
						/*mysql_query("DELETE FROM receipts_data WHERE bill_id = '".$row->id."'");
						mysql_query("DELETE FROM bills_data WHERE bill_id = '".$row->id."'");
						mysql_query("DELETE FROM bills WHERE id = '".$row->id."'");*/
						//$count++;
						if($row->provided == 0){ 
							continue;
						}
					}
					
					$currency_symbol = ($row->currency_id!='2e323c67-38fd-58f7-9296-0344d01173a8' ? $row->currency_symbol : '');
					$amount_foreign_currency = ($row->currency_id!='2e323c67-38fd-58f7-9296-0344d01173a8' ? $total_amount_foreign_currency : '');
					
					$date1 = $row->bill_date;
					$date2 = $date_to_stamp;
					$datediff = $date2 - $date1;
					$days = floor($datediff / (60 * 60 * 24));
					$provided = $row->provided;
					
					$provided_client = '';  // Last Column provided OS in Excel. 
					$tot_credit_days = $row->client_credit_days + $setting->after_provided_days;
					if($tot_credit_days > $days){
						$provided_client = $remaining;
					}
					
					$a30 = ($days >= 0 && $days <= 30 ? $remaining : '');
					$a60 = ($days >= 31 && $days <= 60 ? $remaining : '');
					$a90 = ($days >= 61 && $days <= 90 ? $remaining : '');
					$a120 = ($days >= 91 && $days <= 120 ? $remaining : '');
					$a150 = ($days >= 121 && $days <= 150 ? $remaining : '');
					$a180 = ($days >= 151 && $days <= 180 ? $remaining : '');
					$a270 = ($days >= 181 && $days <= 270 ? $remaining : '');
					$a365 = ($days >= 271 && $days <= 365 ? $remaining : '');
					$a_up = ($days > 365 ? $remaining : '');
										
					$provision_recovered = 0;
					if($row->provided > 0){
						
						if($received){ $remaining = ($row->total_amount + ($row->total_credit_amount*$row->credit_currency_rate)) - $received; }
						else { $remaining = $row->total_amount + ($row->total_credit_amount*$row->credit_currency_rate); }
												
						if($row->receipt_date > strtotime($row->provision_date)){
							$provision_recovered = $row->provision_recovered;
							$provision_recovery_date = $row->receipt_date;
						} else {
							$provision_recovered = 0;
							$provision_recovery_date = '';
						}
					}
					
					$sql = "INSERT INTO bill_outstanding_excel (
									session_id, 
									branch, client_name, sector, client_credit_days, 
									segment, service1,
									partner, manager, sr_manager,
									bill_no, bill_date, 
									credit_days,
									currency_symbol, amount_foreign_currency,
									billed_amount, received, remaining, 
									days, a30, a60, a90, a120, a150, a180, a270, a365, a_up, 
									provided, provision_date, provided_remarks, provision_recovered, provision_recovery_date,
									client_code, provided_client, remarks, remarks2, 
									report_date, report_parameters )
							VALUES (
									'".$session_id."', 
									'".$branch_name."', 
									'".addslashes($row->client_name)."', 
									'".$row->by_sec."',
									'".$row->client_credit_days."',									
									'".$row->segment_name."',
									'".$row->service1_name."',
									'".$row->partner_name."', 
									'".$row->manager_name."',
									'".$row->sr_manager_name."',
									'".$row->bill_no_str."', 
									'".$row->bill_date."',
									'".$row->credit_days."',
									'".$currency_symbol."',
									'".$amount_foreign_currency."',
									'".$total_billed_amount."',
									'".$received."',
									'".$remaining."',
									'".$days."',
									'".$a30."', '".$a60."', '".$a90."', '".$a120."', '".$a150."', '".$a180."', '".$a270."', '".$a365."', '".$a_up."',
									'".$row->provided."', '".$row->provision_date."', '".addslashes($row->provided_remarks)."', 
									'".$provision_recovered."', '".$provision_recovery_date."',
									'".$row->client_code."', '".$provided_client."', 
									'".addslashes($row->remarks)."', '".addslashes($row->remarks2)."',
									'".$report_date."', '".addslashes($report_parameters)."')";
					
					mysql_query($sql, $khi_db) or die(mysql_error());
					
				}
				
			} // --- Main Branch Loop ---- //
			
		} 
		//echo $count; die;
		redirect('reports/outstanding_excel_full');
		
    }
	
    public function excel2() {
		
        /** Include path * */
        $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
        //ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

        /** PHPExcel */
        include_once $basepath . 'PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
		
		/** Load Excel Template File */
		$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/bill_outstanding_template.xlsx");
		
        // Create new PHPExcel object
        //$objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Earnst & Young");
        $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
       	//$objPHPExcel->getProperties()->setTitle("Outstanding Reports");
        //$objPHPExcel->getProperties()->setSubject("Outstanding Reports");
        //$objPHPExcel->getProperties()->setDescription("Outstanding Reports");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Outstanding Report');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Dated: '.date("d M Y").', Time '.date("h:i:sa"));
		
        $zeros = array('0000', '000', '00', '0', '');
        
		$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
		$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
		$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
		//$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
		foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
			if ($field_name == 'branch-name2') {
			
				if(in_array('All',$field_value)){
					// --- JUST SELECT ALL Branches ---- //
				} else {
					unset($branch_arr);
					foreach ($field_value as $branch_id) {
						if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
						if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
						if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
						if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
					}
				}
			}
			
		}
		
		
		$i = 5; // Start from 2nd Row in Excel Sheet
        
		foreach($branch_arr as $branch_id => $branch_name){
			
			//------------------ Check Advances - START ---------------//
			
			$advances = $this->Receipts_model->getAdvancesFull($branch_id);
				
			foreach ($advances as $advance){
				
				$adv_date = $advance->date_advance;
				$cur_date = time();
				$datediff = $cur_date - $adv_date;
				$a_days = floor($datediff / (60 * 60 * 24));
				
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $branch_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $advance->client_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $advance->segment);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $advance->partner);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $advance->manager);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, 'Adv/'.$advance->advance_no);
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, date('d/m/Y', $advance->date_advance));
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, -$advance->amount);
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, '');					
				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, ($a_days >= 0 && $a_days <= 30 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, ($a_days >= 31 && $a_days <= 60 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, ($a_days >= 61 && $a_days <= 91 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, ($a_days >= 91 && $a_days <= 120 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, ($a_days >= 121 && $a_days <= 150 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('V' . $i, ($a_days >= 151 && $a_days <= 180 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('W' . $i, ($a_days >= 181 && $a_days <= 270 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('X' . $i, ($a_days >= 270 && $a_days <= 365 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $i, ($a_days > 365 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $i, $advance->remarks);
				$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $i, '');
				
				$objPHPExcel->getActiveSheet()->getStyle('I'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
				
				$i++; // Move to Next Row
			}
			
			//------------------ Check Advances - END ---------------//
			
			/*if($_REQUEST['report_model'] == 'new'){
				$data = $this->Reports_model->getOutstanding_full($branch_id);
			} else {*/
				$data = $this->Reports_model->getOutstanding_excel('', $branch_id);
			//}
			
			foreach ($data as $row) {
								
				$total_billed_amount = $row->total_amount+$row->total_credit_amount;
				$total_amount_foreign_currency = $total_billed_amount/$row->currency_rate;
				$received = empty($row->amount_received) ? 0 : $row->amount_received;
				$remaining = empty($row->outstanding) ? ($row->total_amount+$row->total_credit_amount) : $row->outstanding;
				
				if(!($remaining > 0 || $remaining < 0)) {
					continue;
				}
				
				$date1 = $row->bill_date;
				$date2 = time();
				$datediff = $date2 - $date1;
				$days = floor($datediff / (60 * 60 * 24));
	
				// Add some data
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $branch_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $row->client_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $row->segment_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $row->service1_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $row->partner_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $row->manager_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $row->sr_manager_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, " " . $row->bill_no_str . " ");
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, date('d/m/Y', $row->bill_date));
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $row->credit_days);		
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ($row->currency_id!='2e323c67-38fd-58f7-9296-0344d01173a8' ? $row->currency_symbol : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, ($row->currency_id!='2e323c67-38fd-58f7-9296-0344d01173a8' ? $total_amount_foreign_currency : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $total_billed_amount);
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $received);
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, $remaining);
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, $days);
				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, ($days >= 0 && $days <= 30 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, ($days >= 31 && $days <= 60 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, ($days >= 61 && $days <= 90 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, ($days >= 91 && $days <= 120 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, ($days >= 121 && $days <= 150 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('V' . $i, ($days >= 151 && $days <= 180 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('W' . $i, ($days >= 181 && $days <= 270 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('X' . $i, ($days >= 271 && $days <= 365 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $i, ($days > 365 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $i, $row->client_code);
				$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $i, $row->remarks);
				$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $i, $row->remarks2);
				
				$objPHPExcel->getActiveSheet()->getStyle('I'.$i)
						->getNumberFormat()
						->setFormatCode(
							'd/mmm/y'  // my own personal preferred format that isn't predefined
						);
				
				$format_code = "###,###,###,###";
				$excel_colum = array('L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y');
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
				}
				$i++;
			}
			
		} // --- Main Branch Loop ---- //
		$i++;
		
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUBTOTAL(9,O5:O'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUBTOTAL(9,P5:P'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUBTOTAL(9,Q5:Q'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUBTOTAL(9,R5:R'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUBTOTAL(9,S5:S'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUBTOTAL(9,T5:T'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, '=SUBTOTAL(9,U5:U'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, '=SUBTOTAL(9,V5:V'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, '=SUBTOTAL(9,W5:W'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, '=SUBTOTAL(9,X5:X'.($i-2).')');
		$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, '=SUBTOTAL(9,Y5:Y'.($i-2).')');
		
		$styleArray = array(
			'font' => array(
				'bold' => true,
			),
			'borders' => array(
				'top' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
				),
				'bottom' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THICK
				)
			)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('U'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('W'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('X'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('Y'.$i)->applyFromArray($styleArray);
		
        // Rename sheet
        //$objPHPExcel->getActiveSheet()->setTitle('Outstanding Reports');

        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		//ob_end_clean();
		
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Outstanding Reports - ' . date('d/m/Y') . '.xlsx"');
        $objWriter->save('php://output');
    }
	
	public function export_to_excel($session_id) {
		
		// echo $session_id;
		// exit();
		
        /** Include path * */
		
		ini_set('memory_limit','1024M');	
		ini_set('max_execution_time', 100000);
		
        $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
        //ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

        /** PHPExcel */
        include_once $basepath . 'PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
		
		/** Load Excel Template File */
		$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/bill_outstanding_template.xlsx");
		
        // Set properties
        $objPHPExcel->getProperties()->setCreator("Earnst & Young");
        $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Outstanding Report');
		
		$data = $this->Reports_model->getGeneratedReport($session_id, 'bill_outstanding_excel');
		// echo '<pre>';
		// print_r($data);
		// exit();
		$i = 5; // Start from 2nd Row in Excel Sheet
        			
		foreach ($data as $row) {
			
			$et_array = array();
			if($row->sr_manager!='' && $row->sr_manager!=NULL){
				$et_array[] = $row->sr_manager;
			}
			if($row->manager!='' && $row->manager!=NULL){
				$et_array[] = $row->manager;
			}
			
			$report_date = $row->report_date;
			if($row->provided > 0){ $provision_date = $row->provision_date; }
			else { $provision_date = ''; }
			
			if($row->provision_recovered > 0){ $provision_recovery_date = date('d/m/Y', $row->provision_recovery_date); }
			else { $provision_recovery_date = ''; }
			
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row->branch);
			$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $row->client_name);
			$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $row->sector);
			$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $row->client_credit_days);
			$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $row->segment);
			$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $row->service1);
			$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $row->partner);
			$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, implode(' | ',$et_array));
			$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, " " . $row->bill_no . " ");
			$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, date('d/m/Y', $row->bill_date));
			$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, $row->credit_days);		
			$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, $row->currency_symbol);
			$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, $row->amount_foreign_currency);
			$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, $row->billed_amount);
			$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, $row->received);
			$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, $row->remaining);
			$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, $row->days);
			$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, $row->a30);
			$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, $row->a60);
			$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, $row->a90);
			$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, $row->a120);
			$objPHPExcel->getActiveSheet()->SetCellValue('V' . $i, $row->a150);
			$objPHPExcel->getActiveSheet()->SetCellValue('W' . $i, $row->a180);
			$objPHPExcel->getActiveSheet()->SetCellValue('X' . $i, $row->a270);
			$objPHPExcel->getActiveSheet()->SetCellValue('Y' . $i, $row->a365);
			$objPHPExcel->getActiveSheet()->SetCellValue('Z' . $i, $row->a_up);
			$objPHPExcel->getActiveSheet()->SetCellValue('AA' . $i, $row->provided);
			$objPHPExcel->getActiveSheet()->SetCellValue('AB' . $i, $provision_date);
			$objPHPExcel->getActiveSheet()->SetCellValue('AC' . $i, $row->provided_remarks);
			$objPHPExcel->getActiveSheet()->SetCellValue('AD' . $i, $row->provision_recovered);
			$objPHPExcel->getActiveSheet()->SetCellValue('AE' . $i, $provision_recovery_date);			
			$objPHPExcel->getActiveSheet()->SetCellValue('AF' . $i, $row->client_code);
			$objPHPExcel->getActiveSheet()->SetCellValue('AG' . $i, $row->provided_client);
			$objPHPExcel->getActiveSheet()->SetCellValue('AH' . $i, $row->remarks);
			$objPHPExcel->getActiveSheet()->SetCellValue('AI' . $i, $row->remarks2);
			
			$objPHPExcel->getActiveSheet()->getStyle('J'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
			
			$objPHPExcel->getActiveSheet()->getStyle('AB'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
					
			$objPHPExcel->getActiveSheet()->getStyle('AE'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
			
			$format_code = "###,###,###,###";
			$excel_colum = array('M', 'N', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AD', 'AG');
			foreach ($excel_colum as $format_column) {
				$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
			}
			$i++;
		}
			
		$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Dated: '.$report_date);
			
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUBTOTAL(9,P5:P'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUBTOTAL(9,R5:R'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUBTOTAL(9,S5:S'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUBTOTAL(9,T5:T'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('U'.$i, '=SUBTOTAL(9,U5:U'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('V'.$i, '=SUBTOTAL(9,V5:V'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('W'.$i, '=SUBTOTAL(9,W5:W'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('X'.$i, '=SUBTOTAL(9,X5:X'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('Y'.$i, '=SUBTOTAL(9,Y5:Y'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('Z'.$i, '=SUBTOTAL(9,Z5:Z'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('AA'.$i, '=SUBTOTAL(9,AA5:AA'.($i-1).')');
		$objPHPExcel->getActiveSheet()->setCellValue('AD'.$i, '=SUBTOTAL(9,AD5:AD'.($i-1).')');
		
		$styleArray = array(
			'font' => array(
				'bold' => true,
			),
			'borders' => array(
				'top' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THIN
				),
				'bottom' => array(
				  'style' => PHPExcel_Style_Border::BORDER_THICK
				)
			)
		);
		
		$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('U'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('V'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('W'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('X'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('Y'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('Z'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('AA'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('AD'.$i)->applyFromArray($styleArray);
		
		
		
		
        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Outstanding Reports - ' . date('d/m/Y') . '.xlsx"');
        $objWriter->save('php://output');
    }
	
	
	public function consolidated_billing() {
        
		/** Include path * */
        $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
        //ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

        /** PHPExcel */
        include_once $basepath . 'PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
		
		/** Load Excel Template File */
		$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/EYFRSH_Consolidated_Billing.xlsx");
		
        // Create new PHPExcel object
        //$objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Earnst & Young");
        $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
        $objPHPExcel->getProperties()->setTitle("EYFRSH Consolidated Billing");
        $objPHPExcel->getProperties()->setSubject("EYFRSH Consolidated Billing");
        $objPHPExcel->getProperties()->setDescription("EYFRSH Consolidated Billing");

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
		
		// CURRENT YEAR DATES
		$current_financial_year_start = $this->variables['financial_year_start'];
		$current_month_start = $this->variables['current_month_start'];
		$current_month_end = $this->variables['current_month_end'];
		
		// LAST YEAR DATES
		$last_financial_year_start = $this->variables['last_financial_year_start'];
		$last_financial_year_end = $this->variables['last_financial_year_end'];
		$last_month_start = $this->variables['last_month_start'];
		$last_month_end = $this->variables['last_month_end'];
		
		$objPHPExcel->getActiveSheet()->SetCellValue('B4', "Billing for the month ended ".date('d M, Y', $current_month_end));
		$objPHPExcel->getActiveSheet()->SetCellValue('R7', "FY ".date('Y', $current_financial_year_start));
		$objPHPExcel->getActiveSheet()->SetCellValue('C8', date('M-Y', $current_month_start)." Gross");
		$objPHPExcel->getActiveSheet()->SetCellValue('D8', date('M-Y', $last_month_start)." Gross");
		$objPHPExcel->getActiveSheet()->SetCellValue('E8', "Bad Debts Provision ".date('M-y', $current_month_start)." Over 180 Days / (Received)");
		$objPHPExcel->getActiveSheet()->SetCellValue('H8', "FY ".date('y', $current_month_start)." (Net of Provision)");
		$objPHPExcel->getActiveSheet()->SetCellValue('K8', "FY ".date('y', $current_financial_year_start));
		$objPHPExcel->getActiveSheet()->SetCellValue('L8', "FY ".date('y', $last_financial_year_start));
		$objPHPExcel->getActiveSheet()->SetCellValue('R8', "FY "."Provided upto ".date('M-Y', $last_financial_year_end));
		$objPHPExcel->getActiveSheet()->SetCellValue('U8', "Provided in FY".date('y', $current_financial_year_start));
				
        // BRANCH IDS
		$karachi = 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9';
		$kabul = '271aad4d-9ee8-58b3-8658-961f18a9b908';
		$lahore = 'fffffff7-efd8-59ef-bb91-926565e01b1a';
		$islamabad = '0ff6e36c-e866-50bb-92d4-c45dc0740351';
		
        
		// -------------------------------------------- KARACHI DATA ------------------------------------------------//
		/* CURRENT MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$karachi."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_month_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ");
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('C10', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C18', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C25', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C31', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C39', $tas_amount);
		
		/* LAST MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$karachi."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_month_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ");
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('D10', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D18', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D25', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D31', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D39', $tas_amount);
		
		/* CURRENT YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$karachi."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_financial_year_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ");
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('K10', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K18', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K25', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K31', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K39', $tas_amount);
		
		/* LAST YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$karachi."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_financial_year_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ");
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('L10', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L18', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L25', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L31', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L39', $tas_amount);
		
		// -------------------------------------------------------------------------------------------------------------//
		
		
		// CONNECT KABUL DB
		$kabul_db_conn = mysql_connect("localhost","root","M03219205211") or die(mysql_error());
		$kabul_db_select = mysql_select_db('ey_kabul',$kabul_db_conn) or die(mysql_error());
		
		// -------------------------------------------- KABUL/AFGHANISTAN DATA ------------------------------------------------//
		/* CURRENT MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$kabul."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_month_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $kabul_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('C11', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C19', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C26', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C32', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C40', $tas_amount);
		
		/* LAST MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$kabul."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_month_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $kabul_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('D11', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D19', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D26', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D32', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D40', $tas_amount);
		
		/* CURRENT YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$kabul."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_financial_year_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $kabul_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('K11', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K19', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K26', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K32', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K40', $tas_amount);
		
		/* LAST YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$kabul."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_financial_year_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $kabul_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('L11', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L19', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L26', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L32', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L40', $tas_amount);
		
		mysql_close($kabul_db_conn);
		// -------------------------------------------------------------------------------------------------------------//
		
		
		// CONNECT LAHORE DB
		$lhr_db_conn = mysql_connect("localhost","root","M03219205211") or die(mysql_error());
		$lhr_db_select = mysql_select_db('ey_lhr',$lhr_db_conn) or die(mysql_error());
		
		// -------------------------------------------- KABUL/AFGHANISTAN DATA ------------------------------------------------//
		/* CURRENT MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$lahore."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_month_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $lhr_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('C12', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C20', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C27', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C33', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C41', $tas_amount);
		
		/* LAST MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$lahore."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_month_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $lhr_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('D12', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D20', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D27', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D33', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D41', $tas_amount);
		
		/* CURRENT YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$lahore."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_financial_year_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $lhr_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('K12', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K20', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K27', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K33', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K41', $tas_amount);
		
		/* LAST YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$lahore."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_financial_year_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $lhr_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('L12', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L20', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L27', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L33', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L41', $tas_amount);
		
		mysql_close($lhr_db_conn);
		// -------------------------------------------------------------------------------------------------------------//
		
		
		// CONNECT ISB DB
		$isb_db_conn = mysql_connect("localhost","root","M03219205211") or die(mysql_error());
		$isb_db_select = mysql_select_db('ey_isb',$isb_db_conn) or die(mysql_error());
		
		// -------------------------------------------- ISLAMABAD DATA ------------------------------------------------//
		/* CURRENT MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$islamabad."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_month_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $isb_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('C13', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C21', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C28', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C34', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('C42', $tas_amount);
		
		/* LAST MONTH */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$islamabad."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_month_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $isb_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('D13', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D21', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D28', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D34', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('D42', $tas_amount);
		
		/* CURRENT YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$islamabad."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$current_financial_year_start}' AND '{$current_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $isb_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('K13', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K21', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K28', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K34', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('K42', $tas_amount);
		
		/* LAST YEAR */
	   	$query = mysql_query("SELECT SUM(bills_data.fee*bills.currency_rate) AS total, bills_data.segment_id
								FROM bills_data
								INNER JOIN bills ON bills.id = bills_data.bill_id
								WHERE bills.status NOT IN ('draft','deleted') AND bills.branch_id IN ('".$islamabad."') 
								AND bills_data.segment_id != 'e7946507-8721-527b-a7ee-3630cb7ac04a' 
								AND bills.bill_date BETWEEN '{$last_financial_year_start}' AND '{$last_month_end}' AND bills_data.segment_id IS NOT NULL
								GROUP BY bills_data.segment_id
							 ", $isb_db_conn);
	   	$advisory_amount = 0;
		$assurance_amount = 0;
		$tas_amount = 0;
		$tax_amount = 0;
		$itra_amount = 0;
		while($row = mysql_fetch_array($query)){
			if($row['segment_id'] == '1216e429-7acc-5462-b648-9a5e4264eac7')	$assurance_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffdb-fda3-5749-99e0-e97559020b24') 		$tax_amount = round($row['total']);
			if($row['segment_id'] == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5') 	$advisory_amount = round($row['total']);
			if($row['segment_id'] == 'ITRA') 		$itra_amount = round($row['total']);
			if($row['segment_id'] == 'c3732f54-c736-5fb8-979d-be7fa46d03c2') 		$tas_amount = round($row['total']);
		}
		$objPHPExcel->getActiveSheet()->SetCellValue('L13', $assurance_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L21', $tax_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L28', $advisory_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L34', $itra_amount);
		$objPHPExcel->getActiveSheet()->SetCellValue('L42', $tas_amount);
		
		mysql_close($isb_db_conn);
		// -------------------------------------------------------------------------------------------------------------//
		
		
        // Rename sheet
        //$objPHPExcel->getActiveSheet()->setTitle('EYFRSH Consolidated Billing');

        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);

        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="EYFRSH Consolidated Billing - ' . date('d/m/Y') . '.xlsx"');
        $objWriter->save('php://output');
    }
	
	
	
	public function outstanding_excel_email($user_id, $filename) {
		
		$setting = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		
        /** Include path * */
        $basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
        //ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');

        /** PHPExcel */
        include_once $basepath . 'PHPExcel.php';

        /** PHPExcel_Writer_Excel2007 */
        include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
		
		/** Load Excel Template File */
		$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/bill_outstanding_template2.xlsx");
		
        // Create new PHPExcel object
        //$objPHPExcel = new PHPExcel();

        // Set properties
        $objPHPExcel->getProperties()->setCreator("Earnst & Young");
        $objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
        $objPHPExcel->getProperties()->setTitle("Outstanding Reports");
        $objPHPExcel->getProperties()->setSubject("Outstanding Reports");
        $objPHPExcel->getProperties()->setDescription("Outstanding Reports");
		
		
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Outstanding Report');
        $objPHPExcel->getActiveSheet()->SetCellValue('A3', 'Dated: '.date("d M Y").', Time '.date("h:i:sa"));
		
        $zeros = array('0000', '000', '00', '0', '');
       
	   	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
	//	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
	//	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
	//	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
			
		foreach ($_REQUEST['fields'] as $field_name => $field_value) {
					
			if ($field_name == 'branch-name2') {
			
				if(in_array('All',$field_value)){
					// --- JUST SELECT ALL Branches ---- //
				} else {
					unset($branch_arr);
					foreach ($field_value as $branch_id) {
						if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
						if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
						if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
						if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
					}
				}
			}
			
		}
		
		$c = 0;
		$i = 6; // Start from 6th Row in Excel Sheet
		$gt = array(); // Array to Remember Client Sub Total Column Numbers to Calculate Grand Total
		
		foreach($branch_arr as $branch_id => $branch_name){
		
			$data = $this->Reports_model->getOutstanding_excel($user_id, $branch_id);
			$segment_names = array();
			$client_ids_ex = array();
			
			foreach ($data as $row) {
				
				// There can be multiple segments
				if(!in_array($row->segment_name, $segment_names)) $segment_names[] = $row->segment_name;
																				
				$total_billed_amount = $row->total_amount + ($row->total_credit_amount*$row->credit_currency_rate);
				$total_amount_foreign_currency = number_format($total_billed_amount/$row->currency_rate, 2);
				$received = empty($row->amount_received) ? 0 : $row->amount_received;
				$remaining = empty($row->outstanding) ? ($row->total_amount + ($row->total_credit_amount*$row->credit_currency_rate)) : $row->outstanding;
			   	$remaining = round($remaining);
			   
			   if(!($remaining > 0 || $remaining < 0)) {
					continue;
			   }
				
				if($c == 0){
					//Top Segment Heading - One time Only
					//$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Segment - '.$row->segment_name);
					
					$previous_client = "";
					 
				}
				$next_client = $row->client_id;
				
				// Get Advances of each client
				if( $next_client != $previous_client ){
					
					if( $c > 0 ){
					
						$cs2 = $i-1; // Remember Ending Row number for Client Total
						$gt[] = $i;  // Remember For Grand Total
						
						/*----------- NOW SHOW CLIENT SUB TOTAL ROW ----------------*/
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUM(J'.$cs1.':J'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUM(K'.$cs1.':K'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUM(L'.$cs1.':L'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUM(M'.$cs1.':M'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUM(N'.$cs1.':N'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUM(O'.$cs1.':O'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUM(P'.$cs1.':P'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUM(Q'.$cs1.':Q'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUM(R'.$cs1.':R'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUM(S'.$cs1.':S'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUM(T'.$cs1.':T'.$cs2.')');
						
						$styleArray = array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb'=>'FBD67D'),
							),
							'borders' => array(
								'top' => array(
								  'style' => PHPExcel_Style_Border::BORDER_DOTTED
								),
								'bottom' => array(
								  'style' => PHPExcel_Style_Border::BORDER_DOTTED
								)
							)
						);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
						$i++;
						$i++;
						/*----------------------------------------------------------*/
						
					}
					
					//Client Name Heading
					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row->client_name);
					$styleArray = array(
						'font' => array(
							'bold' => true,
						)
					);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
					$cs1 = $i+1; // Remember Starting Row number for Client Total
					$i++;
					
					$advances = $this->Receipts_model->getAlladvances($next_client);
					foreach ($advances as $advance){
						
						$client_ids_ex[] = $next_client; // Remember this client to Exclude Later from Advances Check
						$partner_manager = $advance->partner;
						if($advance->manager != '') $partner_manager .= ' | '.$advance->manager;
						
						$adv_date = $advance->date_advance;
						$cur_date = time();
						$datediff = $cur_date - $adv_date;
						$a_days = floor($datediff / (60 * 60 * 24));
						
						$objPHPExcel->setActiveSheetIndex(0);
						$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $branch_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $partner_manager);
						$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Adv/'.$advance->advance_no);
						$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, date('d/m/Y', $advance->date_advance));
						$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $advance->remarks);
						$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, '');
						$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, -$advance->amount);
						$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ($a_days >= 0 && $a_days <= 30 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, ($a_days >= 31 && $a_days <= 60 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, ($a_days >= 61 && $a_days <= 90 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, ($a_days >= 91 && $a_days <= 120 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, ($a_days >= 121 && $a_days <= 150 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, ($a_days >= 151 && $a_days <= 180 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, ($a_days >= 181 && $a_days <= 270 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, ($a_days >= 271 && $a_days <= 365 ? -$advance->amount : ''));
						$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, ($a_days > 365 ? -$advance->amount : ''));
						
						$objPHPExcel->getActiveSheet()->getStyle('D'.$i)
							->getNumberFormat()
							->setFormatCode(
								'd/mmm/y'  // my own personal preferred format that isn't predefined
							);
						
						/*----------- START - Set All Borders for Client Records ----------------*/
						$styleArray = array(
							  'borders' => array(
								  'allborders' => array(
									  'style' => PHPExcel_Style_Border::BORDER_THIN,
									  'color' => array('rgb' => 'FFC6AA')
								  )
							  )
						 );
						$excel_colum = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U');
						foreach ($excel_colum as $format_column) {
							$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->applyFromArray($styleArray);
						}
						/*----------- END - Set All Borders for Client Records ----------------*/
						
						$i++; // Move to Next Row
					}
					
					//Client Name Heading
					/*$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row->client_name);
					$styleArray = array(
						'font' => array(
							'bold' => true,
						)
					);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
					
					$cs1 = $i+1; // Remember New Starting Row number for Client Total
					$i++;*/
					$c++;
					$previous_client = $next_client; // For Next Client
				}
				
	
				$date1 = $row->bill_date;
				$date2 = time();
				$datediff = $date2 - $date1;
				$days = floor($datediff / (60 * 60 * 24));
	
				// Add some data
				$partner_manager = $row->partner_name;
				if($row->sr_manager_name != '') $partner_manager .= ' | '.$row->sr_manager_name;
				if($row->manager_name != '') $partner_manager .= ' | '.$row->manager_name;
				
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $row->branch_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $partner_manager);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, " " . $row->bill_no_str . " ");
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, date('d/m/Y', $row->bill_date));
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $row->remarks);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, $days);
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, ($row->currency_id!='2e323c67-38fd-58f7-9296-0344d01173a8' ? $row->currency_symbol.$total_amount_foreign_currency : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $total_billed_amount);
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $received);
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $remaining);
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ($days >= 0 && $days <= 30 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, ($days >= 31 && $days <= 60 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, ($days >= 61 && $days <= 90 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, ($days >= 91 && $days <= 120 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, ($days >= 121 && $days <= 150 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, ($days >= 151 && $days <= 180 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, ($days >= 181 && $days <= 270 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, ($days >= 271 && $days <= 365 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, ($days > 365 ? $remaining : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('T' . $i, $row->provided);
				$objPHPExcel->getActiveSheet()->SetCellValue('U' . $i, ($row->provided > 0 ? $row->overdue_remarks : ''));
				
				/* ----- START - Add Download LInk on Short Description Column -------- */
				$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getHyperlink()->setUrl(site_url("bills/simple_pdf3/{$row->id}/false"));
				$link_style_array = [
				  'font'  => [
					'color' => ['rgb' => '0000FF'],
					'underline' => 'single'
				  ]
				];
				$objPHPExcel->getActiveSheet()->getStyle('C'.$i)->applyFromArray($link_style_array);
				$objPHPExcel->getActiveSheet()->getComment('C'.$i)->getText()->createTextRun('Click to Download this Bill.');
				/* ----- END - Add Download LInk on Short Description Column -------- */
				
				/* ---------START- Set BG color of Outstanding Days Cell ------------- */
				if($days>90 && $days<365){
					$styleArray = array( 
						'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb'=>'FFFF00'),
							)
					);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($styleArray);
				}
				if($days >= 365){
					$styleArray = array( 
						'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb'=>'FF0000'),
							)
					);
					$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->applyFromArray($styleArray);
				}
				/* ---------END- Set BG color of Outstanding Days Cell ------------- */
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i)
						->getNumberFormat()
						->setFormatCode(
							'd/mmm/y'  // my own personal preferred format that isn't predefined
						);
				
				$format_code = "###,###,###,###";
				$excel_colum = array('H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T');
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
				}
				
				/*----------- START - Set All Borders for Client Records ----------------*/
				$styleArray = array(
					  'borders' => array(
						  'allborders' => array(
							  'style' => PHPExcel_Style_Border::BORDER_THIN,
							  'color' => array('rgb' => 'FFC6AA')
						  )
					  )
				 );
				$excel_colum = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U');
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->applyFromArray($styleArray);
				}
				/*----------- END - Set All Borders for Client Records ----------------*/
				
				$i++;
			}
		} // Main Branch Loop
		
		//Segment Name Heading
		$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Segment - '.implode(', ', $segment_names));
	
		/// ----------------------- START - Calculate Total of Only Last Client ----------------------- //
		$cs2 = $i-1; // Ending Row number of Last Client Total
		$gt[] = $i;  // Remember For Grand Total
		
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUM(J'.$cs1.':J'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUM(K'.$cs1.':K'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUM(L'.$cs1.':L'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUM(M'.$cs1.':M'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUM(N'.$cs1.':N'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUM(O'.$cs1.':O'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUM(P'.$cs1.':P'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUM(Q'.$cs1.':Q'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUM(R'.$cs1.':R'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUM(S'.$cs1.':S'.$cs2.')');
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUM(T'.$cs1.':T'.$cs2.')');
		
		$styleArray = array(
			'fill' => array(
					'type' => PHPExcel_Style_Fill::FILL_SOLID,
					'color' => array('rgb'=>'FBD67D'),
				),
			'borders' => array(
				'top' => array(
				  'style' => PHPExcel_Style_Border::BORDER_DOTTED
				),
				'bottom' => array(
				  'style' => PHPExcel_Style_Border::BORDER_DOTTED
				)
			)
		);
		$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
		$i++;
		$i++;
		
		//------------------ Check Other Advances (If No Bills) - START ---------------//
		if($user_id == ""){
		
			$advances = $this->Receipts_model->getAlladvancesEx($client_ids_ex);
			$prev_client = "";	
			$c = 0;
			foreach ($advances as $advance){
				
				$branch_name = $this->Branches_model->getBranchNameByID($advance->adv_branch_id);
				
				$next_client = $advance->client_id;
				//Client Name Heading
				if($prev_client != $next_client){
					
					if( $c > 0 ){
					
						$cs2 = $i-1; // Remember Ending Row number for Client Total
						$gt[] = $i;  // Remember For Grand Total
						
						/*----------- NOW SHOW CLIENT SUB TOTAL ROW ----------------*/
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUM(J'.$cs1.':J'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUM(K'.$cs1.':K'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUM(L'.$cs1.':L'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUM(M'.$cs1.':M'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUM(N'.$cs1.':N'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUM(O'.$cs1.':O'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUM(P'.$cs1.':P'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUM(Q'.$cs1.':Q'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUM(R'.$cs1.':R'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUM(S'.$cs1.':S'.$cs2.')');
						$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUM(T'.$cs1.':T'.$cs2.')');
						
						$styleArray = array(
							'fill' => array(
								'type' => PHPExcel_Style_Fill::FILL_SOLID,
								'color' => array('rgb'=>'FBD67D'),
							),
							'borders' => array(
								'top' => array(
								  'style' => PHPExcel_Style_Border::BORDER_DOTTED
								),
								'bottom' => array(
								  'style' => PHPExcel_Style_Border::BORDER_DOTTED
								)
							)
						);
						$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
						$i++;
						$i++;
						/*----------------------------------------------------------*/
						
					}
					
					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $advance->client_name);
					$styleArray = array(
						'font' => array(
							'bold' => true,
						)
					);
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i)->applyFromArray($styleArray);
					$cs1 = $i+1; // Remember Starting Row number for Client Total
					$i++;
				}
				
				$partner_manager = $advance->partner;
				if($advance->manager != '') $partner_manager .= ' | '.$advance->manager;
				
				$adv_date = $advance->date_advance;
				$cur_date = time();
				$datediff = $cur_date - $adv_date;
				$a_days = floor($datediff / (60 * 60 * 24));
				
				$objPHPExcel->setActiveSheetIndex(0);
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $branch_name);
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $partner_manager);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, 'Adv/'.$advance->advance_no);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, date('d/m/Y', $advance->date_advance));
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, $advance->remarks);
				$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, '');
				$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, -$advance->amount);
				$objPHPExcel->getActiveSheet()->SetCellValue('K' . $i, ($a_days >= 0 && $a_days <= 30 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('L' . $i, ($a_days >= 31 && $a_days <= 60 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('M' . $i, ($a_days >= 61 && $a_days <= 90 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('N' . $i, ($a_days >= 91 && $a_days <= 120 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('O' . $i, ($a_days >= 121 && $a_days <= 150 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('P' . $i, ($a_days >= 151 && $a_days <= 180 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('Q' . $i, ($a_days >= 181 && $a_days <= 270 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('R' . $i, ($a_days >= 271 && $a_days <= 365 ? -$advance->amount : ''));
				$objPHPExcel->getActiveSheet()->SetCellValue('S' . $i, ($a_days > 365 ? -$advance->amount : ''));
				
				$objPHPExcel->getActiveSheet()->getStyle('D'.$i)
					->getNumberFormat()
					->setFormatCode(
						'd/mmm/y'  // my own personal preferred format that isn't predefined
					);
				
				/*----------- START - Set All Borders for Client Records ----------------*/
				$styleArray = array(
					  'borders' => array(
						  'allborders' => array(
							  'style' => PHPExcel_Style_Border::BORDER_THIN,
							  'color' => array('rgb' => 'FFC6AA')
						  )
					  )
				 );
				$excel_colum = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U');
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->applyFromArray($styleArray);
				}
				/*----------- END - Set All Borders for Client Records ----------------*/
				$prev_client = $next_client;
				$c++;
				$i++; // Move to Next Row
			}
			
			//------------------ Check Other Advances (with No Bills) - END ---------------//
			
			/// ----------------------- START - Calculate Total of Only Last Client (Advances) ----------------------- //
			if( $c > 0 ) {
				$cs2 = $i-1; // Ending Row number of Last Client Total
				$gt[] = $i;  // Remember For Grand Total
				
				$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUM(J'.$cs1.':J'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUM(K'.$cs1.':K'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUM(L'.$cs1.':L'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUM(M'.$cs1.':M'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUM(N'.$cs1.':N'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUM(O'.$cs1.':O'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUM(P'.$cs1.':P'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUM(Q'.$cs1.':Q'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUM(R'.$cs1.':R'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUM(S'.$cs1.':S'.$cs2.')');
				$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUM(T'.$cs1.':T'.$cs2.')');
				
				$styleArray = array(
					'fill' => array(
							'type' => PHPExcel_Style_Fill::FILL_SOLID,
							'color' => array('rgb'=>'FBD67D'),
						),
					'borders' => array(
						'top' => array(
						  'style' => PHPExcel_Style_Border::BORDER_DOTTED
						),
						'bottom' => array(
						  'style' => PHPExcel_Style_Border::BORDER_DOTTED
						)
					)
				);
				$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
				$i++;
				$i++;
			}
		
		}
		
		/* ----------------------- START - GRAND TOTAL ROW ----------------------------*/
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.$i, 'Gross Receivable');
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.($i+1), 'Less Provision');
		$objPHPExcel->getActiveSheet()->SetCellValue('I'.($i+2), 'Net Receivable');
		$GrandTotalstyle = array('font' => array('bold' => true,));
		$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($GrandTotalstyle);
		$objPHPExcel->getActiveSheet()->getStyle('I'.($i+2))->applyFromArray($GrandTotalstyle);
		
		$col_J = array();	$col_K = array();	$col_L = array();	$col_M = array();	$col_N = array();	
		$col_O = array();	$col_P = array();	$col_Q = array();	$col_R = array();	$col_S = array();	$col_T = array();
		
		foreach($gt as $k => $V){
			$col_J[] = 'J'.$V;	$col_K[] = 'K'.$V;	$col_L[] = 'L'.$V;	$col_M[] = 'M'.$V;	$col_N[] = 'N'.$V;	
			$col_O[] = 'O'.$V;	$col_P[] = 'P'.$V;	$col_Q[] = 'Q'.$V;	$col_R[] = 'R'.$V;	$col_S[] = 'S'.$V;	$col_T[] = 'T'.$V;
		}
		
		$objPHPExcel->getActiveSheet()->setCellValue('J'.$i, '=SUM('.implode(',',$col_J).')');
		$objPHPExcel->getActiveSheet()->setCellValue('K'.$i, '=SUM('.implode(',',$col_K).')');
		$objPHPExcel->getActiveSheet()->setCellValue('L'.$i, '=SUM('.implode(',',$col_L).')');
		$objPHPExcel->getActiveSheet()->setCellValue('M'.$i, '=SUM('.implode(',',$col_M).')');
		$objPHPExcel->getActiveSheet()->setCellValue('N'.$i, '=SUM('.implode(',',$col_N).')');
		$objPHPExcel->getActiveSheet()->setCellValue('O'.$i, '=SUM('.implode(',',$col_O).')');
		$objPHPExcel->getActiveSheet()->setCellValue('P'.$i, '=SUM('.implode(',',$col_P).')');
		$objPHPExcel->getActiveSheet()->setCellValue('Q'.$i, '=SUM('.implode(',',$col_Q).')');
		$objPHPExcel->getActiveSheet()->setCellValue('R'.$i, '=SUM('.implode(',',$col_R).')');
		$objPHPExcel->getActiveSheet()->setCellValue('S'.$i, '=SUM('.implode(',',$col_S).')');
		$objPHPExcel->getActiveSheet()->setCellValue('T'.$i, '=SUM('.implode(',',$col_T).')');
		//Less Provision
		$objPHPExcel->getActiveSheet()->setCellValue('J'.($i+1), '=-'.'T'.$i);
		$objPHPExcel->getActiveSheet()->setCellValue('J'.($i+2), '='.'J'.$i.'+'.'J'.($i+1));
		
		$objPHPExcel->getActiveSheet()->getStyle('J'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('J'.($i+1))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('J'.($i+2))->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('K'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('L'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('M'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('N'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('O'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('P'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('Q'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('R'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->applyFromArray($styleArray);
		$objPHPExcel->getActiveSheet()->getStyle('T'.$i)->applyFromArray($styleArray);
		/* ----------------------- END - GRAND TOTAL ROW ----------------------------*/
		
		$i++;
		/// ----------------------- END - Calculate Total of Only Last Client ----------------------- //		
		
        // Save Excel 2007 file
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		
		if($filename){
			$filename=$_SERVER['DOCUMENT_ROOT'].$setting->path_name."/attachments/".$filename."";
			$objWriter->save($filename);
		} else {
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Outstanding Reports - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');	
		}		
		
    }
	
	
	public function bill_register_email($err_msg = '') {
        
		if($this->input->post('submit')){
			
			$variables = $this->variables;
			$period = $_REQUEST['bill_date_from'].' to '.$_REQUEST['bill_date_to'];
						
			$email_settings = $this->Settings_model->getBranchSettings($this->user['branch_id']);
			$from = $email_settings->from_email;
			
			$users = $this->input->post('users');
	
			if(count(array_filter($users, 'strlen')) > 0){
			
				foreach($users as $user_id){
	
					$user = $this->Users_model->getUserByID($user_id);
					$params = $this->Settings_model->getReportParamsBr($user_id);
					
					//$download_link = site_url("reports/excel").'/'.$user_id;
					$filename = "Bill Register - ".time().".xlsx";
					$this->excel($user_id, $filename, $params);
					
					$email_body = 
						'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml"> 
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>EYFRSH Billing Management System</title>
						</head>
						<body>
							<p>Dear Sir,</p>
							<p>Please find attached bill register from '. $period .' for your record.</p>
							<br>
							<p>Regards,</p>
							<p>EYFRSH Billing Management System</p>
						</body>
						</html>';
					
					$email_subject = 'Download your Bill Register from '.$period.'.';
					
					if(!empty($params->from_email)){
						$from_email = $params->from_email;
					} elseif(!empty($from_email_global)) {
						$from_email = $from_email_global;
					} else {
						$from_email = 'Niaz.Ahmed@pk.ey.com';
					}
					
					$to_email = explode(',',$user->email_address);
					foreach($to_email as $key=>$value){
						$to[] = $value;
					}
					
					$cc == array();
					if(!empty($params->cc_email)){	//$cc[] = $params->cc_email;
						$cc_email = explode(',',$params->cc_email);
						foreach($cc_email as $key=>$value){
							$cc[] = $value;
						}
					}
					
					$bcc == array();
					if(!empty($params->bcc_email)){	//$cc[] = $params->cc_email;
						$bcc_email = explode(',',$params->bcc_email);
						foreach($bcc_email as $key=>$value){
							$bcc[] = $value;
						}
					}
					
					if($this->input->post('cc1') != '') $cc[] = $this->input->post('cc1');
					if($this->input->post('cc2') != '') $cc[] = $this->input->post('cc2');
					if($this->input->post('cc3') != '') $cc[] = $this->input->post('cc3');
					
					$to[] = $user->email_address;
					if(!empty($email_settings->bill_register_email1)) $bcc[] = $email_settings->bill_register_email1;
					if(!empty($email_settings->bill_register_email2)) $bcc[] = $email_settings->bill_register_email2;
					//$bcc[] = 'Niaz.Ahmed@pk.ey.com';
					//echo $email_body.'<hr>'; die;
					$filename = $email_settings->path_name."/attachments/".$filename."";
					$this->Email_model->send_email($email_body, $email_subject, $from, $to, $cc, $bcc, $filename);
					
					unset($to);
					unset($bcc);
				}
				redirect('reports/bill_register_email/msg1');
				
			} else {
			
				redirect('reports/bill_register_email/err1');
			
			}
		}
		
        // Views data
        $data = array(
            'page' => 'reports/bill_register_email',
            'users' => $this->Users_model->getAllUsers($pagination_config),
            'links' => $this->pagination->create_links(),
			'err_msg' => $err_msg,
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	
	public function outstanding_email($err_msg = '') {
        
		if($this->input->post('submit')){
			
			$email_settings = $this->Settings_model->getBranchSettings($this->user['branch_id']);
			$from_email_global = $email_settings->from_email;
			
			$users = $this->input->post('users');
	
			if(count(array_filter($users, 'strlen')) > 0){
			
			/// ------ For Each Selected User Get Outstanding Data and Insert Records in Temp Table ---------//	
				foreach($users as $user_id){
	
					$user = $this->Users_model->getUserByID($user_id);
					$params = $this->Settings_model->getReportParams($user_id);
					
				//---- END - Loop To Insert Data in Temp Table----------------//
					
					//$report_url = site_url("reports/outstanding_excel_email/{$user_id}");
					$filename = "Outstanding Report - ".time().".xlsx";
					$this->outstanding_excel_email($user_id, $filename);
						 
					//<p>Dear '.$user->full_name.',</p>
					$body_txt = $email_settings->os_email_body;
					
					$email_body = 
						'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml"> 
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>EYFRSH Billing Management System</title>
						</head>
						<body>
							<p>'.$body_txt.'</p>
							<br>
							<p>Regards,</p>
							<p>EYFRSH Billing Management System</p>
						</body>
						</html>';
					//echo $email_body; die;
					$email_subject = $email_settings->os_email_subject;
					
					if(!empty($params->from_email)){
						$from_email = $params->from_email;
					} elseif(!empty($from_email_global)) {
						$from_email = $from_email_global;
					} else {
						$from_email = 'Niaz.Ahmed@pk.ey.com';
					}
					
					$to_email = explode(',',$user->email_address);
					foreach($to_email as $key=>$value){
						$to[] = $value;
					}
					
					$cc == array();
					if(!empty($params->cc_email)){	//$cc[] = $params->cc_email;
						$cc_email = explode(',',$params->cc_email);
						foreach($cc_email as $key=>$value){
							$cc[] = $value;
						}
					}
					
					$bcc == array();
					if(!empty($params->bcc_email)){	//$cc[] = $params->cc_email;
						$bcc_email = explode(',',$params->bcc_email);
						foreach($bcc_email as $key=>$value){
							$bcc[] = $value;
						}
					}
					
					//if(!empty($params->bcc_email))	$bcc[] = $params->bcc_email;
					
					if($this->input->post('cc1') != '') $cc[] = $this->input->post('cc1');
					if($this->input->post('cc2') != '') $cc[] = $this->input->post('cc2');
					if($this->input->post('cc3') != '') $cc[] = $this->input->post('cc3');
					
					if(!empty($email_settings->os_report_email1)) $bcc[] = $email_settings->os_report_email1;
					if(!empty($email_settings->os_report_email2)) $bcc[] = $email_settings->os_report_email2;
					
					$filename = $email_settings->path_name."/attachments/".$filename."";
				
					$this->Email_model->send_email($email_body, $email_subject, $from_email, $to, $cc, $bcc, $filename);
					
					unset($to);
					unset($cc);
					unset($bcc);
				}
				redirect('reports/outstanding_email/msg1');
				
			} else {
			
				redirect('reports/outstanding_email/err1');
			
			}
		}
		
        // Views data
        $data = array(
            'page' => 'reports/outstanding_email',
            'users' => $this->Users_model->getAllUsers($pagination_config),
            'links' => $this->pagination->create_links(),
			'err_msg' => $err_msg,
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	
	public function send_long_overdue_email($client_id, $segment_id) {
		
		$client_name = $this->Clients_model->getClientsNameByID($client_id);
		$bills = $this->Bills_model->client_os_position($client_id, $segment_id);
		$result = array();
		foreach ($bills as $bill) {
			$result[$bill->client_id][] = $bill;
		}
		$client_os_position_html = '';
		if(sizeof($result)){
			//$client_os_position_html .= "Outstanding receivable position of the client <strong>".$client_name."</strong> as on ".date('d-M-Y').".<br>";
			$client_os_position_html .= '<table cellpadding="2" cellspacing="0" width="100%" style="font-size:12px;" border="1">
											<tr>
												<th>Segment</th>
												<th>Partner</th>
												<th>Engagement Team</th>
												<th>Bill No</th>
												<th>Bill Date</th>        
												<th>Remarks</th>
												<th align="right">Billed</th>
												<th align="right">Partially<br>Received</th>
												<th align="right">Outstanding</th>
												<th>Status</th>
												<th>Outstanding<br>Days</th>
												<th>Remarks</th>
											</tr>';
			$total_billed_amount = 0;
			$outstanding_balance = 0;
			foreach($result as $bills){
				foreach ($bills as $row){
					
					$et_array = array();
					if($row->sr_manager_name!='' && $row->sr_manager_name!=NULL){
						$et_array[] = $row->sr_manager_name;
					}
					if($row->manager_name!='' && $row->manager_name!=NULL){
						$et_array[] = $row->manager_name;
					}
					
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
					
					$total_billed_amount += $row->total_amount/$row->currency_rate;
					$total_billed_text = number_format($row->total_amount);
					if($currency_symbol != 'Rs') 
						$total_billed_text .= '<br>[='.$currency_symbol.($row->total_amount/$row->currency_rate).']';
					
					$received_text = '';
					if($received > 0){
						$received_text = number_format($received); 
						if($currency_symbol != 'Rs') 
							$received_text .= '<br>[='.$currency_symbol.($received/$row->currency_rate).']';
					}
					
					$outstanding_balance += $remaining/$row->currency_rate;
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
					
					$client_os_position_html .= '<tr>
													<td align="center">'.$row->segment_name.'</td>
													<td align="center">'.$row->partner_name.'</td>
													<td align="center">'.implode(' | ', $et_array).'</td>
													<td align="center">'.$row->bill_no_str.'</td>
													<td align="center">'.date('d-M-y', $row->bill_date).'</td>
													<td>'.$row->remarks.'</td>
													<td align="right">'.$total_billed_text.'</td>
													<td align="right">'.$received_text.'</td>
													<td align="right">'.$remaining_text.'</td>
													<td align="center">'.$status.'</td>
													<td align="center">'.($status=='O/S' ? $days : '').'</td>
													<td align="center">'.$receipts_remarks.'</td>
												</tr>';										
				}
			}
			$client_os_position_html .= '<tr>
											<td align="center"></td>
											<td align="center"></td>
											<td align="center"></td>
											<td align="center"></td>
											<td align="center"></td>
											<td></td>
											<td align="right" style="border-top:1pt solid; border-bottom:1pt solid;"><strong>'.number_format($total_billed_amount*$currency_rate).'</strong></td>
											<td align="right"></td>
											<td align="right" style="border-top:1pt solid; border-bottom:1pt solid;"><strong>'.number_format($outstanding_balance*$currency_rate).'</strong></td>
											<td align="center"></td>
											<td align="center"></td>
											<td align="center"></td>
										</tr>';
			$client_os_position_html .= '</table>';
			
			$email_body = 
						'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml"> 
						<head>
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<title>EYFRSH Billing Management System</title>
						</head>
						<body>
							<p>Dear Sir,</p>
							<p>This is to inform you that from <strong>'.$client_name.'</strong> we have longoverdue above 90 days. Outstanding receivable position for this client as on '.date('d-M-Y').'</p>
							<p>'.$client_os_position_html.'</p>
							<br>
							<p>Regards,</p>
							<p>EYFRSH Billing Management System</p>
						</body>
						</html>';
			
		}
		echo $email_body;
		
	}
	
	public function user_outstanding_report($user_id) {
        
			$user = $this->Users_model->getUserByID($user_id);
			$bills = $this->Reports_model->getUsersOutstanding($user_id);
			$result = array();
			foreach ($bills as $bill) {
				$result[$bill->client_id][] = $bill;
			}
								
		//---- START - Loop To Insert Data in Temp Table----------------//
		
			// Create Session ID for Birt Report Temp Table
			$bill_session_id = time().'_'.rand();
			$zeros = array('0000', '000', '00', '0', '');
			$grand_total = array('total' => 0, 'received' => 0, '1-30' => 0, '31-60' => 0, '61-120' => 0, '121-180' => 0, '181-365' => 0, '365-' => 0);
			
			foreach ($result as $bills) :
				$i = 0;
				$total = array('total' => 0, 'received' => 0, '1-30' => 0, '31-60' => 0, '61-120' => 0, '121-180' => 0, '181-365' => 0, '365-' => 0);
				
				foreach ($bills as $row) :
				
					$total_billed_currency = '';
					$received_currency = '';
					$remaining_currency = '';
					
					// Get Currency Symbol
					$currency_symbol = '';
					$query = $this->db->query("SELECT symbol FROM currencies WHERE id = '{$row->currency_id}' LIMIT 1");
					if ($query->num_rows()) {
						$rowC = $query->row();
						$currency_symbol = $rowC->symbol;
					}							
					if($currency_symbol != 'Rs'){
						$total_billed_currency = $row->total_amount/$row->currency_rate;
					}
					
					$received = empty($row->amount_received) ? 0 : $row->amount_received;
					if($currency_symbol != 'Rs'){
						$received_currency = $received/$row->currency_rate;
					}
					
					$total_billed_amount = $row->total_amount+$row->total_credit_amount;
					
					$remaining = empty($row->outstanding) ? ($row->total_amount+$row->total_credit_amount) : $row->outstanding;
					if($currency_symbol != 'Rs'){
						$remaining_currency = $remaining/$row->currency_rate;
					}
					
					$date1 = $row->bill_date;
					$date2 = time();
					$datediff = $date2 - $date1;
					$days = floor($datediff / (60 * 60 * 24));
					
					//If Outstanding Amount is ZERO, Stop Here and Go Back to Start Again, Do Not Enter Record
					if ($remaining <= 0) :
						continue;
					endif;
					
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
					
					// Aging of Data
					$grand_total['total'] = $grand_total['total'] + $row->total_amount;
					$grand_total['received'] = $grand_total['received'] + $received;
					if ($days >= 0 && $days <= 30) {
						$grand_total['1-30'] = $grand_total['1-30'] + $remaining;
					} elseif ($days >= 31 && $days <= 60) {
						$grand_total['31-60'] = $grand_total['31-60'] + $remaining;
					} elseif ($days >= 61 && $days <= 120) {
						$grand_total['61-120'] = $grand_total['61-120'] + $remaining;
					} elseif ($days >= 121 && $days <= 180) {

						$grand_total['121-180'] = $grand_total['121-180'] + $remaining;
					} elseif ($days >= 181 && $days <= 365) {
						$grand_total['181-365'] = $grand_total['181-365'] + $remaining;
					} elseif ($days >= 365) {
						$grand_total['365-'] = $grand_total['365-'] + $remaining;
					}

					$total['total'] = $total['total'] + $row->total_amount;
					$total['received'] = $total['received'] + $received;
					if ($days >= 0 && $days <= 30) {
						$total['1-30'] = $total['1-30'] + $remaining;
					} elseif ($days >= 31 && $days <= 60) {
						$total['31-60'] = $total['31-60'] + $remaining;
					} elseif ($days >= 61 && $days <= 120) {
						$total['61-120'] = $total['61-120'] + $remaining;
					} elseif ($days >= 121 && $days <= 180) {
						$total['121-180'] = $total['121-180'] + $remaining;
					} elseif ($days >= 181 && $days <= 365) {
						$total['181-365'] = $total['181-365'] + $remaining;
					} elseif ($days >= 365) {
						$total['365-'] = $total['365-'] + $remaining;
					}
					
					// --- Insert Record in Temp Table for Birt Report --- //
					$bill_no_string = $row->bill_no_str;
					if($days >= 0 && $days <= 30) $a130 = $remaining; else $a130 = 0;
					if($days >= 31 && $days <= 60) $a3160 = $remaining; else $a3160 = 0;
					if($days >= 61 && $days <= 120) $a3190 = $remaining; else $a3190 = 0;
					if($days >= 121 && $days <= 180) $a91120 = $remaining; else $a91120 = 0;
					if($days >= 181 && $days <= 365) $a121150 = $remaining; else $a121150 = 0;
					if($days >= 365) $a151 = $remaining; else $a151 = 0;							
							
					mysql_query("INSERT INTO bills_outstanding_temp VALUES ('','".$bill_session_id."','".addslashes($row->client_name)."','".addslashes($collector_name)."','".addslashes( $row->segment_name)."','".addslashes($row->partner_name)."','".addslashes($row->manager_name)."','".addslashes($row->sr_manager_name)."','".addslashes($bill_no_string)."','".date('d-M-y', $row->bill_date)."','".addslashes($row->remarks)."','".$total_billed_amount."','".$total_billed_currency."','".$received."','".$received_currency."','".$remaining."','".$remaining_currency."','".$currency_symbol."','".$a130."','".$a3160."','".$a3190."','".$a91120."','".$a121150."','".$a151."','".addslashes($receipts_remarks)."', CURDATE())");
					// --------------------------------------------------------------- //
					
					$i++;
				endforeach;
			endforeach;
			//---- END - Loop To Insert Data in Temp Table----------------//
			
			$show_collector = 0;
			$params = $this->Settings_model->getReportParams($user_id);
			if(!empty($params->collector_id)){
				$show_collector = 1;
			}
			
			$settings = $this->Settings_model->getBranchSettings($this->user['branch_id']);
		
			$report_url = "http://".$_SERVER['HTTP_HOST'].":8080/birt-viewer/frameset?__report=".$settings->path_name."/bills_outstanding.rptdesign&session_id=".$bill_session_id."&show_collector=".$show_collector;
			
			header('Location: '.$report_url.'');
			exit();	
    }
	
	
	public function outstanding_email_long_overdue($action = '') {
				
		$data = array(
            'page' => 'reports/outstanding_long_overdue',
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
            'segments' => $this->Segments_model->getAllSegments(0),
			'variables' => $this->variables,
            'outstanding' => array(),
        );

        if (isset($_REQUEST['search-bills-submit'])) {
			
			$bills = $this->Reports_model->getOutstanding_overdue();
            $result = array();
            foreach ($bills as $bill) {
                $result[$bill->client_id][] = $bill;
            }
			
            $data['result'] = $result;
            $data['page'] = 'reports/outstanding_long_overdue';
        }
        $this->load->view('layout/v2/default', $data);
		
	}
	
	
	public function download_bb() {
		
		$sql_connect = $this->Shapps_model->connect_sqlserver();
		
		if($sql_connect != 'Failed')
		{
			
			/* -----------------------KARACHI BANKS ---------------------------*/
			$mcb_pidc_op_balance = $this->Shapps_model->get_opening_balance('12800200020', $sql_connect);
			$scb_op_balance 	= $this->Shapps_model->get_opening_balance('12800200035', $sql_connect);
			$scb_sadiq_op_balance = $this->Shapps_model->get_opening_balance('12800200036', $sql_connect);
			$scb_usd_op_balance = $this->Shapps_model->get_opening_balance('12800200037', $sql_connect);
			$nbp_op_balance 	= $this->Shapps_model->get_opening_balance('12800200030', $sql_connect);
			$hbl_op_balance 	= $this->Shapps_model->get_opening_balance('12800200015', $sql_connect);
			$meezan_op_balance 	= $this->Shapps_model->get_opening_balance('12800200025', $sql_connect);
			$albaraka_op_balance = $this->Shapps_model->get_opening_balance('12800200001', $sql_connect);
			
			$mcb_pidc_details 	= $this->Shapps_model->get_bank_details('12800200020', $sql_connect);
			$scb_details 		= $this->Shapps_model->get_bank_details('12800200035', $sql_connect);
			$scb_sadiq_details 	= $this->Shapps_model->get_bank_details('12800200036', $sql_connect);
			$scb_usd_details 	= $this->Shapps_model->get_bank_details('12800200037', $sql_connect);
			$nbp_details 		= $this->Shapps_model->get_bank_details('12800200030', $sql_connect);
			$hbl_details 	= $this->Shapps_model->get_bank_details('12800200015', $sql_connect);
			$meezan_details 	= $this->Shapps_model->get_bank_details('12800200025', $sql_connect);
			$albaraka_details 	= $this->Shapps_model->get_bank_details('12800200001', $sql_connect);
			
			/*A-Baraka Bank*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $albaraka_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
			$balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $albaraka_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$albaraka_balance = $balance;
			
			/*MCB PIDC Bank*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $mcb_pidc_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $mcb_pidc_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$mcb_pidc_balance = $balance;
			
			/*MEEZAN Bank*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $meezan_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $meezan_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$meezan_balance = $balance;
			
			/*HBL Bank*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $hbl_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $hbl_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$hbl_balance = $balance;
			
			/*Scb, Main*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $scb_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $scb_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$scb_balance = $balance;
			
			/*Sadiq*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $scb_sadiq_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $scb_sadiq_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$scb_sadiq_balance = $balance;
			
			/*SCB USD*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $scb_usd_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $scb_usd_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$scb_usd_balance = $balance;
			
			/*NBP*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $nbp_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $nbp_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$nbp_balance = $balance;
			
			
			/* -----------------------LAHORE BANKS ---------------------------*/
			$dib_lhr_op_balance = $this->Shapps_model->get_opening_balance('12800200050', $sql_connect);
			$meezan_lhr_op_balance 	= $this->Shapps_model->get_opening_balance('12800200060', $sql_connect);
			$nib_lhr_op_balance = $this->Shapps_model->get_opening_balance('12800200065', $sql_connect);
			$scb_lhr_op_balance = $this->Shapps_model->get_opening_balance('12800200070', $sql_connect);
			
			$dib_lhr_details 	= $this->Shapps_model->get_bank_details('12800200050', $sql_connect);
			$meezan_lhr_details 		= $this->Shapps_model->get_bank_details('12800200060', $sql_connect);
			$nib_lhr_details 	= $this->Shapps_model->get_bank_details('12800200065', $sql_connect);
			$scb_lhr_details 	= $this->Shapps_model->get_bank_details('12800200070', $sql_connect);
			
			/*DIB*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $dib_lhr_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $dib_lhr_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$dib_lhr_balance = $balance;
			
			/*Meezan LHR*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $meezan_lhr_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $meezan_lhr_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$meezan_lhr_balance = $balance;
			
			/*NIB*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $nib_lhr_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $nib_lhr_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$nib_lhr_balance = $balance;
			
			/*SCB LHR*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $scb_lhr_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $scb_lhr_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$scb_lhr_balance = $balance;
			
			
			/* -----------------------ISLAMABAD BANKS ---------------------------*/
			$mcb_isb_op_balance = $this->Shapps_model->get_opening_balance('12800200200', $sql_connect);
			$scb_isb_op_balance 	= $this->Shapps_model->get_opening_balance('12800200201', $sql_connect);
			$mcb_isb_details 	= $this->Shapps_model->get_bank_details('12800200200', $sql_connect);
			$scb_isb_details 		= $this->Shapps_model->get_bank_details('12800200201', $sql_connect);
			
			/*MCB ISB*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $mcb_isb_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $mcb_isb_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$mcb_isb_balance = $balance;
			
			/*SCB ISB*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $scb_isb_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $scb_isb_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$scb_isb_balance = $balance;
			
			
			/* -----------------------KABUL BANKS ---------------------------*/
			$hbl_kabul_usd_op_balance = $this->Shapps_model->get_opening_balance('12800200090', $sql_connect);
			$hbl_kabul_afs_op_balance = $this->Shapps_model->get_opening_balance('12800200091', $sql_connect);
			$hbl_kabul_usd_details 	= $this->Shapps_model->get_bank_details('12800200090', $sql_connect);
			$hbl_kabul_afs_details 	= $this->Shapps_model->get_bank_details('12800200091', $sql_connect);
			
			/*HBL Kabul USD*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $hbl_kabul_usd_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $hbl_kabul_usd_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$hbl_kabul_usd_balance = $balance;
			
			/*HBL Kabul (Afs)*/
			$balance = 0;
			$row_op = sqlsrv_fetch_array( $hbl_kabul_afs_op_balance, SQLSRV_FETCH_ASSOC);
            $openning_balance = $row_op['prev_dr_sum'] - $row_op['prev_cr_sum'];
            $balance = $openning_balance;
			
			while( $row = sqlsrv_fetch_array( $hbl_kabul_afs_details, SQLSRV_FETCH_ASSOC) ){
				$balance = $balance + $row['VD_DR_AMT'] - $row['VD_CR_AMT'];	
			}
			$hbl_kabul_afs_balance = $balance;
			
			
			
			/** Include path * */
			$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
			//ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');
	
			/** PHPExcel */
			include_once $basepath . 'PHPExcel.php';
	
			/** PHPExcel_Writer_Excel2007 */
			include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
	
			// Create new PHPExcel object
			//$objPHPExcel = new PHPExcel();
			
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/bank_balances_template2.xlsx");
	
			// Set properties
			$objPHPExcel->getProperties()->setCreator("Earnst & Young");
			$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
			$objPHPExcel->getProperties()->setTitle("Banks Details");
			$objPHPExcel->getProperties()->setSubject("Banks Details");
			$objPHPExcel->getProperties()->setDescription("Banks Details");
	
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
			
			//PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'Dated: '.date("d/M/Y").' Time: '.date("h:i"));
			
			/*Karachi*/
			$objPHPExcel->getActiveSheet()->SetCellValue('C10', $albaraka_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C11', $mcb_pidc_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C12', $meezan_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C13', $hbl_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C14', $scb_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C15', $scb_sadiq_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C16', $scb_usd_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C17', $nbp_balance);
			$objPHPExcel->getActiveSheet()->setCellValue('C18', '=SUM(C10:C17)');
			
			/*Lahore*/
			$objPHPExcel->getActiveSheet()->SetCellValue('C21', $dib_lhr_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C22', $meezan_lhr_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C23', $nib_lhr_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C24', $scb_lhr_balance);
			$objPHPExcel->getActiveSheet()->setCellValue('C25', '=SUM(C21:C24)');
			
			/*Islamabad*/
			$objPHPExcel->getActiveSheet()->SetCellValue('C28', $mcb_isb_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C29', $scb_isb_balance);
			$objPHPExcel->getActiveSheet()->setCellValue('C30', '=SUM(C28:C29)');
			
			/*Kabul*/
			$objPHPExcel->getActiveSheet()->SetCellValue('C33', $hbl_kabul_usd_balance);
			$objPHPExcel->getActiveSheet()->SetCellValue('C34', $hbl_kabul_afs_balance);
			$objPHPExcel->getActiveSheet()->setCellValue('C35', '=SUM(C33:C34)');
			
			/*Grand Total*/
			$objPHPExcel->getActiveSheet()->setCellValue('C37', '=C18+C25+C30+C35');
			
			/*Format Columns for Numbers*/
			$format_code = "###,###,###,###";
			for ($n = 10; $n <= 37; $n++) {
				$objPHPExcel->getActiveSheet()->getStyle('C'.$n)->getNumberFormat()->setFormatCode($format_code);
			} 
			
			// Rename sheet
			//$objPHPExcel->getActiveSheet()->setTitle('Banks Details');
	
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Bank Balances - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
			
		} else {
			redirect('dashboard');	
		}
		
    }
	
	public function outstanding_excel_download() {
		
		// Generating data for segments
        $all_segments = array();
        $segments = $this->Segments_model->getAllSegments();
        foreach ($segments as $segment) {
			
			if(in_array($segment->id, $this->userSegments) || ($this->user['short_code'] == 'admin')){
		
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
		
		
        $data = array(
            'page' => 'reports/outstanding-excel-download',
			'managers' => $this->Users_model->getUserByRoleName('Manager'),
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
			'sr_managers' => $this->Users_model->getUserByRoleName('Sr. Manager'),
            'segments' => $this->Segments_model->getAllSegments(0),
			'all_segments' => json_encode($all_segments),
			'variables' => $this->variables,
            'bills' => isset($_REQUEST['search-bills-submit']) ? $this->Reports_model->getAllBillsM(array('bill','memo','debit','credit')) : array(),
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	public function outstanding_excel_full() {
		
		// Generating data for segments
        $all_segments = array();
        $segments = $this->Segments_model->getAllSegments();
        foreach ($segments as $segment) {
			
			if(in_array($segment->id, $this->userSegments) || ($this->user['short_code'] == 'admin')){
		
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
		
		
        $data = array(
            'page' => 'reports/outstanding-excel-full',
			'managers' => $this->Users_model->getUserByRoleName('Manager'),
            'partners' => $this->Users_model->getUserByRoleName('Partner'),
			'sr_managers' => $this->Users_model->getUserByRoleName('Sr. Manager'),
            'segments' => $this->Segments_model->getAllSegments(0),
			'generated_reports' => $this->Reports_model->getAllGeneratedReports('bill_outstanding_excel'),
			'all_segments' => json_encode($all_segments),
			'variables' => $this->variables,
			'sectors' => $this->Clients_model->getAllSectors(),
			'industries' => $this->Clients_model->getAllIndustries(),
            'bills' => isset($_REQUEST['search-bills-submit']) ? $this->Reports_model->getAllBillsM(array('bill','memo','debit','credit')) : array(),
        );
        $this->load->view('layout/v2/default', $data);
    }
	
	public function outstanding_excel_pw() {
		
		if (isset($_REQUEST['search-bills-submit'])) {
		
			/** Include path * */
			$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
			//ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');
	
			/** PHPExcel */
			include_once $basepath . 'PHPExcel.php';
	
			/** PHPExcel_Writer_Excel2007 */
			include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
			
			/** Load Excel Template File */
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/os_pw_template.xlsx");
			
			// Create new PHPExcel object
			//$objPHPExcel = new PHPExcel();
	
			// Set properties
			$objPHPExcel->getProperties()->setCreator("Earnst & Young");
			$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
	
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'Receivable Position as on: '.date("d M Y").', Time '.date("h:i:sa"));
			
			$zeros = array('0000', '000', '00', '0', '');
			
			$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
			$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
			$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
			$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
			
			$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
			$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
			$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
			$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
			$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
			$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
			
			foreach ($_REQUEST['fields'] as $field_name => $field_value) {
				
				if ($field_name == 'clients-segments') {
                
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL SEGMENTS ---- //
					} else {
						unset($segment_arr);
						foreach ($field_value as $semgnet_id) {
							if($semgnet_id == '1216e429-7acc-5462-b648-9a5e4264eac7')	$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
							if($semgnet_id == 'ffffffdb-fda3-5749-99e0-e97559020b24')	$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
							if($semgnet_id == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5')	$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
							if($semgnet_id == 'c3732f54-c736-5fb8-979d-be7fa46d03c2')	$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
							if($semgnet_id == '9574c203-61d1-5348-87c5-906a59623fe0')	$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
							if($semgnet_id == 'e7946507-8721-527b-a7ee-3630cb7ac04a')	$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
						}
					}
				}
				
				else if ($field_name == 'branch-name2') {
				
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL Branches ---- //
					} else {
						unset($branch_arr);
						foreach ($field_value as $branch_id) {
							if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
							if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
							if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
							if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
						}
					}
				}
				
			}
			$objPHPExcel->getActiveSheet()->SetCellValue('A3', implode(',',$segment_arr).' ('.$_REQUEST['days_overdue'].' Days Above)');
			
			$i = 7; // Start from 2nd Row in Excel Sheet
			$c = 0;
			$gt = array();
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
				),
				'borders' => array(
					'allborders' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				)
			);
			
			$styleArray2 = array(
				  'borders' => array(
					  'allborders' => array(
						  'style' => PHPExcel_Style_Border::BORDER_THIN
					  )
				  )
			  );
			$format_code = "###,###,###,###"; // Number Format for Values
			
			foreach($branch_arr as $branch_id => $branch_name){
								
				$data = $this->Reports_model->getOutstanding_excel('', $branch_id);
				
				foreach ($data as $row) {
					
					$total_billed_amount = $row->total_amount+$row->total_credit_amount;
					$total_amount_foreign_currency = $total_billed_amount/$row->currency_rate;
					$received = empty($row->amount_received) ? 0 : $row->amount_received;
					$remaining = empty($row->outstanding) ? ($row->total_amount+$row->total_credit_amount) : $row->outstanding;
					$remaining = round($remaining);
					if(!($remaining > 0 || $remaining < 0)) {
						continue;
					}
					
					if($c == 0){
						$cs1 = $i; // Remember Starting Row for Partner Sub Total
						$prv_partner = $row->partner_name;	
					}
					$next_partner = $row->partner_name;
					
					// Show Partner ws Sub-Total Row
					if($prv_partner != $next_partner){
						
						$cs2 = $i - 1; // Ending Row for Partner Sub Total
						$gt[] = $i; //  Rember Row for Grand Total Calculations
						
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '=SUBTOTAL(9,I'.$cs1.':I'.$cs2.')');
						$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($styleArray);
						$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode($format_code);
						$i = $i+2;
						$cs1 = $i; // Remember Starting Row for Partner Sub Total
					}
										
					$date1 = $row->bill_date;
					$date2 = time();
					$datediff = $date2 - $date1;
					$days = floor($datediff / (60 * 60 * 24));
					
					if( $row->manager_name!='' && $row->sr_manager_name!='' ) $managers = $row->sr_manager_name.' | '.$row->manager_name;
					else if( $row->sr_manager_name!= '' ) $managers = $row->sr_manager_name;
					else if( $row->manager_name != '' ) $managers = $row->manager_name;
					else $managers = '-';
		
					// Add some data
					$objPHPExcel->setActiveSheetIndex(0);
					$objPHPExcel->getActiveSheet()->SetCellValue('A' . $i, $branch_name);
					$objPHPExcel->getActiveSheet()->SetCellValue('B' . $i, $row->client_name);
					$objPHPExcel->getActiveSheet()->SetCellValue('C' . $i, $row->partner_name);
					$objPHPExcel->getActiveSheet()->SetCellValue('D' . $i, $managers);
					$objPHPExcel->getActiveSheet()->SetCellValue('E' . $i, " " . $row->bill_no_str . " ");
					$objPHPExcel->getActiveSheet()->SetCellValue('F' . $i, date('d/m/Y', $row->bill_date));
					$objPHPExcel->getActiveSheet()->SetCellValue('G' . $i, $total_billed_amount);
					$objPHPExcel->getActiveSheet()->SetCellValue('H' . $i, $received);
					$objPHPExcel->getActiveSheet()->SetCellValue('I' . $i, $remaining);
					$objPHPExcel->getActiveSheet()->SetCellValue('J' . $i, $days);
					
					$objPHPExcel->getActiveSheet()->getStyle('F'.$i)
							->getNumberFormat()
							->setFormatCode(
								'd/mmm/y'  // my own personal preferred format that isn't predefined
							);
					
					$excel_colum = array('G', 'H', 'I');
					foreach ($excel_colum as $format_column) {
						$objPHPExcel->getActiveSheet()->getStyle($format_column . $i)->getNumberFormat()->setFormatCode($format_code);
					}
					
					$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':J'.$i)->applyFromArray($styleArray2); // Apply Dotted Border
					$i++;
					
					$c++;
					$prv_partner = $next_partner;
				}
				
			} // --- Main Branch Loop ---- //
			
			$cs2 = $i - 1; // Ending Row for Partner Sub Total
			$gt[] = $i; //  Rember Row for Grand Total Calculations
			
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '=SUBTOTAL(9,I'.$cs1.':I'.$cs2.')');
			$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode($format_code);
			$i = $i+2;
			
			// Print Grand Total
			$col_I = array();
			foreach($gt as $k => $v){
				$col_I[] = 'I'.$v;
			}
			$objPHPExcel->getActiveSheet()->setCellValue('I'.$i, '=SUM('.implode(',',$col_I).')');
			$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('I'.$i)->getNumberFormat()->setFormatCode($format_code);
			// Rename sheet
			//$objPHPExcel->getActiveSheet()->setTitle('Outstanding Reports');
	
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
			//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
			//ob_end_clean();
			
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Outstanding Report Partner Ws - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
		
		}
		else {
			// Generating data for segments
			$all_segments = array();
			$segments = $this->Segments_model->getAllSegments();
			foreach ($segments as $segment) {
				
				if(in_array($segment->id, $this->userSegments) || ($this->user['short_code'] == 'admin')){
			
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
			
			
			$data = array(
				'page' => 'reports/outstanding-partner-ws',
				'managers' => $this->Users_model->getUserByRoleName('Manager'),
				'partners' => $this->Users_model->getUserByRoleName('Partner'),
				'sr_managers' => $this->Users_model->getUserByRoleName('Sr. Manager'),
				'segments' => $this->Segments_model->getAllSegments(0),
				'all_segments' => json_encode($all_segments),
				'variables' => $this->variables,
				'sectors' => $this->Clients_model->getAllSectors(),
				'industries' => $this->Clients_model->getAllIndustries(),
				'bills' => isset($_REQUEST['search-bills-submit']) ? $this->Reports_model->getAllBillsM(array('bill','memo','debit','credit')) : array(),
			);
			$this->load->view('layout/v2/default', $data);
		}
    }
	
	public function segments_partners_os_sum() {
        
		if (isset($_REQUEST['search-bills-submit'])) {
			
			/** Include path * */
			$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
			//ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');
	
			/** PHPExcel */
			include_once $basepath . 'PHPExcel.php';
	
			/** PHPExcel_Writer_Excel2007 */
			include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
			
			/** Load Excel Template File */
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/segment_partner_pivot.xlsx");
			
			// Create new PHPExcel object
			//$objPHPExcel = new PHPExcel();
	
			// Set properties
			$objPHPExcel->getProperties()->setCreator("Earnst & Young");
			$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
			$objPHPExcel->getProperties()->setTitle("Outstanding Summary");
			$objPHPExcel->getProperties()->setSubject("Outstanding Summary");
			$objPHPExcel->getProperties()->setDescription("Outstanding Summary");
	
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
	
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			
			// Date Values for Data Query
			$date_from = $this->Reports_model->get_date($_REQUEST['fields']['bills-bill_date']['from']);
			$date_to = $this->Reports_model->get_date($_REQUEST['fields']['bills-bill_date']['to']);
			
			// Date Values for Display
			$from_dt = str_replace('/','-',$_REQUEST['fields']['bills-bill_date']['from']);
			$to_dt = str_replace('/','-',$_REQUEST['fields']['bills-bill_date']['to']);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'From: '.$from_dt. ' To '.$to_dt);
			$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Report Date/time: '.date("d-M-Y h:i:s"));
			
			$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
			$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
			$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
			$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
			
			$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
			$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
			$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
			$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
			$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
			$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
			
			foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
				if ($field_name == 'clients-segments') {
                
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL SEGMENTS ---- //
					} else {
						unset($segment_arr);
						foreach ($field_value as $semgnet_id) {
							if($semgnet_id == '1216e429-7acc-5462-b648-9a5e4264eac7')	$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
							if($semgnet_id == 'ffffffdb-fda3-5749-99e0-e97559020b24')	$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
							if($semgnet_id == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5')	$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
							if($semgnet_id == 'c3732f54-c736-5fb8-979d-be7fa46d03c2')	$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
							if($semgnet_id == '9574c203-61d1-5348-87c5-906a59623fe0')	$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
							if($semgnet_id == 'e7946507-8721-527b-a7ee-3630cb7ac04a')	$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
						}
					}
				}
				
				if ($field_name == 'branch-name') {
                
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL Branches ---- //
					} else {
						unset($branch_arr);
						foreach ($field_value as $branch_id) {
							if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
							if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
							if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
							if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
						}
					}
				}
				
			}
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
				)
			);
			
			$styleArray2 = array(
				  'borders' => array(
					  'allborders' => array(
						  'style' => PHPExcel_Style_Border::BORDER_DOTTED
					  )
				  )
			  );
			
			$r = 6; // Data Starting Row in Excel Sheet
			
			foreach($branch_arr as $branch_id => $branch_name){
			
				foreach($segment_arr as $segment_id => $segment_name){
					
					$data = $this->Reports_model->getOutstanding_segments_partner_sum($segment_id, $branch_id);		
					
					foreach ($data as $row) {
									
						$total_billed_amount = $row->total_amount+$row->total_credit_amount;
						$total_amount_foreign_currency = $total_billed_amount/$row->currency_rate;
						$received = empty($row->amount_received) ? 0 : $row->amount_received;
						$remaining = empty($row->outstanding) ? ($row->total_amount+$row->total_credit_amount) : $row->outstanding;
						$remaining = round($remaining);
						if(!($remaining > 0 || $remaining < 0)) {
							continue;
						}
						
						$date1 = $row->bill_date;
						$date2 = time();
						$datediff = $date2 - $date1;
						$days = floor($datediff / (60 * 60 * 24));
						
						if($days >= 0 && $days <= 30){
							$partner[$row->partner_name]['1-30'] += $remaining;
						}
						if($days >= 31 && $days <= 60){
							$partner[$row->partner_name]['31-60'] += $remaining;
						}
						if($days >= 61 && $days <= 90){
							$partner[$row->partner_name]['61-90'] += $remaining;
						}
						if($days >= 91 && $days <= 120){
							$partner[$row->partner_name]['91-120'] += $remaining;
						}
						if($days >= 121 && $days <= 150){
							$partner[$row->partner_name]['121-150'] += $remaining;
						}
						if($days >= 151 && $days <= 180){
							$partner[$row->partner_name]['151-180'] += $remaining;
						}
						if($days >= 181 && $days <= 270){
							$partner[$row->partner_name]['181-270'] += $remaining;
						}
						if($days >= 271 && $days <= 365){
							$partner[$row->partner_name]['271-365'] += $remaining;
						}
						if($days > 365){
							$partner[$row->partner_name]['365'] += $remaining;
						}
									
					}
					
					foreach($partner as $k => $pp){  //PP - Partner Period
						$objPHPExcel->getActiveSheet()->SetCellValue('A'.$r, $branch_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('B'.$r, $segment_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$r, $k);
						foreach($pp as $k => $v){
							if($k == '1-30')	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$r, $v);
							if($k == '31-60')	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$r, $v);
							if($k == '61-90')	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$r, $v);
							if($k == '91-120')	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$r, $v);
							if($k == '121-150')	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$r, $v);
							if($k == '151-180')	$objPHPExcel->getActiveSheet()->SetCellValue('J'.$r, $v);
							if($k == '181-270')	$objPHPExcel->getActiveSheet()->SetCellValue('K'.$r, $v);
							if($k == '271-365')	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$r, $v);
							if($k == '365')		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$r, $v);
						}
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, '=SUM(E'.$r.':M'.$r.')');
						
						$format_code = "###,###,###,###";
						$excel_colum = array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
						foreach ($excel_colum as $format_column) {
							$objPHPExcel->getActiveSheet()->getStyle($format_column . $r)->getNumberFormat()->setFormatCode($format_code);
						}
						
						$objPHPExcel->getActiveSheet()->getStyle('A'.$r.':M'.$r)->applyFromArray($styleArray2);
						
						$r++;
					}
					unset($partner);
					
				}	// ---- Segment Loop ---- //
			
			} // ---- Branch Loop ---- //
			
			// Rename sheet
			//$objPHPExcel->getActiveSheet()->setTitle('Outstanding Reports');
	
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Outstanding Summary Segment-Partner - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
			
		} else {
			
			$data = array(
				'page' => 'reports/segment-partner-wise-summary',
				'segments' => $this->Segments_model->getAllSegments(0),
				'variables' => $this->variables
			);
			$this->load->view('layout/v2/default', $data);
			
		}
    }
	
	public function segments_partners_receipts_sum() {
        
		if (isset($_REQUEST['search-bills-submit'])) {
			
			/** Include path * */
			$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
			//ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');
	
			/** PHPExcel */
			include_once $basepath . 'PHPExcel.php';
	
			/** PHPExcel_Writer_Excel2007 */
			include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
			
			/** Load Excel Template File */
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/receipts_segment_partner_ws.xlsx");
			
			// Create new PHPExcel object
			//$objPHPExcel = new PHPExcel();
	
			// Set properties
			$objPHPExcel->getProperties()->setCreator("Earnst & Young");
			$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
			$objPHPExcel->getProperties()->setTitle("Outstanding Summary");
			$objPHPExcel->getProperties()->setSubject("Outstanding Summary");
			$objPHPExcel->getProperties()->setDescription("Outstanding Summary");
	
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
	
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			
			// Date Values for Data Query
			$date_from = $this->Reports_model->get_date($_REQUEST['fields']['bills-receipt_date']['from']);
			$date_to = $this->Reports_model->get_date($_REQUEST['fields']['bills-receipt_date']['to']);
			
			// Date Values for Display
			$from_dt = str_replace('/','-',$_REQUEST['fields']['bills-receipt_date']['from']);
			$to_dt = str_replace('/','-',$_REQUEST['fields']['bills-receipt_date']['to']);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A3', 'From: '.$from_dt. ' To '.$to_dt);
			$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'Report Date/time: '.date("d-M-Y h:i:s"));
			
			$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
			$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
			$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
			$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
			
			$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
			$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
			$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
			$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
			$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
			$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
			
			foreach ($_REQUEST['fields'] as $field_name => $field_value) {
           		
				if ($field_name == 'clients-segments') {
                
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL SEGMENTS ---- //
					} else {
						unset($segment_arr);
						foreach ($field_value as $semgnet_id) {
							if($semgnet_id == '1216e429-7acc-5462-b648-9a5e4264eac7')	$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
							if($semgnet_id == 'ffffffdb-fda3-5749-99e0-e97559020b24')	$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
							if($semgnet_id == 'ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5')	$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
							if($semgnet_id == 'c3732f54-c736-5fb8-979d-be7fa46d03c2')	$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
							if($semgnet_id == '9574c203-61d1-5348-87c5-906a59623fe0')	$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
							if($semgnet_id == 'e7946507-8721-527b-a7ee-3630cb7ac04a')	$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
						}
					}
				}
				
				if ($field_name == 'branch-name') {
                
					if(in_array('All',$field_value)){
						// --- JUST SELECT ALL Branches ---- //
					} else {
						unset($branch_arr);
						foreach ($field_value as $branch_id) {
							if($branch_id == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9')	$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
							if($branch_id == 'fffffff7-efd8-59ef-bb91-926565e01b1a')	$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
							if($branch_id == '0ff6e36c-e866-50bb-92d4-c45dc0740351')	$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
							if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908')	$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
						}
					}
				}
				
			}
			
			$styleArray = array(
				'font' => array(
					'bold' => true,
				)
			);
			
			$r = 6; // Starting Row in Excel Sheet
			
			foreach($branch_arr as $branch_id => $branch_name){
								
				foreach($segment_arr as $segment_id => $segment_name){
					
					$data = $this->Reports_model->getReceipts_segments_partner_sum($segment_id, $branch_id);
					
					foreach ($data as $row) {
						
						$amount_received = $row->amount_received;
						
						$date1 = $row->bill_date;
						$date2 = $row->receipt_date;
						$datediff = $date2 - $date1;
						$days = floor($datediff / (60 * 60 * 24));
						
						if($days >= 0 && $days <= 30){
							$partner[$row->partner_name]['1-30'] += $amount_received;
						}
						if($days >= 31 && $days <= 60){
							$partner[$row->partner_name]['31-60'] += $amount_received;
						}
						if($days >= 61 && $days <= 90){
							$partner[$row->partner_name]['61-90'] += $amount_received;
						}
						if($days >= 91 && $days <= 120){
							$partner[$row->partner_name]['91-120'] += $amount_received;
						}
						if($days >= 121 && $days <= 150){
							$partner[$row->partner_name]['121-150'] += $amount_received;
						}
						if($days >= 151 && $days <= 180){
							$partner[$row->partner_name]['151-180'] += $amount_received;
						}
						if($days >= 181 && $days <= 270){
							$partner[$row->partner_name]['181-270'] += $amount_received;
						}
						if($days >= 271 && $days <= 365){
							$partner[$row->partner_name]['271-365'] += $amount_received;
						}
						if($days > 365){
							$partner[$row->partner_name]['365'] += $amount_received;
						}
									
					}
										
					foreach($partner as $k => $pp){  //PP - Partner Period
						$objPHPExcel->getActiveSheet()->SetCellValue('A'.$r, $branch_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('B'.$r, $segment_name);
						$objPHPExcel->getActiveSheet()->SetCellValue('C'.$r, $k);
						foreach($pp as $k => $v){
							if($k == '1-30')	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$r, $v);
							if($k == '31-60')	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$r, $v);
							if($k == '61-90')	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$r, $v);
							if($k == '91-120')	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$r, $v);
							if($k == '121-150')	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$r, $v);
							if($k == '151-180')	$objPHPExcel->getActiveSheet()->SetCellValue('J'.$r, $v);
							if($k == '181-270')	$objPHPExcel->getActiveSheet()->SetCellValue('K'.$r, $v);
							if($k == '271-365')	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$r, $v);
							if($k == '365')		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$r, $v);
						}
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, '=SUM(E'.$r.':M'.$r.')');
						
						$format_code = "###,###,###,###";
						$excel_colum = array('D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
						foreach ($excel_colum as $format_column) {
							$objPHPExcel->getActiveSheet()->getStyle($format_column . $r)->getNumberFormat()->setFormatCode($format_code);
						}
						$r++;
					}
					//$r++;
					unset($partner);
					
				} // ---- Segment Loop ---- //
				
			} // ---- Branch Loop ---- //
			
			// Rename sheet
			//$objPHPExcel->getActiveSheet()->setTitle('Receipts Summary');
	
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Receipts Summary Segment-Partner - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
			
		} else {
			
			$data = array(
				'page' => 'reports/receipts-segment-partner-wise-sum',
				'segments' => $this->Segments_model->getAllSegments(0),
				'variables' => $this->variables
			);
			$this->load->view('layout/v2/default', $data);
			
		}
    }
	
	public function consolidated_os_summary() {
        
		if (isset($_REQUEST['search-bills-submit'])) {
			
			$bill_date_to = $_REQUEST['fields']['bills-bill_date']['to'];
			$date_to_stamp = $this->Reports_model->get_to_date($bill_date_to);
			//Get Doller Rate for Kabul Totals
			$doller = $this->Currencies_model->getCurrencyByID("a6f0e94e-f190-5b8b-a32a-0ab0916e4b5e");
			
			/** Include path * */
			$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
			//ini_set('include_path', ini_get('include_path') . ';' . $basepath . '/application/libraries/');
	
			/** PHPExcel */
			include_once $basepath . 'PHPExcel.php';
	
			/** PHPExcel_Writer_Excel2007 */
			include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
			
			/** Load Excel Template File */
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/outstanding_report_consolidated.xlsx");
			
			// Create new PHPExcel object
			//$objPHPExcel = new PHPExcel();
	
			// Set properties
			$objPHPExcel->getProperties()->setCreator("Earnst & Young");
			$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
			//$objPHPExcel->getProperties()->setTitle("Sheet1");
			$objPHPExcel->getProperties()->setSubject("Outstanding Summary");
			$objPHPExcel->getProperties()->setDescription("Outstanding Summary");
	
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
						
			$objPHPExcel->getActiveSheet()->SetCellValue('A4', 'As at: '.date("d-M-Y h:i:s"));
			
			if($this->user['branch_id'] == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'){
				$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
				$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
				$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
				$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
			} else {
				if($this->user['branch_id'] == '0ff6e36c-e866-50bb-92d4-c45dc0740351'){ $branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad'; }
				if($this->user['branch_id'] == 'fffffff7-efd8-59ef-bb91-926565e01b1a'){ $branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore'; }
				if($this->user['branch_id'] == '271aad4d-9ee8-58b3-8658-961f18a9b908'){ $branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul'; }
			}
			
			$segment_arr['1216e429-7acc-5462-b648-9a5e4264eac7'] = 'Assurance';
			$segment_arr['ffffffdb-fda3-5749-99e0-e97559020b24'] = 'TAX';
			$segment_arr['ffffffd3-55e2-5afa-aa3a-d77b1cb61dd5'] = 'Advisory';
			$segment_arr['9574c203-61d1-5348-87c5-906a59623fe0'] = 'ITRA';
			$segment_arr['c3732f54-c736-5fb8-979d-be7fa46d03c2'] = 'TAS';
			//$segment_arr['e7946507-8721-527b-a7ee-3630cb7ac04a'] = 'Others';
						
			$r = 11; // Data Starting Row in Excel Sheet
			
			$C40 = 0;
			$E40 = 0;
			$F40 = 0;
			$G40 = 0;
			$H40 = 0;
			$I40 = 0;
			$J40 = 0;
			$K40 = 0;
			$L40 = 0;
			$M40 = 0;
			
			foreach($branch_arr as $branch_id => $branch_name){
			
				foreach($segment_arr as $segment_id => $segment_name){
					
					$data = $this->Reports_model->getOutstanding_segments_partner_sum($segment_id, $branch_id);		
					
					foreach ($data as $row) {
									
						$total_billed_amount = $row->total_amount+$row->total_credit_amount;
						$total_amount_foreign_currency = $total_billed_amount/$row->currency_rate;
						$received = empty($row->amount_received) ? 0 : $row->amount_received;
						$remaining = empty($row->outstanding) ? ($row->total_amount+$row->total_credit_amount) : $row->outstanding;
						$remaining = round($remaining);
						if(!($remaining > 0 || $remaining < 0)) {
							continue;
						}
						
						$date1 = $row->bill_date;
						$date2 = $date_to_stamp;
						$datediff = $date2 - $date1;
						$days = floor($datediff / (60 * 60 * 24));
						
						$segment['total'] += $remaining;
						
						if($days >= 0 && $days <= 30){
							$segment['1-30'] += $remaining;
						}
						if($days >= 31 && $days <= 60){
							$segment['31-60'] += $remaining;
						}
						if($days >= 61 && $days <= 90){
							$segment['61-90'] += $remaining;
						}
						if($days >= 91 && $days <= 120){
							$segment['91-120'] += $remaining;
						}
						if($days >= 121 && $days <= 150){
							$segment['121-150'] += $remaining;
						}
						if($days >= 151 && $days <= 180){
							$segment['151-180'] += $remaining;
						}
						if($days >= 181 && $days <= 270){
							$segment['181-270'] += $remaining;
						}
						if($days >= 271 && $days <= 365){
							$segment['271-365'] += $remaining;
						}
						if($days > 365){
							$segment['365'] += $remaining;
						}
									
					}
					
					foreach($segment as $k => $v){
						if($k == 'total')	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$r, $v);
						if($k == '1-30')	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$r, $v);
						if($k == '31-60')	$objPHPExcel->getActiveSheet()->SetCellValue('F'.$r, $v);
						if($k == '61-90')	$objPHPExcel->getActiveSheet()->SetCellValue('G'.$r, $v);
						if($k == '91-120')	$objPHPExcel->getActiveSheet()->SetCellValue('H'.$r, $v);
						if($k == '121-150')	$objPHPExcel->getActiveSheet()->SetCellValue('I'.$r, $v);
						if($k == '151-180')	$objPHPExcel->getActiveSheet()->SetCellValue('J'.$r, $v);
						if($k == '181-270')	$objPHPExcel->getActiveSheet()->SetCellValue('K'.$r, $v);
						if($k == '271-365')	$objPHPExcel->getActiveSheet()->SetCellValue('L'.$r, $v);
						if($k == '365')		$objPHPExcel->getActiveSheet()->SetCellValue('M'.$r, $v);	
					}
					
					if($branch_id == "271aad4d-9ee8-58b3-8658-961f18a9b908"){
						
						foreach($segment as $k => $v){
							if($k == 'total')	$C40 += $v;
							if($k == '1-30')	$E40 += $v;
							if($k == '31-60')	$F40 += $v;
							if($k == '61-90')	$G40 += $v;
							if($k == '91-120')	$H40 += $v;
							if($k == '121-150')	$I40 += $v;
							if($k == '151-180')	$J40 += $v;
							if($k == '181-270')	$K40 += $v;
							if($k == '271-365')	$L40 += $v;
							if($k == '365')		$M40 += $v;
						}
					}
					
					$r++;
					unset($segment);
					
				}	// ---- Segment Loop ---- //
				$r += 3;
			
			} // ---- Branch Loop ---- //
						
			//NOW MULTIPLY DOLLER RATE  AND GET PKR TOTAL IN NEW ROW
			$objPHPExcel->getActiveSheet()->SetCellValue('C42', $C40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('E42', $E40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('F42', $F40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('G42', $G40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('H42', $H40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('I42', $I40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('J42', $J40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('K42', $K40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('L42', $L40*$doller->rate);
			$objPHPExcel->getActiveSheet()->SetCellValue('M42', $M40*$doller->rate);
			
			// Rename sheet
			//$objPHPExcel->getActiveSheet()->setTitle('Outstanding Reports');
	
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="Consolidated Outstanding Summary - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
			
		} else {
			
			$data = array(
				'page' => 'reports/consolidated-os-summary',
				'segments' => $this->Segments_model->getAllSegments(0),
				'variables' => $this->variables
			);
			$this->load->view('layout/v2/default', $data);
			
		}
    }
	
	
	public function revenue_comparison() {
		
		if (isset($_REQUEST['search-bills-submit'])) {
						
			/** Include path * */
			$basepath = str_replace('system/', '', BASEPATH) . '/application/libraries/';
	
			/** PHPExcel */
			include_once $basepath . 'PHPExcel.php';
	
			/** PHPExcel_Writer_Excel2007 */
			include_once $basepath . 'PHPExcel/Writer/Excel2007.php';
			
			/** Load Excel Template File */
			$objPHPExcel = PHPExcel_IOFactory::load($basepath."PHPExcel/revenue_comparison_template.xlsx");
			
			// Set properties
			$objPHPExcel->getProperties()->setCreator("Earnst & Young");
			$objPHPExcel->getProperties()->setLastModifiedBy("Earnst & Young");
			//$objPHPExcel->getProperties()->setTitle("Sheet1");
			$objPHPExcel->getProperties()->setSubject("Outstanding Summary");
			$objPHPExcel->getProperties()->setDescription("Outstanding Summary");
	
			// Add some data
			$objPHPExcel->setActiveSheetIndex(0);
			PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
			
			//CY
			$date_from_stamp = $this->Reports_model->get_to_date( $_REQUEST['fields']['billing_date']['from'] );
			$date_to_stamp = $this->Reports_model->get_to_date( $_REQUEST['fields']['billing_date']['to'] );
			//LY
			$dt_arr_from = explode('/',$_REQUEST['fields']['billing_date']['from']);
			$dt_arr_to = explode('/',$_REQUEST['fields']['billing_date']['to']);
			$date_from_stamp_ly = $this->Reports_model->get_to_date( $dt_arr_from[0].'/'.$dt_arr_from[1].'/'.($dt_arr_from[2]-1) );
			$date_to_stamp_ly = $this->Reports_model->get_to_date( $dt_arr_to[0].'/'.$dt_arr_to[1].'/'.($dt_arr_to[2]-1) );
			
			$start_date = date('Y-m-d', $date_from_stamp);
			$end_date = date('Y-m-d', $date_to_stamp);
			$period = $this->Settings_model->Get_Date_Difference($start_date, $end_date);
			
			$segment_id = $_POST['fields']['clients-segments'][0];
			$segment_name = $this->Segments_model->getSegmentName($segment_id);			
			
			//Get Doller Rate for Kabul Totals
			$doller = $this->Currencies_model->getCurrencyByID("a6f0e94e-f190-5b8b-a32a-0ab0916e4b5e");
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B3', $segment_name.' Revenue - Comparison CY vs LY');
			$objPHPExcel->getActiveSheet()->SetCellValue('H3', 'Dated: '.date("d-M-y"));
			$objPHPExcel->getActiveSheet()->SetCellValue('D9', $period.' ');
			$objPHPExcel->getActiveSheet()->SetCellValue('F9', $period.' ');
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B4', 'CY: '.date("d-M-Y",$date_from_stamp).' --to-- '.date("d-M-Y",$date_to_stamp));
			$objPHPExcel->getActiveSheet()->SetCellValue('B5', 'LY: '.date("d-M-Y",$date_from_stamp_ly).' --to-- '.date("d-M-Y",$date_to_stamp_ly));
			$objPHPExcel->getActiveSheet()->SetCellValue('H9', date("M-Y",$this->variables['last_financial_year_start']).' to '.date("M-Y",$this->variables['last_financial_year_end']));
			
			if($this->user['branch_id'] == 'c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'){
				
				$branch_arr['c7f8273e-a4fa-5487-bf2d-78de1c6df7d9'] = 'Karachi';
				$branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore';
				$branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad';
				
				if($segment_id != 'c3732f54-c736-5fb8-979d-be7fa46d03c2'){
					$branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul';
				}
			} else {
				if($this->user['branch_id'] == '0ff6e36c-e866-50bb-92d4-c45dc0740351'){ $branch_arr['0ff6e36c-e866-50bb-92d4-c45dc0740351'] = 'Islamabad'; }
				if($this->user['branch_id'] == 'fffffff7-efd8-59ef-bb91-926565e01b1a'){ $branch_arr['fffffff7-efd8-59ef-bb91-926565e01b1a'] = 'Lahore'; }
				if($this->user['branch_id'] == '271aad4d-9ee8-58b3-8658-961f18a9b908'){ $branch_arr['271aad4d-9ee8-58b3-8658-961f18a9b908'] = 'Kabul'; }
			}
			
  //-------------------- FEE CURRENT YEAR MONTHS -------------------------------------//
			$headingStyle = array(
				'font' => array(
					'bold' => true,
				)
			);
			$borderStyle = array(
				  'borders' => array(
					  'allborders' => array(
						  'style' => PHPExcel_Style_Border::BORDER_THIN
					  )
				  )
			 );
			$format_code = "###,###,###,###";
			$excel_colum = array('D', 'F', 'H');
			
			$grand_total_D = array();
			$grand_total_F = array();
			$grand_total_H = array();
			$dt_arr = array('r1', 'r2', 'r3');				
			$r = 11;		
			foreach($branch_arr as $branch_id => $branch_name){
				
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$r, $branch_name);
				$objPHPExcel->getActiveSheet()->getStyle('B'.$r)->applyFromArray($headingStyle);
				$r = $r+2;
				
				foreach($dt_arr as $k => $date_range){
				
					//$data = $this->Reports_model->getRevenuSummary($segment_id, $branch_id, $date_range);	
					$data = $this->Reports_model->getRevenuSummary($branch_id, $date_range);
				
					$i = 2;
					foreach ($data as $row) {
						
						$bill_no_str = $row['bill_no_str'];
						
						if($i == 2){
							$bill_no_prev = $bill_no_str;
							$bill_no_next = $bill_no_str;
						} else {
							$bill_no_prev = $bill_no_next;
							$bill_no_next = $bill_no_str;
							if($bill_no_prev==$bill_no_next && $row['type']=='bill'){ continue; }
						}
						
						if($row['type']=='bill'){
							$fee = $row['total_fees']*$row['currency_rate'];
						} else {
							$fee = $row['fee'];
						}
						
						//$value[$row['partner']][$date_range] += $row['total_fees']*$row['currency_rate'];
						$value[$row['partner']][$date_range] += $fee;
						$i++;			
					} // ---- Data Loop ---- //
				
				} // ---- Date Range Loop ---- //
				
				$startCell = $r;
				foreach($value as $partner => $df){
					
					$objPHPExcel->getActiveSheet()->SetCellValue('B'.$r, $partner);
					
					// IF ALL THERE COLUMN VALUES ARE ZERO OR EMPTY DON'T WRITE ANYTHING JUST SKIPE.
					if( ($df['r1']=='' || $df['r1']==0) && ($df['r2']=='' || $df['r2']==0) && ($df['r3']=='' || $df['r3']==0) ){
						continue;	
					}
			
					// IN CASE OF KABUL - MULTIPLY DOLLER RATE TO GET PKR VALUE
					if($branch_id == '271aad4d-9ee8-58b3-8658-961f18a9b908'){
					 	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$r, ($df['r1']*$doller->rate));
						$objPHPExcel->getActiveSheet()->SetCellValue('F'.$r, ($df['r2']*$doller->rate));
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$r, ($df['r3']*$doller->rate));
					} else {
						$objPHPExcel->getActiveSheet()->SetCellValue('D'.$r, $df['r1']);
						$objPHPExcel->getActiveSheet()->SetCellValue('F'.$r, $df['r2']);
						$objPHPExcel->getActiveSheet()->SetCellValue('H'.$r, $df['r3']);
					}
					
					foreach ($excel_colum as $format_column) {
						$objPHPExcel->getActiveSheet()->getStyle($format_column . $r)->getNumberFormat()->setFormatCode($format_code);
					}
					$r = $r+1;
				}
				$endCell = $r-1;
	
				$objPHPExcel->getActiveSheet()->SetCellValue('B'.$r, $branch_name." Total");
				$objPHPExcel->getActiveSheet()->getStyle('B'.$r)->applyFromArray($headingStyle);
				
				$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, '=SUM(D'.$startCell.':D'.$endCell.')');
				$objPHPExcel->getActiveSheet()->getStyle('D'.$r)->applyFromArray($headingStyle);
				$grand_total_D[] = 'D'.$r;
				
				$objPHPExcel->getActiveSheet()->setCellValue('F'.$r, '=SUM(F'.$startCell.':F'.$endCell.')');
				$objPHPExcel->getActiveSheet()->getStyle('F'.$r)->applyFromArray($headingStyle);
				$grand_total_F[] = 'F'.$r;
				
				$objPHPExcel->getActiveSheet()->setCellValue('H'.$r, '=SUM(H'.$startCell.':H'.$endCell.')');
				$objPHPExcel->getActiveSheet()->getStyle('H'.$r)->applyFromArray($headingStyle);
				$grand_total_H[] = 'H'.$r;
				
				foreach ($excel_colum as $format_column) {
					$objPHPExcel->getActiveSheet()->getStyle($format_column . $r)->getNumberFormat()->setFormatCode($format_code);
				}
				
				$r = $r+2;
				unset($value);
			} // ---- Branch Loop ---- //
			
			/*--------- SHOW GRAND TOTALS - START ----------*/
			
			$cellBorders = array(
				'borders' => array(
					'top' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					),
					'bottom' => array(
					  'style' => PHPExcel_Style_Border::BORDER_THICK
					)
				)
			);
			
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$r, " Total ".$segment_name);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$r)->applyFromArray($headingStyle);
			
			$objPHPExcel->getActiveSheet()->setCellValue('D'.$r, '=SUM('.implode('+',$grand_total_D).')');
			$objPHPExcel->getActiveSheet()->getStyle('D'.$r)->applyFromArray($headingStyle);
			$objPHPExcel->getActiveSheet()->getStyle('D'.$r)->applyFromArray($cellBorders);
			
			$objPHPExcel->getActiveSheet()->setCellValue('F'.$r, '=SUM('.implode('+',$grand_total_F).')');
			$objPHPExcel->getActiveSheet()->getStyle('F'.$r)->applyFromArray($headingStyle);
			$objPHPExcel->getActiveSheet()->getStyle('F'.$r)->applyFromArray($cellBorders);
			
			$objPHPExcel->getActiveSheet()->setCellValue('H'.$r, '=SUM('.implode('+',$grand_total_H).')');
			$objPHPExcel->getActiveSheet()->getStyle('H'.$r)->applyFromArray($headingStyle);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$r)->applyFromArray($cellBorders);
			
			foreach ($excel_colum as $format_column) {
				$objPHPExcel->getActiveSheet()->getStyle($format_column . $r)->getNumberFormat()->setFormatCode($format_code);
			}
						
			/*--------- SHOW GRAND TOTALS - END ----------*/
			
			// Save Excel 2007 file
			$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
	
			// We'll be outputting an excel file
			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename="'.$segment_name.' Revenu  - ' . date('d/m/Y') . '.xlsx"');
			$objWriter->save('php://output');
			
		} else {
			
			$data = array(
				'page' => 'reports/revenue_comparison',
				'segments' => $this->Segments_model->getAllSegments(0),
				'variables' => $this->variables
			);
			$this->load->view('layout/v2/default', $data);
			
		}
    }
	
	
	public function delete_report($session_id = 0) {
		
		$this->db->delete('bill_outstanding_excel', array('session_id' => $session_id));
		redirect('reports/outstanding_excel_full');
		
	}
	
	public function delete_report_br($session_id = 0) {
		
		$this->db->delete('bill_register_excel', array('session_id' => $session_id));
		redirect('reports/bill_register');
		
	}
	
}
