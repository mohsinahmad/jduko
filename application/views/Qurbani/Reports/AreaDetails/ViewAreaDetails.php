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
            <h2 style="margin-top: 0px;font-size: 20px">خلاصہ حلقہ جات</h2>
        </div>
    </div>
    <div class="level">
        <?php $Cow_Fresh=$Cow_Old=$Goat_Fresh=$Goat_Old=$Sheep_Fresh=$Sheep_Old=$Camel_Fresh=$Camel_Old=$Buffalo_Fresh=$Buffalo_Old=$fresh_quantity_total=$old_quantity_total=$current_index=0;
        $total_fresh_Cow=$total_old_Cow=$total_fresh_Goat=$total_old_Goat=$total_fresh_Sheep=$total_old_Sheep=$total_fresh_Camel=$total_old_Camel=$total_fresh_Buffalo=$total_old_Buffalo=$total_All_sum_Fresh=$total_All_sum_Old=0;
        $final_total_Fresh_Cow=$final_total_Old_Cow=$final_total_Fresh_Goat=$final_total_Old_Goat=$final_total_Fresh_Sheep=$final_total_Old_Sheep=$final_total_Fresh_Camel=$final_total_Old_Camel=$final_total_Fresh_Buffalo=$final_total_Old_Buffalo=$final_Fresh=$final_Old = $count= 0;
        foreach($AreaDetails as $key => $value){
            // if ($hulqy[$key]->Type == 0) {?>
            <div style="page-break-after: always">
                <div class="row two">
                   <!--  <?php echo ++$count;?> -->
                    <div class="col-lg-12">
                        <div class="panel-body">
                            <!--                            <div class="row">-->
                            <!--                                <div style="margin-top: -5%;margin-bottom: -5%;">-->
                            <!--                                    <h4 style=""> حلقہ - --><?php //echo $hulqy[$key]->hulqa_name?><!--</h4>-->
                            <!--                                </div>-->
                            <!--                                <div>-->
                            <!--                                    <h4 style="float:left;direction: ltr;margin-right: -5%;"><span>نگران - --><?php //echo $hulqy[$key]->supervisor_name?><!--</span></h4>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                                <table class="table-bordered">
                                    <thead>
                                    <tr>
                                        <th colspan="4"><h4 style=""> حلقہ - <?= $hulqy[$key]->hulqa_name?></h4></th>
                                        <th colspan="4"><h4 style="float:left;direction: ltr;margin-right: -5%;"><span>نگران - <?= $hulqy[$key]->supervisor_name?></span></h4></th>
                                    </tr>
                                    <tr style="line-height: 243%;">
                                        <th style="text-align: center;width: 13%">دن</th>
                                        <th style="text-align: center;width: 11%">رسید نمبرم</th>
                                        <th style="text-align: center;width: 11%">گائے</th>
                                        <th style="text-align: center;width: 11%">بکرا</th>
                                        <th style="text-align: center;width: 11%">دنبہ</th>
                                        <th style="text-align: center;width: 11%">اونٹ</th>
                                        <th style="text-align: center;width: 11%">بھینس</th>
                                        <th style="text-align: center;width: 15%">کل</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $Cow_Fresh_Total=$Cow_Old_Total=$Goat_Fresh_Total=$Goat_Old_Total=$Sheep_Fresh_Total=$Sheep_Old_Total=$Camel_Fresh_Total=$Camel_Old_Total=$Buffalo_Fresh_Total=$Buffalo_Old_Total=$fresh_quantity_total_sum=$old_quantity_total_sum= 0;
                                    foreach($value as $D_key => $item){
                                        foreach ($item as $A_key => $report_data) {
                                            if ($report_data != array('')){?>
                                                <tr>
                                                    <?php if($report_data[0]->Receive_Day == 1){?>
                                                        <td><?= "پہلا دن"?></td>
                                                    <?php } elseif($report_data[0]->Receive_Day == 2){?>
                                                        <td><?= "دوسرا دن"?></td>
                                                    <?php } else{?>
                                                        <td><?= "تیسرا دن"?></td>
                                                    <?php }?>
                                                    <td><?= $report_data[0]->receipt_no?></td>
                                                    <td>
                                                        <span><?= $report_data[0]->fresh_quantity;
                                                        $Cow_Fresh = $report_data[0]->fresh_quantity;
                                                        $Cow_Fresh_Total += $Cow_Fresh;
                                                        ?></span><br>
                                                        <span><?= $report_data[0]->old_quantity;
                                                            $Cow_Old =  $report_data[0]->old_quantity;
                                                            $Cow_Old_Total += $Cow_Old; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?= $report_data[1]->fresh_quantity;
                                                            $Goat_Fresh =  $report_data[1]->fresh_quantity;
                                                            $Goat_Fresh_Total += $Goat_Fresh;
                                                            ?></span><br>
                                                        <span><?= $report_data[1]->old_quantity;
                                                            $Goat_Old = $report_data[1]->old_quantity;
                                                            $Goat_Old_Total += $Goat_Old; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?= $report_data[2]->fresh_quantity;
                                                            $Sheep_Fresh = $report_data[2]->fresh_quantity;
                                                            $Sheep_Fresh_Total += $Sheep_Fresh;
                                                            ?></span><br>
                                                        <span><?= $report_data[2]->old_quantity;
                                                            $Sheep_Old = $report_data[2]->old_quantity;
                                                            $Sheep_Old_Total += $Sheep_Old; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?= $report_data[3]->fresh_quantity;
                                                            $Camel_Fresh = $report_data[3]->fresh_quantity;
                                                            $Camel_Fresh_Total += $Camel_Fresh;
                                                            ?></span><br>
                                                        <span><?= $report_data[3]->old_quantity;
                                                            $Camel_Old = $report_data[3]->old_quantity;
                                                            $Camel_Old_Total += $Camel_Old; ?></span>
                                                    </td>
                                                    <td>
                                                        <span><?= $report_data[4]->fresh_quantity;
                                                            $Buffalo_Fresh = $report_data[4]->fresh_quantity;
                                                            $Buffalo_Fresh_Total += $Buffalo_Fresh;
                                                            ?></span><br>
                                                        <span><?= $report_data[4]->old_quantity;
                                                            $Buffalo_Old = $report_data[4]->old_quantity;
                                                            $Buffalo_Old_Total += $Buffalo_Old; ?></span>
                                                    </td>
                                                    <?php $fresh_quantity_total = $Cow_Fresh+$Goat_Fresh+$Sheep_Fresh+$Camel_Fresh+$Buffalo_Fresh;
                                                    $old_quantity_total = $Cow_Old+$Goat_Old+$Sheep_Old+$Camel_Old+$Buffalo_Old; ?>
                                                    <td><span><?php echo $fresh_quantity_total;
                                                            $fresh_quantity_total_sum += $fresh_quantity_total; ?></span><br>
                                                        <span><?php echo $old_quantity_total;
                                                            $old_quantity_total_sum += $old_quantity_total; ?></span>
                                                    </td>
                                                </tr>
                                            <?php } } }?>
                                    </tbody>
                                    <tfoot>
                                    <tr style="line-height: 150%;">
                                        <th></th>
                                        <th><span style="float: right;"> میزان:</span></th>
                                        <th style="text-align: center">
										    <span><?php echo number_format($Cow_Fresh_Total);
                                                $total_fresh_Cow += $Cow_Fresh_Total; ?></span><br>
                                            <span><?php echo number_format($Cow_Old_Total);
                                                $total_old_Cow += $Cow_Old_Total ?></span>
                                        </th>
                                        <th style="text-align: center">
											<span><?php echo number_format($Goat_Fresh_Total);
                                                $total_fresh_Goat += $Goat_Fresh_Total; ?></span><br>
                                            <span><?php echo number_format($Goat_Old_Total);
                                                $total_old_Goat += $Goat_Old_Total; ?></span>
                                        </th>
                                        <th style="text-align: center">
											<span><?php echo number_format($Sheep_Fresh_Total);
                                                $total_fresh_Sheep += $Sheep_Fresh_Total; ?></span><br>
                                            <span><?php echo number_format($Sheep_Old_Total);
                                                $total_old_Sheep += $Sheep_Old_Total; ?></span>
                                        </th>
                                        <th style="text-align: center">
											<span><?php echo number_format($Camel_Fresh_Total);
                                                $total_fresh_Camel += $Camel_Fresh_Total; ?></span><br>
                                            <span><?php echo number_format($Camel_Old_Total);
                                                $total_old_Camel += $Camel_Old_Total ?></span>
                                        </th>
                                        <th style="text-align: center">
											<span><?php echo number_format($Buffalo_Fresh_Total);
                                                $total_fresh_Buffalo += $Buffalo_Fresh_Total; ?></span><br>
                                            <span><?php echo number_format($Buffalo_Old_Total);
                                                $total_old_Buffalo += $Buffalo_Old_Total
                                                ?></span>
                                        </th>
                                        <th style="text-align: center">
											<span><?php echo number_format($fresh_quantity_total_sum);
                                                $total_All_sum_Fresh += $fresh_quantity_total_sum
                                                ?></span><br>
                                            <span><?php echo number_format($old_quantity_total_sum);
                                                $total_All_sum_Old += $old_quantity_total_sum; ?></span>
                                        </th>
                                    </tr>
                                    </tfoot>
                                </table>
                                <?php if (isset($hulqy[$key+1])) {
                                    if ($hulqy[$key+1]->Type != $hulqy[$key]->Type) { ?>
                                        <div class="panel-body" style="direction: rtl;">
                                        <div class="table-responsive" style="width: 104%;margin-right: -2%;overflow-x: hidden;overflow-y: hidden;">
                                            <table class="table-bordered" id="dataTables-example">
                                                <tbody>
                                                <tr class="odd gradeX" style="line-height: 150%;">
                                                	<?php if($hulqy[$key]->Type == 0){?>
                                                    <th style="text-align: center;width: 13%">حلقہ</th>
                                                    <?php }elseif($hulqy[$key]->Type == 1){?>
                                                    <th style="text-align: center;width: 13%">گیٹ</th>
                                                	<?php }elseif($hulqy[$key]->Type == 2){?>
                                                	<th style="text-align: center;width: 13%">حصص</th>
                                                	<?php }?>
                                                    <th style="width: 11%"> میزان: </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Cow);
                                                        $final_total_Fresh_Cow += $total_fresh_Cow;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Cow);
                                                        $final_total_Old_Cow += $total_old_Cow;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Goat);
                                                        $final_total_Fresh_Goat += $total_fresh_Goat;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Goat); 
                                                        $final_total_Old_Goat += $total_old_Goat;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Sheep);
                                                        $final_total_Fresh_Sheep += $total_fresh_Sheep;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Sheep);
                                                        $final_total_Old_Sheep += $total_old_Sheep;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Camel);
                                                        $final_total_Fresh_Camel += $total_fresh_Camel;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Camel);
                                                        $final_total_Old_Camel += $total_old_Camel;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Buffalo);
                                                        $final_total_Fresh_Buffalo += $total_fresh_Buffalo;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Buffalo);
                                                        $final_total_Old_Buffalo += $total_old_Buffalo;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 15%">
                                                        <span><?php echo number_format($total_All_sum_Fresh);
                                                        $final_Fresh += $total_All_sum_Fresh;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_All_sum_Old);
                                                        $final_Old += $total_All_sum_Old;
                                                         ?></span>
                                                    </th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                        <?php $total_fresh_Cow=$total_old_Cow=$total_fresh_Goat=$total_old_Goat=$total_fresh_Sheep=$total_old_Sheep=$total_fresh_Camel=$total_old_Camel=$total_fresh_Buffalo=$total_old_Buffalo=$total_All_sum_Fresh=$total_All_sum_Old=0;?>
                                    <?php } }else{?>
                                    <div class="panel-body" style="direction: rtl;">
                                        <div class="table-responsive" style="width: 104%;margin-right: -2%;overflow-x: hidden;overflow-y: hidden;">
                                            <table class="table-bordered" id="dataTables-example">
                                                <tbody>
                                                <tr class="odd gradeX" style="line-height: 150%;">
                                                    <?php if($hulqy[$key]->Type == 0){?>
                                                    <th style="text-align: center;width: 13%">حلقہ</th>
                                                    <?php }elseif($hulqy[$key]->Type == 1){?>
                                                    <th style="text-align: center;width: 13%">گیٹ</th>
                                                	<?php }elseif($hulqy[$key]->Type == 2){?>
                                                	<th style="text-align: center;width: 13%">حصص</th>
                                                	<?php }?>
                                                    <th style="width: 11%"> میزان: </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Cow);
                                                        $final_total_Fresh_Cow += $total_fresh_Cow;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Cow);
                                                        $final_total_Old_Cow += $total_old_Cow;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Goat);
                                                        $final_total_Fresh_Goat += $total_fresh_Goat;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Goat); 
                                                        $final_total_Old_Goat += $total_old_Goat;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Sheep);
                                                        $final_total_Fresh_Sheep += $total_fresh_Sheep;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Sheep);
                                                        $final_total_Old_Sheep += $total_old_Sheep;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Camel);
                                                        $final_total_Fresh_Camel += $total_fresh_Camel;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Camel);
                                                        $final_total_Old_Camel += $total_old_Camel;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 11%">
                                                        <span><?php echo number_format($total_fresh_Buffalo);
                                                        $final_total_Fresh_Buffalo += $total_fresh_Buffalo;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_old_Buffalo);
                                                        $final_total_Old_Buffalo += $total_old_Buffalo;
                                                         ?></span>
                                                    </th>
                                                    <th style="text-align: center;width: 15%">
                                                        <span><?php echo number_format($total_All_sum_Fresh);
                                                        $final_Fresh += $total_All_sum_Fresh;
                                                         ?></span><br>
                                                        <span><?php echo number_format($total_All_sum_Old);
                                                        $final_Old += $total_All_sum_Old;
                                                         ?></span>
                                                    </th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php $total_fresh_Cow=$total_old_Cow=$total_fresh_Goat=$total_old_Goat=$total_fresh_Sheep=$total_old_Sheep=$total_fresh_Camel=$total_old_Camel=$total_fresh_Buffalo=$total_old_Buffalo=$total_All_sum_Fresh=$total_All_sum_Old=0;?>
                                <?php }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php }?>
    </div>
        <div class="panel-body" style="direction: rtl;">
	        <div class="table-responsive" style="overflow-x: hidden;overflow-y: hidden;">
	            <table class="table-bordered" id="dataTables-example">
	                <tbody>
	                <tr class="odd gradeX" style="line-height: 150%;">
	                    <th style="text-align: center;width: 13%"></th>
	                    <th style="width: 11%">کل میزان: </th>
	                    <th style="text-align: center;width: 11%">
	                        <span><?= number_format($final_total_Fresh_Cow); ?></span><br>
	                        <span><?= number_format($final_total_Old_Cow); ?></span>
	                    </th>
	                    <th style="text-align: center;width: 11%">
	                        <span><?= number_format($final_total_Fresh_Goat); ?></span><br>
	                        <span><?= number_format($final_total_Old_Goat); ?></span>
	                    </th>
	                    <th style="text-align: center;width: 11%">
	                        <span><?= number_format($final_total_Fresh_Sheep); ?></span><br>
	                        <span><?= number_format($final_total_Old_Sheep); ?></span>
	                    </th>
	                    <th style="text-align: center;width: 11%">
	                        <span><?= number_format($final_total_Fresh_Camel); ?></span><br>
	                        <span><?= number_format($final_total_Old_Camel); ?></span>
	                    </th>
	                    <th style="text-align: center;width: 11%">
	                        <span><?= number_format($final_total_Fresh_Buffalo); ?></span><br>
	                        <span><?= number_format($final_total_Old_Buffalo); ?></span>
	                    </th>
	                    <th style="text-align: center;width: 15%">
	                        <span><?= number_format($final_Fresh) ?></span><br>
	                        <span><?= number_format($final_Old) ?></span>
	                    </th>
	                </tr>
	                </tbody>
	            </table>
	        </div>
	    </div>
        <?php date_default_timezone_set('Asia/Karachi');?>
        <span><?= date('l d-m-Y h:i:s');?></span>
</div>
</body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">

    function myFunction() {
        window.print();
    }
</script>