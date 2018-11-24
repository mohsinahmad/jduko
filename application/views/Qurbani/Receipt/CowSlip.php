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
		<?php $dyna_total = 0;?>
		<?php foreach($Cows as $C_key => $value){?>
        <div class="content" style="page-break-before: always">
			<div class="row" id="content">
				<div id="hide">
					<button onclick="myFunction()">Print</button>
				</div>
				<div style="text-align: center;">
					<img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:22%; max-width:330px;">
					<h2 style="margin-top: 0px;font-size: 35px">حصص قربانی ۱۴۳۸ھ</h2>
				</div>
			</div>
			<div style="text-align: center;margin-top: 2%;">
				<label class="form-label" style="font-size: 20px">مرکز :</label>
				<?php if($Per_Head[0]->Loc == 41){?>
				  <input type="text" class="VoucherMOdal" name="" style="width: 55%;text-align: center;line-height: 1%" value="کورنگی" readonly>
				 <?php } elseif($Per_Head[0]->Loc == 43){?>
				  <input type="text" class="VoucherMOdal" name="" style="width: 55%;text-align: center;line-height: 1%" value="گلشن اقبال" readonly>
				 <?php } elseif($Per_Head[0]->Loc == 44){?>
				  <input type="text" class="VoucherMOdal" name="" style="width: 55%;text-align: center;line-height: 1%" value="نانکواڑہ" readonly>
				 <?php }?>
			</div>
			<div class="level">
				<div>
					<div class="row two">
						<div class="col-lg-12">
								<div class="panel-body">
								<div style="margin-top: -4%;">
									<div style="margin-right: -5%">
										<label class="form-label" style="">تاریخ :</label>
										<?php if($value[0]->Day == 1){?>
									  	<input type="text" class="VoucherMOdal" name="" style="width: 28%;line-height: 0%;" value="۱۰ ذی الحج" readonly>
									  	<?php } elseif($value[0]->Day == 2){?>
									  	<input type="text" class="VoucherMOdal" name="" style="width: 28%;line-height: 0%;" value="۱۱ ذی الحج" readonly>
									  	<?php } elseif($value[0]->Day == 3){?>
									  	<input type="text" class="VoucherMOdal" name="" style="width: 28%;line-height: 0%;" value="۱۲ ذی الحج" readonly>
									  	<?php }?>
									</div>
									<div style="margin-right: 32%;margin-top: -3.6%;">
										<label class="form-label" style="">گاےَ نمبر :</label>
									  	<input type="text" class="VoucherMOdal" name="" style="width: 35%;" value="<?= $value[0]->Code?>" readonly>
									</div>
									<div style="margin-right: 67%;margin-top: -3.6%;">
										<label class="form-label" style="">وقت :</label>
									  	<input type="text" class="VoucherMOdal" name="" style="width: 84%;" value="<?= $value[0]->Time?>" readonly>
									</div>
								</div>
									<div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;line-height: 529%;">
										<table class="table-bordered">
											<thead>
											<tr style="line-height: 320%;">
												<th style="text-align: center;width: 5%;">نمبر شمار</th>
												<th style="text-align: center;width: 7%;">رسید نمبر</th>
												<th style="text-align: center;width: 25%">نام حصہ داران</th>
												<th style="text-align: center;width: 8%;">جمع شدہ رقم</th>
												<th style="text-align: center;width: 7%;">صرفہ فی حصہ</th>
												<th style="text-align: center;width: 9%;">واپسی رقم بذ مہ دارالعلوم</th>
												<th style="text-align: center;width: 10%;">بقایا رقم بذ مہ حصہ دار</th>
												<th style="text-align: center;width: 12%;">دسخط وصو لیا بی رقم مندرجہ کالم 6</th>
												<th style="text-align: center;width: 17%">کیفیت</th>
											</tr>
											<tr>
												<th style="text-align: center">1</th>
												<th style="text-align: center">2</th>
												<th style="text-align: center">3</th>
												<th style="text-align: center">4</th>
												<th style="text-align: center">5</th>
												<th style="text-align: center">6</th>
												<th style="text-align: center">7</th>
												<th style="text-align: center">8</th>
												<th style="text-align: center">9</th>
											</tr>
											</thead>
											<tbody>
											<?php $per_head_recive = 0; $per_head_expence = 0; $total = 0; $dyna = 0; $wasol = 0; $total_round = 0; $final = 0;
											foreach($value as $key => $item){?>
											<?php if($item->Self_Cow == 1){?>
											<tr>
												<td style="text-align: center;"><?= ++$key;?></td>
												<td style="text-align: center;"><?= $item->Slip_Number?></td>
												<td style="text-align: center;"><?= $item->Name?></td>
                                                <?php ($item->Paid == 1)?$paid_Amount=$Per_Head[0]->Independent_Expance:$paid_Amount=0;?>
												<td style="text-align: center;"><?= number_format($paid_Amount);
												$per_head_recive = $paid_Amount;
//												$per_head_recive = $Per_Head[0]->Amount; ?>
                                                </td>
												<td style="text-align: center;">
                                                </td>
												<td style="text-align:center"></td>
						                       	<td style="text-align:center"></td>
						                        
												<!-- <td style="text-align: center;"><?= $wasol?></td> -->
												<td style="text-align: center;"></td>
												<td style="text-align: center;"></td>
											</tr>
											<?php } else{?>
											<tr>
												<td style="text-align: center;"><?= ++$key;?></td>
												<td style="text-align: center;"><?= $item->Slip_Number?></td>
												<td style="text-align: center;"><?= $item->Name?></td>
                                                <?php ($item->Paid == 1)?$paid_Amount=$Per_Head[0]->Amount:$paid_Amount=0;?>
												<td style="text-align: center;"><?= number_format($paid_Amount);
												$per_head_recive = $paid_Amount;
//												$per_head_recive = $Per_Head[0]->Amount; ?>
                                                </td>
<td style="text-align: center;"><?php if (isset($TotalExpence[$item->Code][0]->Per_hissa_amoun)) {
echo number_format(round($TotalExpence[$item->Code][0]->Per_hissa_amoun));
												$per_head_expence = round($TotalExpence[$item->Code][0]->Per_hissa_amoun); } ?>
                                                </td>
<?php $total = $per_head_recive - $per_head_expence;
$total_round = ($total / 50);
$final = (round($total_round)) * 50;
($total > 0)?$dyna = $final:$wasol = $final;
if (isset($dyna)) {
if($dyna < 0){ $total_dyna_new = ($dyna * -1);?>
<td style="text-align:center"><?= number_format($total_dyna_new)?></td>
<?php }else{?>
 <td style="text-align:center"><?php echo number_format($total);
$dyna_total += $dyna;
?></td>
						                        <?php } }
						                        if (isset($wasol)) {
						                        if($wasol < 0){ $total_wasol_new = ($wasol * -1);?>
						                            <td style="text-align:center"><?= number_format($total_wasol_new)?></td>
						                        <?php }else{?>
						                            <td style="text-align:center"><?= number_format($wasol)?></td>
						                        <?php } }?>
												<!-- <td style="text-align: center;"><?= $wasol?></td> -->
												<td style="text-align: center;"></td>
												<td style="text-align: center;"></td>
											</tr>
											<?php } }?>
											</tbody>
											<!-- <tfoot>
											<tr style="line-height: 250%;">
												<th></th>
												<th></th>
												<th><span style="float: right;"> میزان:</span></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
												<th></th>
													<th style="text-align: center"></th>
											</tr>
											</tfoot> -->
										</table>
									</div>
								</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php }
		print_r($dyna_total);
		?>

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