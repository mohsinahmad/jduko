<?php

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!($this->session->userdata('in_use'))){
            redirect('login','refresh');
        }
    }

    public function login(){
        $this->load->view('login');
    }

    public function menu()
    {
        $this->load->view('MainMenu');
    }

    public function index()
    {
        $this->load->view('Store/header');
        $this->load->view('Store/index');
        $this->load->view('Store/footer');

    }

    public function setYear($year)
    {
        $session = array('current_year' => $year);
        $this->session->set_userdata($session);
        echo true;
    }
}