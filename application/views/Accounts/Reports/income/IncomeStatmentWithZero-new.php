<html>
<head>
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>

    <link rel="apple-touch-icon"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/images/ico/apple-icon-120.png">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css-rtl/vendors.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/css/tables/datatable/datatables.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/css/tables/extensions/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/vendors/css/tables/datatable/buttons.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css-rtl/app.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css-rtl/custom-rtl.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css-rtl/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/app-assets/css-rtl/core/colors/palette-gradient.min.css">
    <link rel="stylesheet" type="text/css"
          href="https://pixinvent.com/stack-responsive-bootstrap-4-admin-template/assets/css/style-rtl.css">

    <style type="text/css">
        @font-face {
            font-family: "Noto Nastaliq Urdu";
            src: url(<?= base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
        }
        .table-bordered th, .table-bordered td{
            border: 1px solid #0e0e0e!important;
        }
        @media print {
            .table-bordered th, .table-bordered td{
                border: 1px solid #0e0e0e!important;
            }
        }
    </style>
</head>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}
elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}
else{
    $Access_level = '';
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,20,$Access_level);
if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[5];?>">
<?php }?>
<body class="dt-print-view" style="font-family: 'Noto Nastaliq Urdu', serif;">
<div style="text-align: center">
    <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:20%; max-width:330px;">
    <?php if ($Income_Of == 'p'){ ?>
        <h2 style="font-family: 'Noto Nastaliq Urdu', serif;font-size: 22px;margin-top: 0px;">آمدنی کا گوشوارہ<span style="font-size: 13px">(مستقل)</span></h2>
    <?php }else{ ?>
        <h2 style="font-family: 'Noto Nastaliq Urdu', serif;font-size: 22px;margin-top: 0px;text-decoration: underline; ">آمدنی کا گوشوارہ<span style="font-family: 'Noto Nastaliq Urdu', serif;font-size: 13px">(عارضی + مستقل)</span></h2>
    <?php }?>
    <h4 style="font-size: 16px;"><p style="margin-right: -15%"><?php echo $to[0]->Sh_date?></p><p style="margin-right: 147px;margin-top: -29px; margin-bottom: -2%"><?php echo $to[0]->Qm_date;?></p></h4>

    <br><span class ="ta" style="">تا</span>
    <span class="bamutabiq" style="margin-right: 4%;">بمطابق</span>
    <span class="ta1" style="margin-right: 5%;">تا</span><br>

    <h4 style="font-size: 16px;"><p style="margin-right: -15%"><?php echo $from[0]->Sh_date?></p><p style="margin-right: 147px;margin-top: -29px;"><?php echo $from[0]->Qm_date;?></p></h4>
</div>
    <div>
        <div>
            <span style="float: right"><?= $level[0]->LevelName?></span>
        </div>
        <div>
            <span style="float: left"><?= $account_level?> - لیول</span>
        </div>
    </div>
<div></div>
<table class="table table-striped table-bordered dataex-visibility-print dataTable" style="padding-top: 1%">
    <thead>
    <tr>
        <th>کوڈ</th>
        <th>اکاونٹ نام</th>
        <th>بیلنس</th>
    </tr>
    </thead>
    <tbody>
    <?php function searchForId($id, $array) {
        foreach ($array as $key1 => $val) {
            foreach ($val as $key2 => $item) {
                if (isset($item[0]->AccountCode)){
                    if ($item[0]->AccountCode === $id) {
                        $tr_id = array($key1,$key2,0);
                        return $tr_id;
                    }
                }
            }
        }
        return null;
    }
    $balance_sum = 0; $total_balance = 0; $Show_Total = 0;
    $t_id1 = array(); $t_id2 = array();
    $balance = 0; $count = 0; $balance_new = 0;
    foreach ($AccountTree as $acc_key => $Accounts){
        foreach ($Accounts as $key_1 => $level_1){ $Show_Total = 0;
            if (is_numeric($key_1)){
                if ($Links[$acc_key][$key_1] == 'y'){
                    if (isset($IsZero[$acc_key][$key_1])){
                        if ($IsZero[$acc_key][$key_1] == 'y' || $withOutZero == 0){?>
                            <tr>
                                <td><?= $level_1->AccountCode?></td>
                                <td><?= $level_1->AccountName?></td>
                                <?php if ($level_1->Category == '2' || $account_level == 'Detail' || $account_level == '1'){
                                    $t_id1 = searchForId($level_1->AccountCode,$pre_transactions);
                                    if ($t_id1 == array()){
                                        $t_id2 = searchForId($level_1->AccountCode,$post_transactions);
                                    }else{
                                        $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                        $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                        $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                        $balance = (($op_balance + $debit) - $credit);
                                        $t_id2 = array();
                                    } if ($t_id2 != array()){
                                        $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                        $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                        $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                        $balance = (($op_balance + $debit) - $credit);
                                        if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                            <td><?= number_format($balance_new)?></td>
                                        <?php }elseif($balance == '0.00'){?>
                                            <td><?= number_format($balance)?></td>
                                        <?php }else{ ?>
                                            <td>(<?= number_format($balance)?>)</td>
                                        <?php }
                                        }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                            <td>(<?= number_format($balance_new)?>)</td>
                                        <?php }else{?>
                                            <td><?= number_format($balance)?></td>
                                        <?php }
                                        }
                                    }else{ if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                        <td><?= number_format($balance_new)?></td>
                                    <?php }elseif($balance == '0.00'){?>
                                        <td><?= number_format($balance)?></td>
                                    <?php }else{ ?>
                                        <td>(<?= number_format($balance)?>)</td>
                                    <?php }
                                    }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                        <td>(<?= number_format($balance_new)?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($balance)?></td>
                                    <?php }
                                    }
                                    }$balance_sum += $balance; $balance = 0; $count = $key_1; $Show_Total = 1;
                                }else{?>
                                    <td></td>
                                <?php }?>
                            </tr>
                        <?php }
                    }
                }
            }else{
                if (($account_level >= 2 || $account_level == 'Detail') && $level_1 != array()){
                    foreach ($level_1 as $key_2 => $level_2) {
                        if (is_numeric($key_2)){
                            if ($Links[$acc_key][$key_1][$key_2] == 'y'){
                                if (isset($IsZero[$acc_key][$key_1][$key_2])){
                                    if ($IsZero[$acc_key][$key_1][$key_2] == 'y' || $withOutZero == 0){?>
                                        <tr class="odd gradeX">
                                            <td><?= $level_2->AccountCode?></td>
                                            <td><?= $level_2->AccountName?></td>
                                            <?php if ($level_2->Category == '2' || $account_level == '2'){
                                                $t_id1 = searchForId($level_2->AccountCode,$pre_transactions);
                                                if ($t_id1 == array()){
                                                    $t_id2 = searchForId($level_2->AccountCode,$post_transactions);
                                                }else{
                                                    $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                    $balance = (($op_balance + $debit) - $credit);
                                                    $t_id2 = array();
                                                } if ($t_id2 != array()){
                                                    $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                    $balance = (($op_balance + $debit) - $credit);
                                                    if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1; ?>
                                                        <td><?= number_format($balance_new)?></td>
                                                    <?php }elseif($balance == '0.00'){?>
                                                        <td><?= number_format($balance)?></td>
                                                    <?php }else{ ?>
                                                        <td>(<?= number_format($balance)?>)</td>
                                                    <?php }
                                                    }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                        <td>(<?= number_format($balance_new)?>)</td>
                                                    <?php }else{?>
                                                        <td><?= number_format($balance)?></td>
                                                    <?php }
                                                    } }else{ if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                    <td><?= number_format($balance_new)?></td>
                                                <?php }elseif($balance == '0.00'){?>
                                                    <td><?= number_format($balance)?></td>
                                                <?php }else{ ?>
                                                    <td>(<?= number_format($balance)?>)</td>
                                                <?php }
                                                }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                    <td>(<?= number_format($balance_new)?>)</td>
                                                <?php }else{?>
                                                    <td><?= number_format($balance)?></td>
                                                <?php }
                                                }
                                                }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                            }else{?>
                                                <td></td>
                                            <?php }?>
                                        </tr>
                                    <?php }
                                }
                            }
                        }if (($account_level >= 3 || $account_level == 'Detail') && isset($level_1['Child'.$key_2])){
                            foreach ($level_1['Child'.$key_2] as $key_3 => $level_3) {
                                if (is_numeric($key_3)){
                                    if ($Links[$acc_key][$key_1]['Child'.$key_2][$key_3] == 'y'){
                                        if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2][$key_3])){
                                            if ($IsZero[$acc_key][$key_1]['Child'.$key_2][$key_3] == 'y' || $withOutZero == 0){?>
                                                <tr class="odd gradeX">
                                                    <td><?= $level_3->AccountCode?></td>
                                                    <td><?= $level_3->AccountName?></td>
                                                    <?php if ($level_3->Category == '2' || $account_level == '3'){
                                                        $t_id1 = searchForId($level_3->AccountCode,$pre_transactions);
                                                        if ($t_id1 == array()){
                                                            $t_id2 = searchForId($level_3->AccountCode,$post_transactions);
                                                        }else{
                                                            $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                            $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                            $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                            $balance = (($op_balance + $debit) - $credit);
                                                            $t_id2 = array();
                                                        }if ($t_id2 != array()){
                                                            $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                            $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                            $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                            $balance = (($op_balance + $debit) - $credit);
                                                            if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                <td><?= number_format($balance_new)?></td>
                                                            <?php }elseif($balance == '0.00'){?>
                                                                <td><?= number_format($balance)?></td>
                                                            <?php }else{ ?>
                                                                <td>(<?= number_format($balance)?>)</td>
                                                            <?php }
                                                            }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                <td>(<?= number_format($balance_new)?>)</td>
                                                            <?php }else{?>
                                                                <td><?= number_format($balance)?></td>
                                                            <?php }
                                                            } }else{if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1;?>
                                                            <td><?= number_format($balance_new)?></td>
                                                        <?php }elseif($balance == '0.00'){?>
                                                            <td><?= number_format($balance)?></td>
                                                        <?php }else{ ?>
                                                            <td>(<?= number_format($balance)?>)</td>
                                                        <?php }
                                                        }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                            <td>(<?= number_format($balance_new)?>)</td>
                                                        <?php }else{?>
                                                            <td><?= number_format($balance)?></td>
                                                        <?php }
                                                        }
                                                        }$balance_sum += $balance; $balance = 0;$count++; $Show_Total = 1;
                                                    }else{?>
                                                        <td></td>
                                                    <?php }?>
                                                </tr>
                                            <?php }
                                        }
                                    }
                                }if (($account_level >= 4 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3])){
                                    foreach ($level_1['Child'.$key_2]['Child'.$key_3] as $key_4 => $level_4) {
                                        if (is_numeric($key_4)){
                                            if ($Links[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3][$key_4] == 'y'){
                                                if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3][$key_4])){
                                                    if ($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3][$key_4] == 'y' || $withOutZero == 0){?>
                                                        <tr class="odd gradeX">
                                                            <td><?= $level_4->AccountCode?></td>
                                                            <td><?= $level_4->AccountName?></td>
                                                            <?php if ($level_4->Category == '2'){
                                                                $t_id1 = searchForId($level_4->AccountCode,$pre_transactions);
                                                                if ($t_id1 == array()){
                                                                    $t_id2 = searchForId($level_4->AccountCode,$post_transactions);
                                                                }else{
                                                                    $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                    $balance = (($op_balance + $debit) - $credit);
                                                                    $t_id2 = array();
                                                                } if ($t_id2 != array()){
                                                                    $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                    $balance = (($op_balance + $debit) - $credit);
                                                                    if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                        <td><?= number_format($balance_new)?></td>
                                                                    <?php }elseif($balance == '0.00'){?>
                                                                        <td><?= number_format($balance)?></td>
                                                                    <?php }else{ ?>
                                                                        <td>(<?= number_format($balance)?>)</td>
                                                                    <?php }
                                                                    }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                        <td>(<?= number_format($balance_new)?>)</td>
                                                                    <?php }else{?>
                                                                        <td><?= number_format($balance)?></td>
                                                                    <?php }
                                                                    } }else{
                                                                    if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                        <td><?= number_format($balance_new)?></td>
                                                                    <?php }elseif($balance == '0.00'){?>
                                                                        <td><?= number_format($balance)?></td>
                                                                    <?php }else{ ?>
                                                                        <td>(<?= number_format($balance)?>)</td>
                                                                    <?php }
                                                                    }else{
                                                                        if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                            <td>(<?= number_format($balance_new)?>)</td>
                                                                        <?php }else{?>
                                                                            <td><?= number_format($balance)?></td>
                                                                        <?php }
                                                                    }
                                                                }$balance_sum += $balance; $balance = 0;$count++; $Show_Total = 1;
                                                            }else{?>
                                                                <td></td>
                                                            <?php }?>
                                                        </tr>
                                                    <?php }
                                                }
                                            }
                                        }if (($account_level >= 5 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4])){
                                            foreach ($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4] as $key_5 => $level_5) {
                                                if (is_numeric($key_5)){
                                                    if ($Links[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4][$key_5] == 'y'){
                                                        if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4][$key_5])){
                                                            if ($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4][$key_5] == 'y' || $withOutZero == 0){?>
                                                                <tr class="odd gradeX">
                                                                    <td><?= $level_5->AccountCode?></td>
                                                                    <td><?= $level_5->AccountName?></td>
                                                                    <?php if ($level_5->Category == '2'){
                                                                        $t_id1 = searchForId($level_5->AccountCode,$pre_transactions);
                                                                        if ($t_id1 == array()){
                                                                            $t_id2 = searchForId($level_5->AccountCode,$post_transactions);
                                                                        }else{
                                                                            $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                            $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                            $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                            $balance = (($op_balance + $debit) - $credit);
                                                                            $t_id2 = array();
                                                                        }if ($t_id2 != array()){
                                                                            $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                            $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                            $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                            $balance = (($op_balance + $debit) - $credit);
                                                                            if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                                <td><?= number_format($balance_new)?></td>
                                                                            <?php }elseif($balance == '0.00'){?>
                                                                                <td><?= number_format($balance)?></td>
                                                                            <?php }else{ ?>
                                                                                <td>(<?= number_format($balance)?>)</td>
                                                                            <?php }
                                                                            }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                                <td>(<?= number_format($balance_new)?>)</td>
                                                                            <?php }else{?>
                                                                                <td><?= number_format($balance)?></td>
                                                                            <?php }
                                                                            } }else{if ($acc_key == 0){if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                            <td><?= number_format($balance_new)?></td>
                                                                        <?php }elseif($balance == '0.00'){?>
                                                                            <td><?= number_format($balance)?></td>
                                                                        <?php }else{ ?>
                                                                            <td>(<?= number_format($balance)?>)</td>
                                                                        <?php }
                                                                        }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                            <td>(<?= number_format($balance_new)?>)</td>
                                                                        <?php }else{?>
                                                                            <td><?= number_format($balance)?></td>
                                                                        <?php }
                                                                        }
                                                                        }$balance_sum += $balance; $balance = 0; $count++;
                                                                    }else{?>
                                                                        <td></td>
                                                                    <?php }?>
                                                                </tr>
                                                            <?php }
                                                        }
                                                    }
                                                }if (($account_level >= 6 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5])){
                                                    foreach ($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5] as $key_6 => $level_6) {
                                                        if (is_numeric($key_6)){
                                                            if ($Links[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5][$key_6] == 'y'){
                                                                if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5][$key_6])){
                                                                    if ($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5][$key_6] == 'y' || $withOutZero == 0){?>
                                                                        <tr class="odd gradeX">
                                                                            <td><?= $level_6->AccountCode?></td>
                                                                            <td><?= $level_6->AccountName?></td>
                                                                            <?php if ($level_6->Category == '2'){
                                                                                $t_id1 = searchForId($level_6->AccountCode,$pre_transactions);
                                                                                if ($t_id1 == array()){
                                                                                    $t_id2 = searchForId($level_6->AccountCode,$post_transactions);
                                                                                }else{
                                                                                    $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                    $balance = (($op_balance + $debit) - $credit);
                                                                                    $t_id2 = array();
                                                                                } if ($t_id2 != array()){
                                                                                    $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                    $balance = (($op_balance + $debit) - $credit);
                                                                                    if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                        <td><?= number_format($balance_new)?></td>
                                                                                    <?php }elseif($balance == '0.00'){?>
                                                                                        <td><?= number_format($balance)?></td>
                                                                                    <?php }else{ ?>
                                                                                        <td>(<?= number_format($balance)?>)</td>
                                                                                    <?php }
                                                                                    }else{if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                        <td>(<?= number_format($balance_new)?>)</td>
                                                                                    <?php }else{?>
                                                                                        <td><?= number_format($balance)?></td>
                                                                                    <?php }
                                                                                    } }else{ if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1;?>
                                                                                    <td><?= number_format($balance_new)?></td>
                                                                                <?php }elseif($balance == '0.00'){?>
                                                                                    <td><?= number_format($balance)?></td>
                                                                                <?php }else{ ?>
                                                                                    <td>(<?= number_format($balance)?>)</td>
                                                                                <?php }
                                                                                }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                    <td>(<?= number_format($balance_new)?>)</td>
                                                                                <?php }else{?>
                                                                                    <td><?= number_format($balance)?></td>
                                                                                <?php }
                                                                                }
                                                                                }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                            }else{?>
                                                                                <td></td>
                                                                            <?php }?>
                                                                        </tr>
                                                                    <?php }
                                                                }
                                                            }
                                                        }if (($account_level >= 7 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6])){
                                                            foreach ($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6] as $key_7 => $level_7) {
                                                                if (is_numeric($level_7)){
                                                                    if ($Links[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6][$key_7] == 'y'){
                                                                        if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6][$key_7])){
                                                                            if ($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6][$key_7] == 'y' || $withOutZero == 0){?>
                                                                                <tr class="odd gradeX">
                                                                                    <td><?= $level_7->AccountCode?></td>
                                                                                    <td><?= $level_7->AccountName?></td>
                                                                                    <?php if ($level_7->Category == '2'){
                                                                                        $t_id1 = searchForId($level_7->AccountCode,$pre_transactions);
                                                                                        if ($t_id1 == array()){
                                                                                            $t_id2 = searchForId($level_7->AccountCode,$post_transactions);
                                                                                        }else{
                                                                                            $op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                            $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                            $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                            $balance = (($op_balance + $debit) - $credit);
                                                                                            $t_id2 = array();
                                                                                        } if ($t_id2 != array()){
                                                                                            $op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                            $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                            $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                            $balance = (($op_balance + $debit) - $credit);
                                                                                            if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                <td><?= number_format($balance_new)?></td>
                                                                                            <?php }elseif($balance == '0.00'){?>
                                                                                                <td><?= number_format($balance)?></td>
                                                                                            <?php }else{ ?>
                                                                                                <td>(<?= number_format($balance)?>)</td>
                                                                                            <?php }
                                                                                            }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                <td>(<?= number_format($balance_new)?>)</td>
                                                                                            <?php }else{?>
                                                                                                <td><?= number_format($balance)?></td>
                                                                                            <?php }
                                                                                            } }else{
                                                                                            if ($acc_key == 0){ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                <td><?= number_format($balance_new)?></td>
                                                                                            <?php }elseif($balance == '0.00'){?>
                                                                                                <td><?= number_format($balance)?></td>
                                                                                            <?php }else{ ?>
                                                                                                <td>(<?= number_format($balance)?>)</td>
                                                                                            <?php }
                                                                                            }else{if ($balance < 0){$balance_new = $balance * -1; ?>
                                                                                                <td>(<?= number_format($balance_new)?>)</td>
                                                                                            <?php }else{?>
                                                                                                <td><?= number_format($balance)?></td>
                                                                                            <?php }
                                                                                            }
                                                                                        }$balance_sum += $balance; $balance = 0;$count++; $Show_Total = 1;
                                                                                    }else{?>
                                                                                        <td></td>
                                                                                    <?php }?>
                                                                                </tr>
                                                                                <?php
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
    }?>
    </tbody>
    <?php if ($Show_Total == 1){
        if ($count == 0){?>
            <tfoot style="display: none">
        <?php }else{ ?>
            <tfoot>
        <?php }?>
        <tr>
            <th></th>
            <th><span> میزان:</span></th>
            <?php if($acc_key == 0){
                if ($balance_sum < 0){
                    $balance_new = $balance_sum * -1;?>
                    <th><?= number_format($balance_new)?></th>
                <?php }elseif($balance_sum == 0){ ?>
                    <th><?= number_format($balance_sum)?></th>
                <?php }else{ ?>
                    <th>(<?= number_format($balance_sum)?>)</th>
                <?php }?>
            <?php }else{
                if ($balance_sum < 0){
                    $balance_new = $balance_sum * -1;?>
                    <th><span >CR</span><?= number_format($balance_new)?></th>
                <?php }else{ ?>
                    <th ><?= number_format($balance_sum)?></th>
                <?php } }?>
        </tr>
        </tfoot>
    <?php } $total_balance += $balance_sum; $balance_sum=0;?>
</table>
<div></div>
</body>
</html>