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
            <h2 style="margin-top: 10px;text-decoration: underline;font-size: 20px">رقوم کی منتقلی- کورنگی</h2>
            <h4 style="font-size: 16px"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4>
            <h4 style="font-size: 16px"></h4>
        </div>
    </div>
    <div class="level">
        <div> <?php $totalAmountTransfer = 0; $totalAmountTransfer = $slip_data[0]->Total_Transfer + $slip_data[0]->This_Transfer_Amount?>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div>
                            <p>میں، ________________ نے رسید#: (<?= $slip_data[0]->Slip_Number?>) کے ذریعے جناب ___________________ سے <?= number_format($slip_data[0]->This_Transfer_Amount)?> روپے وصول کیئے۔ </p>
                            <p>اب تک میں ان سے کل <?= number_format($totalAmountTransfer)?> روپے وصول کرچکا ہوں۔ </p>
                        </div>
                        <p>
                            اب اس لین دین کے بعد کاونٹر پر <?= number_format($slip_data[0]->Cash_In_Hand_After_This)?> روپے رقم موجود ہے۔
                        </p>
                    </div>
                </div>
            </div>
            <div >
                <div style="margin-right: 0px">
                    <p>_______________________</p>
                    <h4 style="margin-right: 14px;">دستخط وصول کنندہ</h4>
                </div>
                <div style="float: left;margin-top: -8%; margin-left: 4%;">
                    <p style="float: left">_______________________</p>
                    <h4 style="margin-top: 28px;margin-right: 202px;">دستخط جمع کنندہ</h4>
                </div>
            </div>
        </div>
    </div>
</div>
</body><?php $SelfCow = ($Infiradi[0]->Quantity)/7;?>
<footer style="margin-right: 79%;"><?php print_r($Ijtemai[0]->Quantity.':'.$SelfCow);?></footer>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
</script>