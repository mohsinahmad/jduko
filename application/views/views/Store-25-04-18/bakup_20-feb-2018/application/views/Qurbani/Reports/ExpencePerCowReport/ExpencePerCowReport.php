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
        <div style="text-align: center;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:22%; max-width:330px;">
            <h2 style="margin-top: 0px;font-size: 20px">مصارف فی گائے</h2>
<!--            <h4 style="font-size: 16px"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4>-->
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4 style="">کل گائے : <?= $cow_count[0]->Count?></h4>
                            </div>
<!--                            <div class="col-md-6">-->
<!--                                <h4 style="float:left;direction: ltr;margin-right: -5%;"><span> - لیول</span></h4>-->
<!--                            </div>-->
                        </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                    <tr style="line-height: 243%;">
                                        <th style="text-align: center">مصارف</th>
                                        <th style="text-align: center">رقم</th>
                                        <th style="text-align: center">صرفہ فی گائے</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $total_amount = $total = $total_serfa = 0;
                                foreach ($vouchers as $voucher) {
                                    foreach ($voucher as $item) {?>
                                    <tr>
                                        <td><?= $item->type?></td>
                                        <td><?= $item->Amount?></td>
                                        <?php $total_amount += $item->Amount;
                                        $total = $item->Amount / $cow_count[0]->Count;
                                        $total_serfa += $total;?>
                                        <td><?= number_format($total)?></td>
                                    </tr>
                                <?php $total = 0; } } ?>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th><span style="float: right;"> میزان:</span></th>
                                    <th><?= $total_amount?></th>
                                    <th style="text-align: center"><?= number_format($total_serfa).'/'.number_format($total_serfa/7)?></th>
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
<!--<footer class ="footer" id="pagebreak">-->
<!--    <div style="direction: ltr;">-->
<!--        <hr> --><?php //date_default_timezone_set('Asia/Karachi');?>
<!--        <span style="float: left;">--><?//= date('l d-m-Y h:i:s');?><!--</span>-->
<!--        <hr>-->
<!--    </div>-->
<!--</footer>-->
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