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

        #first_table tr td:nth-child(2){
            width: 10% !important;
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
        .remarks{
            table-layout:fixed;
            width:100px;
            word-wrap: break-all;
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
                position: fixed;
                bottom: 0;
                display: inline;
            }
            table tbody tr td:before,
            table tbody tr td:after {
                content : "" ;
                display : block ;
            }
            #hide{
                display: none !important;
            }
            .totaltable{
                margin-top: -17px;
            }
            .level{
                margin-right: 5%;
                margin-left: -5%;
            }
        }

        th, td, span{
            text-align: center;
        }

        td span{
            text-align: center;
        }

        span {
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="row" id="content">
        <div id="hide">
            <button onclick="myFunction()">Print</button>
        </div>
        <div style="text-align: center;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:22%; max-width:330px;">
            <h2 style="margin-top: 0px;font-size: 18px">شعبہ حسابات</h2>
            <h2 style="margin-top: 20px;font-size: 18px">تحویل کیشیئر کی رقم کی تفصیل</h2>
            <!--            <h4 style="font-size: 16px"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4>-->
        </div>
        <div style="margin-top: -5%;margin-right: 79%;">
            <h5><span>
                    رپورٹ نمبر
                    </span> <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 90px;font-weight: bold;font-size: 1em;line-height: 0%" value="<?php echo $pageno?>"></h5>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body two">
                        <!--                        <div class="row">-->
                        <!--                            <div class="col-md-6">-->
                        <!--                                <h4 style="text-decoration: underline; ">Company Name</h4>-->
                        <!--                            </div>-->
                        <!--                            <div class="col-md-6">-->
                        <!--                                <h4 style="float:left;direction: ltr;margin-right: -5%;"><span> - لیول</span></h4>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <?php $cash_total = 0; ?>
                            <table class="table-bordered" id="first_table">
                                <tbody>
                                <tr>
                                    <td style="width: 75%;text-align:right;"><span style="float: right">

                                                         سابقا کیش بیلنس مورخہ:

                                        </span>
                                        <?php echo $from;?>
                                    </td>

                                    <td style="text-align: center;text-align:center;"><?php echo number_format($remainingCash)?></td>
                                </tr>
                                <tr>
                                    <td style="width: 80%;text-align:right;"><span style="float: right">
 زیرتحویل کیشیئرکی رقم مورخہ:
                                        </span><?php echo $recieve;?></td>
                                    <td style="text-align: center;text-align:center;"><?php echo number_format($holdcash)?></td>
                                </tr>
                                <?php foreach ($otherexp as $E_key => $item){?>
                                    <tr>
                                        <td style="width: 75%;text-align: right">
                                            <?php if($E_key == 0){?>
                                                <span style="float: right">دیگر وصولیاں:</span><?php }?>
                                            <?php echo $item[0]?></td>
                                        <td style="text-align: center"><?php echo number_format($item[1]);
                                            $otherexp = $item[1];
                                            ?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <?php $cash_total = $remainingCash + $holdcash + $otherexp;

                                ?>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th style="text-align: left;"><span style="float: left;">
                                              میزان وصولی:
                                        </span></th>
                                    <th id="total" style="text-align: center"><?php echo number_format($cash_total);?></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row two">
                <div style="margin-right: 4%;">
                    <h5><span>
                            زیر تحویل کیشیئر کی رقم مورخہ                        </span><input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 230px;font-weight: bold;font-size: 1em;line-height: 0%" value="<?php echo $todate;?>"><span> تک اداشدہ واؤچر کی تفصیل درجہ ذیل ہیں</span></h5>
                </div>
            </div>
            <div class="panel-body two" style="direction: rtl;">
                <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
                    <table class="table-bordered" id="dataTables-example">
                        <thead>
                        <tr style="line-height: 250%;">
                            <th style="text-align: center;width: 10%;">واؤچر نمبر</th>
                            <th style="text-align: center;width: 23%;">تاریخ</th>
                            <th style="text-align: center;width: 55%;">مصارف کی تفصیل</th>
                            <th style="text-align: center">کل رقم</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $exvoucher_total = $final_total =0?>
                        <?php foreach($exvoucher as $key => $value){?>
                            <tr class="odd gradeX" style="line-height: 150%;">
                                <td class="center" style="text-align: center;font-size: 12px;"><?php echo $value[1].'-'.$value[0]?></td>
                                <td style="text-align: center;font-size: 12px;"><?php echo  $value[3]?> - <?php echo  $value[2]?></td>
                                <td class="remarks" style="text-align: center;font-size: 0.8em;"><?php echo $value[4]?></td>
                                <td style="text-align: center"><?php echo number_format($value[5]);
                                    $exvoucher_total += $value[5];
                                    ?></td>
                            </tr>
                        <?php }?>
                        </tbody>
                        <?php $final_total = $cash_total - $exvoucher_total?>
                        <tfoot>

                        </tfoot>
                    </table>
                </div>
                <div class="table-responsive totaltable" style="overflow-x: hidden;overflow-y: hidden;">
                    <table class="table-bordered" id="dataTables-example">
                        <tfoot>
                        <tr>
                            <th style="text-align: center;width: 10%;"></th>
                            <th style="text-align: center;width: 23%;"></th>
                            <th style="line-height: 32px;width: 48%;text-align: left"><span style="float: left;">
                                   میزان ادائیگی:
                                    </span></th>
                            <th><?php echo number_format($exvoucher_total);?></th>
                        </tr>
                        <tr>
                            <th style="text-align: center;width: 10%;"></th>
                            <th style="text-align: center;width: 23%;"></th>
                            <th style="line-height: 32px;width: 48%;text-align: left"><span style="float: left;">
                                موجودہ بیلنس:
                                <span></th>
                            <th style="width: 11%;"><?php echo number_format($final_total)?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div style="margin-top: 50px;">
                    <div>
                        <label class="label-control">
                            کیشیئر/اکاؤنٹنٹ
                        </label>
                        <input type="text" style="width: 17%" class="VoucherMOdal" value="">
                    </div>
                    <div style="margin-top: -3.5%;margin-right: 28%">
                        <label class="label-control">ڈپٹی چیف اکاؤنٹنٹ
                        </label>
                        <input type="text" style="width: 25%" class="VoucherMOdal" value="">
                    </div>
                    <div style="margin-top: -3.5%;margin-right: 60%;">
                        <label class="label-control">
                            خازن﴿اعزازی﴾ دارالعلوم کراچی
                            </label>
                        <input type="text" style="width: 34%" class="VoucherMOdal" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class ="footer" id="pagebreak">
    <!--    <div style="direction: ltr;">-->
    <!--        <hr> --><?php //date_default_timezone_set('Asia/Karachi');?>
    <!--        <span style="float: left;">--><?//= date('l d-m-Y h:i:s');?><!--</span>-->
    <!--        <hr>-->
    <!--    </div>-->
</footer>
</body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
       // debugger;
        var total = 0;
        $('#first_table tr td:nth-child(2)').each(function(){
            total += parseInt($(this).text().replace(/,/g, ""))
        })
        $('#total').html(total);
        $("#total").text(parseFloat(total, 10).toFixed().replace(/(\d)(?=(\d{3})+\.)/g, "$1,").toString());
        var a = parseInt($('#total').text().replace(/,/g, ''))
        var b = parseInt($('tfoot th').eq(5).text().replace(/,/g, ''))
        $('tfoot th').eq(9).text(a-b)
        $("tfoot th").digits();
    });
    $.fn.digits = function(){
        return this.each(function(){
           $(this).text( $(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
        })
    }
    function myFunction() {
        window.print();
    }
</script>