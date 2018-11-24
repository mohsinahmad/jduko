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
<form action="<?= site_url('Qurbani/CashReciept/Save')?>" method="post">
    <?php $count=0;
    isset($Reciepts)?$Reciept_Number=$Reciepts[0]->Reciept_Number:$Reciept_Number='';
    isset($Reciepts)?$Vendor_Id=$Reciepts[0]->Vendor_Id:$Vendor_Id='';
    isset($Reciepts)?$Name=$Reciepts[0]->Name:$Name='';
    isset($Reciepts)?$DateG=$Reciepts[0]->DateG:$DateG='';
    isset($Reciepts)?$DateH=$Reciepts[0]->DateH:$DateH='';
    isset($Reciepts)?$Amount=$Reciepts[0]->Amount:$Amount='';
    isset($Reciepts)?$Remarks=$Reciepts[0]->Remarks:$Remarks='';
    isset($Reciepts)?$CreatedBy=$Reciepts[0]->CreatedBy:$CreatedBy='';
    isset($Reciepts)?$CreatedOn=$Reciepts[0]->CreatedOn:$CreatedOn='';
    isset($Reciepts)?$Id=$Reciepts[0]->Reciept_Id:$Id='';
    ?>
    <div class="row">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header" style="margin-top: 10px;">کیش رسید<?= isset($Reciepts)?' - '.$Reciept_Number:''?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">وینڈر کا نام</label>
                        <select class="form-control" name="Vendor_Id" style="padding: 0px;margin: 0px" required>
                            <option <?= isset($Reciepts)?'value="'.$Vendor_Id.'"':'disabled'?> disabled selected><?= isset($Reciepts)?$Name:'منتخب کریں'?></option>
                            <?php foreach ($Vendors as $slip) {?>
                                <option value="<?= $slip->Id?>"><?= $slip->Name?></option>
                            <?php }?>
                        </select>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control <?= isset($Reciepts)?'':'englishDate'?>" type="date" id="datepicker" value="<?= isset($Reciepts)?$DateG:date('Y-m-d')?>" name="DateG" required>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control islamicDate" id="EislamicDate" name="DateH" style="width: 250px;" value="<?= isset($Reciepts)?$DateH:''?>" type="date" readonly>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">آمدنی از فروخت بھوسہ وغیرہ
                        <input type="checkbox" value="1" name="Is_Income"></label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">رقم</label>
                        <input class="form-control" type="number" value="<?= isset($Reciepts)?$Amount:''?>" name="Amount" required>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">تفصیل</label>
                        <textarea class="form-control" name="Remarks"><?= isset($Reciepts)?$Remarks:''?></textarea>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-1">
                    <?php if (isset($Reciepts)){?>
                        <input type="hidden" name="Reciept_Number" value="<?= $Reciept_Number?>">
                        <input type="hidden" name="CreatedOn" value="<?= $CreatedOn?>">
                        <input type="hidden" name="CreatedBy" value="<?= $CreatedBy?>">
                        <input type="hidden" name="Edit" value="<?= $Id?>">
                        <button type="button" class="btn btn-primary data-save Delete" data-id="<?= $Id?>" style="font-size: 10px; ">حذف کریں</button>
                        <?php }?>
                    <button type="submit"  class="btn btn-primary data-save" >محفوظ کریں</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">

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
                url: '<?= site_url('Qurbani/CashReciept/Delete/')?>'+ id,
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
                        window.location = "<?= site_url('Qurbani/CashReciept');?>";
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>