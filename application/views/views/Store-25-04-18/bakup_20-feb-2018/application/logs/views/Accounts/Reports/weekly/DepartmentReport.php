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
            margin-right: 4%;
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
            .two{
                direction: rtl;
                width: 97%;
                margin-right: 4%;
                padding-top: 2%;
                line-height: 17px;
            }
        }
    </style>
</head>
<body>
<?php

$P_month_inwords = '';
$H_year = $report_date[0].$report_date[1].$report_date[2].$report_date[3];
$H_month = $report_date[5].$report_date[6];
$H_day = $report_date[8].$report_date[9];
$P_year = $printing_date[0].$printing_date[1].$printing_date[2].$printing_date[3];
$P_month = $printing_date[5].$printing_date[6];
$P_day = $printing_date[8].$printing_date[9];
$new_diff = 0;
$new_closing = 0; ?>

<div class="content">
    <div class="row" id="content">
        <div id="hide">
            <button onclick="myFunction()">Print</button>
        </div>
        <div style="text-align: center;margin-top: -2%;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:20%; max-width:330px;">
            <h4 style="margin-top: 8px; font-size: 14px;">رپورٹ براےَ وصولیاں و مصارف خود کفیل شعبہ جات </h4>
        </div>
    </div>
    <div class="row" style="margin-right: 68%;margin-top: -11%;margin-left: -10%;">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label">حوالہ نمبر<span style="margin-right: 5px;">:</span>
                    <input type="text" class="VoucherMOdal" value="<?= isset($serial) ? $serial : "" ?>" style="width: 48%;" readonly value="">
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
                        $P_month_inwords = 'رجب المرجب';
                    }?>
                    <input type="text" value="<?= $P_day?>  <?=$P_month_inwords?> <?= $P_year?> ھ" class="VoucherMOdal" readonly style="width: 50%;margin-right: 9%;">
                </label>
            </div>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body" style="margin-bottom: -5%;">
                        <div class="row" style="margin-top: -1%;margin-right: -5%;">
                            <div class="col-md-12">
                                <h5 style="font-weight: bold">محترم حضرت ریئس الجامعہ ظلہم العالی،جامعہ دارلعلوم کراچی</h5>
                                <h5 style="">رپورٹ برائے وصولیاں و مصارف و کیش وبینک بیلنس بابت <span style="font-weight: bold;"><?php echo$to;?></span>ھ تا <span style="font-weight: bold;"><?php echo$from;?></span>ھ پیش خدمت ہے</h5>
                            </div>
                        </div><br>
                    </div><br>
                    <div class="table-responsive" align="middle" style="width: 100%;text-align: center; margin-right: -0%;overflow-x: hidden;overflow-y: hidden;">
                        <table class="table-bordered">
                            <thead>
                            <tr>
                                <th style="text-align: center;width: 26%;">شعبہ جات </th>
                                <th style="text-align: center; ">ابتدایَ بیلنس از<br><?= $to ?></th>
                                <th style="text-align: center; ">آمدنی</th>
                                <th style="text-align: center; ">مصارف</th>
                                <th style="text-align: center; ">فرق</th>
                                <th style="text-align: center; ">اختتامی  بیلنس از<br><?= $from ?></th>
                                <?php if(isset($Maddat_Name[1]->Mad_name)):?>
                                    <th style="text-align: center; "><?= $Maddat_Name[1]->Mad_name?></th>
                                <?php endif?>
                            </tr>
                            </thead>
                            <tbody style="text-align: center;">
                            <?php $TotalIncome = 0;$TotalExpense = 0;$TotalDiff = 0;$TotalD1 = 0;$TotalD2 = 0;$TotalCapital = 0;$Nototal = 0;
                            foreach($IncTran as $key => $IncTransaction): ?>
                                <?php if(!($with_zero == 1 && $IncTransaction['ClosingBalance'][0] == 0 && $IncTransaction[0][0]->Credit == 0 && $IncTransaction[$key][0][0]->Debit == 0 && $IncTransaction['ClosingBalance'][1] == 0)):?>
                                    <tr>
                                        <td><?= $IncTransaction['LevelName']; ?></td>
                                        <?php if(is_array($IncTransaction['ClosingBalance'])): ?>
                                            <?php $ClosBal = $IncTransaction['ClosingBalance'][0] == 0 ? '-' : number_format($IncTransaction['ClosingBalance'][0]);?>
                                            <td style="text-align: center;"><?= $ClosBal; ?>
                                                <?php $TotalD2 += $IncTransaction['ClosingBalance'][0];?>
                                            </td>
                                        <?php else: ?>
                                            <td style="text-align: center;">-
                                            </td>
                                        <?php endif ?>
                                        <?php $Cred = $IncTransaction[0][0]->Debit == 0 ? '-' : number_format($IncTransaction[0][0]->Debit);?>
                                        <td style="text-align: center;"><?= $Cred; ?>
                                            <?php $TotalIncome += $IncTransaction[0][0]->Debit;?>
                                        </td>
                                        <?php $Deb = $IncTransaction[0][0]->Credit == 0 ? '-' : number_format($IncTransaction[0][0]->Credit);?>
                                        <td style="text-align: center;"><?= $Deb; ?>
                                            <?php $TotalExpense += $IncTransaction[0][0]->Credit;?>
                                        </td>
                                        <?php if ($IncTransaction['Difference'] < 0){
                                            $new_diff = ($IncTransaction['Difference'] * -1);?>
                                            <td style="text-align: center;">(<?= number_format($new_diff)?>)</td>
                                        <?php }else{ ?>
                                            <?php $Diff = $IncTransaction['Difference'] == 0 ? '-' : number_format($IncTransaction['Difference']);?>
                                            <td style="text-align: center;"><?= $Diff; ?>                                    </td>
                                        <?php }?>
                                        <?php $TotalDiff += $IncTransaction['Difference'];
                                        if ($IncTransaction['BankAddition'] < 0){
                                            $new_BankAddition = ($IncTransaction['BankAddition'] * -1);?>
                                            <td style="text-align: center;">(<?= number_format($new_BankAddition)?>)</td>
                                        <?php }else{ ?>
                                            <?php $Add = $IncTransaction['BankAddition'] == 0 ? '-' : number_format($IncTransaction['BankAddition']);?>
                                            <td style="text-align: center;"><?= $Add ?></td>
                                        <?php } $TotalD1 +=  $IncTransaction['BankAddition'];?>
                                        <?php if(is_array($IncTransaction['ClosingBalance'])): ?>
                                            <?php if(isset($IncTransaction['ClosingBalance'][1])): ?>
                                                <?php $ClosBal2 = $IncTransaction['ClosingBalance'][1] == 0 ? '-' : number_format($IncTransaction['ClosingBalance'][1]);?>
                                                <?php $Nototal = 1;?>
                                                <td style="text-align: center;"><?= $ClosBal2?></td>
                                                <?php $TotalCapital += $IncTransaction['ClosingBalance'][1]; ?>
                                            <?php endif?>
                                        <?php else: ?>
                                            <td style="text-align: center;">-</td>
                                        <?php endif ?>
                                    </tr>
                                <?php endif?>
                            <?php endforeach ?>
                            </tbody>
                            <tfoot style="line-height: 28px;">
                            <tr>
                                <th style="text-align: center;">میزان</th>
                                <th style="text-align: center;"><?= number_format($TotalD2); ?></th>
                                <th style="text-align: center;"><?= number_format($TotalIncome); ?></th>
                                <th style="text-align: center;"><?= number_format($TotalExpense); ?></th>
                                <?php if ($TotalDiff < 0){
                                    $new_TotalDiff = ($TotalDiff * -1);?>
                                    <th style="text-align: center;">(<?= number_format($new_TotalDiff)?>)</th>
                                <?php }else{ ?>
                                    <th style="text-align: center;"><?= number_format($TotalDiff)?></th>
                                <?php }?>
                                <th style="text-align: center;"><?= number_format($TotalD1); ?></th>
                                <?php if($Nototal == 1): ?>
                                    <?php $TotalCap = $TotalCapital == 0 ? '-' : number_format($TotalCapital);?>
                                    <th style="text-align: center;"><?= $TotalCap?></th>
                                <?php endif?>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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
    // $( document ).ready(function() {
    //     window.print();
    // });
    function myFunction() {
        window.print();
    }
</script>