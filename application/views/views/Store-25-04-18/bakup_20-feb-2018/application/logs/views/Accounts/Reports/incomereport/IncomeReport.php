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
        .two {
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
            * {
                overflow: visible !important;
            }

            body {
                width: 105%;
                height: auto;
                margin-right: -3%;
                padding: 0;
                margin-top: 1%;
                margin-bottom: 12%;
            }

            .footer {
                margin: 0px 650px -15px 0px;
                position: fixed;
                bottom: 0;
                display: inline;
            }

            table tbody tr td:before,
            table tbody tr td:after {
                content: "";
                height: 8px;
                display: block;
                orphans: 4;
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
} $rights = $this->AccessModel->check_access($_SESSION['user'][0]->id,18,$Access_level);
if($_SESSION['user'][0]->IsAdmin != 1 && $rights != array()){?>
    <input type="hidden" id="AccessRights" value="<?= $rights[0]->Rights[5];?>">
<?php }?>
<body>
<div class="content">
    <div class="row" id="content">
        <?php if($_SESSION['user'][0]->IsAdmin == 1 || ($rights != array() && $rights[0]->Rights[5] == '1')){?>
            <div id="hide">
                <button onclick="myFunction()">Print</button>
                <a href="<?= site_url('Accounts/IncomeReport/TotalAmountDesc/')?><?= $toE?>/<?= $fromE ?>/<?= $depart?>" target="_blank"><button type="button" >رقومات کی تفصیل</button></a>
            </div> <?php }?>
        <div style="text-align: center;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:42%; max-width:330px;">
            <h3 style="margin-top: 6px; text-decoration: underline; margin-bottom: 2%; ">اسٹینٹ کیشئر کی وصولیوں کی رپورٹ</h3>
            <div>
                <?php $sey=' سے '; $tk = ' تک ';?>
                <h4><p style="margin-right: -19%"><?= $to[0]->Sh_date?></p><p style="margin-right: 107px;margin-top: -29px;"><?= $to[0]->Qm_date;?></p></h4>
                <div style="margin-top: -3%;">
                    <br><span class="ta1" style="text-decoration: underline; margin-right: -2%; "> تا</span>
                    <span id="bamutabiq" style="text-decoration: underline; margin-right: 5%;">بمطابق</span>
                    <span class="ta" style="text-decoration: underline;  margin-right: 5%;"> تا</span><br>
                </div>
                <h4><p style="margin-right: -19%;"><?= $from[0]->Sh_date?></p><p style="margin-right: 107px;margin-top: -29px;"><?= $from[0]->Qm_date;?></p></h4>
            </div>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="margin-bottom: 5%; margin-right: -9%;">مرکزی دارالعلوم کرچی کی وصول شدہ آمدنی کی تفصیل</h5>
                            </div>
                        </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;">
                            <table class="table-bordered">
                                <thead style="line-height: 250%;text-align: center">
                                <th style="text-align: center">کوڈ</th>
                                <th style="text-align: center">شعبہ</th>
                                <th style="text-align: center">کیش رسید نمبر</th>
                                <th style="text-align: center">منتقل</th>
                                <th style="text-align: center">کلیرنگ</th>
                                <th style="text-align: center">آوٹ سٹی</th>
                                <th style="text-align: center">آن لائن</th>
                                <th style="text-align: center">چیک</th>
                                <th style="text-align: center">نقد</th>
                                <th style="text-align: center">کل رقم</th>
                                </thead>
                                <tbody>
                                <?php $count = 1; $t_Cash = 0; $total = 0; $t_cheque = 0; $tm_OD = 0; $t_Online = 0; $t_clearing = 0;
                                $t_outofcity = 0; $t_transfer = 0; $Cash = 0; $cheque = 0; $Online = 0; $outofcity = 0; $clearing = 0;
                                $transfer = 0; $tm_Tran = 0; $tm_CL = 0; $tm_OUT = 0; $tm_CH = 0; $tm_CASH = 0; $tm_Total = 0;
                                if($vouchers != array()){
                                foreach ($vouchers['department_name'] as $m_key => $voucher){
                                    foreach ($voucher as $key1 => $value){
                                        if ($m_key == 0){
                                            foreach ($value as $key2 => $item){
                                                foreach ($item as $key3 => $transactions){ ?>
                                                    <tr>
                                                        <td><?= $count?></td>
                                                        <td style="display: none"><?= (isset($vouchers['ChequeDetails'][$m_key][$key1][$key2][$key3][0]->Income_Id)? $vouchers['ChequeDetails'][$m_key][$key1][$key2][$key3][0]->Income_Id:'0')?></td>
                                                        <td style="width: 23%"><?= $transactions?></td>
                                                        <td style="width: 55%"><?= $vouchers['BookNo'][$m_key][$key1][$key2][$key3]?> : [<?= $vouchers['ReciptNo'][$m_key][$key1][$key2][$key3]?><span>]</span></td>
                                                        <!-- Start of ChequeType foreach -->
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){
                                                                if($ch_type->Cheque_Type == "transfer"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise); $t_transfer += $ch_type->ChequeAmountTypeWise;
                                                                }
                                                            } ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){?>
                                                                <?php if($ch_type->Cheque_Type == "clearing"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise)?>
                                                                    <?php $t_clearing += $ch_type->ChequeAmountTypeWise; } ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){?>
                                                                <?php if($ch_type->Cheque_Type == "outofcity"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise)?>
                                                                    <?php $t_outofcity += $ch_type->ChequeAmountTypeWise; } ?>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){?>
                                                                <?php if($ch_type->Cheque_Type == "deposit"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise); $t_Online += $ch_type->ChequeAmountTypeWise;
                                                                }
                                                            } ?>
                                                        </td>
                                                        <!-- End of ChequeType foreach -->

                                                        <td style="width: 10%"><?= number_format($vouchers['Cheque'][$m_key][$key1][$key2][$key3]->ChequeAmount);
                                                            $t_cheque += $vouchers['Cheque'][$m_key][$key1][$key2][$key3]->ChequeAmount;
                                                            ?></td>
                                                        <td style="width: 10%"><?= number_format($vouchers['Cash'][$m_key][$key1][$key2][$key3]);
                                                            $t_Cash += $vouchers['Cash'][$m_key][$key1][$key2][$key3];?></td>
                                                        <td style="width: 10%"><?= number_format($vouchers['Total'][$m_key][$key1][$key2][$key3]);
                                                            $total += $vouchers['Total'][$m_key][$key1][$key2][$key3];?></td>
                                                    </tr> <?php $Cash = 0;$cheque = 0;$Online = 0; $clearing = 0; $outofcity = 0; $transfer = 0; $count++;?>
                                                <?php   }
                                            }
                                        }
                                    }
                                }

                            }?>
                                </tbody>
                                <?php if (isset($m_key) && $m_key >= 0){ ?>
                                <tfoot>
                                <?php }else{ ?>
                                <tfoot style="display: none">
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>میزان</td>
                                    <td style="width: 10%"><?= number_format($t_transfer)?></td>
                                    <td style="width: 10%"><?= number_format($t_clearing)?></td>
                                    <td style="width: 10%"><?= number_format($t_outofcity)?></td>
                                    <td style="width: 10%"><?= number_format($t_Online)?></td>
                                    <td style="width: 10%"><?= number_format($t_cheque)?></td>
                                    <td style="width: 10%"><?= number_format($t_Cash)?></td>
                                    <td style="width: 10%"><?= number_format($total)?></td>
                                </tr>
                                <?php $tm_OD += $t_Online; $tm_Tran += $t_transfer;
                                $tm_CL += $t_clearing; $tm_OUT += $t_outofcity;
                                $tm_CH += $t_cheque; $tm_CASH += $t_Cash; $tm_Total += $total; ?>
                                </tfoot>
                            </table>
                        </div>
                        <?php if (!isset($depart)){?>
                            <div class="row" >
                                <div class="col-md-6">
                                    <h5 style="margin-bottom: 5%; margin-right: -9%; margin-top: 6%;">خود کیفل شعبہ جات سے وصول شدہ آمدنی کی تفصیل</h5>
                                </div>
                            </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;">
                            <?php }else{?>
                            <div class="table-responsive" align="middle" style="display:none ;width: 108%;text-align: center; margin-right: -5%;">
                            <?php }?>
                            <table class="table-bordered">
                                <tbody>
                                <?php $tkey = 0; $count = 1; $t_Cash1 = 0; $total1 = 0; $t_cheque1 = 0;
                                $t_Online1 = 0; $t_clearing1 = 0; $t_outofcity1 = 0; $t_transfer1 = 0;
                                $Cash1 = 0; $cheque1 = 0; $Online1 = 0; $outofcity1 = 0; $clearing1 = 0;
                                $transfer1 = 0; $tm_OD1 = 0; $tm_Tran1 = 0; $tm_CL1 = 0; $tm_OUT1 = 0;
                                $tm_CH1 = 0; $tm_CASH1 = 0; $tm_Total1 = 0; $m_key = 0;
                                foreach ($vouchers['department_name'] as $m_key => $voucher){
                                    foreach ($voucher as $key1 => $value){
                                        if ($m_key == 1){
                                            foreach ($value as $key2 => $item){
                                                foreach ($item as $key3=> $transactions){ ?>
                                                    <tr>
                                                        <td><?= $count?></td>
                                                        <td style="width: 16%"><?= $transactions?></td>
                                                        <td style="width: 17%"><?= $vouchers['BookNo'][$m_key][$key1][$key2][$key3]?> : <?= '['.$vouchers['ReciptNo'][$m_key][$key1][$key2][$key3].']'?></td>
                                                        <!-- Start of ChequeType foreach -->
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){
                                                                if($ch_type->Cheque_Type == "transfer"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise); $t_transfer1 += $ch_type->ChequeAmountTypeWise;
                                                                }
                                                            } ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){
                                                                if($ch_type->Cheque_Type == "clearing"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise); $t_clearing1 += $ch_type->ChequeAmountTypeWise;
                                                                }
                                                            } ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){
                                                                if($ch_type->Cheque_Type == "outofcity"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise);  $t_outofcity1 += $ch_type->ChequeAmountTypeWise;
                                                                }
                                                            } ?>
                                                        </td>
                                                        <td style="width: 10%">
                                                            <?php foreach($vouchers['ChequeType'][$m_key][$key1][$key2][$key3] as $ch_type){
                                                                if($ch_type->Cheque_Type == "deposit"){?>
                                                                    <?= number_format($ch_type->ChequeAmountTypeWise); $t_Online1 += $ch_type->ChequeAmountTypeWise;
                                                                }
                                                            } ?>
                                                        </td>
                                                        <!-- End of ChequeType foreach -->
                                                        <td style="width: 10%"><?= number_format($vouchers['Cheque'][$m_key][$key1][$key2][$key3]->ChequeAmount);
                                                            $t_cheque1 += $vouchers['Cheque'][$m_key][$key1][$key2][$key3]->ChequeAmount;
                                                            ?></td>
                                                        <td style="width: 10%"><?= number_format($vouchers['Cash'][$m_key][$key1][$key2][$key3]->CurrencyAmount);
                                                            $t_Cash1 += $vouchers['Cash'][$m_key][$key1][$key2][$key3]->CurrencyAmount;?></td>
                                                        <td style="width: 10%"><?= number_format($vouchers['Total'][$m_key][$key1][$key2][$key3]);
                                                            $total1 += $vouchers['Total'][$m_key][$key1][$key2][$key3];?></td>
                                                    </tr> <?php $Cash1 = 0; $cheque1 = 0; $Online1 = 0; $clearing1 = 0; $outofcity1 = 0; $transfer1 = 0; $count++;?>
                                                <?php   }
                                            }
                                        }
                                    }
                                }?>
                                <?php if ($m_key > 1){ ?>
                                <tfoot>
                                <?php }else{ ?>
                                <tfoot style="display: none">
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td>میزان</td>
                                    <td><?= number_format($t_transfer1)?></td>
                                    <td><?= number_format($t_outofcity1)?></td>
                                    <td><?= number_format($t_clearing1)?></td>
                                    <td><?= number_format($t_Online1)?></td>
                                    <td><?= number_format($t_cheque1)?></td>
                                    <td><?= number_format($t_Cash1)?></td>
                                    <td><?= number_format($total1)?></td>
                                </tr>
                                </tfoot>
                                <?php $tm_OD1 += $t_Online1; $tm_Tran1 += $t_transfer1;
                                $tm_CL1 += $t_clearing1; $tm_OUT1 += $t_outofcity1;
                                $tm_CH1 += $t_cheque1; $tm_CASH1 += $t_Cash1; $tm_Total1 += $total1; ?>
                            </table>
                        </div>

                        <br><br><br>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;">
                            <table class="table-bordered">
                                <?php $total_On = $tm_OD + $tm_OD1; $total_T = $tm_Tran + $tm_Tran1;
                                $total_C = $tm_CL + $tm_CL1; $total_O = $tm_OUT + $tm_OUT1;
                                $total_CH = $tm_CH + $tm_CH1; $total_CA = $tm_CASH + $tm_CASH1;
                                $total_tm_T = $tm_Total +$tm_Total1; ?>
                                <?php if ($count > 1){ ?>
                                <tfoot>
                                <?php }else{ ?>
                                <tfoot style="display: none">
                                <?php }?>
                                <tr>
                                    <td></td>
                                    <td style="width: 4%"></td>
                                    <td style="width: 36%">حتمی میزان</td>
                                    <td style="width: 27%"><?= number_format($total_T)?></td>
                                    <td style="width: 10%"><?= number_format($total_C)?></td>
                                    <td style="width: 10%"><?= number_format($total_O)?></td>
                                    <td style="width: 10%"><?= number_format($total_On)?></td>
                                    <td style="width: 10%"><?= number_format($total_CH)?></td>
                                    <td style="width: 10%"><?= number_format($total_CA)?></td>
                                    <td style="width: 10%"><?= number_format($total_tm_T)?></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class ="footer" id="pagebreak">
        <div style="direction: ltr;">
            <hr> <?php date_default_timezone_set('Asia/Karachi');?>
            <span style="float: left;"><?= date('l d-m-Y h:i:s');?></span>
            <hr>
            <input type="hidden" id="print" value="<?= $print;?>">
        </div>
    </footer>
    <div class="PrintMessage" style="margin-right: 200px;margin-top: 500px;font-size: 2em">آپ اس دستاویز کو پرنٹ کرنے کے مجاز نہیں ہیں۔</div>
</body>
</html>
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
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