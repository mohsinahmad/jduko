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
            <?php if(isset($location)){?>
	            <?php if(isset($RewardList[0]->Location)){?>
		            <?php if($RewardList[0]->Location == 41){?>
		            <h2 style="margin-top: 0px;font-size: 20px">ادائیگی بابت انعامات حصص قربانی ۱۴۳۸ھ(کورنگی)</h2>
		            <?php }elseif($RewardList[0]->Location == 43){?>
		            <h2 style="margin-top: 0px;font-size: 20px">ادائیگی بابت انعامات حصص قربانی ۱۴۳۸ھ(گلشن اقبال)</h2>
		            <?php }else{?>
		            <h2 style="margin-top: 0px;font-size: 20px">ادائیگی بابت انعامات حصص قربانی ۱۴۳۸ھ(نانکواڑہ)</h2>
		            <?php }?>
		        <?php }?>
            <?php }else{?>
            <h2 style="margin-top: 0px;font-size: 20px">ادائیگی بابت انعامات حصص قربانی ۱۴۳۸ھ</h2>
            <?php }?>
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
                                    <th style="text-align: center">نمبر شمار</th>
                                    <th style="text-align: center">نام</th>
                                    <th style="text-align: center">منظور شدہ انعام ۳۷ھ</th>
                                    <th style="text-align: center">اضا فہ</th>
                                    <th style="text-align: center">انعام ۱۴۳۸ھ</th>
                                    <th style="text-align: center">دستخط</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $Last_Year_Reward=$total_increment=$total_Reward = 0;?>
                                <?php foreach($RewardList as $key => $value){?>
                                <tr>
                                    <td><?php echo ++$key?></td>
                                    <td><?php echo $value->Name ?></td>
                                    <td><?php echo number_format($value->Last_Year_Reward);
                                    $Last_Year_Reward += $value->Last_Year_Reward;
                                    ?></td>
                                    <?php if($value->Increament_Type == 1){
                                       $increament = ($value->Increament / 100) * $value->Last_Year_Reward;
                                    }else{
                                       $increament = $value->Increament;
                                    }
                                    ?>
                                	<td><?php echo number_format($increament);
                                	$total_increment += $increament;
                                	?></td>
                                    <?php if($value->Increament_Type == 1){
	                                    $check = ($value->Increament / 100) * $value->Last_Year_Reward;
	                                    $reward = ($check + $value->Last_Year_Reward) / 5;
	                                    $final_Reward = round($reward) * 5;
	                                }else{
	                                    $final_Reward = $value->Increament + $value->Last_Year_Reward;
	                                }?>
	                                <td><?php echo number_format($final_Reward);
	                                $total_Reward += $final_Reward;
	                                ?></td>
                                    <td></td>
                                </tr>
                                <?php }?>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 250%;">
                                    <th></th>
                                    <th><span style="float: right;"> میزان:</span></th>
                                    <th style="text-align: center"><?= number_format($Last_Year_Reward) ?></th>
                                    <th style="text-align: center"><?= number_format($total_increment) ?></th>
                                    <th style="text-align: center"><?= number_format($total_Reward) ?></th>
                                    <th style="text-align: center"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
			        <?php date_default_timezone_set('Asia/Karachi');?>
			        <span style="float: left;"><?= date('l d-m-Y h:i:s');?></span>
                </div>
            </div>
           
        </div>
    </div>
</div>
</body>
</html>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        //window.print();
    });
    function myFunction() {
        window.print();
    }
</script>