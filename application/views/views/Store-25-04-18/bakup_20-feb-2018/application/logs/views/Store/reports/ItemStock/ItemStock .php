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
                    <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width: 24%; max-width:330px;">
                    <h2 style="margin-top: 0px;text-decoration: underline;font-size: 22px">آئیٹم اسٹاک</h2>
                    <h4 style="font-size: 15px"><span><?= $Hdate?></span><span>  بمطابق  </span><span><?= $date?></span></h4>
                </div>
            </div>
            <div class="level">
                <div>
                    <div class="row two">
                        <div class="col-lg-12">
                            <div class="panel-body">
                                <?php foreach ($item_setup as $item_key => $items){?>
                                    <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;">
                                        <table class="table-bordered">
                                            <?php $sum_cal_quantity = 0;
                                            foreach ($items as $key => $item) { ;?>
<!--                                            --><?php //if ($key == 0 && $item_key == 0){?>
                                            <?php if ($key == 0 && $item_key == 0 ){?>
                                                <thead>
                                                    <tr style="line-height: 243%;">
                                                        <th style="text-align: center;width:10%;">آئٹم کوڈ</th>
                                                        <th style="text-align: center;width: 25%">آئٹم کا نام</th>
                                                        <th style="text-align: center;width: 12%">پیمائش کی اکائی</th>
                                                        <th style="text-align: center">ڈونیشن کی قسم</th>
                                                        <th style="text-align: center;width: 13%;">موجود مقدار</th>
                                                        <th style="text-align: center;width: 15%;">کل مقدار</th>
                                                    </tr>
                                                </thead>
                                            <?php } $cal_quantity = 0;?>
                                            <tbody>
                                                <tr>
                                                    <?php if ($key == 0){?>
                                                        <td style="text-align: center;width:10%;"><?= $item[0]->code?></td>
                                                        <td style="text-align: center;width:25%;"><?= $item[0]->name?></td>
                                                        <td style="text-align: center;width: 12%"><?= $item[0]->unit_of_measure?></td>
                                                    <?php }else{?>
                                                        <td style="text-align: center"></td>
                                                        <td style="text-align: center;width: 25%"></td>
                                                        <td style="text-align: center;width: 12%"></td>
                                                    <?php }?>
                                                    <td style="text-align: center;"><?= $item[0]->Donation_Type?></td> <?php $cal_quantity = ($item[0]->opening_quantity + $item_recieve[$item_key][$key][0]->Recieve_quantity + $item_return[$item_key][$key][0]->Return_Quantity) - $item_issue[$item_key][$key][0]->Issue_Quantity;?>
                                                    <td style="text-align: center;width: 13%;"><?= number_format($cal_quantity)?></td>
                                                    <?php if (!isset($item_setup[$item_key][$key+1])){ $sum_cal_quantity += $cal_quantity;?>
                                                    <td style="text-align: center;width: 15%;"><?= number_format($sum_cal_quantity)?></td>
                                                    <?php }else {?>
                                                        <td style="text-align: center"></td>
                                                    <?php }?>
                                                    <?php $sum_cal_quantity += $cal_quantity;?>
                                                </tr>
                                            </tbody>
                                            <?php } ?>
                                        </table>
                                    </div>
                                <?php }?>
                            </div>
                        </div>
                    </div>
<!--                    <div class="panel-body" style="direction: rtl;">-->
<!--                        <div class="table-responsive">-->
<!--                            <table class="table-bordered" id="dataTables-example">-->
<!--                                <thead>-->
<!--                                <tr style="line-height: 250%;">-->
<!--                                    <th></th>-->
<!--                                    <th style="text-align: center">بیلینس</th>-->
<!--                                </tr>-->
<!--                                </thead>-->
<!--                                <tbody>-->
<!--                                <tr class="odd gradeX" style="line-height: 250%;">-->
<!--                                    <th class="center" style="text-align: center">خالص آمدنی (نقصان):</th>-->
<!--                                    <th style="text-align: center">()</th>-->
<!--                                </tr>-->
<!--                                </tbody>-->
<!--                            </table>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
        </div>
        <footer class ="footer" id="pagebreak">
            <div style="direction: ltr;">
                <hr> <?php date_default_timezone_set('Asia/Karachi');?>
                <span style="float: left;"><?= date('l d-m-Y h:i:s');?></span>
                <hr>
            </div>
        </footer>
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