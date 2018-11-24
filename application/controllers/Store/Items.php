<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Items extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemCategoryModel');
        $this->load->model('DonationTypeModel');
        $this->load->model('ItemSetupModel');
        $this->load->model('DemandFormModel');
//        $this->load->library('tcpdf');
    }
    public function ItemSetup()
    {
        // echo '<pre>';
        // print_r($_SESSION);
        // exit();
        $data['donations'] = $this->DonationTypeModel->donation_type();
        $data['items'] = $this->ItemSetupModel->getAllItems();
        $this->load->view('Store/header');
        $this->load->view('Store/item_setup/itemsetup', $data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }
    public  function  update_data(){
        $this->db->select('*');
        $this->db->order_by('id');
        $data = $this->db->get('item_setup_details')->result();
        foreach ($data as $key => $val){
            $this->db->set('Item_quantity',$val->opening_quantity);
            $this->db->set('remain_quantity',$val->current_quantity);
            $this->db->set('item_setup_detail_id',$val->id);
            $this->db->where('donation_type',$val->donation_type);
            $this->db->where('item_id',$val->item_setup_id);
            $this->db->update('item_stockrecieve_slip_details');
        }
    }
    public function add_unit()
    {
        $this->form_validation->set_rules('unit_name', 'unit_name', 'trim|required|is_unique[unit_of_measure.name]');
        if ($this->form_validation->run() == true) {
            $name = $this->input->post('unit_name');
            $response = $this->ItemSetupModel->add_unit($name);
            if ($response) {
                $this->session->set_flashdata('success', "پیمائش کامیابی سے شامل ہوگئی");
                redirect('Store/unit_of_measure', 'refresh');
            }
        } else {
            $this->session->set_flashdata('error', "پیمائش پہلے سے موجود ہے");
            redirect('Store/unit_of_measure', 'refresh');
        }
    }
    public function SaveItem()
    {
        $this->check_duplicate_item($_POST['items'], $_POST['DonationType'][0]);
        
        if ($this->check_duplicate_item($_POST['items'], $_POST['DonationType'][0]) == 'updated') {
            $this->session->set_flashdata('error', "اس آئٹم کی ابتداء دی ہوئی مد میں ہو چکی ہے");
            redirect('Store/items/ItemSetup', 'refresh');
        } else if ($this->check_duplicate_item($_POST['items'], $_POST['DonationType'][0])) {
            $this->session->set_flashdata('success', "آئٹم کامیابی سے شامل ہوگیا ہے");
            redirect('Store/items/ItemSetup', 'refresh');
        } else {
            $check = $this->ItemSetupModel->Save_Item();
            if ($check) {
                $this->session->set_flashdata('success', "آئٹم کامیابی سے شامل ہوگیا ہے");
                redirect('Store/items/ItemSetup', 'refresh');
            } else {
                $this->session->set_flashdata('error', "آئٹم شامل نہی ہوا ہے");
                redirect('Store/items/ItemSetup', 'refresh');
            }
        }
    }

    public function check_duplicate_item($item_id, $donation_id)
    {
        $this->db->where('item_setup_id', $item_id);
        $this->db->where('donation_type', $donation_id);
        $result = $this->db->get('item_setup_details');
        if ($result->num_rows() > 0) {
            $data = $result->result();
            if ($data[0]->is_delete == '1') {
                $this->db->set('opening_quantity', $_POST['OpeningQuanity'][0]);
                $this->db->set('current_quantity', $_POST['CurrentQuanity'][0]);
                $this->db->set('is_delete', '0');
                $this->db->where('id', $data[0]->id);
                $this->db->update('item_setup_details');
                if ($this->db->affected_rows() > 0) {
                    return 'updated';
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function DeleteItem($id)
    {
        //exit();
        $check = $this->ItemSetupModel->delete_Item($id);
        if ($check) {
            echo 'succes';
        } else {
            echo 'error';
        }
    }

    public function UpdateItems()
    {
        $check = $this->ItemSetupModel->updateItems();
        if ($check) {
            echo 'succes';
        } else {
            echo 'error';
        }
    }

    public function ItemById($id)
    {
        $data['itemsToEdit'] = $this->ItemSetupModel->Item_By_Id($id);
        $this->load->view('Store/header');
        $this->load->view('Store/item_setup/itemsetup', $data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }

    public function getDonationViseItems($d_id)
    {
        $data = $this->ItemSetupModel->get_donation_vise_items($d_id);
        echo json_encode($data);
    }

    public function GetCategoryViseItem($category)
    {
        $data = $this->ItemSetupModel->get_category_vise_items($category);
        echo json_encode($data);
    }

    public function get_all_items()
    {
        $data = $this->ItemSetupModel->get_items();
        echo json_encode($data);
    }

    public function add_item()
    {
        $name = $this->input->post('item-name');
        $catid = $this->input->post('item-parent');
        $unit = $this->input->post('unit');
        $this->form_validation->set_rules('item-name', 'item-name', 'trim|required|is_unique[item_setup.name]');
        $this->form_validation->set_rules('item-parent', 'item-parent', 'trim|required');
        $this->form_validation->set_rules('unit', 'unit', 'trim|required');
        if ($this->form_validation->run() == true) {
            $check = $this->ItemSetupModel->add_item($name, $catid, $unit);
            if ($check) {
                $this->session->set_flashdata('success', "آئٹم کامیابی سے شامل ہوگیا ہے");
                redirect('Store/Category', 'refresh');
            } else {
                echo 'not save';
            }
        } else {
            echo 'duplicate';
        }
    }

    public function get_items_details($id)
    {
        $unit = $_POST['unit'];
        $donation = $_POST['donation'];
        $data = $this->ItemSetupModel->get_items_details($id, $unit, $donation);
//         echo $this->db->last_query();
        foreach ($data as $key => $val) {
            echo '<tr item-id="' . $val->a_id . '">
            <td>' . $val->item_name . '</td>
            <td>' . $val->unit_of_measure_name . '</td>
            <td>' . $val->Donation_Type . '</td>
            <td>' . $val->opening_quantity . '</td>
            <td>' . $val->current_quantity . '</td>
            <td> <button type="button" class="btn btn-danger delete-item" data-id=' . $val->a_id . ' style="font-size: 10px; ">حذف کریں
            </button>
            <button type="button" class="btn btn-success edit-item" data-toggle="modal" data-target="#gridSystemModal" data-id=' . $val->a_id . ' style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
            </button>
            </td>
            </tr>';
        }
    }


    public function get_edit_items($id)
    {
        $data = $this->ItemSetupModel->get_edit_items($id);
//    echo $this->db->last_query();
        echo json_encode($data);
    }

    public function get_code_items()
    {
        $id = $this->input->post('id');
        $check = $this->ItemSetupModel->get_item_code($id);
        echo json_encode($check);
    }

    public function get_parent_category()
    {
        $data = $this->ItemCategoryModel->get_parent_category();
        echo json_encode($data);
    }

    public function get_sub_category($parent_id)
    {
        $data = $this->ItemCategoryModel->get_sub_category($parent_id);
        echo json_encode($data);
    }

    public function get_items($cat_id = '')
    {
        $data = $this->ItemCategoryModel->get_items($cat_id);
        // echo $this->db->last_query();
       // exit();
        echo json_encode($data);
    }

    public function get_donation()
    {
        $data = $this->DonationTypeModel->get_donation();
        echo json_encode($data);
    }
    public function get_unit()
    {
        $data['unit'] = $this->ItemSetupModel->getunit();
        // echo $this->db->last_query();
        echo json_encode($data['unit']);
    }
    public function generate_report($id)
    {
        $id = $id;
        $result = $this->ItemSetupModel->get_report_data($id);
        $data = '<table cellspacing="0" cellpadding="1" border="1">';
        $data .= '
        <thead>
        <tr>
        <th>Item Code</th>
        <th>Category</th>
        <th>Sub Category</th>
        <th>Item Name</th>
        <th>Opening Quanity</th>
        <th>Current Quanity</th>
        <th>Unit of Measure</th>
        <th>Donation Type</th>
        </tr>
        </thead>
        <tbody>';
        foreach ($result as $key => $value) {
            $firstvalue = current($result);
            if($firstvalue == $value->category_name){
                echo 'same';
            }
            $data .= '<tr>'.'
                                    <td>'.$value->Item_Code.'</td>'.
                '<td>'.$value->category_name.'</td>'.
                '<td>'.$value->sub_category.'</td>'.
                '<td>'.$value->item_name.'</td>'.
                '<td>'.$value->opening_quantity.'</td>'.
                '<td>'.$value->current_quantity.'</td>'.
                '<td>'.$value->unit.'</td>'.
                '<td>'.$value->donation_name.'</td>'.
                '</tr>';
        }
        $data .= '</tbody>
                   </table>';
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 018');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 018', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language dependent data:
$lg = Array();
$lg['a_meta_charset'] = 'UTF-8';
$lg['a_meta_dir'] = 'rtl';
$lg['a_meta_language'] = 'fa';
$lg['w_page'] = 'page';
// set some language-dependent strings (optional)
$pdf->setLanguageArray($lg);
$pdf->AddPage();
// Restore RTL direction
$pdf->setRTL(true);
// print newline
$pdf->Ln();
$pdf->SetFont('freeserif', '', 10);
// Arabic and English content
$pdf->WriteHTML($data, true, 0, true, 0);
$pdf->Output('example_018.pdf', 'I');

 }





// public function generate_report($id)
//     {
//         $id = $id;
//         $result = $this->ItemSetupModel->get_report_data($id);
//         $data = '<table cellspacing="0" cellpadding="1" border="1">';
//         $data .= '
//         <thead>
//         <tr>
//         <th>Item Code</th>
//         <th>Category</th>
//         <th>Sub Category</th>
//         <th>Item Name</th>
//         <th>Opening Quanity</th>
//         <th>Current Quanity</th>
//         <th>Unit of Measure</th>
//         <th>Donation Type</th>
//         </tr>
//         </thead>
//         <tbody>';
//         foreach ($result as $key => $value) {
//             $firstvalue = current($result);
//             if($firstvalue == $value->category_name){
//                 echo 'same';
//             }
//             $data .= '<tr>'.'
//                                     <td>'.$value->Item_Code.'</td>'.
//                 '<td>'.$value->category_name.'</td>'.
//                 '<td>'.$value->sub_category.'</td>'.
//                 '<td>'.$value->item_name.'</td>'.
//                 '<td>'.$value->opening_quantity.'</td>'.
//                 '<td>'.$value->current_quantity.'</td>'.
//                 '<td>'.$value->unit.'</td>'.
//                 '<td>'.$value->donation_name.'</td>'.
//                 '</tr>';
//         }
//         $data .= '</tbody>
//                    </table>';
//         $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// // set document information
// $pdf->SetCreator(PDF_CREATOR);
// $pdf->SetAuthor('Nicola Asuni');
// $pdf->SetTitle('TCPDF Example 018');
// $pdf->SetSubject('TCPDF Tutorial');
// $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// // set default header data
// $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 018', PDF_HEADER_STRING);

// // set header and footer fonts
// $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
// $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// // set default monospaced font
// $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// // set margins
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
// $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
// $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
// // set auto page breaks
// $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
// // set image scale factor
// $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// // set some language dependent data:
// $lg = Array();
// $lg['a_meta_charset'] = 'UTF-8';
// $lg['a_meta_dir'] = 'rtl';
// $lg['a_meta_language'] = 'fa';
// $lg['w_page'] = 'page';
// // set some language-dependent strings (optional)
// $pdf->setLanguageArray($lg);
// $pdf->AddPage();
// // Restore RTL direction
// $pdf->setRTL(true);
// // print newline
// $pdf->Ln();
// $pdf->SetFont('freeserif', '', 10);
// // Arabic and English content
// $pdf->WriteHTML($data, true, 0, true, 0);
// $pdf->Output('example_018.pdf', 'I');

// }



    public function get_category_by_type($val){
        $this->db->where('category_type',$val);
        $data = $this->db->get('item_categories')->result();
        echo json_encode($data);
    }
    public function  get_sub_cate($id){
        $this->db->where('parent_id',$id);
        $data = $this->db->get('item_sub_categories')->result();
        echo json_encode($data);
    }
}
