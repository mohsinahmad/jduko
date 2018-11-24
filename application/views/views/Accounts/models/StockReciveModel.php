<?php
/**
 *
 */
class StockReciveModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('DemandFormModel');
        $this->load->model('ItemIssueModel');
    }

    public function Get_Stock()
    {
//        print_r($_SESSION['user']);

        $this->db->select('*,Sum(Item_price) as price,item_stockrecieve_slip.Id as s_id, users.UserName');
        $this->db->from('item_stockrecieve_slip');
        $this->db->join('item_stockrecieve_slip_details', 'item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id');
        $this->db->join('item_suppliers', 'item_stockrecieve_slip.Supplier_Id = item_suppliers.Id');
        $this->db->join('users', 'users.id = item_stockrecieve_slip.CreatedBy');
        if($_SESSION['user'][0]->IsAdmin != '1') {
            $this->db->where('users.UserName', $_SESSION['user'][0]->UserName);
        }
        $this->db->group_by('Slip_number');
        return $this->db->get()->result();
    }

    public function Save_Stock()
    {


        $Slip_number = $this->DemandFormModel->getSlipNumber('item_stockrecieve_slip');
        $this->db->set('Slip_number',$Slip_number);
        $this->db->set('Purchase_dateG',$_POST['Purchase_dateG']);
        $this->db->set('Purchase_dateH',$_POST['Purchase_dateH']);
        $this->db->set('Item_recieve_dateG',$_POST['Item_recieve_dateG']);
        $this->db->set('Item_recieve_dateH',$_POST['Item_recieve_dateH']);
        $this->db->set('Buyer_name',$_POST['Buyer_name']);
        $this->db->set('Supplier_Id',$_POST['Supplier_Id']);
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        $this->db->insert('item_stockrecieve_slip');

//        echo $this->db->last_query();

        if($this->db->affected_rows() > 0){
            $this->db->select('Id');
            $this->db->where('Slip_number',$Slip_number);
            $ID = $this->db->get('item_stockrecieve_slip')->result();

            foreach ($_POST['ItemName'] as $key => $item) {
                $this->db->set('Recieve_slip_id',$ID[0]->Id);
                $this->db->set('Item_quantity',$_POST['Item_quantity'][$key]);
                $this->db->set('Item_price',$_POST['Item_price'][$key]);
                $this->db->set('Item_remarks',$_POST['Item_remarks'][$key]);
                $this->db->set('Item_id',$_POST['Item_id'][$key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('item_stockrecieve_slip_details');

                $currQ = $this->ItemIssueModel->getCurrentQuantity($_POST['Item_id'][$key]);

                $updateQuan = $currQ[0]->current_quantity + $_POST['Item_quantity'][$key];
                $this->updateQuantity($_POST['Item_id'][$key],$updateQuan);
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

    public function updateQuantity($item_id,$quan)
    {
        $this->db->where('Id', $item_id);
        $this->db->set('current_quantity',$quan);
        $this->db->update('item_setup');
    }

    public function UpdateStock()
    {
        $this->db->select('Id');
        $this->db->where('Slip_number',$_POST['SlipNo']);
        $ID = $this->db->get('item_stockrecieve_slip')->result();

        $this->db->where('Recieve_slip_id', $_POST['StockSlipId']);
        $this->db->delete('item_stockrecieve_slip_details');

        $this->db->where('item_stockrecieve_slip.Id', $_POST['StockSlipId']);
        $this->db->set('Purchase_dateG',$_POST['Purchase_dateG']);
        $this->db->set('Purchase_dateH',$_POST['Purchase_dateH']);
        $this->db->set('Item_recieve_dateG',$_POST['Item_recieve_dateG']);
        $this->db->set('Item_recieve_dateH',$_POST['Item_recieve_dateH']);
        $this->db->set('Buyer_name',$_POST['Buyer_name']);
        $this->db->set('Supplier_Id',$_POST['Supplier_Id']);

        $this->db->update('item_stockrecieve_slip');


        foreach ($_POST['Item_id'] as $key => $item) {
            $this->db->set('Recieve_slip_id',$ID[0]->Id);
            $this->db->set('Item_quantity',$_POST['Item_quantity'][$key]);
            $this->db->set('Item_price',$_POST['Item_price'][$key]);
            $this->db->set('Item_remarks',$_POST['Item_remarks'][$key]);
            $this->db->set('Item_id',$item);
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            $this->db->insert('item_stockrecieve_slip_details');
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Stock_Voucher($id)
    {
        $this->db->select('item_stockrecieve_slip.Id as s_id,item_stockrecieve_slip.Slip_number,item_stockrecieve_slip.Purchase_dateG,item_stockrecieve_slip.Purchase_dateH,item_stockrecieve_slip.Item_recieve_dateG,item_stockrecieve_slip.Item_recieve_dateH,item_suppliers.NameU,item_stockrecieve_slip.Buyer_name,item_setup.code,item_stockrecieve_slip_details.Item_remarks,item_stockrecieve_slip_details.Item_quantity,item_stockrecieve_slip_details.Item_price,item_setup.name as name');
        $this->db->from('item_stockrecieve_slip');
        $this->db->join('item_stockrecieve_slip_details', 'item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id');
        $this->db->join('item_suppliers', 'item_stockrecieve_slip.Supplier_Id = item_suppliers.Id');
        $this->db->join('item_setup', 'item_stockrecieve_slip_details.Item_id = item_setup.Id');
        $this->db->where('item_stockrecieve_slip.Id', $id);
        return $this->db->get()->result();
    }

    public function DeleteStock($id)
    {
        $this->db->where('Recieve_slip_id', $id);
        $this->db->delete('item_stockrecieve_slip_details');

        $this->db->where('item_stockrecieve_slip.Id', $id);
        $this->db->delete('item_stockrecieve_slip');

        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_DataFor_Item_Stock($item_id,$to)
    {
        $this->db->select('`item_stockrecieve_slip_details`.`Item_id`,(item_stockrecieve_slip_details.Item_quantity) as Recieve_quantity,item_stockrecieve_slip.Item_recieve_dateG,item_stockrecieve_slip.Item_recieve_dateH,item_stockrecieve_slip.Slip_number,item_stockrecieve_slip.Buyer_name,item_suppliers.NameU');
        $this->db->join('item_stockrecieve_slip_details','item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id');
        $this->db->join('item_suppliers','item_stockrecieve_slip.Supplier_Id=item_suppliers.Id', 'left');
        $this->db->where('item_stockrecieve_slip_details.Item_id',$item_id);
        $this->db->where("item_stockrecieve_slip.Item_recieve_dateG <" ,$to);
        $this->db->order_by('item_stockrecieve_slip.Item_recieve_dateH' , 'ASC');
        return $this->db->get('item_stockrecieve_slip')->result();
    }
}