<?php
/**
 *
 */
class AmountDescriptionModel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function GetCurrency()
    {
        return $this->db->get('currency')->result();
    }

    public function Save_Cheque_Currency_Detail($income_id)
    {
        if (isset($_POST['Currency_Id'])) {
            foreach ($_POST['Currency_Id'] as $key => $value) {
                $this->db->set('Income_Id', $income_id);
                $this->db->set('Currency_Id', $_POST['Currency_Id'][$key]);
                $this->db->set('Currency_Quantity', $_POST['Currency_Quantity'][$key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('income_currency_data');
            }
        }
        if (isset($_POST['Cheque_Number'] )) {
            foreach ($_POST['Cheque_Number'] as $C_key => $item) {
                $this->db->set('Income_Id', $income_id);
                $this->db->set('Cheque_Number', $_POST['Cheque_Number'][$C_key]);
                $this->db->set('Cheque_Date', $_POST['Cheque_Date'][$C_key]);
                $this->db->set('Bank_Name', $_POST['Bank_Name'][$C_key]);
                $this->db->set('Cheque_Type', $_POST['Cheque_Type'][$C_key]);
                $this->db->set('Cheque_amount', $_POST['Cheque_amount'][$C_key]);
                $this->db->set('CreatedBy',$_SESSION['user'][0]->id);
                $this->db->set('CreatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('income_cheque_data');
            }
        }
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Update_Income_Amount_Details($income_id)
    {
//		echo "<pre>";
//        print_r($income_id);
//        print_r($_POST);
//        exit();

        if (isset($_POST['Currency_Id'])) {
            $this->db->where('Income_Id',$income_id);
            $this->db->delete('income_currency_data');
            foreach ($_POST['Currency_Id'] as $key => $value) {
                $this->db->set('Income_Id', $income_id);
                $this->db->set('Currency_Id', $_POST['Currency_Id'][$key]);
                $this->db->set('Currency_Quantity', $_POST['Currency_Quantity'][$key]);
                $this->db->set('CreatedBy',$_POST['CreatedBy']);
                $this->db->set('CreatedBy',$_POST['CreatedOn']);
                $this->db->set('UpdatedBy',$_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn',date('Y-m-d H:i:s'));
                $this->db->insert('income_currency_data');
            }
        }

        if (isset($_POST['Cheque_Number'] )) {
            $this->db->where('Income_Id',$income_id);
            $this->db->delete('income_cheque_data');
            foreach ($_POST['Cheque_Number'] as $C_key => $item) {
                $this->db->set('Income_Id', $income_id);
                $this->db->set('Bank_Name', $_POST['Bank_Name'][$C_key]);
                $this->db->set('Cheque_Number', $_POST['Cheque_Number'][$C_key]);
                $this->db->set('Cheque_amount', $_POST['Cheque_amount'][$C_key]);
                $this->db->set('Cheque_Date', $_POST['Cheque_Date'][$C_key]);
                $this->db->set('Cheque_Type', $_POST['Cheque_Type'][$C_key]);
                $this->db->set('CreatedBy', $_POST['CreatedBy']);
                $this->db->set('CreatedOn', $_POST['CreatedOn']);
                $this->db->set('UpdatedBy', $_SESSION['user'][0]->id);
                $this->db->set('UpdatedOn', date('Y-m-d H:i:s'));
                $this->db->insert('income_cheque_data');
            }
        }

        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function Get_Edit_Cheque_Detail($income_id)
    {
        $this->db->select('*');
        $this->db->where('Income_Id', $income_id);
        $result = $this->db->get('income_cheque_data');
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return 0;
        }
    }

    public function Get_total_cheque_amount($income_id)
    {
        $this->db->select('IFNULL(SUM(Cheque_amount),0) as TotalCheque');
        $this->db->where('Income_Id', $income_id);
        $this->db->group_by('Income_Id');
        $result = $this->db->get('income_cheque_data');
        if($result->num_rows() > 0){
            return $result->row();
        }else{
            return 0;
        }
    }

    public function Get_Edit_Currency_Detail($income_id)
    {
        $this->db->select('income_currency_data.Id as IC_ID, income_currency_data.Income_Id,SUM(income_currency_data.Currency_Quantity) as Currency_Quantity,currency.Id as C_ID,currency.Currency,SUM(currency.Currency * income_currency_data.Currency_Quantity) as CurrencyAmount , income_currency_data.CreatedBy , income_currency_data.CreatedOn');
        $this->db->from('income_currency_data');
        $this->db->join('currency', 'income_currency_data.Currency_Id = currency.Id');
        $this->db->where('income_currency_data.Income_Id', $income_id);
        $this->db->group_by('Currency');
        $this->db->order_by('income_currency_data.Currency_Id','ASC');
        $result =  $this->db->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return null;
        }
    }

    public function Get_total_currency_amount($income_id)
    {
        // $this->db->select('IFNULL(SUM(currency.Currency * income_currency_data.Currency_Quantity),0) as CurrencyAmount');
        // $this->db->from('income_currency_data');
        // $this->db->join('currency', 'income_currency_data.Currency_Id = currency.Id');
        // $this->db->where('income_currency_data.Income_Id', $income_id);
        //$result = $this->db->get();
        $query = $this->db->query('SELECT Currency,CASE WHEN Currency = "ریزگاری" THEN Currency_Quantity ELSE SUM(Currency * Currency_Quantity) END as CurrencyAmount FROM `income_currency_data` JOIN `currency` ON `currency`.`Id` = `income_currency_data`.`Currency_Id` WHERE `Income_Id` = '.$income_id.' ');
        //return $query->row();
        if($query->num_rows() > 0){
            return $query->row();
        }else{
            return null;
        }
    }
}