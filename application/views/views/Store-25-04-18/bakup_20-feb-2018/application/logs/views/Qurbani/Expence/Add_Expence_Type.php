<form action="<?= site_url('Qurbani/ExpenceType/Save')?>" method="POST">
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
        <?php endif ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;"> اخراجات کی قسم </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">اخراجات کی مد</label>
                            <input class="form-control" id="" name="type" style="width: 213px;" value="" type="text" autofocus required>
                        </div>
                    </div>
                    <div class="col-xs-3">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">انعام
                                <input name="IsReward" value="1" type="checkbox"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
    </div>
</form>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>اخراجات کی اقسام</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 1%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="width: 15%;">شناخت</th>
                            <th style="width: 60%;"> اقسام</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($Expence_Type as $type):?>
                            <tr class="odd gradeX">
                                <td><?= $type->id?></td>
                                <td><?= $type->type?></td>
                                <td style="width: 18%;">
                                    <button type="button" class="btn btn-danger delete_type" data-id="<?= $type->id;?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <button type="button" class="btn btn-success getid" data-toggle="modal" data-target="#gridSystemModal" data-id="<?= $type->id;?>" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="gridSystemModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gridModalLabel" aria-hidden="true" onload="myOnload()">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridModalLabel">اخراجات کی قسم</h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid bd-example-row">
                    <div class="row">
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label class="control-label" for="inputSuccess">اخراجات کی قسم </label>
                                            <input class="form-control" id="ex_type" name="ex_type" style="width: 250px;" value="" type="text" autofocus required>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="checkbox" id="checkbox">
                                            <button type="button" class="btn btn-primary expenceEdit">محفوظ کریں</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
    var id;
    $('.getid').on('click',function(){
        id=$(this).data('id');
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('Qurbani/ExpenceType/ExpenceTypeById'); ?>'+'/'+id,
            returnType: 'JSON',
            success:function (response) {
                var data = $.parseJSON(response);
                $('#ex_type').val(data.type);
            }
        });
    });

    $('.expenceEdit').on('click',function(){
        var post = new Object();
        post.type = $('#ex_type').val();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('Qurbani/ExpenceType/UpdateExpenceType'); ?>'+'/'+id,
            returnType: 'JSON',
            data: post,
            success: function (response) {
                if (response['success']) {
                    new PNotify({
                        title: 'کامیابی',
                        text: 'edit ho gya ha',
                        type: 'success',
                        hide: true
                    });
                }
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
        });
    });

    $('.delete_type').on('click',function(){
        var id=$(this).data('id');
        (new PNotify({
            title: 'تصدیق درکار',
            text: 'کیا آپ حذف کرنا چاہتے ہیں؟',
            icon: 'glyphicon glyphicon-question-sign',
            hide: false,
            type: "success",
            confirm: {
                confirm: true,
                buttons: [{text: "جی ہاں", addClass: "", promptTrigger: true, click: function(notice, value){ notice.remove(); notice.get().trigger("pnotify.confirm", [notice, value]); }},{text: "نیہں", addClass: "", click: function(notice){ notice.remove(); notice.get().trigger("pnotify.cancel", notice); }}]
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
        })).get().on('pnotify.confirm', function(){

            $.ajax({
                url:'<?php echo site_url('Qurbani/ExpenceType/DeleteExpenceType'); ?>'+'/'+id,
                returnType:'JSON',
                success:function(response){
                    var data = $.parseJSON(response);
                    if(data['success']){
                        new PNotify({
                            title: 'حذف',
                            text: 'قسم حذف کر دیا گیا ہے',
                            type: 'success',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },0.5);
                    }
                    if(data['error']){
                        new PNotify({
                            title: 'حذف',
                            text: 'عطیہ حذف نیہں ہو سکتا ہے',
                            type: 'error',
                            hide: true
                        });
                        setTimeout(function(){
                            location.reload();
                        },1000);
                    }
                }
            });
        }).on('pnotify.cancel', function(){
        });
    });
</script>