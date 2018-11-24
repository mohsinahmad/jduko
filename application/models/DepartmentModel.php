<?php

class DepartmentModel extends CI_Model
{
	 public function __construct()
    {
        parent::__construct();
        $this->load->model('ChartModel');
        $this->load->model('YearModel');
        date_default_timezone_set('Asia/Karachi');
        $this->activeyear = $this->session->userdata('current_year');
        $this->year_status = $this->YearModel->getYearStatus($this->activeyear);
    }

    public function department_name()
    {
        return $this->db->get('departments')->result();
    }

    public function DepartNameBy_Id($id)
    {
        $this->db->select('*');
        $this->db->where('Id', $id);
        return $this->db->get('departments')->result();
    }

    public function account_name($comp_id,$book_type='')
    {
        if($book_type == 'CR' || $book_type == 'CP')
        {
           $types = array(1,7);
        }else{
            $types = array(2,7);
        }
        if($this->year_status->Active == 1) {
    	$this->db->select('a.id, a.AccountCode, a.AccountName ,b.AccountName as parentName');
    	$this->db->from('chart_of_account');
    	$this->db->join('account_title a', 'a.id = chart_of_account.AccountId');
    	$this->db->join('account_title b', 'b.AccountCode = a.ParentCode');
        $this->db->join('chart_of_account_years', 'chart_of_account.id=chart_of_account_years.ChartOfAccountId');
        $this->db->where('chart_of_account_years.Active', 1);
        $this->db->where('chart_of_account.LevelId', $comp_id);
        if($book_type != ''){
        $this->db->where_in('account_title.Type', $types);
        }
    }else{
        $this->db->select('a.id, a.AccountCode, a.AccountName ,b.AccountName as parentName');
        $this->db->from('chart_of_account');
        $this->db->join('account_title a', 'a.id = chart_of_account.AccountId');
        $this->db->join('account_title b', 'b.AccountCode = a.ParentCode');
        $this->db->join('archived_chart_of_account_years', 'chart_of_account.id=archived_chart_of_account_years.ChartOfAccountId');
        $this->db->where('archived_chart_of_account_years.Active', 1);
        $this->db->where('chart_of_account.LevelId', $comp_id);
        if($book_type != ''){
        $this->db->where_in('account_title.Type', $types);
        }

    }
    	return $this->db->get()->result();
    }
    public function add_department()
    {
        $createdOn = date('Y-m-d H:i:s');
        $createdBy = $_SESSION['user'][0]->id;
        $departname = array('DepartmentName' => $this->input->post('depart_name',true), 'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);
        if(empty($departname)){
            return false;
        }else{
            $this->db->insert('departments', $departname);
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function Delete_Depart($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('departments');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Update_Depart($id)
    {
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;
        $this->db->set('DepartmentName', $_POST['Dname']);
        $this->db->set('UpdatedBy', $updatedBy);
        $this->db->set('UpdatedOn', $updatedOn);
        $this->db->where('Id',$id);
        $this->db->update('departments');

        if($this->db->affected_rows()>0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function get_accounts($id)
    {
        $this->db->select('a.id, a.AccountCode, a.AccountName,a.Type,b.AccountName as parentName');
        $this->db->from('account_title as a');
        $this->db->join('account_title as b', 'b.AccountCode = a.ParentCode');
        $this->db->join('chart_of_account', 'chart_of_account.AccountId = a.id');
        $this->db->join('chart_of_account_years', 'chart_of_account.id=chart_of_account_years.ChartOfAccountId');
        $this->db->where('chart_of_account_years.Active', 1);
        $this->db->where('chart_of_account.LevelId', $id);
//        $this->db->where('chart_of_account.LevelId', $id)->group_start();
//        $this->db->like('a.AccountCode' , '4','after');
//        $this->db->or_where_in('a.Type', array('1','2'))->group_end();
        
        return $this->db->get()->result();

    }

}