<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 9/16/2017
 * Time: 4:08 PM
 */

class RewardList extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('QurConfigModel');
        $this->load->model('QurRewardListModel');
    }

    public function index()
    {
        $data['Locations'] = $this->QurConfigModel->Get_All('qur_location');
        $data['RewardList'] = $this->QurConfigModel->Get_All('qur_reward_list');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/RewardList/RewardList',$data);
        $this->load->view('Qurbani/footer');
    }

    public function SaveList()
    {
        $check = $this->QurRewardListModel->Save_List();
        if($check){
            $this->session->set_flashdata('success', 'شامل کردیا گیا۔۔!!');
            redirect('Qurbani/RewardList','refresh');
        }else{
            $this->session->set_flashdata('error', 'شامل نہیں کیا جاسکا، دوبارہ کوشش کیجیئے۔۔!!');
            redirect('Qurbani/RewardList','refresh');
        }
    }

    public function GetRewardList()
    {
        $data['Locations'] = $this->QurConfigModel->Get_All('qur_location');
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/RewardList/GetRewardList',$data);
        $this->load->view('Qurbani/footer');
    }
    public function ViewRewardList()
    {
        if (isset($_POST['location'])) {
            $location = $_POST['location'];
            $data['location'] = 123;
        }else{
            $location = array(41,43,44);
        }
        $data['RewardList'] = $this->QurRewardListModel->Get_Reward_List($location);
        $this->load->view('Qurbani/RewardList/ViewRewardList',$data);
    }
}