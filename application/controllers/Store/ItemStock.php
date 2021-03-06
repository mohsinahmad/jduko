<?php

class ItemStock extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemCategoryModel');
        $this->load->model('DonationTypeModel');
        $this->load->model('ItemSetupModel');
        $this->load->model('ItemReturnModel');
        $this->load->model('ItemIssueModel');
        $this->load->model('StockReciveModel');
    }

    public function index()
    {
        $data['category'] = $this->ItemCategoryModel->get_categoreis_for_ladger();
		
		//echo '<pre>'.$this->db->last_query();		
		//print_r($data['category']);
		//exit();
        $data['donation'] = $this->DonationTypeModel->donation_type();
        $this->load->view('Store/header');
        $this->load->view('Store/reports/ItemStock/GetItemStock' ,$data);
        $this->load->view('Store/footer');
    }
    public function form_number(){

        $item_codes = $this->ItemSetupModel->get_last_nubmer_for_form();

        echo json_encode($item_codes);

    }
    public function GetItemStock()
    {

        // echo '<pre>';
        // print_r($_POST);

        $item_codes = array();
        $opening_quantity_new = array();
        $to = $this->input->post('to');
      //  echo $to;
        $from = $this->input->post('from');
        //echo $from;
        $cat_id =  $this->input->post('category');
      //  echo $cat_id;

        // echo $to;
        // exit();

        if ($this->input->post('item') != 'all'){
            $item = $this->input->post('item');
            // print_r($item);
            // echo 'true';
        }
//         else{
//             // echo 'false';
//             $item_codes = $this->ItemSetupModel->getAllItems_by_catid($cat_id);
// //            echo '<pre>';
//           // echo $this->db->last_query();
//            print_r($item_codes);
// //            echo '</pre>';
//             foreach ($item_codes as $item_code) {
//                 $item = $item_code->code;
//             }
//            //echo $this->db->last_query();
//         }

        // echo '<pre>';
        // print_r($item);
        // exit();
       if(isset($item)){
           $data['item_code'] = $this->ItemSetupModel->Get_Items_By_Cdoe($item);

        // echo '<pre>';
        // print_r($data['item_code']);
        // exit();

        foreach ($data['item_code'] as $key => $datums) {
            foreach ($datums as $key_1 => $datum) {
                $item_setup[$key][$key_1] = $this->ItemSetupModel->Get_Item_Stock($datum->Id);
                // echo '<pre>';
                // echo $this->db->last_query();
                // exit();

                $item_return[$key][$key_1] = $this->ItemReturnModel->Get_DataFor_Item_Stock($datum->Id,$to);
// echo '<pre>';
//                 echo $this->db->last_query();
//                 exit();

                $item_issue[$key][$key_1] = $this->ItemIssueModel->Get_DataFor_Item_Stock($datum->Id,$to);

// echo '<pre>';
//                 echo $this->db->last_query();
//                 exit();

                $item_recieve[$key][$key_1] = $this->StockReciveModel->Get_DataFor_Item_Stock($datum->Id,$to);

                    // echo '<pre>';
                    // echo $this->db->last_query();
                    // exit();

                 $item_ledger[$key][$key_1] = $this->ItemIssueModel->Get_Data_For_ItemLadger($datum->Id,$to,$from);
                
                // echo '<pre>';
                // echo $this->db->last_query();
                // exit();
               
                if (isset($item_setup[$key][$key_1][0]->opening_quantity)){
                    $open = $item_setup[$key][$key_1][0]->opening_quantity;
                }else{
                    $open = 0;
                }

                if(isset($item_return[$key][$key_1][0]->Return_Quantity)){
                    $return = $item_return[$key][$key_1][0]->Return_Quantity;
                }else{
                    $return = 0;
                }

                
               

                if (isset($item_recieve[$key][$key_1]->Recieve_quantity)){
                    $recieve = $item_recieve[$key][$key_1]->Recieve_quantity;
                }else{
                    $recieve = 0;
                }

               
                if (isset($item_issue[$key][$key_1][0]->Issue_Quantity)){
                    $issue = $item_issue[$key][$key_1][0]->Issue_Quantity;
                }else{
                    $issue = 0;
                }

                $opening_quantity_new[$key][$key_1][0] = ($open + $return + $recieve) - $issue;
            }
           // echo  $opening_quantity_new[$key][$key_1][0];
           //  exit();
        }

        $data['to'] = $this->CalenderModel->getHijriDate($from);
        $data['from'] = $this->CalenderModel->getHijriDate($to);
        // echo '<pre>'.;
        // print_r($data);
        // exit();
        $data['Opening_Quantity_Cal'] = $opening_quantity_new;
        $data['item_setup'] = $item_setup ? $item_setup:'';
        $data['item_return'] = $item_return;
        $data['item_issue'] = $item_issue;
        $data['item_recieve'] = $item_recieve;
        $data['ItemLedger'] = $item_ledger;
       // echo "<pre>";
       // print_r($data);
       // exit();
        //print_r($data);
        $this->load->view('Store/reports/ItemStock/ItemStock ' ,$data);
       }
        else{
            $this->session->set_flashdata('message_name', 'ڈیٹا موجود نہیں ہے۔');
            redirect('Store/ItemStock');
            }
       }

    public function Get_Item_by_Category($id)
    {
		// echo $id;
		// exit();
        $item = $this->ItemSetupModel->Get_By_Category($id);
        echo json_encode($item);
    }
	

    public function Get_Item_by_Category_And_donation($category,$donation)
    {
        $item = $this->ItemSetupModel->Get_By_Category_donation($category,$donation);
        echo json_encode($item);
    }

    public function Get_Item_by_Donation($id)
    {
        $item = $this->ItemSetupModel->Get_By_Donation($id);
        echo json_encode($item);
    }
}