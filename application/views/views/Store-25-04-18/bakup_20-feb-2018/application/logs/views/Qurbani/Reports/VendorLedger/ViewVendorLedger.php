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
            <h2 style="margin-top: 0px;font-size: 20px">وینڈر لیجر</h2>
        </div>
    </div>
    <div class="level">
        <div style="margin-top: -4%;">
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                                <h4 style=""><span>وینڈر نام :
                                        <?php if(isset($vendor_Name[0]->Name)){?>
                                            <?= $vendor_Name[0]->Name ; }?>
											</span></h4>
                            </div>
                        </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center;">تاریخ</th>
                                    <th style="text-align: center;">سلپ نمبر</th>
                                    <th style="text-align: center;">تفصیل</th>
                                    <th style="text-align: center;">رقم وصولی</th>
                                    <th style="text-align: center;">کھال</th>
                                    <th style="text-align: center;">تعداد</th>
                                    <th style="text-align: center;">ریٹ</th>
                                    <th style="text-align: center;">رقم</th>
                                    <th style="text-align: center;">کل کھال</th>
                                    <th style="text-align: center;">کل رقم</th>
                                    <th style="text-align: center;">بیلنس</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $new_bal=$Cow_Fresh=$Cow_Old=$Goat_Old=$Goat_Fresh=$Sheep_Old=$Sheep_Fresh=$Camel_Fresh=$Camel_Old=$Buffelo_Fresh=$Buffelo_Old=$total = 0;
                                $Fresh_Rate_Cow=$Old_Rate_Cow=$Fresh_Rate_Goat=$Old_Rate_Goat=$Fresh_Rate_Sheep=$Old_Rate_Sheep=$Fresh_Rate_Camel=$Old_Rate_Camel=$Fresh_Rate_Buffelo=$Old_Rate_Buffelo=$total_amount =$balance= 0;
                                $amount_sum=$total_amount_sum=$total_sum = 0;
                                // ($vendors_Cash != '')?$MyArray =  $vendors_Cash:$MyArray = $SaleSlip;
                                foreach($Slip as $key => $value){?>
                                    <tr>
                                        <td><?= (isset($value->DateH))?$value->DateH:$value->Slip_DateH?></td>
                                        <td><?= (isset($value->Amount))?$value->Number:$value->Number?></td>
                                        <td><?= (isset($value->Remarks))?$value->Remarks:''?></td>
                                        <td>
                                            <?php if(isset($value->Amount)){
                                                echo number_format($value->Amount);
                                                $amount_sum +=$value->Amount;
                                            }?>
                                        </td>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span><?= "گائے تازہ";?></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span><?= "گائے باسی"?></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span><?= "بکرا تازہ";?></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span><?= "بکرا باسی";?></span><br><?php }?>
                                            <?php if ($value->Sheep_Fresh > 0){?>
                                                <span><?= "دنبہ تازہ";?></span><br><?php }	?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span><?= "دنبہ باسی";?></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span><?= "اونٹ تازہ";?></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span><?= "اونٹ باسی";?></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span><?= "بھینس تازہ";?></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span><?= "بھینس باسی";?></span><br><?php }?>
                                        </td>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span><?= $value->Cow_Fresh;?></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span><?= $value->Cow_Old;?></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span><?= $value->Goat_Fresh;?></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span><?= $value->Goat_Old;?></span><br><?php }?>
                                            <?php if($value->Sheep_Fresh > 0){?>
                                                <span><?= $value->Sheep_Fresh;?></span><br><?php }?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span><?= $value->Sheep_Old;?></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span><?= $value->Camel_Fresh;?></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span><?= $value->Camel_Old;?></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span><?= $value->Buffelo_Fresh;?></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span><?= $value->Buffelo_Old;?></span><br><?php }?>
                                        </td>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Cow,2);?></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Cow,2);?></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Goat,2);?></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Goat,2);?></span><br><?php }?>
                                            <?php if($value->Sheep_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Sheep,2);?></span><br><?php }?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Sheep,2);?></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Camel,2);?></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Camel,2);?></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Buffelo,2);?></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Buffelo,2);?></span><br><?php }?>
                                        </td>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Cow*$value->Cow_Fresh,2);?></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span><?= number_format( $value->Old_Rate_Cow*$value->Cow_Old,2);?></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Goat*$value->Goat_Fresh,2);?></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Goat*$value->Goat_Old,2);?></span><br><?php }?>
                                            <?php if($value->Sheep_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Sheep*$value->Sheep_Fresh,2);?></span><br><?php }?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Sheep*$value->Sheep_Old,2);?></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Camel*$value->Camel_Fresh,2);?></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Camel*$value->Camel_Old,2);?></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span><?= number_format($value->Fresh_Rate_Buffelo*$value->Buffelo_Fresh,2);?></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span><?= number_format($value->Old_Rate_Buffelo*$value->Buffelo_Old,2);?></span><br><?php }?>
                                        </td>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span><?php $Cow_Fresh = $value->Cow_Fresh?></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span><?php $Cow_Old =  $value->Cow_Old;?></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span><?php $Goat_Fresh = $value->Goat_Fresh;?></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span><?php $Goat_Old = $value->Goat_Old;?></span><br><?php }?>
                                            <?php if($value->Sheep_Fresh > 0){?>
                                                <span><?php $Sheep_Fresh = $value->Sheep_Fresh;?></span><br><?php }?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span><?php $Sheep_Old = $value->Sheep_Old;?></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span><?php $Camel_Fresh = $value->Camel_Fresh;?></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span><?php $Camel_Old = $value->Camel_Old;?></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span><?php $Buffelo_Fresh = $value->Buffelo_Fresh;?></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span><?php $Buffelo_Old = $value->Buffelo_Old;?></span><br><?php }?>
                                            <?php $total = $Cow_Fresh+$Cow_Old+$Goat_Old+$Goat_Fresh+$Sheep_Old+$Sheep_Fresh+$Camel_Fresh+$Camel_Old+$Buffelo_Fresh+$Buffelo_Old;
                                            $Cow_Fresh = $Cow_Old = $Goat_Old = $Goat_Fresh = $Sheep_Old = $Sheep_Fresh = $Camel_Fresh = $Camel_Old = $Buffelo_Fresh = $Buffelo_Old = 0;?>
                                            <span><?php if(isset($value->Total_Amount))
                                                { echo number_format($total);
                                                    $total_sum += $total; }?>
													</span>
                                        </td>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span><?php $Fresh_Rate_Cow = $value->Fresh_Rate_Cow*$value->Cow_Fresh;?></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span><?php $Old_Rate_Cow = $value->Old_Rate_Cow*$value->Cow_Old;?></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span><?php $Fresh_Rate_Goat = $value->Fresh_Rate_Goat*$value->Goat_Fresh;?></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span><?php $Old_Rate_Goat = $value->Old_Rate_Goat*$value->Goat_Old;?></span><br><?php }?>
                                            <?php if($value->Sheep_Fresh > 0){?>
                                                <span><?php $Fresh_Rate_Sheep = $value->Fresh_Rate_Sheep*$value->Sheep_Fresh;?></span><br><?php }?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span><?php $Old_Rate_Sheep = $value->Old_Rate_Sheep*$value->Sheep_Old;?></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span><?php $Fresh_Rate_Camel = $value->Fresh_Rate_Camel*$value->Camel_Fresh;?></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span><?php $Old_Rate_Camel = $value->Old_Rate_Camel*$value->Camel_Old;?></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span><?php $Fresh_Rate_Buffelo = $value->Fresh_Rate_Buffelo*$value->Buffelo_Fresh;?></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span><?php $Old_Rate_Buffelo = $value->Old_Rate_Buffelo*$value->Buffelo_Old;?></span><br><?php }?>
                                            <?php $total_amount = $Fresh_Rate_Cow+$Old_Rate_Cow+$Fresh_Rate_Goat+$Old_Rate_Goat+$Fresh_Rate_Sheep+$Old_Rate_Sheep+$Fresh_Rate_Camel+$Old_Rate_Camel+$Fresh_Rate_Buffelo+$Old_Rate_Buffelo;
                                            $Fresh_Rate_Cow = $Old_Rate_Cow = $Fresh_Rate_Goat = $Old_Rate_Goat = $Fresh_Rate_Sheep = $Old_Rate_Sheep = $Fresh_Rate_Camel = $Old_Rate_Camel = $Fresh_Rate_Buffelo = $Old_Rate_Buffelo = 0;
                                            if(isset($value->Total_Amount)){
                                                echo number_format($total_amount);
                                                $total_amount_sum += $total_amount;} ?>
                                        </td>
                                        <?php if(isset($value->Amount)){
                                            $balance += $value->Amount;
                                        }elseif (isset($value->Total_Amount)) {
                                            $balance += $value->Amount - $total_amount; }?>
                                        <td>
                                            <?php if($value->Cow_Fresh > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Cow_Old > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Goat_Fresh > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Goat_Old > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Sheep_Fresh > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Sheep_Old > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Camel_Fresh > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Camel_Old > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Buffelo_Fresh > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($value->Buffelo_Old > 0){?>
                                                <span></span><br><?php }?>
                                            <?php if($balance < 0 ) {
                                                echo '(' . number_format($balance * -1) . ')';
                                            }else{
                                                echo number_format($balance);
                                            }?>
                                        </td>
                                    </tr>
                                <?php }?>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th></th>
                                    <th><span style="float: right;"> میزان:</span></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"><?= number_format($amount_sum); ?></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"></th>
                                    <th style="text-align: center;"><?= number_format($total_sum); ?></th>
                                    <th style="text-align: center;"><?= number_format($total_amount_sum) ?></th>
                                    <?php if ($balance < 0){ $new_bal = $balance * -1; ?>
                                        <th style="text-align: center;">(<?= number_format($new_bal) ?>)</th>
                                    <?php }else{?>
                                        <th style="text-align: center;"><?= number_format($balance) ?></th>
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
</div>
</body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
</script>