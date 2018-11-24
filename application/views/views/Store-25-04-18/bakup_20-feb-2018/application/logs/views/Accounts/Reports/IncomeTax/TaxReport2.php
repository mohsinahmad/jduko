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
            max-width: 850px;
            margin:auto;
            padding:20px;
            direction: ltr;
        }
        .two{
            direction: ltr;
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
            .ntn{
                margin-left: 400px;
            }
            .ntnno{
                margin-left: 152px;
            }
            .marginup{
                margin-top: -30px;
            }
        }
    </style>
</head>
<body>
<div class="content">
    <div id="hide">
        <button onclick="myFunction()">Print</button>
    </div>
    <div class="row" id="content">
        <div class="col-md-6">
            <label class="ntn">NTN <span class="ntnno" style="text-decoration: underline;margin-left: 152px">1000000-0</span></label>
            <br>
            <?php if ($from[5].$from[6] == 01) { $monthname = "January";
            }elseif ($from[5].$from[6] == 02) { $monthname = "February";
            }elseif ($from[5].$from[6] == 03) { $monthname = "March";
            }elseif ($from[5].$from[6] == 04) { $monthname = "April";
            }elseif ($from[5].$from[6] == 05) { $monthname = "May";
            }elseif ($from[5].$from[6] == 06) { $monthname = "June";
            }elseif ($from[5].$from[6] == 07) { $monthname = "July";
            }elseif ($from[5].$from[6] == '08') { $monthname = "August";
            }elseif ($from[5].$from[6] == '09') { $monthname = "September";
            }elseif ($from[5].$from[6] == 10) { $monthname = "October";
            }elseif ($from[5].$from[6] == 11) { $monthname = "November";
            }elseif ($from[5].$from[6] == 12) { $monthname = "December"; }?>
            <label class="ntn">Statment for the month ending
                <span style="text-decoration: underline;word-spacing: 2px; "> <?= $from[8].$from[9]?> <?= $monthname?> <?= $from[0].$from[1].$from[2].$from[3]?></span>
            </label>
        </div>
        <div class="col-md-6">
            <label class="marginup">Name :<span style="text-decoration: underline;word-spacing: 2px;margin-left: 14px;">Jamia Darul Uloom Karachi</span></label>
            <br>
            <label>Address :<span style="text-decoration: underline;word-spacing: 2px;">Korangi Industrial Area, Karachi</span></label>
        </div>
    </div>

    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body" style="margin-top: -30px;">
                        <h4 style="word-spacing: 2px; text-align: center;text-decoration-line: underline;"> PART I - Detail Of Payment etc, where tax has been collected or deducted at source</h4>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-left: -3%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="word-spacing: 2px;font-size: 0.9em;line-height: 150%;">
                                    <th style="text-align: center">S.No</th>
                                    <th style="text-align: center;">Name,Address and NTN of the person from whom tax collected or deducted. <br><br> Where NTN is not avalible indicate CNIC or Electricity Consumer or Telephone or Vehicle Registration No, etc</th>
                                    <th style="text-align: center;width: 9%;">Nature of Payment etc</th>
                                    <th style="text-align: center;">Section under which tax Collected or deducted</th>
                                    <th style="text-align: center;">Date of Payment <br> (dd/mm/yyyy)</th>
                                    <th style="text-align: center;width: 13%;">Value / Amount on which tax collected or deducted<br>(Rupees)</th>
                                    <th style="text-align: center;">Rate of tax collected or deducted <br>(Percentage)</th>
                                    <th style="text-align: center;">Ammount of tax collected or deducted <br> (Rupees)</th>
                                    <th style="text-align: center;">Ammount of tax deposited<br>(Rupees)</th>
<!--                                    <th style="text-align: center;">Date of Deposit <br> (dd/mm/yyyy)</th>-->
<!--                                    <th style="text-align: center;">CPRM Number</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <?php $CreditAmount = 0; $count = 1; $debit_sum = 0; $percentage = 0; $totaldebit = 0; $totalcredit = 0;
                                if (isset($TaxData)){
                                    foreach($TaxData as $key => $data){
                                        foreach ($data as $items){ ?>
                                            <tr>
                                                <td><?= $count?></td>
                                                <td style="text-align: left;">
                                                    <span><?= $Suppliers[$key]->NameE?></span>
                                                    <br> <span style="font-size: 0.8em;"><?= $Suppliers[$key]->AddressE?></span>
                                                    <br>
                                                    <?php if($Suppliers[$key]->NTN_number != ''){?>
                                                        <span>NTN</span>
                                                        <br>
                                                        <span style="font-size: 0.8em;"><?=$Suppliers[$key]->NTN_number?></span>
                                                    <?php } else{?>
                                                        <span>CNIC</span>
                                                        <br>
                                                        <span style="font-size: 0.8em;"><?=$Suppliers[$key]->CNIC?></span>
                                                    <?php }?>
                                                </td>
                                                <td><?= $Suppliers[$key]->Nature_Of_Payment?></td>
                                                <td></td>
                                                <?php $dateG = strtotime($items[0]->VoucherDateG);?>
                                                <td><?=date('d-m-Y' , $dateG)?></td>
                                                <?php foreach ($items as $item){
                                                    if ($item->LinkID != $chartId[$key] && $item->Type != 2 && $item->TaxDebit == 1){
                                                        $debit_sum += $item->Debit; }} $totaldebit += $debit_sum; ?>
                                                <td><?= number_format($debit_sum)?></td>
                                                <?php foreach ($items as $item){
                                                    if ($item->LinkID == $chartId[$key]){
                                                        $CreditAmount = $item->Credit;
                                                        $totalcredit += $item->Credit;
                                                    }
                                                } if($debit_sum < 1 ){$percentage = 0;  }else{ $percentage = ($CreditAmount / $debit_sum)* 100; } ?>
                                                <td><?=number_format($percentage,2)?> <span>%</span></td>
                                                <td><?= number_format($CreditAmount)?></td>
                                                <td><?= number_format($CreditAmount)?></td>
<!--                                                <td></td>-->
<!--                                                <td></td>-->
                                            </tr>
                                            <?php $count++; $CreditAmount = 0; $debit_sum = 0;}
                                    }
                                }?>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th></th>
                                    <th><span style="float: right;"> Total:</span></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="text-align: center"><?=number_format($totaldebit) ?></th>
                                    <th></th>
                                    <th style="text-align: center"><?=number_format($totalcredit) ?></th>
                                    <th style="text-align: center"><?=number_format($totalcredit) ?></th>
                                    <th></th>
                                    <th></th>
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
<script src="<?= base_url()."assets/"; ?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
//    $( document ).ready(function() {
//        window.print();
//    });
    function myFunction() {
        window.print();
    }
</script>