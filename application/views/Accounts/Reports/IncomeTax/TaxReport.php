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
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">ٹیکس کٹوتی</title>
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
            direction: ltr;
            width: 97%;
            margin-right: 4%;
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
            margin-right: 10px;
            border: 0;
            outline: 0;
            background: transparent;
            border-bottom: 1px solid black;
        }
        .note{
            margin-right: 10px;
            border: 0;
            outline: 0;
            background: transparent;
            border-bottom: 0px;
        }
        /*.table>tbody>tr>td {
          border: none!important;
          }*/
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
            .two{
                direction: ltr;
                width: 97%;
                margin-right: 4%;
                padding-top: 2%;
                line-height: 17px;
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
        <div style="text-align: center;margin-top: -2%;">
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:20%; max-width:330px;">
            <h4>ٹیکس کٹوتی کی تفصیلات </h4>
            <h5>2017 کے سال کے لئے</h5>
            <h5>بینک ادائیگی / کیش ادائیگی </h5>
        </div>
        <div class="col-md-6 heading" style="float: right;margin-top: -7%">
            <?php $sey=' سے '; $tk = ' تک ';?>
            <h4><p><?= $to[0]->Sh_date?></p><p style="margin-right: 123px;margin-top: -29px;"><?= $to[0]->Qm_date;?></p></h4>
            <span class ="ta" style="text-decoration: underline; margin-right: 5%;">تا</span>
            <span class="bamutabiq" style="text-decoration: underline; margin-right: 12%;">بمطابق</span>
            <span class="ta1" style="text-decoration: underline; margin-right: 10%;">تا</span>
            <h4><p><?= $from[0]->Sh_date?></p><p style="margin-right: 123px;margin-top: -29px;"><?= $from[0]->Qm_date;?></p></h4>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body" style="padding-right: 0px;padding-top: 0px;margin-bottom: -5%;">
                        <div class="table-responsive" align="middle" style="width: 100%;text-align: center; margin-right: -0%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">S.No</th>
                                        <th style="text-align: center; ">Date</th>
                                        <th style="text-align: center; ">Voucher Numebr</th>
                                        <th style="text-align: center; ">Party Name</th>
                                        <th style="text-align: center; ">NTN Number</th>
                                        <th style="text-align: center; ">Address</th>
                                        <th style="text-align: center; ">Payments</th>
                                        <th style="text-align: center; ">Paid Amount</th>
    <!--                                    <th style="text-align: center; ">Section</th>-->
                                    </tr>
                                </thead>
                                <?php $count = 1; $debit_sum = 0;?>
                                <tbody style="text-align: center;">
                                <?php if (isset($TaxData)){
                                    foreach($TaxData as $key => $data){
                                        foreach ($data as $items){ ?>
                                            <tr>
                                                <td><?= $count?></td>
                                                <?php $dateG = strtotime($items[0]->VoucherDateG);?>
                                                <td style="text-align: center;width: 13%"><?=date('d-m-Y' , $dateG)?></td>
                                                <?php if ($items[0]->Permanent_VoucherNumber != ''){?>
                                                    <td>P - <?= $items[0]->Permanent_VoucherNumber?></td>
                                                <?php }else{?>
                                                    <td>T - <?= $items[0]->VoucherNo?></td>
                                                <?php }if($Suppliers[$key]->NameU == ""){?>
                                                    <td><?= $Suppliers[$key]->NameE?></td>
                                                <?php }else if($Suppliers[$key]->NameU != "" && $Suppliers[$key]->NameE != "") {?>
                                                    <td><?= $Suppliers[$key]->NameE?></td>
                                                <?php } else {?>
                                                    <td><?= $Suppliers[$key]->NameE?></td>
                                                <?php } if ($Suppliers[$key]->NTN_number != ''){?>
                                                    <td><?= $Suppliers[$key]->NTN_number ?></td>
                                                <?php }else{ ?>
                                                    <td><?= $Suppliers[$key]->CNIC ?></td>
                                                <?php } if($Suppliers[$key]->AddressU == ""){ ?>
                                                    <td><?= $Suppliers[$key]->AddressE?></td>
                                                <?php }else if($Suppliers[$key]->AddressU != "" && $Suppliers[$key]->AddressE != "") {?>
                                                    <td><?= $Suppliers[$key]->AddressE?></td>
                                                <?php } else {?>
                                                    <td><?= $Suppliers[$key]->AddressE?></td>
                                                <?php }foreach ($items as $item){
                                                    if ($item->LinkID != $chartId[$key] && $item->Type != 2 && $item->TaxDebit){
                                                        $debit_sum += $item->Debit; }} ?>
                                                <td><?= number_format($debit_sum)?></td>
                                                <?php $countie = 0; $debit_sum = 0;
                                                foreach ($items as $item){
                                                    if ($item->LinkID == $chartId[$key]){
                                                        // temp conditions
                                                        if ($countie == 0){?>
                                                        <td><?= number_format($item->Credit)?></td>
                                                    <?php } $countie++; }
                                                } $count++;?>
                                            </tr>
                                        <?php }
                                    }
                                }?>
                                </tbody>
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
    // $( document ).ready(function() {
    //     window.print();
    // });
    function myFunction() {
        window.print();
    }
</script>