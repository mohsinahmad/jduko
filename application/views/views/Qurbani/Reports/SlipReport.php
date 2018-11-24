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
					<h2 style="margin-top: 0px;font-size: 20px">یوم وار کی رپورٹ</h2>
					<!-- <h4 style="font-size: 16px"><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4> -->
				</div>
			</div>
			<div class="level">
				<div>
                    <?php $total_received_Amount0=0;$total_received_Amount1=0;$total_received_Amount2=0;?>
					<div class="row two">
						<div class="col-lg-12">
								<div class="panel-body">
									<div class="row">
									</div>
									<div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
										<table class="table-bordered">
											<thead>
											<tr style="line-height: 243%;">
												<th style="text-align: center">تاریخ </th>
												<th style="text-align: center">قربانی کا دن</th>
												<th style="text-align: center">رسیدوں کی تعداد	</th>
												<th style="text-align: center">حصص/گائے</th>
												<th style="text-align: center">رقم</th>
												<th style="text-align: center">خود خرید کردہ</th>
												<th style="text-align: center">رقم</th>
												<th style="text-align: center"> کل رقم</th>
											</tr>
											</thead>
											<?php $cow = 0;$hissa = 0;$final = 0; $cow1 = 0;$hissa1 = 0;$final1 = 0; $cow2 = 0;$hissa2 = 0;$final2 = 0; $slip = 0;$total_hissa =0;$share_amount =0;$total_self_cow =0;$total_self_amount =0;$slip1 = 0;$total_hissa1 =0;$share_amount1 =0;$total_self_cow1 =0;$total_self_amount1 =0;$slip2 = 0;$total_hissa2 =0;$share_amount2 =0;$total_self_cow2 =0;$total_self_amount2 =0; $total_amount0 =0; $total_amount1 =0; $total_amount2 =0; ?>
											<tbody>
                                            <!-- foreach loop goes here -->
                                            <?php foreach ($report_dates as $key => $report_date) {?>
                                                <tr>
                                                    <td rowspan="3" style="border-bottom: 2px solid;"><?= $report_date->Slip_Date_G?></td>
                                                    <td style="text-align: center">۱۰ ذی الحج</td>
                                                    <?php if($report_data[$key]['share'][0]['slip_count'] == 0) {?>
                                                    <td> - </td>
                                                    <?php }else{?>
                                                    <td style="text-align: center"><?php echo $report_data[$key]['share'][0]['slip_count'];
                                                    $slip += $report_data[$key]['share'][0]['slip_count'];
                                                    ?></td>
                                                    <?php }?>
                                                <?php if($report_data[$key]['share'][0]['Quantity'] == 0){ ?>
                                                	<td>-</td>
                                                	<?php } else {
                                                		 $cow = floor($report_data[$key]['share'][0]['Quantity'] / 7);
                                                		 $hissa = $cow * 7;
                                                		 $final =  $report_data[$key]['share'][0]['Quantity'] - $hissa;?>
                                                    <td style="text-align: center"><?php echo $cow.'/'.$final;
                                                    $total_hissa += $report_data[$key]['share'][0]['Quantity'];
                                                    ?></td>
                                                <?php } ?>
                                                	<?php if($report_data[$key]['share'][0]['Total_Amount'] == 0){?>
                                                	<td>-</td>
                                                	<?php } else { ?>
                                                    	<td style="text-align: center"><?php echo number_format($report_data[$key]['share'][0]['Total_Amount']);
                                                    	$share_amount += $report_data[$key]['share'][0]['Total_Amount']; ?></td>
                                                		<?php }?>
                                                		<?php if($report_data[$key]['self'][0]['Quantity'] == 0){ ?>
                                                			<td>-</td>
                                                		<?php } else {?>
                                                    	<td style="text-align: center"><?php echo floor($report_data[$key]['self'][0]['Quantity'] / 7);
                                                    	$total_self_cow += $report_data[$key]['self'][0]['Quantity'];
                                                    	?></td>
                                                		<?php }?>
                                                		<?php if($report_data[$key]['self'][0]['Total_Amount'] == 0){?>
                                                		<td>-</td>
                                                		<?php } else {?>
                                                    <td style="text-align: center"><?php echo number_format($report_data[$key]['self'][0]['Total_Amount']);
                                                    $total_self_amount +=$report_data[$key]['self'][0]['Total_Amount'];
                                                    ?></td>
                                                    <?php }?>
                                                    <?php $total_received_Amount0 = $report_data[$key]['share'][0]['Total_Amount'] + $report_data[$key]['self'][0]['Total_Amount'];
                                                    	$total_amount0 += $total_received_Amount0; 
                                                     ?>
                                                    <td style="text-align: center"><?= ($total_received_Amount0 == 0)?'-':number_format($total_received_Amount0)?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center">۱۱ ذی الحج</td>
                                                    <?php if($report_data[$key]['share'][1]['slip_count'] == 0) {?>
                                                    <td> - </td>
                                                    <?php }else{?>
                                                    <td style="text-align: center"><?php echo $report_data[$key]['share'][1]['slip_count'];
                                                    $slip1 += $report_data[$key]['share'][1]['slip_count'];
                                                    ?></td>
                                                    <?php }?>
                                                <?php if($report_data[$key]['share'][1]['Quantity'] == 0){ ?>
                                                	<td>-</td>
                                                	<?php } else {
                                                		 $cow1 = floor($report_data[$key]['share'][1]['Quantity'] / 7);
                                                		 $hissa1 = $cow1 * 7;
                                                		 $final1 =  $report_data[$key]['share'][1]['Quantity'] - $hissa1;?>
                                                    <td style="text-align: center"><?php echo $cow1.'/'.$final1;
                                                    $total_hissa1 += $report_data[$key]['share'][1]['Quantity'];
                                                    ?></td>
                                                <?php } ?>
                                                	<?php if($report_data[$key]['share'][1]['Total_Amount'] == 0){?>
                                                	<td>-</td>
                                                	<?php } else { ?>
                                                    	<td style="text-align: center"><?php echo number_format($report_data[$key]['share'][1]['Total_Amount']);
                                                    	$share_amount1 += $report_data[$key]['share'][1]['Total_Amount']; ?></td>
                                                		<?php }?>
                                                		<?php if($report_data[$key]['self'][1]['Quantity'] == 0){ ?>
                                                			<td>-</td>
                                                		<?php } else {?>
                                                    	<td style="text-align: center"><?php echo floor($report_data[$key]['self'][1]['Quantity'] / 7);
                                                    	$total_self_cow1 += $report_data[$key]['self'][1]['Quantity'];
                                                    	?></td>
                                                		<?php }?>
                                                		<?php if($report_data[$key]['self'][1]['Total_Amount'] == 0){?>
                                                		<td>-</td>
                                                		<?php } else {?>
                                                    <td style="text-align: center"><?php echo number_format($report_data[$key]['self'][1]['Total_Amount']);
                                                    $total_self_amount1 +=$report_data[$key]['self'][1]['Total_Amount'];
                                                    ?></td>
                                                    <?php }?>
                                                    <?php $total_received_Amount1 = $report_data[$key]['share'][1]['Total_Amount'] + $report_data[$key]['self'][1]['Total_Amount'];
                                                    	$total_amount1 += $total_received_Amount1;
                                                     ?>
                                                    <td style="text-align: center"><?= ($total_received_Amount1 == 0)?'-':number_format($total_received_Amount1)?></td>
                                                </tr>
                                                <tr>
                                                    <td style="text-align: center;border-bottom: 2px solid;">۱۲ ذی الحج</td>
                                                    <?php if($report_data[$key]['share'][2]['slip_count'] == 0) {?>
                                                    <td style="text-align: center;border-bottom: 2px solid;"> - </td>
                                                    <?php }else{?>
                                                    <td style="text-align: center;border-bottom: 2px solid;"><?php echo $report_data[$key]['share'][2]['slip_count'];
                                                    $slip2 += $report_data[$key]['share'][2]['slip_count'];
                                                    ?></td>
                                                    <?php }?>
                                                <?php if($report_data[$key]['share'][2]['Quantity'] == 0){ ?>
                                                	<td style="text-align: center;border-bottom: 2px solid;">-</td>
                                                	<?php } else {
                                                		 $cow2 = floor($report_data[$key]['share'][2]['Quantity'] / 7);
                                                		 $hissa2 = $cow2 * 7;
                                                		 $final2 =  $report_data[$key]['share'][2]['Quantity'] - $hissa2;?>
                                                    <td style="text-align: center;border-bottom: 2px solid;"><?php echo $cow2.'/'.$final2;
                                                    $total_hissa2 += $report_data[$key]['share'][2]['Quantity'];
                                                    ?></td>
                                                <?php } ?>
                                                	<?php if($report_data[$key]['share'][2]['Total_Amount'] == 0){?>
                                                	<td style="text-align: center;border-bottom: 2px solid;">-</td>
                                                	<?php } else { ?>
                                                    	<td style="text-align: center;border-bottom: 2px solid;"><?php echo number_format($report_data[$key]['share'][2]['Total_Amount']);
                                                    	$share_amount2 += $report_data[$key]['share'][2]['Total_Amount']; ?></td>
                                                		<?php }?>
                                                		<?php if($report_data[$key]['self'][2]['Quantity'] == 0){ ?>
                                                			<td style="text-align: center;border-bottom: 2px solid;">-</td>
                                                		<?php } else {?>
                                                    	<td style="text-align: center;border-bottom: 2px solid;"><?php echo floor($report_data[$key]['self'][2]['Quantity'] / 7);
                                                    	$total_self_cow2 += $report_data[$key]['self'][2]['Quantity'];
                                                    	?></td>
                                                		<?php }?>
                                                		<?php if($report_data[$key]['self'][2]['Total_Amount'] == 0){?>
                                                		<td style="text-align: center;border-bottom: 2px solid;">-</td>
                                                		<?php } else {?>
                                                    <td style="text-align: center;border-bottom: 2px solid;"><?php echo number_format($report_data[$key]['self'][2]['Total_Amount']);
                                                    $total_self_amount2 +=$report_data[$key]['self'][2]['Total_Amount'];
                                                    ?></td>
                                                    <?php }?>
                                                    <?php $total_received_Amount2 = $report_data[$key]['share'][2]['Total_Amount'] + $report_data[$key]['self'][2]['Total_Amount'];
                                                    	$total_amount2 += $total_received_Amount2;
                                                     ?>
                                                    <td style="text-align: center;border-bottom: 2px solid;"><?= ($total_received_Amount2 == 0)?'-':number_format($total_received_Amount2)?></td>
                                                </tr>
                                            <?php }?>
											</tbody>
											<?php $final_slip =0;$final_total_cow =0; $final_total_self_cow =0;$final_total_self_amount =0;$final_total_received_Amount =0;$final_total_cow =0;$final_cow_hissa =0;?>
											<tfoot>
											<tr style="line-height: 250%;">
												<th></th>
												<th><span style="float: right;"> میزان:</span></th>
												<?php $final_slip = $slip+$slip1+$slip2;?>
												<th style="text-align: center"><?php echo $final_slip; ?></th>
												<?php 
												$final_total_cow = floor(($total_hissa+$total_hissa1+$total_hissa2) / 7);
												$final_total_hissa = $final_total_cow * 7;
												$final_cow_hissa = ($total_hissa+$total_hissa1+$total_hissa2) - $final_total_hissa
												?>
												<th style="text-align: center"><?php echo $final_total_cow.'/'.$final_cow_hissa?></th>
												<?php $final_share_amount = $share_amount+$share_amount1+$share_amount2;?>
												<th style="text-align: center"><?php echo number_format($final_share_amount)?></th>
												<?php $final_total_self_cow = $total_self_cow+$total_self_cow1+$total_self_cow2?>
												<th style="text-align: center"><?php echo round($final_total_self_cow / 7)?></th>
												<?php $final_total_self_amount = $total_self_amount+$total_self_amount1+$total_self_amount2;?>
												<th style="text-align: center"><?php echo number_format($final_total_self_amount)?></th>
												<?php $final_total_received_Amount = $total_amount0+$total_amount1+$total_amount2?>
												<th style="text-align: center"><?php echo number_format($final_total_received_Amount) ?></th>
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
		<!-- <footer class ="footer" id="pagebreak">
			<div style="direction: ltr;">
				<hr> <?php date_default_timezone_set('Asia/Karachi');?>
				<span style="float: left;"><?= date('l d-m-Y h:i:s');?></span>
				<hr>
			</div>
		</footer> -->
	</body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    function myFunction() {
        window.print();
    }
</script>