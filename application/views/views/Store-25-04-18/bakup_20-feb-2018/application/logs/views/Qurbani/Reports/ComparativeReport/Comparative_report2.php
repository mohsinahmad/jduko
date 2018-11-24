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
            <h2 style="margin-top: 0px;font-size: 20px">تقا بلی جائزہ فروخت تعداد چرم قربانی اور حاصل شدہ قیمت ۱۴۳۸ھ</h2>
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
                                <?php
                                $old_Quantity0 = $OLD_Data['Fresh_Quantity'][0] + $OLD_Data['Old_Quantity'][0];
                                $OldDiff_Cow0 = $SaleSlip[0]->Cow_Quantity - $old_Quantity0;
                                $amount_diff0 = $SaleSlip[0]->Cow_Amount - $OLD_Data['Amount'][0];
                                ?>
                                <tr>
                                    <td><?= $amount[0][0]->chrum_type?></td>
                                    <td style="text-align: center;"><?= number_format($old_Quantity0)?></td>
                                    <td><?= number_format($SaleSlip[0]->Cow_Quantity)?></td>
                                    <?php if($OldDiff_Cow0 < 0){
                                        $New_diff_quantity0 = $OldDiff_Cow0 * -1;
                                        ?>
                                        <td>(<?= number_format($New_diff_quantity0)?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($OldDiff_Cow0) ?></td>
                                    <?php }?>
                                    <?php $diff0 = ($OldDiff_Cow0 / $old_Quantity0)*100; ?>
                                    <?php if($diff0 < 0){
                                        $New_diff_per0 = $diff0 * -1;
                                        ?>
                                        <td>(<?php echo number_format($New_diff_per0);?>%)</td>
                                    <?php }else{?>
                                        <td><?php echo number_format($diff0);?>%</td>
                                    <?php }?>
                                    <td><?= number_format($OLD_Data['Amount'][0]);?></td>
                                    <td><?= number_format($SaleSlip[0]->Cow_Amount);?></td>
                                    <?php if($amount_diff0 < 0){
                                        $New_amount_diff0 = $amount_diff0 * -1;
                                        ?>
                                        <td>(<?= number_format($New_amount_diff0);?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($amount_diff0);?></td>
                                    <?php }?>
                                    <td style="">
                                        <span>1437ھ = قیمت فی کھال = <?= $old[0][0]->Fresh_Rate;?> روپے</span>
                                        <br>
                                        <span>1438ھ = قیمت فی کھال = <?= ($amount[0][0]->latest_amount);?> روپے</span>
                                    </td>
                                </tr>
                                <?php
                                $old_Quantity1 = $OLD_Data['Fresh_Quantity'][1] + $OLD_Data['Old_Quantity'][1];
                                $OldDiff_Cow1 = $SaleSlip[0]->Goat_Quantity - $old_Quantity1;
                                $amount_diff1 = $SaleSlip[0]->Goat_Amount - $OLD_Data['Amount'][1];
                                ?>
                                <tr>
                                    <td><?= $amount[1][0]->chrum_type?></td>
                                    <td style="text-align: center;"><?= number_format($old_Quantity1)?></td>
                                    <td><?= number_format($SaleSlip[0]->Goat_Quantity)?></td>
                                    <?php if($OldDiff_Cow1 < 0){
                                        $New_diff_quantity1 = $OldDiff_Cow1 * -1;
                                        ?>
                                        <td>(<?= number_format($New_diff_quantity1)?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($OldDiff_Cow1) ?></td>
                                    <?php }?>
                                    <?php $diff1 = ($OldDiff_Cow1 / $old_Quantity1)*100; ?>
                                    <?php if($diff1 < 0){
                                        $New_diff_per1 = $diff1 * -1;
                                        ?>
                                        <td>(<?php echo number_format($New_diff_per1);?>%)</td>
                                    <?php }else{?>
                                        <td><?php echo number_format($diff1);?>%</td>
                                    <?php }?>
                                    <td><?= number_format($OLD_Data['Amount'][1]);?></td>
                                    <td><?= number_format($SaleSlip[0]->Goat_Amount);?></td>
                                    <?php if($amount_diff1 < 0){
                                        $New_amount_diff1 = $amount_diff1 * -1;
                                        ?>
                                        <td>(<?= number_format($New_amount_diff1);?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($amount_diff1);?></td>
                                    <?php }?>
                                    <td style="">
                                        <span>1437ھ = قیمت فی کھال = <?= $old[1][0]->Fresh_Rate;?> روپے</span>
                                        <br>
                                        <span>1438ھ = قیمت فی کھال = <?= ($amount[1][0]->latest_amount);?> روپے</span>
                                    </td>
                                </tr>
                                <?php
                                $old_Quantity2 = $OLD_Data['Fresh_Quantity'][2] + $OLD_Data['Old_Quantity'][2];
                                $OldDiff_Cow2 = $SaleSlip[0]->Sheep_Quantity - $old_Quantity2;
                                $amount_diff2 = $SaleSlip[0]->Sheep_Amount - $OLD_Data['Amount'][2]; ?>
                                <tr>
                                    <td><?= $amount[2][0]->chrum_type?></td>
                                    <td style="text-align: center;"><?= number_format($old_Quantity2)?></td>
                                    <td><?= number_format($SaleSlip[0]->Sheep_Quantity)?></td>
                                    <?php if($OldDiff_Cow2 < 0){
                                        $New_diff_quantity2 = $OldDiff_Cow2 * -1;
                                        ?>
                                        <td>(<?= number_format($New_diff_quantity2)?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($OldDiff_Cow2) ?></td>
                                    <?php }?>
                                    <?php $diff2 = ($OldDiff_Cow2 / $old_Quantity2)*100; ?>
                                    <?php if($diff2 < 0){
                                        $New_diff_per2 = $diff2 * -1;
                                        ?>
                                        <td>(<?php echo number_format($New_diff_per2);?>%)</td>
                                    <?php }else{?>
                                        <td><?php echo number_format($diff2);?>%</td>
                                    <?php }?>
                                    <td><?= number_format($OLD_Data['Amount'][2]);?></td>
                                    <td><?= number_format($SaleSlip[0]->Sheep_Amount);?></td>
                                    <?php if($amount_diff2 < 0){
                                        $New_amount_diff2 = $amount_diff2 * -1;
                                        ?>
                                        <td>(<?= number_format($New_amount_diff2);?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($amount_diff2);?></td>
                                    <?php }?>
                                    <td style="">
                                        <span>1437ھ = قیمت فی کھال = <?= $old[2][0]->Fresh_Rate;?> روپے</span>
                                        <br>
                                        <span>1438ھ = قیمت فی کھال = <?= ($amount[2][0]->latest_amount);?> روپے</span>
                                    </td>
                                </tr>
                                <?php
                                $old_Quantity3 = $OLD_Data['Fresh_Quantity'][3] + $OLD_Data['Old_Quantity'][3];
                                $OldDiff_Cow3 = $SaleSlip[0]->Camel_Quantity - $old_Quantity3;
                                $amount_diff3 = $SaleSlip[0]->Camel_Amount - $OLD_Data['Amount'][3]; ?>
                                <tr>
                                    <td><?= $amount[3][0]->chrum_type?></td>
                                    <td style="text-align: center;"><?= number_format($old_Quantity3)?></td>
                                    <td><?= number_format($SaleSlip[0]->Camel_Quantity)?></td>
                                    <?php if($OldDiff_Cow3 < 0){
                                        $New_diff_quantity3 = $OldDiff_Cow3 * -1;
                                        ?>
                                        <td>(<?= number_format($New_diff_quantity3)?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($OldDiff_Cow3) ?></td>
                                    <?php }?>
                                    <?php $diff3 = ($OldDiff_Cow3 / $old_Quantity3)*100; ?>
                                    <?php if($diff3 < 0){
                                        $New_diff_per3 = $diff3 * -1;
                                        ?>
                                        <td>(<?php echo number_format($New_diff_per3);?>%)</td>
                                    <?php }else{?>
                                        <td><?php echo number_format($diff3);?>%</td>
                                    <?php }?>
                                    <td><?= number_format($OLD_Data['Amount'][3]);?></td>
                                    <td><?= number_format($SaleSlip[0]->Camel_Amount);?></td>
                                    <?php if($amount_diff3 < 0){
                                        $New_amount_diff3 = $amount_diff3 * -1;
                                        ?>
                                        <td>(<?= number_format($New_amount_diff3);?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($amount_diff3);?></td>
                                    <?php }?>
                                    <td style="">
                                        <span>1437ھ = قیمت فی کھال = <?= $old[3][0]->Fresh_Rate;?> روپے</span>
                                        <br>
                                        <span>1438ھ = قیمت فی کھال = <?= ($amount[3][0]->latest_amount);?> روپے</span>
                                    </td>
                                </tr>
                                <?php
                                $old_Quantity4 = $OLD_Data['Fresh_Quantity'][4] + $OLD_Data['Old_Quantity'][4];
                                $OldDiff_Cow4 = $SaleSlip[0]->Buffalo_Quantity - $old_Quantity4;
                                $amount_diff4 = $SaleSlip[0]->Buffalo_Amount - $OLD_Data['Amount'][4]; ?>
                                <tr>
                                    <td><?= $amount[4][0]->chrum_type?></td>
                                    <td style="text-align: center;"><?= number_format($old_Quantity4)?></td>
                                    <td><?= number_format($SaleSlip[0]->Buffalo_Quantity)?></td>
                                    <?php if($OldDiff_Cow4 < 0){
                                        $New_diff_quantity4 = $OldDiff_Cow4 * -1;
                                        ?>
                                        <td>(<?= number_format($New_diff_quantity4)?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($OldDiff_Cow4) ?></td>
                                    <?php }?>
                                    <?php $diff4 = ($OldDiff_Cow4 / $old_Quantity4)*100; ?>
                                    <?php if($diff4 < 0){
                                        $New_diff_per4 = $diff4 * -1;
                                        ?>
                                        <td>(<?php echo number_format($New_diff_per4);?>%)</td>
                                    <?php }else{?>
                                        <td><?php echo number_format($diff4);?>%</td>
                                    <?php }?>
                                    <td><?= number_format($OLD_Data['Amount'][4]);?></td>
                                    <td><?= number_format($SaleSlip[0]->Buffalo_Amount);?></td>
                                    <?php if($amount_diff4 < 0){
                                        $New_amount_diff4 = $amount_diff4 * -1;
                                        ?>
                                        <td>(<?= number_format($New_amount_diff4);?>)</td>
                                    <?php }else{?>
                                        <td><?= number_format($amount_diff4);?></td>
                                    <?php }?>
                                    <td style="">
                                        <span>1437ھ = قیمت فی کھال = <?= $old[4][0]->Fresh_Rate;?> روپے</span>
                                        <br>
                                        <span>1438ھ = قیمت فی کھال = <?= ($amount[4][0]->latest_amount);?> روپے</span>
                                    </td>
                                </tr>
                                </tbody>
                                <?php
                                $total_Old_Quantity = $old_Quantity0+$old_Quantity1+$old_Quantity2+$old_Quantity3+$old_Quantity4;
                                $total_Current_Quantity = $SaleSlip[0]->Cow_Quantity+$SaleSlip[0]->Goat_Quantity+$SaleSlip[0]->Sheep_Quantity+$SaleSlip[0]->Camel_Quantity+$SaleSlip[0]->Buffalo_Quantity;
                                $total_diff = $total_Current_Quantity - $total_Old_Quantity;
                                $total_diff_per = ($total_diff / $total_Old_Quantity) * 100;
                                $total_Old_Amount = $OLD_Data['Amount'][0]+$OLD_Data['Amount'][1]+$OLD_Data['Amount'][2]+$OLD_Data['Amount'][3]+$OLD_Data['Amount'][4];
                                $total_Current_Amount = $SaleSlip[0]->Cow_Amount+$SaleSlip[0]->Goat_Amount+$SaleSlip[0]->Sheep_Amount+$SaleSlip[0]->Camel_Amount+$SaleSlip[0]->Buffalo_Amount;
                                $total_diff_amount = $total_Current_Amount - $total_Old_Amount;
                                ?>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th> میزان:</th>
                                    <th style="text-align: center;"><?php echo number_format($total_Old_Quantity) ?></th>
                                    <th style="text-align: center;"><?php echo number_format($total_Current_Quantity) ?></th>
                                    <?php if($total_diff < 0){
                                        $New_total_diff = $total_diff * -1;
                                        ?>
                                        <th style="text-align: center;">(<?php echo number_format($New_total_diff) ?>)</th>
                                    <?php }else{?>
                                        <th style="text-align: center;"><?php echo number_format($total_diff) ?></th>
                                    <?php }?>
                                    <?php if($total_diff_per < 0){
                                        $New_total_diff_per = $total_diff_per * -1;
                                        ?>
                                        <th style="text-align: center;">(<?php echo number_format($New_total_diff_per) ?>%)</th>
                                    <?php } else{?>
                                        <th style="text-align: center;">%<?php echo number_format($total_diff_per) ?></th>
                                    <?php }?>
                                    <th style="text-align: center;"><?php echo number_format($total_Old_Amount) ?></th>
                                    <th style="text-align: center;"><?php echo number_format($total_Current_Amount) ?></th>
                                    <?php if($total_diff_amount < 0){
                                        $New_total_diff_amount = $total_diff_amount * -1;
                                        ?>
                                        <th style="text-align: center;">(<?php echo number_format($New_total_diff_amount) ?>)</th>
                                    <?php }else{?>
                                        <th style="text-align: center;"><?php echo number_format($total_diff_amount) ?></th>
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
                        <textarea rows="6" cols="100" type="text" class="VoucherMOdal" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;width: 65%;overflow:hidden;margin-right: 11%;margin-top: -2%;line-height: 26px;font-size: 0.9em;color: black;"><?= $OLD_Data['details']?></textarea>
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