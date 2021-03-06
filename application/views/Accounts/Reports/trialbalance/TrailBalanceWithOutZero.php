<!DOCTYPE html>
<html moznomarginboxes mozdisallowselectionprint>
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
            .ta{
                margin-left: 3%;

            }
            .bamutabiq{
                margin-left: 2%;
            }
            .ta1{
                margin-left: 5%;

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
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,15,$Access_level);
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
            <img src="<?php echo base_url()?>assets/images/logo.jpg" align="middle" style="width:20%; max-width:330px;">
            <?php if ($Trail_Balance_Of == 'p'){?>
                <h2 style="margin-top: 0px;font-size: 22px;">ٹرایل بیلنس<span style="font-size: 13px">(مستقل)</span></h2>
            <?php }else{?>
                <h2 style="margin-top: 0px;font-size: 22px;">ٹرایل بیلنس<span style="font-size: 13px">(عارضی + مستقل)</span></h2>
            <?php } $sey=' سے '; $tk = ' تک ';?>
            <h4 style="font-size: 16px;"><p style="margin-right: -15%"><?php echo $to[0]->Sh_date?></p><p style="margin-right: 147px;margin-top: -29px; margin-bottom: -2%"><?php echo $to[0]->Qm_date;?></p></h4>

            <br><span class ="ta" style="text-decoration: underline; margin-right: 3%;">تا</span>
            <span class="bamutabiq" style="text-decoration: underline; margin-right: 4%;">بمطابق</span>
            <span class="ta1" style="text-decoration: underline; margin-right: 5%;">تا</span><br>

            <h4 style="font-size: 16px;"><p style="margin-right: -15%"><?php echo $from[0]->Sh_date?></p><p style="margin-right: 147px;margin-top: -29px;"><?php echo $from[0]->Qm_date;?></p></h4>
        </div>
    </div>
    <div class="level" style="margin-top: -40px;">
        <div>
            <div class="row two">
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
                    $showTotal = 0;
                    $balance_sum = 0;$debit_sum = 0;$credit_sum = 0;$c_balance_sum = 0;
                    $total_balance = 0;$total_c_balance_sum = 0;$total_balance = 0;
                    $debit_sum_total = 0;$credit_sum_total = 0;$op_balance_sum_total = 0;
                    $cl_balance_sum = 0;$cl_balance_sum_total = 0;$count = 0;
                    $t_id1 = array(); $t_id2 = array();
                    $balance = 0; $debit = 0; $credit = 0; $balance_new = 0;
                    $new_cl_balance_sum_total = 0 ; $new_op_balance_sum_total = 0;
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
                                    <?php if ($acc_key == 0){?>
                                    <thead>
                                    <tr style="line-height: 243%;">
                                        <th style="text-align: center">کوڈ</th>
                                        <th style="text-align: center">اکاونٹ نام</th>
                                        <th style="text-align: center">ابتدائی بیلنس</th>
                                        <th style="text-align: center">ڈیبٹ</th>
                                        <th style="text-align: center">کریڈٹ</th>
                                        <th style="text-align: center">اختتامی بیلنس</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php } foreach ($Accounts as $key_1 => $level_1){ $showTotal = 0;
                                        if (is_numeric($key_1)) {
                                            if ($Links[$acc_key][$key_1] == 'y'){
                                                if (isset($IsZero[$acc_key][$key_1])){ //  || $withOutZero == 0
                                                    if ($IsZero[$acc_key][$key_1] == 'y' || $withOutZero == 0) {?>
                                                        <tr class="odd gradeX">
                                                        <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_1->AccountCode ?></td>
                                                        <td style="width: 15%; font-weight: bold;"><?= $level_1->AccountName ?></td>
                                                        <?php if (($level_1->Category == 2 && $account_level == 'Detail') || $account_level == 1) {
                                                            $t_id1 = searchForId($level_1->AccountCode,$pre_transactions);
                                                            if ($t_id1 == array()){
                                                                $t_id2 = searchForId($level_1->AccountCode,$post_transactions);
                                                            }else{
                                                                $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                $t_id2 = array();
                                                            }
                                                            if ($t_id2 != array()){
                                                                $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                            }
                                                            if ($balance < 0){
                                                                $balance_new = $balance * -1;?>
                                                                <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                            <?php }else{?>
                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                            <?php }?>
                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                            <?php $cl_balance = (($balance + $debit) - $credit);
                                                            if ($cl_balance < 0){
                                                                $cl_balance_new = ($cl_balance * -1);?>
                                                                <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                            <?php }else{?>
                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                            <?php } $balance_sum += $balance; $balance=0; $showTotal = 1;
                                                            $debit_sum += $debit; $debit = 0;
                                                            $credit_sum += $credit; $credit = 0;
                                                            $cl_balance_sum += $cl_balance; $cl_balance = 0;
                                                            $count = $key_1; ?>
                                                            </tr>
                                                        <?php }else{ ?>
                                                            <td style="width: 15%; font-weight: bold;"></td>
                                                            <td style="width: 15%; font-weight: bold;"></td>
                                                            <td style="width: 15%; font-weight: bold;"></td>
                                                            <td style="width: 15%; font-weight: bold;"></td>
                                                            </tr>
                                                        <?php }
                                                    }
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
                                                                    <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_2->AccountCode ?></td>
                                                                    <td style="width: 15%; font-weight: bold;"><?= $level_2->AccountName ?></td>
                                                                    <?php if (($level_2->Category == 2) || $account_level >= 2) {
                                                                        $t_id1 = searchForId($level_2->AccountCode,$pre_transactions);
                                                                        if ($t_id1 == array()){
                                                                            $t_id2 = searchForId($level_2->AccountCode,$post_transactions);
                                                                        }else{
                                                                            $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                            $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                            $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                            $t_id2 = array();
                                                                        }
                                                                        if ($t_id2 != array()){
                                                                            $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                            $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                            $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                        }
                                                                        if ($balance < 0){
                                                                            $balance_new = $balance * -1;?>
                                                                            <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                                        <?php }else{?>
                                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                                        <?php }?>
                                                                        <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                                        <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                                        <?php $cl_balance = (($balance + $debit) - $credit);
                                                                        if ($cl_balance < 0){
                                                                            $cl_balance_new = $cl_balance * -1 ;?>
                                                                            <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                                        <?php }else{?>
                                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                                        <?php } $balance_sum += $balance; $balance = 0; $showTotal = 1;
                                                                        $debit_sum += $debit; $debit = 0;
                                                                        $credit_sum += $credit; $credit = 0;
                                                                        $cl_balance_sum += $cl_balance; $cl_balance = 0;
                                                                        $count++; ?>
                                                                        </tr>
                                                                    <?php } else{ ?>
                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                        </tr>
                                                                    <?php }
                                                                }
                                                            }
                                                        }
                                                    }if (($account_level >= 3 || $account_level == 'Detail') && isset($level_1['Child'.$key_2])){
                                                        foreach ($level_1['Child'.$key_2] as $key_3 => $level_3) {
                                                            if (is_numeric($key_3)){
                                                                if ($Links[$acc_key][$key_1]['Child'.$key_2][$key_3] == 'y'){
                                                                    if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2][$key_3])){
                                                                        if ($IsZero[$acc_key][$key_1]['Child'.$key_2][$key_3] == 'y' || $withOutZero == 0){?>
                                                                            <tr class="odd gradeX">
                                                                            <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_3->AccountCode ?></td>
                                                                            <td style="width: 15%; font-weight: bold;"><?= $level_3->AccountName ?></td>
                                                                            <?php if (($level_3->Category == 2) || $account_level >= 3) {
                                                                                $t_id1 = searchForId($level_3->AccountCode,$pre_transactions);
                                                                                if ($t_id1 == array()){
                                                                                    $t_id2 = searchForId($level_3->AccountCode,$post_transactions);
                                                                                }else{
                                                                                    $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                    $t_id2 = array();
                                                                                }
                                                                                if ($t_id2 != array()){
                                                                                    $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                }
                                                                                if ($balance < 0){
                                                                                    $balance_new = $balance * -1;?>
                                                                                    <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                                                <?php }else{?>
                                                                                    <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                                                <?php }?>
                                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                                                <?php $cl_balance = (($balance + $debit) - $credit);
                                                                                if ($cl_balance < 0){
                                                                                    $cl_balance_new = $cl_balance * -1 ;?>
                                                                                    <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                                                <?php }else{?>
                                                                                    <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                                                <?php } $balance_sum += $balance; $balance=0; $showTotal = 1;
                                                                                $debit_sum += $debit; $debit = 0;$credit_sum += $credit; $credit = 0;
                                                                                $cl_balance_sum += $cl_balance; $cl_balance = 0;$count++;?>
                                                                                </tr>
                                                                            <?php } else{ ?>
                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                </tr>
                                                                            <?php }
                                                                        }
                                                                    }
                                                                }
                                                            }if (($account_level >= 4 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3])){
                                                                foreach ($level_1['Child'.$key_2]['Child'.$key_3] as $key_4 => $level_4) {
                                                                    if (is_numeric($key_4)){
                                                                        if ($Links[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3][$key_4] == 'y'){
                                                                            if (isset($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3][$key_4])){
                                                                                if ($IsZero[$acc_key][$key_1]['Child'.$key_2]['Child'.$key_3][$key_4] == 'y' || $withOutZero == 0){?>
                                                                                    <tr class="odd gradeX">
                                                                                    <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_4->AccountCode ?></td>
                                                                                    <td style="width: 15%; font-weight: bold;"><?= $level_4->AccountName ?></td>
                                                                                    <?php if (($level_4->Category == 2) || $account_level >= 4) {
                                                                                        $t_id1 = searchForId($level_4->AccountCode,$pre_transactions);
                                                                                        if ($t_id1 == array()){
                                                                                            $t_id2 = searchForId($level_4->AccountCode,$post_transactions);
                                                                                        }else{
                                                                                            $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                            $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                            $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                            $t_id2 = array();
                                                                                        }
                                                                                        if ($t_id2 != array()){
                                                                                            $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                            $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                            $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                        }
                                                                                        if ($balance < 0){
                                                                                            $balance_new = $balance * -1;?>
                                                                                            <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                                                        <?php }else{?>
                                                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                                                        <?php }?>
                                                                                        <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                                                        <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                                                        <?php $cl_balance = (($balance + $debit) - $credit);
                                                                                        if ($cl_balance < 0){
                                                                                            $cl_balance_new = $cl_balance * -1 ;?>
                                                                                            <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                                                        <?php }else{?>
                                                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                                                        <?php } $balance_sum += $balance; $balance=0; $showTotal = 1;
                                                                                        $debit_sum += $debit; $debit = 0;
                                                                                        $credit_sum += $credit; $credit = 0;
                                                                                        $cl_balance_sum += $cl_balance; $cl_balance = 0;$count++; ?>
                                                                                        </tr>
                                                                                    <?php } else{ ?>
                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                        </tr>
                                                                                    <?php }
                                                                                }
                                                                            }
                                                                        }
                                                                    }if (($account_level >= 5 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4])) {
                                                                        foreach ($level_1['Child' . $key_2]['Child' . $key_3]['Child' . $key_4] as $key_5 => $level_5) {
                                                                            if (is_numeric($key_5)) {
                                                                                if ($Links[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4][$key_5] == 'y'){
                                                                                    if (isset($IsZero[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4][$key_5])){
                                                                                        if ($IsZero[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4][$key_5] == 'y' || $withOutZero == 0) {?>
                                                                                            <tr class="odd gradeX">
                                                                                            <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_5->AccountCode ?></td>
                                                                                            <td style="width: 15%; font-weight: bold;"><?= $level_5->AccountName ?></td>
                                                                                            <?php if (($level_5->Category == 2) || $account_level >= 5) {
                                                                                                $t_id1 = searchForId($level_5->AccountCode,$pre_transactions);
                                                                                                if ($t_id1 == array()){
                                                                                                    $t_id2 = searchForId($level_5->AccountCode,$post_transactions);
                                                                                                }else{
                                                                                                    $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                    $t_id2 = array();
                                                                                                }
                                                                                                if ($t_id2 != array()){
                                                                                                    $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                }
                                                                                                if ($balance < 0){
                                                                                                    $balance_new = $balance * -1;?>
                                                                                                    <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                                                                <?php }else{?>
                                                                                                    <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                                                                <?php }?>
                                                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                                                                <?php $cl_balance = (($balance + $debit) - $credit);
                                                                                                if ($cl_balance < 0){
                                                                                                    $cl_balance_new = $cl_balance * -1 ;?>
                                                                                                    <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                                                                <?php }else{?>
                                                                                                    <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                                                                <?php } $balance_sum += $balance; $balance=0; $showTotal = 1;
                                                                                                $debit_sum += $debit; $debit = 0;
                                                                                                $credit_sum += $credit; $credit = 0;
                                                                                                $cl_balance_sum += $cl_balance; $cl_balance = 0;$count++;?>
                                                                                                </tr>
                                                                                            <?php } else{ ?>
                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                </tr>
                                                                                            <?php }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }if (($account_level >= 6 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5])) {
                                                                                foreach ($level_1['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5] as $key_6 => $level_6) {
                                                                                    if (is_numeric($key_6)) {
                                                                                        if ($Links[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5][$key_6] == 'y'){
                                                                                            if (isset($IsZero[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5][$key_6])){
                                                                                                if ($IsZero[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5][$key_6] == 'y' || $withOutZero == 0) {?>
                                                                                                    <tr class="odd gradeX">
                                                                                                    <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_6->AccountCode ?></td>
                                                                                                    <td style="width: 15%; font-weight: bold;"><?= $level_6->AccountName ?></td>
                                                                                                    <?php if (($level_6->Category == 2) || $account_level >= 6) {
                                                                                                        $t_id1 = searchForId($level_6->AccountCode,$pre_transactions);
                                                                                                        if ($t_id1 == array()){
                                                                                                            $t_id2 = searchForId($level_6->AccountCode,$post_transactions);
                                                                                                        }else{
                                                                                                            $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                            $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                            $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                            $t_id2 = array();
                                                                                                        }
                                                                                                        if ($t_id2 != array()){
                                                                                                            $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                            $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                            $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                        }
                                                                                                        if ($balance < 0){
                                                                                                            $balance_new = $balance * -1;?>
                                                                                                            <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                                                                        <?php }else{?>
                                                                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                                                                        <?php }?>
                                                                                                        <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                                                                        <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                                                                        <?php $cl_balance = (($balance + $debit) - $credit);
                                                                                                        if ($cl_balance < 0){
                                                                                                            $cl_balance_new = $cl_balance * -1 ;?>
                                                                                                            <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                                                                        <?php }else{?>
                                                                                                            <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                                                                        <?php } $balance_sum += $balance; $balance=0; $showTotal = 1;
                                                                                                        $debit_sum += $debit; $debit = 0;
                                                                                                        $credit_sum += $credit; $credit = 0;
                                                                                                        $cl_balance_sum += $cl_balance; $cl_balance = 0;$count++;?>
                                                                                                        </tr>
                                                                                                    <?php } else{ ?>
                                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                                        <td style="width: 15%; font-weight: bold;"></td>
                                                                                                        </tr>
                                                                                                    <?php }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }if (($account_level >= 7 || $account_level == 'Detail') && isset($level_1['Child'.$key_2]['Child'.$key_3]['Child'.$key_4]['Child'.$key_5]['Child'.$key_6])) {
                                                                                        foreach ($level_1['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5]['Child' . $key_6] as $key_7 => $level_7) {
                                                                                            if (is_numeric($key_7)) {
                                                                                                if ($Links[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5]['Child' . $key_6][$key_7] == 'y'){
                                                                                                    if (isset($IsZero[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5]['Child' . $key_6][$key_7])){
                                                                                                        if ($IsZero[$acc_key][$key_1]['Child' . $key_2]['Child' . $key_3]['Child' . $key_4]['Child' . $key_5]['Child' . $key_6][$key_7] == 'y' || $withOutZero == 0) {?>
                                                                                                            <tr class="odd gradeX">
                                                                                                            <td style="width: 15%; font-weight: bold;text-align: left;"><?= $level_7->AccountCode ?></td>
                                                                                                            <td style="width: 15%; font-weight: bold;"><?= $level_7->AccountName ?></td>
                                                                                                            <?php if (($level_7->Category == 2) || $account_level >= 7) {
                                                                                                                $t_id1 = searchForId($level_7->AccountCode,$pre_transactions);
                                                                                                                if ($t_id1 == array()){
                                                                                                                    $t_id2 = searchForId($level_7->AccountCode,$post_transactions);
                                                                                                                }else{
                                                                                                                    $balance = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Balance;
                                                                                                                    $debit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Debit;
                                                                                                                    $credit = $pre_transactions[$t_id1[0]][$t_id1[1]][$t_id1[2]]->Credit;
                                                                                                                    $t_id2 = array();
                                                                                                                }
                                                                                                                if ($t_id2 != array()){
                                                                                                                    $balance = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Balance;
                                                                                                                    $debit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Debit;
                                                                                                                    $credit = $post_transactions[$t_id2[0]][$t_id2[1]][$t_id2[2]]->Credit;
                                                                                                                }
                                                                                                                if ($balance < 0){
                                                                                                                    $balance_new = $balance * -1;?>
                                                                                                                    <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($balance_new)?></td>
                                                                                                                <?php }else{?>
                                                                                                                    <td style="width: 15%; font-weight: bold;"><?= number_format($balance)?></td>
                                                                                                                <?php }?>
                                                                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($debit)?></td>
                                                                                                                <td style="width: 15%; font-weight: bold;"><?= number_format($credit)?></td>
                                                                                                                <?php $cl_balance = (($balance + $debit) - $credit);
                                                                                                                if ($cl_balance < 0){
                                                                                                                    $cl_balance_new = $cl_balance * -1 ;?>
                                                                                                                    <td style="width: 15%; font-weight: bold;"><span style="float: left">CR</span><?= number_format($cl_balance_new)?></td>
                                                                                                                <?php }else{?>
                                                                                                                    <td style="width: 15%; font-weight: bold;"><?= number_format($cl_balance)?></td>
                                                                                                                <?php } $balance_sum += $balance; $balance=0; $showTotal = 1;
                                                                                                                $debit_sum += $debit; $debit = 0;
                                                                                                                $credit_sum += $credit; $credit = 0;
                                                                                                                $cl_balance_sum += $cl_balance; $cl_balance = 0;$count++;?>
                                                                                                                </tr>
                                                                                                            <?php } else{ ?>
                                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                                <td style="width: 15%; font-weight: bold;"></td>
                                                                                                                <td style="width: 15%; font-weight: bold;"></td>
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
                                        }
                                    } ?>
                                    </tbody>
                                    <?php if ($showTotal == 1){
                                    if ($count == 0){?>
                                    <tfoot style="display: none">
                                    <?php }else{ ?>
                                    <tfoot>
                                    <?php }?>
                                    <tr style="line-height: 250%;">
                                        <th></th>
                                        <th><span style="float: right;"> میزان</span></th>
                                        <?php if ($balance_sum < 0){
                                            $balance_new = $balance_sum * -1;?>
                                            <th style="text-align: center"><span style="float:left;: left">CR</span><?= number_format($balance_new)?></th>
                                        <?php }else{ ?>
                                            <th style="text-align: center"><?= number_format($balance_sum)?></th>
                                        <?php }?>
                                        <th style="text-align: center"><?= number_format($debit_sum)?></th>
                                        <th style="text-align: center"><?= number_format($credit_sum)?></th> <?php //$c_balance_sum = (($balance_sum + $debit_sum) - $credit_sum);?>
                                        <?php if ($cl_balance_sum < 0){
                                            $c_balance_sum_new = $cl_balance_sum * -1;?>
                                            <th style="text-align: center"><span style="float:left;: left">CR</span><?= number_format($c_balance_sum_new)?></th>
                                        <?php }else{ ?>
                                            <th style="text-align: center"><?= number_format($cl_balance_sum)?></th>
                                        <?php }?>
                                    </tr>
                                    </tfoot>
                                    <?php }$op_balance_sum_total += $balance_sum; $balance_sum = 0;
                                    $debit_sum_total += $debit_sum; $debit_sum = 0;
                                    $credit_sum_total += $credit_sum; $credit_sum = 0;
                                    $cl_balance_sum_total += $cl_balance_sum; $cl_balance_sum = 0; ?>
                                </table>
                            </div>
                        </div>
                    <?php }?>
                </div>
            </div>
            <div class="panel-body" style="direction: rtl;">
                <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
                    <table class="table-bordered" id="dataTables-example">
                        <thead>
                        <tr style="line-height: 250%;">
                            <th style="width: 16%;"></th>
                            <th style="text-align: center; width: 17%;"></th>
                            <th style="text-align: center; width: 17%">ابتدائ بیلینس</th>
                            <th style="text-align: center">ڈیبٹ</th>
                            <th style="text-align: center">کریڈٹ</th>
                            <th style="text-align: center; width: 17%;">اختتامی بیلنس</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="odd gradeX" style="line-height: 250%;">
                            <th style="text-align: center"></th>
                            <th class="center" style="text-align: center">کل</th>
                            <?php if ($op_balance_sum_total < 0){
                                $new_op_balance_sum_total = ($op_balance_sum_total * -1);?>
                                <th style="text-align: center"><span style="float:left;: left">CR</span><?= number_format($new_op_balance_sum_total)?></th>
                            <?php }else{ ?>
                                <th style="text-align: center"><?= number_format($op_balance_sum_total)?></th>
                            <?php }?>
                            <th style="text-align: center"><?= number_format($debit_sum_total)?></th>
                            <th style="text-align: center"><?= number_format($credit_sum_total)?></th>
                            <?php $cl_balance_sum_total = (($op_balance_sum_total + $debit_sum_total) - $credit_sum_total);?>
                            <?php if ($cl_balance_sum_total < 0){
                                $new_cl_balance_sum_total = ($cl_balance_sum_total * -1);?>
                                <th style="text-align: center"><span style="float:left;: left">CR</span><?= number_format($new_cl_balance_sum_total)?></th>
                            <?php }else{ ?>
                                <th style="text-align: center"><?= number_format($cl_balance_sum_total)?></th>
                            <?php }?>
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
    if (!(AccessRights == 1 || <?=$_SESSION['user'][0]->IsAdmin?> == 1)){
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