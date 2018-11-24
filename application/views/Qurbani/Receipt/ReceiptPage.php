<link rel="stylesheet" href="<?= base_url()."assets/css/"; ?>jquery-ui.css">
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script src="<?= base_url()."assets/js/"; ?>jquery-ui.js"></script>
<style>
    div.dataTables_info{
        display: none;
    }div.dataTables_paginate{
         display: none;
     }div.dataTables_length label{
          display: none;
      }
    #res {
        margin: 0px;
        padding-left: 0px;
        width: 150px;
    }
    #res li {
        list-style-type: none;
    }
    #res li:hover {
        cursor: pointer;
    }
    #dataTables-example tr:hover  {
        cursor: pointer;
    }
    .demand tr:hover  {
        cursor: pointer;
    }
    .form-group input[type="checkbox"] {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }
    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }
    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }
</style>
<form action="<?= site_url('Qurbani/Receipt/SaveReceipt')?>" method="post">
    <?php $count=0;?>
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">قربانی رسید</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">نام</label>
                        <input  class="form-control" name="name" style="width: 250px;" value="" type="text" autofocus required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">پتہ</label>
                        <input  class="form-control" name="Address" style="width: 250px;" value="" type="text" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">وقت</label>
                        <input  class="form-control" id="Time" name="Time" style="width: 250px;" value="" type="time" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">موبائل نمبر</label>
                        <input  class="form-control PhoneNo" name="Mobile_Number" style="width: 250px;" value="" type="number" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">فون نمبر</label>
                        <input  class="form-control PhoneNo" name="Phone_Number" style="width: 250px;" value="" type="number" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="date" id="datepicker" name="ReceiptDateG" required>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control islamicDate" id="EislamicDate" name="ReceiptDateH" style="width: 250px;"  type="date" readonly required>
                    </div>
                </div>
                <!--                 <div class="col-md-4">
                                    <label>َہدایات برائے گوشت قربانی</label>
                                    <div class="form-group">
                                        <label class="radio-inline"><input type="radio" value="1" name="Collection_Status" required>وقف</label>
                                        <label class="radio-inline"><input type="radio" value="0" name="Collection_Status" required checked>ذاتی استعمال</label>
                                        <label class="radio-inline"><input type="radio" value="2" name="Collection_Status" class="jdk_donate" required >وقف + ذاتی استعمال</label>
                                    </div>
                                </div> -->

            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess">قربانی کا دن</label>
                    <div class="form-group">
                        <label class="radio-inline"><input type="radio" value="1" name="Collection_Day" required checked>۱۰ ذی الحج</label>
                        <label class="radio-inline"><input type="radio" value="2" name="Collection_Day" required>۱۱  ذی الحج</label>
                        <label class="radio-inline"><input type="radio" value="3" name="Collection_Day" required>۱۲ ذی الحج</label>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>رسید کی قسم</label>
                    <div class="form-group">
                        <label class="radio-inline"><input type="radio" value="1" name="ReceiptType" required>اجتمایَ قربانی</label>
                        <label class="radio-inline"><input type="radio" value="0" name="ReceiptType" required>خود خرید کردہ گاےَ</label>
                    </div>
                </div>
                <div class="col-xs-4 CowNumber">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">گاےَ نمبر</label>
                        <input class="form-control" style="width: 250px;" type="text" name="CowNumber" id="CowNumber" readonly>
                    </div>
                </div>
            </div>
            <div class="row IjtimaeQurbani">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">رقم فی حصہ</label>
                        <input class="form-control" name="" id="PerHead" style="width: 250px;" type="text" readonly>
                    </div>
                </div>
                <div class="col-xs-4 IjtimaeQurbaniLabel">
                    <div class="form-group">
                        <label class="control-label IjtimaeLabel" for="inputSuccess">کل تعداد حصص</label>
                        <input  class="form-control" id="quantity" name="quantity" value="" style="width: 250px;" type="text">
                    </div>
                </div>
                <div class="col-xs-4 IjtimaeQurbaniLabel" >
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">علی الحساب وصول شدہ رقم</label>
                        <input  class="form-control" name="TotalAmount" id="Total" style="width: 250px;" type="text" readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="row" >
                <div class="col-xs-1">
                    <button type="submit"  class="btn btn-primary data-save" >محفوظ کریں</button>
                </div>
                <div class="[ form-group ] col-xs-3 IjtimaeQurbani2" style="display: none">
                    <input type="checkbox" name="EqualyDivide" id="fancy-checkbox-success" autocomplete="off" value="1" />
                    <div class="[ btn-group ]">
                        <label for="fancy-checkbox-success" class="[ btn btn-success ]">
                            <span class="[ glyphicon glyphicon-ok ]"></span>
                            <span> </span>
                        </label>
                        <label for="fancy-checkbox-success" class="[ btn btn-default active ]">
                            حصہ داران میں مساوی تقسیم
                        </label>
                    </div>
                </div>
                <div class="col-xs-3" style="float: left">
                    <input type="checkbox" name="NotPaid" value="0">
                    <label>بعد میں رقم کی ادائیگی</label>
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="dataTables">
                            <thead>
                            <tr>
                                <th style="width: 25%;">حصہ داران کا نام</th>
                                <th style="width: 50%;">تفصیل</th>
                                <th>براےَ وقف</th>
                            </tr>
                            </thead>
                            <tbody class="toEdit">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $(document).on('ready',function() {
        $('.PhoneNo').keypress(function() {
            if (this.value.length >= 11) {
                return false;
            }
        });

        $('.IjtimaeQurbani2').attr('style','display:none');

        var now = new Date();

        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = now.getFullYear()+"-"+(month)+"-"+(day);
        $('.englishDate').val(today);

        var date = $('.englishDate').val();
        $.ajax({
            type:"GET",
            url:'<?= site_url('Accounts/Books/getDate');?>'+'/'+date,
            success:function(response){
                var data = $.parseJSON(response);
                $('.islamicDate').val(data.date);
            }
        });
    });

    $('input[name=EqualyDivide]').on('click',function(){
        var equal_dist = $('input[name=EqualyDivide]:checked').val(); // 1 = equal dist
        var receipt_type = $('input[name=ReceiptType]:checked').val(); // 1 = ijtemai
        var a = 0;
        $('.toEdit').html('');
        if (equal_dist == 1){
            if (receipt_type == 0){
                for (a=0;a<7;a++){
                    $('.toEdit').append('<tr class="addnew"><td><input class="form-control HissaName" name="HissaName[]" type="text"></td><td><textarea class="form-control Hissadescription" rows="1" name="HissaDescription[]"></textarea></td><td><input class="HissaWaqf" name="HissaWaqf'+a+'" type="checkbox" value="1"></td></tr>');
                }
            }
        }else{
            if (receipt_type == 0){
                for (a=0;a<7;a++){
                    $('.toEdit').append('<tr class="addnew"><td><input class="form-control HissaName" name="HissaName[]"   type="text" required></td><td><textarea class="form-control Hissadescription" rows="1" name="HissaDescription[]"></textarea></td><td><input class="HissaWaqf" name="HissaWaqf'+a+'" type="checkbox" value="1"></td></tr>');
                }
            }
        }
    });

    $('input[name=ReceiptType]').on('change',function(){
        var receip_type = $(this).val();
        var equal_dist = $('input[name=EqualyDivide]:checked').val();
        $('.toEdit').html('');
        $('#PerHead').removeAttr('name');
        if (receip_type == 0){
            $('#quantity').val("7");
            $('.IjtimaeQurbani2').removeAttr("style");
            $('#Total').removeAttr("name");
            $('#PerHead').attr("name",'TotalAmount');
            for (a=0;a<7;a++){
                $('.toEdit').append('<tr class="addnew"><td><input class="form-control HissaName" name="HissaName[]" type="text" required></td><td><textarea class="form-control Hissadescription" rows="1" name="HissaDescription[]"></textarea></td><td><input class="HissaWaqf" name="HissaWaqf'+a+'" type="checkbox" value="1"></td></tr>');
            }
        }else {
            $('#quantity').val("");
        }
    });

    $('input[name=ReceiptType]').on('change',function(){
        var ReceiptType = $(this).val();
        var qty = 7;
        var jdk_donate = $('input[name=Collection_Status]:checked').val();
        var Day = $('input[name=Collection_Day]:checked').val();
        $('#Total').val('');
        $('#quantity').attr('value',qty);
        if(ReceiptType == 1){
            $('#PerHead').val('<?= $Per_Head[0]->Amount?>');
            $('.IjtimaeQurbaniLabel').removeAttr("style");
            $('#CowNumber').val('');
        }else{
            $('#PerHead').val('<?= $Per_Head[0]->Independent_Expance?>');
            $('.IjtimaeQurbaniLabel').css("display","none");
            $.ajax({
                type:'POST',
                url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                returnType:'JSON',
                data:{'day':Day,'qty':qty},
                success:function(response){
                    var data = $.parseJSON(response);
                    $('#CowNumber').val(data.Code);
                    $('#Time').val(data.Time);
                }
            });
        }
    });

    $('input[name=EqualyDivide]').on('click',function(){
        var isChecked = $('input[name=EqualyDivide]:checked').val();
        var Day = $('input[name=Collection_Day]:checked').val();
        var ReceiptType = $('input[name=ReceiptType]:checked').val();
        var qty = $('#quantity').val();
        if(isChecked == 1){
            if(ReceiptType == 1){
                $.ajax({
                    type:'POST',
                    url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                    returnType:'JSON',
                    data:{'qty':qty,'day':Day},
                    success:function(response){
                        var data = $.parseJSON(response);
                        $('#CowNumber').val(data.Code);
                        $('#Time').val(data.Time);
                    }
                });
            }else{
                $.ajax({
                    type:'POST',
                    url:'<?= site_url('Qurbani/Receipt/getSelfCowNumber');?>',
                    returnType:'JSON',
                    data:{'day':Day},
                    success:function(response){
                        var data = $.parseJSON(response);
                        $('#CowNumber').val(data.Code);
                        $('#Time').val(data.Time);
                    }
                })
            }
        }else{
            $('#CowNumber').val('');
        }
    });

    $('.englishDate').on('change',function(){
        var date = $(this).val();
        $.ajax({
            type:"GET",
            url:'<?= site_url('Accounts/Books/getDate');?>'+'/'+date,
            success:function(response){
                var data = $.parseJSON(response);
                $('.islamicDate').val(data.date);
            }
        });
    });

    $("#dataTables").on( "click", ".edit", function(e) {
        var quantity = $('#quantity').val();
        if(quantity){
            var trLength = $('tr.addnew').length;
            var tr = $(this).parents('tr.addnew');
            var clone = tr.clone();
            clone.find('input').val('');
            clone.find('textarea').val('');
            if(trLength >= quantity-1){
                $('button.edit').attr('disabled',true);
                clone.find('button.edit').attr('disabled',true);
            }
            clone.insertAfter('.addnew:last');
        }else{
            new PNotify({
                title: 'انتباہ',
                text: "پیہلے تعداد حصص اندراج کریں",
                type: 'error',
                delay: 3000,
                hide: true
            });
        }
    });

    $( "#dataTables" ).on( "click", ".del", function(e) {
        e.preventDefault();
        var tr = $(this).parents('tr');
        $( this ).parents( "tr" ).remove();
    });

    $(document).ready(function(){
        $("#Time").blur(function(){
            var time = $(this).val();
            var receipt_type = $('input[name=ReceiptType]:checked').val(); // 1 = ijtemai
            if (receipt_type == 1){
                var qty = $("#quantity").val();
            }else{
                var qty = 7;
            }
            var Day = $('input[name=Collection_Day]:checked').val();

            $.ajax({
                type:'POST',
                url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                returnType:'JSON',
                data:{'qty':qty,'day':Day,'time':time},
                success:function(response){
                    var data = $.parseJSON(response);
                    $('#CowNumber').val('');
                    $('#CowNumber').val(data.Code);
                }
            }).done(function(){
                //$('#Total').val('');
                //$('#Total').val(qty*Perhead);
            });
        });
    });

    $(document).ready(function(){
        $("#quantity").keyup(function(){
            var qty = $(this).val();
            var Day = $('input[name=Collection_Day]:checked').val();
            var Perhead = $('#PerHead').val();
            var ReceiptType = $('input[name=ReceiptType]:checked').val();
            var isChecked = $('input[name=EqualyDivide]:checked').val();
            var a = 0;
            $('.toEdit').html('');
            if (ReceiptType == 1){
                for (a=0;a<qty;a++){
                    $('.toEdit').append('<tr class="addnew"><td><input class="form-control" name="HissaName[]" type="text" required></td><td><textarea class="form-control Hissadescription" rows="1" name="HissaDescription[]"></textarea></td><td><input class="HissaWaqf" name="HissaWaqf'+a+'" type="checkbox" value="1"></td></tr>');
                }
            }
            if ((ReceiptType != 1 && ReceiptType != 2)){
                $('.data-save').attr('disabled',true);
                $('#CowNumber').val('');
                $('#Total').val('');
                $('#quantity').val('');
                new PNotify({
                    title: 'انتباہ',
                    text: "پہلے رسید کی قسم بتایئں ",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
            }else if(parseInt(qty) > 7){
                new PNotify({
                    title: 'انتباہ',
                    text: "شریعت میں گاےَ کے لیے ۷ حصوں کی اجازت ہے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $('.data-save').attr('disabled','true');
            }else if(!qty){
                $('#CowNumber').val('');
                $('#Total').val('');
                $('.IjtimaeQurbani2').css("display",'none');
            }else if(parseInt(qty) == 7){
                $('.IjtimaeQurbani2').removeAttr("style");
                if(isChecked != 1){
                    $.ajax({
                        type:'POST',
                        url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                        returnType:'JSON',
                        data:{'qty':qty,'day':Day},
                        success:function(response){
                            var data = $.parseJSON(response);
                            console.log(response);
                            $('#CowNumber').val(data.Code);
                            $('#Time').val(data.Time);
                        }
                    }).done(function(){
                        //$('#Total').val('');
                        $('#Total').val(qty*Perhead);
                    });
                }else{
                    $('#Total').val(qty*Perhead);
                }
            }else{
                $('.IjtimaeQurbani2').css("display",'none');
                $('.data-save').removeAttr('disabled');
                if(isChecked != 1){
                    $.ajax({
                        type:'POST',
                        url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                        returnType:'JSON',
                        data:{'qty':qty,'day':Day},
                        success:function(response){
                            var data = $.parseJSON(response);
                            console.log(data);
                            $('#CowNumber').val(data.Code);
                            $('#Time').val(data.Time);
                        }
                    }).done(function(){
                        //$('#Total').val('');
                        $('#Total').val(qty*Perhead);
                    });
                }else{
                    $('#Total').val(qty*Perhead);
                }
            }
        });
    });

    $("form").submit(function( event ) {
        var isChecked = $('input[name=EqualyDivide]:checked').val();
        var ReceiptType = $('input[name=ReceiptType]:checked').val(); // 1 = ijtemai | 0 = khud ki
//        var jdk_donate = $('input[name=Collection_Status]:checked').val();
        var Qty = $('#quantity').val();
//        var JDK_Qty;
        var totalQuantity = 0;
        var totalQuantity = $('#trCount').val();
        //return;
        if(ReceiptType == 1 && isChecked != 1){
            if(parseInt(totalQuantity) != parseInt(Qty)){
                return;
                new PNotify({
                    title: 'انتباہ',
                    text: "حصوں کی مقدار درست کیجیے",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                event.preventDefault();
            }else{
                return;
            }
        }else if(isChecked != 1){
            if(ReceiptType == 0){
                return;
            }else{
                // JDK_Qty = $('#JDK_CountI').val();
                // Qty = Qty - JDK_Qty;
                if(parseInt(totalQuantity) != parseInt(Qty)){
                    new PNotify({
                        title: 'انتباہ',
                        text: "حصوں کی مقدار درست کیجیے",
                        type: 'error',
                        delay: 3000,
                        hide: true
                    });
                    event.preventDefault();
                }else{
                    return;
                }
            }
        }else{
            return;
        }
    });
</script>