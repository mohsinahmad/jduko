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
    <title style="font-family: 'Noto Nastaliq Urdu', serif;">دارالعلوم کراچی  </title>
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
            margin-right: 5%;
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
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width: 20%; max-width:330px;">
            <h2 style="margin-top: 0px;font-size: 20px">  آئٹم  لیجر   </h2>



            <h4 style="font-size: 15px"><span> ١۴۳۹ تا  ١۴۴۰ </span></h4><!-- 
            <h4 style="font-size: 15px"><span><?= $from[0]->Qm_date?></span></h4> -->
        </div>


    </div>



    <?php foreach ($ItemLedger as $item_key => $items){?>
        <div class="level">
            <div>
                <div class="row two">
                    <div class="col-lg-12">
                        <?php $sum_cal_quantity = 0;
                        ?>
                        <div class="panel-body">
                            <h4 style="float: left">ابتدائ بیلنس :<span><?= $Opening_Quantity_Cal[$item_key][0][0]?></span></h4>
                            <!--                                <div>-->
                            <!--                                    <div style="margin-right: -5%">-->
                            <!--                                        <h4>کوڈ : <span>--><!--</span></h4>-->
                            <!--                                    </div>-->
                            <!--                                    <div style="margin-right: -5%">-->
                            <!--                                        <h4>نام : <span>--><!--</span></h4>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                                <table border="1">

                                    <!--                                            --><?php //if ($key == 0 && $item_key == 0){?>
                                    <!--                                            --><?php //if ($key == 0 && $item_key == 0 ){?>
                                    <thead>
                                    <tr>
                                        <th colspan="3">
                                            <?php if (isset($item_setup[$item_key][0][0]->code) && isset($item_setup[$item_key][0][0]->name)){?>
                                                <h5 style="margin-right: 3%">کوڈ : <span><?= $item_setup[$item_key][0][0]->code?></span></h5>
                                                <h5 style="margin-right: 3%">آئٹم : <span><?= $item_setup[$item_key][0][0]->name?></span></h5>
                                                <h5 style="float: left;margin-top: -6%;margin-left: 2%;"> پیمائش کی اکائی : <span><?= $item_setup[$item_key][0][0]->unit?></span></h5>
                                            <?php }?>
                                        </th>
                                        <th></th>
                                        <th style="text-align: center" colspan="3">وصولی</th>
                                        <th style="text-align: center">اجراء</th>
                                        <th style="text-align: center"></th>

                                    </tr>
                                    <tr style="line-height: 243%;">
                                        <th style="text-align: center;width:7%;">فارم نمبر</th>
                                        <th style="text-align: center;width: 10%">تاریخ</th>
                                        <th style="text-align: center;width: 18%">تفصیل</th>
                                        <th style="text-align: center;width: 10%">  تعاون کی قسم  </th>
                                        <th style="text-align: center;width: 8%;">مقدار</th>
                                        <th style="text-align: center;width: 8%;">قیمت</th>
                                        <th style="text-align: center;width: 10%;">مالیت</th>
                                        <th style="text-align: center;width: 8%;">مقدار</th>
                                        <th style="text-align: center;width: 8%;">بلینس</th>
                                    </tr>
                                    </thead>
                                    <?php 

                                    $cal_quantity = $per_head = $recive = $issue = $return = $balance = $balance_New = 0; 
                                    $balance = $Opening_Quantity_Cal[$item_key][0][0];?>
                                    <tbody>
                                    <?php foreach ($items[0] as $key => $item) {?>
                                        <tr>
                                            <?php if(isset($item->recive_quantity)){?>
                                                <td><?php echo $item->Number?></td>
                                            <?php }elseif(isset($item->Issue_Quantity)){?>
                                                <td><?php echo $item->Number?></td>
                                            <?php } elseif(isset($item->return_quantity)){?>
                                                <td><?php echo $item->Number?></td>
                                            <?php }else{?>
                                                <td></td>
                                            <?php }?>
                                            <?php if(isset($item->dateG)){?>
                                                <td>
                                                    <span><?php echo $item->dateH?></span><br>
                                                    <span><?php echo $item->dateG?></span>
                                                </td>
                                            <?php } else{?>
                                                <td></td>
                                            <?php }?>
                                            <?php if(isset($item->DepartmentName)){?>
                                                <td><?php echo $item->LevelName.'-'.$item->DepartmentName.'-'.$item->UserName;?></td>
                                            <?php } elseif(!isset($item->DepartmentName)){?>
                                                <td><?php echo $item->LevelName.'-'.$item->UserName;?></td>
                                            <?php }elseif(isset($item->NameU)){?>
                                                <td><?php echo "نام سپلائر".'-'.$item->Buyer_name?> <br> <?php echo "نام خرید کنندہ".'-'.$item->NameU?></td>
                                            <?php }else{?>
                                                <td></td>
                                            <?php }?>
                                            <td>  <?php echo $item->Donation_Type; ?> </td>
                                            <?php if(isset($item->recive_quantity)){?>
                                                <td><?php echo $item->recive_quantity;
                                                    $recive = $item->recive_quantity;
                                                    ?></td>
                                                <?php $per_head = $item->Item_price / $item->recive_quantity?>
                                                <td><?php echo $per_head;?></td>
                                                <td><?php echo $item->Item_price;?></td>
                                            <?php }elseif($item->return_quantity){?>
                                                <td><?php echo $item->return_quantity;
                                                    $return = $item->return_quantity;
                                                    ?></td>
                                                <td></td>
                                                <td></td>
                                            <?php } else{?>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            <?php }?>
                                            <?php if(isset($item->Issue_Quantity)){?>
                                                <td><?php echo $item->Issue_Quantity;
                                                    $issue = $item->Issue_Quantity;
                                                    ?></td>
                                            <?php } else{?>
                                                <td></td>
                                            <?php }?>
                                            <?php $balance = ($balance +
                                            $return + $item->recive_quantity ) - $item->Issue_Quantity; ?>
                                            <?php if ($balance < 0){
                                                $balance_New = $balance * -1; ?>
                                                <td>(<?php echo $balance_New;?>)</td>
                                            <?php }else{?>
                                                <td><?php echo $balance;?></td>
                                            <?php }?>
                                        </tr>
                                        <!--                                            --><?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php }?>
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