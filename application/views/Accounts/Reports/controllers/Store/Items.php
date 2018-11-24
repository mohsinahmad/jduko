<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Items extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ItemCategoryModel');
        $this->load->model('DonationTypeModel');
        $this->load->model('ItemSetupModel');
        $this->load->model('DemandFormModel');
    }
    public function ItemSetup()
    {
        if(!$_SESSION['type']){
            $_SESSION['type'] = $this->uri->segment(4);
        }
        $data['donations'] = $this->DonationTypeModel->donation_type();
        $data['items'] = $this->ItemSetupModel->getAllItems();
//        echo $this->db->last_query();
        $this->load->view('Store/header');
        $this->load->view('Store/item_setup/itemsetup',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }
    public function add_unit(){
        $name = $this->input->post('p_name');
        $response =  $this->ItemSetupModel->add_unit($name);
        if($response){
            echo 'added';
        }else{
            echo 'error';
        }
    }

    public function SaveItem()
    {
//        echo '<pre>';
//        print_r($_POST);
        if($this->check_duplicate_item($_POST['items'],$_POST['DonationType'][0])){
            $this->session->set_flashdata('error',"اس آئٹم کی ابتداء منظور شدہ مد میں ہو چکی ہے");
            redirect('Store/items/ItemSetup','refresh');
        }
        else{
            $check = $this->ItemSetupModel->Save_Item();
            if($check){
                $this->session->set_flashdata('success',"آئٹم کامیابی سے شامل ہوگیا ہے");
                redirect('Store/items/ItemSetup','refresh');
            }else{
                $this->session->set_flashdata('error',"آئٹم شامل نہی ہوا ہے");
                redirect('Store/items/ItemSetup','refresh');
            }
        }
    }


    public function check_duplicate_item($item_id,$donation_id){
        $this->db->where('item_setup_id',$item_id);
        $this->db->where('donation_type',$donation_id);
        $result =  $this->db->get('item_setup_details');
        if($result->num_rows() > 0){
            return true;
        }
        else{
            return false;
        }
    }


    public function DeleteItem($id)
    {

        $check = $this->ItemSetupModel->delete_Item($id);
        if($check){
            echo 'succes';
        }
        else{
            echo 'error';
        }
    }




    public function UpdateItems()
    {
        $check = $this->ItemSetupModel->updateItems();
        if($check){
            echo 'succes';
        }
        else{
            echo 'error';
        }
    }



    public function ItemById($id)
    {
//        $type = $_SESSION['type'];
        $data['itemsToEdit'] = $this->ItemSetupModel->Item_By_Id($id);
//        echo $this->db->last_query();
//                echo '<pre>';
//                print_r($data['itemsToEdit']);
//                exit();
////        $data['itemsToEdit'][0]->category_Id;
//        $data['unit'] = $this->ItemSetupModel->getunit();
////                echo '<pre>';
////                print_r($data['unit']);
////                exit();
//        $data['categories'] = $this->ItemCategoryModel->getParCategoriesByTYpeedit($data['itemsToEdit'][0]->item_category);
//        $data['allcategories'] = $this->ItemCategoryModel->getParCategoriesByTYpe($type);
//        $data['donations'] = $this->DonationTypeModel->donation_type();
//        $data['items'] = $this->ItemSetupModel->get_items();
//               echo '<pre>';
//               print_r($data['items']);
//               exit();

        $this->load->view('Store/header');
        $this->load->view('Store/item_setup/itemsetup',$data);
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
//        echo $this->db->last_query();
        echo json_encode($data);
    }


    public function get_all_items(){
        $data = $this->ItemSetupModel->get_items();
        echo json_encode($data);
    }
    public function add_item(){
        $name = $this->input->post('item-name');
        $catid = $this->input->post('item-parent');
        $unit = $this->input->post('unit');
        $this->form_validation->set_rules('item-name','item-name','trim|required|is_unique[item_setup.name]');
        $this->form_validation->set_rules('item-parent','item-parent','trim|required');
        $this->form_validation->set_rules('unit','unit','trim|required');
        if($this->form_validation->run() == true){
            $check = $this->ItemSetupModel->add_item($name,$catid,$unit);
            if($check){
                $this->session->set_flashdata('success', "آئٹم کامیابی سے شامل ہوگیا ہے");
                redirect('Store/Category/index/' . $_SESSION['type'], 'refresh');
            }
            else{
                echo 'not save';
            }
        }
        else{
            echo 'duplicate';
        }
    }
    public  function  get_items_details($id){
        $unit = $_POST['unit'];
        $donation = $_POST['donation'];
        $data = $this->ItemSetupModel->get_items_details($id,$unit,$donation);
//         echo $this->db->last_query();
        foreach ($data as $key => $val){
            echo '<tr item-id="'.$val->a_id.'">
            <td>'.$val->item_name.'</td>
            <td>'.$val->unit_of_measure_name.'</td>
            <td>'.$val->Donation_Type.'</td>
            <td>'.$val->opening_quantity.'</td>
            <td>'.$val->current_quantity.'</td>
            <td> <button type="button" class="btn btn-danger delete-item" data-id='.$val->a_id.' style="font-size: 10px; ">حذف کریں
            </button>
            <button type="button" class="btn btn-success edit-item" data-toggle="modal" data-target="#gridSystemModal" data-id='.$val->a_id.' style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
            </button>
            </td>
            </tr>';
     }
}

public function get_edit_items($id){
   $data = $this->ItemSetupModel->get_edit_items($id);
//    echo $this->db->last_query();
   echo json_encode($data);
}



    public function get_code_items(){
        $id = $this->input->post('id');
        $check = $this->ItemSetupModel->get_item_code($id);
        echo json_encode($check);
    }
    public  function get_parent_category(){
       $data = $this->ItemCategoryModel->get_parent_category();
       echo json_encode($data);
    }
    public  function get_sub_category($parent_id){
        $data = $this->ItemCategoryModel->get_sub_category($parent_id);
        echo json_encode($data);
    }
    public  function get_items($cat_id){
        $data = $this->ItemCategoryModel->get_items($cat_id);
//        echo $this->db->last_query();
        echo json_encode($data);
    }
    public function get_donation(){
        $data = $this->DonationTypeModel->get_donation();
        echo json_encode($data);
    }
    public function get_unit(){
    $data['unit'] = $this->ItemSetupModel->getunit();
//    echo $this->db->last_query();
    echo json_encode($data['unit']);
}

}
