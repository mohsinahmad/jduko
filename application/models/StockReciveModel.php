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
        $this->db->join('item_stockrecieve_slip_details', 'item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id','left');
        $this->db->join('item_suppliers', 'item_stockrecieve_slip.Supplier_Id = item_suppliers.Id','left');
        $this->db->join('users', 'users.id = item_stockrecieve_slip.CreatedBy','left');
        if($_SESSION['user'][0]->IsAdmin != '1') {
            // $this->db->where('users.UserName', $_SESSION['user'][0]->UserName);
        }
        $this->db->group_by('Slip_number');
        return $this->db->get()->result();
    }

    public function Save_Stock()
    {
           
        isset($_POST['Temprary'])?$temprary = 1:$temprary=0;
        if($this->DemandFormModel->check_item("StockSlip")){
        $a = true;         
        $Slip_number =    $this->DemandFormModel->getSlipNumber('item_stockrecieve_slip');
        $this->db->set('Slip_number',$Slip_number);
        $this->db->set('Purchase_dateG',$_POST['Purchase_dateG']);
        $this->db->set('Purchase_dateH',$_POST['Purchase_dateH']);
        $this->db->set('Item_recieve_dateG',$_POST['Item_recieve_dateG']);
        $this->db->set('Item_recieve_dateH',$_POST['Item_recieve_dateH']);
       
        $this->db->set('Buyer_name',$_POST['Buyer_name']);
        $this->db->set('Supplier_Id',$_POST['Supplier_Id']);
        $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
        if($temprary == 1){
            $this->db->set("temprary",$temprary);
        }
        $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
        $this->db->insert('item_stockrecieve_slip'); 
        $last_slip_id = $this->db->insert_id();
        if($this->db->affected_rows() > 0){
        $this->db->select('Id');
        $this->db->where('Slip_number',$Slip_number);
        $ID =  $this->db->get('item_stockrecieve_slip')->result();
        foreach($_POST['ItemName'] as $key => $item) {
            $check = $this->db->query('select * from item_setup_details where item_setup_id = '.$_POST['Item_Id'][$key].'                              and
                donation_type =  '.$_POST['donation'][$key].'');
                if($check->num_rows() > 0){
                    $this->db->set('Recieve_slip_id',$ID[0]->Id);
                     if($temprary == 0){
                    $this->db->set('is_permit',1);
                    }
                    $this->db->set('Item_quantity',$_POST['Item_quantity'][$key]);
                    $this->db->set('Item_price',$_POST['Item_price'][$key]);
                    $this->db->set('Item_remarks',$_POST['Item_remarks'][$key]);
                    $this->db->set('donation_type',$_POST['donation'][$key]);
                    $this->db->set('remain_quantity',$_POST['Item_quantity'][$key]);
                    $this->db->set('Item_id',$_POST['Item_Id'][$key]);
                    $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                    $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                    $this->db->insert('item_stockrecieve_slip_details');
                    if($temprary == 0){
                        $currQ = $this->ItemIssueModel->getCurrentQuantity($_POST['Item_Id'][$key],
                    $_POST['donation'][$key]);                 
                    $updateQuan =  $currQ[0]->current_quantity + $_POST['Item_quantity'][$key];
                    $this->updateQuantity($_POST['Item_Id'][$key],$updateQuan,$_POST['donation'][$key]);    
                    }                                    
                }
                else{
                    $a = false;
                    $this->db->where("id",$last_slip_id);     
                    $this->db->delete('item_stockrecieve_slip');
                }                                         
        }

        if($this->db->affected_rows() > 0)
        {                 
            return true;
        }elseif($a == false){
                return 'not exits';
        }else{          
            return false;   
        }     
        }else{

        return false;    
        }
        }   
else{

    return false;
}

}
    public function Save_direct_issue()
    {
       // echo '<pre>';
       // print_r($_POST);
       // exit();
        $a = true;
            $Slip_number =    $this->DemandFormModel->getSlipNumber('item_stockrecieve_slip');
            $this->db->set('Slip_number',$Slip_number);
            $this->db->set('department',$_POST['Department']);
            $this->db->set('suplyer',$_POST['Supplier_Id']);
            $this->db->set('purchaser',$_POST['Buyer_name']);
            $this->db->set('issued_date',$_POST['Item_recieve_dateG']);
            $this->db->set('craeteby',$_SESSION['user'][0]->id);
            $this->db->set('createon',date('y-m-d h:i:s'));
            $this->db->set('purchase_date',$_POST['Purchase_dateG']);            
            $this->db->insert('direct_issue');
            $id = $this->db->insert_id();
            foreach($_POST['ItemName'] as $key => $item) {
                $this->db->set('direct_issue_id',$id);                
                $this->db->set('item_id',$_POST['Item_id'][$key]);
                $this->db->set('category_id',$_POST['category'][$key]);
                $this->db->set('donation_type',$_POST['donation'][$key]);
                $this->db->set('unit_of_measure',$_POST['unit'][$key]);
                $this->db->set('amount',$_POST['Item_price'][$key]);
                $this->db->set('quantity',$_POST['Item_quantity'][$key]);
                $this->db->set('createby',$_SESSION['user'][0]->id);
                $this->db->set('createon',date('y-m-d h:i:s'));
                $this->db->insert('direct_issue_details');
            }
            if($this->db->affected_rows() > 0)
            {
                return true;
            }else{
                return false;
            }
   }

                public function updateQuantity($item_id,$quan,$donation)
                { 

                $this->db->where('item_setup_id', $item_id);
                $this->db->where('donation_type', $donation);
                $this->db->set('current_quantity',$quan);
                $this->db->update('item_setup_details');     
            }
             

    public function UpdateStock()
    {

        isset($_POST['Temprary'])?$temprary = 1:$temprary=0;
        isset($_POST['permit'])?$permit = 1:$permit=0;
        $this->db->select('Id');
        $this->db->where('Slip_number',$_POST['SlipNo']);
       
        $ID = $this->db->get('item_stockrecieve_slip')->result();
        // echo '<pre>';
        // print_r($ID);
        // exit();
        $old_data = $this->db->get_where('item_stockrecieve_slip_details',array('Recieve_slip_id'=>$ID[0]->Id))->result();
            foreach ($old_data as $key => $value) {
                if($value->is_permit == 1){
                        // echo 'true';
                        $currQ = $this->ItemIssueModel->getCurrentQuantity($value->Item_id,
                        $value->donation_type);                    
                        $updateQuan =  $currQ[0]->current_quantity - $value->Item_quantity;
                        $this->updateQuantity($value->Item_id,$updateQuan,$value->donation_type); 
                }            
        }
        
        $this->db->where('Recieve_slip_id',$ID[0]->Id);
        $this->db->delete('item_stockrecieve_slip_details');
        if($this->db->affected_rows() > 0){
            $this->db->where('Slip_number',$_POST['SlipNo']);
            $this->db->delete('item_stockrecieve_slip');
            if($this->db->affected_rows() > 0){
            $this->db->set('Purchase_dateG',$_POST['Purchase_dateG']);
            $this->db->set('Purchase_dateH',$_POST['Purchase_dateH']);
            $this->db->set('Item_recieve_dateG',$_POST['Item_recieve_dateG']);
            $this->db->set('Item_recieve_dateH',$_POST['Item_recieve_dateH']);
            $this->db->set('Slip_number',$_POST['SlipNo']);
            $this->db->set('Buyer_name',$_POST['Buyer_name']);
            $this->db->set('temprary',$temprary);
            $this->db->set('Supplier_Id',$_POST['Supplier_Id']);
            $this->db->insert('item_stockrecieve_slip');
            } 
        }
         $last_recieve_id = $this->db->insert_id();
        foreach ($_POST['Item_id'] as $key => $item) {
            $this->db->set('Recieve_slip_id',$last_recieve_id);
            $this->db->set('Item_quantity',$_POST['Item_quantity'][$key]);
            $this->db->set('Item_price',$_POST['Item_price'][$key]);
            $this->db->set('Item_remarks',$_POST['Item_remarks'][$key]);
            $this->db->set('Item_id',$item);
            $this->db->set('CreatedBy',$_POST['CreatedBy']);
            $this->db->set('donation_type',$_POST['donation'][$key]);
            $this->db->set('CreatedOn',$_POST['CreatedOn']);
            $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
            $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
            if($permit == 1){
                $this->db->set('is_permit',1);
            }
            $this->db->insert('item_stockrecieve_slip_details');
            if($permit == 1){           
                        $currQ = $this->ItemIssueModel->getCurrentQuantity($_POST['Item_id'][$key],
                        $_POST['donation'][$key]);                    
                        $updateQuan =  $currQ[0]->current_quantity + $_POST['Item_quantity'][$key];
                        $this->updateQuantity($_POST['Item_id'][$key],$updateQuan,$_POST['donation'][$key]); 
            }
        }
           if($this->db->affected_rows() > 0){
                return true;
           }
           else{
              return false;
           }
    
}
        public function get_direct_issue_data(){
        $query = $this->db->query('select direct_issue.id as s_id,direct_issue.slip_number as Slip_number,direct_issue.purchaser,item_setup.name,donation_type.donation_type,
        direct_issue_details.unit_of_measure,direct_issue_details.amount,item_suppliers.NameU,
        direct_issue_details.quantity,direct_issue.purchase_date as Purchase_dateG,direct_issue.issued_date as Item_recieve_dateG,
        sum(direct_issue_details.amount) as price
        from direct_issue
        left join direct_issue_details on direct_issue.id = direct_issue_details.direct_issue_id
        left join item_setup on item_setup.id = direct_issue_details.item_id
        left join donation_type on donation_type.id = direct_issue_details.donation_type
        left join item_suppliers on item_suppliers.id = direct_issue.suplyer
        group by direct_issue.id')->result();    
            return $query;
        }
        public function get_direct_issue_report_data($id){
        $query = $this->db->query('select direct_issue.id as s_id,direct_issue.slip_number as slip_number,direct_issue.purchaser,item_setup.name,donation_type.donation_type,
        direct_issue_details.unit_of_measure,direct_issue_details.amount,item_suppliers.NameU,
        direct_issue_details.quantity,direct_issue.purchase_date as Purchase_dateG,direct_issue.issued_date as Item_recieve_dateG,
        direct_issue_details.amount as price
        from direct_issue
        left join direct_issue_details on direct_issue.id = direct_issue_details.direct_issue_id
        left join item_setup on item_setup.id = direct_issue_details.item_id
        left join donation_type on donation_type.id = direct_issue_details.donation_type
        left join item_suppliers on item_suppliers.id = direct_issue.suplyer
        where direct_issue.id = '.$id.'')->result();    
            return $query;
        }
    public function Get_Stock_Voucher($id)
    {
        $this->db->select('item_stockrecieve_slip.Id as s_id,item_stockrecieve_slip.Slip_number,item_stockrecieve_slip.Purchase_dateG,item_stockrecieve_slip.Purchase_dateH,item_stockrecieve_slip.Item_recieve_dateG,item_stockrecieve_slip.Item_recieve_dateH,item_suppliers.NameU,item_stockrecieve_slip.Buyer_name,item_setup.code,item_stockrecieve_slip_details.Item_remarks,item_stockrecieve_slip_details.Item_quantity,item_stockrecieve_slip_details.Item_price,item_setup.name as name');
        $this->db->from('item_stockrecieve_slip');
        $this->db->join('item_stockrecieve_slip_details', 'item_stockrecieve_slip.Id = item_stockrecieve_slip_details.Recieve_slip_id','left');
        $this->db->join('item_suppliers', 'item_stockrecieve_slip.Supplier_Id = item_suppliers.Id','left');
        $this->db->join('item_setup', 'item_stockrecieve_slip_details.Item_id = item_setup.Id','left');
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