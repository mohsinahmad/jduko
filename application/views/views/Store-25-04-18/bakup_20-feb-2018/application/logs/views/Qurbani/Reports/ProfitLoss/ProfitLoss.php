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
        .ist_table{
            width: 50%;
            margin-top: -43.8%;
            margin-right: 50%;
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
            .ist_table{
                width: 50%;
                margin-top: -46.8%;
                margin-right: 50%;
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
            <h2 style="margin-top: 0px;font-size: 20px">گوشوارہ آمدنی و اخراجات حصص قربانی 1438ھ</h2>
            <!--            <h4 style="font-size: 16px"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4>-->
        </div>
    </div>
    <div class="level">
        <div class="row">
            <div style="width: 50%;margin-right: 3%">
                <div class="panel-body" style="direction: rtl;">
                    <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
                        <table class="table-bordered" id="dataTables-example">
                            <thead>
                            <tr style="line-height: 250%;">
                                <!--                                <th style="text-align: center">نمبر</th>-->
                                <th style="text-align: center">ایام نحر</th>
                                <th style="text-align: center">تعداد رسید</th>
                                <th style="text-align: center">حصص</th>
                                <th style="text-align: center">رقم</th>
                                <th style="text-align: center">کل رقم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $Mis_Income =$total_income = $total_exp = $remaining = 0;
                            foreach ($income_share as $key => $item) {
                                if ($item[0]->Collection_Day == 1){
                                    $Day = 'پہلا دن';
                                }elseif ($item[0]->Collection_Day == 2){
                                    $Day = 'دوسرا دن';
                                }elseif ($item[0]->Collection_Day == 3){ $Day = 'تیسرا دن'; }?>
                                <tr class="odd gradeX" style="line-height: 250%;">
                                    <!--                                    <th class="center" style="text-align: center">--><?//= $key+1?><!--</th>-->
                                    <th class="center" style="text-align: center"><?= $Day?></th>
                                    <th class="center" style="text-align: center"><span><?= ($item[0]->Count == 0)?'-':$item[0]->Count?></span><br><span>خود خرید کردہ گائے</span></th>
                                    <th class="center" style="text-align: center"><span><?= ($item[0]->Quantity == 0)?'-':$item[0]->Quantity?></span><br><span><?= ($income_self[$key][0]->Quantity == '')?'-':$income_self[$key][0]->Quantity?></span></th>
                                    <th class="center" style="text-align: center"><span><?= ($item[0]->Amount == 0)?'-':number_format($item[0]->Amount)?></span><br><span><?= ($income_self[$key][0]->Amount == 0)?'-':number_format($income_self[$key][0]->Amount)?></span></th>
                                    <?php $total = $item[0]->Amount + $income_self[$key][0]->Amount;?>
                                    <th class="center" style="text-align: center"><span></span><br><span><?= ($total == 0)?'-':number_format($total)?></span></th>
                                </tr>
                                <?php $total_income += $total; }?>
                            </tbody>
                            <tfoot>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th class="center" style="text-align: right" colspan="2">میزان</th>
                                <th class="center" style="text-align: left" colspan="3"><?= number_format($total_income)?></th>
                            </tr>
                            <?php foreach ($Misc_Income as $income){?>
                                <tr class="odd gradeX" style="line-height: 250%;">
                                    <th class="center" style="text-align: right" colspan="2"><?= $income->Remarks?></th>
                                    <th class="center" style="text-align: left" colspan="3"><?= number_format($income->Amount)?></th>
                                </tr>
                                <?php $Mis_Income += $income->Amount; } $Income_Sum = $total_income + $Mis_Income;?>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th class="center" style="text-align: right" colspan="2">کل میزان</th>
                                <th class="center" style="text-align: left" colspan="3"><?= number_format($Income_Sum)?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="ist_table" >
                <div class="panel-body" style="direction: rtl;">
                    <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
                        <table class="table-bordered" id="dataTables-example">
                            <thead>
                            <tr style="line-height: 250%;">
                                <th style="text-align: center">واوچر</th>
                                <th style="text-align: center">تفصیل</th>
                                <th style="text-align: center;width:24%;">رقم</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($Expence as $exp_key => $value) {?>
                                <tr class="odd gradeX" style="line-height: 250%;">
                                    <th class="center" style="text-align: center"><?= $exp_key+1?></th>
                                    <th class="center" style="text-align: right"><?= $value->Description?></th>
                                    <th style="text-align: center"><?= number_format($value->Amount)?></th>
                                </tr>
                                <?php $total_exp += $value->Amount; }?>
                            </tbody>
                            <tfoot>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th class="center" style="text-align: center"></th>
                                <th class="center" style="text-align: right">کل اخراجات</th>
                                <th style="text-align: center"><?= number_format($total_exp)?></th>
                            </tr>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th class="center" style="text-align: center"></th>
                                <th class="center" style="text-align: right">بقایاء رقم</th>
                                <?php $remaining = $total_exp - $Income_Sum;
                                if ($remaining < 0){ $new_remain = $remaining * -1;?>
                                    <th style="text-align: center">(<?= number_format($new_remain)?>)</th>
                                <?php }else{?>
                                    <th style="text-align: center"><?= number_format($remaining)?></th>
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