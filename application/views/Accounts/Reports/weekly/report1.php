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

        .VoucherMOdal {
            margin-right: 10px;
            border: 0;
            outline: 0;
            background: transparent;
            border-bottom: 1px solid black;
        }
        .note{
            margin-right: 10px;
            border: 0;
            outline: 0;
            background: transparent;
            border-bottom: 0px;
        }
        /*.table>tbody>tr>td {
          border: none!important;
          }*/
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
<body>
<?php
$reportdate = $markaz['report_date']; $printdate = $markaz['printing_date'];
$H_year = $reportdate[0].$reportdate[1].$reportdate[2].$reportdate[3];
$H_month = $reportdate[5].$reportdate[6]; $H_day = $reportdate[8].$reportdate[9];
$P_year = $printdate[0].$printdate[1].$printdate[2].$printdate[3];
$P_month = $printdate[5].$printdate[6]; $P_day = $printdate[8].$printdate[9];?>
<div class="content">
    <div class="row" id="content">
        <div id="hide">
            <button onclick="myFunction()">Print</button>
        </div>
        <div style="text-align: center;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:23%; max-width:330px;">
            <h2 style="margin-top: 0px;text-decoration: underline; font-size: 27px; ">شعبہ حسابات</h2>
        </div>
    </div>
    <div class="row" style="margin-right: 65%;margin-top: -11%;margin-left: -6%;">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">حوالہ نمبر<span style="margin-right: 5px;">:</span>
                    <input type="text" class="VoucherMOdal" value="<?= isset($serial) ? $serial : "" ?>" style="width: 48%;" readonly>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">تاریخ<span style="margin-right: 2px;">:</span>
                    <?php
                    if ($P_month == '01') { $P_month_inwords = 'محرم‬';
                    }elseif($P_month == '02'){ $P_month_inwords = 'صفر‬';
                    }elseif($P_month == '03'){ $P_month_inwords = 'ر بیع الاول‬';
                    }elseif($P_month == '04'){ $P_month_inwords = 'ربیع الثانی';
                    }elseif($P_month == '05'){ $P_month_inwords = 'جمادی الاول‬';
                    }elseif($P_month == '06'){ $P_month_inwords = 'جمادی الثانی‬';
                    }elseif($P_month == '07'){ $P_month_inwords = 'رجب‬';
                    }elseif($P_month == '08'){ $P_month_inwords = 'رجب المرجب';
                    }elseif($P_month == '09'){ $P_month_inwords = 'رمضان المبارک';
                    }elseif($P_month == '10'){ $P_month_inwords = 'شوال';
                    }elseif($P_month == '11'){ $P_month_inwords = 'ذوالقعدۃ‬';
                    }elseif($P_month == '12'){ $P_month_inwords = '‫ذوالحجۃ‬'; }?>
                    <input type="text" value="<?= $P_day?>  <?=$P_month_inwords?> <?= $P_year?> ھ" class="VoucherMOdal" readonly style="width: 50%;margin-right: 9%;">
                </label>
            </div>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row" style="margin-top: -6%;margin-right: -5%;">
                            <div class="col-md-12">
                                <h5>محترم حضرت ریئس الجامعہ مدظلہم العالی،جامعہ دارالعلوم کراچی</h5>
                                <h5 style="">رپورٹ برائے وصولیاں و مصارف و کیش وبینک بیلنس بابت <span style="font-weight: bold;"><?=$markaz['To'];?></span>ھ تا <span style="font-weight: bold;"><?=$markaz['From'];?></span>ھ پیش خدمت ہے</h5>
                            </div>
                        </div><br>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%; overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr>
                                    <th rowspan="2" style="text-align: center;">مّدات</th>
                                    <th colspan="3" style="text-align: center;line-height: 215%;"> تفصیل</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">وصولیاں</th>
                                    <th style="text-align: center;">مصارف</th>
                                    <th style="text-align: center;">فرق</th>
                                </tr>
                                </thead>
                                <tbody style="text-align: center;">
                                <?php $frq = 0; $total_wsoliyan = 0; $total_msarif = 0; $total_frq = 0; $P_total_wsoliyan = 0; $P_total_msarif = 0; $P_total_frq = 0; $P_frq = 0; $TranReverse = 0; $TranReverseEx = 0; $Wnew = 0; $Pnew = 0; $t_check = 0; $go = 0;?>
                                <?php foreach($markaz['Previous'] as $P_key => $value){
                                        
                                        // print_r($t_check);
                                        // echo "<br>";
                                        if(is_numeric($P_key)){
                                            $P_total_wsoliyan += ($value[0]->Credit - $markaz['IsReversePrevious'][$P_key][0]->Debit);
                                            //$t_check += $markaz['Previous']['OpeningBal'][$P_key]->OpeningBalance;
                                        //$markaz['Previous']['OpeningBal'][$P_key]->OpeningBalance
                                        $P_total_msarif += ($value[0]->Debit - $markaz['IsReversePrevious'][$P_key][0]->Credit);
                                        $P_frq = $P_total_msarif - $P_total_wsoliyan;
                                        $P_total_frq = $P_frq;
                                        }
                                        //$go = $t_check + $P_total_wsoliyan;
                                        //print_r($P_total_wsoliyan);
                                        
                                    
                                 }?>
                                <?php foreach($markaz['Maddat_Name'] as $key => $maddat):?>
                                    <?php 
                                    //echo $P_total_wsoliyan;
                                    if ($key == 0){ ?>
                                        <tr>
                                            <?php  $year = $markaz['To'][0].$markaz['To'][1].$markaz['To'][2].$markaz['To'][3].$markaz['To'][4];
                                            $month = $markaz['To'][5].$markaz['To'][6].$markaz['To'][7];
                                            $day = $markaz['To'][8].$markaz['To'][9] - 1;
                                            $date = $year.$month.$day;?>
                                            <th style="text-align: center;"> میزان بتاریخ<br>
                                                <?= $date?></th>
                                            <?php if($P_total_wsoliyan == 0.00){?>
                                            <th style="text-align: center;">-</th>
                                            <?php }elseif($P_total_wsoliyan < 0){
                                                    $Wnew = $P_total_wsoliyan * -1;?>
                                            <th style="text-align: center;">(<?=number_format($Wnew) ?>)</th>
                                            <?php }else{?>
                                            <th style="text-align: center;"><?=number_format($P_total_wsoliyan);?></th>
                                            <?php }?>
                                            <?php if($P_total_msarif == 0.00){?>
                                            <th style="text-align: center;">-</th>
                                            <?php }elseif($P_total_msarif < 0){
                                                $Pnew = $P_total_msarif * -1;?>
                                            <th style="text-align: center;">(<?=number_format($Pnew) ?>)</th>
                                            <?php }else{?>
                                            <th style="text-align: center;"><?=number_format($P_total_msarif) ?></th>
                                            <?php }?>
                                            <?php if ($P_total_frq == 0.00){ ?>
                                                <th style="text-align: center;">-</th>
                                            <?php } elseif ($P_total_frq < 0){
                                                $new = $P_total_frq * -1; ?>
                                                <th style="text-align: center;">(<?=number_format($new);?>)</th>
                                            <?php }else{ ?>
                                                <th style="text-align: center;"><?=number_format($P_total_frq);?></th>
                                            <?php }?>
                                        </tr>
                                    <?php }?>
                                    <?php if(!($with_zero == 1 && $markaz['TillToday'][$key][0]->Credit == 0 && $markaz['TillToday'][$key][0]->Debit == 0 )){?>
                                        <tr>
                                            <td><?= $maddat->Mad_name?></td>
                                            <?php if (is_numeric($markaz['TillToday'][$key][0]->Credit) && is_numeric($markaz['TillToday'][$key][0]->Debit)) {
                                                if(!($markaz['TillToday'][$key][0]->Credit == 0.00)){
                                                    $TranReverse = $markaz['TillToday'][$key][0]->Credit - $markaz['IsReverse'][$key][0]->Debit;?>
                                                    <td style="text-align: center;"><?=number_format($TranReverse); $total_wsoliyan += $TranReverse; ?></td>
                                                    <?php if(!($markaz['TillToday'][$key][0]->Debit == 0.00)){
                                                        $TranReverseEx = $markaz['TillToday'][$key][0]->Debit - $markaz['IsReverse'][$key][0]->Credit;?>
                                                        <td style="text-align: center;"><?=number_format($TranReverseEx);
                                                            $total_msarif += $TranReverseEx; ?></td>
                                                    <?php } else{ ?>
                                                        <td style="text-align: center;">-</td>
                                                    <?php }
                                                    } else{?>
                                                    <td style="text-align: center;">-</td>
                                                    <td style="text-align: center;">-</td>
                                                <?php }
                                            }else{?>
                                                <td style="text-align: center;">-</td>
                                                <td style="text-align: center;">-</td>
                                            <?php } if (is_numeric($markaz['TillToday'][$key][0]->Credit) && is_numeric($markaz['TillToday'][$key][0]->Debit)) {
                                                $frq = $markaz['TillToday'][$key][0]->Credit - $markaz['TillToday'][$key][0]->Debit;
                                                $total_frq += $frq; 
                                                ?>
                                                <?php if($frq == 0){?>
                                                    <td style="text-align: center;">-</td>
                                                <?php } elseif($frq < 0){
                                                    $newfrq = ($frq * -1);?>
                                                    <td style="text-align: center;">(<?= number_format($newfrq);?>)</td>
                                                <?php }else{ 
                                                    ?>
                                                    <td style="text-align: center;"><?= number_format($frq);?></td>
                                                <?php } } else {
                                                    ?>
                                                <td style="text-align: center;">-</td>
                                            <?php }?>
                                        </tr>
                                    <?php }?>
                                <?php endforeach?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="text-align: center;">میزان بتاریخ<br><?= $markaz['From']?></th>
                                    <?php if($total_wsoliyan == 0.00){?>
                                    <th style="text-align: center;">-</th>
                                    <?php } elseif($total_wsoliyan < 0){
                                        $New_total_wasliyan = $total_wsoliyan * -1;?>
                                    <th style="text-align: center;">(<?= number_format($New_total_wasliyan)?>)</th>
                                    <?php }else{?>
                                     <th style="text-align: center;"><?= number_format($total_wsoliyan)?></th>
                                    <?php }?>
                                    <?php if($total_msarif == 0.00){?>
                                    <th style="text-align: center;">-</th>
                                    <?php }elseif($total_msarif < 0){ 
                                        $New_total_msarif = $total_msarif * -1;?>
                                    <th style="text-align: center;">(<?= number_format($New_total_msarif)?>)</th>
                                    <?php } else{?>
                                    <th style="text-align: center;"><?= number_format($total_msarif)?></th>
                                    <?php }?>
                                    <?php if ($total_frq == 0.00){ ?>
                                        <th style="text-align: center;">-</th>
                                    <?php }elseif ($total_frq < 0){
                                        $newTotalFarq = ($total_frq * -1);?>
                                        <th style="text-align: center;">(<?= number_format($newTotalFarq)?>)</th>
                                    <?php }else{ ?>
                                        <th style="text-align: center;"><?= number_format($total_frq)?></th>
                                    <?php }?>
                                </tr>
                                <?php $PWCW = 0; $PMCM = 0; $PFCF = 0;
                                $PWCW = $P_total_wsoliyan + $total_wsoliyan;
                                $PMCM = $P_total_msarif + $total_msarif;
                                $PFCF = $P_total_frq + $total_frq;
                                //print_r($PWCW);
                                ?>

                                <tr style="text-align: center;">
                                    <th style="line-height: 30px;text-align: center;">میزان</th>
                                    <th style="text-align: center;"><?= number_format($PWCW)?></th>
                                    <th style="text-align: center;"><?= number_format($PMCM)?></th>
                                    <th style="text-align: center;"><?= number_format($PFCF)?></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row" style="margin-top: -7%;    margin-right: -5%;margin-bottom: -3%;">
                            <div class="col-md-12">
                                <h5 style="text-align: center;">کیش و بینک بیلنس</h5>
                            </div>
                        </div><br>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr>
                                    <th style="text-align: center;line-height: 215%;">تفصیل</th>
                                    <th style="text-align: center;">آمدنی کھاتہ(کورنگی)</th>
                                    <th style="text-align: center;">مصارف کھاتہ(کورنگی)</th>
                                    <th style="text-align: center;">سرمایہ کاری</th>
                                </tr>
                                </thead>
                                <?php $B_amdni_total = 0; $B_msarif_total = 0; $B_srmayakari_total = 0;
                                $B_total_amdni_previous = 0; $B_total_msarif_previous = 0;
                                $B_total_srmayakari_previous = 0; $TranReverseIncome = 0; $TranReverseExpance = 0 ;
                                $TranReverseSurmayakari = 0; $TranReverseIncomeOpening = 0; ?>
                                <tbody style="text-align: center;">

                                <?php foreach ($CashBalance['PreviousBank'] as $PB_key => $pvalue) {
                                    foreach ($pvalue as $PA_key => $pbank) {

                                        if ($PB_key == "Other") {
                                            //$CashBalance['PreviousBank']['OpeningBal'][$PB_key][$PA_key]->OpeningBalance;*
                                        $B_total_amdni_previous += ($CashBalance['PreviousBank'][$PB_key][$PA_key][0]->Credit - $CashBalance['IsReversePreviousBank'][$PB_key][$PA_key][0]->Debit);

                                        $B_total_msarif_previous += ($CashBalance['PreviousBank'][$PB_key][$PA_key][0]->Debit - $CashBalance['IsReversePreviousBank'][$PB_key][$PA_key][0]->Credit);

                                        }elseif($PB_key == "Captial"){
                                        $B_total_srmayakari_previous += ($CashBalance['PreviousBank'][$PB_key][$PA_key][0]->Credit -$CashBalance['IsReversePreviousBank'][$PB_key][$PA_key][0]->Debit);}
                                    }
                                } ?>
                                <?php foreach($CashBalance['TillTodayBank'] as $B_key => $item):?>
                                    <?php foreach($item as $A_key => $value ){

                                        $year = $CashBalance['To'][0].$CashBalance['To'][1].$CashBalance['To'][2].$CashBalance['To'][3].$CashBalance['To'][4];
                                        $month= $CashBalance['To'][5].$CashBalance['To'][6].$CashBalance['To'][7];
                                        $day = $CashBalance['To'][8].$CashBalance['To'][9]-1;
                                        $dateCash = $year.$month.$day;
                                        if ($A_key == 0){ ?>
                                            <tr>
                                                <th style="text-align: center;">میزان بتاریخ<br><?= $dateCash?></th>
                                                <?php if($B_total_amdni_previous == 0.00){?>
                                                <th style="text-align: center;">-</th>
                                            <?php }elseif($B_total_amdni_previous < 0){
                                                $new_B_total_amdni_previous = $B_total_amdni_previous * -1; ?>
                                                <th style="text-align: center;">(<?=number_format($new_B_total_amdni_previous) ?>)</th>
                                                <?php }else{?>
                                                <th style="text-align: center;"><?=number_format($B_total_amdni_previous) ?></th>
                                                <?php }?>
                                                <?php if($B_total_msarif_previous == 0.00){?>
                                                <th style="text-align: center;">-</th>
                                                <?php }elseif($B_total_msarif_previous < 0){
                                                    $New_B_total_msarif_previous = $B_total_msarif_previous * -1;
                                                    ?>
                                                <th style="text-align: center;">(<?=number_format($New_B_total_msarif_previous) ?>)</th>
                                                <?php }else{?>
                                                <th style="text-align: center;"><?=number_format($B_total_msarif_previous) ?></th>
                                                <?php }?>
                                                <?php if($B_key == "Captial"){?>
                                                    <?php if ($B_total_srmayakari_previous == 0.00){ ?>
                                                        <th style="text-align: center;">-</th>
                                                    <?php }elseif ($B_total_srmayakari_previous < 0){
                                                        $new_B_total_srmayakari_previous = ($B_total_srmayakari_previous * -1); ?>
                                                        <th style="text-align: center;">(<?= number_format($new_B_total_srmayakari_previous)?>)</th>
                                                    <?php }else{ ?>
                                                        <th style="text-align: center;"><?= number_format($B_total_srmayakari_previous)?></th>
                                                    <?php }}else{?>
                                                    <th style="text-align: center;">-</th>
                                                <?php }?>
                                            </tr>
                                        <?php }?>
                                        <?php if(!($with_zero == 1 && $CashBalance['TillTodayBank'][$B_key][$A_key][0]->Credit == 0 && $CashBalance['TillTodayBank'][$B_key][$A_key][0]->Debit && $CashBalance['TillTodayBank'][$B_key][$A_key][0]->Debit == 0 )){?>
                                            <tr>
                                                <td><?= $CashBalance['Maddat_Name_Bank'][$A_key]->Mad_name ?></td>
                                                <?php if ($B_key == "Other") { 
                                                if($CashBalance['TillTodayBank'][$B_key][$A_key][0]->Credit == 0.00){ ?>
                                                    <td style="text-align: center;">-</td>
                                                <?php } else{
                                                         $TranReverseIncome = $CashBalance['TillTodayBank'][$B_key][$A_key][0]->Credit - $CashBalance['IsReverse'][$B_key][$A_key][0]->Debit;?>
                                                    <td style="text-align: center;"><?=number_format($TranReverseIncome);?></td>
                                                <?php } $B_amdni_total += $TranReverseIncome;?>
                                                <?php if($CashBalance['TillTodayBank'][$B_key][$A_key][0]->Debit == 0.00){?>
                                                    <td style="text-align: center;">-</td>
                                                <?php } else{
                                                    $TranReverseExpance = $CashBalance['TillTodayBank'][$B_key][$A_key][0]->Debit - $CashBalance['IsReverse'][$B_key][$A_key][0]->Credit;?>
                                                    <td style="text-align: center;"><?=number_format($TranReverseExpance)?></td>
                                                <?php } $B_msarif_total += $TranReverseExpance; }?>
                                                <?php if ($B_key == "Captial") {
                                                    if($CashBalance['TillTodayBank'][$B_key][$A_key][0]->Debit == 0.00){?>
                                                        <td style="text-align: center;">-</td>
                                                    <?php } else{
                                                        $TranReverseSurmayakari = $CashBalance['TillTodayBank'][$B_key][$A_key][0]->Debit - $CashBalance['IsReverse'][$B_key][$A_key][0]->Credit;?>
                                                        <td style="text-align: center;"><?=number_format($TranReverseSurmayakari)?></td>
                                                    <?php } $B_srmayakari_total += $TranReverseSurmayakari;
                                                }else{ $B_srmayakari_total = 0;?>
                                                    <td style="text-align: center;">-</td>
                                                <?php }?>
                                            </tr>
                                        <?php } $TranReverseIncome = 0;
                                        $TranReverseExpance = 0;
                                      }
                                 endforeach?>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th style="text-align: center;">میزان بتاریخ<br><?= $CashBalance['From']?></th>
                                    <?php if($B_amdni_total == 0.00){?>
                                    <th style="text-align: center;">-</th>
                                    <?php } elseif($B_amdni_total < 0){
                                        $New_B_amdni_total =  $B_amdni_total * -1;?>
                                    <th style="text-align: center;">(<?= number_format($New_B_amdni_total)?>)</th>
                                    <?php }else{?>
                                    <th style="text-align: center;"><?= number_format($B_amdni_total)?></th>
                                    <?php }?>
                                    <?php if($B_msarif_total == 0.00){?>
                                    <th style="text-align: center;">-</th>
                                    <?php } elseif($B_msarif_total < 0){
                                        $New_B_msarif_total = $B_msarif_total * -1;?>
                                    <th style="text-align: center;">(<?= number_format($New_B_msarif_total)?>)</th>
                                    <?php } else{?>
                                    <th style="text-align: center;"><?= number_format($B_msarif_total)?></th>
                                    <?php } ?>
                                    <?php if ($B_srmayakari_total == 0.00){ ?>
                                        <th style="text-align: center;">-</th>
                                    <?php }elseif ($B_srmayakari_total < 0){
                                        $new_B_srmayakari_total = ($B_srmayakari_total * -1); ?>
                                        <th style="text-align: center;">(<?= number_format($new_B_srmayakari_total)?>)</th>
                                    <?php }else{ ?>
                                        <th style="text-align: center;"><?= number_format($B_srmayakari_total)?></th>
                                    <?php }?>
                                </tr>
                                <?php $PACA = 0; $PMSACMSA = 0; $PBFCBF = 0;
                                $PACA =  $B_total_amdni_previous + $B_amdni_total;
                                $PMSACMSA = $B_total_msarif_previous + $B_msarif_total;
                                $PBFCBF = $B_total_srmayakari_previous + $B_srmayakari_total;

                                ?>
                                <tr>
                                    <th style="text-align: center;line-height: 30px;">میزان</th>
                                    <th style="text-align: center;"><?= number_format($PACA) ?></th>
                                    <th style="text-align: center;"><?= number_format($PMSACMSA) ?></th>
                                    <th style="text-align: center;"><?= number_format($PBFCBF) ?></th>
                                    
                                </tr>

                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <p>نوٹ:</p>
                    </div>
                </div>
                <div class="col-md-10">
                    <div class="form-group">
                        <textarea rows="4" cols="100" type="text" class="note" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;   width: 70%;overflow:hidden;font-weight: bold;"></textarea>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</div>
<!-- <footer class ="footer" id="pagebreak">
            <div style="direction: ltr;">
                <hr> <?php date_default_timezone_set('Asia/Karachi');?>
                <span style="float: left;"><?= date('l d-m-Y h:i:s');?></span>
                <hr>
            </div>
        </footer> -->
</body>
</html>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
</script>