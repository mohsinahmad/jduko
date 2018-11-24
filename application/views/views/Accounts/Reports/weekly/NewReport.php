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
                src: url(<?php echo base_url().'assets/'; ?>fonts/NotoNastaliqUrdu-Regular.ttf) format("truetype");
            }
        </style>
        <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی</title>
        <link href="<?php echo base_url()."assets/"; ?>css/plugins/dataTables.bootstrap.css" rel="stylesheet">
        <link href="<?php echo base_url()."assets/"; ?>css/bootstrap.min.css" rel="stylesheet">
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


              #table>tbody>tr>td {
                border: none!important;
                }
              #table>tfoot>tr>td {
                border: none!important;
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

                .test{
                    border-bottom-style: double !important; 
                }
            }
        </style>
    </head>
    <body>
    <?php 
    $H_year = $report_date[0].$report_date[1].$report_date[2].$report_date[3];
    $H_month = $report_date[5].$report_date[6];
    $H_day = $report_date[8].$report_date[9];
    $P_year = $printing_date[0].$printing_date[1].$printing_date[2].$printing_date[3];
    $P_month = $printing_date[5].$printing_date[6];
    $P_day = $printing_date[8].$printing_date[9];?>
        <div class="content">
            <div class="row" id="content">
                <div id="hide">
                    <button onclick="myFunction()">Print</button>
                </div>
                <div style="text-align: center;">
                    <img src="<?php echo base_url()?>assets/images/logo.jpg" align="middle" style="width:20%; max-width:330px;">
                    <h4 style="margin-top: 0px; font-size: 14px; ">ررپورٹ براےَ وصولی و ادائیگیاں مبسلسلہ <?= $Titles[0]->ReportName?> </h4>
                    <h5>مورخہ  <?= $To?> ھ مطابق <?= $From?> ھ</h5>
                </div>
            </div>
                <div class="row" style="margin-right: 67%;margin-top: -11%; margin-left: -10%;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label">حوالہ نمبر<span style="margin-right: 5px;">:</span>
                                <input type="text" class="VoucherMOdal" value="<?php echo isset($serial) ? $serial : "" ?>" style="width: 48%;" readonly>
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
                            }
                            if ($H_month == '09'){
                                $R_month_inwords = 'رمضان المبارک';
                            }elseif ($H_month == '10'){
                                $R_month_inwords = 'شوال';
                            }elseif ($H_month == '08'){
                                $R_month_inwords = 'رجب المرجب';
                            }?>
                                <input type="text" value="<?= $P_day?>  <?=$P_month_inwords?> <?= $P_year?> ھ" class="VoucherMOdal" readonly style="width: 50%;margin-right: 9%;">
                            </label>
                        </div>
                    </div>
                </div>  
                <?php $ITotal = 0;?>
            <div class="level">
                <div>
                    <div class="row two">
                        <div class="col-lg-12">
                        
                                <table>
                                    <tr>
                                        <td class="test" style="float: right; border-bottom-style: double;">وصولیاں</td>
                                    </tr>
                                    <tr>
                                        <td style="float: right;">کل وصولی تا حال <?= $Titles[0]->ReportName?></td>
                                        <td style="float: left;"><?= number_format($Previous[0][0]->Credit)?> روپے</td>
                                    </tr>
                                    <tr>
                                        <td style="float: right;">کل وصولی دوران ہفتہ <?= $Titles[0]->ReportName?></td>
                                        <td style="float: left;"><?= number_format($TillToday[0][0]->Credit)?> روپے</td>
                                    </tr>
                                    <tr>
                                        <td style="float: right; margin-right: 35%;">کل آمدنی </td>
                                        <td style="float: left;     border-bottom-style: double; border-top: 1px solid;"><?php $ITotal = $Previous[0][0]->Credit + $TillToday[0][0]->Credit;
                                            echo number_format($ITotal);
                                        ?> روپے</td>
                                    </tr>
                                </table>
<!--                                 <div class="panel-body">
                                    <div class="row" style="margin-top: -1%;margin-right: -5%;">
                                        <div class="col-md-12">
                                            <div class="col-md-6">
                                                <h5 style="font-style: bold">وصولیاں</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input type="text" value="" class="VoucherMOdal" readonly style="width: 46%;margin-right: 42%;">
                                                    <label class="control-label">روپے
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                          </div>
                    </div>
                    <div class="row two">
                        <div class="col-lg-12">
                                <div class="panel-body">
                                    <div class="row" style="margin-top: -1%;margin-right: -5%;">
                                            <div class="col-md-12">
                                                <h5 style="text-weight: bold;">مصارف</h5>
                                            </div>
                                    </div><br>
                                    <div class="table-responsive" align="middle" style="width: 108%; margin-right: -5%; overflow-x: hidden;overflow-y: hidden;">
                                        <table class="table-bordered table" id="table">
                                            <tbody>
                                            <?php $MTotal = 0; $count = 0;?>
                                            <?php foreach($Titles as $key => $Title): ?>
                                                <tr>
                                                    <td style="float: right;"><?= $key + 1;?>-<?= $Title->Title   ?></td>
                                                    <td style="float: left;"> <?= number_format($Title->Amount) ?> روپے</td>
                                                    <?php $MTotal += $Title->Amount; $count = $key;?>
                                                </tr>
                                            <?php endforeach?>
                                            </tbody>
                                            <?php if ($count > 0 ){ ?>
                                                <tfoot>
                                                <tr>
                                                    <td style=" float: right; margin-right: 35%;">کل مصارف</td>
                                                    <td style="float: left;"><span style="border-bottom-style: double; border-top: 1px solid;"><?= number_format($MTotal)?> روپے</span></td>
                                                </tr>
                                                </tfoot>
                                            <?php }?>
                                        </table>
                                    </div>
                                </div>
                          </div>
                          <div>
                            <span style=" float: right; margin-right: 35%;"> آمدنی زا َید از مصارف</span>
                            <span style="float: left; margin-left: 2.5%;     border-bottom-style: double; border-top: 1px solid; padding-bottom: 10px;" > <?php
                                $Total = $ITotal - $MTotal; ?> 
                                <?= $Total < 0 ? '('. number_format($Total * -1) .')' :  number_format($Total); ?> روپے</span>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <footer class ="footer" id="pagebreak">
            <div style="direction: ltr;">
                <hr> <?php date_default_timezone_set('Asia/Karachi');?>
                <span style="float: left;"><?php echo date('l d-m-Y h:i:s');?></span>
                <hr>
            </div>
        </footer>
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