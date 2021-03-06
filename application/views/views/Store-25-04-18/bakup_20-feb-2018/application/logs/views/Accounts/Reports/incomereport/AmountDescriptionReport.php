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
            table .solidborader{
                border: 2px solid #080808!important;
                border-top: 2px solid #080808!important;
                border-bottom: 2px solid #080808!important;
            }
            .line {
                line-height: 10px;
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
            <!-- <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:42%; max-width:330px;"> -->
            <h2 style="margin-top: 0px; ">رقومات کی تفصیل</h2>
            <!-- <h4><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4> -->
        </div>
    </div>
    <?php $ch = count($Cheque); $cash = count($Currency); $income_data = array(); ?>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="table-responsive" align="middle" style="width: 107%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered line">
                                <thead>
                                <tr class="solidborader" style="line-height: 38px;">
                                    <th colspan="4" style="text-align: center;border: 2px solid #080808;">چیک کی  تفصیل</th>
                                    <th class="solidborader" colspan="3" style="text-align: center;border-right: 2px solid black;border-top: 0;border: 2px solid #080808;">نقدی کی  تفصیل</th>
                                </tr>
                                <tr style="line-height: 243%;">
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;">نمبر شمار</th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;">بینک کا نام</th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;">چیک نمبر</th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;">چیک کی رقم</th>
                                    <th class="solidborader" style="text-align: center;border-right: 2px solid black;border-top: 0;border: 2px solid #080808;">کرنسی</th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;">تعداد</th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;">رقم</th>
                                </tr>
                                </thead>
                                <?php $count = 0; $total = 0; $chequamount_total =0; $currency_quantity_total =0; $total_currency_quantity =0;?>
                                <tbody style="border: 2px solid #080808;">
                                <?php ($ch >= $cash)?$income_data = $Cheque:$income_data = $Currency;
                                foreach ($income_data as $key => $income_datum) { $count = $key;?>
                                    <tr>
                                        <td class="solidborader" style="border: 2px solid #080808;width: 11.5%;"><?= ++$count ?></td>
                                        <?php if (isset($Cheque[$key]->Bank_Name)){?>
                                            <td class="solidborader" style="text-align: center; border: 2px solid #080808;width: 16%;"><?= $Cheque[$key]->Bank_Name?></td>
                                            <td class="solidborader" style="border: 2px solid #080808;width: 17%;"><?= $Cheque[$key]->Cheque_Number?></td>
                                            <td class="solidborader" style="border: 2px solid #080808;width: 19%;"><?= number_format($Cheque[$key]->Cheque_amount);?></td>
                                        <?php $chequamount_total += $Cheque[$key]->Cheque_amount;
                                            }else{?>
                                            <td class="solidborader" style="text-align: center; border: 2px solid #080808;"></td>
                                            <td class="solidborader" style="border: 2px solid #080808;"></td>
                                            <td class="solidborader" style="border: 2px solid #080808;"></td>
                                        <?php } if (isset($Currency[$key]->Currency)){?>
                                            <td class="solidborader" style="border-right: 2px solid black;border-top: 0;border: 2px solid #080808;"><?= $Currency[$key]->Currency?></td>
                                            <td class="solidborader" style="border: 2px solid #080808;"><?= $Currency[$key]->Currency_Quantity?></td>
                                        <?php $currency_quantity_total += $Currency[$key]->Currency_Quantity; $total = $Currency[$key]->Currency * $Currency[$key]->Currency_Quantity;?>
                                            <?php if($Currency[$key]->Currency == 'ریزگاری'){?>
                                            <td class="solidborader" style="border: 2px solid #080808;"><?= number_format($Currency[$key]->Currency_Quantity); ?></td> 
                                            <?php }else{?>
                                            <td class="solidborader" style="border: 2px solid #080808;"><?=number_format($total); ?></td>
                                            <?php }?>
                                            <!-- ya total ya current or note ki quantity ky -->
                                        <?php $total_currency_quantity += $total; }else{?>
                                            <td class="solidborader" style="text-align: center; border: 2px solid #080808;"></td>
                                            <td class="solidborader" style="border: 2px solid #080808;"></td>
                                            <td class="solidborader" style="border: 2px solid #080808;"></td>
                                        <?php }?>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tfoot style="border: 2px solid #080808;">
                                <tr style="line-height: 250%;">
                                    <th class="solidborader" style="border: 2px solid #080808;"></th>
                                    <th class="solidborader" style="border: 2px solid #080808;"></th>
                                    <th class="solidborader" style="border: 2px solid #080808;"><span style="float: right;"> میزان:</span></th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?=number_format($chequamount_total);?></th>
                                    <th class="solidborader" style="border-right: 2px solid black;border-top: 0;border: 2px solid #080808;"></th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?=number_format($currency_quantity_total);?></th>
                                    <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?=number_format($total_currency_quantity);?></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php $clearing = 1; $deposit = 1; $transfer= 1; $outofcity = 1; $total1 = 0; $total2 = 0; $total3 = 0; $total4 = 0; $chq_amount1 =0; $chq_amount2 = 0; $chq_amount3 =0; $chq_amount4 = 0;
            if ($Cheque != 0){
                foreach ($Cheque as $key => $value) {
                    if ($value->Cheque_Type == 'clearing') {
                        $total1 = $clearing ++;
                        $chq_amount1 += $value->Cheque_amount;
                    }elseif ($value->Cheque_Type == 'deposit') {
                        $total2 = $deposit ++;
                        $chq_amount2 += $value->Cheque_amount;
                    }elseif ($value->Cheque_Type == 'transfer') {
                        $total3 = $transfer ++;
                        $chq_amount3 += $value->Cheque_amount;
                    }elseif ($value->Cheque_Type == 'outofcity') {
                        $total4 = $outofcity ++;
                        $chq_amount4 += $value->Cheque_amount;
                    }
                }
            } ?>
            <div class="panel-body" style="direction: rtl;">
                <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;width: 63%;margin-top: -4.5%;margin-right: -0.5%;">
                    <table class="table-bordered" id="dataTables-example">
                        <thead style="line-height: 250%;">
                        <tr>
                            <td class="solidborader" colspan="4" style="text-align: center;border: 2px solid #080808;border-top: 0px rgba(255, 255, 255, .15);line-height: 27px;font-weight: bold;">چیک کی اقسام</td>
                        </tr>
                        <tr>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;width: 18%">نمبر شمار</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;width: 25%">چیک کی قسم</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;width: 27%">چیک کی تعداد</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;">رقم</th>
                        </tr>
                        </thead>
                        <tbody style="border: 2px solid #080808;line-height: 200%">
                        <tr class="odd gradeX" style="">
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;">1</th>
                            <th class="solidborader" class="center" style="text-align: center;border: 2px solid #080808;">منتقل</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?php print_r($total3);?></th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?= number_format($chq_amount3)?></th>
                        </tr>
                        <tr class="odd gradeX" style="">
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;">2</th>
                            <th class="solidborader" class="center" style="text-align: center;border: 2px solid #080808;">کلیرنگ</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?php print_r($total1);?></th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?= number_format($chq_amount1)?></th>
                        </tr>
                        <tr class="odd gradeX" style="">
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;">3</th>
                            <th class="solidborader" class="center" style="text-align: center;border: 2px solid #080808;">آوٹ سٹی</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?php print_r($total4);?></th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?= number_format($chq_amount4)?></th>
                        </tr>
                        <tr class="odd gradeX" style="">
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;">4</th>
                            <th class="solidborader" class="center" style="text-align: center;border: 2px solid #080808;">آن لائن</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?php print_r($total2);?></th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?= number_format($chq_amount2)?></th>
                        </tr>
                        </tbody>
                        <?php $total_chque_amount = $chq_amount1 + $chq_amount2 + $chq_amount3 + $chq_amount4; ?>
                        <tfoot>
                        <tr style="line-height: 32px;">
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"></th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"></th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;">میزان:</th>
                            <th class="solidborader" style="text-align: center;border: 2px solid #080808;"><?= number_format($total_chque_amount) ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php $final_total  = $chequamount_total + $total_currency_quantity?>
            <div class="panel-body" style="direction: rtl;">
                <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;width: 63%;margin-top: -4.5%;margin-right: -0.5%;">
                    <table class="table-bordered" id="dataTables-example">
                        <thead>
                        <tr>
                            <th class="solidborader" colspan="4" style="text-align: center;border: 2px solid #080808;line-height: 27px;font-weight: bold;width:43%;">چیک + نقدی </th>
                            <th class="solidborader" colspan="3" style="text-align: center;border: 2px solid #080808;line-height: 27px;font-weight: bold;"><?= number_format($final_total) ?></th>
                        </tr>

                        </thead>
                        <tbody style="border: 2px solid #080808;">
                        <!-- <tr class="odd gradeX" style="line-height: 250%;">
									<th style="text-align: center;border: 2px solid #080808;">1</th>
									<th class="center" style="text-align: center;border: 2px solid #080808;">منتقل</th>
									<th style="text-align: center;border: 2px solid #080808;"><?php print_r($total3);?></th>
									<th style="text-align: center;border: 2px solid #080808;"><?= number_format($chq_amount3)?></th>
								</tr> -->
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
</script>