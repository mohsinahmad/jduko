<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>واؤچر</title>
    <style>
        @import url(http://fonts.googleapis.com/earlyaccess/notonastaliqurdu.css);
    </style>
    <style>
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
        .bp{
            border-collapse: collapse;
            border: 1px solid black;
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
            margin-top: 3px;
            font-size: 22px;
        }
    </style>
</head>
<body>

<div class="content">
    <div id="hide">
        <button onclick="myFunction()">Print</button>
    </div> <?php //print_r($vouch)?>
    <div class="invoice-box" style="padding-bottom: 0px;">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr class="form-horizontal">
                            <td class="title">
                                <img src="<?php echo base_url()?>assets/images/logo.jpg" style="width:100%; max-width:300px;">
                                <?php if (($vouch[0]->count)%2 != 0){
                                    if($vouch[0]->VoucherType == 'BR'){?>
                                        <p class="page-header vtitle">   بینک وصولی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'BP'){?>
                                        <p class="page-header vtitle">   بینک ادائیگی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'CP'){?>
                                        <p class="page-header vtitle">   کیش ادائیگی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'CR'){?>
                                        <p class="page-header vtitle">   کیش وصولی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'JV'){?>
                                        <p class="page-header vtitle">   جنرل جرنل  واؤچر </p>
                                    <?php }}else{
                                    if($vouch[0]->VoucherType == 'BR'){?>
                                        <p class="page-header vtitle">   بینک وصولی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'BP'){?>
                                        <p class="page-header vtitle">   بینک ادائیگی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'CP'){?>
                                        <p class="page-header vtitle">   کیش ادائیگی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'CR'){?>
                                        <p class="page-header vtitle">   کیش وصولی  واؤچر </p>
                                    <?php }elseif($vouch[0]->VoucherType == 'JV'){?>
                                        <p class="page-header vtitle">   جنرل جرنل  واؤچر </p>
                                    <?php }}?>
                            </td>
                            <td style="direction: rtl;float: right;">
                                <?php if (($vouch[0]->count)%2 != 0){?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="col-sm-2">
                                                <label class="col-sm-4 control-label">واؤچر نمبر<span style="margin-right: 11px;">:</span>
                                                    <input type="text" value="  <?php echo $vouch[0]->VoucherType; ?>-<?php echo $vouch[0]->VoucherNo?>" class="form-control VoucherMOdal" readonly>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="col-md-2">
                                                <label class="col-sm-4 control-label">ہجری تاریخ<span style="margin-right: 5px;">:</span>
                                                    <input type="text" class="VoucherMOdal" value="  <?php echo $vouch[0]->VoucherDateH?>" readonly >
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="col-sm-2">
                                                <label class="col-sm-4 control-label">شمسی تاریخ<span style="margin-right: 2px;">:</span>
                                                    <input type="text" value="  <?php echo $vouch[0]->VoucherDateG?>" class="VoucherMOdal" readonly style="    width: 162px;">
                                                </label>
                                            </div>
                                        </div> <br>
                                        <div style="border:2px solid #d8d6d6" class="Jv" >
                                            <div class="form-group col-md-6">
                                                <div class="col-sm-2">
                                                    <label class="col-sm-4 control-label">مستقل واؤچر#<span style="margin-right: 2px;">:</span>
                                                        <input type="text" value="" class="" readonly style="    width: 162px;">
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <div class="col-sm-2">
                                                    <label class="col-sm-4 control-label">خزانچی واؤچر#<span style="margin-right: 2px;">:</span>
                                                        <input type="text" value="" class="" readonly style="    width: 162px;">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }else{?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <div class="col-sm-2">
                                                <label class="col-sm-4 control-label"> واؤچر نمبر<span style="margin-right: 15px;">:</span>
                                                    <input type="text" value="  <?php echo $vouch[0]->VoucherType; ?>-<?php echo $vouch[0]->Permanent_VoucherNumber?>" class="form-control VoucherMOdal" readonly>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="col-md-2">
                                                <label class="col-sm-4 control-label">ہجری تاریخ<span style="margin-right: 5px;">:</span>
                                                    <input type="text" class="VoucherMOdal" value="  <?php echo $vouch[0]->Permanent_VoucherDateH?>" readonly >
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <div class="col-sm-2">
                                                <label class="col-sm-4 control-label">شمسی تاریخ<span style="margin-right: 2px;">:</span>
                                                    <input type="text" value="  <?php echo $vouch[0]->Permanent_VoucherDateG?>" class="VoucherMOdal" readonly style="    width: 162px;">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                <?php }?>
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
                                <div class="form-group col-md-4 Jv" >
                                    <div class="col-sm-2"> <?php $debit ='';$ChequeDate ='';$ChequeNumber ='';?>
                                        <label class="col-sm-2 control-label">رقم ہندسوں میں :
                                            <?php foreach($vouch as $acc): ?>
                                                <?php if($acc->Type == 1 || $acc->Type == 2): if ($acc->Type == 2){ $ChequeDate = $acc->ChequeDate; $ChequeNumber = $acc->ChequeNumber; }?>
                                                    <?php if($vouch[0]->VoucherType == 'BR' || $vouch[0]->VoucherType == 'CR'):?>
                                                        <?php $debit += $acc->Debit; ?>
                                                    <?php endif; ?>
                                                    <?php if($vouch[0]->VoucherType == 'BP' || $vouch[0]->VoucherType == 'CP'): ?>
                                                        <?php $debit += $acc->Credit; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                                <?php if($vouch[0]->VoucherType == 'JV'): ?>
                                                    <?php if($acc->Credit == 0.00): ?>
                                                        <?php $debit += $acc->Debit; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach ?>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 230px;" value="<?php echo @number_format($debit,2);?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4 Jv">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">رقم عبارت میں :
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif;width: 230px;" value="<?php echo $AmountInWords;?>">
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td style="direction: rtl;float: right;">
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">شعبہ<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 240px;" value="  <?php echo $vouch[0]->LevelName?>  -  <?php echo $vouch[0]->DepartmentName?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-4">
                                    <div class="col-sm-2">
                                        <label class="col-sm-2 control-label">بنام<span style="margin-right: 11px;">:</span>
                                            <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 242px;" value="  <?php echo $vouch[0]->PaidTo?>">
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="direction: rtl;float: right;width: 100%;margin-right: -97px;padding-bottom: 0px;">
                                <div class="form-group ">
                                    <div class="">
                                        <label class="col-sm-2 control-label">بابت<span style="margin-right: 5px;">:</span>
                                            <textarea rows="1" cols="75" type="text" class="VoucherMOdal" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;   width: 70%;"><?= $vouch[0]->Remarks?></textarea>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="panel panel-default" style="margin-top: -60px;">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table style="border-radius: 25px;color: black; font-size: 13px;margin-top: 75px" border="1" height="10" class="table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 15%;border-top-left-radius: 25px;" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">کریڈٹ</th>
                                <th style="width: 15%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">ڈیبٹ</th>
                                <th style="width: 20%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">کھاتا نمبر</th>
                                <?php if ($vouch[0]->Comp_Parent != 102){?>
                                    <th style="" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">مدات</th>
                                <?php }else{?>
                                    <th style="" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">مدات</th>
                                <?php }?>
                                <th style="border-top-right-radius: 25px;" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">تفصیل کھاتہ</th>
                            </tr>
                            </thead>
                            <tbody style="text-align: center">
                            <?php foreach($vouch as $acc): ?>
                                <tr>
                                    <td style="width: 15%" bordercolorlight="#000000" bordercolordark="#000000"><?php echo number_format($acc->Credit,2); ?></td>
                                    <td style="width: 15%;display: none" class="credit" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $acc->Credit; ?></td>
                                    <td style="width: 15%;display: none;" class="debit" style="text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $acc->Debit; ?></td>
                                    <td style="width: 15%" style="text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"><?php echo number_format($acc->Debit,2); ?></td>
                                    <td style="width: 20%" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $acc->AccountCode; ?></td>
                                    <?php if ($vouch[0]->Comp_Parent != 102){?>
                                        <td bordercolorlight="#000000" bordercolordark="#000000"><?php echo $acc->ParentName ?></td>
                                    <?php }else{?>
                                        <td bordercolorlight="#000000" bordercolordark="#000000"></td>
                                    <?php }?>
                                    <td bordercolorlight="#000000" bordercolordark="#000000"><?php echo $acc->AccountName?></td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <td style="border-bottom-left-radius: 25px;"><span id="totalc">0</span></td>
                                <td style="text-align: center"><span id="totald">0</span></td>
                                <td></td>
                                <td></td>
                                <td style="border-bottom-right-radius: 25px;"><b>:کل</b></td>
                            </tr>
                            </tfoot>
                        </table>
                        <?php if ($vouch[0]->VoucherType == 'CR' || $vouch[0]->VoucherType == 'BR') {?>
                            <table style="margin-top: -45px; ">
                                <br>
                                <br>
                                <br>
                                <tr style="direction: rtl;">
                                    <td>
                                        <div class="form-group col-md-2" style="float: left;width: 345px;">
                                            <label class="col-sm-1 control-label">ڈپٹی چیف اکاؤنٹنٹ:
                                                <input type="text" class="VoucherMOdal" style="width: 218px;" readonly></label>
                                        </div>
                                    </td>
                                    <td style="width: 360px;">
                                        <div class="form-group col-md-4" style="float: right">
                                            <label class="col-sm-2 control-label">اکاؤنٹنٹ:
                                                <input type="text" class="VoucherMOdal" style="width: 218px;" readonly></label>
                                        </div>
                                    </td>
                                </tr>
                            </table><?php }else{?>
                            <table style="margin-top: -45px;">
                                <br>
                                <br>
                                <br>
                                <tr style="direction: rtl;" class="Jv">
                                    <td style="width: 577px;">
                                        <div class="form-group col-md-4"  style="float: left">
                                            <label class="col-sm-2 control-label">دستخط وصول کنندہ:
                                                <input type="text" class="VoucherMOdal" readonly></label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group col-md-4" style="float: right">
                                            <label class="col-sm-2 control-label">کیشیئر:
                                                <input type="text" class="VoucherMOdal" readonly style="width: 153px;"></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr style="direction: rtl;" class="info">
                                    <td class="td1">
                                        <div class="form-group col-md-2 dfter" style="float: left;width: 312px;">
                                            <label class="col-sm-1 control-label">دستخط رئيس الجامعہ:
                                                <input type="text" class="VoucherMOdal" readonly></label>
                                        </div>
                                        <div class="form-group col-md-2 td1-1" style="float: right;width: 262px;">
                                            <label class="col-sm-1 control-label">ڈپٹی چیف اکاؤنٹنٹ:
                                                <input type="text" class="VoucherMOdal" style="width: 135px;" readonly></label>
                                        </div>
                                    </td>
                                    <td style="width: 200px;" class="td2">
                                        <div class="form-group col-md-4" style="float: right">
                                            <label class="col-sm-2 control-label">اکاؤنٹنٹ:
                                                <input type="text" class="VoucherMOdal" style="width: 131px;" readonly></label>
                                        </div>
                                    </td>
                                </tr>
                                <?php if($vouch[0]->VoucherType == 'BP'){?>
                                    <tr style="direction: rtl" class="bp">
                                        <td class="td1" style="">
                                            <div class="form-group col-md-2" style="float: left;">
                                                <label class="col-sm-1 control-label">(2)
                                                    <input type="text" class="VoucherMOdal" style="width: 120px;" readonly>
                                                </label>
                                            </div>
                                            <div class="form-group col-md-2 td1-1" style="float: left;margin-left: 15px;">
                                                <label class="col-sm-1 control-label">(1)
                                                    <input type="text" class="VoucherMOdal" style="width: 120px;" readonly></label>
                                            </div>
                                            <div class="form-group col-md-4" style="float: left;margin-left: 15px;">
                                                <label class="col-sm-2 control-label">تاریخ:
                                                    <input type="text" class="VoucherMOdal" value="<?= $ChequeDate?>" style="width: 100px;" readonly></label>
                                            </div>
                                        </td>
                                        <td style="" class="td2">
                                            <div class="form-group col-md-4" style="position: absolute;margin-top: -1%;">
                                                <label class="col-sm-2 control-label">دستخط از مجاز برچیک نمبر:
                                                    <input type="text" class="VoucherMOdal" value="<?= $ChequeNumber?>" style="text-align: center" readonly></label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }?>
                            </table>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
    </div>
        <footer class="footer">
            <div style="direction: ltr;" id="pagebreak">
                <hr> <?php date_default_timezone_set('Asia/Karachi');?>
                <?php if(is_null($Permanent_VoucherNumber)){?>
                    <span> Unposted : <?php echo date('l d-m-Y h:i:s');?></span> &nbsp
                <?php } else{?>
                    <span> Posted : <?php echo date('l d-m-Y h:i:s');?></span>&nbsp &nbsp
                <?php }?>
                <span> Prepared By: <?php echo $Createdby?></span>&nbsp &nbsp
                <span> On : <?php echo $CreatedOn?> </span>&nbsp &nbsp
                <?php if(isset($UpdatedBy)){?>
                    <span> Updated By : <?php echo $UpdatedBy?></span>&nbsp &nbsp
                    <span> On : <?php echo $UpdatedOn?></span>&nbsp &nbsp
                <?php }?>
                <hr>
            </div>
        </footer>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?php echo base_url()."assets/";?>js/accounting.min.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
    $(document).ready(function () {
        //window.print();
        var dsum = 0;
        var csum = 0;
        var credit = 0;
        var debit = 0;

        $('.table-bordered tr').each(function () {
            $(this).find('.debit').each(function () {
                var debit = $(this).text();
                if (!isNaN(debit) && debit.length !== 0) {
                    dsum += parseFloat(debit);
                }
            });$('#totald').text(accounting.formatMoney(dsum));

            $(this).find('.credit').each(function () {
                var credit = $(this).text();
                if (!isNaN(credit) && credit.length !== 0) {
                    csum += parseFloat(credit);
                }
            });$('#totalc').text(accounting.formatMoney(csum));
        });

        var type = '<?php echo $vouch[0]->VoucherType; ?>';

        if(type == "JV"){
            $('.Jv').hide();
//            $('.vtitle').attr('style','margin-bottom: 0px;');
//            $('.title').attr('style','padding-bottom: 0px;');
        }else{
            $('.Jv').show();
        }

    });
</script>