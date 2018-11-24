<?php

class Dashboard extends MY_Controller
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
        if(isset($_SESSION['user'])) {
            $this->load->view('Store/header');
            $this->load->view('Store/index');
            $this->load->view('Store/footer');
        }
        else{
            echo $_SESSION['UserName'];
           // $this->load->view('login');
        }
    }

    public function setYear($year)
    {
        $session = array('current_year' => $year);
        $this->session->set_userdata($session);
        echo true;
    }
}