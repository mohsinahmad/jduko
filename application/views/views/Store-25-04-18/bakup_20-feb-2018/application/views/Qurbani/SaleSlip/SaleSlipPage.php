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
<form action="<?= site_url('Qurbani/SaleSlip/Save')?>" method="post">
    <?php $count=0;$Total=0;$G_Total=0;$Old_Total = 0;$Fresh_Total=0;
    isset($SlipData)?$Name=$SlipData[0]->Name:$Name='';
    isset($SlipData)?$Vendor_Id=$SlipData[0]->Vendor_Id:$Vendor_Id='';
    isset($SlipData)?$Slip_DateG=$SlipData[0]->Slip_DateG:$Slip_DateG='';
    isset($SlipData)?$Slip_DateH=$SlipData[0]->Slip_DateH:$Slip_DateH='';
    isset($SlipData)?$Car_Number=$SlipData[0]->Car_Number:$Car_Number='';
    isset($SlipData)?$Slip_Number=$SlipData[0]->Slip_Number:$Slip_Number='';
    if (isset($SlipData)){
        foreach ($Chrum as $key=>$item) {
            if ($key == 0){
                $Chrum_Data[$key]['Old_Rate'] = $SlipData[0]->Old_Rate_Cow;
                $Chrum_Data[$key]['Old'] = $SlipData[0]->Cow_Old;
                $Chrum_Data[$key]['Fresh_Rate'] = $SlipData[0]->Fresh_Rate_Cow;
                $Chrum_Data[$key]['Fresh'] = $SlipData[0]->Cow_Fresh;
            }elseif ($key == 1){
                $Chrum_Data[$key]['Old_Rate'] = $SlipData[0]->Old_Rate_Goat;
                $Chrum_Data[$key]['Old'] = $SlipData[0]->Goat_Old;
                $Chrum_Data[$key]['Fresh_Rate'] = $SlipData[0]->Fresh_Rate_Goat;
                $Chrum_Data[$key]['Fresh'] = $SlipData[0]->Goat_Fresh;
            }elseif ($key == 2){
                $Chrum_Data[$key]['Old_Rate'] = $SlipData[0]->Old_Rate_Sheep;
                $Chrum_Data[$key]['Old'] = $SlipData[0]->Sheep_Old;
                $Chrum_Data[$key]['Fresh_Rate'] = $SlipData[0]->Fresh_Rate_Sheep;
                $Chrum_Data[$key]['Fresh'] = $SlipData[0]->Sheep_Fresh;
            }elseif ($key == 3){
                $Chrum_Data[$key]['Old_Rate'] = $SlipData[0]->Old_Rate_Camel;
                $Chrum_Data[$key]['Old'] = $SlipData[0]->Camel_Old;
                $Chrum_Data[$key]['Fresh_Rate'] = $SlipData[0]->Fresh_Rate_Camel;
                $Chrum_Data[$key]['Fresh'] = $SlipData[0]->Camel_Fresh;
            }elseif ($key == 4){
                $Chrum_Data[$key]['Old_Rate'] = $SlipData[0]->Old_Rate_Buffelo;
                $Chrum_Data[$key]['Old'] = $SlipData[0]->Buffelo_Old;
                $Chrum_Data[$key]['Fresh_Rate'] = $SlipData[0]->Fresh_Rate_Buffelo;
                $Chrum_Data[$key]['Fresh'] = $SlipData[0]->Buffelo_Fresh;
            }
        }
    }
    isset($SlipData)?$Total_Amount=$SlipData[0]->Total_Amount:$Total_Amount='';
    isset($SlipData)?$CreatedBy=$SlipData[0]->CreatedBy:$CreatedBy='';
    isset($SlipData)?$CreatedOn=$SlipData[0]->CreatedOn:$CreatedOn='';
    isset($SlipData)?$Code=$SlipData[0]->Code:$Code='';
    isset($SlipData)?$Id=$SlipData[0]->S_ID:$Id='';?>
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">سیل سلپ چرم قربانی<?= isset($SlipData)?' - '.$Slip_Number:''?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">وینڈر کا نام</label>
                        <select class="form-control" name="Vendor_Id" style="padding: 0px;margin: 0px">
                            <?php if (isset($SlipData)){?>
                                <option value="<?= $Vendor_Id?>" selected><?= $Name?></option>
                            <?php }else{?>
                                <option disabled selected>منتخب کریں</option>
                            <?php }?>
                            <?php foreach ($Slips as $slip) {?>
                                <option value="<?= $slip->Id?>" ><?= $slip->Name?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control <?= isset($SlipData)?'': 'englishDate'?>" type="date" id="datepicker" value="<?= isset($SlipData)?$Slip_DateG:date('Y-m-d')?>" name="Slip_DateG" required>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control <?= isset($SlipData)?'': 'islamicDate'?>" id="EislamicDate" name="Slip_DateH" style="width: 250px;" value="<?= isset($SlipData)?$Slip_DateH:''?>" type="date" readonly required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">گاڑی نمبر</label>
                        <input class="form-control" name="Car_Number" style="width: 250px;" value="<?= isset($SlipData)?$Car_Number:''?>" type="text" required>
                    </div>
                </div>
            </div>
            <?php foreach ($Chrum as $key=>$item) {
            if ($key==0){?>
            <div class="row" style="margin-top: 3%">
                <?php }else{?>
                <div class="row">
                    <?php }?>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key==0){?>
                                <label class="control-label" for="inputSuccess">چرم کی قسم</label>
                            <?php }?>
                            <input  class="form-control" name="chrum[]" style="width: 150px;" value="<?= $item->chrum_type?>" type="text" readonly>
                            <input  class="form-control" name="chrum_Id[]" style="width: 150px;" value="<?= $item->id?>" type="hidden">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key==0){?>
                                <label class="control-label" for="inputSuccess">تازہ چرم کی تعداد</label>
                            <?php }?>
                            <input  class="form-control quantity fresh" id="fresh<?= $key?>" name="fresh[]" data-id="<?= $key?>" style="width: 150px;" value="<?= isset($SlipData)?$Chrum_Data[$key]['Fresh']:'';?>" type="number">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key==0){?>
                                <label class="control-label" for="inputSuccess">تازہ چرم کی قیمت</label>
                            <?php }?>
                            <input  class="form-control" name="Fresh_Rate[]" id="ratefresh<?= $key?>" style="width: 150px;" value="<?= isset($SlipData)?$Chrum_Data[$key]['Fresh_Rate']:$item->latest_amount?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key==0){?>
                                <label class="control-label" for="inputSuccess">باسی چرم کی تعداد</label>
                            <?php }?>
                            <input  class="form-control quantity old" id="old<?= $key?>" data-id="<?= $key?>" name="old[]" style="width: 150px;" value="<?= isset($SlipData)?$Chrum_Data[$key]['Old']:''?>" type="number">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key==0){?>
                                <label class="control-label" for="inputSuccess">باسی چرم کی قیمت</label>
                            <?php }?>
                            <input  class="form-control" id="rateold<?= $key?>" name="Old_Rate[]" style="width: 150px;" value="<?= isset($SlipData)?$Chrum_Data[$key]['Old_Rate']:$item->old_amount?>" type="text">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <?php if ($key == 0){?>
                                <label class="control-label" for="inputSuccess">کل رقم</label>
                            <?php } if (isset($SlipData)){ $Total = ($Chrum_Data[$key]['Old_Rate'] * $Chrum_Data[$key]['Old']) + ($Chrum_Data[$key]['Fresh_Rate'] * $Chrum_Data[$key]['Fresh']);
                                $Old_Total += $Chrum_Data[$key]['Old'];
                                $Fresh_Total += $Chrum_Data[$key]['Fresh'];}?>
                            <input class="form-control Total" id="Total<?= $key?>" name="Total" style="width: 150px;" value='<?= isset($SlipData)?($Total):''?>' type="text">
                        </div>
                    </div>
                </div>
                <?php $Total=0; }?>
                <div class="row">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" style="width: 150px;float: left;margin-left: -16px;" id="Total_Amount" name="Total_Amount" value="<?= isset($SlipData)?($Total_Amount):''?>" readonly>
                        <input class="form-control" type="number" style="width: 150px;float: left;margin-left: 185px;" id="Total_Old" value="<?= isset($SlipData)?($Old_Total):''?>" readonly>
                        <input class="form-control" type="number" style="width: 150px;float: left;margin-left: 185px;" id="Total_Fresh" value="<?= isset($SlipData)?($Fresh_Total):''?>" readonly>
                        <button type="submit"  class="btn btn-primary data-save" >محفوظ کریں</button>
                    </div>
                </div>
            </div>
        </div>
        <?php if (isset($SlipData)){?>
            <button type="button" class="btn btn-primary data-save Delete" data-id="<?= $Id?>" style="font-size: 10px; ">حذف کریں</button>
            <input type="hidden" name="CreatedBy" value="<?= $CreatedBy?>">
            <input type="hidden" name="CreatedOn" value="<?= $CreatedOn?>">
            <input type="hidden" name="Slip_Number" value="<?= $Slip_Number?>">
            <input type="hidden" name="Edit" value="<?= $Id?>">
        <?php }?>
</form>
<script type="text/javascript">
    $(document).on('ready',function() {
        var Sum = parseInt(0);
        var Sum_Fresh = parseInt(0);
        var Sum_Old = parseInt(0);
        var total = parseInt(0);
        var total_Fresh = parseInt(0);
        var total_Old = parseInt(0);
        var key;
        $(".quantity").blur(function(){
            var key = $(this).data('id');
            var quantity_old = $('#old'+key).val();
            if(quantity_old == '') {
                quantity_old = 0;
            }
            var rate_old = $('#rateold'+key).val();
            var total_old = parseInt(rate_old) * parseInt(quantity_old);

            var quantity_fresh = $('#fresh'+key).val();
            if(quantity_fresh == '') {
                quantity_fresh = 0;
            }
            var rate_fresh = $('#ratefresh'+key).val();
            var total_fresh = parseInt(rate_fresh) * parseInt(quantity_fresh);
            var Total_Amount = parseInt(total_old) + parseInt(total_fresh);


            $('#Total'+key).val('');
            $('#Total'+key).val(Total_Amount);

            $(".Total").each(function() {
                var value = $( this ).val();
                if (value != ''){
                    Sum = parseInt(Sum) + parseInt(value);
                }
            });
            $('#Total_Amount').val('');
            $('#Total_Amount').val(Sum);
            Sum = 0;
        });

        $(".fresh").blur(function(){
            var Sum_Fresh = 0;

            $(".fresh").each(function() {
                var value = $( this ).val();
                if (value != ''){
                    Sum_Fresh = parseInt(Sum_Fresh) + parseInt(value);
                }
            });

            $('#Total_Fresh').val('');
            $('#Total_Fresh').val(Sum_Fresh);
        });

        $(".old").blur(function(){
            var Sum_Old = 0;
            $(".old").each(function() {
                var value = $( this ).val();
                if (value != ''){
                    Sum_Old = parseInt(Sum_Old) + parseInt(value);
                }
            });
            $('#Total_Old').val('');
            $('#Total_Old').val(Sum_Old);
        });
    });

    $(document).on('ready',function() {
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

    $('.Delete').on('click',function(){
        var id = $(this).data('id');
        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ اس حلقے کو حذف کرنا چاہتے ہیں؟',
                icon: 'glyphicon glyphicon-question-sign',
                hide: false,
                type: "success",
                confirm: {
                    confirm: true,
                    buttons: [{
                        text: "جی ہاں", addClass: "", promptTrigger: true, click: function (notice, value) {
                            notice.remove();
                            notice.get().trigger("pnotify.confirm", [notice, value]);
                        }
                    }, {
                        text: "نیہں", addClass: "", click: function (notice) {
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
                url: '<?= site_url('Qurbani/SaleSlip/Delete/')?>'+ id,
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: "حذف کردیا گیا۔۔۔",
                            type: 'success',
                            hide: true
                        });
                    }else{
                        new PNotify({
                            title: 'حذف',
                            text: "حذف نہیں کیا جاسکا، دوبارہ کوشش کیجیئے۔۔!!'",
                            type: 'error',
                            hide: true
                        });
                    }
                    setTimeout(function () {
                        window.location = "<?= site_url('Qurbani/SaleSlip');?>";
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>