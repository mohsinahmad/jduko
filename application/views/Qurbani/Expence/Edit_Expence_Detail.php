<form action="<?= site_url('Qurbani/ExpenceDetail/UpdateExpenseDetail/')?><?= $this->uri->segment(4);?>" method="POST">
    <div class="row">
        <?php if($this->session->flashdata('success')) :?>
            <div class="alert alert-success alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('success');?>
            </div>
        <?php endif;
        if($this->session->flashdata('error')) :?>
            <div class="alert alert-danger alert-dismissable" id="dalert">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <?= $this->session->flashdata('error');?>
            </div>
        <?php endif;?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;"> مصارف کی تفصیل - <?= $Expence_Detail[0]->Voucher_Number?></h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">بنام </label>
                        <input type="text" class="form-control" name="Receiver_Name" value="<?= isset($Expence_Detail)?$Expence_Detail[0]->Receiver_Name:'';?>" autofocus required>
                        <input type="hidden" class="form-control" name="Voucher_Number" value="<?= isset($Expence_Detail)?$Expence_Detail[0]->Voucher_Number:'';?>">
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group" style="width:250px;">
                        <label>شمسی تاریخ</label>
                        <div class="form-group">
                            <input class="form-control englishDate" type="date" id="datepicker" value="<?= date('Y-m-d'); ?>" name="DateG" required>
                        </div>
                    </div>
                </div>
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label" for="inputSuccess">قمری تاریخ</label>
                        <input class="form-control islamicDate" id="EislamicDate" name="DateH" style="width: 250px;"  type="date" readonly required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                        <label class="control-label">تفصیل</label>
                        <textarea class="form-control" rows="3" name="description"><?= isset($Expence_Detail)?$Expence_Detail[0]->Master_Desc:'';?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">محفوظ کریں</button>
        <button type="button" class="btn btn-primary Delete">حذف کریں</button>
        <button type="button" class="btn btn-primary add showmodal"><i class="fa fa-plus"></i> اخراجات کی مد</button>
    </div>
    <br>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTables">
                            <thead>
                            <tr>
                                <th style="width: 22%;">اخراجات کی مد</th>
                                <th>رقم</th>
                                <th>تفصیل</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="toEdit">
                            <?php  $count = 0;?>
                            <?php if (isset($Expence_Detail)){
                                foreach ($Expence_Detail as $item) {?>
                                    <tr>
                                        <th style="width: 22%;">
                                            <input class="form-control type" type="text" value="<?= $item->type?>" readonly>
                                            <input class="form-control Type_Id" name="EditExpence_Type_Id[]" type="hidden" value="<?= $item->Type_Id?>">
                                        </th>
                                        <th><input class="form-control amount" name="EditAmount[]" type="text" value="<?= $item->Amount?>" readonly></th>
                                        <th><textarea class="form-control detail" name="EditDescription[]" readonly><?= $item->detail_desc?></textarea></th>
                                        <th><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button>
                                            <button type="button" class="btn btn-info btn-circle edit ex_detail" id="toEdit<?= ++$count;?>"><i class="fa fa-plus"></i></button>
                                        </th>
                                    </tr>
                                <?php } }?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<br>
<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" onload="myOnload()">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">اخراجات کی مد</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">اخراجات کی مد </label>
                                            <select class="form-control ex_type" name="expence_type_id" id="etype" style="padding-top: 0px;padding-bottom: 0px;">
                                                <option value="0" selected disabled> منتخب کریں</option>
                                                <?php foreach ($Expence_Type as $type) {?>
                                                    <option value="<?= $type->id?>"><?= $type->type?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">رقم</label>
                                            <input class="form-control amount"  name="amount" style="width: 250px;"  type="number" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">تفصیل</label>
                                        <textarea class="form-control desc" rows="3" name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary expence_add">محفوظ کریں</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="EditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" onload="myOnload()">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">اخراجات کی مد</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">اخراجات کی مد </label>
                                            <select class="form-control Editex_type" name="" style="padding-top: 0px;padding-bottom: 0px;">
                                                <option value="0" selected disabled> منتخب کریں</option>
                                                <?php foreach ($Expence_Type as $type) {?>
                                                    <option value="<?= $type->id?>"><?= $type->type?></option>
                                                <?php }?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label">رقم</label>
                                            <input class="form-control Editamount"  name="" style="width: 250px;"  type="number" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label">تفصیل</label>
                                        <textarea class="form-control Editdesc" rows="3" name=""></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary editexpence">محفوظ کریں</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
    var count = 1;
    $(document).on('ready',function() {
//        $('.add').on('click',function(){
//            $('table').find('.madat_Lists').first().clone().appendTo('table');
//        })

        $('.add').on('click',function(){
            var select = $('.js-example-basic-single');
            var tr = $('.madat_Lists1');
            //select.select2("destroy");
            var clone = tr.clone(true).find('input[type=text]').val("").end();
            select.select2({
                placeholder: "منتخب کریں",
                dir: "rtl"
            });
//            clone.insertAfter('tr.madat_Lists:last').find('.js-example-basic-single').select2({
//                placeholder: "منتخب کریں",
//                dir: "rtl"
//            });
            clone.insertAfter('tr.madat_Lists'+count+':last').find('.inputTextarea').val('');
            count++;
        });
    });

    $(document).on('ready',function() {
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

    $('.showmodal').on('click',function(){
        $('#gridSystemModal').modal('show');
    });

    $('.edit').on('click',function(){
        $('#EditModal').modal('show');
    });

    $('.expence_add').on('click',function(){
        var post = new Object();
        var id  = $('.ex_type').val();
        post.type = $('.ex_type').val();
        post.amount = $('.amount').val();
        post.dec = $('.desc').val();
        post.name = '';
        $.ajax({
            type:'POST',
            dataType: 'JSON',
            url: '<?php echo site_url('Qurbani/ExpenceDetail/GetExpenceName')?>/'+id,
            success:function(response){
                post.name = response._name;
            }
        }).done(function(){
            if (post.amount != '') {
                $('tbody').append('<tr class="AddNew"><td><input type="hidden" class="form-control typeid" style="" name="Expence_Type_Id[]" value="'+post.type+'" readonly><input type="text" class="form-control type_name" style="" name="" value="'+post.name+'" readonly></td><td><input type="number" class="form-control" style="" name="Amount[]" value="'+post.amount+'" readonly></td><td><textarea class="form-control" rows="1" name="Description[]" readonly>'+post.dec+'</textarea></td><td><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
                $('#gridSystemModal').modal('toggle');
            }else{ alert('رقم موجد نہیں ہے'); }
        });
    });
    $( "#dataTables").on( "click", ".del", function(e) {
        lengthClass = $('.ex_detail').length;
        e.preventDefault();
        if (lengthClass == 1) {
            new PNotify({
                title: "ہوشیار",
                text: " اس فیلڈ کو ہٹا نہیں سکتا!",
                type: 'error',
                hide: true
            });
        }else{
            $(this).parents( "tr" ).remove();
        }
    });

    var idToEdit='';
    $( "#dataTables" ).on( "click", ".edit", function(e) {
        $('.editexpence').attr('disabled',false);
        idToEdit = $(this).attr('id');
        var type_id =  $(this).parents('tr').find('.Type_Id').val();
        var amount = $(this).parents('tr').find('.amount').val();
        var detail = $(this).parents('tr').find('.detail').val();
        $('.Editex_type').val(type_id).trigger('change');
        $('.Editamount').val(amount);
        $('.Editdesc').val(detail);

    });

    $('.editexpence').on('click',function(){
        var newDetails = $('.Editdesc').val();
        var newtype = $('.Editex_type').val();
        var newamount = $('.Editamount').val();
        var post = new Object();
        post.name = '';
        $.ajax({
            type:'POST',
            dataType: 'JSON',
            url: '<?php echo site_url('Qurbani/ExpenceDetail/GetExpenceName')?>/'+newtype,
            success:function(response){
                post.name = response._name;
                $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.Type_Id').val(newtype);
                $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.type').val(post.name);
                $('#etype').val(post.name).attr("selected");
            }
        });
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.detail').text(newDetails);
        $('tbody.toEdit').find('#'+idToEdit).parents('tr').find('.amount').val(newamount);
        $('.editexpence').attr('disabled',true);

        $('#EditModal').modal('toggle');

    });
    $('.Delete').on('click',function(){
        var id = <?= $this->uri->segment(4);?>;
        (new PNotify({
                title: 'تصدیق درکار',
                text: 'کیا آپ اس مصارف کی تفصیل کو حذف کرنا چاہتے ہیں؟',
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
                url: '<?= site_url('Qurbani/ExpenceDetail/Delete_voucher/')?>'+ id,
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
                        window.location = "<?= site_url('Qurbani/ExpenceDetail');?>";
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });

</script>