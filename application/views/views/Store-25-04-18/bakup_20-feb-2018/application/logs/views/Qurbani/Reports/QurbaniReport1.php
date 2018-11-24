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
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:42%; max-width:250px;">
            <h2 style="margin-top: 0px;text-decoration: underline;font-size: x-large; ">وصولی آمدنی براےُ حصص قربانی 1438</h2>
            <!-- <h4><span>1438-05-21</span><span>  بمطابق  </span><span>2017-25-04</span></h4> -->
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-6">
                                <h4 style="float:left;direction: ltr;margin-right: -5%;"><span> لیول -</span>کورنگی </h4>
                            </div>
                        </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center">دن</th>
                                    <th style="text-align: center">حصص</th>
                                    <th style="text-align: center">گاےُ</th>
                                    <th style="text-align: center">رقم</th>
                                    <th style="text-align: center">آخری گاےُ نمبر</th>
                                    <th style="text-align: center">خود خرید کردہ</th>
                                    <th style="text-align: center">رقم</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>۱۰ ذی الحجہ</td>
                                    <td><?= $Ijtimai[0][0]->Quantity?></td>
                                    <?php $cow = 0; $final = 0;$final1 = 0;$final2 = 0;
                                    $no_cow = $Ijtimai[0][0]->Quantity / 7;
                                    if (is_integer($no_cow)) {
                                        $final = $no_cow;
                                    }else{
                                        $final = floor($no_cow) + 1;
                                    } ?>
                                    <td><?= $final?></td>
                                    <td><?= number_format($Ijtimai[0][0]->Total)?></td>
                                    <td><?= $Last_Cow[0]->Code?></td>
                                    <?php $SelfCow0 = $Inferadi[0][0]->Quantity/7;?>
                                    <td><?= $SelfCow0?></td>
                                    <td><?= number_format($Inferadi[0][0]->Total)?></td>
                                </tr>
                                <tr>
                                    <td>۱۱ ذی الحجہ</td>
                                    <td><?= $Ijtimai[1][0]->Quantity?></td>
                                    <?php
                                    $no_cow = $Ijtimai[1][0]->Quantity / 7;
                                    if (is_integer($no_cow)) {
                                        $final1 = $no_cow;
                                    }else{ $final1 = floor($no_cow) + 1; }?>
                                    <td><?= $final1?></td>
                                    <td><?= number_format($Ijtimai[1][0]->Total)?></td>
                                    <td><?= $Last_Cow[1]->Code?></td>
                                    <?php $SelfCow1 = $Inferadi[1][0]->Quantity/7;?>
                                    <td><?= $SelfCow1?></td>
                                    <td><?= number_format($Inferadi[1][0]->Total)?></td>
                                </tr>
                                <tr>
                                    <td>۱۲ ذی الحجہ</td>
                                    <td><?= $Ijtimai[2][0]->Quantity?></td>
                                    <?php
                                    $no_cow = $Ijtimai[2][0]->Quantity / 7;
                                    if (is_integer($no_cow)) {
                                        $final2 = $no_cow;
                                    }else{ $final2 = floor($no_cow) + 1; } ?>
                                    <td><?= $final2?></td>
                                    <td><?= number_format($Ijtimai[2][0]->Total)?></td>
                                    <td><?= $Last_Cow[2]->Code?></td>
                                    <?php $SelfCow2 = $Inferadi[2][0]->Quantity/7;?>
                                    <td><?= $SelfCow2?></td>
                                    <td><?= number_format($Inferadi[2][0]->Total)?></td>
                                </tr>
                                </tbody>
                                <?php $cow = 0; $hissa = 0; $hissa_amount =0; $self = 0; $self_amount = 0;?>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th><span style="float: right;"> میزان:</span></th>
                                    <?php $hissa = $Ijtimai[0][0]->Quantity + $Ijtimai[1][0]->Quantity + $Ijtimai[2][0]->Quantity;?>
                                    <th style="text-align: center"><?= $hissa ?></th>
                                    <?php $cow = $final+$final1+$final2;?>
                                    <th style="text-align: center"><?= $cow?></th>
                                    <?php $hissa_amount = $Ijtimai[0][0]->Total + $Ijtimai[1][0]->Total + $Ijtimai[2][0]->Total; ?>
                                    <th style="text-align: center"><?= number_format($hissa_amount)?></th>
                                    <th style="text-align: center"></th>
                                    <?php $self = $SelfCow0 + $SelfCow1 + $SelfCow2;?>
                                    <th style="text-align: center"><?= $self?></th>
                                    <?php $self_amount = $Inferadi[0][0]->Total + $Inferadi[1][0]->Total + $Inferadi[2][0]->Total; ?>
                                    <th style="text-align: center"><?= number_format($self_amount)?></th>
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
<script type="text/javascript">
    $( document ).ready(function() {
        window.print();
    });
    function myFunction() {
        window.print();
    }
</script>