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
<form action="<?= site_url('Qurbani/Receipt/UpdateReceipt')?>" method="post">
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">   قربانی رسید - <span style="font-size: 22px;position: absolute;margin-top: 10px;">سلپ نمبر - <?= $Receipt[0]->Slip_Number?></span></h1>
                    <input type="hidden" id="slip_id" value="<?= $Receipt[0]->S_ID?>">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">نام</label>
                        <input  class="form-control" name="name" style="width: 250px;" value="<?= $Receipt[0]->Name?>"  type="text" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">پتہ</label>
                        <input  class="form-control" name="Address" style="width: 250px;" value="<?= $Receipt[0]->Address?>" type="text" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">وقت</label>
                        <input  class="form-control" id="Time" name="Time" style="width: 250px;" value="<?= $Receipt[0]->Time?>" type="time" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">موبائل نمبر</label>
                        <input  class="form-control PhoneNo" name="Mobile_Number" style="width: 250px;" value="<?= $Receipt[0]->Mobile_Number?>" type="number" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">فون نمبر</label>
                        <input  class="form-control PhoneNo" name="Phone_Number" style="width: 250px;" value="<?= $Receipt[0]->Phone_Number?>" type="number" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="date" id="datepicker" name="ReceiptDateG" value="<?= $Receipt[0]->Slip_Date_G?>" required>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control islamicDate" id="EislamicDate" name="ReceiptDateH" style="width: 250px;" value="<?= $Receipt[0]->Slip_Date_H?>" type="date" readonly required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <label class="control-label" for="inputSuccess">قربانی کا دن</label>
                    <div class="form-group">
                        <?php if($Receipt[0]->Collection_Day == 1){?>
                            <label class="radio-inline"><input type="radio" value="1" checked name="Collection_Day" required>۱۰ ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="2" name="Collection_Day" required>۱۱  ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="3" name="Collection_Day" required>۱۲ ذی الحج</label>
                        <?php }else if($Receipt[0]->Collection_Day == 2){?>
                            <label class="radio-inline"><input type="radio" value="1" name="Collection_Day" required>۱۰ ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="2" checked name="Collection_Day" required>۱۱  ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="3" name="Collection_Day" required>۱۲ ذی الحج</label>
                        <?php }else if($Receipt[0]->Collection_Day == 3){?>
                            <label class="radio-inline"><input type="radio" value="1" name="Collection_Day" required>۱۰ ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="2" name="Collection_Day" required>۱۱  ذی الحج</label>
                            <label class="radio-inline"><input type="radio" value="3" checked name="Collection_Day" required>۱۲ ذی الحج</label>
                        <?php }?>
                    </div>
                </div>
                <div class="col-md-4">
                    <label>رسید کی قسم</label>
                    <div class="form-group">
                        <?php if($Receipt[0]->Self_Cow == 0){?>
                            <label class="radio-inline"><input type="radio" value="1" name="ReceiptType" checked required>اجتماعی قربانی</label>
                            <label class="radio-inline"><input type="radio" value="0" name="ReceiptType" required>خود خرید کردہ گاےَ</label>
                        <?php }else{?>
                            <label class="radio-inline"><input type="radio" value="1" name="ReceiptType"  required>اجتماعی قربانی</label>
                            <label class="radio-inline"><input type="radio" value="0" name="ReceiptType" checked required>خود خرید کردہ گاےَ</label>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-xs-4 CowNumber">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">گاےَ نمبر</label>
                        <input class="form-control" name="CowNumber" id="CowNumber" value="<?= $Receipt[0]->Code?>" readonly="readonly" style="width: 250px;" type="text" required>
                    </div>
                </div>
            </div>
            <div class="row IjtimaeQurbani">
                <div class="col-xs-4 ">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">رقم فی حصہ</label>
                        <input class="form-control" name="" id="PerHead" style="width: 250px;" type="text" readonly>
                    </div>
                </div>
                <div class="col-xs-4 IjtimaeQurbaniLabel">
                    <div class="form-group">
                        <label class="control-label IjtimaeLabel" for="inputSuccess">کل تعداد حصص</label>
                        <label class="control-label waqf" for="inputSuccess" style="display: none">تعداد وقف</label>
                        <input  class="form-control" id="quantity" value="<?= $Receipt[0]->total_quantity?>" name="quantity" style="width: 250px;"  type="text" required>
                    </div>
                </div>
                <div class="col-xs-4 IjtimaeQurbaniLabel">
                    <div class="form-group">
                        <label class="control-label " for="inputSuccess">علی الحساب وصول شدہ رقم</label>
                        <input  class="form-control" name="TotalAmount"  value="<?= $Receipt[0]->Total_Amount?>" id="Total" style="width: 250px;" type="text" readonly="readonly">
                    </div>
                </div>
            </div>

            <div class="row IjtimaeQurbani1">
                <div class="col-xs-4">
                    <button type="submit"  class="btn btn-primary data-save">محفوظ کریں</button>
                    <button type="button"  class="btn btn-primary delete_slip">حذف کریں</button>
                </div>
                <div class="[ form-group ] col-xs-4 IjtimaeQurbani2"  style="display: none">
                    <input type="checkbox" name="EqualyDivide" id="fancy-checkbox-success" autocomplete="off" value="1" <?= $Receipt[0]->Equal_Destribution == 1 ? 'checked' : ''?> />
                    <div class="[ btn-group ]" style="right: -55%;">
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
                    <?php if($Receipt[0]->Paid == 1){?>
                        <input type="checkbox" name="NotPaid" value="<?= $Receipt[0]->Paid?>">
                    <?php }else{?>
                        <input type="checkbox" name="NotPaid" value="<?= $Receipt[0]->Paid?>" checked>
                    <?php }?>
                    <label>بعد میں رقم کی ادائیگی</label>
                </div>
            </div>
            <div class="hiddenFields">
                <input type="hidden" name="Slip_Number" value="<?= $Receipt[0]->Slip_Number?>">
                <input type="hidden" name="Slip_Id" id="slip_id" value="<?= $Receipt[0]->S_ID?>">

            </div>

        </div>
    </div>
    <div class="col-lg-12 ">
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
                            <?php foreach($Receipt as $Key => $HissaDaran):?>
                                <?php if(is_numeric($Key)):?>
                                    <tr class="addnew">
                                        <td>
                                            <input class="form-control" name="HissaName[]" value="<?= $HissaDaran->NameI?>" type="text">
                                        </td>
                                        <td>
                                            <textarea class="form-control Hissadescription" rows="1" name="HissaDescription[]"><?= $HissaDaran->Description?></textarea>
                                        </td>
                                        <td>
                                            <input class="HissaWaqf" name="HissaWaqf<?=$Key?>" type="checkbox" value="1" <?= $HissaDaran->HissaWaqfCount == 1 ? 'checked' : '' ?> >
                                        </td>
                                    </tr>
                                <?php endif?>
                            <?php endforeach ?>
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

        ReceiptTypeEvent();

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

    $('.delete_slip').on('click',function(){
        var slip_id = $('#slip_id').val();
        var Day = $('input[name=Collection_Day]:checked').val();
        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                type: "success",
                confirm: {
                    confirm: true,
                    buttons: [{
                        text: "ٹھیک ہے", addClass: "", promptTrigger: true, click: function (notice, value) {
                            notice.remove();
                            notice.get().trigger("pnotify.confirm", [notice, value]);
                        }
                    }, {
                        text: "منسوخ", addClass: "", click: function (notice) {
                            notice.remove();
                            notice.get().trigger("pnotify.cancel", notice);
                        }
                    }]
                },
                buttons: {
                    closer: false,
                    sticker: false
                },
                history: {
                    history: false
                },
                addclass: 'stack-modal',
                stack: {'dir1': 'down', 'dir2': 'right', 'modal': true}
            })
        ).get().on('pnotify.confirm', function () {
            $.ajax({
                url: '<?= site_url('Qurbani/Receipt/deleteReceipt')?>' + '/' + slip_id + '/' + Day,
                success: function (response) {
                    var data = $.parseJSON(response);
                    console.log(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: data['message'],
                            type: 'success',
                            hide: true
                        });
                    }else{
                        new PNotify({
                            title: 'کامیابی',
                            text: data['message'],
                            type: 'error',
                            hide: true
                        });
                    }setTimeout(function () {
                        window.location.href = "<?= site_url('Qurbani/Receipt') ?>";
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
    $('input[name=ReceiptType]').on('change',function(){
        var ReceiptType = $(this).val();
        var jdk_donate = $('input[name=Collection_Status]:checked').val();
        var Day = $('input[name=Collection_Day]:checked').val();
        $('#Total').val('');
        $('#quantity').val('');
        if(ReceiptType == 1){
            $('#PerHead').val('<?= $Per_Head[0]->Amount?>');
            $('.IjtimaeQurbaniLabel').removeAttr("style");
            $('#CowNumber').val('');
        }else{
            $('#PerHead').val('<?= $Per_Head[0]->Independent_Expance?>');
            $('.IjtimaeQurbaniLabel').css("display","none");
            $.ajax({
                type:'POST',
                url:'<?= site_url('Qurbani/Receipt/getSelfCowNumber');?>',
                returnType:'JSON',
                data:{'day':Day},
                success:function(response){
                    $('#CowNumber').val(response);
                }
            });
        }
    });

    function ReceiptTypeEvent(){
        var ReceiptType = $('input[name=ReceiptType]:checked').val();
        var Day = $('input[name=Collection_Day]:checked').val();
        if(ReceiptType == 1){
            $('#PerHead').attr('value','<?= $Per_Head[0]->Amount?>');
            $('.IjtimaeQurbaniLabel').removeAttr("style");
            $('.IjtimaeQurbani2').css("display",'none');
        }else{
            $('#PerHead').val('<?= $Per_Head[0]->Independent_Expance?>');
            $('.IjtimaeQurbani2').removeAttr("style");
            $('.IjtimaeQurbaniLabel').css("display","none");
        }
    }

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
        var slipQuantity = '<?= $Receipt[0]->total_quantity?>';
        var prevCowCount = '<?= $Receipt[0]->total_quantity?>';
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
                $('#Total').val(qty*Perhead);
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
            }else if(parseInt(qty) > parseInt(prevCowCount)){
                new PNotify({
                    title: 'انتباہ',
                    text: "حصص کی تعداد پچھلی تعداد سے زیادہ نہیں ہوسکتی",
                    type: 'error',
                    delay: 3000,
                    hide: true
                });
                $('.data-save').attr('disabled',true);
            }else if(parseInt(qty) == 7){
                $('.IjtimaeQurbani2').removeAttr("style");
                if(isChecked != 1){ // equal divide
                    $.ajax({
                        type:'POST',
                        url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                        returnType:'JSON',
                        data:{'qty':qty,'day':Day},
                        success:function(response){
                            var data = $.parseJSON(response);
                            //console.log(response);
                            $('#CowNumber').val(data.Code);
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
                        url:'<?= site_url('Qurbani/Receipt/checkCowCount');?>',
                        returnType:'JSON',
                        data:{'code':prevCowNumber,'day':Day},
                        success:function(response){
                            var data = $.parseJSON(response);
                            pCowCount = data['Count'];
                            newQuant = pCowCount - slipQuantity;
                            if(newQuant < 7){
                                $.ajax({
                                    type:'POST',
                                    url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                                    returnType:'JSON',
                                    data:{'qty':newQuant,'day':Day},
                                    success:function(response2){
                                        var data2 = $.parseJSON(response2);
                                        $('#CowNumber').val(data2.Code);
                                    }
                                });
                            }else{
                                $.ajax({
                                    type:'POST',
                                    url:'<?= site_url('Qurbani/Receipt/getCowNumber');?>',
                                    returnType:'JSON',
                                    data:{'qty':qty,'day':Day},
                                    success:function(response3){
                                        var data3 = $.parseJSON(response3);
                                        $('#CowNumber').val(data3.Code);
                                    }
                                });
                            }
                        }
                    }).done(function(){
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
        var Qty = $('#quantity').val();
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