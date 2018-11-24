<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {

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
        $data['categories'] = $this->ItemCategoryModel->Get_Sub_Category();
        $data['donations'] = $this->DonationTypeModel->donation_type();
        $data['items'] = $this->ItemSetupModel->getAllItems();
//        echo "<pre>";
//        print_r($data);
//        exit();
        $this->load->view('Store/header');
        $this->load->view('Store/item_setup/itemsetup',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }

    public function SaveItem()
    {
        $check = $this->ItemSetupModel->Save_Item();
        if($check){
            $this->session->set_flashdata('success',"آئٹم کامیابی سے شامل ہوگیا ہے");
            redirect('Store/items/ItemSetup','refresh');
        }else{
            $this->session->set_flashdata('error',"آئٹم شامل نہی ہوا ہے");
            redirect('Store/items/ItemSetup','refresh');
        }
    }

    public function DeleteItem($id)
    {
        $check = $this->ItemSetupModel->delete_Item($id);
        if($check === true){
            $response= array('success' => "ok");
        }elseif ($check == 12){
            $response = array('userecord' => "ok");
        }else{
            $response=array('error' =>"ok");
        }
        echo json_encode($response);
    }

    public function ItemById($id)
    {
        $data['itemsToEdit'] = $this->ItemSetupModel->Item_By_Id($id);
        $data['categories'] = $this->ItemCategoryModel->getParCategories();
        $data['donations'] = $this->DonationTypeModel->donation_type();
        $data['items'] = $this->ItemSetupModel->getAllItems();
        $this->load->view('Store/header');
        $this->load->view('Store/item_setup/itemsetup_edit',$data);
        $this->load->view('Store/footer');
        $this->load->view('Store/js/itemJs');
    }

    public function UpdateItems()
    {
        $check = $this->ItemSetupModel->updateItems();
        if($check){
            $this->session->set_flashdata('success',"آئٹم کامیابی سے تدوین ہوگیا ہے");
            redirect('Store/items/ItemSetup','refresh');
        }else{
            $this->session->set_flashdata('error',"آئٹم تدوین نہی ہوئ ہے");
            redirect('Store/items/ItemSetup','refresh');
        }
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

}
