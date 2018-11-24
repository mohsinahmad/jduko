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
					<h2 style="margin-top: 0px;font-size: 20px">تفصیل انعامات برائے نگران حلقہ جات چرم قربانی ۱۴۳۸ھ</h2>
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
												<th style="text-align: center;width: 25%;">حلقہ/مرکز</th>
												<th style="text-align: center">گائے</th>
												<th style="text-align: center">بکرا</th>
												<th style="text-align: center">دنبہ</th>
												<th style="text-align: center">اونٹ</th>
												<th style="text-align: center">بھینس</th>
												<th style="text-align: center">کل تعداد/رقم</th>
												<th style="text-align: center">انعامی رقم</th>
<!--												<th style="text-align: center">انعامی رقم<br>بحساب 28 فیصد</th>-->
												<th style="text-align: center">اضافی انعامی رقم</th>
<!--												<th style="text-align: center">اضافہ<br>دو فیصد انعام</th>-->
												<th style="text-align: center">کل انعامی رقم</th>
<!--												<th style="text-align: center">بعد ازاضافہ<br>کل انعام 30%</th>-->
											</tr>
											</thead>
											<tbody>
                                            <?php $total_amount=$Full_Inaam=$Full_Izafi_Inaam=$Full_Total_Inaam=0;
                                            $fresh_quantity_total1=$old_quantity_total1=$amount_total1=$fresh_quantity_total2=$old_quantity_total2=$amount_total2=$fresh_quantity_total3=$old_quantity_total3=$amount_total3=$fresh_quantity_total4=$old_quantity_total4=$amount_total4=$fresh_quantity_total5=$old_quantity_total5=$amount_total5 = 0;
                                            foreach ($report_Data as $key => $report_Datum) {
                                                if ($report_Datum != array()){?>
                                                    <tr>
                                                        <td style="text-align: right">
                                                            <span><?= $report_Datum[0]->supervisor_name?></span><br>
                                                            <span></span><br>
                                                            <span><?= $report_Datum[0]->hulqa_name?></span>
                                                        </td>
                                                        <td style="text-align: right">
                                                            <span><?php echo $report_Datum[0]->fresh_quantity;
                                                            $fresh_quantity_total1 += $report_Datum[0]->fresh_quantity;
                                                            ?></span><br>
                                                            <span><?php echo $report_Datum[0]->old_quantity;
                                                            	$old_quantity_total1 += $report_Datum[0]->old_quantity;
                                                            ?></span><br>
                                                            <span><?php echo number_format($report_Datum[0]->amount);
                                                            	$amount_total1 +=$report_Datum[0]->amount;
                                                            ?></span>
                                                        </td>
                                                        <td style="text-align: right">
                                                            <span><?php echo $report_Datum[1]->fresh_quantity;
                                                            	$fresh_quantity_total2 += $report_Datum[1]->fresh_quantity;
                                                            ?></span><br>
                                                            <span><?php echo $report_Datum[1]->old_quantity;
                                                            $old_quantity_total2 += $report_Datum[1]->old_quantity;
                                                            ?></span><br>
                                                            <span><?php echo number_format($report_Datum[1]->amount);
                                                            	$amount_total2 +=$report_Datum[1]->amount;
                                                            ?></span>
                                                        </td>
                                                        <td style="text-align: right">
                                                            <span><?php echo $report_Datum[2]->fresh_quantity;
                                                            	$fresh_quantity_total3 += $report_Datum[2]->fresh_quantity;
                                                            ?></span><br>
                                                            <span><?php echo $report_Datum[2]->old_quantity;
                                                            	$old_quantity_total3 += $report_Datum[2]->old_quantity;
                                                            ?></span><br>
                                                            <span><?php echo number_format($report_Datum[2]->amount);
                                                            	$amount_total3 += $report_Datum[2]->amount;
                                                            ?></span>
                                                        </td>
                                                        <td style="text-align: right">
                                                            <span><?php echo $report_Datum[3]->fresh_quantity;
                                                            	$fresh_quantity_total4 += $report_Datum[3]->fresh_quantity;
                                                            ?></span><br>
                                                            <span><?php echo $report_Datum[3]->old_quantity;
                                                            	$old_quantity_total4 += $report_Datum[3]->old_quantity;
                                                            ?></span><br>
                                                            <span><?php echo number_format($report_Datum[3]->amount);
                                                            	$amount_total4 += $report_Datum[3]->amount;
                                                            ?></span>
                                                        </td>
                                                        <td style="text-align: right">
                                                            <span><?php echo $report_Datum[4]->fresh_quantity;
                                                            	$fresh_quantity_total5 += $report_Datum[4]->fresh_quantity;
                                                            ?></span><br>
                                                            <span><?php echo $report_Datum[4]->old_quantity;
                                                            	$old_quantity_total5 += $report_Datum[4]->old_quantity;
                                                            ?></span><br>
                                                            <span><?php echo number_format($report_Datum[4]->amount);
                                                            	$amount_total5 += $report_Datum[4]->amount;
                                                            ?></span><br>
                                                        </td>
                                                        <?php $fresh_quantity=$old_quantity=$amount=$inaam=$Izafi_inaam=$total_Inaam=0;
                                                        $fresh_quantity = $report_Datum[0]->fresh_quantity + $report_Datum[1]->fresh_quantity + $report_Datum[2]->fresh_quantity + $report_Datum[3]->fresh_quantity + $report_Datum[4]->fresh_quantity;
                                                        $old_quantity = $report_Datum[0]->old_quantity + $report_Datum[1]->old_quantity + $report_Datum[2]->old_quantity + $report_Datum[3]->old_quantity + $report_Datum[4]->old_quantity;
                                                        $amount = $report_Datum[0]->amount + $report_Datum[1]->amount + $report_Datum[2]->amount + $report_Datum[3]->amount + $report_Datum[4]->amount;?>
                                                        <td style="text-align: right">
                                                            <span><?= $fresh_quantity?></span><br>
                                                            <span><?= $old_quantity?></span><br>
                                                            <span><?= number_format($amount)?></span>
                                                        </td>
                                                        <?php isset($percent[$key])?$inaam=$amount*$Inami_Raqam[$key]:$inaam=$Inami_Raqam[$key];?>
                                                        <td style="text-align: right">
                                                            <span></span><br>
                                                            <span><?= isset($percent[$key])?('('.$Inami_Raqam[$key]*100).'%)':''?></span><br>
                                                            <span><?= number_format(round($inaam))?></span>
                                                        </td>
                                                        <?php isset($Izafi_Inami_percent[$key])?$Izafi_inaam=$amount*$Izafi_Inami_Raqam[$key]:$Izafi_inaam=$Izafi_Inami_Raqam[$key];?>
                                                        <td style="text-align: right">
                                                            <span></span><br>
                                                            <span><?= isset($Izafi_Inami_percent[$key])?('('.$Izafi_Inami_Raqam[$key]*100).'%)':''?></span><br>
                                                            <span><?= number_format(round($Izafi_inaam))?></span>
                                                        </td>
                                                        <?php $total_Inaam = $inaam + $Izafi_inaam;?>
                                                        <td style="text-align: right">
                                                            <span></span><br>
                                                            <span><?= isset($Izafi_Inami_percent[$key]) && isset($percent[$key])?'('.(($Izafi_Inami_Raqam[$key]*100)+($Inami_Raqam[$key]*100)).'%)':''?></span><br>
                                                            <span><?= number_format(round($total_Inaam))?></span>
                                                        </td>
                                                    </tr>
                                                <?php $total_amount += $amount; $Full_Inaam += $inaam; $Full_Izafi_Inaam += $Izafi_inaam; $Full_Total_Inaam += $total_Inaam;
                                                }
                                            } ?>
											</tbody>
											<tfoot>
											<tr style="line-height: 150%;">
												<th style="text-align: center;"><span>کل قابل ادا انعام</span></th>
												<th>
													<span><?php echo number_format($fresh_quantity_total1); ?></span><br>
													<span><?php echo number_format($old_quantity_total1); ?></span><br>
													<span><?php echo number_format($amount_total1) ?></span>
												</th>
												<th>
													<span><?php echo number_format($fresh_quantity_total2); ?></span><br>
													<span><?php echo number_format($old_quantity_total2); ?></span><br>
													<span><?php echo number_format($amount_total2) ?></span>
												</th>
												<th>
													<span><?php echo number_format($fresh_quantity_total3); ?></span><br>
													<span><?php echo number_format($old_quantity_total3); ?></span><br>
													<span><?php echo number_format($amount_total3) ?></span>
												</th>
												<th>
													<span><?php echo number_format($fresh_quantity_total4); ?></span><br>
													<span><?php echo number_format($old_quantity_total4); ?></span><br>
													<span><?php echo number_format($amount_total4) ?></span>
												</th>
												<th>
													<span><?php echo number_format($fresh_quantity_total5); ?></span><br>
													<span><?php echo number_format($old_quantity_total5); ?></span><br>
													<span><?php echo number_format($amount_total5) ?></span>
												</th>
												<th style="text-align: right">
													<span></span><br>
													<span></span><br>
													<span><?= number_format($total_amount)?></span>
													</th>
												<th style="text-align: right">
													<span></span><br>
													<span></span><br>
													<span><?= number_format($Full_Inaam)?></span>
												</th>
                                                <th style="text-align: right">
                                                	<span></span><br>
                                                	<span></span><br>
                                                	<span><?= number_format($Full_Izafi_Inaam)?></span>
                                                </th>
                                                <th style="text-align: right">
                                                	<span></span><br>
                                                	<span></span><br>
                                                	<span><?= number_format($Full_Total_Inaam)?></span>
                                                </th>
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