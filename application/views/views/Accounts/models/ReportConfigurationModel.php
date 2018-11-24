<?php
/**
 *
 */
class ReportConfigurationModel extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function Reportname()
    {
        return $this->db->get('reports')->result();
    }

    public function Save_Report()
    {
        $createdOn = date('Y-m-d H:i:s');
        $createdBy = $_SESSION['user'][0]->id;
        $report = array('ReportName' => $this->input->post('ReportName'),'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn, 'ReportType' => 1);
        if(empty($report)){
            return false;
        }else{
            $this->db->insert('reports', $report);
            if($this->db->affected_rows() > 0){
                return true;
            }else{
                return false;
            }
        }
    }

    public function ReportNameBy_Id($id)
    {
        $this->db->select('*');
        $this->db->where('Id', $id);
        return $this->db->get('reports')->result();
    }

    public function Update_Report($id)
    {
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;
        $this->db->set('ReportName', $_POST['Rname']);
        $this->db->set('UpdatedBy', $updatedBy);
        $this->db->set('UpdatedOn', $updatedOn);
        $this->db->where('Id',$id);
        $this->db->update('reports');

        if($this->db->affected_rows()>0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function Delete_Report($id)
    {
        $this->db->where('Id', $id);
        $this->db->delete('reports');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }
}