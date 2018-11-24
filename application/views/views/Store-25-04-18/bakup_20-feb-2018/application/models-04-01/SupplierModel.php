<?php
/**
* 
*/
class SupplierModel extends CI_Model
{
	
	function __construct()
	{
		parent::__construct();
	}

	public function GetSupplier($module)
	{
	    $this->db->where('Supplier_Module',$module);
		return $this->db->get('item_suppliers')->result();
	}
	public function Save_Supplier()
	{
        isset($_POST['NameU']) ? $this->db->set('NameU',$_POST['NameU']) : "";
		isset($_POST['NameE']) ? $this->db->set('NameE',$_POST['NameE']) : "";
		isset($_POST['account']) ? $this->db->set('ChartOfAcc_id',$_POST['account']) : "";
        isset($_POST['CNIC']) ? $this->db->set('CNIC',$_POST['CNIC']): "";
        isset($_POST['Phone_number']) ? $this->db->set('Phone_number',$_POST['Phone_number']): "";
        isset($_POST['AddressU']) ?  $this->db->set('AddressU',$_POST['AddressU']): "";
        isset($_POST['AddressE']) ?  $this->db->set('AddressE',$_POST['AddressE']): "";
        isset($_POST['Contact_person']) ?  $this->db->set('Contact_person',$_POST['Contact_person']): "";
        isset($_POST['NTN_number']) ?  $this->db->set('NTN_number',$_POST['NTN_number']): "";
        isset($_POST['Supplier_Module']) ?  $this->db->set('Supplier_Module',$_POST['Supplier_Module']): $this->db->set('Supplier_Module',1);
        isset($_POST['Nature_Of_Payment']) ?  $this->db->set('Nature_Of_Payment',$_POST['Nature_Of_Payment']): "";
		$this->db->set('CreatedBy',$_SESSION['user'][0]->id);
		$this->db->set('CreatedOn',date('Y-m-d H:i:s'));
		$this->db->insert('item_suppliers');
		if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
	}
	public function SupplierBy_Id($id)
	{	
		$this->db->select('*');
		$this->db->where('Id', $id);
		return $this->db->get('item_suppliers')->result();
	}
	public function Update_Supplier($id)
    {
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;
        $this->db->set('NameU', $_POST['SUname']);
        $this->db->set('NameE', $_POST['SEname']);
        $this->db->set('ChartOfAcc_id',$_POST['account']);
        $this->db->set('NTN_number', $_POST['ntn']);
        $this->db->set('CNIC', $_POST['cnic']);
        $this->db->set('AddressU', $_POST['UAddre']);
        $this->db->set('AddressE', $_POST['EAddre']);
        $this->db->set('Contact_person', $_POST['Cperson']);
        $this->db->set('Nature_Of_Payment',$_POST['Nature_Of_Payment']);
        $this->db->set('UpdatedBy', $updatedBy);
        $this->db->set('UpdatedOn', $updatedOn);
        $this->db->where('Id',$id);
        $this->db->update('item_suppliers');

        if($this->db->affected_rows()>0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function Delete_Supplier($id)
    {
        $this->db->where('Id', $id);
        $this->db->delete('item_suppliers');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function GetSupplierById($id)
    {
        $this->db->select('item_setup.code,item_setup.Id as Item_id,item_setup.name,item_stockrecieve_slip_details.Item_remarks,item_stockrecieve_slip_details.Item_price,item_stockrecieve_slip_details.Item_quantity,item_stockrecieve_slip.Buyer_name,item_stockrecieve_slip.Purchase_dateG,item_stockrecieve_slip.CreatedBy,item_stockrecieve_slip.CreatedOn,item_stockrecieve_slip.Slip_number,item_stockrecieve_slip.Purchase_dateH,item_stockrecieve_slip.Item_recieve_dateG,item_stockrecieve_slip.Item_recieve_dateH,item_suppliers.id, item_suppliers.NameU as sup_name,item_stockrecieve_slip.Id as s_id ');
        $this->db->join('item_stockrecieve_slip_details','item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id');
        $this->db->join('item_setup','item_setup.Id = item_stockrecieve_slip_details.Item_id');
        $this->db->join('item_suppliers','item_stockrecieve_slip.Supplier_Id = item_suppliers.Id');
        $this->db->where('item_stockrecieve_slip.Id',$id);
        return $this->db->get('item_stockrecieve_slip')->result();
    }

    public function GetSupplierAccountId($level)
    {
        $this->db->select('chart_of_account.`AccountId`');
        $this->db->join('chart_of_account','item_suppliers.`ChartOfAcc_id` = chart_of_account.`id`');
        $this->db->where('chart_of_account.`LevelId`',$level);
        return $this->db->get('item_suppliers')->result();
    }
}