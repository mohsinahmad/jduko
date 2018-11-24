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
				<label class="label-control" style="font-size: 1.4em;">مرکز حصص قربانی زیر اہتمام</label><br>
					<img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:22%; max-width:330px;"><br>
					<h2 style="margin-top: 0px;text-decoration: underline;font-size: 20px">کورنگی/گلشن اقبال/نانکواڑہ</h2>
					<h4 style="font-size: 16px;margin-top: 3%;">ادائیگی واؤچر </h4>
				</div>
			</div>
			<div class="level">
				<div style="margin-right: 2%;">
					<div>
						<label class="label-control">واؤچر نمبر:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 30%;text-align: center;" value="<?= $Expence_Detail[0]->Voucher_Number?>" readonly>
					</div>
					<div style="">
						<label class="label-control">ادائیگی بنام:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 29.5%;text-align: center;line-height: 0;" value="<?= $Expence_Detail[0]->Receiver_Name?>" readonly>
					</div>
					<div style="margin-right: 65%;margin-top: -8.5%;">
						<label class="label-control">عیسوی تاریخ:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 55%;text-align: center;" value="<?= $Expence_Detail[0]->DateG?>" readonly>
					</div><br><br>
					<div style="margin-right: 65%;margin-top: -3.5%;">
						<label class="label-control">ہجری تاریخ:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 57%;text-align: center;" value="<?= $Expence_Detail[0]->DateH?>" readonly>
					</div>
					<div style="margin-top: 2%;">
						<label class="label-control">رقم ہندسوں میں:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 25.6%;text-align: center;" value="=/<?= $AmountInNumber?>" readonly>
					</div>
					<div style="margin-right: 40%;margin-top: -4.5%;">
						<label class="label-control">رقم عبارت میں:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 69.5%;text-align: center;line-height: 0%;" value="<?= $AmountInWords?>" readonly>
					</div>
					<div style="margin-bottom: 4%;">
						<label class="label-control">بابت:</label>
						<textarea rows="1" cols="80" type="text" class="VoucherMOdal" readonly="" style=" font-family: 'Noto Nastaliq Urdu', serif;width: 89%;overflow:hidden;line-height: 212%;">
							<?php foreach($Expence_Detail as $key => $value){
							echo $value->type.',';
						} ?>
						</textarea>
					</div>
					<div>
						<label class="label-control">دستخط وصول کنندہ:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 32.8%;text-align: center;" value="" readonly>
					</div>
					<div style="margin-right: 48%;margin-top: -3.5%;">
						<label class="label-control">کیشیئر:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 78.5%;text-align: center;" value="" readonly>
					</div>
					<div style="margin-top: 2.5%;">
						<label class="label-control">وپتہ:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 42%;text-align: center;" value="" readonly>
					</div>
					<div style="margin-top: 2.5%;">
						<label class="label-control">نگران مرکز:</label>
						<input type="text" class="VoucherMOdal" name="" style="width:25%;text-align: center;" value="" readonly>
					</div>
					<div style="margin-right: 33%;margin-top: -3.5%;">
						<label class="label-control">اکاؤنٹنٹ:</label>
						<input type="text" class="VoucherMOdal" name="" style="width: 30%;text-align: center;" value="" readonly>
					</div>
					<div style="margin-right: 61%;margin-top: -3.5%;">
						<label class="label-control">چیف اکاؤنٹنٹ:</label>
						<input type="text" class="VoucherMOdal" name="" style="width:55%;text-align: center;" value="" readonly>
					</div>
					<div style="margin-top: 2.5%;">
						<label class="label-control">تصدیق از نگرانِ اعلیٰ مہم حصص قربانی:</label>
						<input type="text" class="VoucherMOdal" name="" style="width:68%;text-align: center;" value="" readonly>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    // $( document ).ready(function() {
    //     window.print();
    // });
    function myFunction() {
        window.print();
    }
</script>