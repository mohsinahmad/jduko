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
            src: url(<?= base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
        }
    </style>
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>
    <link href="<?= base_url()."assets/"; ?>css/plugins/dataTables.bootstrap.css" rel="stylesheet">
    <link href="<?= base_url()."assets/"; ?>css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .content{
            border:0px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            max-width: 1000px;
            margin:auto;
            padding:20px;
        }
        .one{
            line-height:10%;
            margin: 0px;
        }
        .heading{
            float: right;
            text-align: -webkit-right;
            /*margin-top: 24px;*/
        }
        .panel-body{
            padding: 0px;
        }
        .acc-details{
            float: left;
            text-align: -webkit-right;
            line-height: 20px;
            font-size: 15px;
            /*margin-top: 21px;*/
            width: 31%;
        }
        .two{
            direction: rtl;
            width: 97%;
            margin-right: 2%;
            padding-top: 2%;
        }
        .remarks{
            table-layout:fixed;
            width:100px;
            word-break: break-all;
        }
        @media print {
            html, body{
                width:104%;
                height:auto;
                margin-right: -1%;
                margin-left: 2%;
                padding:0;
                margin-top: 1%;
                margin-bottom: 2%;
                overflow: auto;
                overflow-x: hidden;
            }
            #pagebreak {
                page-break-after: always;
            }
            .remarks{
                table-layout:fixed;
                width:100px;
                word-break: break-all;
            }
            #hide{
                display: none !important;
            }
            .ta{
                margin-left: 12%;

            }
            .bamutabiq{
                margin-left: 5%;
            }
            .ta1{
                margin-left: 12%;

            }
            .tabledarkborder>thead>tr>th,.tabledarkborder>tbody>tr>td,.tabledarkborder>tfoot>tr>th {
                border:1px solid black;
                padding-bottom: 5px;
            }
            .opening{
                margin-top: 0px;
                float:left;
                direction: ltr
            }
        }
        .tabledarkborder>thead>tr>th,.tabledarkborder>tbody>tr>td,.tabledarkborder>tfoot>tr>th {
            border:1px solid black;
            padding-bottom: 5px;
        }
    </style>
</head>
<?php if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,11,$Access_level);
if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[5];?>">
<?php }
$complete_debit_sum = 0; $complete_credit_sum = 0; $complete_balance_sum = 0; ?>
<body>
<div class="content" style="width: 95%;">
    <div class="row" id="content" style="margin-top: -24px">
        <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <div id="hide">
                <button onclick="myFunction()">Print</button>
            </div> <?php }?>
        <div style="text-align: center;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:28%; max-width:330px;">
            <br><br>
            <label>   مالی سال : <?php print_r($_SESSION['current_year'])?> </label>
            <br><br>
            <?php if (isset($consolidated)){
                if($VoucherType != ''){?>
                    <h2 style="font-size: 23px;margin-top: 0px;text-decoration: underline; "><?php
                        if($VoucherType == 'CR'){
                            echo 'کیش وصولی رپورٹس';
                        }elseif($VoucherType == 'CP' ){
                            echo 'کیش ادائیگی رپورٹس';
                        }elseif($VoucherType == 'BR'){
                            echo 'بینک وصولی رپورٹس';
                        }elseif($VoucherType == 'BP'){
                            echo 'بینک ادائیگی رپورٹس';
                        }elseif($VoucherType == 'JV'){
                            echo 'جنرل جرنل رپورٹس';
                        }elseif($VoucherType == 'IC'){
                            echo 'آمدنی واؤچر رپورٹس';
                        }
                        ?></h2>
                <?php }else{
                    if (isset($all)){?>
                        <h2 style="font-size: 23px;margin-top: 0px;text-decoration: underline; ">مشترکہ لیجر<span style="font-size:17px ">(مستقل+عارضی)</span></h2>
                    <?php } else{?>
                        <h2 style="font-size: 23px;margin-top: 0px;text-decoration: underline; ">مشترکہ لیجر<span style="font-size:17px ">(مستقل)</span></h2>
                    <?php }
                }
            }else{
                if($VoucherType != ''){?>
                    <h2 style="font-size: 23px;margin-top: 0px;text-decoration: underline; ">
                        <?php
                        if($VoucherType == 'CR'){
                            echo 'کیش وصولی رپورٹس';
                        }elseif($VoucherType == 'CP' ){
                            echo 'کیش ادائیگی رپورٹس';
                        }elseif($VoucherType == 'BR'){
                            echo 'بینک وصولی رپورٹس';
                        }elseif($VoucherType == 'BP'){
                            echo 'بینک ادائیگی رپورٹس';
                        }elseif($VoucherType == 'JV'){
                            echo 'جنرل جرنل رپورٹس';
                        }elseif($VoucherType == 'IC'){
                            echo 'آمدنی واؤچر رپورٹس';
                        }
                        ?>
                    </h2>
                <?php }else{
                    if (isset($all)){?>
                        <h2 style="font-size: 23px;margin-top: 0px;text-decoration: underline; ">اکاؤنٹ لیجر<span style="font-size:17px ">(مستقل+عارضی)</span></h2>
                    <?php } else{?>
                        <h2 style="font-size: 23px;margin-top: 0px;text-decoration: underline; ">اکاؤنٹ لیجر<span style="font-size:17px ">(مستقل)</span></h2>
                    <?php }
                }
            }?>
        </div>
    </div>
    <?php foreach ($AccName as $A_key => $acc){?>
        <div class="level">
            <div class="row one">
                <?php if ($A_key == 0){?>
                    <div class="col-md-6 heading">
                        <?php $sey=' سے '; $tk = ' تک ';?>
                        <h4><p><?= $to[0]->Sh_date?></p><p style="margin-right: 123px;margin-top: -29px;"><?= $to[0]->Qm_date;?></p></h4>
                        <br><span class ="ta" style="text-decoration: underline; margin-right: 5%;">تا</span>
                        <span class="bamutabiq" style="text-decoration: underline; margin-right: 12%;">بمطابق</span>
                        <span class="ta1" style="text-decoration: underline; margin-right: 10%;">تا</span><br>
                        <h4><p><?= $from[0]->Sh_date?></p><p style="margin-right: 123px;margin-top: -29px;"><?= $from[0]->Qm_date;?></p></h4>
                    </div>
                    <div class="col-md-6 acc-details">
                        <h3 style="font-weight: normal;"><?= $LevelName?></h3> <!-- Level Name -->
                    </div>
                <?php }?>
            </div>
            <div>
                <?php $trans_count = 0; $count = 1; $total_final_p = 0; $total_credit_final_p = 0 ; $total_debit_final_p = 0;$total_debit_all = 0;$total_credit_all = 0;$balance_all_total = 0;
                foreach ($transactions[$A_key] as $key => $transaction1){?>
                    <div class="row two">
                        <div class="col-lg-12" style="padding-right: 0px">
                            <div class="panel-body"> <?php if (isset($openingbalance[$A_key])){ $openingbalances = $openingbalance[$A_key];}?>
                                <?php if ($openingbalances < 0){
                                    $new_openingbalance = ($openingbalances * -1);?>
                                    <h4 class="opening" style="margin-top: -29px;float: left;direction: ltr;">(<?= number_format($new_openingbalance); ?>)<span>: ابتدائ بیلنس</span></h4>
                                <?php }else{ ?>
                                    <h4 class="opening" style="margin-top: -29px;float: left;direction: ltr;"><span><?= number_format($openingbalances); ?></span><span>: ابتدائ بیلنس</span></h4>
                                <?php }?>

                                <div class="table-responsive" align="middle" style="overflow-x: hidden;overflow-y: hidden;">
                                    <table class="tabledarkborder" style="width: 100%" id="debitp<?= $count?>">
                                        <thead>
                                        <tr>
                                            <?php if(isset($consolidated)){?>
                                                <th colspan="8">
                                                    <h4 style="margin-top: 6px;font-weight: normal;"><?=($Head[$A_key][0]->AccountName);?> :<span style="font-weight: normal;"><?=($AccName[$A_key][0]->AccountName);?></span>-<?=($AccName[$A_key][0]->AccountCode);?></h4>
                                                </th>
                                            <?php }else{?>
                                                <th colspan="7">
                                                    <h4 style="margin-top: 6px;font-weight: normal;"><?=($Head[$A_key][0]->AccountName);?> :<span style="font-weight: normal;"><?=($AccName[$A_key][0]->AccountName);?></span>-<?=($AccName[$A_key][0]->AccountCode);?></h4>
                                                </th>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <th style="text-align: center; width:10%">واؤچر نمبر</th>
                                            <th style="text-align: center; width:10%">عیسوی تاریخ</th>
                                            <th style="text-align: center; width:10%">ہجری تاریخ</th>
                                            <?php if (isset($consolidated)){?>
                                                <th style="text-align: center;width:7%;">لیول</th>
                                            <?php }?>
                                            <th style="text-align: center;height: 34px;">تفصیل</th>
                                            <th style="width: 10%;text-align: center">ڈیبٹ</th>
                                            <th style="width: 10%;text-align: center">کریڈٹ</th>
                                            <th style="width: 13%;text-align: center">بیلینس</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php 
                                        
                                        $balance = 0; 
                                    $balance = $openingbalances + $balance;
                                        $total_debit = 0; 
                                        $total_credit = 0; 
                                        $TransactionCounts = 0; 
                                        
                                        foreach($transaction1 as $Tkey => $transaction):?>
                                            <?php if($transaction->Permanent_VoucherNumber != ''){ $TransactionCounts++;?>
                                                <tr class="odd gradeX" style="text-align: center;">
                                                    <td style="width: 10%;"><?= $transaction->VoucherType.'-'.$transaction->Permanent_VoucherNumber?></td>
                                                    <td style="width: 10%;"><?= $transaction->Permanent_VoucherDateG?></td>
                                                    <td style="width: 10%;"><?= $transaction->Permanent_VoucherDateH?></td>
                                                    <?php if (isset($consolidated)){?>
                                                        <td style="width: 10%;text-align: center"><?= $transaction->LevelName?></td>
                                                    <?php }?>
                                                    <?php
                                                  if($transaction->Remarks != ''){
                                                    ?>
                                                        <td class="remarks"><?= $transaction->Remarks?></td>
                                                    <?php }else{?>
                                                        <td class="remarks"><?= $transaction->Remarks?></td>
                                                    <?php } ?>
                                                    <td style="width:10%" class="center"><?= number_format($transaction->Debit)?></td>
                                                    <td style="width:10%" class="center "><?= number_format($transaction->Credit)?></td>
                                                    <?php $total_debit += $transaction->Debit;
                                                    $total_credit += $transaction->Credit;?>
                                                    <?php $balance = (string)$balance + $transaction->Debit - $transaction->Credit; ?>
                                                    <td style="display: none"><input type="hidden" class="balancep<?= $count;?>" value="<?= $balance;?>"></td>
                                                    <?php if($balance < 0){ $balance_new = ($balance * -1);?>
                                                        <td class="center" style="width:13%">(<?= number_format($balance_new)?>)</td>
                                                    <?php }else{?>
                                                        <td class="center" style="width:13%"><?= number_format($balance);?></td>
                                                    <?php } $trans_count = $Tkey;?>
                                                </tr>
                                            <?php } endforeach ?>
                                        </tbody>
                                        <?php if ($TransactionCounts > 1){ ?>
                                        <tfoot>
                                        <?php }else{ ?>
                                        <tfoot style="display:none">
                                        <?php }?>
                                        <tr style="text-align: center;">
                                            <th></th>
                                            <th></th>
                                            <?php if (isset($consolidated)){?>
                                                <th></th>
                                            <?php }?>
                                            <th></th>
                                            <th><span style="float: left;"> میزان:</span></th>
                                            <th style="text-align: center"><?= number_format($total_debit);?></th>
                                            <th style="text-align: center"><?= number_format($total_credit);?></th>
                                            <?php $total_debit_final_p += $total_debit;
                                            $total_credit_final_p += $total_credit;
                                            if($balance < 0){ $balance_new = ($balance * -1);?>
                                                <th style="text-align: center">(<?= number_format($balance_new);?>)</th>
                                            <?php }else{?>
                                                <th style="text-align: center"><span class="totalbp<?= $count?>"><?= number_format($balance);?></span></th>
                                            <?php } $total_final_p += $balance;?>
                                            <th style="display: none"><span class="totalbp2<?= $count?>"><?= number_format($balance)?></span></th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                    
                                    <?php if (isset($all)){?>
                                        <p>عارضی واؤچر</p>
                                        <table class="tabledarkborder" style="width: 100%" id="temp<?= $count;?>">
                                            <tbody>
                                            <?php if (!isset($all)){ $balance = 0; $balance += $openingbalances;}
                                            $total_debit = 0; $total_credit = 0; $tem_count = 0;
                                            foreach($transaction1 as $key_tr => $transaction): ?>
                                                <?php if(($transaction->Permanent_VoucherNumber) == null){ $tem_count = $key_tr;?>
                                                    <tr class="odd gradeX" style="text-align: center;">
                                                        <td style="width: 10%;"><?= $transaction->VoucherType.'-'.$transaction->VoucherNo?></td>
                                                        <td style="width: 10%;"><?= $transaction->VoucherDateG?></td>
                                                        <td style="width: 10%;"><?= $transaction->VoucherDateH?></td>
                                                        <?php if (isset($consolidated)){?>
                                                            <td style="width: 10%;text-align: center"><?= $transaction->LevelName?></td>
                                                        <?php }?>
                                                        <?php
                                                        if($transaction->Remarks != ''){?>
                                                            <td class="remarks"><?= $transaction->Remarks?></td>
                                                        <?php }else{?>
                                                            <td class="remarks"><?= $transaction->Remarks?></td>
                                                        <?php } ?>
                                                        <td class="center" style="width:10%"><?= number_format($transaction->Debit)?></td>
                                                        <td class="center " style="width:10%"><?= number_format($transaction->Credit)?></td>
                                                        <?php $balance = (string)$balance + $transaction->Debit - $transaction->Credit; ?>
                                                        <td style="display: none"><input type="hidden" class="balancet<?= $count;?>" value="<?= $balance;?>"></td>
                                                        <?php $total_debit += $transaction->Debit;
                                                        $total_credit += $transaction->Credit; $tcount = $key_tr;?>
                                                        <?php if($balance < 0){
                                                            $balance_new = $balance;
                                                            $balance_new = ($balance * -1);?>
                                                            <td class="center" style="width:13%">(<?= number_format($balance_new)?>)</td>
                                                        <?php }else{?>
                                                            <td class="center" style="width:13%"><?= number_format($balance);?></td>
                                                        <?php } $trans_count = $key_tr;?>
                                                    </tr> <?php } ?>
                                            <?php endforeach ?>
                                            </tbody>
                                            <tfoot>
                                            <?php if ($tem_count > 0){?>
                                                <tr style="text-align: center;">
                                                    <th style="width:10%;text-align:center"></th>
                                                    <th style="width:10%;text-align:center"></th>
                                                    <?php if (isset($consolidated)){?>
                                                        <th></th>
                                                    <?php }?>
                                                    <th style="width:10%;text-align:center"></th>
                                                    <th><span style="float: left;"> میزان:</span></th>
                                                    <th style="width:10%;text-align:center"><span><?= number_format($total_debit);?></span></th>
                                                    <th style="width:10%;text-align:center"><span><?= number_format($total_credit);?></span></th>
                                                    <?php $total_debit_final_p += $total_debit;
                                                    $total_credit_final_p += $total_credit;
                                                    if($balance < 0){ $balance_new = ($balance * -1);?>
                                                        <th style="width:13%;text-align:center">(<?= number_format($balance_new)?>)</th>
                                                    <?php }else{?>
                                                        <th style="width:13%;text-align:center"><span><?= number_format($balance);?></span></th>
                                                    <?php } $total_final_p += $balance;?>
                                                    <th style="display: none"><span><?= number_format($balance)?></span></th>
                                                </tr>
                                            <?php } if (isset($all)){?>
                                            <?php if ($trans_count > 0){?>
                                            <tr class="odd gradeX">
                                                <?php }else{?>
                                            <tr class="odd gradeX" style="display: none">
                                                <?php }?>
                                                <th style="padding-right: 10%;width:10%;text-align:center"></th>
                                                <th style="padding-right: 10%;width:10%"></th>
                                                <th style="padding-right: 10%;width:10%"></th>
                                                <?php if (isset($consolidated)){?>
                                                    <th></th>
                                                <?php }?>
                                                <th class="center" style="height: 26px">حتمی میزان </th>
                                                <th style="width:10%;text-align:center"><?= number_format($total_debit_final_p)?></th>
                                                <th style="width:10%;text-align:center"><?= number_format($total_credit_final_p)?></th>
                                                <?php if($balance < 0){
                                                    $total_final_p_new = ($balance * -1);?>
                                                    <th style="width:13%;text-align:center">(<?= number_format($total_final_p_new)?>)</th>
                                                <?php }else{?>
                                                    <th style="width:13%;text-align:center"><?= number_format($balance)?></th>
                                                <?php }  $complete_debit_sum += $total_debit_final_p;
                                                $complete_credit_sum += $total_credit_final_p;
                                                $complete_balance_sum += $total_final_p;
                                                $total_final_p = 0; $total_debit_final_p = 0; $total_credit_final_p = 0;
                                                } else{
                                                    $complete_debit_sum += $total_debit;
                                                    $complete_credit_sum += $total_credit;
                                                    $complete_balance_sum += $balance;
                                                } $count++; ?>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    <?php }?>
                                </div>

                            </div>
                        </div>
                    </div>

                    <?php if (isset($all)){?>
                        <!-- <div class="panel-body" style="direction: rtl; width:94%;margin-right: 3%;">-->
                        <!-- <div class="table-responsive">-->
                        <!-- <table class="table table-striped table-bordered table-hover" id="dataTables-example">-->
                        <!-- <thead>-->
                        <!-- <tr>-->
                        <!-- <th></th>-->
                        <!-- <th></th>-->
                        <!-- <th></th>-->
                        <!-- <th></th>-->
                        <!-- <th>ڈیبٹ</th>-->
                        <!-- <th>کریڈٹ</th>-->
                        <!-- <th>بیلینس</th>-->
                        <!-- </tr>-->
                        <!-- </thead>-->
                        <!-- <tbody>-->
                        <!-- <tr class="odd gradeX">-->
                        <!-- <td style="padding-right: 10%"></td>-->
                        <!-- <td style="padding-right: 10%"></td>-->
                        <!-- <td style="padding-right: 10%"></td>-->
                        <!-- <th class="center">حتمی میزان:</th>-->
                        <!-- <th id="totalalld--><?php //echo $count?><!--">--><?//= number_format($total_debit_final_p)?><!--</th>-->
                        <!-- <th id="totalallc--><?php //echo $count?><!--">--><?//= number_format($total_credit_final_p)?><!--</th>-->
                        <!--  --><?php //if($total_final_p < 0){
//                                                $total_final_p_new = ($total_final_p * -1);?>
                        <!-- <th class="atotalallb--><?php //echo $count?><!--">--><?//= number_format($total_final_p_new)?><!--<span style="float:left;">CR</span></th>-->
                        <!--  --><?php //}else{?>
                        <!-- <th class="atotalallb--><?php //echo $count?><!--">--><?//= number_format($total_final_p)?><!--</th>-->
                        <!--  --><?php //}?>
                        <!-- </tr>-->
                        <!--  --><?php
//                                        $complete_debit_sum += $total_debit_final_p;
//                                        $complete_credit_sum += $total_credit_final_p;
//                                        $complete_balance_sum += $total_final_p;
//                                        $total_final_p = 0; $total_debit_final_p = 0; $total_credit_final_p = 0;?>
                        <!-- </tbody>-->
                        <!-- </table>-->
                        <!-- </div>-->
                        <!-- </div>-->
                    <?php }else{
                        $complete_debit_sum += $total_debit;
                        $complete_credit_sum += $total_credit;
                        $complete_balance_sum += $balance;
                    } $count++; ?>
                <?php } ?>
            </div>
        </div> <?php $l_key = $key;?>
        <?php $openingbalances = 0;}?>
    <?php if ($l_key > 0){?>
        <div class="panel-body" style="direction: rtl; width:94%;margin-right: 3%;">
            <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                    <tbody>
                    <tr class="odd gradeX" style="text-align: center;">
                        <td style="width:10%;padding-right: 10%"></td>
                        <td style="width:10%;padding-right: 10%"></td>
                        <?php if (isset($consolidated)){?>
                            <th></th>
                        <?php }?>
                        <td style="width:10%;padding-right: 10%"></td>
                        <th class="center">حتمی میزان Final</th>
                        <th style="width:10%;text-align:center"><?= number_format($complete_debit_sum)?></th>
                        <th style="width:10%;text-align:center"><?= number_format($complete_credit_sum)?></th>
                        <?php if($complete_balance_sum < 0){
                            $total_final_p_new = ($complete_balance_sum * -1);?>
                            <th style="width:13%;text-align:center">(<?= number_format($total_final_p_new)?>)</th>
                        <?php }else{?>
                            <th style="width:13%;text-align:center"><?= number_format($complete_balance_sum)?></th>
                        <?php }?>
                    </tr>
                    <?php $total_final_p = 0; $total_debit_final_p = 0; $total_credit_final_p = 0;?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php }?>
    <?php

    // echo $complete_credit_sum;
    $bal = 0;
    if($complete_debit_sum == $complete_credit_sum){
           $bal= 0;
    }
    else{
        $bal = $complete_balance_sum;
    }
                                        echo '<div style="width: 95%;margin: auto;"><table class="table table table-bordered"><tr>                                    
                                            <th style="width:67%;text-align:center"><span style="float: left;"> میزان:</span></th>
                                            <th style="width:10%;text-align:center">'.number_format($complete_debit_sum).'</th>
                                            <th style="width:10%;text-align:center">'.number_format($complete_credit_sum).'</th><th>'.number_format($bal).'</th>
                                            </tr><table><div>';?>
    <!--    <footer>-->
    <!--        <div style="direction: ltr;" id="pagebreak">-->
    <!--            <hr> --><?php //date_default_timezone_set('Asia/Karachi');?>
    <!--            <p>--><?php //echo date('l d-m-Y h:i:s');?><!--</p>-->
    <!--            <hr>-->
    <!--            <input type="hidden" id="print" value="--><?//= $print;?><!--">-->
    <!--        </div>-->
    <!--    </footer>-->
</div>
<div class="PrintMessage" style="margin-right: 200px;margin-top: 500px;font-size: 2em">آپ اس دستاویز کو پرنٹ کرنے کے مجاز نہیں ہیں۔</div>
</body>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
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
</script>
<script>



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
    }
</script>
</html>