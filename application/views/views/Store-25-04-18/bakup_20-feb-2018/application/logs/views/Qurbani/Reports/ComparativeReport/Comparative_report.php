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
            max-width: 1100px;
            margin:auto;
            padding:20px;
        }
        .two{
            direction: rtl;
            width: 97%;
            margin-right: 2%;
            padding-top: 0%;
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
            border-bottom: 0px solid black;
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
            <h2 style="margin-top: 0px;font-size: 20px">تقا بلی جائزہ تعداد چرم قربانی اور حاصل شدہ قیمت ۱۴۳۸ھ</h2>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center;width: 6%" rowspan="2">اقسام چرم</th>
                                    <th style="text-align: center;width: 15%;" colspan="2">کل تعدادصحیح وناقص چرم</th>
                                    <th style="text-align: center;width: 7%;" rowspan="2">اضافہ/کمی بلحاظ تعداد</th>
                                    <th style="text-align: center;width: 7%;" rowspan="2">اضافہ/کمی بلحاظ فیصد</th>
                                    <th style="text-align: center;width: 22%" colspan="2">حاصل شدہ قیمت</th>
                                    <th style="text-align: center;width: 10%;" rowspan="2">قیمت میں اضافہ/کمی</th>
                                    <th style="text-align: center;width: 23%" rowspan="2">کیفیت</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">1437ھ<br>تعداد</th>
                                    <th style="text-align: center;">1438ھ<br>تعداد</th>
                                    <th style="text-align: center;">1437ھ<br>روپے</th>
                                    <th style="text-align: center;">1438ھ<br>روپے</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $diff_quantity = $diff_amount = $diff_per = $total_old_Quantity = $total_current_Quantity = 0;
                                $total_diff_Quantity = $total_diff_per = $total_old_Amount = $total_current_Amount = 0;
                                $total_diff_Amount = $New_diff_quantity = $New_diff_per = $New_diff_amount = 0;
                                foreach ($amount as $key => $value) { ?>
                                    <tr>
                                        <td><?= $value[0]->chrum_type?></td>
                                        <td><?= ($old[$key][0]->Fresh_Quantity + $old[$key][0]->Old_Quantity);
                                            $total_old_Quantity += ($old[$key][0]->Fresh_Quantity + $old[$key][0]->Old_Quantity);?>
                                        </td>
                                        <td><?= number_format(($value[0]->fresh_quantity + $value[0]->old_quantity));
                                            $total_current_Quantity += ($value[0]->fresh_quantity + $value[0]->old_quantity); ?>
                                        </td>
                                        <?php $diff_quantity = ($value[0]->fresh_quantity + $value[0]->old_quantity) - ($old[$key][0]->Fresh_Quantity + $old[$key][0]->Old_Quantity);
                                        if($diff_quantity < 0){
                                            $New_diff_quantity = $diff_quantity * -1;?>
                                            <td>(<?= number_format($New_diff_quantity);?>)</td>
                                        <?php } else{?>
                                            <td><?= number_format($diff_quantity);
                                                ?></td>
                                        <?php } $total_diff_Quantity += $diff_quantity;
                                        $diff_per = ($diff_quantity / ($old[$key][0]->Fresh_Quantity + $old[$key][0]->Old_Quantity)) * 100;
                                        $total_diff_per = ($total_diff_Quantity / $total_old_Quantity) * 100;
                                        if ($diff_per < 0){ $New_diff_per = $diff_per * -1; ?>
                                            <td>(<?= number_format($New_diff_per) ?>%)</td>
                                        <?php } else{?>
                                            <td><?= number_format($diff_per) ?>%</td>
                                        <?php }?>
                                        <td><?= number_format($old[$key][0]->Amount);
                                            $total_old_Amount += $old[$key][0]->Amount; ?>
                                        </td>
                                        <td><?= number_format((($value[0]->latest_amount * $value[0]->fresh_quantity))+($value[0]->old_amount * $value[0]->old_quantity));
                                            $total_current_Amount +=(($value[0]->latest_amount * $value[0]->fresh_quantity)+($value[0]->old_amount * $value[0]->old_quantity)); ?></td>
                                        <?php $diff_amount = (($value[0]->latest_amount * $value[0]->fresh_quantity))+($value[0]->old_amount * $value[0]->old_quantity) - ($old[$key][0]->Amount);
                                        if($diff_amount < 0){
                                            $New_diff_amount = $diff_amount * -1;?>
                                            <td>(<?= number_format($New_diff_amount);?>)</td>
                                        <?php } else{?>
                                            <td><?= number_format($diff_amount);?></td>
                                        <?php } $total_diff_Amount += $diff_amount;?>
                                        <td style="">
                                            <span>1437ھ = قیمت فی کھال = <?= $old[$key][0]->Fresh_Rate;?> روپے</span>
                                            <br>
                                            <span>1438ھ = قیمت فی کھال = <?= ($per_Chrum[$key]->latest_amount);?> روپے</span>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <?php $New_total_diff_Quantity = $New_total_diff_per = $New_total_diff_Amount = 0;?>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th> میزان:</th>
                                    <th style="text-align: center;"><?= number_format($total_old_Quantity)?></th>
                                    <th style="text-align: center;"><?= number_format($total_current_Quantity)?></th>
                                    <?php if($total_diff_Quantity < 0){ $New_total_diff_Quantity = $total_diff_Quantity * -1;?>
                                        <th style="text-align: center;">(<?= number_format($New_total_diff_Quantity)?>)</th>
                                    <?php  } else{?>
                                        <th style="text-align: center;"><?= number_format($total_diff_Quantity)?></th>
                                    <?php }
                                    if($total_diff_per < 0){ $New_total_diff_per =$total_diff_per * -1; ?>
                                        <th style="text-align: center;">(<?= number_format($New_total_diff_per) ?>%)</th>
                                    <?php }else{?>
                                        <th style="text-align: center;"><?= number_format($total_diff_per)?>%</th>
                                    <?php }?>
                                    <th style="text-align: center;"><?= number_format($total_old_Amount)?></th>
                                    <th style="text-align: center;"><?= number_format($total_current_Amount)?></th>
                                    <?php if($total_diff_Amount < 0){ $New_total_diff_Amount = $total_diff_Amount * -1; ?>
                                        <th style="text-align: center;">(<?= number_format($New_total_diff_Amount); ?>)</th>
                                    <?php } else{?>
                                        <th style="text-align: center;"><?= number_format($total_diff_Amount)?></th>
                                    <?php }?>
                                    <th style="text-align: center;"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="" style="margin-bottom: 0px;margin-top: 3%;margin-right: -10%;">
                    <div class="form-group">
                        <textarea rows="6" cols="100" type="text" class="VoucherMOdal" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;width: 65%;overflow:hidden;margin-right: 11%;margin-top: -2%;line-height: 26px;font-size: 0.9em;color: black;"><?= $details?></textarea>
                    </div>
                </div>
            </div>
            <div style="margin-top: -10%; margin-right: 80%;">
                <p style="text-align: center;font-size: smaller;">وحید اقبال</p>
                <p style="text-align: center;font-size: smaller;">ڈپٹی چیف اکاونٹینٹ</p>
                <p style="text-align: center;font-size: smaller;">جامعہ دارلعلوم کراچی</p>
            </div>
                <?php date_default_timezone_set('Asia/Karachi');?>
                <span style="float: left;"><?= date('l d-m-Y h:i:s');?></span>
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