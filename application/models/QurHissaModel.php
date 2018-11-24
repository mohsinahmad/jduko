<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 7/31/2017
 * Time: 4:19 PM
 */

class QurHissaModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_All()
    {
        return $this->db->get('qur_hissa')->result();
    }

    public function Get_Report_hissa()
    {
        $arr  = array(1,2,3);
        foreach ($arr as $key => $value) {
            $this->db->select('SUM(qur_slip.Quantity) as Quantity, count(qur_cow.Code) as Cow , max(qur_cow.Code) as lastCow');
            $this->db->from('qur_slip');
            $this->db->join('qur_hissa_dar', 'qur_slip.Id= qur_hissa_dar.Slip_id');
            $this->db->join('qur_cow', 'qur_hissa_dar.Cow_id=qur_cow.Id');
            $this->db->where('qur_slip.Collection_Day', $value);
            $this->db->where('qur_cow.Self_Cow', 0);
            $res[] = $this->db->get()->result();
        }
        return $res;
    }
    public function Get_Report_self_Cow()
    {
        $arr  = array(1,2,3);
        foreach ($arr as $key => $value) {
            $this->db->select('SUM(qur_slip.Quantity) as Quantity, count(qur_cow.Code) as Cow , max(qur_cow.Code) as lastCow');
            $this->db->from('qur_slip');
            $this->db->join('qur_hissa_dar', 'qur_slip.Id= qur_hissa_dar.Slip_id');
            $this->db->join('qur_cow', 'qur_hissa_dar.Cow_id=qur_cow.Id');
            $this->db->where('qur_slip.Collection_Day', $value);
            $this->db->where('qur_cow.Self_Cow', 1);
            $res[] = $this->db->get()->result();
        }
        return $res;
    }
    public function GetAmount()
    {
        $this->db->select('Amount,Independent_Expance');
        $this->db->from('qur_hissa');
        return $this->db->get()->result();
    }

    public function get_self_cow_number()
    {
        $day = $_POST['day'];
        $this->db->select('Code');
        $this->db->from('qur_cow');
        $this->db->where('Self_Cow', 1);
        $this->db->where('Day', $day);
        return $this->db->get();
    }

    public function get_self_cow_number_serial()
    {
        $this->db->select('Self_Cow_No');
        $this->db->from('qur_hissa');
        return $this->db->get()->row();
    }
}