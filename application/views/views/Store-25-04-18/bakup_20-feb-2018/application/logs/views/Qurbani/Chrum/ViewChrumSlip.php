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
            <h2 style="margin-top: 0px;font-size: 20px">رسید برائے وصولی چرم</h2>

        </div>
    </div>
    <div class="level">
        <div style=" margin-top: -6%;">
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div style="float: right;margin-right: -3%;">
                                <h4 style="direction: ltr;"><span>رسید نمبر -</span><?= $Quantity[0]->receipt_no?></h4>
                            </div>
                            <div style="margin-top: 7%;margin-right: 35%">
                                <h4><span>نگران - </span><?= $Quantity[0]->supervisor_name?></h4>
                            </div>
                            <div style="margin-top: -4%;margin-left: 38%;margin-bottom: -9%;">
                                <h4 style=""><span>حلقہ - </span><?= $Quantity[0]->hulqa_name?></h4>
                            </div>
                            <div style="margin-right: 71%;margin-top: 7%;">
                                <h4 style="font-size: 16px"><span><?= $Quantity[0]->dateH?></span><span>  بمطابق  </span><span><?= $Quantity[0]->dateG?></span></h4>
                            </div>
                        </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center;width: 36%;">چرم کی قسم</th>
                                    <th style="text-align: center">تا زہ</th>
                                    <th style="text-align: center">باسی</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $total_fresh = 0; $total_old =0;?>
                                <?php foreach($Quantity as $item){ ?>
                                    <tr>
                                        <td style="text-align: center;"><?= $item->chrum_type?></td>
                                        <td style="text-align: center;"><?= $item->fresh_quantity;
                                            $total_fresh += $item->fresh_quantity;
                                            ?></td>
                                        <td style="text-align: center;"><?= $item->old_quantity;
                                            $total_old += $item->old_quantity;
                                            ?></td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th></th>
                                    <th style="text-align: center"><?= $total_fresh;?></th>
                                    <th style="text-align: center"><?= $total_old;?></th>
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
    function myFunction() {
        window.print();
    }
</script>