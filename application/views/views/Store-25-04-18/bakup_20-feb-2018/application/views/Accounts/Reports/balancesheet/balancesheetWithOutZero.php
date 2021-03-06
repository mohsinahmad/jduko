<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <style type="text/css">
        @font-face {
            font-family: "Noto Nastaliq Urdu";
            src: url(<?php echo base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
        }
    </style>
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>
    <link href="<?php echo base_url()."assets/"; ?>css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?php echo base_url()."assets/"; ?>css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .content{
            border:0px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            max-width: 800px;
            margin:auto;
            padding:20px;
        }
        .two{
            direction: rtl;
            width: 97%;
            margin-right: 2%;
            padding-top: 2%;
        }
        table{
            width:100%;
            line-height:inherit;
            text-align:center;
        }
        table td{
            padding:5px;
        }
        table tr td:nth-child(2){
            text-align:right;
        }
        table tr.top table td{
            padding-bottom:20px;
        }
        table tr.top table td.title{
            font-size:45px;
            line-height:45px;
            color:#333;
        }
        table tr.information table td{
            padding-bottom:40px;
        }
        table tr.heading td{
            background:#eee;
            border-bottom:1px solid #ddd;
            font-weight:bold;
        }
        table tr.details td{
            padding-bottom:20px;
        }
        table tr.item td{
            border-bottom:1px solid #eee;
        }
        table tr.item.last td{
            border-bottom:none;
        }
        table tr.total td:nth-child(2){
            border-top:2px solid #eee;
            font-weight:bold;
        }

        @media print {
            html, body{
                width:105%;
                height:auto;
                margin-right: -3%;
                padding:0;
                margin-top: 1%;
                margin-bottom: 2%;
            }
            .footer {
                margin: 0px 650px -15px 0px;
                position: fixed;
                bottom: 0;
                display: inline;
            }
            table tbody tr td:before,
            table tbody tr td:after {
                content : "" ;
                height : 8px ;
                display : block ;
            }
            #hide{
                display: none !important;
            }
        }
    </style>
</head>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,19,$Access_level);
if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[5];?>">
<?php }?>
<body>
<div class="content">
    <div class="row" id="content">
        <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <div id="hide">
                <button onclick="myFunction()">Print</button>
            </div> <?php }?>
        <div style="text-align: center;">
            <img src="<?php echo base_url()?>assets/images/logo.jpg" align="middle" style="width:22%; max-width:330px;">
            <?php if ($B_Sheet_Of == 'p'){ ?>
                <h2 style="font-size: 22px;margin-top: 0px;text-decoration: underline; ">بیلنس شیٹ<span style="font-size: 13px">(مستقل)</span></h2>
            <?php }else{ ?>
                <h2 style="font-size: 22px;margin-top: 0px;text-decoration: underline; ">بیلنس شیٹ<span style="font-size: 13px">(عارضی + مستقل)</span></h2>
            <?php }?>
            <h4 style="font-size: 16px"><span><?= $from[0]->Qm_date?></span><span>  بمطابق  </span><span><?= $from[0]->Sh_date?></span></h4>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two" style="padding-top: 0px">
                <div class="col-lg-12">
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
                    $balance = 0; $balance_new = 0; $count = 0;
                    foreach ($AccountTree as $acc_key => $Accounts){ ?>
                        <div class="panel-body">
                            <div class="row">
                                <?php if ($acc_key == 0){ ?>
                                    <div class="col-md-6">
                                        <h4 style="text-decoration: underline; "><?= $level[0]->LevelName?></h4>
                                    </div>
                                    <div class="col-md-6">
                                        <h4 style="float:left;direction: ltr;margin-right: -5%;"><span><?= $account_level?> - لیول</span></h4>
                                    </div>
                                <?php }?>
                            </div>
                            <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                                <table class="table-bordered">
                                    <?php if ($acc_key == 0){ ?>
                                    <thead>
                                        <tr style="line-height: 243%;">
                                            <th style="width:15%;text-align: center">کوڈ</th>
                                            <th style="width: 35%;text-align: center">اکاونٹ نام</th>
                                            <th style="width:15%;text-align: center">بیلنس</th>
                                        </tr>
                                    </thead>
                                    <?php }?>
                                    <tbody>
                                    <?php foreach ($Accounts as $key_1 => $level_1){ $Show_Total = 0;
                                        if (is_numeric($key_1)){
                                            if ($Links[$acc_key][$key_1] == 'y'){
                                                if (isset($IsZero[$acc_key][$key_1])){
                                                    if ($IsZero[$acc_key][$key_1] == 'y' || $withOutZero == 0){?>
                                                        <tr class="odd gradeX">
                                                            <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_1->AccountCode?></td>
                                                            <td style="width: 35%; font-weight: bold;"><?= $level_1->AccountName?></td>
                                                            <?php if ($level_1->Category == '2' || $account_level == 'Detail' || $account_level == '1'){
                                                                $t_id1 = searchForId($level_1->AccountCode,$pre_transactions);
                                                                if ($t_id1 == array()){
                                                                    $t_id2 = searchForId($level_1->AccountCode,$post_transactions);
                                                                }else{
                                                                    $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                    $balance = (($Op_balance + $debit) - $credit);
                                                                    $t_id2 = array();
                                                                } if ($t_id2 != array()){
                                                                    $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                    $balance = (($Op_balance + $debit) - $credit);
                                                                    if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                        <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                    <?php } else{?>
                                                                        <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                    <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                        <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                    <?php }else{?>
                                                                        <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                    <?php }
                                                                }$balance_sum += $balance; $balance = 0; $count = $key_1; $Show_Total = 1;
                                                            }else{?>
                                                                <td style="width: 15%;"></td>
                                                            <?php }?>
                                                        </tr>
                                                    <?php }
                                                }
                                            }
                                        } else{
                                            if (($account_level >= 2 || $account_level == 'Detail') && $level_1 != array()){
                                                foreach ($level_1 as $key_2 => $level_2) {
                                                    if (is_numeric($key_2)){
                                                        if ($Links[$acc_key][$key_1][$key_2] == 'y'){
                                                            if (isset($IsZero[$acc_key][$key_1][$key_2])){
                                                                if ($IsZero[$acc_key][$key_1][$key_2] == 'y' || $withOutZero == 0){?>
                                                                    <tr class="odd gradeX">
                                                                        <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_2->AccountCode?></td>
                                                                        <td style="width: 15%; font-weight: bold;"><?= $level_2->AccountName?></td>
                                                                        <?php if ($level_2->Category == '2' || $account_level == '2'){
                                                                            $t_id1 = searchForId($level_2->AccountCode,$pre_transactions);
                                                                            if ($t_id1 == array()){
                                                                                $t_id2 = searchForId($level_2->AccountCode,$post_transactions);
                                                                            }else{
                                                                                $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                $balance = (($Op_balance + $debit) - $credit);
                                                                                $t_id2 = array();
                                                                            } if ($t_id2 != array()){
                                                                                $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                $balance = (($Op_balance + $debit) - $credit);
                                                                                if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                    <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                <?php }else{?>
                                                                                    <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                    <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                <?php }else{?>
                                                                                    <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                <?php }
                                                                            }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                        }else{?>
                                                                            <td style="width: 15%;"></td>
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
                                                                                <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_3->AccountCode?></td>
                                                                                <td style="width: 15%; font-weight: bold;"><?= $level_3->AccountName?></td>
                                                                                <?php if ($level_3->Category == '2' || $account_level == '3'){
                                                                                    $t_id1 = searchForId($level_3->AccountCode,$pre_transactions);
                                                                                    if ($t_id1 == array()){
                                                                                        $t_id2 = searchForId($level_3->AccountCode,$post_transactions);
                                                                                    }else{
                                                                                        $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                        $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                        $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                        $balance = (($Op_balance + $debit) - $credit);
                                                                                        $t_id2 = array();
                                                                                    } if ($t_id2 != array()){
                                                                                        $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                        $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                        $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                        $balance = (($Op_balance + $debit) - $credit);
                                                                                        if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                            <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                        <?php }else{?>
                                                                                            <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                        <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                            <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                        <?php }else{?>
                                                                                            <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                        <?php }
                                                                                    }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                                }else{?>
                                                                                    <td style="width: 15%;"></td>
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
                                                                                        <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_4->AccountCode?></td>
                                                                                        <td style="width: 15%; font-weight: bold;"><?= $level_4->AccountName?></td>
                                                                                        <?php if ($level_4->Category == '2' || $account_level == '4'){
                                                                                            $t_id1 = searchForId($level_4->AccountCode,$pre_transactions);
                                                                                            if ($t_id1 == array()){
                                                                                                $t_id2 = searchForId($level_4->AccountCode,$post_transactions);
                                                                                            }else{
                                                                                                $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                $balance = (($Op_balance + $debit) - $credit);
                                                                                                $t_id2 = array();
                                                                                            } if ($t_id2 != array()){
                                                                                                $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                $balance = (($Op_balance + $debit) - $credit);
                                                                                                if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                    <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                <?php }else{?>
                                                                                                    <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                    <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                <?php }else{?>
                                                                                                    <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                <?php }
                                                                                            }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                                        }else{?>
                                                                                            <td style="width: 15%;"></td>
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
                                                                                                <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_5->AccountCode?></td>
                                                                                                <td style="width: 15%; font-weight: bold;"><?= $level_5->AccountName?></td>
                                                                                                <?php if ($level_5->Category == '2' || $account_level == '5'){
                                                                                                    $t_id1 = searchForId($level_5->AccountCode,$pre_transactions);
                                                                                                    if ($t_id1 == array()){
                                                                                                        $t_id2 = searchForId($level_5->AccountCode,$post_transactions);
                                                                                                    }else{
                                                                                                        $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                        $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                        $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                        $balance = (($Op_balance + $debit) - $credit);
                                                                                                        $t_id2 = array();
                                                                                                    } if ($t_id2 != array()){
                                                                                                        $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                        $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                        $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                        $balance = (($Op_balance + $debit) - $credit);
                                                                                                        if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                            <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                        <?php }else{?>
                                                                                                            <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                        <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                            <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                        <?php }else{?>
                                                                                                            <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                        <?php }
                                                                                                    }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                                                }else{?>
                                                                                                    <td style="width: 15%;"></td>
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
                                                                                                        <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_6->AccountCode?></td>
                                                                                                        <td style="width: 15%; font-weight: bold;"><?= $level_6->AccountName?></td>
                                                                                                        <?php if ($level_6->Category == '2' || $account_level == '6'){
                                                                                                            $t_id1 = searchForId($level_6->AccountCode,$pre_transactions);
                                                                                                            if ($t_id1 == array()){
                                                                                                                $t_id2 = searchForId($level_6->AccountCode,$post_transactions);
                                                                                                            }else{
                                                                                                                $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                                $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                                $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                                $balance = (($Op_balance + $debit) - $credit);
                                                                                                                $t_id2 = array();
                                                                                                            } if ($t_id2 != array()){
                                                                                                                $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                                $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                                $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                                $balance = (($Op_balance + $debit) - $credit);
                                                                                                                if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                                    <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                                <?php }else{?>
                                                                                                                    <td style="width: 15%;"><?= number_format($balance)?>)</td>
                                                                                                                <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                                    <td style="width: 15%;">(<?= number_format($balance_new)?></td>
                                                                                                                <?php }else{?>
                                                                                                                    <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                                <?php }
                                                                                                            }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                                                        }else{?>
                                                                                                            <td style="width: 15%;"></td>
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
                                                                                                            <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_7->AccountCode?></td>
                                                                                                            <td style="width: 15%; font-weight: bold;"><?= $level_7->AccountName?></td>
                                                                                                            <?php if ($level_7->Category == '2'  || $account_level == '7'){
                                                                                                                $t_id1 = searchForId($level_7->AccountCode,$pre_transactions);
                                                                                                                if ($t_id1 == array()){
                                                                                                                    $t_id2 = searchForId($level_7->AccountCode,$post_transactions);
                                                                                                                }else{
                                                                                                                    $Op_balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                                    $balance = (($Op_balance + $debit) - $credit);
                                                                                                                    $t_id2 = array();
                                                                                                                } if ($t_id2 != array()){
                                                                                                                    $Op_balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                                    $balance = (($Op_balance + $debit) - $credit);
                                                                                                                    if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                                        <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                                    <?php }else{?>
                                                                                                                        <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                                    <?php } }else{ if ($balance < 0){ $balance_new = $balance * -1; ?>
                                                                                                                        <td style="width: 15%;">(<?= number_format($balance_new)?>)</td>
                                                                                                                    <?php }else{?>
                                                                                                                        <td style="width: 15%;"><?= number_format($balance)?></td>
                                                                                                                    <?php }
                                                                                                                }$balance_sum += $balance; $balance = 0; $count++; $Show_Total = 1;
                                                                                                            }else{?>
                                                                                                                <td style="width: 15%;"></td>
                                                                                                            <?php }?>
                                                                                                            </tr>
                                                                                                        <?php }
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
                                        <tr style="line-height: 250%;">
                                            <th></th>
                                            <th><span style="float: right;"> میزان:</span></th>
                                            <?php if ($balance_sum < 0){
                                                $balance_new = $balance_sum * -1;?>
                                                <th style="text-align: center">(<?= number_format($balance_new)?>)</th>
                                            <?php }else{ ?>
                                                <th style="text-align: center"><?= number_format($balance_sum)?></th>
                                            <?php }?>
                                        </tr>
                                        </tfoot>
                                    <?php }if ($acc_key != 0){
                                        $total_balance += $balance_sum;
                                    } $balance_sum = 0; ?>
                                </table>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
            <?php $final_balance = 0; $final_balance_new = 0; $assists = 0; $income_balance = 0;

            $final_balance = $total_balance;

            $Rev_Obalance = array(); $exp_Obalance = array();$Rev_balance=0;$exp_balance=0;
            $Rev_Obalance = searchForId('4',$profit_loss);
            $exp_Obalance = searchForId('5',$profit_loss);

            $Rev_balance = (($profit_loss[$Rev_Obalance[0]][$Rev_Obalance[1]][$Rev_Obalance[2]]->Balance + $profit_loss[$Rev_Obalance[0]][$Rev_Obalance[1]][$Rev_Obalance[2]]->Debit) - $profit_loss[$Rev_Obalance[0]][$Rev_Obalance[1]][$Rev_Obalance[2]]->Credit);
            $exp_balance = (($profit_loss[$exp_Obalance[0]][$exp_Obalance[1]][$exp_Obalance[2]]->Balance + $profit_loss[$exp_Obalance[0]][$exp_Obalance[1]][$exp_Obalance[2]]->Debit) - $profit_loss[$exp_Obalance[0]][$exp_Obalance[1]][$exp_Obalance[2]]->Credit);

            $income_balance = $Rev_balance + $exp_balance ;

            $assists = $final_balance + $income_balance;?>
            <div class="panel-body" style="direction: rtl;">
                <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
                    <table class="table-bordered" id="dataTables-example">
                        <tbody>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th class="center" style="text-align: center">خالص آمدنی (نقصان):</th>
                                <?php if ($income_balance < 0){
                                    $income_balance_new = ($income_balance * -1);?>
                                    <td style="width:24%;text-align: center">(<?= number_format($income_balance_new)?>)</td>
                                <?php }else{?>
                                    <td style="width:24%;text-align: center"><?= number_format($income_balance)?></td>
                                <?php } $total_balance = 0;?>
                            </tr>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th class="center" style="text-align: center">حتمی میزان:</th>
                                <?php if ($assists < 0){
                                    $assists_new = ($assists * -1);?>
                                    <td style="width:24%;text-align: center">(<?= number_format($assists_new)?>)</td>
                                <?php }else{?>
                                    <td style="width:24%;text-align: center"><?= number_format($assists)?></td>
                                <?php } $assists = 0; ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class ="footer" id="pagebreak">
    <div style="direction: ltr;">
        <hr> <?php date_default_timezone_set('Asia/Karachi');?>
        <span style="float: left;"><?php echo date('l d-m-Y h:i:s');?></span>
        <hr>
        <input type="hidden" id="print" value="<?= $print;?>">
    </div>
</footer>
<div class="PrintMessage" style="margin-right: 200px;margin-top: 500px;font-size: 2em">آپ اس دستاویز کو پرنٹ کرنے کے مجاز نہیں ہیں۔</div>
</body>
</html>
<script src="<?php echo base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $(".PrintMessage").hide();
        if ($('#print').val() == 1){
            window.print();
        }
    });
    function myFunction() {
        window.print();
    }

    var AccessRights = $('#AccessRights').val();
    if (!(AccessRights == 1 || <?=$_SESSION['user'][0]->id?> == 1)){
        if ('matchMedia' in window) {
            // Chrome, Firefox, and IE 10 support mediaMatch listeners
            window.matchMedia('print').addListener(function(media) {
                if (media.matches) {
                    beforePrint();
                } else {
                    // Fires immediately, so wait for the first mouse movement
                    $(document).one('mouseover', afterPrint);
                }
            });
        } else {
            // IE and Firefox fire before/after events
            $(window).on('beforeprint', beforePrint);
            $(window).on('afterprint', afterPrint);
        }

        function beforePrint() {
            $(".content").hide();
            $(".PrintMessage").show();
        }

        function afterPrint() {
            $(".PrintMessage").hide();
            $(".content").show();
        }
    }else {
        $(".PrintMessage").hide();
        $(".content").show();
    }
</script>