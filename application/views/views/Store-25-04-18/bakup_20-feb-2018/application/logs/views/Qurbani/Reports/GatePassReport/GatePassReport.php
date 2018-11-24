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
            <h2 style="margin-top: 0px;text-decoration: underline;font-size: 20px">گیٹ پاس رپورٹ</h2>
            <!--            <h4 style="font-size: 16px"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4>-->
        </div>
    </div>
    <div class="level">
        <div>
            <div class="panel-body" style="direction: rtl;">
                <div class="table-responsive">
                    <table class="table-bordered" id="dataTables-example">
                        <thead>
                        <tr style="line-height: 250%;text-align: center">
                            <th rowspan="2" style="text-align: center">دن</th>
                            <th rowspan="2" style="text-align: center">رسید نمبر</th>
                            <th colspan="2" style="text-align: center">گائے</th>
                            <th colspan="2" style="text-align: center">بکرا</th>
                            <th colspan="2" style="text-align: center">میشہ</th>
                            <th colspan="2" style="text-align: center">اونٹ</th>
                            <th colspan="2" style="text-align: center">بھینس</th>
                            <th rowspan="2" style="text-align: center">کل تعداد</th>
                            <!--                            <th rowspan="2">۹</th>-->
                        </tr>
                        <tr style="line-height: 250%;">
                            <th style="text-align: center">تازہ</th>
                            <th style="text-align: center">باسی</th>
                            <th style="text-align: center">تازہ</th>
                            <th style="text-align: center">باسی</th>
                            <th style="text-align: center">تازہ</th>
                            <th style="text-align: center">باسی</th>
                            <th style="text-align: center">تازہ</th>
                            <th style="text-align: center">باسی</th>
                            <th style="text-align: center">تازہ</th>
                            <th style="text-align: center">باسی</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $Day =$total_countFresh0 = $total_countOld0 = $total_countFresh1 = $total_countOld1 = $total_countFresh2 = 0;
                        $total_countOld2 = $total_countFresh3 = $total_countOld3 = $total_countFresh4 = $total_countOld4 = $total_count = $count = 0;
                        foreach ($Receipt_numbers as $receipt) {
//                            foreach ($receipt as $item) {
                            $total = 0;
                            if ($receipt->Slip_DateH[8].$receipt->Slip_DateH[9] == 10){
                                $Day = 'پہلا دن';
                            }elseif ($receipt->Slip_DateH[8].$receipt->Slip_DateH[9] == 11){
                                $Day = 'دوسرا دن';
                            }else{ $Day = 'تیسرا دن'; }?>
                            <tr class="odd gradeX" style="line-height: 250%;">
                                <th style="text-align: center"><?= $Day?></th>
                                <th style="text-align: center"><?= $receipt->Slip_Number?></th>
                                <th style="text-align: center"><?= ($receipt->Cow_Fresh == 0)?'-':$receipt->Cow_Fresh?></th>
                                <th style="text-align: center"><?= ($receipt->Cow_Old == 0)?'-':$receipt->Cow_Old?></th>
                                <th style="text-align: center"><?= ($receipt->Goat_Fresh == 0)?'-':$receipt->Goat_Fresh?></th>
                                <th style="text-align: center"><?= ($receipt->Goat_Old == 0)?'-':$receipt->Goat_Old?></th>
                                <th style="text-align: center"><?= ($receipt->Sheep_Fresh == 0)?'-':$receipt->Sheep_Fresh?></th>
                                <th style="text-align: center"><?= ($receipt->Sheep_Old == 0)?'-':$receipt->Sheep_Old?></th>
                                <th style="text-align: center"><?= ($receipt->Camel_Fresh == 0)?'-':$receipt->Camel_Fresh?></th>
                                <th style="text-align: center"><?= ($receipt->Camel_Old == 0)?'-':$receipt->Camel_Old?></th>
                                <th style="text-align: center"><?= ($receipt->Buffelo_Fresh == 0)?'-':$receipt->Buffelo_Fresh?></th>
                                <th style="text-align: center"><?= ($receipt->Buffelo_Old == 0)?'-':$receipt->Buffelo_Old?></th>
                                <?php $total = $receipt->Cow_Fresh + $receipt->Cow_Old +$receipt->Goat_Fresh + $receipt->Goat_Old +$receipt->Sheep_Fresh + $receipt->Sheep_Old + $receipt->Camel_Fresh + $receipt->Camel_Old + $receipt->Buffelo_Fresh + $receipt->Buffelo_Old;
                                $total_countFresh0 += $receipt->Cow_Fresh; $total_countOld0 += $receipt->Cow_Old;
                                $total_countFresh1 += $receipt->Goat_Fresh; $total_countOld1 += $receipt->Goat_Old;
                                $total_countFresh2 += $receipt->Sheep_Fresh; $total_countOld2 += $receipt->Sheep_Old;
                                $total_countFresh3 += $receipt->Camel_Fresh; $total_countOld3 += $receipt->Camel_Old;
                                $total_countFresh4 += $receipt->Buffelo_Fresh; $total_countOld4 += $receipt->Buffelo_Old;
                                $total_count += $total; $count++;?>
                                <th style="text-align: center"><?= $total?></th>
                            </tr>
                        <?php }?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th style="text-align: center"></th>
                            <th style="text-align: center"><?= $count?></th>
                            <th style="text-align: center"><?= $total_countFresh0?></th>
                            <th style="text-align: center"><?= $total_countOld0?></th>
                            <th style="text-align: center"><?= $total_countFresh1?></th>
                            <th style="text-align: center"><?= $total_countOld1?></th>
                            <th style="text-align: center"><?= $total_countFresh2?></th>
                            <th style="text-align: center"><?= $total_countOld2?></th>
                            <th style="text-align: center"><?= $total_countFresh3?></th>
                            <th style="text-align: center"><?= $total_countOld3?></th>
                            <th style="text-align: center"><?= $total_countFresh4?></th>
                            <th style="text-align: center"><?= $total_countOld4?></th>
                            <th style="text-align: center"><?= $total_count?></th>
                        </tr>
                        <tr>
                            <th style="text-align: center">نرخ</th>
                            <th style="text-align: center"></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Cow)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Cow)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Goat)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Goat)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Sheep)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Sheep)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Camel)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Camel)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Buffelo)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Buffelo)?></th>
                            <th style="text-align: center"> - </th>
                        </tr>
                        <tr>
                            <th style="text-align: center"></th>
                            <th style="text-align: center"></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Cow * $total_countFresh0)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Cow * $total_countOld0)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Goat * $total_countFresh1)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Goat * $total_countOld1)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Sheep * $total_countFresh2)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Sheep * $total_countOld2)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Camel * $total_countFresh3)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Camel * $total_countOld3)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Fresh_Rate_Buffelo * $total_countFresh4)?></th>
                            <th style="text-align: center"><?= number_format((int)$Receipt_numbers[0]->Old_Rate_Buffelo * $total_countOld4)?></th>
                            <?php $Final_Total = ((int)$Receipt_numbers[0]->Fresh_Rate_Cow * (int)$total_countFresh0)+((int)$Receipt_numbers[0]->Old_Rate_Cow * (int)$total_countOld0)+((int)$Receipt_numbers[0]->Fresh_Rate_Goat * (int)$total_countFresh1)+((int)$Receipt_numbers[0]->Old_Rate_Goat * (int)$total_countOld1)+((int)$Receipt_numbers[0]->Fresh_Rate_Sheep * (int)$total_countFresh2)+((int)$Receipt_numbers[0]->Old_Rate_Sheep * (int)$total_countOld2)+((int)$Receipt_numbers[0]->Fresh_Rate_Camel * (int)$total_countFresh3)+((int)$Receipt_numbers[0]->Old_Rate_Camel * (int)$total_countOld3)+((int)$Receipt_numbers[0]->Fresh_Rate_Buffelo * (int)$total_countFresh4)+((int)$Receipt_numbers[0]->Old_Rate_Buffelo * (int)$total_countOld4); ?>
                            <th style="text-align: center"><?= number_format($Final_Total)?></th>
                        </tr>
                        </tfoot>
                    </table>
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