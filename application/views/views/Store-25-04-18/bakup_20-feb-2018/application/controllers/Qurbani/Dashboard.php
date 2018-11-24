<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 7/31/2017
 * Time: 11:58 AM
 */

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
//        $this->db->query("ALTER TABLE `qur_reward_list` MODIFY `Id` int(60) NOT NULL AUTO_INCREMENT;");
        $this->load->view('Qurbani/header');
        $this->load->view('Qurbani/index');
        $this->load->view('Qurbani/footer');
    }
}