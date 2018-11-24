<?php
/**
 *
 */
class QurChrumAmountModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_Chrum_Amount($id='')
    {
        if ($id != ''){
            $this->db->where('id',$id);
        }
        return $this->db->get('qur_chrum_amount')->result();
    }

    public function Save_Chrum_amount()
    {
        if (isset($_POST['edit'])){
            $this->db->set('chrum_type',$_POST['chrum_type']);
            $this->db->set('latest_amount',$_POST['latest_amount']);
            $this->db->set('old_amount',$_POST['old_amount']);
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->where('id',$_POST['id']);
            $this->db->update('qur_chrum_amount');
        }else{
            foreach ($_POST['chrum_type'] as $key => $value) {
                $this->db->set('chrum_type',$_POST['chrum_type'][$key]);
                isset($_POST['latest_amount'][$key])?$this->db->set('latest_amount',$_POST['latest_amount'][$key]):$this->db->set('latest_amount',0);
                isset($_POST['old_amount'][$key])?$this->db->set('old_amount',$_POST['old_amount'][$key]):$this->db->set('old_amount',0);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('qur_chrum_amount');
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Data_Chrum_Quantity_Amount()
    {
        $chrum = array();
        $this->db->select('id');
        $result = $this->db->get('qur_chrum_amount')->result();

        foreach ($result as $key => $value) {
            $this->db->select('qur_chrum_amount.chrum_type,(qur_chrum_amount.latest_amount) as latest_amount,(qur_chrum_amount.old_amount) as old_amount,SUM(qur_chrum_quantity_detail.fresh_quantity) as fresh_quantity,SUM(qur_chrum_quantity_detail.old_quantity) as old_quantity,qur_chrum_quantity_detail.chrum_type as type');
            $this->db->from('qur_chrum_quantity_master');
            $this->db->join('qur_chrum_quantity_detail', 'qur_chrum_quantity_master.id=qur_chrum_quantity_detail.qur_chrum_amount_master_id');
            $this->db->join('qur_chrum_amount', 'qur_chrum_quantity_detail.chrum_type=qur_chrum_amount.id');
            $this->db->where('qur_chrum_quantity_detail.chrum_type', $value->id);
            $chrum[]= $this->db->get()->result();
        }
        return $chrum;
    }
}