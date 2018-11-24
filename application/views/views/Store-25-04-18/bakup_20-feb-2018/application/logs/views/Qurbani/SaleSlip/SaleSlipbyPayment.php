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
            max-width: 1000px;
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
            <h2 style="margin-top: 10px;font-size: 20px">اجراء چرم بلحاظ قیمت</h2>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="row">
                            <div style="margin-right: -2.3%;margin-top: -4.5%;">
                                <h4 style=""><span>سلپ نمبر : </span><?php echo $Slips[0]->Slip_Number ?></h4>
                            </div>
                            <div style="margin-right: 28%;margin-top: -3.4%;">
                                <h4 style=""><span>وینڈر نام : </span><?php echo $Slips[0]->Name ?></h4>
                            </div>
                            <div style="margin-right: 59%;margin-top: -2.7%;">
                                <h4 style="font-size: 16px"><span><?php echo $Slips[0]->Slip_DateH ?></span><span>  بمطابق  </span><span><?php echo $Slips[0]->Slip_DateG ?></span></h4>
                            </div>
                        </div>
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center;" colspan="4">گائے</th>
                                    <th style="text-align: center;" colspan="4">بکرا</th>
                                    <th style="text-align: center;" colspan="4">دنبہ</th>
                                    <th style="text-align: center;" colspan="4">اونٹ</th>
                                    <th style="text-align: center;" colspan="4">بھینس</th>
                                </tr>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center">تا زہ</th>
                                    <th style="text-align: center">تا زہ قیمت</th>
                                    <th style="text-align: center">باسی</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                    <th style="text-align: center">تا زہ</th>
                                    <th style="text-align: center">تا زہ قیمت</th>
                                    <th style="text-align: center">باسی</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                    <th style="text-align: center">تا زہ</th>
                                    <th style="text-align: center">تا زہ قیمت</th>
                                    <th style="text-align: center">باسی</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                    <th style="text-align: center">تا زہ</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                    <th style="text-align: center">باسی</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                    <th style="text-align: center">تا زہ</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                    <th style="text-align: center">باسی</th>
                                    <th style="text-align: center">باسی قیمت</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($Slips as $key => $slip){?>
                                    <tr>
                                        <td><?php echo $slip->Cow_Fresh?></td>
                                        <td><?php echo $slip->Fresh_Rate_Cow?></td>
                                        <td><?php echo $slip->Cow_Old?></td>
                                        <td><?php echo $slip->Old_Rate_Cow?></td>
                                        <td><?php echo $slip->Goat_Fresh?></td>
                                        <td><?php echo $slip->Fresh_Rate_Goat?></td>
                                        <td><?php echo $slip->Goat_Old?></td>
                                        <td><?php echo $slip->Old_Rate_Goat?></td>
                                        <td><?php echo $slip->Sheep_Fresh?></td>
                                        <td><?php echo $slip->Fresh_Rate_Sheep?></td>
                                        <td><?php echo $slip->Sheep_Old?></td>
                                        <td><?php echo $slip->Old_Rate_Sheep?></td>
                                        <td><?php echo $slip->Camel_Fresh?></td>
                                        <td><?php echo $slip->Fresh_Rate_Camel?></td>
                                        <td><?php echo $slip->Camel_Old?></td>
                                        <td><?php echo $slip->Old_Rate_Camel?></td>
                                        <td><?php echo $slip->Buffelo_Fresh?></td>
                                        <td><?php echo $slip->Fresh_Rate_Buffelo?></td>
                                        <td><?php echo $slip->Buffelo_Old?></td>
                                        <td><?php echo $slip->Old_Rate_Buffelo?></td>
                                    </tr>
                                <?php }?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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