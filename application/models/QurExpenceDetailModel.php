<?php
/**
 *
 */
class QurExpenceDetailModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Get_Expence_Detail($id='')
    {
        ($id=='')?$this->db->select('*,qur_expence_master.Id as MasterId,SUM(qur_expence_detail.Amount) as SUM_Amount'):$this->db->select('qur_expence_master.Id as MasterId, qur_expence_detail.Id as detail_Id, qur_expence_type.id as Type_Id, qur_expence_master.Receiver_Name, qur_expence_master.Voucher_Number, qur_expence_master.DateG, qur_expence_master.DateH, qur_expence_master.Description as Master_Desc,  qur_expence_detail.Amount, qur_expence_detail.Description as detail_desc, qur_expence_type.type');
        $this->db->join('qur_expence_detail','qur_expence_master.Id=qur_expence_detail.Master_Id');
        $this->db->join('qur_expence_type','qur_expence_detail.Expence_Type_Id = qur_expence_type.id');
        ($id!='')?$this->db->where('qur_expence_master.Id',$id):$this->db->group_by('qur_expence_master.Id');
        return $this->db->get('qur_expence_master')->result();
    }

    public function Get_All_Expence_Details($loc='',$reward='')
    {
        ($loc != '')?$this->db->select('SUM(Amount) as Amount'):'';
        $this->db->join('qur_expence_type','qur_expence_detail.Expence_Type_Id = qur_expence_type.id');
        ($reward == '')?$this->db->where('qur_expence_type.IsReward',0):$this->db->where('qur_expence_type.IsReward',1);
        ($loc != '')?$this->db->where('qur_expence_detail.Loc_Id',$loc):'';
        $this->db->order_by('qur_expence_type.IsReward','ASC');
        return $this->db->get('qur_expence_detail')->result();
    }

    public function Get_Expence_Voucehrs($voucher_number)
    {
        $this->db->join('qur_expence_detail','qur_expence_master.Id=qur_expence_detail.Master_Id');
        $this->db->join('qur_expence_type','qur_expence_detail.Expence_Type_Id = qur_expence_type.id');
        $this->db->where('qur_expence_master.Voucher_Number',$voucher_number);
        return $this->db->get('qur_expence_master')->result();
    }

    public function Get_Expence_Voucher($id)
    {
        $this->db->select('SUM(Amount) as Amount');
        $this->db->where('Is_Advance',1);
        $this->db->where('Halqy_Id',$id);
        return $this->db->get('qur_expence_voucher')->result();
    }

    public function Get_VoucherNumber()
    {
        $this->db->select('IFNULL(MAX(`Voucher_Number`),0) AS `Voucher_Number`');
        $voucher = $this->db->get('qur_expence_master')->result();
        $voucher_Num = $voucher[0]->Voucher_Number;
        $v_number = $voucher_Num + 1;
        return str_pad($v_number, 4, 0, STR_PAD_LEFT);
    }

    public function Save_Detail()
    {
        $VoucherNo = $this->Get_VoucherNumber();
        isset($_POST['Receiver_Name'])?$this->db->set('Receiver_Name',$this->input->post('Receiver_Name')):$this->db->set('Receiver_Name','');
        $this->db->set('Voucher_Number',$VoucherNo);
        $this->db->set('DateG',$this->input->post('DateG'));
        $this->db->set('DateH',$this->input->post('DateH'));
        $this->db->set('Description',$this->input->post('description'));
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        $this->db->insert('qur_expence_master');
        if($this->db->affected_rows() > 0){
            $this->db->select_max('Id');
            $Max_id = $this->db->get('qur_expence_master')->result();
            foreach ($_POST['Expence_Type_Id'] as $key => $item) {
                $this->db->set('Master_Id',$Max_id[0]->Id);
                $this->db->set('Expence_Type_Id',$_POST['Expence_Type_Id'][$key]);
                $this->db->set('Amount',$_POST['Amount'][$key]);
                $this->db->set('Description',$_POST['Description'][$key]);
                $this->db->insert('qur_expence_detail');
            }
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function Update_Detail($id)
    {
        $this->db->where('qur_expence_detail.Master_Id', $id);
        $this->db->delete('qur_expence_detail');

        isset($_POST['Receiver_Name'])?$this->db->set('Receiver_Name',$this->input->post('Receiver_Name')):$this->db->set('Receiver_Name','');
        $this->db->set('DateG',$this->input->post('DateG'));
        $this->db->set('DateH',$this->input->post('DateH'));
        $this->db->set('Description',$this->input->post('description'));
        $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
        $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
        $this->db->where('qur_expence_master.Id',$id);
        $this->db->update('qur_expence_master');
        if($this->db->affected_rows() > 0){
            foreach ($_POST['EditExpence_Type_Id'] as $key => $item) {
                $this->db->set('Master_Id',$id);
                $this->db->set('Expence_Type_Id',$_POST['EditExpence_Type_Id'][$key]);
                $this->db->set('Amount',$_POST['EditAmount'][$key]);
                $this->db->set('Description',$_POST['EditDescription'][$key]);
                $this->db->insert('qur_expence_detail');
            }
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function Save_Expence_Voucher()
    {
        if (isset($_POST['edit'])){
            isset($_POST['Name'])?$this->db->set('Name',$this->input->post('Name')):'';
            isset($_POST['Halqy_Id'])?$this->db->set('Halqy_Id',$this->input->post('Halqy_Id')):'';
            isset($_POST['Is_Advance'])?$this->db->set('Is_Advance',$this->input->post('Is_Advance')):'';
            isset($_POST['DateG'])?$this->db->set('DateG',$this->input->post('DateG')):'';
            isset($_POST['DateH'])?$this->db->set('DateH',$this->input->post('DateH')):'';
            isset($_POST['Description'])?$this->db->set('Description',$this->input->post('Description')):'';
            isset($_POST['Amount'])?$this->db->set('Amount',$this->input->post('Amount')):'';
            isset($_POST['CreatedBy'])?$this->db->set('CreatedBy',$this->input->post('CreatedBy')):'';
            isset($_POST['CreatedOn'])?$this->db->set('CreatedOn',$this->input->post('CreatedOn')):'';
            isset($_POST['UpdatedBy'])?$this->db->set('Name',$_SESSION['user'][0]->id):'';
            isset($_POST['UpdatedOn'])?$this->db->set('Name',date('Y-m-d H:i:s')):'';
            $this->db->update('qur_expence_voucher');
        }else{
            $this->db->select_max('VoucherNumber');
            $max = $this->db->get('qur_expence_voucher')->result();
            if ($max[0]->VoucherNumber != ''){
                $max_num = $max[0]->VoucherNumber;
                $max_num++;
                $voucher_number = str_pad($max_num,4,0,STR_PAD_LEFT);
            }else{
                $voucher_number = '0001';
            }

            $this->db->set('VoucherNumber',$voucher_number);
            $this->db->set('Name',$this->input->post('Name'));
            isset($_POST['Halqy_Id'])?$this->db->set('Halqy_Id',$this->input->post('Halqy_Id')):'';
            isset($_POST['Is_Advance'])?$this->db->set('Is_Advance',$this->input->post('Is_Advance')):'';
            $this->db->set('DateG',$this->input->post('DateG'));
            $this->db->set('DateH',$this->input->post('DateH'));
            $this->db->set('Description',$this->input->post('Description'));
            $this->db->set('Amount',$this->input->post('Amount'));
            $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
            $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('qur_expence_voucher');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Delete_exp_voucher($id)
    {
        $this->db->where('Id',$id);
        $this->db->delete('qur_expence_voucher');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Delete_voucher($id)
    {
        $this->db->where('qur_expence_master.Id',$id);
        $this->db->delete('qur_expence_master');
        if ($this->db->affected_rows() > 0) {
            $this->db->where('qur_expence_detail.Master_Id', $id);
            $this->db->delete('qur_expence_detail');
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }
}