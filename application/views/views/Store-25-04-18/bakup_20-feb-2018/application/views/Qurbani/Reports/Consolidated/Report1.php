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
                    <h4 style="margin-top: 0px;font-size: 14px;text-align: right"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4>
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
                                حضرت ر ئیس الجامعہ مدظلہم العالی
                            </h4>
                        </div>
                        <div class="row">
                            <h5 style="text-align: center;">
                                السلام علیکم ورحمتہ اللہ و برکاتہ</h5>
                        </div>
                        <div class="row">
                            <h4 style="text-align: center">
                                موضوع:مراکز حصص کی با قیما ندہ رقم با بت سال ۱۴۳۸ھ
                            </h4>
                        </div>
                        <div class="row">
                            <p> حضرت والا کی خدمت میں عرض ہے کہ حصص و چرم قربانی ۱۴۳۸ھ کی مہم بحمد اللہ بخیروخوبی انجام پائی۔اس سال حصص داران کی باقیما ندہ رقم کی صور تحال درج ذیل ہے:</p>
                        </div>
                        <?php $profit0 = $profit1 = $profit2 = $total = $Reward_Sum = $netProfit0 = $netProfit1 = $netProfit2 = $netProfitSum = 0;?>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center"></th>
                                    <th style="text-align: center">کورنگی</th>
                                    <th style="text-align: center">گلشن اقبال</th>
                                    <th style="text-align: center">نا نکواڑہ</th>
                                    <th style="text-align: center">کل رقم</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>بچت قبل انعامات کارکنان</td>
                                    <?php
                                    $profit0 = ($income[0][0]->Amount + $Misc_Income[0][0]->Amount) - $Expence[0][0]->Amount;
                                    $profit1 = ($income[1][0]->Amount + $Misc_Income[1][0]->Amount) - $Expence[1][0]->Amount;
                                    $profit2 = ($income[2][0]->Amount + $Misc_Income[2][0]->Amount) - $Expence[2][0]->Amount;
                                    $total = $profit0 + $profit1 + $profit2;
                                    $Reward_Sum = $Reward[0][0]->Amount + $Reward[1][0]->Amount + $Reward[2][0]->Amount;
                                    $netProfit0 = $profit0 - $Reward[0][0]->Amount;
                                    $netProfit1 = $profit1 - $Reward[1][0]->Amount;
                                    $netProfit2 = $profit2 - $Reward[2][0]->Amount;
                                    $netProfitSum = $total - $Reward_Sum;

                                    ?>
                                    <td style="text-align: center"><?= number_format($profit0)?></td>
                                    <td style="text-align: center"><?= number_format($profit1)?></td>
                                    <td style="text-align: center"><?= number_format($profit2)?></td>
                                    <td style="text-align: center"><?= number_format($total)?></td>
                                </tr>
                                <tr>
                                    <td>منہا:انعامات کارکنان</td>
                                    <td style="text-align: center"><?= number_format($Reward[0][0]->Amount)?></td>
                                    <td style="text-align: center"><?= number_format($Reward[1][0]->Amount)?></td>
                                    <td style="text-align: center"><?= number_format($Reward[2][0]->Amount)?></td>
                                    <td style="text-align: center"><?= number_format($Reward_Sum)?></td>
                                </tr>
                                <tr>
                                    <td>باقیما ندہ رقم</td>
                                    <td style="text-align: center"><?= number_format($netProfit0)?></td>
                                    <td style="text-align: center"><?= number_format($netProfit1)?></td>
                                    <td style="text-align: center"><?= number_format($netProfit2)?></td>
                                    <td style="text-align: center"><?= number_format($netProfitSum)?></td>
                                </tr>
                                </tbody>
                                <!-- <tfoot>
                                <tr style="line-height: 250%;">
                                    <th><span style="float: right;"> میزان:</span></th>
                                    <th></th>
                                    <th style="text-align: center"></th>
                                    <th style="text-align: center"></th>
                                    <th style="text-align: center"></th>
                                </tr>
                                </tfoot> -->
                            </table>
                        </div>
                    </div>
                </div>
                <div>
                    <div>
                        <p>امسال تینوں ماکز حصص قربانی ۱۴۳۸ھ کی مجموعی باقیما ندہ رقم  مبلغ-/ <?= number_format($netProfitSum)?> روپے ہے۔</p>
                    </div>
                    <div>
                        <p>گز شتہ سال آنجناب نے حصص قربانی سے بچی ہوئی رقم مبلغ -/<?= number_format($netProfitSum)?> روپے کو عطیہ یا تعمیر مینار یا کسی اور مد میں ریکرڈ کرنے کا حسبِ صوابدید فیصلہ بھی فرمادیں- والسلام</p>
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