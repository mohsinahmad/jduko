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
                border: 0;
                outline: 0;
                background: transparent;
                border-bottom: 1px solid black;
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
            }
        </style>
    </head>
    <body>
        <div class="content">
            <div class="row" id="content">
                <div id="hide">
                    <button onclick="myFunction()">Print</button>
                </div>
                <div style="text-align: center;margin-right: 3%;margin-top: 2%;margin-bottom: -9%;">
                    <h2 style="margin-top: 0px;font-size: 14px;text-align: right">وحید اقبال،ناظم شعبہ حسابات</h2>
                    <h2 style="margin-top: 0px;font-size: 14px;text-align: right">جامعہ دارالعلوم کراچی</h2>
                    <h4 style="margin-top: 0px;font-size: 14px;text-align: right"><span><?php echo $HijriDate[0]->Qm_date?></span><span>  بمطابق  </span><span><?php echo $HijriDate[0]->Sh_date?></span></h4>
                </div>
                <div style="text-align: center;">
                    <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:22%; max-width:330px;">
                </div>
            </div>
            <div class="level">
                <div>
                    <div class="row two">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <div class="row">
                                    <h4 style="text-align: center">
                                        حضرت ر ئیس الجامعہ مدظلہم العالی ، جامعہ دارالعلوم کراچی
                                    </h4>
                                </div>
                                <div class="row">
                                    <h5 style="text-align: right;margin-right: 25%;">
                                        السلام علیکم ورحمتہ اللہ و برکاتہ</h5>
                                </div>
                                <div class="row">
                                    <h4 style="text-align: center">
                                        موضوع: <?php echo $vendor_Name[0]->Name?> صاحب سے کھالوں کی مد میں وصول شدہ ایڈوانس رقم کا حساب
                                    </h4>
                                </div>
                                <div class="row">
                                    <h5 style="text-align: justify;line-height: 25px; font-weight: 500">
                                        جامعہ دارالعلوم کراچی نے <?php echo $vendor_Name[0]->Name?> صاحب سے ایام نحر ۱۴۳۸ ؁ھ کی کھالوں کی فروختگی سے متعلق ایک معاہدہ کیا تھا جس کے تحت جامعہ دارالعلوم کراچی ایام عید کے تین دنوں میں جمع ہونے والی قربانی کے جانوروں سے حاصل شدہ مکس کوالٹی کی <?php 
                                        if (($Slip[0]->Cow_Old > 0) || ($Slip[0]->Cow_Fresh > 0)) {
                                            echo "گاےَ";
                                        }if (($Slip[0]->Goat_Old > 0) || ($Slip[0]->Goat_Fresh > 0)) {
                                            echo "،بکرا";
                                        }if (($Slip[0]->Sheep_Old > 0) || ($Slip[0]->Sheep_Fresh > 0)) {
                                           echo "،دنبہ";
                                        }if (($Slip[0]->Camel_Old > 0) || ($Slip[0]->Camel_Fresh > 0)) {
                                           echo "،اونٹ";
                                        }if (($Slip[0]->Buffelo_Old > 0) || ($Slip[0]->Buffelo_Fresh > 0)) {
                                            echo "،بھینس";
                                        }
                                        ?> کی کھالیں طے شدہ نرخوں پر <?php echo $vendor_Name[0]->Name?> صاحب کو فروخت کرے گا۔معاہدہ کے مطابق <?php echo $vendor_Name[0]->Name?> صاحب سے مبلغ -/<?php echo number_format($vendor_Name[0]->Amount);?> روپے علی الحساب وصول کر لیے گئے تھے تاہم <?php echo $vendor_Name[0]->Name?> صاحب کو امسال۱۴۳۸ ؁ھ میں مبلغ <?php echo number_format($total_amount_khaal);?> روپے کی کھالیں فروخت کی گئی ہیں۔فروختگی چرم کی تفصیل حسبِ ذیل ہے:
                                    </h5>
                                </div>
                                <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                                    <table class="table-bordered">
                                        <thead>
                                        <tr style="line-height: 243%;">
                                            <th style="text-align: center">تفصیل</th>
                                            <th style="text-align: center;width: 25%">رقم(روپے)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="text-align: right">علی الحساب وصول شدہ رقم <?php echo $vendor_Name[0]->Name?></td>
                                            <td style="text-align: center"><?= number_format($vendor_Name[0]->Amount)?></td>
                                        </tr>
                                        <tr>
                                            <th colspan="2" style="line-height: 33px;">فروخت شدہ کھالوں کی تفصیل </th>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Cow_Fresh > 0){?>
                                            <td><span style="float: right;">گاےَ تازہ</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Cow_Fresh)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Fresh_Rate_Cow,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Cow_Fresh * $Slip[0]->Fresh_Rate_Cow) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Cow_Old > 0){?>
                                            <td><span style="float: right;">گاےَ باسی</span>
                                            <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Cow_Old)?></span> 
                                            <span style="margin-right: 2%">عددبحساب</span>
                                            <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Old_Rate_Cow,2) ?></span>
                                             <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Cow_Old * $Slip[0]->Old_Rate_Cow) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Goat_Fresh > 0){?>
                                            <td><span style="float: right;">بکرا تازہ</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Goat_Fresh)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Fresh_Rate_Goat,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Goat_Fresh * $Slip[0]->Fresh_Rate_Goat) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Goat_Old > 0){?>
                                            <td><span style="float: right;">بکرا باسی</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Goat_Old)?></span> 
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Old_Rate_Goat,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Goat_Old * $Slip[0]->Old_Rate_Goat) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Sheep_Fresh > 0){?>
                                            <td><span style="float: right;">دنبہ تازہ</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Sheep_Fresh)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Fresh_Rate_Sheep,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Sheep_Fresh * $Slip[0]->Fresh_Rate_Sheep) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Sheep_Old > 0){?>
                                            <td><span style="float: right;">دنبہ باسی</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Sheep_Old)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Old_Rate_Sheep,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Sheep_Old * $Slip[0]->Old_Rate_Sheep) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Camel_Fresh > 0){?>
                                            <td><span style="float: right;">اونٹ تازہ</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Camel_Fresh)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Fresh_Rate_Camel,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Camel_Fresh * $Slip[0]->Fresh_Rate_Camel) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Camel_Old > 0){?>
                                            <td><span style="float: right;">اونٹ باسی</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Camel_Old)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Old_Rate_Camel,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Camel_Old * $Slip[0]->Old_Rate_Camel) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Buffelo_Fresh > 0){?>
                                            <td><span style="float: right;">بھینس تازہ</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Buffelo_Fresh)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Fresh_Rate_Buffelo,2) ?></span>
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Buffelo_Fresh * $Slip[0]->Fresh_Rate_Buffelo) ?></td>
                                            <?php }?>
                                        </tr>
                                        <tr>
                                            <?php if($Slip[0]->Buffelo_Old > 0){?>
                                            <td><span style="float: right;">بھینس باسی</span>
                                                <span style="margin-right: 8%"><?php echo number_format($Slip[0]->Buffelo_Old)?></span>
                                                <span style="margin-right: 2%">عددبحساب</span>
                                                <span style="margin-right: 13%"><?php echo number_format($Slip[0]->Old_Rate_Buffelo,2) ?></span> 
                                                <span style="float: left;margin-left: 25%"> روپے</span> </td>
                                            <td><?php echo number_format($Slip[0]->Buffelo_Old * $Slip[0]->Old_Rate_Buffelo) ?></td>
                                            <?php }?>
                                        </tr>
                                        </tbody>
                                        <?php 
                                            $total_amount = ($Slip[0]->Cow_Fresh * $Slip[0]->Fresh_Rate_Cow)+($Slip[0]->Cow_Old * $Slip[0]->Old_Rate_Cow)+($Slip[0]->Goat_Fresh * $Slip[0]->Fresh_Rate_Goat)+($Slip[0]->Goat_Old * $Slip[0]->Old_Rate_Goat)+($Slip[0]->Sheep_Fresh * $Slip[0]->Fresh_Rate_Sheep)+($Slip[0]->Sheep_Old * $Slip[0]->Old_Rate_Sheep)+($Slip[0]->Camel_Fresh * $Slip[0]->Fresh_Rate_Camel)+($Slip[0]->Camel_Old * $Slip[0]->Old_Rate_Camel)+($Slip[0]->Buffelo_Fresh * $Slip[0]->Fresh_Rate_Buffelo)+($Slip[0]->Buffelo_Old * $Slip[0]->Old_Rate_Buffelo);
                                            $final_amount = $vendor_Name[0]->Amount - $total_amount;
                                        ?>
                                        <tfoot>
                                        <tr style="line-height: 250%;">
                                            <th>کل آمدنی بزریعہ فروختگی چرم قربانی</th>
                                            <th style="text-align: center"><?= number_format($total_amount)?></th>
                                        </tr>
                                        <tr style="line-height: 250%;">
                                            <th>قابل ادا / قابل وصول رقم</th>
                                            <?php if($final_amount < 0){
                                                $New_fianl_amount = $final_amount * -1;
                                                ?>
                                            <th style="text-align: center">(<?= number_format($New_fianl_amount)?>)</th>
                                            <?php }else{?>
                                            <th style="text-align: center"><?= number_format($final_amount)?></th>
                                            <?php }?>
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
    </body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        //window.print();
    });
    function myFunction() {
        window.print();
    }
</script>