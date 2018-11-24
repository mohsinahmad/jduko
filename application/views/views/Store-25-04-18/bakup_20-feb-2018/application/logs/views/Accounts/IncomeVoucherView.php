<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>واؤچر</title>
    <style type="text/css">
        @font-face {
            font-family: "Noto Nastaliq Urdu";
            src: url(<?= base_url().'assets/'?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
        }
        @font-face {
            font-family: 'Glyphicons Halflings';
            src: url(<?= base_url().'assets/'?>fonts/glyphicons-halflings-regular.woff) format("woff");
            src: url(<?= base_url().'assets/'?>fonts/glyphicons-halflings-regular.ttf) format("truetype");
        }
        td,tr,th {
            height: 35px;
        }
        .content{
            border:0px solid #eee;
            box-shadow:0 0 10px rgba(0, 0, 0, .15);
            max-width:800px;
            margin:auto;
            padding:20px;
            font-size:15px;
            line-height:24px;
            font-family: 'Noto Nastaliq Urdu', serif;
        }
        .invoice-box table{
            width:100%;
            line-height:inherit;
            text-align:center;
        }

        .invoice-box table td{
            padding:5px;
        }
        .invoice-box table tr td:nth-child(2){
            text-align:right;
        }
        .invoice-box table tr.top table td{
            padding-bottom:20px;
        }
        .invoice-box table tr.top table td.title{
            font-size:45px;
            line-height:45px;
            color:#333;
        }
        .invoice-box table tr.information table td{
            padding-bottom:40px;
        }
        .invoice-box table tr.heading td{
            background:#eee;
            border-bottom:1px solid #ddd;
            font-weight:bold;
        }
        .invoice-box table tr.details td{
            padding-bottom:20px;
        }
        .invoice-box table tr.item td{
            border-bottom:1px solid #eee;
        }
        .invoice-box table tr.item.last td{
            border-bottom:none;
        }
        .invoice-box table tr.total td:nth-child(2){
            border-top:2px solid #eee;
            font-weight:bold;
        }
        .footer {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            padding: 0rem;
            /* background-color: #efefef;*/
            text-align: center;
        }
        #bp-table{
            border: 1px solid black;
            border-collapse: collapse;
        }

        /*@media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
        }
        .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
        }
    }*/

        @media print{
            #hide{
                display: none !important;
            }
            .invoice-box table tr.info{
                margin-top: -45px;
            }
            .invoice-box table tr.info .td1 .td2{
                display: inline-block;
            }
            .content{
                box-shadow: none;
                margin-right: -10%;
                padding-left: -3%;
            }
        }
    </style>
    <style type="text/css">
        .VoucherMOdal {
            margin-right: 10px;
            border: 0;
            outline: 0;
            background: transparent;
            border-bottom: 1px solid black;
        }
        .vtitle{
            margin-top: -17px;
            font-size: 22px;
        }
    </style>
</head>
<body>
<div class="content">
    <div id="hide">
        <button onclick="myFunction()">Print</button>
    </div>
    <div class="invoice-box" style="padding-bottom: 0px;">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr class="form-horizontal">
                            <td style="direction: rtl;float: right;margin-right: 16%;">
                                <div class="form-group col-md-3">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">رسید بک بنام<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 240px;font-weight: bold;font-size: 1em" value="<?= $Createdby?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">جلد نمبر<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 245px; margin-right: 11%;font-weight: bold;font-size: 1em" value="<?= $vouch[0]->BookNo;?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">رسیدات نمبر<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 242px; margin-right: 2%;font-weight: bold;font-size: 1em" value="<?= $vouch[0]->ReciptNo;?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">واوچر نمبر<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 242px; margin-right: 2%;font-weight: bold;font-size: 1em" value="<?= $vouch[0]->VoucherType.'-'.$vouch[0]->VoucherNo;?>">
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td class="title">
                                <img src="<?= base_url()?>assets/images/logo.jpg" style="width:100%; max-width:300px;">
                                <h6 class="page-header vtitle" style="margin-right: 18%;margin-top: -7px;">  مدات جمع شدہ آمدنی </h6>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td style="direction: rtl;float: left;">
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">رقم (ہندسوں میں) :
                                            <?php $debit =''; foreach ($vouch as $acc){ $debit += $acc->Debit; } ?>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 230px;font-size: 1.2em;font-weight: 800;" value="<?= '=/'.number_format($debit);?> . Rs ">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">رقم (عبارت میں) :
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif;width: 230px;font-weight: bold;" value="<?= $AmountInWords;?>">
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <?php $submit_date = strtotime($vouch[0]->DepositDateG)?>
                            <td style="direction: rtl;float: right;padding-bottom: 0px;">
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">آمدنی برائے شعبہ<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" style="font-family: 'Noto Nastaliq Urdu', serif; width: 178px;font-weight: bold;font-size: 1em" value="<?=$vouch[0]->DepartmentName?>" readonly>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td style="direction: rtl;float: right;">
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">رقم جمع کروانے کی تاریخ<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 178px;font-weight: bold;font-size: 1em" value="<?= date('d-m-Y' , $submit_date);;?> ">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">ڈپازٹ سلپ نمبر<span style="margin-right: 5px;">:</span>
                                            <?php $deposit = 0;
                                            foreach ($vouch as $value) {
                                                if ($value->DepositSlipNo != '') {
                                                    $deposit = $value->DepositSlipNo; } }
                                            if(!$deposit){?>
                                                <input type="text" class="VoucherMOdal" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;   width: 226px;" value="">
                                            <?php }else{?>
                                                <input type="text" class="VoucherMOdal" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;width: 226px;font-weight: bold;font-size: 1em" value="<?= $deposit;?>">
                                            <?php }?>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table style="border-radius: 25px;color: black; font-size: 13px;margin-top: 10px;" border="1" height="10" class="table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 15%;border-top-left-radius: 25px;" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">کریڈٹ</th>
                                <th style="width: 15%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">ڈیبٹ</th>
                                <th style="width: 20%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">تفصیل مدات آمدنی</th>
                                <th style="width: 15%;border-top-right-radius: 25px;" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">نمبر شمار</th>
                            </tr>
                            </thead>
                            <tbody style='text-align: center'>
                            <?php $cash = 0;$cheque = 0;$totald = 0;$totalc = 0;
                            foreach ($vouch as $key => $acc){ ?>
                                <tr style = 'font-weight: bold;'>
                                    <?php if ($acc->Credit != 0){ ?>
                                        <td style="width: 15%; font-size: 1.2em;font-weight: 700;" bordercolorlight="#000000" bordercolordark="#000000"><?=number_format($acc->Credit);?></td>
                                    <?php }else{ ?>
                                        <td style="width: 15%; font-size: 1.2em;font-weight: 700;" bordercolorlight="#000000" bordercolordark="#000000"></td>
                                    <?php }?>
                                    <?php $totald +=$acc->Debit;
                                    if ($acc->Debit != 0){ ?>
                                        <td style="width: 15%;font-size: 1.2em;font-weight: 700;text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"><?= number_format($acc->Debit);?></td>
                                    <?php }else{ ?>
                                        <td style="width: 15%;font-size: 1.2em;font-weight: 700;text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"></td>
                                    <?php }?>
                                    <?php $totalc +=$acc->Credit;?>
                                    <td style="width: 15%;font-size: 1.2em;font-weight: 700;" bordercolorlight="#000000" bordercolordark="#000000"><?= $acc->ParentName.' - ' .$acc->AccountName;?></td>
                                    <td style="width: 15%;font-size: 1.2em;font-weight: 700;" bordercolorlight="#000000" bordercolordark="#000000"><?= $key+1;?></td>
                                </tr>
                                <?php if ($acc->DepositType == 0) {
                                    $cash += $acc->Credit;
                                }else{
                                    $cheque += $acc->Credit;
                                }?>
                            <?php }?>
                            </tbody>
                            <tfoot style = 'font-weight: bold;'>
                            <tr>
                                <td style="border-bottom-left-radius: 25px;font-size: 1.2em;font-weight: 700;"><?= number_format($totalc);?></td>
                                <td style="font-size: 1.2em;font-weight: 700;text-align: center;"><?= number_format($totald);?></td>
                                <td style="font-size: 1.2em;font-weight: 700;"><b>کل</b></td>
                                <td></td>
                            </tr>
                            </tfoot>
                        </table>
                        <table>
                            <tr style="direction: rtl;">
                                <td>
                                    <div class="form-group col-md-2" style="float: left;width: 345px;">
                                        <label class="col-sm-1 control-label">نقد:
                                            <input type="text" class="VoucherMOdal" value="<?=number_format($AmountDescriptionCash->CurrencyAmount);?>" style="width: 218px;font-weight: bold;font-size: 1em" readonly></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group col-md-2" style="float: left;width: 345px;">
                                        <label class="col-sm-1 control-label">چیک:
                                            <input type="text" class="VoucherMOdal" value="<?=number_format($AmountDescriptionCheque->ChequeAmount);?>" style="width: 218px;font-weight: bold;font-size: 1em" readonly></label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <table style="margin-top: -45px;">
                            <br>
                            <br>
                            <br>
                            <tr style="direction: rtl;">
                                <td>
                                    <div class="form-group col-md-2" style="float: left;width: 345px;">
                                        <label class="col-sm-1 control-label">کیشئر:
                                            <input type="text" class="VoucherMOdal" style="width: 218px;" readonly></label>
                                    </div>
                                </td>
                                <td style="width: 360px;">
                                    <div class="form-group col-md-4" style="float: right">
                                        <label class="col-sm-2 control-label">نمائندہ استقبالیہ:
                                            <input type="text" class="VoucherMOdal" style="width: 187px;" readonly></label>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <!-- <table style="margin-top: -45px;">
                            <br>
                            <br>
                            <br>
                            <tr style="direction: rtl;">
                                <td style="width: 577px;">
                                    <div class="form-group col-md-4"  style="float: left; margin-top: -6%">
                                        <label class="col-sm-2 control-label">تاریخ:
                                            <input type="text" class="VoucherMOdal" readonly></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group col-md-4" style="float: right; margin-top: -16%">
                                        <label class="col-sm-2 control-label">تاریخ:
                                            <input type="text" class="VoucherMOdal" readonly style="width: 153px;"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr style="direction: rtl;">
                                <td>
                                    <div class="form-group col-md-2" style="float: left;width: 312px;">
                                        <label class="col-sm-1 control-label">دستخط ناظم شعبہ:
                                            <input type="text" class="VoucherMOdal" readonly></label>
                                    </div>
                                    <div class="form-group col-md-2" style="float: right;width: 262px;">
                                        <label class="col-sm-1 control-label">کیشئر:
                                            <input type="text" class="VoucherMOdal" style="width: 135px;" readonly></label>
                                    </div>
                                </td>
                                <td style="width: 200px;">
                                    <div class="form-group col-md-4" style="float: right">
                                        <label class="col-sm-2 control-label">نمائندہ استقبالیہ:
                                            <input type="text" class="VoucherMOdal" style="width: 100px;" readonly></label>
                                    </div>
                                </td>
                            </tr>
                        </table>
     -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--    <footer class="footer">-->
    <!--        <div style="direction: ltr;" id="pagebreak">-->
    <!--            <hr> --><?php //date_default_timezone_set('Asia/Karachi');?>
    <!--            --><?php //if(is_null($Permanent_VoucherNumber)){?>
    <!--                <span> Unposted : --><?php //echo date('l d-m-Y h:i:s');?><!--</span> &nbsp-->
    <!--            --><?php // } else{?>
    <!--                <span> Posted : --><?php //echo date('l d-m-Y h:i:s');?><!--</span>&nbsp &nbsp-->
    <!--            --><?php //}?>
    <!--            <span> Prepared By: --><?php //echo $Createdby?><!--</span>&nbsp &nbsp-->
    <!--            <span> On : --><?php //echo $CreatedOn?><!-- </span>&nbsp &nbsp-->
    <!--            --><?php //if(isset($UpdatedBy)){?>
    <!--                <span> Updated By : --><?php //echo $UpdatedBy?><!--</span>&nbsp &nbsp-->
    <!--                <span> On : --><?php //echo $UpdatedOn?><!--</span>&nbsp &nbsp-->
    <!--            --><?php //}?>
    <!--            <hr>-->
    <!--        </div>-->
    <!--    </footer>-->
</div>
</body>
</html>
<script src="<?= base_url()."assets/";?>js/jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/";?>js/accounting.min.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
</script>