
<!doctype html>
<html xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="utf-8">
    <title>واؤچر</title>
    <style>
        @import url(http://fonts.googleapis.com/earlyaccess/notonastaliqurdu.css);
    </style>
    <style>


        table{

            border-radius: 0px;
            color: black;
            /* font-size: 13px; */
            margin-top: 40px;
            border-collapse: collapse;

        }
        #invoice_table{

            width:100%;

        }
        #invoice_table th,#invoice_table td {

            border:1px solid black;


        }
        #invoice_table th{

            padding:10px;

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
            font-size: 30px;
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
                            <?php if($status == 1){?>
                                <p class="page-header vtitle"> مو صول شدہ فارم </p>
                            <?php }else{?>
                                <p class="page-header vtitle"> اجراء فارم </p>
                                <?php }?>
                            </td>
                            <td style="direction: rtl;float: right;">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <div class="col-md-2">
                                            <label class="col-sm-4 control-label">ہجری تاریخ<span style="margin-right: 5px;">:</span>
                                                <input type="text" class="VoucherMOdal" value="<?php echo $IssueSlip[0]->Issued_DateH?>" readonly >
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group col-md-6">
                                        <div class="col-sm-2">
                                            <label class="col-sm-4 control-label">شمسی تاریخ<span style="margin-right: 2px;">:</span>
                                                <input type="text" value="<?php echo $IssueSlip[0]->Issued_DateG?>" class="VoucherMOdal" readonly style="width: 162px;">
                                            </label>
                                        </div>
                                    </div> <br>

                                </div>
                                <p class="page-header vtitle" style="float: right;font-size: 20px;"><span>
 <?php if($status == 1){?>
     <span> مو صول شدہ </span>
 <?php }else{?>
     <span> اجراء </span>
 <?php }?>
                                    </span> فارم نمبر:<?php
                                    echo $this->uri->segment(4);
                                    ?>
                                </p>
                                <span>--</span>
                                <span style="font-size: 20px;">ڈیمانڈ نمبر:<?php
                                    echo $demand_no;
                                    ?>
                                </span>
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
                      <center>
                        <table id="invoice_table"  class="table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 60%;">کیفیت</th>
                                <th style="width: 10%;">پیماَش</th>
                                <th style="width: 9%;"> مقدار</th>
                                <th style="width: 40%;">نام اشیاء</th>
                                <th>نمبرشمار</th>
                            </tr>
                            </thead>
                            <tbody style="text-align: center">
                            <?php $totalissue = 0; ?>
                            <?php foreach($IssueSlip as $key => $value):?>
                                <tr>
                                    <td class="remarks" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $value->Remarks?></td>
                                    <td style=" text-align: center" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $value->unit?></td>
                                    <td style="text-align: center;" class="" style="text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $value->Approve_Quantity;
                                       // $totalissue += $ApproveQuantity[$key]->Approve_Quantity;
                                        ?></td>
                                    <td style="text-align: center;" bordercolorlight="#000000" bordercolordark="#000000"><?php echo $value->name?></td>
                                    <td  bordercolorlight="#000000" bordercolordark="#000000"><?php echo ++$key?></td>
                                </tr>
                            <?php endforeach?>
                            </tbody>
                            <!--<tfoot>
                            <tr>
                                <td style="text-align: center;border-bottom-left-radius: 25px">
                                <td style="text-align: center;"><?php //number_format($totalissue,2)?></td>
                                <td style=""><b>:کل</b></td>
                                <td style="border-bottom-right-radius: 25px;"></td>
                            </tr>
                            </tfoot>-->
                        </table>
                      </center>
                          <p style="margin-left: 10%">میں تصد یق کرتا ہوں کہ مذکورہ بالا سامان خالصتادارلعلوم کراچی کے مقاصد کے لیے استعمال کیا گیا ہے اور اسکے جملہ اندراجات درست ہیں۔</p>

                        <table style="margin-top: -45px; ">
                            <br>
                            <br>
                            <br>
                            <tr style="direction: rtl;">
                                <td>
                                    <div class="form-group col-md-2" style="float: left;width: 345px;">
                                        <label class="col-sm-1 control-label">دستخط استعمال کنندہ:
                                            <input type="text" class="VoucherMOdal" style="width: 218px;" readonly></label>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <table style="margin-top: -45px;">
                            <br>
                            <br>
                            <br>
                            <tr style="direction: rtl;" class="Jv">
                                <td style="width: 230px;">
                                    <div class="form-group col-md-4"  style="float: left">
                                        <label class="col-sm-2 control-label">تصدیقی دستخط ناظم شعبہ:
                                            <input type="text" class="VoucherMOdal" readonly></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group col-md-4" style="float: right">
                                        <label class="col-sm-2 control-label">دستخط قابل واپسی سامان کی وصو لیابی ازاسٹورکیپر:
                                            <input type="text" class="VoucherMOdal" readonly style="width: 153px;"></label>
                                    </div>
                                </td>
                            </tr>
                            <tr style="direction: rtl;" class="info">
                                <td class="td1">
                                    <div class="form-group col-md-2" style="float: left;width: 213px;">
                                        <label class="col-sm-1 control-label">تاریخ:
                                            <input type="text" class="VoucherMOdal" readonly></label>
                                    </div>
                                    <!-- <div class="form-group col-md-2 td1-1" style="float: right;width: 262px;">
                                        <label class="col-sm-1 control-label">ڈپٹی چیف اکاؤنٹنٹ:
                                            <input type="text" class="VoucherMOdal" style="width: 135px;" readonly></label>
                                    </div> -->
                                </td>
                                <td style="width: 200px;" class="td2">
                                    <div class="form-group col-md-4" style="float: right">
                                        <label class="col-sm-2 control-label">تاریخ:
                                            <input type="text" class="VoucherMOdal" style="width: 131px;" readonly></label>
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
    </footer> -->
</div>
</body>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="<?php echo base_url()."assets/";?>js/accounting.min.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
    //     $(document).ready(function () {
    //         //window.print();
    //         var dsum = 0;
    //         var csum = 0;
    //         var credit = 0;
    //         var debit = 0;

    //         $('.table-bordered tr').each(function () {
    //             $(this).find('.debit').each(function () {
    //                 var debit = $(this).text();
    //                 if (!isNaN(debit) && debit.length !== 0) {
    //                     dsum += parseFloat(debit);
    //                 }
    //             });$('#totald').text(accounting.formatMoney(dsum));

    //             $(this).find('.credit').each(function () {
    //                 var credit = $(this).text();
    //                 if (!isNaN(credit) && credit.length !== 0) {
    //                     csum += parseFloat(credit);
    //                 }
    //             });$('#totalc').text(accounting.formatMoney(csum));
    //         });

    //
    //     });


</script>
</html>