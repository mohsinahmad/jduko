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
            border:1px solid #eee;
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
        #table>tbody>tr>td.asas {
            border: 0!important;
            font-size : 0.8em!important;
            line-height:16px;
        }
        #table>tbody>tr {
            line-height: 8px!important;
        }
        #table>tfoot>tr {
            line-height: 10px!important;
        }
        /*#table>tbody>tr>td.ifStyle {*/
        /*border: 0!important;*/
        /*position: absolute!important;*/
        /*margin-right: 1%!important;*/
        /*margin-top: -5px!important;*/
        /*}*/
        /*#table>tbody>tr>td.ElseStyle {*/
        /*border: 0!important;*/
        /*position: absolute!important;*/
        /*margin-right: 1%!important;*/
        /*margin-top: -13px!important;*/
        /*}*/
        /*#table>tbody>tr>td {
            border: none!important;
        }*/
        @media print {
            #table>tbody>tr>td.borderzero {
                border: 0!important;
            }
            #table>tbody>tr>td.borderright {
                border-left: 1px dashed black!important;
                border-right: 2px solid black!important;
                border-bottom: 0!important;
                border-top: 0!important;
            }
            #table>tbody>tr>td.borderleft{
                border-left: 2px solid black!important;
                border-bottom: 0!important;
                border-right: 0!important;
                border-top: 0!important;
            }
            #table>tfoot>tr>td.borderleft{
                border-left: 2px solid black!important;
                border-bottom: 0!important;
                border-right: 0!important;
                border-top: 0!important;
            }

            #table>tfoot>tr>td.borderright {
                border-left: 1px dashed black!important;
                border-right: 2px solid black!important;
                border-bottom: 0!important;
                border-top: 0!important;
            }
            #table>tfoot>tr>td.borderzero {

                border: 0!important;
            }
            #table>tbody>tr>td.ifStyle {
                position: absolute!important;
                margin-right: 1%!important;
                margin-top: -5px!important;
                border: 0!important;
            }
            #table>tbody>tr>td.ElseStyle {
                position: absolute!important;
                margin-right: 1%!important;
                margin-top: -13px!important;
                border: 0!important;
            }
            #table>tbody>tr>td.asas {
                border: 0!important;
                font-size : 0.8em!important;
                line-height:16px;
            }
            #table>tbody>tr {
                line-height: 8px!important;
            }
            #table>tfoot>tr {
                line-height: 14px!important;
            }
            html, body{
                width:105%;
                height:auto;
                margin-right: -2%;
                padding:0;
                margin-top: 1%;
                margin-bottom: 2%;
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
            .content{
                border:0!important;
                width: 100%!important;
            }
            table {
                width: 100%;
            }
        }
    </style>
</head>
<?php $total_last_year = 0; $total_till_last_year = 0;
$TCredit0 = 0; $TCredit1 = 0; $TCredit2 = 0;
$TtCredit0 = 0; $TtCredit1 = 0; $TtCredit2 = 0;
$today_sum[0] = 0; $till_today_sum[0] = 0;
$today_sum[1] = 0; $till_today_sum[1] = 0;
$today_sum[2] = 0; $till_today_sum[2] = 0;
$total_sum_till_today = 0; $total_sum_today = 0;
$sum_Today = 0; $sum_Till_Today = 0;
$H_year = $report_date[0].$report_date[1].$report_date[2].$report_date[3];
$H_month = $report_date[5].$report_date[6];
$H_day = $report_date[8].$report_date[9];
$P_year = $printing_date[0].$printing_date[1].$printing_date[2].$printing_date[3];
$P_month = $printing_date[5].$printing_date[6];
$P_day = $printing_date[8].$printing_date[9];
if (isset($_SESSION['comp_id'])){
    $Access_level = $_SESSION['comp_id'];
}elseif (isset($_SESSION['comp'])){
    $Access_level = $_SESSION['comp'];
}else{
    $Access_level = '';
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,31,$Access_level);
if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[5];?>">
<?php }?>
<body>
<div class="content">
    <div class="row" id="content">
        <div id="hide">
            <button onclick="myFunction()">Print</button>
        </div>
        <div style="text-align: center;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:20%; max-width:330px;">
            <h2 style="margin-top: 0px;font-size: 22px; ">شعبہ حسابات</h2>
        </div>
    </div>
    <div class="row" style="margin-right: 64%;margin-top: -8%;">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">حوالہ نمبر<span style="margin-right: 5px;">:</span>
                    <input type="text" class="VoucherMOdal" value="<?= $this->input->post('serial')?>" style="width: 48%;" readonly>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">تاریخ<span style="margin-right: 2px;">:</span>
                    <?php if ($P_month == '09'){
                        $P_month_inwords = 'رمضان المبارک';
                    }elseif ($P_month == '10'){
                        $P_month_inwords = 'شوال';
                    }elseif ($P_month == '08'){
                        $P_month_inwords = 'شعبان المعظم';
                    }
                    if ($H_month == '09'){
                        $R_month_inwords = 'رمضان المبارک';
                    }elseif ($H_month == '10'){
                        $R_month_inwords = 'شوال';
                    }elseif ($H_month == '08'){
                        $R_month_inwords = 'شعبان المعظم';
                    }?>
                    <input type="text" value="<?= $P_day?>  <?=$P_month_inwords?> <?= $P_year?> ھ" class="VoucherMOdal" readonly style="height: 30px;width: 55%;margin-right: 9%;">
                </label>
            </div>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two" style="padding-top: 0px!important;">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row" style="margin-top: -4%;margin-right: -5%;">
                            <div class="col-md-12">
                                <h5>محترم حضرت ریئس الجامعہ مدظلہم العالی،جامعہ دارالعلوم کراچی</h5>
                                <?php if ($H_day == '01'){?>
                                    <h5>آنجناب کی خدمت میں رپورٹ برائے آمدنی مورخہ یکم رمضان المبارک <?=$H_year?>ھ  پیش ہے</h5>
                                <?php }else{ ?>
                                    <h5>آنجناب کی خدمت میں رپورٹ برائے آمدنی مورخہ یکم رمضان تا <?=$H_day?> <?= $R_month_inwords?> <?=$H_year?>ھ  پیش ہے</h5>
                                <?php }?>
                            </div>
                        </div><br>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table style="border: 2px solid black;" class="table-bordered" id="table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="text-align: center;font-weight: 300;color: black;border: 2px solid black!important;width:16%;">تفصیل</th>
                                        <th colspan="2" style="line-height: 27px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی از کورنگی مرکز</th>
                                        <th colspan="2" style="line-height: 27px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;"> آمدنی از نانکواڑہ مرکز</th>
                                        <th colspan="2" style="line-height: 27px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;"> آمدنی از گلشن اقبال مرکز</th>
                                        <th colspan="2" style="line-height: 27px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">کل آمدنی </th>
                                    </tr>
                                    <tr style="height: 73px">
                                        <?php   if ($this->input->post('multyday') == 1){ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی مورخہ <?= $H_day?> <?= $R_month_inwords?>* </th>
                                        <?php } else{ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی مورخہ <?= $H_day?> <?= $R_month_inwords?> </th>
                                        <?php } if ($H_month == '10'){?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم رمضان تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php } else{?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php } if ($this->input->post('multyday') == 1){ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی مورخہ <?= $H_day?> <?= $R_month_inwords?>*</th>
                                        <?php } else{ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی مورخہ <?= $H_day?> <?= $R_month_inwords?></th>
                                        <?php } if ($H_month == '10'){?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم رمضان تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php } else{?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php } if ($this->input->post('multyday') == 1){ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی مورخہ <?= $H_day?> <?= $R_month_inwords?>*</th>
                                        <?php } else{ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی مورخہ <?= $H_day?> <?= $R_month_inwords?></th>
                                        <?php } if ($H_month == '10'){?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم رمضان تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php }else{?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php } if ($this->input->post('multyday') == 1){ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">کل آمدنی مورخہ <?= $H_day?><?= $R_month_inwords?>*</th>
                                        <?php } else{ ?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">کل آمدنی مورخہ <?= $H_day?><?= $R_month_inwords?></th>
                                        <?php }if ($H_month == '10'){?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم رمضان تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php }else{?>
                                            <th style="line-height: 19px;text-align: center;font-weight: 300;color: black;border: 2px solid black!important;">آمدنی یکم تا <?= $H_day?> <?= $R_month_inwords?> <?=$H_year?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody  style="text-align: center;color: black;border: 2px solid black;">
                                <?php foreach($Maddat_Name as $key => $value){?>
                                    <tr>
                                        <td class="asas"><?= $value->Mad_name?></td>
                                        <?php if ($Today[0][$key][0]->Credit == 0.00){?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                        <?php }else{ $TCredit0 = $Today[0][$key][0]->Credit - $TodayR[0][$key][0]->Debit;?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($TCredit0)?></td>
                                        <?php } $today_sum[0] += $TCredit0?>

                                        <?php if ($TillToday[0][$key][0]->Credit == 0.00){?>
                                            <td class="borderzero" style="text-align: center;border: 0;"> - </td>
                                        <?php }else{ $TTCredit0 = $TillToday[0][$key][0]->Credit - $TillTodayR[0][$key][0]->Debit;?>
                                            <td class="borderzero" style="text-align: center;border: 0;"><?= number_format($TTCredit0)?></td>
                                        <?php } $till_today_sum[0] += $TTCredit0?>

                                        <?php if ($Today[2][$key][0]->Credit == 0.00){?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                        <?php }else{ $TCredit2 = $Today[2][$key][0]->Credit - $TodayR[2][$key][0]->Debit;?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($TCredit2)?></td>
                                        <?php }$today_sum[2] += $TCredit2?>

                                        <?php if ($TillToday[2][$key][0]->Credit == 0.00){?>
                                            <td class="borderzero" style="text-align: center;border: 0;"> - </td>
                                        <?php }else{ $TTCredit2 = $TillToday[2][$key][0]->Credit - $TillTodayR[2][$key][0]->Debit;?>
                                            <td class="borderzero" style="text-align: center;border: 0;"><?= number_format($TTCredit2)?></td>
                                        <?php }$till_today_sum[2] += $TTCredit2?>

                                        <?php if ($Today[1][$key][0]->Credit == 0.00){?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                        <?php }else{ $TCredit1 = $Today[1][$key][0]->Credit - $TodayR[1][$key][0]->Debit;?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($TCredit1)?></td>
                                        <?php }$today_sum[1] += $TCredit1?>

                                        <?php if ($TillToday[1][$key][0]->Credit == 0.00){?>
                                            <td class="borderleft" style="text-align: center;border-left: 2px solid black;border-bottom: 0;border-right: 0;border-top: 0;"> - </td>
                                        <?php }else{ $TTCredit1 = $TillToday[1][$key][0]->Credit - $TillTodayR[1][$key][0]->Debit;?>
                                            <td class="borderleft" style="text-align: center;border-left: 2px solid black;border-bottom: 0;border-right: 0;border-top: 0;"><?= number_format($TTCredit1)?></td>
                                        <?php }$till_today_sum[1] += $TTCredit1?>

                                        <?php $sum_Today = $TCredit0 + $TCredit1 + $TCredit2;?>
                                        <?php $sum_Till_Today = $TTCredit0 + $TTCredit1 + $TTCredit2;?>

                                        <?php if ($sum_Today == 0.00){?>
                                            <td class="borderright"  style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                        <?php }else{ ?>
                                            <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($sum_Today)?></td>
                                        <?php } $total_sum_today += $sum_Today?>

                                        <?php if ($sum_Till_Today == 0.00){?>
                                            <td class="borderzero" style="text-align: center;border: 0"> - </td>
                                        <?php }else{ ?>
                                            <td class="borderzero" style="text-align: center;border: 0"><?= number_format($sum_Till_Today)?></td>
                                        <?php } $total_sum_till_today += $sum_Till_Today;
                                        $sum_Till_Today = 0; $sum_Today = 0;
                                        $TCredit0 = 0; $TCredit1 = 0; $TCredit2 = 0;
                                        $TTCredit0 = 0; $TTCredit1 = 0; $TTCredit2 = 0;?>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tfoot>
                                <tr class="linehieght" style="color: black;">
                                    <td class="borderzero" style="font-weight: 300;border: 0;line-height: 25px;">میزان</td>
                                    <?php if ($today_sum[0] == 0){?>
                                        <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($today_sum[0])?></td>
                                    <?php }?>

                                    <?php if ($till_today_sum[0] == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($till_today_sum[0])?></td>
                                    <?php }?>

                                    <?php if ($today_sum[2] == 0){?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($today_sum[2])?></td>
                                    <?php }?>

                                    <?php if ($till_today_sum[2] == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($till_today_sum[2])?></td>
                                    <?php }?>

                                    <?php if ($today_sum[1] == 0){?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($today_sum[1])?></td>
                                    <?php }?>

                                    <?php if ($till_today_sum[1] == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($till_today_sum[1])?></td>
                                    <?php }?>

                                    <?php if ($total_sum_today == 0){?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($total_sum_today)?></td>
                                    <?php }?>

                                    <?php if ($total_sum_till_today == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($total_sum_till_today)?></td>
                                    <?php }?>
                                </tr>
                                <tr style="color: black;">
                                    <td class="borderzero" style="font-weight: 300;border: 0;line-height: 21px;">سال گزشتہ 1437</td>
                                    <?php if ($this->input->post('today0') == 0){?>
                                        <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="text-align: center;border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($this->input->post('today0'))?></td>
                                    <?php }?>

                                    <?php if ($this->input->post('tilltoday0') == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($this->input->post('tilltoday0'))?></td>
                                    <?php }?>

                                    <?php if ($this->input->post('today2') == 0){?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($this->input->post('today2'))?></td>
                                    <?php }?>

                                    <?php if ($this->input->post('tilltoday2') == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($this->input->post('tilltoday2'))?></td>
                                    <?php }?>

                                    <?php if ($this->input->post('today1') == 0){?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($this->input->post('today1'))?></td>
                                    <?php }?>

                                    <?php if ($this->input->post('tilltoday1') == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($this->input->post('tilltoday1'))?></td>
                                    <?php }?>
                                    <?php $total_last_year = $this->input->post('today0') + $this->input->post('today1') + $this->input->post('today2');
                                    $total_till_last_year = $this->input->post('tilltoday0') + $this->input->post('tilltoday1') + $this->input->post('tilltoday2');?>
                                    <?php if ($total_last_year == 0){?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderright" style="border-left: 1px dashed black;border-right: 2px solid black;border-bottom: 0;border-top: 0;"><?= number_format($total_last_year)?></td>
                                    <?php }?>

                                    <?php if ($total_till_last_year == 0){?>
                                        <td class="borderzero" style="border: 0"> - </td>
                                    <?php }else{ ?>
                                        <td class="borderzero" style="border: 0"><?= number_format($total_till_last_year)?></td>
                                    <?php }?>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                        <?php if ($this->input->post('multyday') == 1){ ?>
                            <span style="position: absolute;left: 0px;margin-top: 1%">  *مذکورہ بالا رپورٹ میں گزشتہ ۲ ایّام کی رقوم شامل ہیں۔</span>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10" style="margin-top: 1%">
                    <span style="color: black;">نوٹ:</span>
                    <div class="form-group" style="margin-bottom: 0px">
                        <textarea rows="6" cols="200" type="text" class="note" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;   width: 100%;overflow:hidden;margin-right: 11%;margin-top: -2%;line-height: 26px;font-size: 0.9em;color: black;"><?= $notes?></textarea>
                    </div>
                    <div class="form-group" style="margin-top: 3%">
                        <span style="color: black;">کاپی برائے:</span>
                        <span style="margin-right: 5%;color: black;">حضرت نائب ریئس الجامعہ مدظلہم العالی،جامعہ دارالعلوم کراچی</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<div class="PrintMessage" style="margin-right: 200px;margin-top: 500px;font-size: 2em">آپ اس دستاویز کو پرنٹ کرنے کے مجاز نہیں ہیں۔</div>-->
</body>
</html>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
    //    $( document ).ready(function() {
    //        $(".PrintMessage").hide();
    //    });
    //
    //    var AccessRights = $('#AccessRights').val();
    //    if (!(AccessRights == 1 || <?//=$_SESSION['user'][0]->id?>// == 1)){
    //        if ('matchMedia' in window) {
    //            // Chrome, Firefox, and IE 10 support mediaMatch listeners
    //            window.matchMedia('print').addListener(function(media) {
    //                if (media.matches) {
    //                    beforePrint();
    //                } else {
    //                    // Fires immediately, so wait for the first mouse movement
    //                    $(document).one('mouseover', afterPrint);
    //                }
    //            });
    //        } else {
    //            // IE and Firefox fire before/after events
    //            $(window).on('beforeprint', beforePrint);
    //            $(window).on('afterprint', afterPrint);
    //        }
    //
    //        function beforePrint() {
    //            $(".content").hide();
    //            $(".PrintMessage").show();
    //        }
    //
    //        function afterPrint() {
    //            $(".PrintMessage").hide();
    //            $(".content").show();
    //        }
    //    }else {
    //        $(".PrintMessage").hide();
    //        $(".content").show();
    //    }
</script>