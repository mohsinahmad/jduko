<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 7/31/2017
 * Time: 4:55 PM
 */

class QurCowModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getMaxCode($day = '')
    {
        $this->db->select('IFNULL(MAX(Code),0) as Code');
        if ($day != '') {
            $this->db->where('Day', $day);
        }
        $this->db->where('Self_Cow', 0);
        return $this->db->get('qur_cow')->row();
    }

    public function getMaxCount()
    {
        $this->db->select_max('Count');
        return $this->db->get('qur_cow')->row();
    }

    public function getMaxSlipNo()
    {
        $this->db->select_max('Slip_Number');
        return $this->db->get('qur_slip')->row();
    }

    public function get_cow_number()
    {
        $Day = $_POST['day'];
        $qty = $_POST['qty'];
        if($qty != ''){
            if($qty != 7){
                $toFindQuantity = 7 - (int)$qty;
            }else{
                $toFindQuantity = 0;
            }
        }else{
            $toFindQuantity = 0;
        }
        $cowNumber = $this->getCowCount($toFindQuantity,$Day);
        if($cowNumber->num_rows() > 0){
            return $cowNumber->row();
        }else{
            $max_code = $this->getMaxCode($Day);
            $new_code = (int)$max_code->Code + 1;
            return $new_code;
        }
    }

    public function getCowCount($Qty,$Day)
    {
        $this->db->select();
        $this->db->where('Count <=', $Qty);
        isset($_POST['time'])?$this->db->where('Time', $_POST['time']):'';
        $this->db->where('Day', $Day);
        $this->db->where('Self_Cow', 0);
        $this->db->order_by('Code', 'asc');
        return $this->db->get('qur_cow');
    }

    public function getCowIdByNumber($cowNumber,$Day)
    {
        $this->db->select('id');
        $this->db->where('Code', $cowNumber);
        $this->db->where('Day', $Day);
        return $this->db->get('qur_cow')->row();
    }

    public function verifyCowNumber($cowNumber,$Day)
    {
        $this->db->select('*');
        $this->db->where('Code', $cowNumber);
        $this->db->where('Day', $Day);
        return $this->db->get('qur_cow');
    }

    public function getSelfCowIdByNumber($cowNumber,$Day)
    {
        $this->db->select('id');
        $this->db->where('Code', $cowNumber);
        $this->db->where('Day', $Day);
        return $this->db->get('qur_cow')->row();
    }

    public function check_cow_count()
    {
        $cowNumber = $_POST['code'];
        $Day = $_POST['day'];
        $this->db->select('Count,JDK_Count');
        $this->db->where('Code', $cowNumber);
        $this->db->where('Day', $Day);
        return $this->db->get('qur_cow')->result();
    }

    public function Get_Cows($from,$to,$day)
    {
        $result = array();
        $this->db->select('Id,Code');
        $this->db->where('Count != ', 0);
        $this->db->where('Day', $day);
        $this->db->where("Code BETWEEN '" . $from . "' AND '" . $to . "'");
        $cow = $this->db->get('qur_cow')->result();
        foreach ($cow as $key => $value) {
            $this->db->select('qur_slip.Paid,qur_cow.Code,qur_cow.Time,qur_hissa_dar.Name,qur_slip.Slip_Number,qur_cow.Day,qur_cow.Self_Cow');
            $this->db->from('qur_cow');
            $this->db->join('qur_hissa_dar', 'qur_cow.Id=qur_hissa_dar.Cow_id');
            $this->db->join('qur_slip', 'qur_hissa_dar.Slip_id=qur_slip.Id');
            $this->db->where('qur_hissa_dar.Cow_id ', $value->Id);
            $result[] =  $this->db->get()->result();
        }
        return $result;
    }

    public function Get_No_Cows()
    {
        $this->db->select('COUNT(qur_cow.Count) as Count');
        $this->db->from('qur_cow');
        $this->db->where('qur_cow.Count != ', 0);
        return $this->db->get()->result();
    }

    public function GetRemainingHissa()
    {
        $this->db->select('CODE,Count,Time,Day');
        $this->db->where("Count BETWEEN '" . 1 . "' AND '" . 6 . "'");
        return $this->db->get('qur_cow')->result();
    }
}