<?php

class ChartModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('LinkModel');
    }

    public function save_data()
    {
        $this->db->insert('account_title', $_POST);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_head($id)
    {
        $this->db->select('AccountName');
        $this->db->where('id', $id);
        return $this->db->get('account_title')->result();
    }

    public function get_a_data($code)
    {
        $this->db->where('AccountCode', $code);
        return $this->db->get('account_title')->result();
    }

    public function get_data()
    {
        return $this->db->get('account_title')->result();
    }

    public function get_account_name_for_ledger($id)
    {
        foreach ($id as $accid){
            $this->db->select();
            $this->db->where_in('id', $accid);
            $result[] = $this->db->get('account_title')->result();
        }
        return $result;
    }

    public function get_account_name($id = '', $cat = '', $parent = '')
    {
        $this->db->select();
        if ($id != '') {
            $this->db->where_in('id', $id);
        } elseif ($cat != '') {
            $this->db->where('Category', $cat);
        }
        $this->db->order_by('AccountCode','asc');
        return $this->db->get('account_title')->result();

    }

    public function get_parent_Name($id)
    {
        $this->db->where('AccountCode', $id);
        $parentData = $this->db->get('account_title')->result();
        return $parentData[0]->AccountName;
    }

    public function get_account_name_2($comp='',$head='')
    {


        $this->db->select('*,chart_of_account.id as ch_id,a.id as a_id,a.AccountName ,b.AccountName as parentName');
        $this->db->from('chart_of_account');
        $this->db->join('account_title a', 'a.id = chart_of_account.AccountId');
        $this->db->join('account_title b', 'b.AccountCode = a.ParentCode');
        $this->db->join('account_title', 'account_title.id = chart_of_account.AccountId');
        $this->db->group_by('a.id');
        if($comp != ''){
            $this->db->where('chart_of_account.LevelId', $comp);
        }
        if($head != ''){
            $this->db->like('a.AccountCode', $head,'after');
        }
        $this->db->order_by('a.AccountCode','asc');
        return $this->db->get()->result();
    }

    public function getSubHead($type)
    {
        if ($type == "assets") {
            $where = array('ParentCode' => 1, 'head' => 1);
            $this->db->where($where);
            $this->db->order_by('AccountCode', 'asc');
            return $this->db->get('account_title')->result();
        } else if ($type == "libilities") {
            $where = array('ParentCode' => 2, 'head' => 2);
            $this->db->where($where);
            $this->db->order_by('AccountCode', 'asc');
            return $this->db->get('account_title')->result();
        } else if ($type == "capital") {
            $where = array('ParentCode' => 3, 'head' => 3);
            $this->db->where($where);
            $this->db->order_by('AccountCode', 'asc');
            return $this->db->get('account_title')->result();
        } else if ($type == "revenue") {
            $where = array('ParentCode' => 4, 'head' => 4);
            $this->db->where($where);
            $this->db->order_by('AccountCode', 'asc');
            return $this->db->get('account_title')->result();
        } else if ($type == "expense") {
            $where = array('ParentCode' => 5, 'head' => 5);
            $this->db->where($where);
            $this->db->order_by('AccountCode', 'asc');
            return $this->db->get('account_title')->result();
        }
    }

    public function get_SubHead($id, $type)
    {
        if ($type == 'assets') {
            $this->db->where('ParentCode', $id);
            return $this->db->get('account_title')->result();
        } else if ($type == 'libilities') {
            $this->db->where('ParentCode', $id);
            return $this->db->get('account_title')->result();
        } else if ($type == 'capital') {
            $this->db->where('ParentCode', $id);
            return $this->db->get('account_title')->result();
        } else if ($type == 'revenue') {
            $this->db->where('ParentCode', $id);
            return $this->db->get('account_title')->result();
        } else if ($type == 'expense') {
            $this->db->where('ParentCode', $id);
            return $this->db->get('account_title')->result();
        }
    }

    public function get_Edit_Account($id)
    {
        $child = 0;
        $this->db->where('AccountCode', $id);
        $data = $this->db->get('account_title')->result();
        $this->db->where('ParentCode', $id);
        $check_child = $this->db->get('account_title');
        if ($check_child->num_rows() > 0) {
            $child = 1;
        }
        if ($data[0]->ParentCode == 0 && $data[0]->AccountCode == 1) {
            $account = array('parentName' => "Assets", 'LevelData' => $data, 'child' => $child);
            return $account;
        } else if ($data[0]->ParentCode == 0 && $data[0]->AccountCode == 2) {
            $account = array('parentName' => "Libilities", 'LevelData' => $data, 'child' => $child);
            return $account;
        } else if ($data[0]->ParentCode == 0 && $data[0]->AccountCode == 3) {
            $account = array('parentName' => "Capital", 'LevelData' => $data, 'child' => $child);
            return $account;
        } else if ($data[0]->ParentCode == 0 && $data[0]->AccountCode == 4) {
            $account = array('parentName' => "Revenue", 'LevelData' => $data, 'child' => $child);
            return $account;
        } else if ($data[0]->ParentCode == 0 && $data[0]->AccountCode == 5) {
            $account = array('parentName' => "Expense", 'LevelData' => $data, 'child' => $child);
            return $account;
        } else {
            $parentname = $this->get_parent_Name($data[0]->ParentCode);
            $account = array('parentName' => $parentname, 'LevelData' => $data, 'child' => $child);
            return $account;
        }
    }

    public function new_Account($id)
    {
        $head = "";
        $this->db->where('AccountCode', $id);
        $data = $this->db->get('account_title')->result();
        $parent_code = $data[0]->AccountCode;
        $parent_id = $data[0]->ParentCode;
        if ($parent_id == 0) {
            $head = $parent_code;
        } else {
            $head = $id[0];
        }
        $parent_name = $data[0]->AccountName;
        $this->db->where('ParentCode', $parent_code);
        $parentData = $this->db->get('account_title');
        if ($parentData->num_rows() > 0) {
            $ParentsData = $parentData->result();
            $maxtoSearch = $ParentsData[0]->ParentCode;
            $max_id = $this->get_max($maxtoSearch);
            $level_id = (string)$max_id[0]->AccountCode;
            //$newCode = substr($level_id, -5);
            //echo $level_id;
            //echo "<br>";
            $new = ++$level_id;
            //echo "New : ".$new;
            //echo "<br>";
            //$new = str_pad($new, 3, "0", STR_PAD_LEFT);
            //echo "New after padding: ".$new;
            //echo "<br>";
            //$new_id = substr_replace($level_id, $new, -5);
            //echo "New_id : ".$new_id;
            //echo "<br>";
            //exit();
            $newAccount = array('parentID' => $parent_code, 'parentNAME' => $parent_name, 'new_ID' => $new, 'head' => $head);
            $result = $newAccount;
        } else {
            $level_id = $parent_code . '01';
            $newAccount = array('parentID' => $parent_code, 'parentNAME' => $parent_name, 'new_ID' => $level_id, 'head' => $head);
            $result = $newAccount;
        }
        return $result;
    }

    public function get_max($id)
    {
        $this->db->where('ParentCode', $id);
        $this->db->select_max('AccountCode');
        return $this->db->get('account_title')->result();
    }

    public function save_new_account()
    {
        $createdOn = date('Y-m-d H:i:s');
        $createdBy = $_SESSION['user'][0]->id;
        $chartofaccount = array('ParentCode' => $_POST['ParentCode'], 'AccountCode' =>$_POST['AccountCode'], 'AccountName' => $_POST['AccountName'], 'Head' =>$_POST['Head'], 'Type' => $_POST['Type'], 'Category' => $_POST['Category'], 'CreatedBy' => $createdBy, 'CreatedOn' => $createdOn);
        $this->db->insert('account_title', $chartofaccount);
        if ($this->db->affected_rows() > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function edit_account($id)
    {
        $updatedOn = date('Y-m-d H:i:s');
        $updatedBy = $_SESSION['user'][0]->id;

        $chartofaccountedit = array('ParentCode' => $_POST['ParentCode'], 'AccountCode' =>$_POST['AccountCode'], 'AccountName' => $_POST['AccountName'], 'Head' =>$_POST['Head'], 'Type' => $_POST['Type'], 'Category' => $_POST['Category'], 'UpdatedBy' => $updatedBy, 'UpdatedOn' => $updatedOn);
        $this->db->where('AccountCode', $id);
        $this->db->update('account_title', $chartofaccountedit);
        if ($this->db->affected_rows() > 0) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function delete_account($id)
    {
        $aid = "";
        $this->db->where('ParentCode', $id);
        $childs = $this->db->get('account_title');
        if ($childs->num_rows() > 0) {
            $result = false;
        } else {
            $this->db->select('id');
            $this->db->where('AccountCode', $id);
            $data = $this->db->get('account_title')->result();
            $aid = $data[0]->id;
            $this->db->where('AccountId', $aid);
            $ch_account = $this->db->get('chart_of_account');
            if ($ch_account->num_rows() > 0) {
                $result = 203;
            } else {
                $this->db->where('AccountCode', $id);
                $this->db->delete('account_title');
                if ($this->db->affected_rows() > 0) {
                    $result = true;
                }
            }
        }
        return $result;
    }

    public function get_l4SubHead($id, $type)
    {
        if ($type == 'assets') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'libilities') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'capital') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'revenue') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'expense') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        }
        return $result;
    }

    public function get_l5SubHead($id, $type)
    {
        if ($type == 'assets') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'libilities') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'capital') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'revenue') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'expense') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        }
        return $result;
    }

    public function get_l6SubHead($id, $type)
    {
        if ($type == 'assets') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'libilities') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'capital') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'revenue') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'expense') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        }
        return $result;
    }

    public function get_l7SubHead($id, $type)
    {
        if ($type == 'assets') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'libilities') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'capital') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'revenue') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        } else if ($type == 'expense') {
            $this->db->where('ParentCode', $id);
            $result = $this->db->get('account_title')->result();
        }
        return $result;
    }

    public function get_MainHead()
    {
        $query = 'SELECT ParentCode,AccountCode,AccountName FROM account_title WHERE ParentCode = 0';
        $data = $this->db->query($query)->result();
        return $data;
    }

    public function get_account_code($id)
    {
        $this->db->select('id,AccountCode,AccountName,Type');
        $this->db->where('id', $id);
        return $this->db->get('account_title')->result();
    }

    public function GetAccountTree($accounts)
    {

        $level = array();
        foreach ($accounts as $KEY1 => $level1) {
            $this->db->where('AccountCode',$level1);
            $this->db->order_by('AccountCode', 'asc');
            $level[$KEY1] = $this->db->get('account_title')->result();

            foreach ($level[$KEY1] as $KEY2 => $level2) {
                if (isset($level2)){
                    $this->db->where('ParentCode',$level2->AccountCode);
                    $this->db->order_by('AccountCode', 'asc');
                    $level[$KEY1]['Child'.$KEY2] = $this->db->get('account_title')->result();
                   // echo $this->db->last_query();
                    foreach ($level[$KEY1]['Child'.$KEY2] as $KEY3 => $level3) {
                        if (isset($level3)) {
                            if ($level3->Category != 2) {
                                $this->db->where('ParentCode', $level3->AccountCode);
                                $this->db->order_by('AccountCode', 'asc');
                                $level[$KEY1]['Child'.$KEY2]['Child' . $KEY3] = $this->db->get('account_title')->result();
                                foreach ($level[$KEY1]['Child'.$KEY2]['Child' . $KEY3] as $KEY4 => $level4) {
                                    if (isset($level4)) {
                                        if ($level4->Category != 2) {
                                            $this->db->where('ParentCode', $level4->AccountCode);
                                            $this->db->order_by('AccountCode', 'asc');
                                            $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child' . $KEY4] = $this->db->get('account_title')->result();
                                            foreach ($level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child' . $KEY4] as $KEY5 => $level5) {
                                                if (isset($level5)){
                                                    if ($level4->Category != 2) {
                                                        $this->db->where('ParentCode',$level5->AccountCode);
                                                        $this->db->order_by('AccountCode', 'asc');
                                                        $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child' . $KEY5] = $this->db->get('account_title')->result();
                                                        foreach ($level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child' . $KEY5] as $KEY6 => $level6) {
                                                            if (isset($level6)){
                                                                if ($level4->Category != 2){
                                                                    $this->db->where('ParentCode',$level6->AccountCode);
                                                                    $this->db->order_by('AccountCode', 'asc');
                                                                    $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child'.$KEY5]['Child' . $KEY6] = $this->db->get('account_title')->result();
                                                                    foreach ($level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child'.$KEY5]['Child' . $KEY6] as $KEY7 => $level7) {
                                                                        if (isset($level7)){
                                                                            if ($level4->Category != 2) {
                                                                                $this->db->where('ParentCode',$level7->AccountCode);
                                                                                $this->db->order_by('AccountCode', 'asc');
                                                                                $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child'.$KEY5]['Child'.$KEY6]['Child' . $KEY7] = $this->db->get('account_title')->result();
                                                                            }
                                                                        }

                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
//                                                    echo $level5->AccountName.'<br>';
                                                    if($_POST['parent'] == '37') {
                                                        if (strpos($level5->AccountName, '-') !== false) {
                                                            $a = explode('-', $level5->AccountName);
                                                            $level5->AccountName = $a[0];
//                                                    echo '<pre>';
//                                                    print_r($a);
//                                                    echo '</pre>';
                                                        }
                                                    }
                                                }
                                            }
                                            if($_POST['parent'] == '37') {
                                                if (strpos($level4->AccountName, '-') !== false) {
//                                                    echo $level4->AccountName.'<br>';
                                                    $a = explode('-', $level4->AccountName);
                                                    $level4->AccountName = $a[0];
//                                                    echo '<pre>';
//                                                    print_r($a);
//                                                    echo '</pre>';
                                                }
                                            }

                                        }
                                    }
                                }
                                if($_POST['parent'] == '37') {
                                    if (strpos($level3->AccountName, '-') !== false) {
//                                        echo $level3->AccountName.'<br>';
                                        $a = explode('-', $level3->AccountName);
                                        $level3->AccountName = $a[0];
                                    }
                                }
                            }
                        }
                    }
//                    if($_POST['parent'] == '37') {
//                        if (strpos($level2->AccountName, '-') !== false) {
//                            $a = explode('-', $level2->AccountName);
//                            $level2->AccountName = $a[0];
//                        }
//                    }
//                    echo $level2->AccountName.'<br>';
                }
            }

        }
        return $level;
    }
    //sufyan work start from here

    public function GetAccountTreeby_parent_ledger($parent_accounts)
    {

        $level = array();
        foreach ($parent_accounts as $KEY1 => $level1) {
            $this->db->where('AccountCode',$level1);
            $this->db->select('AccountCode,AccountName,Category');
            $this->db->order_by('AccountCode', 'asc');
            $level[$KEY1] = $this->db->get('account_title')->result();
            foreach ($level[$KEY1] as $KEY2 => $level2) {
                if (isset($level2)){
                    $this->db->where('ParentCode',$level2->AccountCode);
                    $this->db->select('AccountCode,AccountName,Category');
                    $this->db->order_by('AccountCode', 'asc');
                    $level[$KEY1]['Child'.$KEY2] = $this->db->get('account_title')->result();
                    foreach ($level[$KEY1]['Child'.$KEY2] as $KEY3 => $level3) {
                       if (isset($level3)) {
                           if ($level3->Category != 2) {
                               $this->db->where('ParentCode', $level3->AccountCode);
                               $this->db->select('AccountCode,AccountName,Category');
                               $this->db->order_by('AccountCode', 'asc');
                               $level[$KEY1]['Child'.$KEY2]['Child' . $KEY3] = $this->db->get('account_title')->result();
                              foreach ($level[$KEY1]['Child'.$KEY2]['Child' . $KEY3] as $KEY4 => $level4) {
                                   if (isset($level4)) {
                                       if ($level4->Category != 2) {
                                           $this->db->where('ParentCode', $level4->AccountCode);
                                           $this->db->order_by('AccountCode', 'asc');
                                           $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child' . $KEY4] = $this->db->get('account_title')->result();
                                           foreach ($level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child' . $KEY4] as $KEY5 => $level5) {
                                               if (isset($level5)){
                                                   if ($level4->Category != 2) {
                                                       $this->db->where('ParentCode',$level5->AccountCode);
                                                       $this->db->order_by('AccountCode', 'asc');
                                                       $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child' . $KEY5] = $this->db->get('account_title')->result();
                                                       foreach ($level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child' . $KEY5] as $KEY6 => $level6) {
                                                           if (isset($level6)){
                                                               if ($level4->Category != 2){
                                                                   $this->db->where('ParentCode',$level6->AccountCode);
                                                                   $this->db->order_by('AccountCode', 'asc');
                                                                   $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child'.$KEY5]['Child' . $KEY6] = $this->db->get('account_title')->result();
                                                                   foreach ($level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child'.$KEY5]['Child' . $KEY6] as $KEY7 => $level7) {
                                                                       if (isset($level7)){
                                                                           if ($level4->Category != 2) {
                                                                               $this->db->where('ParentCode',$level7->AccountCode);
                                                                               $this->db->order_by('AccountCode', 'asc');
                                                                               $level[$KEY1]['Child'.$KEY2]['Child'.$KEY3]['Child'.$KEY4]['Child'.$KEY5]['Child'.$KEY6]['Child' . $KEY7] = $this->db->get('account_title')->result();
                                                                           }
                                                                       }
                                                                   }
                                                               }
                                                           }
                                                       }
                                                   }
                                               }
                                           }
                                       }
                                   }
                               }
                           }
                       }
                   }
                }
            }
        }
        return $level;
    }































    // sufyan work end here
    public function get_move_account_name($code,$cmp_id)
    {
        $this->db->select('id');
        $this->db->where('AccountCode',$code);
        $id = $this->db->get('account_title')->result();
        if($id){
            $is_linked = $this->check_link($id[0]->id,$cmp_id);
            if($is_linked->num_rows() > 0){
                $this->db->select('AccountName');
                $this->db->where('AccountCode',$code);
                $result = $this->db->get('account_title')->result();
            }else{
                $result = null;
            }
        } return $result;
    }

    public function check_link($id,$cmp_id)
    {
        $this->db->select('id');
        $this->db->where('AccountID',$id);
        $this->db->where('Levelid',$cmp_id);
        return $this->db->get('chart_of_account');
    }

    public function getBalances($level_id)
    {
        $OriginAccount = $this->input->post('AccountCode1');
        $DestAccount = $this->input->post('AccountCode2');
//        $to = $this->input->post('to');
//        $from = $this->input->post('from');
//        $ttype = $this->input->post('ttype');
        $result = array();
        $O_id = $this->Get_Aid($OriginAccount);
        $D_id = $this->Get_Aid($DestAccount);
        $ids = array($O_id->id,$D_id->id);
        foreach ($ids as $id) {
            $this->db->select('SUM(OpeningBalance) as OpeningBalance');
            $this->db->from('`chart_of_account_years`');
            $this->db->join('chart_of_account','chart_of_account_years.ChartOfAccountId = chart_of_account.id');
            $this->db->where('chart_of_account.LevelId',$level_id);
            $this->db->where('chart_of_account.AccountId',$id);
            $this->db->where('chart_of_account_years.Year',$_SESSION['current_year']);
            $result[] = $this->db->get()->result();
        }
        return $result;

//        $data['OAPDC'] = $this->getPerDebCred($O_id->id,$level_id,$to,$from);
//        if(!$data['OAPDC']->Debit && !$data['OAPDC']->Credit){
//            $data['OAPDC']->Debit = '0.00';
//            $data['OAPDC']->Credit = '0.00';
//        }
//        $data['DAPDC'] = $this->getPerDebCred($D_id->id,$level_id,$to,$from);
//        if(!$data['DAPDC']->Debit && !$data['DAPDC']->Credit){
//            $data['DAPDC']->Debit = '0.00';
//            $data['DAPDC']->Credit ='0.00';
//        }
//        $data['OATDC'] = $this->getTempDebCred($O_id->id,$level_id,$to,$from);
//        if(!$data['OATDC']->Debit && !$data['OATDC']->Credit){
//            $data['OATDC']->Debit = '0.00';
//            $data['OATDC']->Credit = '0.00';
//        }
//        $data['DATDC'] = $this->getTempDebCred($D_id->id,$level_id,$to,$from);
//        if(!$data['DATDC']->Debit && !$data['DATDC']->Credit){
//            $data['DATDC']->Debit = '0.00';
//            $data['DATDC']->Credit ='0.00';
//        }
//        $oa_id =  $this->getAccLinkid($O_id->id,$level_id);
//        $da_id = $this->getAccLinkid($D_id->id,$level_id);
//        $data['OAB'] = $this->getAccBalances($oa_id->id);
//        $data['DAB']= $this->getAccBalances($da_id->id);
//        $data['OAID'] = $O_id->id;
//        $data['DAID'] = $D_id->id;
//        return $data;
    }

    public function Get_Aid($code)
    {
        $this->db->select('id');
        $this->db->where('AccountCode',$code);
        return $this->db->get('account_title')->row();
    }

    public function getPerDebCred($id,$level,$to,$from)
    {
        $this->db->Select('SUM(debit) as Debit, SUM(credit) as Credit');
        $this->db->where('AccountID',$id);
        $this->db->where('LevelID',$level);
        $this->db->where("Permanent_VoucherDateG >= '" . $to . "' AND Permanent_VoucherDateG <=  '" . $from . "'");
        $this->db->where('Permanent_VoucherNumber !=', NULL);
        $this->db->from('transactions');
        $query1 = $this->db->get_compiled_select();

        $this->db->Select('SUM(debit) as Debit, SUM(credit) as Credit');
        $this->db->where('AccountID',$id);
        $this->db->where('LevelID',$level);
        $this->db->where("Permanent_VoucherDateG >= '" . $to . "' AND Permanent_VoucherDateG <=  '" . $from . "'");
        $this->db->where('Permanent_VoucherNumber !=', NULL);
        $this->db->from('income');
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query('Select SUM(debit) as Debit,SUM(credit) as Credit From('. $query1 . ' UNION ' . $query2.')'.'as t');

        return $query->row();
    }

    public function getTempDebCred($id,$level,$to,$from)
    {
        $this->db->Select('SUM(debit) as Debit, SUM(credit) as Credit');
        $this->db->where('AccountID',$id);
        $this->db->where('LevelID',$level);
        $this->db->where("VoucherDateG >= '" . $to . "' AND VoucherDateG <=  '" . $from . "'");
        $this->db->where('Permanent_VoucherNumber', NULL);
        $this->db->from('transactions');
        $query1 = $this->db->get_compiled_select();

        $this->db->Select('SUM(debit) as Debit, SUM(credit) as Credit');
        $this->db->where('AccountID',$id);
        $this->db->where('LevelID',$level);
        $this->db->where("VoucherDateG >= '" . $to . "' AND VoucherDateG <=  '" . $from . "'");
        $this->db->where('Permanent_VoucherNumber', NULL);
        $this->db->from('income');
        $query2 = $this->db->get_compiled_select();

        $query = $this->db->query('Select SUM(debit) as Debit,SUM(credit) as Credit From('. $query1 . ' UNION ' . $query2.')'.'as t');

        return $query->row();
    }

    public function getAccLinkid($id,$level)
    {
        $this->db->select('id');
        $this->db->where('AccountId', $id);
        $this->db->where('LevelId', $level);
        $this->db->from('chart_of_account');
        $a_id = $this->db->get()->row();
        return $a_id;
    }

    public function getAccBalances($id)
    {
        $this->db->select('OpeningBalance,CurrentBalance');
        $this->db->where('ChartOfAccountId', $id);
        return $this->db->get('chart_of_account_years')->row();
    }

    public function update_move_account()
    {
        $desAccId = $this->get_a_data($_POST['destAccountCode']);
        //$desAccId[0]->id

        $origenAccId = $this->get_a_data($_POST['originAccountCode']);
        //$origenAccId[0]->id

        $dacc_link_id =  $this->getAccLinkid($desAccId[0]->id,$_POST['level']);
        //$dacc_link_id->id

        $OriAcc_link_id =  $this->getAccLinkid($origenAccId[0]->id,$_POST['level']);
        //$OriAcc_link_id->id

        foreach ($_POST['trans_ids'] as $trans_id) {
            $this->db->set('AccountID',$desAccId[0]->id);
            $this->db->set('LinkID',$dacc_link_id->id); //LinkId
//            $this->db->where('AccountID', $_POST['originAccount']);
//            $this->db->where('LevelID', $level);
            $this->db->where('Id', $trans_id);
            $this->db->update('transactions');
//            if($this->db->affected_rows() > 0){
//
//            }else{
//                $this->db->set('AccountID',$desAccId[0]->id);
//                $this->db->set('LinkID',$dacc_link_id->id); //LinkId
////                $this->db->where('AccountID', $_POST['originAccount']);
////                $this->db->where('LevelID', $level);
//                $this->db->where('Id', $trans_id);
//                $this->db->update('income');
//            }
        }

        if($this->db->affected_rows() > 0){
            $is_updt_des =  $this->updateBalances($dacc_link_id->id,$_POST['DACB']);
            $is_updt_ori = $this->updateBalances($OriAcc_link_id->id,$_POST['OACB']);
            if($is_updt_ori && $is_updt_des){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function updateBalances($id,$bal)
    {
        $this->db->set('CurrentBalance',$bal);
        $this->db->where('ChartOfAccountId', $id);
        $this->db->update('chart_of_account_years');
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getIncomeAcc()
    {
        $query = $this->db->query("SELECT id FROM `account_title` WHERE AccountCode LIKE '4%'");
        return $query->result();
    }

    public function getExpenseAcc()
    {
        $query = $this->db->query("SELECT id FROM `account_title` WHERE AccountCode LIKE '5%'");
        return $query->result();
    }

    public function getBankAcc()
    {
        $this->db->select('id');
        $this->db->where('AccountCode','102040101');
        $this->db->from('account_title');
        return $this->db->get()->row();
    }
}