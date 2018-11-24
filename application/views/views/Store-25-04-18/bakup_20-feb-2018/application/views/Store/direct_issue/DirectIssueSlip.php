<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>واؤچر</title>
    <style>
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
        .remarks{
            table-layout:fixed;
            width:90px;
            word-break:break-all;
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
    </div>
<div class="invoice-box" style="padding-bottom: 0px;">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr class="form-horizontal">
                        <td class="title">
                            <img src="<?= base_url()?>assets/images/logo.jpg" style="width:100%; max-width:300px;">
                                <p class="page-header vtitle">اسٹاک اجراء سلپ </p>
                        </td>
                        <td style="direction: rtl;float: right;">
                           
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="col-sm-2">
                                        <label class="col-sm-4 control-label">اسٹاک سلپ#<span style="margin-right: 11px;">:</span>
                                            <input type="text" value="<?= $IssueSlip[0]->Form_Number?>" class="form-control VoucherMOdal" style="margin-right: 19%" readonly>
                                            </label>
                                    </div>
                                </div> <br>
                            </div>
                            
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <div class="col-md-2">
                                        <label class="col-sm-4 control-label">عیسوی تاریخ اجراء دراسٹور<span style="margin-right: 5px;">:</span>
                                            <input type="text" class="VoucherMOdal" value="<?= $IssueSlip[0]->Issued_DateG?>" readonly >
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="col-sm-2">
                                        <label class="col-sm-4 control-label" style="margin-right:1%">ہجری تاریخ اجراء دراسٹور<span style="margin-right: 2%;">:</span>
                                            <input type="text" value="<?= $IssueSlip[0]->Issued_DateH?>" class="VoucherMOdal" readonly style="width: 168px;">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr class="information">
            <td colspan="2">
                <table>
                    <tr><?php $debit ='';?>
                        <td style="direction: rtl;float: left;">
                            <div class="form-group col-md-4 Jv" >
                                <div class="col-sm-2"> 
                                    <label class="col-sm-2 control-label">رقم ہندسوں میں :
                                        
                                        <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 230px;" value="<?= number_format($AmountInNumber)?>">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group col-md-4 Jv">
                                <div class="col-sm-2">
                                    <label class="col-sm-2 control-label">رقم عبارت میں :
                                        <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif;width: 230px;" value="<?= $AmountInWords?>">
                                    </label>
                                </div>
                            </div>
                        </td>
                        <td style="direction: rtl;float: right;">
                            <div class="form-group col-md-4">
                                <div class="col-sm-2">
                                    <label class="col-sm-2 control-label">مقام وتفصیل استعمال<span style="margin-right: 11px;">:</span>
                                        <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 240px;" value="<?= $IssueSlip[0]->LevelName.'-'.$IssueSlip[0]->DepartmentName?>">
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
                            <th style="width: 15%;border-top-left-radius: 25px;" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">قیمت</th>
                            <th style="width: 15%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">وزن/ناپ/تعداد</th>
                            <th style="width: 25%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">تفصیل</th>
                            <th style="" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">آئٹم کا نام</th>
                            <th style="width: 7%"colspan="1" bordercolorlight="#000000" bordercolordark="#000000">آئٹم کا کوڈ</th>
                            <th style="border-top-right-radius: 25px; width: 7%" colspan="1" bordercolorlight="#000000" bordercolordark="#000000">نمبر شمار</th>
                        </tr>
                        </thead>
                        <tbody style="text-align: center">
                        <?php $totalquantity = 0; $totalprice = 0;
                        foreach($IssueSlip as $key => $stock): ?>
                            <tr>
                                <td style="width: 15%" bordercolorlight="#000000" bordercolordark="#000000"><?= $viewstock[$key]->Item_price;
                                   $totalprice += $viewstock[$key]->Item_price;?></td>
                                <td style="width: 15%; text-align: center;" class="debit" style="text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"><?= $stock->Issue_Quantity;
                                  $totalquantity += $stock->Issue_Quantity; ?></td>
                                <td style="width: 15%" style="text-align: center;" class="remarks" bordercolorlight="#000000" bordercolordark="#000000"><?= $stock->Remarks?></td>
                                <td style="width: 20%" bordercolorlight="#000000" bordercolordark="#000000"><?= $stock->name?></td>
                                <td style="width: 7%" bordercolorlight="#000000" bordercolordark="#000000"><?= $stock->code?></td>
                                <td bordercolorlight="#000000" bordercolordark="#000000"><?= ++$key?></td>
                            </tr>
                        <?php endforeach ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <td style="border-bottom-left-radius: 25px;"><?=number_format($totalprice)?></td>
                            <td style="text-align: center"><?=number_format($totalquantity)?></td>
                            <td><b>کل</b></td>
                            <td></td>
                            <td style="width: 7%"></td>
                            <td style="border-bottom-right-radius: 25px;"></td>
                        </tr>
                        </tfoot>
                    </table>
                    
                    <table style="margin-top: -45px; ">
                        <br>
                        <br>
                        <br>
                        <tr style="direction: rtl;">
                            <td>
                                <div class="form-group col-md-2" style="float: left;width: 345px;">
                                    <label class="col-sm-1 control-label">اسٹور کیپر:
                                        <input type="text" class="VoucherMOdal" style="width: 218px;" readonly></label>
                                </div>
                            </td>
                            <td>
                                <div class="form-group col-md-2" style="float: left;width: 345px;">
                                    <label class="col-sm-1 control-label">دستخط وصول کنندہ:
                                        <input type="text" class="VoucherMOdal" style="width: 218px;" readonly></label>
                                </div>
                            </td>
                        </tr>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <footer class="footer">
        <div style="direction: ltr;" id="pagebreak">
            <hr> <?php date_default_timezone_set('Asia/Karachi');?>
            <?php if(is_null($Permanent_VoucherNumber)){?>
                <span> Unposted : <?= date('l d-m-Y h:i:s');?></span> &nbsp
            <?php } else{?>
                <span> Posted : <?= date('l d-m-Y h:i:s');?></span>&nbsp &nbsp
            <?php }?>
            <span> Prepared By: <?= $Createdby?></span>&nbsp &nbsp
            <span> On : <?= $CreatedOn?> </span>&nbsp &nbsp
            <?php if(isset($UpdatedBy)){?>
            <span> Updated By : <?= $UpdatedBy?></span>&nbsp &nbsp
            <span> On : <?= $UpdatedOn?></span>&nbsp &nbsp
            <?php }?>
            <hr>
        </div>
    </footer> -->
</div>
</body>
<script src="<?= base_url()."assets/js/"?>jquery-1.12.4.js"></script>
<script type="text/javascript">
function myFunction() {
    window.print();}

</script>
</html>