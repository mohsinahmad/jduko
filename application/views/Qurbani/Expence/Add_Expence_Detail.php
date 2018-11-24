<form action="<?= site_url('Qurbani/ExpenceDetail/Save')?>" method="POST">
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
            <h1 class="page-header" style="margin-top: 10px;"> مصارف کی تفصیل </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-4">
                    <div class="form-group">
                       <label class="control-label" for="inputSuccess">بنام </label>
                       <input type="text" class="form-control" name="Receiver_Name" value="<?= isset($Expence_Detail)?$Expence_Detail[0]->Receiver_Name:'';?>" autofocus required>
                      
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
        <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        <button type="button" class="btn btn-primary add showmodal"><i class="fa fa-plus"></i> اخراجات کی مد</button>
    </div>
    <br>
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body" style="padding: 0px;">
                <div class="container-fluid" >
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 22%;">اخراجات کی مد</th>
                                    <th>رقم</th>
                                    <th>تفصیل</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($Expence_Detail)){
                                foreach ($Expence_Detail as $item) {?>
                                    <tr>
                                        <th style="width: 22%;"><select class="form-control" style="padding-top: 0px;padding-bottom: 0px;"><?php foreach ($Expence_Type as $value) {?>
                                                    <option value="<?= $item->Type_Id?>" selected><?= $item->type?></option>
                                                    <option><?= $value->type?></option>
                                                <?php }?></select></th>
                                        <th><input class="form-control" type="text" value="<?= $item->Amount?>"></th>
                                        <th><textarea class="form-control"><?= $item->detail_desc?></textarea></th>
                                        <th><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></th>
                                    </tr>
                                <?php }
                            }else{?>
                                <tr class="madat_Lists">
                                    <th style="width: 22%;"><select class="form-control js-example-basic-single" style="padding-top: 0px;padding-bottom: 0px;"><option selected>منتخب کریں</option><?php foreach ($Expence_Type as $value) {?>
                                                <option><?= $value->type?></option>
                                            <?php }?></select></th>
                                    <th><input class="form-control" type="text" value=""></th>
                                    <th><textarea class="form-control inputTextarea"></textarea></th>
                                    <th>
                                        <button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button>
                                    </th>
                                </tr>
                            <?php }?>
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
                                            <select class="form-control ex_type" name="expence_type_id" style="padding-top: 0px;padding-bottom: 0px;">
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
                $('tbody').append('<tr class="AddNew"><td><input type="hidden" class="form-control" style="" name="Expence_Type_Id[]" value="'+post.type+'" readonly><input type="text" class="form-control" style="" name="" value="'+post.name+'" readonly></td><td><input type="number" class="form-control" style="" name="Amount[]" value="'+post.amount+'" readonly></td><td><textarea class="form-control" rows="1" name="Description[]" readonly>'+post.dec+'</textarea></td><td><button type="button" class="btn btn-info btn-circle del" ><i class="fa fa-minus"></i></button></td></tr>');
              $('#gridSystemModal').modal('toggle');
            }else{ alert('رقم موجد نہیں ہے'); }
        });
    });

    $( "#data-table" ).on( "click", ".del", function(e) {
        e.preventDefault();
        $( this ).parents( "tr" ).remove();
    });
</script>