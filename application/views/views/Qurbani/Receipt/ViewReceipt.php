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
            <img src="<?= base_url()?>assets/images/logo.jpg" align="middle" style="width:28%; max-width:330px;">
            <h2 style="margin-top: 0px;font-size: xx-large;">مرکز حصص قربانی ۱۴۳۸ھ</h2>
            <?php if ($Per_Head[0]->Loc == 41){?>
                <h3 style="margin-top: 3%;">کورنگی</h3>
            <?php } elseif ($Per_Head[0]->Loc == 44){?>
                <h3 style="margin-top: 3%;">نانکواڑہ</h3>
            <?php } elseif ($Per_Head[0]->Loc == 43){?>
                <h3 style="margin-top: 3%;">گلشن اقبال</h3>
            <?php }?>
        </div>
        <div style="margin-right: 4%;">
            <div style="margin-top: -9%;">
                <label class="form-label"> سلپ نمبر :</label>
                <input type="text" class="VoucherMOdal" name="" style="width: 17%;text-align: center;" value="<?= $Receipt[0]->Slip_Number?>" readonly>
            </div>
            <div style="margin-top: -0%;">
                <label class="form-label"> یوم :</label>
                <?php if($Receipt[0]->Day == 1){?>
                    <input type="text" class="VoucherMOdal" name="" style=" width: 16.9%;margin-right: 4%;line-height: 0%;text-align: center;" value="۱۰ ذی الحج " readonly>
                <?php }elseif($Receipt[0]->Day == 2){?>
                    <input type="text" class="VoucherMOdal" name="" style=" width: 16.9%;margin-right: 4%;line-height: 0%;text-align: center;" value="۱۱ ذی الحج" readonly>
                <?php }elseif($Receipt[0]->Day == 3){?>
                    <input type="text" class="VoucherMOdal" name="" style=" width: 16.9%;margin-right: 4%;line-height: 0%;text-align: center;" value="۱۲ ذی الحج" readonly>
                <?php }?>
            </div>
        </div>
        <div style="margin-right: 75%;margin-top: -15%;">
            <label class="form-label"> فون نمبر :</label>
        </div>
        <div style="float: left;margin-left: 1%;margin-top: -5%;">
            <?php if ($Per_Head[0]->Loc == 41){?>
                <div style="margin-top: -9%;">
                    <label class="form-label"> کورنگی :</label>
                    <span style="margin-right: 12%;">35049774-6</span>
                </div>
            <?php }elseif ($Per_Head[0]->Loc == 44){ ?>
                <div style="margin-top: -0%;">
                    <label class="form-label"> نانکواڑہ :</label>
                    <span style="">32714189</span>
                    <br>
                    <span style="margin-right: 40%">32725859</span>
                </div>
            <?php }elseif ($Per_Head[0]->Loc == 43){?>
                <div style="margin-top: 5%;">
                    <label class="form-label"> گلشن اقبال :</label>
                    <span>34982579</span>
                    <br>
                    <span style="margin-right: 52%;">34825847</span>
                </div>
            <?php }?>
        </div>
        <?php if ($Per_Head[0]->Loc == 41){?>
            <div style="float: left;margin-left: -12%;margin-top: 8%;">
                <div style="">
                    <label class="form-label"> گاےُ نمبر :</label>
                    <input type="text" class="VoucherMOdal" name="" style="width: 40%;text-align: center;" value="<?= $Receipt[0]->Code?>" readonly>
                </div>
                <div style="">
                    <label class="form-label"> وقت :</label>
                    <input type="text" class="VoucherMOdal" name="" style="width: 39.9%;margin-right: 6%;text-align: center;" value="<?= $Receipt[0]->Time?>" readonly>
                </div>
            </div>
        <?php } elseif ($Per_Head[0]->Loc == 43){?>
            <div style="float: left;margin-left: -29%;margin-top: 8%;">
                <div style="">
                    <label class="form-label"> گاےُ نمبر :</label>
                    <input type="text" class="VoucherMOdal" name="" style="width: 40%;text-align: center;" value="<?= $Receipt[0]->Code?>" readonly>
                </div>
                <div style="">
                    <label class="form-label"> وقت :</label>
                    <input type="text" class="VoucherMOdal" name="" style="width: 39.9%;margin-right: 6%;text-align: center;" value="<?= $Receipt[0]->Time?>" readonly>
                </div>
            </div>
        <?php } elseif ($Per_Head[0]->Loc == 44){?>
            <div style="float: left;margin-left: -26%;margin-top: 8%;">
                <div style="">
                    <label class="form-label"> گاےُ نمبر :</label>
                    <input type="text" class="VoucherMOdal" name="" style="width: 40%;text-align: center;" value="<?= $Receipt[0]->Code?>" readonly>
                </div>
                <div style="">
                    <label class="form-label"> وقت :</label>
                    <input type="text" class="VoucherMOdal" name="" style="width: 39.9%;margin-right: 6%;text-align: center;" value="<?= $Receipt[0]->Time?>" readonly>
                </div>
            </div>
        <?php }?>

        <div style="margin-top: 16%;margin-right: 4%;">
            <div style="margin-top: -1%;">
                <label class="form-label">نام و مکمل پتہ حصہ دار(یا انکے نما ئندے):</label>
                <input type="text" class="VoucherMOdal" name="" style="width: 69.5%;line-height: 0%;" value="<?= $Receipt[0]->Name.' - '.$Receipt[0]->Address?>" readonly>
            </div>
            <div>
                <label class="form-label">فون نمبر :</label>
                <input type="text" class="VoucherMOdal" name="" style="width: 25%;text-align: center;" value="<?= $Receipt[0]->Phone_Number.'-'.$Receipt[0]->Mobile_Number?>" readonly>
            </div>
        </div>
    </div>
    <div class="level">
        <div>
            <div class="row two">
                <div class="col-lg-12">
                    <div class="panel-body" style="margin-top: -3%">
                        <div class="table-responsive" align="middle" style="width: 108%;text-align: center; margin-right: -5%;overflow-x: hidden;overflow-y: hidden;">
                            <table class="table-bordered">
                                <thead>
                                <tr style="line-height: 243%;">
                                    <th style="text-align: center">نمبر شمار</th>
                                    <th style="text-align: center">نام حصہ داران</th>
                                    <th style="text-align: center">تعداد حصص</th>
                                    <th style="text-align: center">رقم فی حصہ</th>
                                    <th style="text-align: center">علی الحساب وصول شدہ رقم</th>
                                    <th style="text-align: center">ہدایات براےُ گوشت قربانی</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $count = 0; $total_hissa = 0; $total_per_head_amount = 0; $total_wsol =0;
                                foreach($Receipt as $key => $value){ ?>
                                    <?php if($value->Equal_Destribution == 1){?>
                                        <tr>
                                            <td><?= ++$key?></td>
                                            <td><?= $value->NameI?></td>
                                            <td>1</td>
                                            <td><?= $Per_Head[0]->Independent_Expance?></td>
                                            <td><?= $Per_Head[0]->Independent_Expance?></td>
                                            <?php $total_per_head_amount += $Per_Head[0]->Amount;
                                            if($value->HissaWaqfCount == 1){?>
                                                <td><?= "وقف"?></td>
                                            <?php } else{?>
                                                <td><?= $value->Description?></td>
                                            <?php }?>
                                        </tr>
                                    <?php }elseif($value->Self_Cow == 1){?>
                                        <tr>
                                            <td><?= ++$key?></td>
                                            <td><?= $value->NameI?></td>
                                            <td>1</td>
                                            <td></td>
                                            <td><?= ($key < 2)?$Per_Head[0]->Independent_Expance:''; ?></td>
                                            <?php $total_per_head_amount = $Per_Head[0]->Amount;
                                            if($value->HissaWaqfCount == 1){?>
                                                <td><?= "وقف"?></td>
                                            <?php } else{?>
                                                <td><?= $value->Description?></td>
                                            <?php }?>
                                        </tr>
                                    <?php }else{?>
                                        <tr>
                                            <td><?= ++$key?></td>
                                            <td><?= $value->NameI?></td>
                                            <td>1</td>
                                            <td><?= $Per_Head[0]->Amount;?></td>
                                            <td><?=$Per_Head[0]->Amount?></td>
                                            <?php $total_per_head_amount += $Per_Head[0]->Amount;
                                            if($value->HissaWaqfCount == 1){?>
                                                <td><?= " وقف ".$value->Description?></td>
                                            <?php } else{?>
                                                <td><?= $value->Description?></td>
                                            <?php }?>
                                        </tr>
                                    <?php } $count = $key;}?>
                                </tbody>
                                <?php if($count > 0){?>
                                    <?php if($Receipt[0]->Equal_Destribution == 1){?>
                                        <tfoot>
                                        <tr style="line-height: 250%;">
                                            <th></th>
                                            <th><span style="float: right;"> میزان:</span></th>
                                            <th style="text-align: center"><?=$key?></th>
                                            <th style="text-align: center"></th>
                                            <th style="text-align: center"><?= number_format($Per_Head[0]->Amount * 7)?><?= ($Receipt[0]->Paid == 0)?' ( بقایاء) ':''?></th>
                                            <th style="text-align: center;font-size: large;line-height: 52px;">گا ئے کی حصہ داران میں مساوی تقسیم</th>
                                        </tr>
                                        </tfoot>
                                    <?php } else{?>
                                        <tfoot>
                                        <tr style="line-height: 250%;">
                                            <th></th>
                                            <th><span style="float: right;"> میزان:</span></th>
                                            <th style="text-align: center"><?=$key?></th>
                                            <th style="text-align: center"><?= ($Receipt[0]->Self_Cow == 1)?'':number_format($Per_Head[0]->Amount) ?></th>
                                            <th style="text-align: center"><?= ($Receipt[0]->Self_Cow == 1)?$Per_Head[0]->Independent_Expance:number_format($Per_Head[0]->Amount*7);?><?= ($Receipt[0]->Paid == 0)?'( بقایاء)':''?></th>
                                            <th style="text-align: center"></th>
                                        </tr>
                                        </tfoot>
                                    <?php } }?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top: -2%;margin-bottom: -1%;">
        <h5 style="text-decoration: underline;">ہدایات براےُ گوشت و متعلقات چرم قربانی</h5>
    </div>
    <div style="float: left;margin-left: -9%;margin-top: -1%;">
        <label class="form-label">دستخط وصول کنندہ</label>
        <br>
        <label class="form-label">تاریخ</label>
        <input type="text" class="VoucherMOdal" name="" style="width: 60%;" readonly>
    </div>
    <div style="line-height: 240%;">
        <span>۱- میرے حصہ/ہمارے حصوں کی چرم قربانی برائے طلبہ دارالعلوم کو دیجائے۔</span><br>
        <span>۲- جو شخص بھی یہ مطبوعہ رسید پیش کرے اسے میرا/ہمارے حصے دید یا جائے/دیدےُ جا ئیں۔</span><br>
        <span>۳- حصہ نہ لینے کی صورت میں ہم دارالعلوم کو اجازت دیتے ہیں کہ  ہمارے حصے کا گوشت،سری،پاےُ،کلیجی اور مغز وغیرہ جن کو مناسب سمجھے تقسیم کرے اگر چہ لینے والے سید یا مال دار ہوں نیز اگر مجھے اپنا حصہ/حصے وصول کرنے میں اتنی تا خیر ہوجاےُ کے گوشت خراب ہونے کا اندشہ ہو تو اس صورت میں دارالعلوم مندرجہ بالا تفصیل کے بطابق اپنی صوابدیدہ پر خرچ یا تقسیم کرنے کا مجاز ہوگا۔</span><br>
        <span>۴-اپنی قربانی کے لُے جانور کی خریداری سے لے کر کے گوشت و غیرہ کی تقسیم تک کے تمام مراحل کے لُے صدر دارا لعلوم کرچی اپنا با اختیار وکیل بنا رہا ہوں وہ خود یا اپنے نما ئندہ کے ذریعے ان امور کا انتظام کریں گے اور تخمینی اخراجات کی رقم جو نقل و حمل،چارہ کھلا ُی، قصاب کی اجرت کی اور دیگر ضروریات و انتظامات کے لُے درکار ہے میرے حساب سے منہا کر کے معا ملہ بے باق کر دیاجاُے ۔ تمام مصارف کے معد تخمینی رقم سے اگر کچھ بچ جائے تو صدر دارا لعلوم حسب  صوابد ید کسی بھی مصرف میں خرچ کر دسکتے ہیں۔ </span><br>
        <span>۵-اگر تخمینی رقم وضع کرنے اور گائے کی خر یداری کے بعد بھی میرے حسہ/حصوں کی کچھ رقم اگر بچ  جاُے تو وہ اگر میں نے یا میرے نما ئندے نے قربانی کے دن وصول نہ کی تو اسے دارا لعلوم میں بطور عطیہ بذریعہ رسید جمع کردی جا ُے ۔</span><br>
        <span>۶۔مرکزِحصص قربانی کی طرف سے کسی کے نام پر کوئی جانور متعین کرنے کے بعد بھی کوئی جانور ہلاک ہوگیا یا چوری ہوکر وقت قربانی سے پہلے نہ مل سکا تو مشترکہ رقم سے دوسراجانور خریدنے کی میری طرف سے اجازت ہے۔</span><br>
        <span style="font-size: larger;">نوٹ :</span><span>مقرررہ وقت  سے گھنٹہ یا ڈیڑھ گھنٹہ کی تا خیر ہو سکتی ہے۔</span><br>
        <span>حصص قربانی کا انتظام جا معہ دارالعلوم کرچی کے مندرجہ ذیل تین مقامات پر کیا جاتا ہے۔</span>
    </div>
    <div style="float: left;margin-left: -9%;margin-top: -4%;">
        <label class="form-label"> دستخط حصہ دار یا نما ئندہ</label>
        <input type="text" class="VoucherMOdal" name="" style="width: 45%;" readonly>
    </div>
    <div>
        <br>
        <span>۱- جا معہ دارالعلوم کرچی کورنگی</span><br>
        <span>کورنگی انڈ سڑ یل ایریا،کرچی</span><br>
        <span>345049774-6</span>
    </div>
    <div style="margin-right: 34%;margin-top: -8%;">
        <span>۲- جا معہ دارالعلوم کرچی نا نکواڑ</span><br>
        <span>بلمقا بل شیخ ہسپتال نزد گوشت مار کیٹ کراچی</span><br>
        <span>فون نمبر :</span>
        <span>32714189</span><br>
        <span style="margin-right: 9%;">32725859</span>
    </div>
    <div style="margin-right: 74%;margin-top: -11%;">
        <span>۳- جا معہ دارالعلوم کرچی گلشن اقبال</span><br>
        <span>احطہ بیت المکرم،مین یو نیورسٹی روڈ کرچی</span><br>
        <span>فون نمبر :</span>
        <span>34982579</span><br>
        <span style="margin-right: 23%;">34825847</span>
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