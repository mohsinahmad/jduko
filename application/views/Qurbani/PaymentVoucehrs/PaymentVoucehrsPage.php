<form action="<?= site_url('Qurbani/ExpenceDetail/SaveExpenceVouvher')?>" method="POST">
    <div class="row">
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success');?>
            </div>
        <?php endif ?>
        <?php if($this->session->flashdata('error')) :?>
            <div class="alert alert-danger alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('error');?>
            </div>
        <?php endif;
        isset($edit[0]->VoucherNumber)?$VoucherNumber=$edit[0]->VoucherNumber:$VoucherNumber='';
        isset($edit[0]->Name)?$name=$edit[0]->Name:$name='';
        isset($edit[0]->DateH)?$DateH=$edit[0]->DateH:$DateH='';
        isset($edit[0]->DateG)?$DateG=$edit[0]->DateG:$DateG='';
        isset($edit[0]->Amount)?$Amount=$edit[0]->Amount:$Amount='';
        isset($edit[0]->Description)?$Description=$edit[0]->Description:$Description='';
        isset($edit[0]->CreatedBy)?$CreatedBy=$edit[0]->CreatedBy:$CreatedBy='';
        isset($edit[0]->CreatedOn)?$CreatedOn=$edit[0]->CreatedOn:$CreatedOn=''; ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">ادائیگی واؤچر<?= $VoucherNumber?></h1>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">ادائیگی بنام </label>
                        <input class="form-control" id="Name" type="text" name="Name" style="width: 250px;" value="<?= $name?>">
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>حلقے</label>
                        <div class="form-group">
                            <select name="Halqy_Id" id="Halqy_Id">
                                <option value="0" selected>منتخب کریں</option>
                                <?php foreach ($hulqy as $item) {?>
                                    <option value="<?= $item->id?>"><?= $item->supervisor_name.' - '.$item->hulqa_name?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <?php if (isset($edit)){?>
                                <input class="form-control englishDate" type="date" id="datepicker" name="DateG" value="<?= $DateG?>" required>
                            <?php }else{?>
                                <input class="form-control englishDate" type="date" id="datepicker" name="DateG" value="<?= date('Y-m-d'); ?>" required>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <?php if (isset($edit)){?>
                            <input class="form-control islamicDate" id="EislamicDate" name="DateH" style="width: 250px;"  type="date" value="<?= $DateG?>" readonly required>
                        <?php }else{?>
                            <input class="form-control islamicDate" id="EislamicDate" name="DateH" style="width: 250px;"  type="date" readonly required>
                        <?php }?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group" id="Advance">
                            <label class="control-label" for="inputSuccess">پیشگی رقم
                            <input type="checkbox" name="Is_Advance" value="1"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">رقم</label>
                            <input class="form-control" id="" type="text" name="Amount" value="<?= $Amount?>" style="width: 250px;" required>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">تفصیل</label>
                            <textarea class="form-control" rows="3" cols="50" type="text" name="Description" style=" font-family: 'Noto Nastaliq Urdu', serif;width: 89%;"><?= $Description?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" id="submit" class="btn btn-primary" >محفوظ کریں</button>
        <?php if (isset($edit)){?>
            <input type="hidden" value="<?= $edit[0]->Id?>" id="Edit" name="edit">
            <button type="button"  class="btn btn-primary delete_slip">حذف کریں</button>
        <?php }?>
    </div>
</form>
<script src="<?= base_url()."assets/"?>js/jquery-1.12.4.js"></script>
<script>
    $(document).on('ready',function() {
        $('#Advance').attr("style",'display:none');
        $("#Name").blur(function(){
            if ($(this).val() != ''){
                $('#Halqy_Id').prop("disabled", true);
                $('#Advance').attr("style",'display:none');
            }else {
                $('#Halqy_Id').removeAttr("disabled");
                $('#Advance').removeAttr("style");
            }
        });

        $("#Halqy_Id").blur(function(){
            if ($("#Halqy_Id").val() != 0){
                $('#Name').prop("disabled", true);
                $('#Advance').removeAttr("style");
            }else {
                $('#Name').removeAttr("disabled");
                $('#Advance').attr("style",'display:none');
            }
        });

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

    $('.delete_slip').on('click',function(){
        var Voucher_id = $('#Edit').val();
        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
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
                url: '<?= site_url('Qurbani/ExpenceDetail/DeleteExpenceVoucher/')?>'+Voucher_id,
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
                        window.location.href = "<?= site_url('Qurbani/ExpenceDetail/ExpenceVoucherList') ?>";
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>