<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 8/1/2017
 * Time: 12:01 PM
 */

class QurConfigModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Get_All($table,$id='',$config='')
    {
        if ($id != ''){
            $this->db->where('Id',$id);
        }
        if ($config != ''){
            $this->db->select('*,qur_location.Id as Loc_id');
            $this->db->join('qur_location','qur_hissa.Loc = qur_location.Id');
        }
        return $this->db->get($table)->result();
    }

    public function Get_All_config($table,$id='')
    {
        return $this->db->get($table)->result();
    }

    public function Save_Time()
    {
        if (isset($_POST['edit'])){
            $this->db->query('TRUNCATE qur_time');
        }
        foreach ($_POST['starttime'] as $key => $item) {
            $ID = $key + 1;
            $this->db->set('Id',$ID);
            $this->db->set('Start_Time',$_POST['starttime'][$key]);
            $this->db->set('End_Time',$_POST['endtime'][$key]);
            $this->db->set('Per_Hour_Quantity',$_POST['quantity'][$key]);
            $this->db->set('Per_Day_Quantity',$_POST['total_quantity'][$key]);
            $this->db->set('Time_Difference',$_POST['Time_difference'][$key]);
            if (isset($_POST['edit'])){
                $this->db->set('CreatedBy',$_POST['CreatedBy'][$key]);
                $this->db->set('CreatedOn',$_POST['CreatedOn'][$key]);
                $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            }else{
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            }
            $this->db->Insert('qur_time');
        }
        if($this->db->affected_rows() > 0){
            if (isset($_POST['edit'])){
                return true;
            }else{
                foreach ($_POST['total_quantity'] as $day => $item) {
                    $start_time = $_POST['starttime'][$day];
                    $to_time = strtotime($start_time);
                    $to_time_new = $to_time;
                    $b = 0;

                    for ($a = 0 ; $a < $item ; $a++){
                        if ($b%$_POST['quantity'][$day] == 0 && $a != 0){
                            $to_time_new = $to_time + 3600;  // 1 hour 60*60
                            $to_time12 = date("H:i",$to_time_new);
                            $to_time = strtotime($to_time12);
                            $b = 0;
                        }$b++;
                        $cow[$day][$a+1] = array(
                            'Code' => $a+1,
                            'Time' => date("H:i",$to_time_new),
                            'Day' => $day+1
                        );
                        $this->db->insert('qur_cow',$cow[$day][$a+1]);
                    }
                }

                if($this->db->affected_rows() > 0){
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
    }

    public function Save_Amount()
    {
        if (isset($_POST['edit'])){
            $this->db->where('1=1');
            $this->db->delete('qur_hissa');
        }
        $this->db->set('Amount',$_POST['Amount']);
        $this->db->set('Loc',$_POST['location']);
        $this->db->set('Independent_Expance',$_POST['Independent_Expance']);
        $this->db->set('Self_Cow_No',$_POST['Self_Cow_No']);
        $this->db->set('Common_Package',$_POST['Common_Package']);
        if (isset($_POST['Common_Package_Type'])) {
            $this->db->set('Common_Package_Type',$_POST['Common_Package_Type']);
            $this->db->set('Common_Package_Amount',$_POST['Common_Package_Amount']);
        }
        if (isset($_POST['edit'])){
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
        }else{
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        }
        $this->db->Insert('qur_hissa');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}