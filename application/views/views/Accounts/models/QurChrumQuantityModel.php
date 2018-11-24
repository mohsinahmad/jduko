<?php
/**
 *
 */
class QurChrumQuantityModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('QurHulqyModel');
    }

    public function Get_Chrum_Quantity()
    {
        $this->db->select(' *,qur_chrum_quantity_master.id as M_ID');
        $this->db->from('qur_chrum_quantity_master');
        $this->db->join('qur_chrum_quantity_detail', 'qur_chrum_quantity_master.id = qur_chrum_quantity_detail.qur_chrum_amount_master_id');
        $this->db->join('qur_hulqy', 'qur_chrum_quantity_master.qur_hulqy_id=qur_hulqy.id');
        $this->db->group_by('qur_chrum_quantity_master.receipt_no');
        return $this->db->get()->result();
    }

    public function Save_Quantity()
    {
        if (isset($_POST['edit'])){
            $this->db->set('qur_hulqy_id',$_POST['qur_hulqy_id']);
            $this->db->set('receipt_no',$_POST['receipt_no']);
            $this->db->set('dateG',$_POST['dateG']);
            $this->db->set('dateH',$_POST['dateH']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->where('id',$_POST['edit']);
            $this->db->update('qur_chrum_quantity_master');
            foreach ($_POST['chrum_type'] as $key => $value) {
                $this->db->set('fresh_quantity',$_POST['fresh_quantity'][$key]);
                $this->db->set('old_quantity',$_POST['old_quantity'][$key]);
                $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
                $this->db->where('chrum_type',$value);
                $this->db->where('qur_chrum_amount_master_id',$_POST['edit']);
                $this->db->update('qur_chrum_quantity_detail');
            }
        }else{
            $this->db->select_max('receipt_no');
            $max = $this->db->get('qur_chrum_quantity_master')->result();
            if ($max[0]->receipt_no != ''){
                $max_num = $max[0]->receipt_no;
                $max_num++;
                $new_number = str_pad($max_num,4,0,STR_PAD_LEFT);
                $new_number;
            }else{
                $new_number = '0001';
            }
            $this->db->set('qur_hulqy_id',$this->input->post('qur_hulqy_id'));
            $this->db->set('receipt_no',$new_number);
            $this->db->set('dateG',$this->input->post('dateG'));
            $this->db->set('dateH',$this->input->post('dateH'));
            $this->db->set('Receive_Day',$this->input->post('Receive_Day'));
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('qur_chrum_quantity_master');
            $chrum_master_id = $this->db->insert_id();
            foreach ($_POST['chrum_type'] as $key => $value) {
                $this->db->set('qur_chrum_amount_master_id',$chrum_master_id);
                $this->db->set('chrum_type',$_POST['chrum_type'][$key]);
                $this->db->set('fresh_quantity',$_POST['fresh_quantity'][$key]);
                $this->db->set('old_quantity',$_POST['old_quantity'][$key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('qur_chrum_quantity_detail');
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_data_For_Chrum_Slip($id)
    {
        $this->db->select('*,qur_chrum_quantity_master.id as Master_Id ');
        $this->db->from('qur_chrum_quantity_master');
        $this->db->join('qur_chrum_quantity_detail', 'qur_chrum_quantity_master.id=qur_chrum_quantity_detail.qur_chrum_amount_master_id');
        $this->db->join('qur_hulqy', 'qur_chrum_quantity_master.qur_hulqy_id=qur_hulqy.id');
        $this->db->join('qur_chrum_amount', 'qur_chrum_quantity_detail.chrum_type=qur_chrum_amount.id');
        $this->db->where('qur_chrum_quantity_master.id', $id);
        return $this->db->get()->result();
    }

    public function Comparitive_Report_Data($halqa)
    {
        $this->db->select("qur_hulqy.hulqa_name, qur_hulqy.supervisor_name, qur_chrum_amount.chrum_type, SUM(qur_chrum_quantity_detail.fresh_quantity) as fresh_quantity, SUM(qur_chrum_quantity_detail.old_quantity) as old_quantity,  ((SUM(qur_chrum_quantity_detail.fresh_quantity)*latest_amount)+(SUM(qur_chrum_quantity_detail.old_quantity)*old_amount)) as amount");
        $this->db->join('qur_chrum_quantity_detail','qur_chrum_quantity_master.id = qur_chrum_quantity_detail.qur_chrum_amount_master_id');
        $this->db->join('qur_chrum_amount','qur_chrum_quantity_detail.chrum_type = qur_chrum_amount.id');
        $this->db->join('qur_hulqy','qur_chrum_quantity_master.qur_hulqy_id = qur_hulqy.id','LEFT');
        $this->db->WHERE('qur_hulqy_id',$halqa);
        $this->db->group_by('qur_chrum_amount.id');
        return $this->db->get('qur_chrum_quantity_master')->result();
    }

    public function GetAreaDetailReport($receipt_no)
    {
        $this->db->select('qur_hulqy.hulqa_name,qur_hulqy.supervisor_name,qur_chrum_quantity_master.Receive_Day,qur_chrum_quantity_master.receipt_no,qur_chrum_quantity_detail.chrum_type,qur_chrum_quantity_detail.fresh_quantity,qur_chrum_quantity_detail.old_quantity');
        $this->db->from('qur_chrum_quantity_master');
        $this->db->join('qur_chrum_quantity_detail ', 'qur_chrum_quantity_master.id=qur_chrum_quantity_detail.qur_chrum_amount_master_id');
        $this->db->join('qur_hulqy', 'qur_chrum_quantity_master.qur_hulqy_id=qur_hulqy.id');
        $this->db->where('qur_chrum_quantity_master.receipt_no',$receipt_no);
        //$this->db->where('qur_chrum_quantity_master.Receive_Day',$value);
        //$this->db->order_by('qur_chrum_quantity_master.receipt_no','ASC');
        $this->db->order_by('qur_chrum_quantity_detail.chrum_type', 'ASC');
        return $this->db->get()->result();
    }

    public function GetReciptNo($id,$day)
    {
        $receipt_no = array();
        foreach ($day as $H_key => $value) {
            $this->db->select('qur_chrum_quantity_master.receipt_no,qur_chrum_quantity_master.Receive_Day');
            $this->db->from('qur_chrum_quantity_master');
            $this->db->join('qur_chrum_quantity_detail ', 'qur_chrum_quantity_master.id=qur_chrum_quantity_detail.qur_chrum_amount_master_id');
            $this->db->join('qur_hulqy', 'qur_chrum_quantity_master.qur_hulqy_id=qur_hulqy.id');
            $this->db->where('qur_chrum_quantity_master.qur_hulqy_id',$id);
            $this->db->where('qur_chrum_quantity_master.Receive_Day',$value);
            $this->db->order_by('qur_chrum_quantity_master.receipt_no','ASC');
            $this->db->order_by('qur_chrum_quantity_detail.chrum_type', 'ASC');
            $this->db->group_by('qur_chrum_quantity_master.receipt_no');
            $receipt_no[] = $this->db->get()->result();
        }
        return $receipt_no;
    }
}