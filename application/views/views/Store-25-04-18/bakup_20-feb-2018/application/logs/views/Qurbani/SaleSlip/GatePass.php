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
            max-width: 600px;
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
        .VoucherMOdal1{
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
            <h2 style="margin-top: 10px;font-size: 20px">گیٹ پاس</h2>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div style=" margin-right: 14.7%;margin-top: -5%;">
                                <h4 style=""><span>سلپ نمبر : </span><?php echo $Slips[0]->Slip_Number ?></h4>
                            </div>
                            <div style="margin-right: 15%;margin-top: 4%;">
                                <h4 style="font-size: 16px"><span>تاریخ :</span><span><?php echo $Slips[0]->Slip_DateH ?></span><span>  <br>  </span><span style="margin-right: 8%;"><?php echo $Slips[0]->Slip_DateG ?></span></h4>
                            </div>
                            <div style="margin-right: 28%;margin-top: 2%;">
                                <h4 style=""><span>وینڈر نام : </span><?php echo $Slips[0]->Name ?></h4>
                            </div>

                        </div>
                        <?php $Cow_Fresh = $Cow_Old = $Goat_Fresh = $Goat_Old = $Sheep_Fresh = $Sheep_Old = $Camel_Fresh = $Camel_Old = $Buffelo_Fresh = $Buffelo_Old = $total= 0?>
                        <div>
                            <div style="margin-right: 15%;margin-top: 2%;">
                                <?php if($Slips[0]->Cow_Fresh > 0){?>
                                    <span>گائے تازہ : <?php echo $Slips[0]->Cow_Fresh ?></span><br><?php }?>
                                <?php if($Slips[0]->Cow_Old > 0){?>
                                    <span>گائے باسی : <?php echo $Slips[0]->Cow_Old ?></span><br><?php }?>
                                <?php if($Slips[0]->Goat_Fresh > 0){?>
                                    <span>بکرا تازہ : <?php echo $Slips[0]->Goat_Fresh ?></span><br><?php }?>
                                <?php if($Slips[0]->Goat_Old > 0){?>
                                    <span>بکرا باسی : <?php echo $Slips[0]->Goat_Old ?></span><br><?php }?>
                                <?php if($Slips[0]->Sheep_Fresh > 0){?>
                                    <span>دنبہ تازہ : <?php echo $Slips[0]->Sheep_Fresh ?></span><br><?php }?>
                                <?php if($Slips[0]->Sheep_Old > 0){?>
                                    <span>دنبہ باسی : <?php echo $Slips[0]->Sheep_Old ?></span><br><?php }?>
                                <?php if($Slips[0]->Camel_Fresh > 0){?>
                                    <span>اونٹ تازہ : <?php echo $Slips[0]->Camel_Fresh ?></span><br><?php }?>
                                <?php if($Slips[0]->Camel_Old > 0){?>
                                    <span>اونٹ باسی : <?php echo $Slips[0]->Camel_Old ?></span><br><?php }?>
                                <?php if($Slips[0]->Buffelo_Fresh > 0){?>
                                    <span>بھینس تازہ : <?php echo $Slips[0]->Buffelo_Fresh ?></span><br><?php }?>
                                <?php if($Slips[0]->Buffelo_Old > 0){?>
                                    <span>بھینس باسی : <?php echo $Slips[0]->Buffelo_Old ?></span><br><?php }?>
                            </div>

                            <?php if($Slips[0]->Cow_Fresh > 0){
                                $Cow_Fresh = $Slips[0]->Cow_Fresh;}
                            if($Slips[0]->Cow_Old > 0){
                                $Cow_Old =  $Slips[0]->Cow_Old; }
                            if($Slips[0]->Goat_Fresh > 0){
                                $Goat_Fresh = $Slips[0]->Goat_Fresh; }
                            if($Slips[0]->Goat_Old > 0){
                                $Goat_Old = $Slips[0]->Goat_Old; }
                            if($Slips[0]->Sheep_Fresh > 0){
                                $Sheep_Fresh = $Slips[0]->Sheep_Fresh; }
                            if($Slips[0]->Sheep_Old > 0){
                                $Sheep_Old = $Slips[0]->Sheep_Old;}
                            if($Slips[0]->Camel_Fresh > 0){
                                $Camel_Fresh = $Slips[0]->Camel_Fresh; }
                            if($Slips[0]->Camel_Old > 0){
                                $Camel_Old = $Slips[0]->Camel_Old;}
                            if($Slips[0]->Buffelo_Fresh > 0){
                                $Buffelo_Fresh = $Slips[0]->Buffelo_Fresh;}
                            if($Slips[0]->Buffelo_Old > 0){
                                $Buffelo_Old = $Slips[0]->Buffelo_Old; }?>
                            <div style="margin-right: 15%;margin-top: 4%;">
                                <?php $total = $Cow_Fresh+$Cow_Old+$Goat_Fresh+$Goat_Old+$Sheep_Fresh+$Sheep_Old+$Camel_Fresh+ $Camel_Old+$Buffelo_Fresh+$Buffelo_Old ?>
                                <span>کل: <?php echo number_format($total)?></span>
                            </div>
                            <div style="margin-top: 6%;margin-right: 12%;">
                                <textarea class="form-control VoucherMOdal1" rows="3" name=""></textarea>

                            </div>
                        </div>
                        <!-- <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
										<table class="table-bordered">
											<thead>
											<tr style="line-height: 243%;">
												<th style="text-align: center;" colspan="2">گائے</th>
												<th style="text-align: center;" colspan="2">بکرا</th>
												<th style="text-align: center;" colspan="2">دنبہ</th>
												<th style="text-align: center;" colspan="2">اونٹ</th>
												<th style="text-align: center;" colspan="2">بھینس</th>
											</tr>
											<tr style="line-height: 243%;">
												<th style="text-align: center">تا زہ</th>
												<th style="text-align: center">باسی</th>
												<th style="text-align: center">تا زہ</th>
												<th style="text-align: center">باسی</th>
												<th style="text-align: center">تا زہ</th>
												<th style="text-align: center">باسی</th>
												<th style="text-align: center">تا زہ</th>
												<th style="text-align: center">باسی</th>
												<th style="text-align: center">تا زہ</th>
												<th style="text-align: center">باسی</th>
											</tr>
											</thead>
											<tbody>
											<?php foreach($Slips as $key => $slip){?>
											<tr>
												<td><?php echo $slip->Cow_Fresh?></td>
												<td><?php echo $slip->Cow_Old?></td>
												<td><?php echo $slip->Goat_Fresh?></td>
												<td><?php echo $slip->Goat_Old?></td>
												<td><?php echo $slip->Sheep_Fresh?></td>
												<td><?php echo $slip->Sheep_Old?></td>
												<td><?php echo $slip->Camel_Fresh?></td>
												<td><?php echo $slip->Camel_Old?></td>
												<td><?php echo $slip->Buffelo_Fresh?></td>
												<td><?php echo $slip->Buffelo_Old?></td>
											</tr>
											<?php }?>
											</tbody>

										</table>
									</div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-right: 19%;margin-top: 7%;">
        <span>گاری نمبر : <?php echo $Slips[0]->Car_Number ?></span>
    </div>
    <div style="margin-top: 2%;margin-right: 66%;">
        <label class="form-label"> دستخط </label>
        <input type="text" class="VoucherMOdal" name="" style="width: 70%;" readonly>
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