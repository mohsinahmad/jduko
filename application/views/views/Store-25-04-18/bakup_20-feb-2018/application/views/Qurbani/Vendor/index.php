<form action="<?= site_url('Qurbani/Vendor/Save')?>" method="POST">
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
        <?php endif; $vendor_name = $vendor_Code = $id = $Package_Type = $Package_Amount = $Indivisual_Package_Type = $Indivisual_Package_Amount =0;
        isset($edit)?$vendor_name=$vendors[0]->Name:$vendor_name='';
        isset($edit)?$vendor_Code=$vendors[0]->Code:$vendor_Code='';
        isset($edit)?$id=$vendors[0]->Id:$id='';
        isset($edit)?$CreatedOn=$vendors[0]->CreatedOn:$CreatedOn='';
        isset($edit)?$CreatedBy=$vendors[0]->CreatedBy:$CreatedBy='';
        ?>
        <div class="heading">
            <br>
            <h1 class="page-header" style="margin-top: 10px;">وینڈر </h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="form-group">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label class="control-label" for="inputSuccess">وینڈر کا نام</label>
                            <input class="form-control" id="" name="Name" style="width: 250px;" value="<?= $vendor_name?>" type="text" autofocus required>
                            <input class="form-control" id="" name="Code" style="width: 250px;" value="<?= $vendor_Code?>" type="hidden">
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($edit)){?>
                <input type="hidden" name="edit" value="<?= $id?>">
                <input type="hidden" name="CreatedBy" value="<?= $CreatedBy?>">
                <input type="hidden" name="CreatedOn" value="<?= $CreatedOn?>">
            <?php }?>
            <button type="submit" class="btn btn-primary ">محفوظ کریں</button>
        </div>
    </div>
</form>
<br><br><br>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>وینڈرز</h1>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <label style="float: left; margin-left: 3%;">تلاش کریں
                        <input type="text" id="myInputTextField" style="float: left; height: 6%;"></label>
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th style="text-align: center">کوڈ</th>
                            <th style="text-align: center">نام</th>
                            <th style="text-align: center">حذف/تدوین</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($vendors as $value):?>
                            <tr class="odd gradeX">
                                <td><?= $value->Code?></td>
                                <td><?= $value->Name?></td>
                                <td style="width: 18%;">
                                    <button type="button" class="btn btn-danger Delete" data-id="<?= $value->Id?>" style="font-size: 10px; ">حذف کریں
                                    </button>
                                    <a href="<?= site_url('Qurbani/Vendor/index/').$value->Id?>"><button type="button" class="btn btn-success" style="font-size: 10px;background-color: #517751;border-color: #517751; ">تصیح کریں
                                        </button>
                                    </a>
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
<script src="<?= base_url()."assets/js/"; ?>jquery-1.12.4.js"></script>
<script type="text/javascript">
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
                url: '<?= site_url('Qurbani/Vendor/delete/'); ?>'+ id,
                success: function (response) {
                    var data = $.parseJSON(response);
                    if (data['success']) {
                        new PNotify({
                            title: 'حذف',
                            text: "حذف کردیا گیا۔۔۔",
                            type: 'success',
                            hide: true
                        });
                    }else if(data['exist']){
                        new PNotify({
                            title: 'حذف',
                            text: "حذف ناممکن، رسید موجود ہے۔۔!!",
                            type: 'error',
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
                        location.reload();
                    }, 1000);
                }
            });
        }).on('pnotify.cancel', function () {
        });
    });
</script>