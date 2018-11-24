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
        .hiddenborder{
                border-left-style: hidden;
                border-right-style: hidden;
                border-top-style: hidden;
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
            .logo{
                width: 150px;
            }
            .hiddenborder{
                border-left-style: hidden;
                border-right-style: hidden;
                border-top-style: hidden;
            }
        }
    </style>
</head>
<body>
<div class="content">
    <div>
        <div id="hide">
            <button onclick="myFunction()">Print</button>
        </div>
        
    </div>
   
    
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body">
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -4%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr class="hiddenborder">
                                    <th colspan="5">
                                        <div class="row" style="text-align: center;margin-right: -60%;margin-top: 1%;">
                                            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:300px;">
                                            <h2 style="margin-top: 0px;text-decoration: underline;font-size: 20px">آمدنی کا گوشوارہ</h2>
                                        </div>
                                         <div class="row" style="margin-top: -15%;margin-right: 55.4%;">
                                            <div>
                                                <label class="control-label">واؤچر نمبر:
                                                    <input type="text" value="" class="VoucherMOdal" style="width: 61.9%;margin-right: 2%;" readonly>
                                                </label>
                                            </div>
                                            <div>
                                                <label class="control-label">ہجری تاریخ:
                                                    <input type="text" class="VoucherMOdal" value="" style="width: 60%;margin-right: 0%;" readonly>
                                                </label>
                                            </div>
                                            <div >
                                                <label class="control-label">عیسوی تاریخ:
                                                    <input type="text" value="" class="VoucherMOdal" readonly style="width: 58%;">
                                                </label>
                                            </div>
                                            <div class="Jv" >
                                                <div>
                                                    <label class="control-label">مستقل واؤچر:
                                                        <input type="text" value="" class="VoucherMOdal" readonly style="font-weight:bold;width: 56%;">
                                                    </label>
                                                </div>
                                                <div>
                                                    <label class="control-label">خزانچی واؤچر:
                                                        <input type="text" value="" class="VoucherMOdal" readonly style="font-weight:bold;width: 56%;">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="margin-top: 4%;">
                                            <div style="margin-right: 2%">
                                                <label class="control-label">شعبہ<span style="margin-right: 11px;">:</span>
                                                    <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 300px;font-weight: bold;" value="">
                                                </label>
                                            </div>
                                            <div style="margin-right: 2%">
                                                <label class="control-label">بنام<span style="margin-right: 11px;">:</span>
                                                    <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 300px;font-weight: bold;" value="">
                                                </label>
                                            </div>
                                            <div style="margin-top: -7%;margin-right: 55%;">
                                                <label class="control-label">رقم ہندسوں میں :
                                                <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 230px;font-size: 1.2em;font-weight: 800;" value="">
                                                </label>
                                            </div>
                                            <div style="margin-right: 55%;">
                                                <label class="control-label">رقم عبارت میں :
                                                <input type="text" class="VoucherMOdal" readonly style="font-family: 'Noto Nastaliq Urdu', serif; width: 232px;font-size: 1.2em;font-weight: 800;" value="">
                                                </label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div style="margin-right: 2%">
                                                <label class="control-label">بابت<span style="margin-right: 5px;">:</span>
                                                    <textarea rows="1" cols="80" type="text" class="VoucherMOdal" readonly style=" font-family: 'Noto Nastaliq Urdu', serif;width: 92%;overflow:hidden;font-weight: bold;"></textarea>
                                                </label>
                                             </div>
                                         </div>
                                    </th>
                                </tr>
                                <tr style="line-height: 243%;border-right-style: inset;">
                                    <th style="text-align: center">کوڈ</th>
                                    <th style="text-align: center">اکاونٹ نام</th>
                                    <th style="text-align: center">بیلنس</th>
                                    <th style="text-align: center">بیلنس</th>
                                    <th style="text-align: center">بیلنس</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr style="border-right-style: inset;">
                                    <td>asdasda</td>
                                    <td>asdasda</td>
                                    <td>asdasda</td>
                                    <td>asdasda</td>
                                    <td>asdasda</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr style="line-height: 250%;border-right-style: inset;">
                                    <th></th>
                                    <th><span style="float: right;"> میزان:</span></th>
                                    <th style="text-align: center"></th>
                                    <th style="text-align: center"></th>
                                    <th style="text-align: center"></th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div style="float: right;margin-right: 4%;">
            <label class="control-label">اکاؤنٹنٹ:
            <input type="text" class="VoucherMOdal" style="width: 150px;" readonly></label>
        </div>
        <div style="">
            <label class="control-label">ڈپٹی چیف اکاؤنٹنٹ:
            <input type="text" class="VoucherMOdal" style="width: 138px;" readonly></label>
        </div>
        <div style="margin-right: 60%;margin-top: -3.6%;">
            <label class="control-label">دستخط براےَ رئيس الجامعہ:
            <input type="text" class="VoucherMOdal" style="width:50%" readonly></label>
        </div>
    </div>
    <div class="row">
        <div style="margin-top: 6%;margin-right: 4%">
            <label class="control-label">دستخط از مجاز برچیک نمبر:
            <input type="text" class="VoucherMOdal" value="" style="text-align: center;width: 35%" readonly></label>
        </div>
        <div style="margin-top: -3.7%;margin-right: 36%;">
            <label class="control-label">تاریخ:
                <input type="text" class="VoucherMOdal" value="" style="width: 40%;" readonly></label>
        </div>
        <div style="margin-top: -3.7%;margin-right: 56%;">
            <label class="control-label">(2)
                <input type="text" class="VoucherMOdal" style="width: 47%;" readonly>
            </label>
        </div>
        <div style="margin-top: -3.7%;margin-right: 76%;">
            <label class="control-label">(1)
                <input type="text" class="VoucherMOdal" style="width: 77%;" readonly></label>
        </div>
    </div>
    <div class="row">
        <div style="margin-top: 8%;margin-right: 4%;">
            <label class="control-label">دستخط وصول کنندہ:
            <input type="text" class="VoucherMOdal" readonly style="width: 65%;"></label>
        </div>
         <div style="margin-top: -3.6%;margin-right: 46%;">
            <label class="control-label">کیشیئر:
            <input type="text" style="width: 370px;" class="VoucherMOdal" readonly></label>
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